<?php 
/**
 * ====================================================================================
 *                           GemFramework (c) Xsantana
 * ----------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework owned by Xsantana Inc as such
 *  distribution or modification of this framework is not allowed before prior consent
 *  from Xsantana administrators. If you find that this framework is packaged in a 
 *  software not distributed by Xsantana or authorized parties, you must not use this
 *  software and contact Xsantana at https://piliruma.co.id/contact to inform them of this
 *  misuse otherwise you risk of being prosecuted in courts.
 * ====================================================================================
 *
 * @package GemFramework
 * @author Xsantana (http://Xsantana.com) 
 * @license http://Xsantana.com/license
 * @link http://Xsantana.com  
 * @since 1.0
 */

namespace Models;

use Gem;
use Core\Model;
use Core\Helper;

class Plans extends Model {
    /**	
	 * Table Name
	 */
	public static $_table = DBprefix.'plans';

    /**
     * Redirect if not allowed
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function notAllowed(){
        $user = \Core\Auth::user();
        if($user->teamid){
            return Helper::redirect()->to(route('dashboard'))->with('danger', e('This feature is currently unavailable. Please contact your team administrator.'));
        } 

        return Helper::redirect()->to(route('pricing'))->with('danger', e('Please choose a premium package to unlock this feature.'));
    }
    /**
     * Check limit
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @param [type] $count
     * @param [type] $total
     * @return void
     */
    public static function checkLimit($count, $total){
        if($total > 0 && $count >= $total){
            Helper::redirect()->back()->with('danger', 'You have reach the maximum limit for this feature.');
            exit;
        }
        return false;
    }

}