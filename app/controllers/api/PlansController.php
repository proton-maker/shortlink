<?php
/**
 * =======================================================================================
 *                           GemFramework (c) gempixel                                     
 * ---------------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework as such distribution
 *  or modification of this framework is not allowed before prior consent from
 *  gempixel. If you find that this framework is packaged in a software not distributed 
 *  by gempixel or authorized parties, you must not use this software and contact gempixel
 *  at https://piliruma.co.id/contact to inform them of this misuse.
 * =======================================================================================
 *
 * @package gempixel\Premium-URL-Shortener
 * @author Xsantana
 * @license https://piliruma.co.id/licenses
 * @link https://piliruma.co.id  
 */

namespace API;

use \Core\Helper;
use \Core\Request;
use \Core\Response;
use \Core\DB;
use \Core\Auth;
use \Models\User;

class Plans {
    /**
     * Check if is admin
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     */
    public function __construct(){

        if(!Auth::ApiUser()->admin){
            die(Response::factory(['error' => 1, 'message' => 'You do not have permission to access this endpoint.'], 403)->json());
        }        
    }
    /**
     * List all plans
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @return void
     */
    public function get(Request $request){

        $plans = [];
    
        foreach(DB::plans()->findMany() as $i => $plan){
            $permissions = json_decode($plan->permission, true);

            $plans[$i] = [
                "id" => $plan->id,
                "name" => $plan->name,
                "free" => $plan->free ? true : false,
                "prices" => [
                    'monthly' => $plan->price_monthly,
                    'yearly' => $plan->price_yearly,
                    'lifetime' => $plan->price_lifetime
                ],
                'limits' => [
                    'links' => $plan->numurls,
                    'clicks' => $plan->numclicks,
                    'retention' => $plan->retention,
                ]
            ];

            $plans[$i]['limits'] += $permissions;
        }

        return Response::factory(['error' => 0, 'data' => $plans])->json();

    }
    /**
     * Subscribe user
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @return void
     */
    public function subscribe(Request $request, int $planid, int $userid){

        $data = $request->getJSON();

        if(!isset($data->type) || !in_array($data->type, ['monthly', 'yearly', 'lifetime'])) return Response::factory(['error' => 1, 'message' => 'API request is missing the type parameter.'])->json(); 

        if(!$planid || !$plan = DB::plans()->first(clean($planid))){
            return Response::factory(['error' => 1, 'message' => 'Plan does not exist.'])->json(); 
        }

        if(!$userid || !$user = DB::user()->first(clean($userid))){
            return Response::factory(['error' => 1, 'message' => 'User does not exist.'])->json(); 
        }

        $user->planid = $plan->id;
        $user->pro = $plan->free ? 0 : 1;
        $user->last_payment = Helper::dtime();

        if(isset($data->expiration)&& !empty($data->expiration) && strtotime($data->expiration)){
            $user->expiration = Helper::dtime($data->expiration);
        } else {
            if($data->type == 'lifetime'){
                $user->expiration = Helper::dtime('+10 years');
            } elseif($data->type == 'yearly'){
                $user->expiration = Helper::dtime('+1 year');
            } else {
                $user->expiration = Helper::dtime('+1 month');
            }
        }

        $user->save();
        
        return Response::factory(['error' => 0, 'message' => 'User has been successfully subscribed.'])->json();

    }    
}