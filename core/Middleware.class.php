<?php 
/**
 * ====================================================================================
 *                           GemFramework (c) gempixel.com
 * ----------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework owned by gempixel.com Inc as such
 *  distribution or modification of this framework is not allowed before prior consent
 *  from gempixel.com administrators. If you find that this framework is packaged in a 
 *  software not distributed by gempixel.com or authorized parties, you must not use this
 *  sofware and contact gempixel.com at https://gempixel.com/contact to inform them of this
 *  misuse otherwise you risk of being prosecuted in courts.
 * ====================================================================================
 *
 * @package Core\Middleware 
 * @author gempixel.com (http://gempixel.com.com)
 * @copyright 2020 gempixel.com
 * @license http://gempixel.com.com/license
 * @link http://gempixel.com.com  
 * @since 1.0
 */
namespace Core;

use Core\Helper;
use Core\Request;

class Middleware {	

	protected $_exempt = [];

	/**
	 * Check if route is allowed
	 * @author Xsantana
	 * @version 1.0
	 * @return  [type] [description]
	 */
	protected function check(Request $request){
		foreach ($this->_exempt as $ignore) {
			if(strpos($request->path(), str_replace("*", "", $ignore)) !== false) return false;
		}

		return true;
	}
}