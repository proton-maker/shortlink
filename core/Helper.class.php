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
 * @package Helper
 * @author Xsantana (http://Xsantana.com)
 * @copyright 2018 Xsantana
 * @license http://Xsantana.com/license
 * @link http://Xsantana.com  
 * @since 1.0
 */
namespace Core;

use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

use GemError;

final class Helper {

  /**
   * Configuration
   * @var array
   */
  static protected $config  = [];  
  /**
   * Pagination Data
   * @var array
   */
  private static $paginate  = [];
  /**
   * Cacher Instance
   * @var null
   */
  private static $cacheInstance = null;
  /**
   * Password Hash Cost
   */
  private static $hashCost = 10;
  /**
   * Cache Driver
   * @var string
   */
  const  CACHEDRIVER = "files";  
  /**
   * Constact to redirect back to source
   */
  const  BACKTOSOURCE  = "__backtosource";
  /**
   * Session name constant
   */
  const  SESSIONMESSAGE  = "__message";
  /**
   * CSRF name constant
   */
  const  CSRFNAME = "__CRSF";

  /**
   * Constructor
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   */
  public function __construct(){

  }

  /**
   * Set value
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @param   $var
   * @param   $val
   */
  public static function set($var, $val){
    self::$$var = $val;
  }
  /**
   * Redirect to
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @param   string|null $route
   * @return      mixed
   */
  public static function redirect(?string $route = null, int $code = 302){
    switch ($code) {
      case '301':
        header('HTTP/1.1 301 Moved Permanently');
        break;
      case '404':
        header('HTTP/1.1 404 Not Found');
        break;
      case '503':
        header('HTTP/1.1 503 Service Temporarily Unavailable');
        header('Status: 503 Service Temporarily Unavailable');
        header('Retry-After: 60');
        break;
    }

    if($route){
      header("Location: ".self::$config->url."/$route");
    }

    return new self();
  }
  /**
   * Redirect Back
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @return 
   */
  public function back(){
    $request = new Request();
    $redirect = empty($request->referer()) ? url('') : $request->referer();
    header("Location: {$redirect}");     
    return $this;     
  }
  /**
   * Redirect to URL
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @param   string $url
   * @return  void
   */
  public function to(string $url){
    header("Location: $url");     
    return $this;    
  }
  /**
   * With Message
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @param   string $error  
   * @param   string $message
   * @return     mixed
   */
  public function with(string $error, string $message){
    $_SESSION[self::SESSIONMESSAGE] = ["type" => self::clean($error, 3, false), "message" => self::clean($message, 3, false, '<a><strong><br>')];
    return $this;
  }

  /**
   * Set message
   *
   * @author Xsantana <https://piliruma.co.id> 
   * @version 6.0
   * @param string $error
   * @param string $message
   * @return void
   */
  public static function setMessage(string $error, string $message){
    $_SESSION[self::SESSIONMESSAGE] = ["type" => self::clean($error, 3, false), "message" => self::clean($message, 3, false, '<a><strong><br>')];
  }

  /**
   * Notification Function
   * @author Xsantana <http://Xsantana.com>
   * @version 1.0
   * @param   string $style
   * @return   mixed
   */
  public static function message($style = ""){
    if(isset($_SESSION[self::SESSIONMESSAGE]) && !empty($_SESSION[self::SESSIONMESSAGE])) {
      
      $message = $_SESSION[self::SESSIONMESSAGE];

      $message["type"] = self::clean($message["type"], 3, false, '<a><strong><br>');
      $message["message"] = self::clean($message["message"], 3, false, '<a><strong><br>');
      $message = "<div class=\"custom-alert alert bg-{$message["type"]} text-white shadow\">{$message["message"]}</div>";
      unset($_SESSION[self::SESSIONMESSAGE]);
    }else {
      $message = "";
    }

    return $message;
  } 

 /**
  * Clean a string
  * @author  Xsantana <http://Xsantana.com>
  * @version 1.0
  * @param    $string
  * @param   $level cleaning level (1=lowest, 2, 3=highest)
  * @param   boolean $chars 
  * @param   string  $leave 
  * @return     mixed
  */
  public static function clean($string, $level = '1', $chars = FALSE, $leave = ""){

    if(!is_string($string)) return $string;

    $string = preg_replace('/<script[^>]*>([\s\S]*?)<\/script[^>]*>/i', '', $string);      

    switch ($level) {     
      case '3':
        if(empty($leave)){
          $search = array('@<script[^>]*?>.*?</script>@si',
                         '@<[\/\!]*?[^<>]*?>@si',
                         '@<style[^>]*?>.*?</style>@siU',
                         '@<![\s\S]*?--[ \t\n\r]*>@'
          ); 
          $string = preg_replace($search, '', $string);              
        }
        $string = strip_tags($string, $leave);      
        if($chars) {
          $string = htmlspecialchars($string, ENT_QUOTES);  
        }
        break;
      case '2':
        $string = strip_tags($string,'<b><i><s><p><u><strong><span>');
        break;
      case '1':
        $string = strip_tags($string,'<b><i><s><u><strong><a><pre><code><p><div><span>');
        break;
    }   
    if(!preg_match('!nofollow!', $string)) $string = str_replace('href=','rel="nofollow" href=', $string);   
    return $string; 
  }
  /**
   * [Sanitize description]
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @param   $type
   */
  public static function SanitizePost(){
    $_POST = array_map("self::RequestClean", $_POST);
  }

  /**
   * Clean Requests
   * @author  Xsantana <http://Xsantana.com>
   * @version 1.0
   * @see     Main::clean
   * @param   $string
   */
  public static function RequestClean($string){
    return self::clean($string, 3, TRUE);
  }    
  /**
   * Generate custom slug
   * @author Xsantana <http://Xsantana.com>
   * @version 1.0
   * @param   $text     
   * @param   array  $replace  
   * @param   string $delimiter
   * @return       mixed
   */
    public static function slug($text, $replace = array(), $delimiter='-') {
      $str = $text;
      if( !empty($replace) ) {
        $str = str_replace((array)$replace, ' ', $str);
      }

      $text = $str;
      static $translit = array(
        'a' => '/[ÀÁÂẦẤẪẨÃĀĂẰẮẴȦẲǠẢÅÅǺǍȀȂẠẬẶḀĄẚàáâầấẫẩãāăằắẵẳȧǡảåǻǎȁȃạậặḁą]/u',
        'b' => '/[ḂḄḆḃḅḇ]/u',     'c' => '/[ÇĆĈĊČḈçćĉċčḉ]/u',
        'd' => '/[ÐĎḊḌḎḐḒďḋḍḏḑḓð]/u',
        'e' => '/[ÈËÉĒĔĖĘĚȄȆȨḔḖḘḚḜẸẺẼẾỀỂỄỆèéëēĕėęěȅȇȩḕḗḙḛḝẹẻẽếềểễệ]/u',
        'f' => '/[Ḟḟ]/u',       'g' => '/[ĜĞĠĢǦǴḠĝğġģǧǵḡ]/u',
        'h' => '/[ĤȞḢḤḦḨḪĥȟḣḥḧḩḫẖ]/u',    'i' => '/[ÌÏĨĪĬĮİǏȈȊḬḮỈỊiìïĩīĭįǐȉȋḭḯỉị]/u',
        'j' => '/[Ĵĵǰ]/u',        'k' => '/[ĶǨḰḲḴKķǩḱḳḵ]/u',
        'l' => '/[ĹĻĽĿḶḸḺḼĺļľŀḷḹḻḽ]/u',   'm' => '/[ḾṀṂḿṁṃ]/u',
        'n' => '/[ÑŃŅŇǸṄṆṈṊñńņňǹṅṇṉṋ]/u',
        'o' => '/[ÒÖŌŎŐƠǑǪǬȌȎȪȬȮȰṌṎṐṒỌỎỐỒỔỖỘỚỜỞỠỢØǾòöōŏőơǒǫǭȍȏȫȭȯȱṍṏṑṓọỏốồổỗộớờởỡợøǿ]/u',
        'p' => '/[ṔṖṕṗ]/u',       'r' => '/[ŔŖŘȐȒṘṚṜṞŕŗřȑȓṙṛṝṟ]/u',
        's' => '/[ŚŜŞŠȘṠṢṤṦṨſśŝşšșṡṣṥṧṩ]/u',  'ss'  => '/[ß]/u',
        't' => '/[ŢŤȚṪṬṮṰţťțṫṭṯṱẗ]/u',    'th'  => '/[Þþ]/u',
        'u' => '/[ÙŨŪŬŮŰŲƯǓȔȖṲṴṶṸṺỤỦỨỪỬỮỰùũūŭůűųưǔȕȗṳṵṷṹṻụủứừửữựµ]/u',
        'v' => '/[ṼṾṽṿ]/u',       'w' => '/[ŴẀẂẄẆẈŵẁẃẅẇẉẘ]/u',
        'x' => '/[ẊẌẋẍ×]/u',      'y' => '/[ÝŶŸȲẎỲỴỶỸýÿŷȳẏẙỳỵỷỹ]/u',
        'z' => '/[ŹŻŽẐẒẔźżžẑẓẕ]/u',       
        'ae'  => '/[ÄǞÆǼǢäǟæǽǣ]/u',     'oe'  => '/[Œœ]/u',
        'dz'  => '/[ǄǅǱǲǆǳ]/u',
        'ff'  => '/[ﬀ]/u',  'fi'  => '/[ﬃﬁ]/u', 'ffl' => '/[ﬄﬂ]/u',
        'ij'  => '/[Ĳĳ]/u', 'lj'  => '/[Ǉǈǉ]/u',  'nj'  => '/[Ǌǋǌ]/u',
        'st'  => '/[ﬅﬆ]/u', 'ue'  => '/[ÜǕǗǙǛüǖǘǚǜ]/u',
        'eur'   => '/[€]/u',  'cents' => '/[¢]/u',  'lira'  => '/[₤]/u',  'dollars' => '/[$]/u',
        'won' => '/[₩]/u',  'rs'  => '/[₨]/u',  'yen' => '/[¥]/u',  'pounds'  => '/[£]/u',
        'pts' => '/[₧]/u',
        'degc'  => '/[℃]/u',  'degf'  => '/[℉]/u',
        'no'  => '/[№]/u',  'tm'  => '/[™]/u'
      );
      $str = preg_replace (array_values($translit), array_keys($translit), $str);
      
      $str = preg_replace (
        array('/\p{P}/u',  '/[^A-Za-z0-9]/', '/-{2,}/', '/^-|-$/'),
        array('-',           '-',              '-',       '-'),
        function_exists ('transliterator_transliterate') ? transliterator_transliterate (
          'NFKD; '.
          'Latin; '.
          'Latin/US-ASCII; '.
          '[:Nonspacing Mark:] Remove; '.
          'Lower',
        $str)
        
        //attempt transliteration with iconv: <php.net/manual/en/function.iconv.php>
        : strtolower (function_exists ('iconv') ? str_replace (array ("'", '"', '`', '^', '~'), '', strtolower (
          iconv ('UTF-8', 'US-ASCII//IGNORE//TRANSLIT', $str)
        )) : $str)
      );
            
      if(!$str || $str =="_" || $str == "-"){
        $str = preg_replace("/[^A-Za-z0-9 -]/", '', $text);  
        $str = preg_replace("/[\/_|+ -]+/", $delimiter, $str);
        $str = str_replace("'","",$str);
        $str = str_replace('"','',$str);
        $str = strtolower(rtrim(trim($str,'-'), '-'));
        return $str;           
      }else{
        return trim($str, '-');
      }           
    }  
  /**
  * Convert a timestap into timeago format
  * @author  Xsantana <http://Xsantana.com>
  * @version 1.0
  * @param time
  * @return timeago
  */  
  public static function timeago($time, $tense=TRUE){
   $time = strtotime($time);
   $periods = ["second","minute","hour","day","week","month","year","decade"];
   $lengths = ["60","60","24","7","4.35","12","10"];
   $now = time();
     $difference = $now - $time;
     for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
     }
     $difference = round($difference);
     if($difference > 1) $periods[$j] = $periods[$j].'s';
    return e("{d} {p} ago", null, ["d" => $difference, "p" => e($periods[$j])]);
  } 
       
  /**
   * Generate NOW date time
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @return 
   */
  public static function dtime($time = "now", $format = "Y-m-d H:i:s") : string{
    return date($format, strtotime($time));
  }
/**
  * Truncate a string
  * @param string, delimiter, append string
  * @return truncated message
  */  
  public static function truncate($string, $delimiter, $end="...") {
    $length = strlen($string);

    if ($length > $delimiter) {
      $newstring = substr($string, 0, strrpos($string, ' ', $delimiter-$length)).$end;
      
      if(strlen($newstring) < 5){
        return substr($string, 0, $delimiter).$end;
      } else {
        return $newstring;
      }
    }    
        
    return $string;
  }   
  /**
   * Counts the time it takes to read the post
   * @author Xsantana <http://Xsantana.com>
   * @version 1.0
   * @param   string $text
   * @return  mixed
   */
  public static function readCount(string $text){
    $count = str_word_count(strip_tags($text));
    $averageTime = 175; // Words Per Minute
    $time = round(($count / $averageTime));

    return ($time < "2" ? "1 min <strong>read</strong>": "$time mins <strong>read</strong>");      
  }  

  /**
   * Readmore
   * @author Xsantana <http://Xsantana.com>
   * @version 1.0
   * @param   object $post
   * @return  mixed
   */
  public static function readmore($content, $url, $text = "Read more"){
    $content = explode("{{--more--}}", $content);
    $content = explode("<!--more-->", $content[0]);
    $content = explode("&lt;!--more--&gt;", $content[0]);
    return strip_tags($content[0], "a").($text ? "<p><a href='".$url."' class='btn btn-primary btn-blog'>$text</a></p>" : "");        
  } 

  /**
   * Paginate
   *
   * @author Xsantana <https://piliruma.co.id> 
   * @version 6.0
   * @param $total
   * @param $perpage
   * @param $page
   * @param $format
   * @param boolean $simple
   * @return void
   */
  public static function paginate($total, $perpage, $page, $format, $simple = false){

    if($simple == false) {
      // Pagination
      if(($total%$perpage)<>0) {
        $max = floor($total/$perpage)+1;
      } else {
        $max = floor($total/$perpage);
      }     

      self::$paginate = [
          "total"   => $max,
          "page"    => $page,
          "format"  => $format
      ];      
    } else {

      self::$paginate = [
        "total"   => $total,
        "hasnext" => $total > 0 && $total <= $perpage ? true : false,
        "page"    => $page,
        "format"  => $format
      ];
    }

  }
  /**  
  * Generates pagination with class "pagination"
  * @param total number of pages, current pages, format of url
  * @return complete pagination elements
  */
  public static function pagination($class = 'pagination', $liclass = 'page-item', $aclass = 'page-link'){
   
    $total = self::$paginate["total"];
    $format = self::$paginate["format"];
    $current = self::$paginate["page"];

    $limit = 1;

    $page_count = ceil($total/$limit);
    $current_range = array(($current-5 < 1 ? 1 : $current-3), ($current+5 > $page_count ? $page_count : $current+3));

    $first_page = $current > 5 ? '<li'.($liclass?' class="'.$liclass.'"':'').'><a'.($liclass?' class="'.$aclass.'"':'').' href="'.sprintf($format, '1').'">'.e("First").'</a></li>'.($current < 5 ? ' ' : '') : null;
    $last_page = $current < $page_count-2 ? ($current > $page_count-4 ? ' ' : '  ').'<li'.($liclass?' class="'.$liclass.'"':'').'><a '.($liclass?' class="'.$aclass.'"':'').'href="'.sprintf($format, $page_count).'">'.e("Last").'</a></li>' : null;

    $previous_page = $current > 1 ? '<li class="previous '.($liclass?$liclass:'').'"><a'.($liclass?' class="'.$aclass.'"':'').' href="'.sprintf($format, ($current-1)).'">'.e("Previous").'</a></li> ' : null;
    $next_page = $current < $page_count ? ' <li class="next '.($liclass?$liclass:'').'"><a'.($liclass?' class="'.$aclass.'"':'').' href="'.sprintf($format, ($current+1)).'">'.e("Next").'</a></li> ' : null;

    for ($x=$current_range[0];$x <= $current_range[1]; ++$x)    
        $pages[] = ($x == $current ? '<li class="active '.($liclass?$liclass:'').'"><a'.($liclass?' class="'.$aclass.'"':'').' href="#">'.$x.'</a></li>' : '<li'.($liclass?' class="'.$liclass.'"':'').'><a '.($liclass?' class="'.$aclass.'"':'').'href="'.sprintf($format, $x).'"">'.$x.'</a></li>');
    if ($page_count > 1)
      return '<ul class="'.$class.'">'.$first_page.$previous_page.implode(' ', $pages).$next_page.$last_page.'</ul>';
  }
  /**
   * Simple Pagination
   * @author Xsantana <http://Xsantana.com>
   * @version 1.0
   * @param   $total 
   * @param   $format
   * @param   string $class 
   * @return    mixed
   */
  public static function simplePagination($class = 'pagination', $liclass = 'page-item', $aclass = 'page-link'){
      
    $format = self::$paginate["format"];
    $current = self::$paginate["page"];

    $previous_page = $current > 1 ? '<a class="btn btn-primary btn-sm'.($class? ' '.$class:'').'" href="'.sprintf($format, ($current-1)).'">'.e("Previous").'</a>' : null;
    $next_page = self::$paginate['hasnext'] ? '<a class="btn btn-primary btn-sm'.($class? ' '.$class:'').' ms-auto" href="'.sprintf($format, ($current+1)).'">'.e("Next").'</a>' : null;

    return $previous_page.$next_page;
  }    
  /**
  * Generated CSRF Token
  * @param none
  * @return token
  * @since 1.0
  */   
  public static function CSRF($form = true){
    
    $_SESSION[self::CSRFNAME] = isset($_SESSION[self::CSRFNAME]) && !empty($_SESSION[self::CSRFNAME]) ?  $_SESSION[self::CSRFNAME] : self::Encode("csrf_token".rand(0,1000000).time().uniqid(), "sha1");

    if($form) return "<input type=\"hidden\" name=\"_token\" value=\"{$_SESSION[self::CSRFNAME]}\" />\n";

    return $_SESSION[self::CSRFNAME];
  }

  /**
  * Encode string
  * @param string
  * @return hash
  */   
  public static function Encode($string, $encoding = "bcrypt"){      
    
    $encoding = strtolower($encoding);

    return password_hash($string.AuthToken, PASSWORD_BCRYPT, ['cost' => self::$hashCost]);
  }
  /**
  * Check Password
  * @param $string
  * @param $hash
  * @return boolean
  */   
  public static function validatePass($string, $hash){      
    return password_verify($string.AuthToken, $hash);
  }  
  /**
  * Validate and sanitize email
  * @param string
  * @return email
  */  
  public static function Email($email){
      $email=trim($email);
      if (preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{2,})+$/i', $email) && strlen($email)<=50 && filter_var($email, FILTER_SANITIZE_EMAIL)){
          return filter_var($email, FILTER_SANITIZE_EMAIL);
      }
      return false;
  } 
  /**
   * Validate URL
   *
   * @author Xsantana <https://piliruma.co.id> 
   * @version 6.0
   * @param $url
   * @return boolean
   */
  public static function isURL($url){
      if (preg_match("#(?i)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))#", $url)){
        return true;
      }    
      return false;
  }
  /**
   * Validate username
   *
   * @author Xsantana <https://piliruma.co.id> 
   * @version 6.0
   * @param $user
   * @return void
   */
  public static function username($user){
    if(preg_match('/^\w{4,}$/', $user) && strlen($user)<=20 && filter_var($user,FILTER_SANITIZE_STRING)) {
      return filter_var(trim($user),FILTER_SANITIZE_STRING);
    }
    return false;    
  }
  /**
   * Output Countries
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @param   string $code
   */
  public static function Country($code="", $asForm = false, $reverse = false){

    $countries = ["AD"  => "Andorra","AF"  => "Afghanistan","AG" => "Antigua and Barbuda","AI" => "Anguilla","AL"  => "Albania","AM" => "Armenia","AN" => "Netherlands Antilles","AO"  => "Angola","AQ"  => "Antarctica","AR"  => "Argentina","AS" => "American Samoa","AT"  => "Austria","AU" => "Australia","AW" => "Aruba","AX" => "Åland","AZ" => "Azerbaijan","BA"  => "Bosnia and Herzegovina","BB"  => "Barbados","BD"  => "Bangladesh","BE"  => "Belgium","BF" => "Burkina Faso","BG"  => "Bulgaria","BH"  => "Bahrain","BI" => "Burundi","BJ" => "Benin","BL" => "Saint Barthélemy","BM"  => "Bermuda","BN" => "Brunei","BO"  => "Bolivia","BQ"   => "Bonaire, Sint Eustatius and Saba","BR"  => "Brazil","BS"  => "Bahamas","BT" => "Bhutan","BV"  => "Bouvet Island","BW" => "Botswana","BY"  => "Belarus","BZ" => "Belize","CA"  => "Canada","CC"  => "Cocos [Keeling] Islands","CD" => "DR Congo","CF"  => "Central African Republic","CG"  => "Congo Republic","CH"  => "Switzerland","CI" => "Ivory Coast","CK" => "Cook Islands","CL"  => "Chile","CM" => "Cameroon","CN"  => "China","CO" => "Colombia","CR"  => "Costa Rica","CS"  => "Serbia and Montenegro","CU" => "Cuba","CV"  => "Cabo Verde","CW"  => "Curaçao","CX" => "Christmas Island","CY"  => "Cyprus","CZ"  => "Czechia","DE" => "Germany","DJ" => "Djibouti","DK"  => "Denmark","DM" => "Dominica","DO"  => "Dominican Republic","DZ"  => "Algeria","EC" => "Ecuador","EE" => "Estonia","EG" => "Egypt","EH" => "Western Sahara","ER"  => "Eritrea","ES" => "Spain","ET" => "Ethiopia","FI"  => "Finland","FJ" => "Fiji","FK"  => "Falkland Islands","FM"  => "Micronesia","FO"  => "Faroe Islands","FR" => "France","GA"  => "Gabon","GB" => "United Kingdom","GD"  => "Grenada","GE" => "Georgia","GF" => "French Guiana","GG" => "Guernsey","GH"  => "Ghana","GI" => "Gibraltar","GL" => "Greenland","GM" => "Gambia","GN"  => "Guinea","GP"  => "Guadeloupe","GQ"  => "Equatorial Guinea","GR" => "Greece","GS"  => "South Georgia and South Sandwich Islands","GT"  => "Guatemala","GU" => "Guam","GW"  => "Guinea-Bissau","GY" => "Guyana","HK"  => "Hong Kong","HM" => "Heard Island and McDonald Islands","HN" => "Honduras","HR"  => "Croatia","HT" => "Haiti","HU" => "Hungary","ID" => "Indonesia","IE" => "Ireland","IL" => "Israel","IM"  => "Isle of Man","IN" => "India","IO" => "British Indian Ocean Territory","IQ"  => "Iraq","IR"  => "Iran","IS"  => "Iceland","IT" => "Italy","JE" => "Jersey","JM"  => "Jamaica","JO" => "Jordan","JP"  => "Japan","KE" => "Kenya","KG" => "Kyrgyzstan","KH"  => "Cambodia","KI"  => "Kiribati","KM"  => "Comoros","KN" => "St Kitts and Nevis","KP"  => "North Korea","KR" => "South Korea","KW" => "Kuwait","KY"  => "Cayman Islands","KZ"  => "Kazakhstan","LA"  => "Laos","LB"  => "Lebanon","LC" => "Saint Lucia","LI" => "Liechtenstein","LK" => "Sri Lanka","LR" => "Liberia","LS" => "Lesotho","LT" => "Lithuania","LU" => "Luxembourg","LV"  => "Latvia","LY"  => "Libya","MA" => "Morocco","MC" => "Monaco","MD"  => "Moldova","ME" => "Montenegro","MF"  => "Saint Martin","MG"  => "Madagascar","MH"  => "Marshall Islands","MK"  => "North Macedonia","ML" => "Mali","MM"  => "Myanmar","MN" => "Mongolia","MO"  => "Macao","MP" => "Northern Mariana Islands","MQ"  => "Martinique","MR"  => "Mauritania","MS"  => "Montserrat","MT"  => "Malta","MU" => "Mauritius","MV" => "Maldives","MW"  => "Malawi","MX"  => "Mexico","MY"  => "Malaysia","MZ"  => "Mozambique","NA"  => "Namibia","NC" => "New Caledonia","NE" => "Niger","NF" => "Norfolk Island","NG"  => "Nigeria","NI" => "Nicaragua","NL" => "Netherlands","NO" => "Norway","NP"  => "Nepal","NR" => "Nauru","NU" => "Niue","NZ"  => "New Zealand","OM" => "Oman","PA"  => "Panama","PE"  => "Peru","PF"  => "French Polynesia","PG"  => "Papua New Guinea","PH"  => "Philippines","PK" => "Pakistan","PL"  => "Poland","PM"  => "Saint Pierre and Miquelon","PN" => "Pitcairn Islands","PR"  => "Puerto Rico","PS" => "Palestine","PT" => "Portugal","PW"  => "Palau","PY" => "Paraguay","QA"  => "Qatar","RE" => "Réunion","RO" => "Romania","RS" => "Serbia","RU"  => "Russia","RW"  => "Rwanda","SA"  => "Saudi Arabia","SB"  => "Solomon Islands","SC" => "Seychelles","SD"  => "Sudan","SE" => "Sweden","SG"  => "Singapore","SH" => "Saint Helena","SI"  => "Slovenia","SJ"  => "Svalbard and Jan Mayen","SK"  => "Slovakia","SL"  => "Sierra Leone","SM"  => "San Marino","SN"  => "Senegal","SO" => "Somalia","SR" => "Suriname","SS"  => "South Sudan","ST" => "São Tomé and Príncipe","SV" => "El Salvador","SX" => "Sint Maarten","SY"  => "Syria","SZ" => "Eswatini","TC"  => "Turks and Caicos Islands","TD"  => "Chad","TF"  => "French Southern Territories","TG" => "Togo","TH"  => "Thailand","TJ"  => "Tajikistan","TK"  => "Tokelau","TL" => "Timor-Leste","TM" => "Turkmenistan","TN"  => "Tunisia","TO" => "Tonga","TR" => "Turkey","TT"  => "Trinidad and Tobago","TV" => "Tuvalu","TW"  => "Taiwan","TZ"  => "Tanzania","AE" => "United Arab Emirates","UA"  => "Ukraine","UG" => "Uganda","UM"  => "U.S. Minor Outlying Islands","US" => "United States","UY" => "Uruguay","UZ" => "Uzbekistan","VA"  => "Vatican City","VC"  => "St Vincent and Grenadines","VE" => "Venezuela","VG" => "British Virgin Islands","VI"  => "U.S. Virgin Islands","VN" => "Vietnam","VU" => "Vanuatu","WF" => "Wallis and Futuna","WS" => "Samoa","XK" => "Kosovo","YE"  => "Yemen","YT" => "Mayotte","ZA" => "South Africa","ZM"  => "Zambia","ZW"  => "Zimbabwe"];

    if($asForm){
      $form="";
      foreach ($countries as $key => $value) {
        $form.='<option value="'.$value.'"'.($code == $value ? ' selected':'').'>'.$value.'</option>';
      }
      return $form;
    }

    if($reverse){
      $countries = array_flip($countries);
      $code = ucwords($code);
      $code = str_replace('And', 'and', $code);
      return isset($countries[$code]) ? $countries[$code] : false;  
    }

    if($code !== false){
      $code = strtoupper($code);
      return isset($countries[$code]) ? $countries[$code] : false;
    }

    if($code !== false && empty($code)) return '';

    return $countries;
  }   
  /**
   * List of devices
   *
   * @author Xsantana <https://piliruma.co.id> 
   * @version 6.0
   * @return void
   */
  public static function devices($code = null){
      $os = [
        'windows'            =>  'Windows',
        'mac'                =>  'Mac OS',
        'linux'              =>  'Linux',
        'ubuntu'             =>  'Ubuntu',
        'iphone'             =>  'iPhone',
        'ipad'               =>  'iPad',
        'android'            =>  'Android',
        'blackberry'         =>  'BlackBerry',
        'webos'              =>  'Other mobile'
    ];
    $form = "";
    foreach ($os as $key => $value) {
      $form .= '<option value="'.$value.'"'.($code == strtolower($value)?' selected':'').'>'.$value.'</option>';
    }
    return $form;   
  }
  /**
   * Generate Random
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @param   integer $length
   * @param   string  $api   
   * @return     mixed
   */
  public static function rand($length = 12, $api = ""){    
      $use = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
      srand((double)microtime()*1000000); 
      for($i=0; $i<$length; $i++) { 
        $api.= $use[rand()%strlen($use)]; 
      } 
    return $api; 
  }  
  /**
   * Return extension of a file
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @param   string $input
   * @return  string extenstion of the input
   */
  public static function extension(string $input) : string {
    $ext = strrchr($input, "."); 
    $next = explode("?", $ext);
    return trim($next[0], ".");
  }
  /**
   * Return Cache Instance
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @return 
   */
  public static function cacheInstance(){
    return self::$cacheInstance;
  }
  /**
   * Cache Configuration
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @param   string|null $path
   * @return       mixed
   */
  public static function cacheConfig(?string $path = null){
    if($path){
      CacheManager::setDefaultConfig(new ConfigurationOption([
          'path' => $path,
      ]));
    }

    self::$cacheInstance = CacheManager::getInstance(self::CACHEDRIVER);
  }
  /**
   * Get Cache
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @param   string $key
   * @return  void
   */
  public static function cacheGet(string $key){

    if(CACHE !== true) return null;

    $cache = self::$cacheInstance->getItem($key);
    if($cache->isHit()){
      return $cache->get();
    }
    return null;
  }
  /**
   * Set Cache
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @param   string $key 
   * @param   $data
   * @return  mixed
   */
  public static function cacheSet(string $key, $data, int $expiry = 3600){
    
    if(CACHE !== true) return $data;
    
    $cache = self::$cacheInstance->getItem($key);
    $cache->set($data)->expiresAfter($expiry);
    self::$cacheInstance->save($cache);

    return true;
  }
  /**
   * Update cache without changing expiration
   *
   * @author Xsantana <https://piliruma.co.id> 
   * @version 6.0
   * @param string $key
   * @param $data
   * @return mixed
   */
  public static function cacheUpdate(string $key, $data){
    if(CACHE !== true) return $data;
    
    $cache = self::$cacheInstance->getItem($key);
    if($cache->isHit()){
      $expiry = $cache->getExpirationDate();
      $cache->set($data)->expiresAt($expiry);
      self::$cacheInstance->save($cache);
      return $expiry;
    }

    return false;
  }
  /**
   * Get cache expiry
   *
   * @author Xsantana <https://piliruma.co.id> 
   * @version 6.2
   * @param $key
   * @return boolean
   */
  public static function cacheExpiry($key){
      if(CACHE !== true) return false;
      
      $cache = self::$cacheInstance->getItem($key);
      if($cache->isHit()){
        return $cache->getExpirationDate();
      }
  
      return false;
  }
  /**
   * [cacheDelete description]
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @param   string $key
   * @return  void
   */
  public static function cacheDelete(string $key){
    if(self::$cacheInstance->getItem($key)){
      self::$cacheInstance->deleteItem($key);
    }
  }
  /**
   * Clear all cache
   * @author Xsantana <https://piliruma.co.id>
   * @version 1.0
   * @return 
   */
  public static function cacheClear(){
    self::$cacheInstance->clear();
  }

  /**
   * Encrypt Data
   *
   * @author Xsantana <https://piliruma.co.id> 
   * @version 1.0
   * @param $data
   * @return void
   */
  public static function encrypt($data){
    try {
      $key = \Defuse\Crypto\Key::loadFromAsciiSafeString(EncryptionToken);
      return \Defuse\Crypto\Crypto::encrypt($data, $key);
    } catch(\Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $e){
      GemError::log($e->getMessage());
      return $data;
    }
  }
  /**
   * Decrypt Data
   *
   * @author Xsantana <https://piliruma.co.id> 
   * @version 1.0
   * @param $data
   * @return void
   */
  public static function decrypt($data){
    try {
      $key = \Defuse\Crypto\Key::loadFromAsciiSafeString(EncryptionToken);
      return \Defuse\Crypto\Crypto::decrypt($data, $key);
    } catch(\Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $e){
      GemError::log($e->getMessage());
      return $data;
    }
  }
  /**
   * If empty
   *
   * @author Xsantana <https://piliruma.co.id> 
   * @version 6.0
   * @param $variable
   * @param $default
   * @return void
   */
  public static function empty($variable, $default){
    return empty($variable) ? $default : $variable;
  }
  /**
   * Create a unique Nonce Token
   *
   * @author Xsantana <https://piliruma.co.id> 
   * @version 6.0
   * @param string $action
   * @param string $key
   * @return void
   */
  public static function nonce($action = '', $duration = 60){
    $i = ceil( time() / ( $duration*60 / 2 ) );
    $nonce = md5( $i . $action . $action);
    return substr($nonce, -12, 10);
  }
  /**
   * Validate Nonce
   *
   * @author Xsantana <https://piliruma.co.id> 
   * @version 6.0
   * @param string $action
   * @param string $key
   * @return void
   */
  public static function validateNonce($nonce, $action = ""){
    if(substr(self::nonce($action), -12, 10) == $nonce){
      return true;
    }
    return false;
  } 
  /**
   * Parse URL
   *
   * @author Xsantana <https://piliruma.co.id> 
   * @version 6.0
   * @param string $url
   * @param $selector
   * @return void
   */
  public static function parseUrl(string $url, $selector = null){
    
    $list = ["PHP_URL_SCHEME","PHP_URL_HOST","PHP_URL_PORT","PHP_URL_USER","PHP_URL_PASS","PHP_URL_PATH","PHP_URL_QUERY","PHP_URL_FRAGMENT"];

    $selector = "PHP_URL_".strtoupper($selector);

    $component = in_array($selector, $list) ? constant($selector) : -1;
    
    return parse_url($url, $component);    
  }
}