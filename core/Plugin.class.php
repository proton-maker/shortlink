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
 * @package Core\Plugin
 * @author gempixel (http://gempixel.com)
 * @copyright 2020 gempixel
 * @license http://gempixel.com/license
 * @link http://gempixel.com  
 * @since 1.0
 */
namespace Core;

final class Plugin {	
	/**
	 * Plugin Directory
	 * @var array
	 */
	private static $plugins = [];   	
	
	/**
	 * Preload Plugin
	 *
	 * @author gempixel <https://piliruma.co.id> 
	 * @version 6.0
	 * @return void
	 */
	public static function preload(){
		// Load theme config
		if($config = View::config('include')){
			foreach($config as $include){
				if(file_exists(View::$path.'/'.$include)){
					include_once View::$path.'/'.$include;
				}else{
					if(View::config('child')){
						include_once STORAGE.'/themes/'.appConfig('app.default_theme').'/'.$include;
					}
				}
			}
		}
		
		// Load active plugins
		if($plugins = config('plugins')){
			foreach($plugins as $name => $plugin){
				if(file_exists(PLUGIN.'/'.$name.'/plugin.php')){
					include_once PLUGIN.'/'.$name.'/plugin.php';
				}
			}
		}
	}

	/**
	* Dispatch Event
	* @author gempixel
	* @since  1.0
	* @param  string $area  Area to plugin function
	* @param  array  $param Parameters sent by the function
	*/
	public static function dispatch($area, $param = array()){
		
		$return = [];      

		if(isset(self::$plugins[$area]) && is_array(self::$plugins[$area])) {
			foreach (self::$plugins[$area] as $fn) {
				if(is_array($fn) && class_exists($fn[0]) && method_exists($fn[0], $fn[1])){        
					$f = $fn[1];
					$return[] = $fn[0]::$f($param);    					   
				}elseif(is_callable($fn) || function_exists($fn)){
					$return[] = $fn($param);
				}
			}
			return $return;
		}
	}
	/**
	* Static Plug-in Function
	* @author gempixel
	* @since  1.0
	* @param  string $area  Area to plugin function
	* @param  array  $param Parameters sent by the function
	*/
	public static function staticPlug($area, $param = array()){
		$return = [];      
		if(isset(self::$plugins[$area]) && is_array(self::$plugins[$area])) {
			foreach (self::$plugins[$area] as $fn) {
				$return[] = $fn;       
			}
			return $return;
		}
	}    
	/**
	* Register Event
	* @author gempixel <https://piliruma.co.id>
	* @version 1.0
	* @param   [type] $area  [description]
	* @param   [type] $fn    [description]
	* @param   string $param [description]
	* @return  [type]        [description]
	*/
	public static function register($area, $fn, $param = ""){
		
		if(is_callable($fn)) {
			self::$plugins[$area][] = $fn;  
			return;
		}  

		if(is_array($fn) && class_exists($fn[0]) && method_exists($fn[0], $fn[1])){
			self::$plugins[$area][] = $fn;  
			return;    
		}

		if(is_string($fn) && function_exists($fn)) {
			self::$plugins[$area][] = $fn;  
			return;
		}

		self::$plugins[$area][] = call_user_func($fn, $param);
		return;
	}
	/**
	 * Return list of plugins
	 *
	 * @author gempixel <https://piliruma.co.id> 
	 * @version 6.0
	 * @param [type] $area
	 * @return void
	 */
	public static function plugins($area = null){
		
		if($area && isset(self::$plugins[$area])) return self::$plugins[$area];

		return self::$plugins;
	}
}