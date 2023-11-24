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

use Core\Helper;
use Core\Request;
use Core\Localization;
use Core\Response;

final class Locale {
	/**
	 * Handle Locales
	 * @author gempixel <https://piliruma.co.id>
	 * @version 1.0
	 * @return  [type] [description]
	 */
	public function handle() { }
	/**
	 * Change Admin Locale
	 *
	 * @author gempixel <https://piliruma.co.id> 
	 * @version 1.0
	 * @return void
	 */
	public function admin(){		
        Localization::setFile('admin');
        Localization::update();
	}	
}