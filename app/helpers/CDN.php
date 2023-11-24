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

namespace Helpers;

use Core\DB;
use Core\View;

final class CDN {
    /**
     * JS
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     */
    public static $list = [];
    /**
     * Load CDN Assets
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param string $name
     * @return void
     */
    public static function load(string $name){
        
        if(empty(self::$list)) self::$list = appConfig('cdn');

        if(isset(self::$list[$name])){          
            if(isset(self::$list[$name]['js'])){
                foreach(self::$list[$name]['js'] as $js){
                    $js = str_replace('[version]', self::$list[$name]['version'], $js);
                    View::push($js, 'js')->toFooter();
                }
            }
            if(isset(self::$list[$name]['css'])){
                foreach(self::$list[$name]['css'] as $css){
                    $css = str_replace('[version]', self::$list[$name]['version'], $css);
                    View::push($css, 'css')->toHeader();
                }
            }
        }
    }
}