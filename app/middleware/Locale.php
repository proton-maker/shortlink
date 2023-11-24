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

use Core\Helper;
use Core\Request;
use Core\Localization;
use Core\Response;

final class Locale {
	/**
	 * Handle Locales
	 * @author Xsantana
	 * @version 1.0
	 * @return  [type] [description]
	 */
	public function handle() { }
	/**
	 * Change Admin Locale
	 *
	 * @author Xsantana 
	 * @version 1.0
	 * @return void
	 */
	public function admin(){		
        Localization::setFile('admin');
        Localization::update();
	}	
}