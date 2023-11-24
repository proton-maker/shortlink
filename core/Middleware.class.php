<?php 
/**
 * ====================================================================================
 *                           GemFramework (c) gempixel
 * ----------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework owned by gempixel Inc as such
 *  distribution or modification of this framework is not allowed before prior consent
 *  from gempixel administrators. If you find that this framework is packaged in a 
 *  software not distributed by gempixel or authorized parties, you must not use this
 *  sofware and contact gempixel at https://piliruma.co.id/contact to inform them of this
 *  misuse otherwise you risk of being prosecuted in courts.
 * ====================================================================================
 *
 * @package Core\Middleware 
 * @author gempixel (http://gempixel.com)
 * @copyright 2020 gempixel
 * @license http://gempixel.com/license
 * @link http://gempixel.com  
 * @since 1.0
 */
namespace Core;

use Core\Helper;
use Core\Request;

class Middleware {	

	protected $_exempt = [];

	/**
	 * Check if route is allowed
	 * @author gempixel <https://piliruma.co.id>
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