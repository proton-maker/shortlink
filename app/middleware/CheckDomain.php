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
use Core\Helper;
use Core\View;

final class CheckDomain extends Middleware {    
	/**
	 * Check pointed domain
	 * @author gempixel <https://piliruma.co.id>
	 * @version 1.0
	 */
	public function handle(Request $request) {

        $currenturi = trim(str_replace($request->path(), '', $request->uri(false)), '/');    

        if(!in_array(config('url'), [$currenturi, str_replace('www.', '', $currenturi)])){
            
            $host = \idn_to_utf8(Helper::parseUrl($request->host(), 'host'));

            if($domain = \Core\DB::domains()->whereRaw("domain = ? OR domain = ?", ["http://".$host,"https://".$host])->first()){
                if($domain->redirect){
                    header("Location: {$domain->redirect}");
                    exit;
                }
            }
            
            $domains_names = explode("\n", config('domain_names'));
            $domains_names = array_map('trim', $domains_names);
            
            if(in_array($currenturi, $domains_names)){
                header("Location: ".config('url'));
                exit;
            }

            View::set('title', e('Great! Your domain is working.'));
            View::with('gates.domain')->extend('layouts.auth'); 
            exit;
        }

        return true;
	}
} 