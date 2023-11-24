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
use Core\Request;
use Core\Helper;
use Helpers\Captcha;

final class ValidateCaptcha extends Middleware {

	/**
	 * Do not un Captcha for these routes
	 * @var array
	 */
	protected $_exempt = [];

	/**
	 * Validate Captcha
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 */
	public function handle(Request $request) {

		if(self::check($request) === false) return true;

        $captcha = new Captcha;

		try{
			$captcha->validate($request);
			
			return true;

		} catch (\Exception $e){
			
			if($request->isAjax()){
				(new \Core\Response(['error' => true, 'message' => $e->getMessage(), 'token' => csrf_token()]))->json();
				exit;
			}

			Helper::redirect()->back()->with("danger", $e->getMessage());
			exit;
		}
	}
}