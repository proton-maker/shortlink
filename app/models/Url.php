<?php 
/**
 * ====================================================================================
 *                           GemFramework (c) gempixel
 * ----------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework owned by gempixel Inc as such
 *  distribution or modification of this framework is not allowed before prior consent
 *  from gempixel administrators. If you find that this framework is packaged in a 
 *  software not distributed by gempixel or authorized parties, you must not use this
 *  software and contact gempixel at https://piliruma.co.id/contact to inform them of this
 *  misuse otherwise you risk of being prosecuted in courts.
 * ====================================================================================
 *
 * @package GemFramework
 * @author gempixel (http://gempixel.com) 
 * @license http://gempixel.com/license
 * @link http://gempixel.com  
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
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function recent(){
        return parent::where('archived', 0)->whereNull('qrid')->whereNull('profileid')->whereRaw('(expiry IS NULL OR expiry > DATE(CURDATE()))');
    }
    /**
     * Archived Links
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function archived(){
        return parent::where('archived', 1)->whereNull('qrid')->whereNull('profileid')->whereRaw('(expiry IS NULL OR expiry > DATE(CURDATE()))');
    } 

    /**
     * Expired
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function expired(){
        return parent::where('archived', 0)->whereNull('qrid')->whereNull('profileid')->whereRaw('expiry < DATE(CURDATE())');
    }
}