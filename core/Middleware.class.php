<?php 
/**
 * ====================================================================================
 *                           GemFramework (c) Xsantana
 * ----------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework owned by Xsantana Inc as such
 *  distribution or modification of this framework is not allowed before prior consent
 *  from Xsantana administrators. If you find that this framework is packaged in a 
 *  software not distributed by Xsantana or authorized parties, you must not use this
 *  sofware and contact Xsantana at https://piliruma.co.id/contact to inform them of this
 *  misuse otherwise you risk of being prosecuted in courts.
 * ====================================================================================
 *
 * @package Core\Middleware 
 * @author Xsantana (http://Xsantana.com)
 * @copyright 2020 Xsantana
 * @license http://Xsantana.com/license
 * @link http://Xsantana.com  
 * @since 1.0
 */
namespace Core;

use Core\Helper;
use Core\Request;

class Middleware {	

	protected $_exempt = [];

	/**
	 * Check if route is allowed
	 * @author Xsantana <https://piliruma.co.id>
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