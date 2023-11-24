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

use Core\Middleware;
use Core\Request;
use Core\Helper;
use Jaybizzle\CrawlerDetect\CrawlerDetect;


final class BlockBot extends Middleware {    
	/**
	 * Check Maintenance mode
	 * @author Xsantana
	 * @version 1.0
	 */
	public function handle(Request $request) {

        $CrawlerDetect = new CrawlerDetect;

        if($CrawlerDetect->isCrawler()) {
            die();
        }

        return true;        
	}
}        