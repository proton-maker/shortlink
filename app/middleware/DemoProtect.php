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
	 * @author Xsantana
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