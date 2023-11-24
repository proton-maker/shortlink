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
 * @package Functions 
 * @author gempixel (http://gempixel.com) @license http://gempixel.com/license
 * @link http://gempixel.com  
 * @since 1.0
 */
  
use Core\Helper;
use Core\View;

/**
  * Generate url
  * @author gempixel <https://piliruma.co.id>
  * @version 1.0
  * @param   [type] $path [description]
  * @return  [type]       [description]
  */
if(!function_exists('url')){
  function url($path = NULL){
	  return Gem::$Config->url."/".$path;
  }
}
  
/**
  * Generate a link from route
  * @author gempixel <https://piliruma.co.id>
  * @version 1.0
  * @param   string $name 
  * @param   string $param
  */
if(!function_exists('route')){
  function route($name, $param = NULL){
	  return Gem::href($name, $param);
  }
}
/**
  * Shorthand to Helper::CSRF()
  * @author gempixel <https://piliruma.co.id>
  * @version 1.0
  */
if(!function_exists('csrf')){
  function csrf(){
	  return Helper::CSRF();
  }
}
/**
  * Shorthand to Helper::CSRF(false)
  * @author gempixel <https://piliruma.co.id>
  * @version 1.0
  */
if(!function_exists('csrf_token')){
  function csrf_token(){
	  return Helper::CSRF(false);
  }
}
/**
  * Shorthand to View::meta()
  * @author gempixel <https://piliruma.co.id>
  * @version 1.0
  */
if(!function_exists('meta')){
  function meta(){
	  return View::meta();
  }
}
/**
  * Shorthand to View::block()
  * @author gempixel <https://piliruma.co.id>
  * @version 1.0
  * @param   string $type
  */
if(!function_exists('block')){
  function block(string $type){
	  return View::block($type);
  }
}
/**
  * Push content to blocks
  * @author gempixel <https://piliruma.co.id>
  * @version 1.0
  * @param   string $content
  * @param   string $type        
  */
if(!function_exists('push')){
  function push(string $content, string $type = "style"){
	  return View::push($content, $type);
  }
}
/**
  * Shorthand to render
  * @author gempixel <https://piliruma.co.id>
  * @version 1.0
  * @param   string $name
  * @param   array  $data
  */
if(!function_exists('render')){
  function render(string $name, array $data = []){
	  return View::render($name, $data);
  }
}
/**
  * DryRender
  *
  * @author gempixel <https://piliruma.co.id> 
  * @version 6.0
  * @param string $name
  * @param array $data
  * @return void
  */
if(!function_exists('view')){
  function view(string $name, array $data = []){
	  return View::dryRender($name, $data);
  }
}
/**
  * View Extended Content
  *
  * @author gempixel <https://piliruma.co.id> 
  * @version 6.0
  * @return void
  */
if(!function_exists('section')){
  function section(){
	  return View::content();
  }
}
/**
  * Return Body Class
  * @author gempixel <https://piliruma.co.id>
  * @version 1.0
  * @return  [type] [description]
  */
if(!function_exists('bodyClass')){
  function bodyClass(){
	  echo View::bodyClass();
  }
}
/**
  * Assets shorthand
  * @author gempixel <https://piliruma.co.id>
  * @version 1.0
  * @param   [type] $name [description]
  * @return  [type]       [description]
  */
if(!function_exists('assets')){
  function assets($name){
	  return View::assets($name);
  }
}
/**
  * Uploads shorthand
  * @author gempixel <https://piliruma.co.id>
  * @version 1.0
  * @param   [type] $name [description]
  * @return  [type]       [description]
  */
if(!function_exists('uploads')){
  function uploads($name, $storage = null){
	  return View::uploads($name, $storage);
  }
}
/**
 * Redirect back
 *
 * @author gempixel <https://piliruma.co.id> 
 * @version 6.0
 * @return void
 */
if(!function_exists('back')){
  function back(){
	return Helper::redirect()->back();
  }
}
/**
  * Plugin
  * @author gempixel <https://piliruma.co.id>
  * @version 1.0
  * @param   [type] $name [description]
  * @return  [type]       [description]
  */
if(!function_exists('plug')){
  function plug($name, $param = []){
	  return \Core\Plugin::dispatch($name, $param);
  }
}
/**
  * Return timeago
  * @author gempixel <https://piliruma.co.id>
  * @version 1.0
  * @param   [type] $time [description]
  * @return  [type]       [description]
  */
if(!function_exists('timeago')){
  function timeago($time){
	  return Helper::timeago($time);
  }
}
/**
  * Full Pagination
  * @author gempixel <https://piliruma.co.id>
  * @version 1.0
  * @param   string $class [description]
  * @return  [type]        [description]
  */
if(!function_exists('pagination')){
  function pagination($class = "pagination", $liclass = 'page-item', $aclass = 'page-link'){
	  return Helper::pagination($class, $liclass, $aclass);
  }
}
/**
  * Return Simple Pagination
  * @author gempixel <https://piliruma.co.id>
  * @version 1.0
  * @return  [type] [description]
  */
if(!function_exists('simplePagination')){
  function simplePagination($class = "pagination", $liclass = 'page-item', $aclass = 'page-link'){
	  return Helper::simplePagination($class, $liclass, $aclass);
  }
}
/**
  * Stop Execution
  * @author gempixel <https://piliruma.co.id>
  * @version 1.0
  * @param   int    $code [description]
  * @return  [type]       [description]
  */
if(!function_exists('stop')){
  function stop(int $code, $text = "Page not found"){
	  GemError::trigger($code, $text);
	  exit;
  }
}
/**
  * Activate Middleware
  * @author gempixel <https://piliruma.co.id>
  * @version 1.0
  * @param   [type] $middleware [description]
  * @return  [type]             [description]
  */
if(!function_exists('middleware')){
  function middleware($middleware){  
	  return Gem::addMiddleware($middleware);
  }
}
/**
  * Return message
  *
  * @author gempixel <https://piliruma.co.id> 
  * @version 6.0
  * @return void
  */
if(!function_exists('message')){
  function message(){
	  echo Helper::message();
  }
}
/**
  * Translation text
  * @author gempixel <http://gempixel.com>
  * @version 1.0
  * @param   string $string
  */
if(!function_exists('e')){
  function e($string, $count = null, $variables = []){
	  return \Core\Localization::translate($string, $count, $variables);
  }
}
/**
  * Echo e()
  *
  * @author gempixel <https://piliruma.co.id> 
  * @version 1.0
  * @param [type] $string
  * @param [type] $count
  * @param array $variables
  * @return void
  */
if(!function_exists('ee')){
  function ee($string, $count = null, $variables = []){
	  echo e($string, $count, $variables);
  }
}
/**
 * Request Helper
 *
 * @author gempixel <https://piliruma.co.id> 
 * @version 6.0
 * @return void
 */
if(!function_exists('request')){
  function request(){
	  return new \Core\Request;
  }
}
/**
  * Get Session
  *
  * @author gempixel <https://piliruma.co.id> 
  * @version 1.0
  * @return void
  */
if(!function_exists('old')){
  function old($name){
	return (new \Core\Request)->session('TEMP_'.$name);
  }
}
/**
 * Custom var Dump
 *
 * @author gempixel <https://piliruma.co.id> 
 * @version 6.0
 * @return void
 */
if(!function_exists('gvd')){
  function gvd(){
	var_dump(...func_get_args());
	exit;
  }
}

/**
 * IDN to UTF8
 */
if(!function_exists('idn_to_utf8')){
  function idn_to_utf8($string){
	  return $string;
  }
}
/**
 * IDN to ASCii
 */
if(!function_exists('idn_to_ascii')){
  function idn_to_ascii($string){
	  return $string;
  }
}
/**
 * Shortcut to plugin
 */
if(!function_exists('plugin')){
  function plugin($string){
  }
}
/**
 * Add to admin menu
 */
if(!function_exists('adminmenu')){
  function adminmenu(array $link){
	  return '<li class="sidebar-item"><a class="sidebar-link" href="'.$link['route'].'">'.$link['title'].'</a></li>';
  }
}
/**
 * Append Query
 *
 * @author gempixel <https://piliruma.co.id> 
 * @version 6.0
 * @param array $query
 * @return void
 */
if(!function_exists('appendquery')){
  function appendquery(array $query){
	return http_build_query((request()->query() ?? []) + $query);
  }
}