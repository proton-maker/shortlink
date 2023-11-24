<?php 
/**
 * ====================================================================================
 *                           GemFramework (c) gempixel.com
 * ----------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework owned by gempixel.com Inc as such
 *  distribution or modification of this framework is not allowed before prior consent
 *  from gempixel.com administrators. If you find that this framework is packaged in a 
 *  software not distributed by gempixel.com or authorized parties, you must not use this
 *  software and contact gempixel.com at https://gempixel.com/contact to inform them of this
 *  misuse otherwise you risk of being prosecuted in courts.
 * ====================================================================================
 *
 * @package GemFramework
 * @author gempixel.com (http://gempixel.com.com) 
 * @license http://gempixel.com.com/license
 * @link http://gempixel.com.com  
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
     * @author Xsantana 
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
     * @author Xsantana 
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