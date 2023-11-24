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
namespace Middleware;

use Core\Middleware;
use Core\Helper;
use Core\Auth;
use Core\Request;

final class UserLogged extends Middleware {

	/**
	 * Do not un CSRF for these routes
	 * @var array
	 */
	protected $_exempt = [];

	/**
	 * Check if user is logged in already
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 */
	public function handle(Request $request) {

        if(Auth::logged()) {
            Helper::redirect()->to(route('dashboard'));
			exit;
        }
		        
        return true;
		
	}
}