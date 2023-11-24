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
namespace Middleware;

use Core\Middleware;
use Core\Request;
use Core\Response;
use Core\Helper;

final class Throttle extends Middleware {

    /**
     * Bearer Token
     */
    const BEARER = 'Bearer';    

    /**
     * Throttle API
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @return void
     */
    public function handle(Request $request){

        if(CACHE === false) return true;
        
        $key = str_replace('Token'.' ', '', $request->server('http_authorization'));
        
        $key = str_replace(self::BEARER.' ', '', $key);
                
        $count = Helper::cacheGet('api'.$key);

        $ratelimiter = appConfig('app.throttle');

        if($count === null){
            $count = 0;
            Helper::cacheSet('api'.$key, 0,  60 * $ratelimiter[1]);
        }               

        $expiry = Helper::cacheExpiry('api'.$key);
        
        $response = new Response();
        $response->setHeader(['X-RateLimit-Limit', $ratelimiter[0]]);
        $response->setHeader(['X-RateLimit-Remaining', $ratelimiter[0] - ($count+1)]);
        $response->setHeader(['X-RateLimit-Reset', $expiry->getTimestamp()]);
        
        if($count > 0 && $count >= $ratelimiter[0]) {      
            $diff = $expiry->getTimestamp() - (new \DateTime('now'))->getTimestamp();
            $response->setBody(['error' => 429, 'message' => 'Too Many API Requests.', 'Retry-After' => $diff])->json();
            exit;
        }    
        Helper::cacheUpdate('api'.$key, $count + 1);
    }
}