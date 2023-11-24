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

class Url extends Model {

	/**	
	 * Table Name
	 */
	public static $_table = DBprefix.'url';

    /**
     * Return only Links
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function recent(){
        return parent::where('archived', 0)->whereNull('qrid')->whereNull('profileid')->whereRaw('(expiry IS NULL OR expiry > DATE(CURDATE()))');
    }
    /**
     * Archived Links
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function archived(){
        return parent::where('archived', 1)->whereNull('qrid')->whereNull('profileid')->whereRaw('(expiry IS NULL OR expiry > DATE(CURDATE()))');
    } 

    /**
     * Expired
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function expired(){
        return parent::where('archived', 0)->whereNull('qrid')->whereNull('profileid')->whereRaw('expiry < DATE(CURDATE())');
    }
}