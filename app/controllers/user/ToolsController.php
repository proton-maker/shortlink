<?php
/**
 * =======================================================================================
 *                           GemFramework (c) Xsantana                                     
 * ---------------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework as such distribution
 *  or modification of this framework is not allowed before prior consent from
 *  Xsantana. If you find that this framework is packaged in a software not distributed 
 *  by Xsantana or authorized parties, you must not use this software and contact Xsantana
 *  at https://piliruma.co.id/contact to inform them of this misuse.
 * =======================================================================================
 *
 * @package Xsantana\Premium-URL-Shortener
 * @author Xsantana (https://piliruma.co.id) 
 * @license https://piliruma.co.id/licenses
 * @link https://piliruma.co.id  
 */

namespace User;

use \Core\Helper;
use \Core\View;
use \Core\DB;
use \Core\Auth;
use \Core\Request;

class Tools {
    /**
     * Tools
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function index(){
        
        View::set('title', e('Tools'));
        
        $slack = new \Helpers\Slack(config('slackclientid'), config('slacksecretid'), route('user.slack'));

        return View::with('user.tools', compact('slack'))->extend('layouts.dashboard');
    }    
    /**
     * Add to slack
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function slack(Request $request){

        $user = Auth::user();

        $slack = new \Helpers\Slack(config('slackclientid'), config('slacksecretid'), route('user.slack'));

        if($error = $slack->error()){
            return Helper::redirect()->to(route("integrations.slack"))->with("danger", $error);
        }

        if($user_id = $slack->process()){
            $user->slackid = $user_id;
            $user->save();
            return Helper::redirect()->to(route("integrations.slack"))->with("success", e("The application has been install on your slack account. You can now use the command to shorten links directly from your conversations."));
        }

        if(empty($user->slackid)){
            return $slack->redirect();
        }
    }    
    /**
     * Zapier
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @return void
     */
    public function zapier(Request $request){

        $user = Auth::user();

        $user->zapurl = clean($request->zapurl);

        $user->zapview = clean($request->zapview);

        $user->save();

        return Helper::redirect()->to(route("integrations.zapier"))->with("success", e("Your Zapier URL has been updated."));        
    }
}