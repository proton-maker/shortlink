<?php 
/**
 * ====================================================================================
 *                           GemFramework (c) Xsantana
 * ----------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework owned by Xsantana Inc as such
 *  distribution or modification of this framework is not allowed before prior consent
 *  from Xsantana administrators. If you find that this framework is packaged in a 
 *  software not distributed by Xsantana or authorized parties, you must not use this
 *  sofware and contact Xsantana at https://piliruma.co.id/contact to inform them of this
 *  misuse otherwise you risk of being prosecuted in courts.
 * ====================================================================================
 *
 * @package Gem\Core\Request
 * @author Xsantana (http://Xsantana.com)
 * @copyright 2020 Xsantana
 * @license http://Xsantana.com/license
 * @link http://Xsantana.com  
 * @since 1.0
 */
namespace Core;

use Core\Helper;

final class Request {
	/**
	 * Sessions
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 1.0
	 */
	private static $session = [];
	/**
	 * URL to send request
	 * @var null
	 */
	private $_HTTPURL = NULL;
	/**
	 * CURL Response
	 * @var array
	 */
	private $_HTTPCURLRESPONSE = [];
	/**
	 * CURL Parameters
	 * @var array
	 */
	private $_HTTPCURLPARAMS = [];
	/**
	 * $request variable
	 * @var null
	 */
	private $_HTTPrequest = NULL;	
	/**
	 * HTTP Method
	 * @var null
	 */
	private $_HTTPmethod = NULL;	
	/**
	 * HTTP Param Count
	 * @var integer
	 */
	private $_HTTPcount = 0;
	/**
	 * Acceptable File Uploads
	 * @var array
	 */
	private $_FILEacceptable = ["image/jpg", "image/png", "image/jpeg"];
	/**
	 * List of Common File Types
	 * @var array
	 */
	private $_FILEcommon	= [
								"js" => ["application/javascript"],
								"json" => ["application/json"],
								"xml"  => ["application/xml"],
								"zip"  => ["application/zip", "application/x-zip-compressed"],
								"pdf"  => ["application/pdf"],
								"sql"  => ["application/sql"],
								"doc"  => ["application/msword"],
								"mpeg" => ["audio/mpeg"],
								"mp4" => ["video/mp4"],
								"ogg"  => ["audio/ogg"],
								"css"  => ["text/css"],
								"html" => ["text/html"],
								"xml"  => ["text/xml"],
								"csv"  => ["text/csv"],
								"txt"	=> ["text/plain"],
								"png"  => ["image/png"],
								"jpeg" => ["image/jpeg"],
								"jpg" => ["image/jpeg"],
								"gif"  => ["image/gif"],
								"ico" => ["image/x-icon"]
							];
	/**
	 * File Object
	 * @var null
	 */
	private $_HTTPfiles = NULL;

	/**
	 * Response
	 * @var null
	 */
	public $_HTTPPARAMETERS = NULL;

	/**
	 * Capture Requests via HTTP Post or HTTP Get
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 */
	public function __construct(){
		
		
		$this->_HTTPmethod = Helper::clean($_SERVER['REQUEST_METHOD'], 3, TRUE);

		if(!in_array($this->_HTTPmethod, ["GET", "POST", "PUT", "DELETE","PATCH"])) return false;

		$this->_HTTPrequest = new \stdClass;

		foreach ($_REQUEST as $key => $value) {
			if($this->_HTTPmethod == "GET"){
				$this->_HTTPrequest->{$key} = Helper::clean($value, 3, TRUE);
			}else{
				$this->_HTTPrequest->{$key} = $value;
			}
			$this->_HTTPcount++;
		}

		$this->catchFile();
	}	

	/**
	 * Output class
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 * @return  string [description]
	 */
	public function __toString(){
		echo "<pre>";
		print_r($this->_HTTPrequest);
		echo "</pre>";
		return "";
	}
	/**
	 * Get variable magically
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 * @param   [type] $variable [description]
	 * @return  [type]           [description]
	 */
	public function __get($variable){
		if(!isset($this->_HTTPrequest->{$variable})) return null;
		return $this->_HTTPrequest->{$variable};
	}
	/**
	 * Get variable
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 1.0
	 * @param [type] $variable
	 * @return void
	 */
	public function get($variable){
		if(!isset($this->_HTTPrequest->{$variable})) return null;
		return $this->_HTTPrequest->{$variable};
	}
	/**
	 * Return All Variables
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 1.0
	 * @return void
	 */
	public function all($asArray = false){
		return $asArray ? (array) $this->_HTTPrequest : $this->_HTTPrequest;
	}
	/**
	 * Catch and process file uploads
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0	 
	 */
	private function catchFile(){

		if($_FILES){
			
			$this->_HTTPfiles = new \stdClass;
			foreach ($_FILES as $key => $file) {
				
				if(empty($file["type"]) || empty($file["name"])) continue;
				if(isset($this->_HTTPfiles->{$key})) continue;

				$this->_HTTPfiles->{$key} = new \stdCLass;
				$this->_HTTPfiles->{$key}->allowed = in_array($file["type"], $this->_FILEacceptable) ? true : false;
				$this->_HTTPfiles->{$key}->name = Helper::clean($file["name"]);
				$this->_HTTPfiles->{$key}->ext = Helper::extension($file["name"]);
				$this->_HTTPfiles->{$key}->type = Helper::clean($file["type"]);
				$this->_HTTPfiles->{$key}->location = Helper::clean($file["tmp_name"]);
				$this->_HTTPfiles->{$key}->size = $file["size"];
				$this->_HTTPfiles->{$key}->sizekb = round($file["size"] / 1024, 2);
				$this->_HTTPfiles->{$key}->sizemb = round($this->_HTTPfiles->{$key}->sizekb / 1024, 3);
				$this->_HTTPfiles->{$key}->mimematch = (isset($this->_FILEcommon[Helper::extension($file["name"])]) && in_array($file["type"], $this->_FILEcommon[Helper::extension($file["name"])])) ? true : false;
			}
		}

	}
	/**
	 * Return File Object
	 * @author Xsantana <https://piliruma.co.id>
	 * @param  $input Filter an input name
	 * @version 1.0
	 * @return  object File object
	 */
	public function file($input = NULL){

		if(!is_null($input)) {
			
			if(isset($this->_HTTPfiles->{$input})) return $this->_HTTPfiles->{$input};

			return false;
		}

		return $this->_HTTPfiles;
	}
	/**
	 * Move file to another directory
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 * @param   class $request   [description]
	 * @param   [type]  $directory [description]
	 * @return  [type]             [description]
	 */
	public function move($request, $directory = null, $name = null){
		
		$directory = $directory ?? PUB."/".VIEW::UPLOADS;		
		$filename = $name ?: $request->name;

		if(move_uploaded_file($request->location, $directory.'/'.$filename)){
			return true;
		}
		return false;
	}
	/**
	 * Set allowed types
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 * @param   array $types
	 */
	public function setAllowedType(array $types) {

		foreach ($types as $type) {
			
			if(!isset($this->_FILEcommon[$type])) continue;

			$this->_FILEacceptable[] = $this->_FILEcommon[$type];
		}

	}
	/**
	 * Type of HTTP Request
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 */
	public function typeof(){
		return strtolower($this->_HTTPmethod);
	}
	/**
	 * Count number of parameters
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 */
	public function count(){
		return $this->_HTTPcount;
	}	
	/**
	 * Check if a method has been posted
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 */
	public function isPost(){
		return $this->typeof() == "post" ? true : false;
	}
	/**
	 * Get Request Body
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 */
	public function getBody(){
		return file_get_contents("php://input");
	}
	/**
	 * Get HTTP Code
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 */
	public function httpCode($code = NULL){

		if(!is_null($code)) return http_response_code($code);

		return http_response_code();
	}
	/**
	 * Get Body JSON
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 */
	public function getJSON(){
		return json_decode($this->getBody());
	}
	/**
	 * Get Server Information
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 * @param   string $name [description]
	 * @return  [type]       [description]
	 */
	public function server(string $name){
		
		$name = strtoupper($name);

		if(isset($_SERVER[$name])) {
			return Helper::clean($_SERVER[$name], 3);
		}
		return null;
	}
	/**
	 * Full URI
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 */
	public function uri($withquery = true){
		$uri = $this->http()."://".$this->server("HTTP_HOST").$this->server("REQUEST_URI");

		return ($withquery == false && $parts = explode("?", $uri)) ? $parts[0] : $uri;
	}
	/**
	 * Get Host
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 * @return  [type] [description]
	 */
	public function host(){
		return $this->http()."://".$this->server("HTTP_HOST");
	}
	/**
	 * Grabs referer URI
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 */
	public function referer(){
		return $this->server("HTTP_REFERER") ?? null;
	}
	/**
	 * Return path with or without query
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 1.0
	 * @param boolean $withquery
	 * @return void
	 */
	public function path($withquery = false){
		$path = rawurldecode(trim(rtrim($this->server("REQUEST_URI"), "/"), "/"));
		$base = trim(str_replace('/public/index.php', '', $this->server('PHP_SELF')), '/');
		$path = trim(preg_replace("~$base~", '', $path, 1), '/');		

		return ($withquery == false && $parts = explode("?", $path)) ? $parts[0] : $path;
	}

	/**
	 * Parse and return queries
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 1.0
	 * @param [type] $query
	 * @return void
	 */
	public function query($query = null){
		$path = explode('?', $this->path(true));
		
		if(!is_array($path) || !isset($path[1])) return null;

		parse_str($path[1], $queries);		

		return ($query && isset($queries[$query])) ? $queries[$query] : $queries;
	}
	/**
	 * Get URI Segment
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 * @param   int    $segment [description]
	 * @return  [type]          [description]
	 */
	public function segment(int $segment = null){

		$uri = explode("/", $this->path());

		if(is_numeric($segment) && isset($uri[$segment-1])) return $uri[$segment-1];

		return $uri;
	}
	/**
	 * Return HTTP method
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 */
	public function http(){
		return $this->isSecure() ? "https" : "http";
	}
	/**
	 * Is Secure
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.2
	 * @return  boolean [description]
	 */
	public function isSecure(){

		if($this->server('HTTPS') == 'on' || $this->server('SERVER_PORT') == 443 || $this->server('HTTP_X_FORWARDED_PROTO') == 'https'){
			return true;
		}

		if($this->server('HTTP_CF_VISITOR')){
			$visitor = json_decode($this->server('HTTP_CF_VISITOR'));
			if(isset($visitor) && $visitor->scheme == 'https') return true;
		}

		return false;
	}
	/**
	 * Is Ajax
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 * @return  boolean [description]
	 */
	public function isAjax(){
		return ($this->server("HTTP_X_REQUESTED_WITH") && strcasecmp($_SERVER['HTTP_X_REQUESTED_WITH'], 'xmlhttprequest') == 0) ? true : false;
	}
	/**
	 * Get Requester IP
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 6.2.1
	 */
	public function ip(){

		if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])) $ipaddress =  $_SERVER['HTTP_CF_CONNECTING_IP'];
		elseif (isset($_SERVER['HTTP_X_REAL_IP'])) 	 	$ipaddress = $_SERVER['HTTP_X_REAL_IP'];
		elseif (isset($_SERVER['HTTP_CLIENT_IP']))	 		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']))		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		elseif (isset($_SERVER['HTTP_X_FORWARDED']))		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		elseif (isset($_SERVER['HTTP_FORWARDED']))	$ipaddress = $_SERVER['HTTP_FORWARDED'];
		elseif (isset($_SERVER['REMOTE_ADDR']))	$ipaddress = $_SERVER['REMOTE_ADDR'];

		$ipaddress = Helper::clean($ipaddress, 3, true);

		if(substr($ipaddress, 0, 7) == "::ffff:"){
		    $ipaddress = str_replace("::ffff:", "", $ipaddress);
		}
		
		$ip = explode(",", $ipaddress);
		if(is_array($ip) && count($ip) > 1) return $ip[0];

		return $ipaddress;
	}
	/**
	 * Current User Agent
	 * @author Xsantana <https://piliruma.co.id>
	 * @version 1.0
	 */
	public function userAgent(){
		return isset($_SERVER["HTTP_USER_AGENT"]) ? Helper::clean($_SERVER["HTTP_USER_AGENT"], 3, TRUE): null;
	}
	
	/**
	 * Detect Device
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 6.0
	 * @return void
	 */
	public function device(){
		$platform =   "Unknown OS";
		$os       =  [
					'/windows nt 11.0/i'    =>  'Windows 11',
					'/windows nt 10.0/i'    =>  'Windows 10',
					'/windows nt 6.3/i'     =>  'Windows 8.1',
					'/windows nt 6.2/i'     =>  'Windows 8',
					'/windows nt 6.1/i'     =>  'Windows 7',
					'/windows nt 6.0/i'     =>  'Windows Vista',
					'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
					'/windows nt 5.1/i'     =>  'Windows XP',
					'/windows xp/i'         =>  'Windows XP',
					'/windows nt 5.0/i'     =>  'Windows 2000',
					'/windows me/i'         =>  'Windows ME',
					'/win98/i'              =>  'Windows 98',
					'/win95/i'              =>  'Windows 95',
					'/win16/i'              =>  'Windows 3.11',
					'/macintosh|mac os x/i' =>  'Mac OS X',
					'/mac_powerpc/i'        =>  'Mac OS 9',
					'/linux/i'              =>  'Linux',
					'/ubuntu/i'             =>  'Ubuntu',
					'/iphone/i'             =>  'iPhone',
					'/ipod/i'               =>  'iPod',
					'/ipad/i'               =>  'iPad',
					'/android/i'            =>  'Android',
					'/blackberry/i'         =>  'BlackBerry',
					'/bb10/i'         		=>  'BlackBerry',
					'/cros/i'				=>	'Chrome OS',
					'/webos/i'              =>  'Mobile'
				];
		foreach ($os as $regex => $value) { 
			if (preg_match($regex, $this->userAgent())) {
				$platform    =   $value;
			}
		}   
		return $platform;	
	}
	/**
	 * User's browser
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 6.2
	 * @return void
	 */
	public function browser() {
		$matched   = 	false;
		$browser   =   "Unknown Browser";
		$browsers  =   [
						'/safari/i'     =>  'Safari',			
						'/firefox/i'    =>  'Firefox',
						'/fxios/i'    	=>  'Firefox',						
						'/msie/i'       =>  'Internet Explorer',
						'/Trident\/7.0/i'  =>  'Internet Explorer',
						'/chrome/i'     =>  'Chrome',
						'/crios/i'		=>	'Chrome',
						'/opera/i'      =>  'Opera',
						'/opr/i'      	=>  'Opera',
						'/netscape/i'   =>  'Netscape',
						'/maxthon/i'    =>  'Maxthon',
						'/konqueror/i'  =>  'Konqueror',
						'/edg/i'       =>  'Edge',
					];
		
		foreach ($browsers as $regex => $value) { 
			if (preg_match($regex,  $this->userAgent())) {
				$browser  =  $value;
				$matched = true;
			}
		}
		
		if(!$matched && preg_match('/mobile/i', $this->userAgent())){
			$browser = 'Mobile Browser';
		}

		return $browser;
	  } 
	/**
	 * Get geoip
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 1.0
	 * @param [type] $ip
	 * @return void
	 */
	public function country($ip = null){		
		$ip = $ip ?? $this->ip();

		if(appConfig('app.geodriver') == 'api'){

			$url = str_replace('{IP}', $ip, appConfig('app.geopath'));
			$response = Http::url($url)->get()->bodyObject();
			return ['city' => $response->city, 'country' => $response->country_name];
		}

		if(appConfig('app.geodriver') == 'maxmind'){
			try{
				$reader = new \MaxMind\Db\Reader(appConfig('app.geopath'));
				$response = $reader->get($ip);
				$reader->close();
	
				return ['city' => $response['city']['names']['en'] ?? '', 'state' => $response['subdivisions'][0]['names']['en'] ?? '', 'country' => $response['country']['names']['en'] ?? ''];				
			
			} catch(\Exception $e){
				\GemError::log('IP Error: '.$e->getMessage(), ['ip' => $ip]);
				return ['city' => null, 'state' => null, 'country' => null];
			}
		}

		if(appConfig('app.geodriver') == 'custom'){
			return \call_user_func_array(appConfig('app.geopath'), [$ip]);
		}
	}
	/**
   * Read/Write Cookie
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @param   string  $name 
   * @param   string  $value
   * @param   integer $time  in minutes        
   */
  public function cookie($name, $value = "", $time = 1){
    if(empty($value)){
      if(isset($_COOKIE[$name])){
        return Helper::clean($_COOKIE[$name], 3, FALSE);
      }else{
        return FALSE;
      }
    }
    setcookie($name, $value, time()+($time*60), "/", "", FALSE, TRUE);
  }	
  /**
   * Read/Write Session
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @param   string $name  [description]
   * @param   mixed $value [description]
   */
  public function session(string $name, $value = ""){
    if(empty($value)){
      if(isset($_SESSION[$name])){
        return Helper::clean($_SESSION[$name], 3, FALSE);
      }else{
        return FALSE;
      }
    }
    $_SESSION[$name] = $value;
    return true;
  }
  /**
   * Unset session
   *
   * @author Xsantana <https://piliruma.co.id> 
   * @version 1.0
   * @param string $name
   * @return void
   */
  public function unset(string $name){
	if(isset($_SESSION[$name])) {
		unset($_SESSION[$name]);
		return true;
	}
	return false;
  }
   /**
   * Save temporary data
   *
   * @author Xsantana <https://piliruma.co.id> 
   * @version 1.0
   * @param [type] $name
   * @param [type] $value
   * @return void
   */
  public static function save($name, $value){
	$name = "TEMP_".$name;
    $_SESSION[$name] = $value;
    self::$session[] = $name;
  }
  /**
   * Clear temporary data
   *
   * @author Xsantana <https://piliruma.co.id> 
   * @version 1.0
   * @return void
   */
  public static function clear(){
    foreach (self::$session as $session) {
      unset($_SESSION[$session]);
	}
  }
 	/**
 	 * Validate Input
 	 * @author Xsantana <https://piliruma.co.id>
 	 * @version 1.0
 	 * @param   string $input [description]
 	 * @param   mixed $rule  [description]
 	 * @return  [type]        [description]
 	 */
  public function validate(?string $input, $rule = null){

  	if(!$input || empty($input)) return false;

  	if($rule && is_numeric($rule)) {
  		if(strlen($input) < $rule) return false;
  	}

  	if($rule && $rule == "email") {
  		if(Helper::Email($input) === false) return false;
  	}

	if($rule && $rule == "url") {
		if(Helper::isURL($input) === false) return false;
	}

	if($rule && $rule == "username") {
		if(Helper::Username($input) === false) return false;
	}

  	if($rule && $rule == "int"){
  		if(!is_numeric($input)) return false;
  	}
  	return true;
  } 
}
