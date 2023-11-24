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

final class DemoProtect extends Middleware {
    /*
	 * Error Message
	 * @var string
	 */
	protected $message = "This feature is disabled in demo.";

	/**
	 * Validate CSRF Token
	 * @author gempixel <https://piliruma.co.id>
	 * @version 1.0
	 */
	public function handle(Request $request) {

        if(_STATE == 'DEMO') {
            
            if($request->isAjax()){
                (new \Core\Response(['error' => true, 'message' => e($this->message)]))->json();
                exit;
            }

            Helper::redirect()->back()->with('danger',  e($this->message));
            exit;
        }
        return true;
	}
}