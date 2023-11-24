<?php
/**
 * =======================================================================================
 *                           GemFramework (c) gempixel.com                                     
 * ---------------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework as such distribution
 *  or modification of this framework is not allowed before prior consent from
 *  gempixel.com. If you find that this framework is packaged in a software not distributed 
 *  by gempixel.com or authorized parties, you must not use this software and contact gempixel.com
 *  at https://gempixel.com/contact to inform them of this misuse.
 * =======================================================================================
 *
 * @package gempixel.com\Premium-URL-Shortener
 * @author Xsantana 
 * @license https://gempixel.com/licenses
 * @link https://gempixel.com  
 */

use Core\View;
use Core\File;
use Core\Helper;
use Core\Request;
use Core\Response;
use Core\Localization;
use Core\DB;

class Home {

    use \Traits\Links;
    /**
     * Home Page
     *
     * @author Xsantana 
     * @version 6.0
     * @return void
     */
    public function index(Request $request){
 
        $request->ref ? $request->cookie('urid', clean($request->ref), 60 * 24 * 30) : '';

        if(config('home_redir')){
            return Helper::redirect()->to(config('home_redir'));
        }
        
        $count = new \stdClass;
        if(config("homepage_stats")){
            $count->users = \Core\DB::user()->count();
            $count->links = \Core\DB::url()->count();
            $count->clicks = \Core\DB::url()->selectExpr('SUM(click) as click')->first()->click;
        }

        View::push(assets('frontend/libs/clipboard/dist/clipboard.min.js'), 'js')->toFooter();

        $themeconfig = config('theme_config');        
            
        return View::with('index', compact('count', 'themeconfig'))->extend('layouts.main');
    }
}