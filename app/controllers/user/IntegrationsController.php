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

namespace User;

use Core\Helper;
use Core\View;
use Core\DB;
use Core\Auth;
use Core\Request;

class Integrations {

    /**
     * INtegrations
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.3
     * @return void
     */
    public function index(Request $request, string $provider){

		if($class = $this->integrations($provider)){
            if(isset($class[0]) && \method_exists($class[0], $class[1])) return call_user_func($class, $request);
		}

        return stop(404);
    }
    /**
     * Integrations
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.3
     * @param [type] $name
     * @return void
     */
    public function integrations($name = null){

        $list = [			 
			'slack' => [self::class, 'slack'],
            'zapier' => [self::class, 'zapier'],
            'wordpress' => [self::class, 'wordpress'],
            'plugin' => [self::class, 'plugin'],
            'shortcuts' => [self::class, 'shortcuts']
		];

		if($extended = \Core\Plugin::dispatch('integrations.extend')){
			foreach($extended as $fn){
				$list = array_merge($list, $fn);
			}
		}

		if(isset($list[$name])) return $list[$name];

		return $list;
    }
    /**
     * Slack Integration
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.3
     * @return void
     */
    public static function slack(){

        if(!config('slackclientid') || !config('slacksecretid')){
            stop(404);
        }

        View::set('title', e('Slack Integration'));
        
        $slack = new \Helpers\Slack(config('slackclientid'), config('slacksecretid'), route('user.slack'));

        return View::with('integrations.slack', compact('slack'))->extend('layouts.dashboard');
    }    
    /**
     * Zapier Integration
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.3
     * @return void
     */
    public static function zapier(){

        View::set('title', e('Zapier Integration'));
    
        return View::with('integrations.zapier')->extend('layouts.dashboard');
    }    
    /**
     * WordPress Integration
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.3
     * @return void
     */
    public static function wordpress(){

        if(!config('api') || !user()->has('api')) return \Models\Plans::notAllowed();

        View::set('title', e('WordPress Integration'));

        \Helpers\CDN::load('hljs');
        View::push('<script>hljs.highlightAll();</script>','custom')->tofooter();
    
        return View::with('integrations.wordpress')->extend('layouts.dashboard');
    } 
    /**
     * Shortcuts Integration
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.3
     * @return void
     */
    public static function shortcuts(){
        
        if(!config('api') || !user()->has('api')) return \Models\Plans::notAllowed();

        View::set('title', e('Shortcuts Integration'));
    
        return View::with('integrations.shortcuts')->extend('layouts.dashboard');
    }
    /**
     * WP Plugin
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.3
     * @return void
     */
    public function plugin(){
        
        if(!config('api') || !user()->has('api')) return \Models\Plans::notAllowed();

        $plugin = file_get_contents(STORAGE."/app/wpplugin.php");

		$plugin = str_replace("__URL__", config('url'), $plugin);
		$plugin = str_replace("__AUTHOR__", config('title'), $plugin);
		$plugin = str_replace("__API__", route('api.url.create'), $plugin);
		$plugin = str_replace("__KEY__", user()->api, $plugin);


        $zip = new \ZipArchive();
        
        $tmpname = Helper::rand(12).".zip";

        if(!$zip->open(STORAGE."/app/".$tmpname,  \ZipArchive::CREATE)){
            return back()->wih('danger', e('Plugin cannot be generated. Please contact us for more information.'));
        }

        $zip->addFromString('plugin.php', $plugin);
        $zip->close();
        
        header('Content-disposition: attachment; filename=linkshortenershortcode.zip');
        header('Content-type: application/zip');
        readfile(STORAGE."/app/".$tmpname);
        unlink(STORAGE."/app/".$tmpname);
    }
}