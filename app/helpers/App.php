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
use Core\Helper;

final class App {

    /**
     * Custom Pages Link
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function pages($category = null){
        
        $query = DB::page()->where('menu', 1);

        if($category){
            $query->where('category', $category);
        }
        return $query->find();
    }
    /**
     * Get pricing faqs
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function pricingFaqs(){
        return DB::faqs()->where('pricing', 1)->findMany();
    }
    /**
     * Currency
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param string $code
     * @param string $amount
     * @return void
     */
    public static function currency($code="",$amount=""){
        $array = array('AED' => array('label'=>'United Arab Emirates Dirham','format' => 'AED %s'),'AUD' => array('label'=>'Australian Dollar','format' => '$%s'),'CAD' => array('label' => 'Canadian Dollar','format' => '$%s'),'EUR' => array('label' => 'Euro','format' => '€ %s'),'GBP' => array('label' => 'Pound Sterling','format' => '£ %s'),'JPY' => array('label' => 'Japanese Yen','format' => '¥ %s'),'USD' => array('label' => 'U.S. Dollar','format' => '$%s'),'NZD' => array('label' => 'N.Z. Dollar','format' => '$%s'),'CHF' => array('label' => 'Swiss Franc','format' => '%s Fr'),'HKD' => array('label' => 'Hong Kong Dollar','format' => '$%s'),'SGD' => array('label' => 'Singapore Dollar','format' => '$%s'),'SEK' => array('label' => 'Swedish Krona','format' => '%s kr'),'DKK' => array('label' => 'Danish Krone','format' => '%s kr'),'PLN' => array('label' => 'Polish Zloty','format' => '%s zł'),'NOK' => array('label' => 'Norwegian Krone','format' => '%s kr'),'HUF' => array('label' => 'Hungarian Forint','format' => '%s Ft'),'CZK' => array('label' => 'Czech Koruna','format' => '%s Kč'),'ILS' => array('label' => 'Israeli New Sheqel','format' => '₪ %s'),'MXN' => array('label' => 'Mexican Peso','format' => '$%s'),'BRL' => array('label' => 'Brazilian Real','format' => 'R$%s'),'MYR' => array('label' => 'Malaysian Ringgit','format' => 'RM %s'),'PHP' => array('label' => 'Philippine Peso','format' => '₱ %s'),'TWD' => array('label' => 'New Taiwan Dollar','format' => 'NT$%s'),'THB' => array('label' => 'Thai Baht','format' => '฿ %s'),'TRY' => array('label' => 'Turkish Lira','format' => 'TRY %s'), 'INR' => array('label' => 'Indian Rupee','format' => '₹ %s'),);
        if(empty($code)) return $array;
        
        $code=strtoupper($code);
        if(isset($array[$code])) return sprintf($array[$code]["format"],$amount);
    } 
    /**
     * Return Timezones
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function timezone(){
        $tzs = [];

        foreach(\DateTimeZone::listIdentifiers() as $tz){
            $tzs[] = $tz;
        }
        for($i = 12; $i > -13; $i--){
            if($i >=0) $i = "+".$i;
            $tzs[] = 'Etc/GMT'.$i;
        }

        return $tzs;
    }
    /**
     * Get List of Languages
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function languages(){
        
        $languages = [];

        foreach (new \RecursiveDirectoryIterator(LOCALE) as $path){
            if($path->getFilename() == "." || $path->getFilename() == ".." || $path->getFilename() == "index.php") continue;

            if(!\file_exists(LOCALE.'/'.$path->getFilename().'/app.php')) continue;

            $language = include LOCALE.'/'.$path->getFilename().'/app.php';
            $languages[$language['code']] = ['name' => $language['name'], 'author' => $language['author']];
        }

        return $languages;
    }
    /**
     * Check if user has an extended license 
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return boolean
     */
    public static function isExtended(){

        $config = config();

        try{
            DB::subscription()->first();
            return true;
        } catch(\Exception $e){
            return false;
        }

        if(!config('purchasecode')) return false;

        $response = \Core\Http::url("https://cdn.gempixel.com/validator/")
                                ->with('X-Authorization', 'TOKEN '.md5(url()))
                                ->body(['url' => url(), 'key' => config('purchasecode')])
                                ->post()
                                ->getBody();

        if(!$response || empty($response) || $response == "Failed"){
            return false;
        }elseif($response == "Wrong.Item"){
            return false;
        }elseif($response == "Wrong.License"){
            return false;
        }

        return true;
    }
    /**
     * Ad Type
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param [type] $type
     * @param boolean $format
     * @return void
     */
    public static function adType($type = null, $format = false){
		$types = array(
            "728" => array("name" => "728x90", "format" => "primary"),
            "300" =>  array("name" => "300x250","format" => "danger"),
            "468" =>  array("name" => "468x60", "format" => "info"),
            "resp" =>  array("name" => "Responsive", "format" => "warning"),
            "frame" =>  array("name" => "Frame Page", "format" => "success"),
            "splash" =>  array("name" => "Splash Page", "format" => "success"),
        );
		
        if(isset($types[$type])) return $types[$type]["name"];

		if($format){
			return "<span class='label label-{$types[$type]["format"]}'>{$types[$type]["name"]}</span>";
		}

		return $types;
	} 
    /**
     * Page Category
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function pageCategories($name = null){
        $list = [
            'main' => 'Main',
            'policy' => 'Policy',
            'terms' => 'Terms',
            'others' => 'Others',            
        ]; 
        
        if(isset($list[$name])) return $list[$name];

        return $list;
    }
    /**
     * Generate Short Link 
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param [type] $domain
     * @param [type] $alias
     * @return void
     */
    public static function shortRoute($domain, $alias){
        if(!$domain || empty($domain)) $domain = config('url');
        return $domain.'/'.$alias;
    }
    /**
     * Copy Folder
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param [type] $source
     * @param [type] $destination
     * @return void
     */
    public static function copyFolder($source, $destination){
        $directory = opendir($source); 
        @mkdir($destination); 
        while(false !== ( $file = readdir($directory)) ) { 
          if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($source . '/' . $file) ) { 
             self::copyFolder($source . '/' . $file, $destination . '/' . $file); 
            } 
            else { 
              copy($source . '/' . $file, $destination . '/' . $file); 
            } 
          } 
        } 
        closedir($directory);
    }
    /**
     * Delete Folder
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param [type] $target
     * @return void
     */
    public static function deleteFolder($target){
        if(is_dir($target)){
            $files = glob( $target . '*', GLOB_MARK ); 
            foreach($files as $file){
                self::deleteFolder($file);      
            }
            if(is_dir($target)) rmdir($target);
        } elseif(is_file($target)) {
            unlink($target);  
        }
    }

    /**
     * Update Notification  
     * @since 6.0
     */
    public static function newUpdate($version = false){

        $request = \Core\Http::url("https://cdn.gempixel.com/updater/index.php?p=".md5('shortener'))->get();
    
        $data = $request->bodyObject();

        if(isset($data->status) && $data->status == "ok"){
            if(config('version') < $data->current_version){
                if($version == true){
                    return $data->current_version;
                }else{
                    return "<div class='custom-alert alert-success'>This script has been updated to version {$data->current_version}. You can run the <a href='".route("admin.update")."' class='button green' style='color:#fff'><u>automatic updater</u></a> or you can download it from <a href='http://codecanyon.net/downloads' target='_blank' class='button green' style='color:#fff'><u>CodeCanyon</u></a> and manually update it.</div>";
                }
            }
        }
        return false;
    }
    /**
     * Get Changelog
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function updateChangelog(){
        return \Core\Http::url("https://piliruma.co.id/changelog/premium-url-shortener?integrity=".md5('shortener'))->get()->bodyObject();
    }
    /**
     * Default Plan for admin
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.2.2
     * @return void
     */
    public static function defaultPlan(){

        if($pluggedplan = \Core\Plugin::dispatch('defaultplan')) return $pluggedplan;

        $plan = [];
        $plan['name'] = "Default";
        $plan['free'] = "0";
        $plan['numurls'] = "0";
        $plan['numclicks'] = "0";
        $plan['retention'] = "0";
        $plan['permission'] = [
            "profile"   => ["enabled" => true],
            "splash"    => ["enabled" => true,"count" => 0],
            "overlay"   => ["enabled" => true,"count" => 0],
            "domain"    => ["enabled" => true,"count" => 0],	
            "team"      => ["enabled" => true,"count" => 0],														
            "pixels"    => ["enabled" => true, "count" => 0],	
            "geo"       => ["enabled" => true],
            "device"    => ["enabled" => true],	
            "bundle"    => ["enabled" => true],															
            "api"       => ["enabled" => true],
            "export"    => ["enabled" => true],
            "parameters"=> ["enabled" => true],	
            "alias"     => ["enabled" => true],	
            "multiple"  => ["enabled" => true],
            "qr"        => ["enabled" => true, "count" => 0],
            "bio"       => ["enabled" => true, "count" => 0],
            "custom"    => null
        ];

        if($features = plug('feature')){
            foreach($features as $feature){
                $plan['permission'][$feature['slug']] = $feature['count'] ? ["enabled" => true, "count" => 0] : ['enabled' => true];
            }
        }

        $plan['permission'] = json_encode($plan['permission']);
        
        return $plan;
    }
    /**
     * Check DNS and make sure both domains have the same IP
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param string $d1
     * @param string $d2
     * @return boolean
     */
    public static function checkDNS(string $d1, string $d2){
        
        $d1 = str_replace(['http://', 'https://'], '', $d1);
        $d2 = str_replace(['http://', 'https://'], '', $d2);
        
        $d2 = idn_to_ascii($d2);

        return gethostbyname($d1) == gethostbyname($d2) ? true : false;
    }    
    /**
     * Notifications
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function notifications(){
        
        $list = [];
        $list['total'] = 0;
        
        $reports = DB::reports()->where('status', 0)->limit(5)->find();
        $count = count($reports);
        $list['total'] += $count;        

        $html = "";

        $list['data']['reports'] = [
            'count' => $count,
            'list' => $reports
        ];

        $pending = DB::url()->where('status', 0)->limit(5)->find();
        $count = count($pending);
        $list['total'] += $count;

        $list['data']['pending'] = [
            'count' => $count,
            'list' => $pending
        ];

        return $list;
    }
    /**
     * Display Ads
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param mixed $size
     * @return void
     */
    public static function ads($size){
        if(config('ads')){
            $user = user();
            if($user && $user->pro()) return;
            if(!$ad = DB::ads()->where("type", $size)->where("enabled", "1")->orderByExpr('RAND()')->first()) return;  
            $ad->impression++;
            $ad->save();    
            return print("<div class='a-block a--{$size} mt-2 mb-4'>{$ad->code}</div>");
        }

		return;
    }
    /**
     * Detect bots
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function bot(){        
        $CrawlerDetect = new \Jaybizzle\CrawlerDetect\CrawlerDetect;
        if($CrawlerDetect->isCrawler()) {
          return true;
        }   
        return false;
    }
    /**
     * Flag SVG
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param [type] $country
     * @return void
     */
    public static function flag($country){

        if(empty($country)) return assets('images/unknown.svg');
        return assets('images/flags/'.strtolower(Helper::Country($country, false, true)).'.svg');
    }
    /**
     * OS SVG
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param [type] $os
     * @return void
     */
    public static function os($os){
        
        $cos = strtolower(explode(' ', $os)[0]);

        if(empty($cos) || $cos == "unknown") return assets('images/unknown.svg');

        if(in_array($cos, ['iphone', 'ipod', 'ipad'])) $cos = 'mac';

        return assets('images/os/'.$cos.'.svg');
    }
    /**
     * Browser SVG
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param [type] $browser
     * @return void
     */
    public static function browser($browser){
        
        $cos = strtolower(explode(' ', $browser)[0]);

        if(empty($cos) || $cos == "unknown") return assets('images/unknown.svg');

        if(in_array($cos, ['Internet Explorer'])) $cos = 'ie';

        return assets('images/browsers/'.$cos.'.svg');
    }
    /**
     * iFrame Policy
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param [type] $url
     * @return void
     */
    public static function iframePolicy($url){
        
        if(!\Core\Helper::isURL($url)) return false;

        $url_headers = get_headers($url);
        foreach ($url_headers as $key => $value){
            $x_frame_options_deny = strpos(strtolower($url_headers[$key]), strtolower('X-Frame-Options: DENY'));
            $x_frame_options_sameorigin = strpos(strtolower($url_headers[$key]), strtolower('X-Frame-Options: SAMEORIGIN'));
            $x_frame_options_allow_from = strpos(strtolower($url_headers[$key]), strtolower('X-Frame-Options: ALLOW-FROM'));
            if ($x_frame_options_deny !== false || $x_frame_options_sameorigin !== false || $x_frame_options_allow_from !== false){
                return true;
            }
        }
        return false;
    }
    /**
     * Get metadata
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param [type] $url
     * @param boolean $checkHeader
     * @return void
     */
    public static function metaData($url, $checkHeader = false){

        if(!\Core\Helper::isURL($url)) return false;
        
        $array = array('title' => '','description' => '');

        $request = \Core\Http::url($url)
                        ->with('user-agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.182 Safari/537.36')
                        ->get(['timeout' => 2]);

        $contentType = $request->response('content_type');

        if(!preg_match("~text/html~", strtolower($contentType))){
            return $array;         
        }

        $charsetString = explode(';', $contentType);
        $charset = null;
        if(isset($charsetString[1])){
            $charset = str_replace('charset=', '', strtolower(trim($charsetString[1])));
        }
        
        if($content = $request->getBody()){
            $pattern = "#<[\s]*title[\s]*>([^<]+)<[\s]*/[\s]*title[\s]*>#Ui";            
            if(preg_match($pattern, $content, $match)){
                $array['title'] = $charset && $charset != 'utf-8' ? \mb_convert_encoding($match[1], 'utf-8', $charset) : $match[1];
            }

            $pattern = "#<[\s]*meta[\s]*name=(?:'|\")description(?:'|\")[\s]*content=(?:'|\")([^<]+)(?:'|\")[\s]*[-/]?>#i";
            if(preg_match($pattern, $content, $match)){
                $array['description'] =  $charset && $charset != 'utf-8' ? \mb_convert_encoding($match[1], 'utf-8', $charset) : $match[1];
            }

            if(!mb_check_encoding($array['title'], 'utf-8')){
                $array['title'] = '';
            }

            if(!mb_check_encoding($array['description'], 'utf-8')){
                $array['description'] = '';
            }

            unset($data);
            unset($content);
            unset($match);      

            return $array;
        }
        return false;
    }
    /**
     * Share Buttons
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param [type] $url
     * @param [type] $title
     * @param array $site
     * @return void
     */
    public static function share($url, $site = array()){
        
        if(!config('sharing')) return;

        $html  = "";
        $url = urlencode($url);
        if(empty($site) || in_array("facebook",$site)){
          $html .="<a href='https://www.facebook.com/sharer.php?u=$url' target='_blank' class='popup btn btn-default me-1'><span class='fab fa-facebook'></span></a>";
        }
        if(empty($site) || in_array("twitter",$site)){
          $html .="<a href='https://twitter.com/share?url=$url' target='_blank' class='popup btn btn-default me-1'><span class='fab fa-twitter'></span></a>";
        }        
        if(empty($site) || in_array("reddit",$site)){
          $html .="<a href='https://reddit.com/submit?url=$url' target='_blank' class='popup btn btn-default me-1'><span class='fab fa-reddit'></span></a>";
        }
        if(empty($site) || in_array("linkedin",$site)){
          $html .="<a href='https://www.linkedin.com/shareArticle?mini=true&url=$url' target='_blank' class='popup btn btn-default me-1'><span class='fab fa-linkedin'></span></a>";
        }
    
        return $html;
    } 
    /**
     * Format Pixel Name
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param [type] $name
     * @return void
     */
    public static function pixelName($name){
        
        $type = str_replace('pixel', '', $name);
        if($type == 'fb') $type = 'facebook';
        if($type == 'ga') $type = 'Google Analytics';
        if($type == 'gtm') $type = 'Google Tag Manager';
        if($type == 'adwords') $type = 'Google Ads';
        return $type;    
    }
    /**
     * Theme Configuration Shortcut
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param [type] $key
     * @param [type] $value
     * @param [type] $if
     * @param [type] $else
     * @return void
     */
    public static function themeConfig($key, $value, $if, $else = null){
        
        $config = config('theme_config');

        if( !isset($config->{$key}) || $config->{$key} != $value ) return $else;

        return $if;
    }   
    /**
     * Method Color
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param [type] $method
     * @return void
     */
    public static function apiMethodColor($method){
        $list  = [
            'GET' => 'primary',
            'PUT' => 'warning',
            'POST' => 'success',
            'DELETE' => 'danger'
        ];

        return $list[$method];
    }
    /**
     * Get user history
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function userHistory(){
        $uh = request()->cookie('userhistory');

        $urls = [];

        if(!$uh) return false;

        foreach(json_decode(Helper::decrypt($uh), true) as $id){
            if(!$id || !$url = DB::url()->where('id', $id)->where('userid', 0)->findArray()) continue;
            $urls[] = $url[0];
        }

        return $urls;
    }
    /**
     * Langs
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function langs(){
    
        $langs = \Core\Localization::listInfo();

        $langs[] = ['name' => 'English', 'author' => 'gempixel', 'code' => 'en'];

        if(count($langs) == 1) return null;

        $current = config('default_lang');
     
        return $langs;
    }
    /**
     * All available domains
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function domains(){

        $user = \Core\Auth::user();
        $domains = [];

        if(config('multiple_domains')){

            if($user->has("domain") !== false && $userdomains = DB::domains()->where("userid", $user->rID())->where('status','1')->findMany()){
                foreach ($userdomains as $domain) {
                    $domains[] = trim($domain->domain);
                }
            }
            if(!config('root_domain') || ($user && $user->has("multiple") !== false)){                
                foreach (explode("\n", config('domain_names')) as $domain) {
                    if(!empty($domain)) $domains[] = strtolower(trim($domain));
                }
            }				
        }
        
        if(config('root_domain')){
            $domains[] = strtolower(config('url'));
        }
        return $domains;
    }
    /**
     * Redirect Types
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function redirects(){
        
        $user = \Core\Auth::user();
        $redirects = [];

        if(config('frame') == 3 || $user->pro()){

            $redirects[e('Redirection')] = [
               "direct" => e("Direct"),
               "frame" => e("Frame"),
               "splash" => e("Splash")
            ];

        } 
        
        if($user->has('overlay') !== false){
            
            foreach(DB::overlay()->where('userid', $user->id)->find() as $overlay){
                $redirects[e('CTA Overlay')]['overlay-'.$overlay->id] = $overlay->name;
            }

        }
        if($user->has('splash') !== false){
            foreach(DB::splash()->where('userid', $user->id)->find() as $overlay){
                $redirects[e('Custom Splash')][$overlay->id] = $overlay->name;
            }

        }
        return $redirects;
    }
    /**
     * Get States
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param string $name
     * @return void
     */
    public static function states($name = 'United States'){

        $request = request();

        if($request->country) $name = $request->country;
        
        $response = \Core\Http::url('https://countriesnow.space/api/v0.1/countries/states/q?country='.strtolower($name))->get();

        if($response ){
            $states = $response->bodyObject();
            if(isset($states->data->states)) return $request->output ? \Core\Response::factory($states->data->states)->json() : $states->data->states;
        }

        return [];
    }
    /**
     * Check Encryption
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public static function checkEncryption(){
        
        if(EncryptionToken == null){
            $file = file_get_contents(ROOT.'/config.php');

            $file = str_replace("define('EncryptionToken', null)",  "define('EncryptionToken', '".\Defuse\Crypto\Key::createNewRandomKey()->saveToAsciiSafeString()."');", $file);
    
            $fh = fopen(ROOT.'/config.php', 'w') or die("Can't open config.php. Make sure it is writable.");
    
            fwrite($fh, $file);
            fclose($fh);
        }

        if(EncryptionToken == '__ENC__'){
            $file = file_get_contents(ROOT.'/config.php');

            $file = str_replace("__ENC__", \Defuse\Crypto\Key::createNewRandomKey()->saveToAsciiSafeString(), $file);
    
            $fh = fopen(ROOT.'/config.php', 'w') or die("Can't open config.php. Make sure it is writable.");
    
            fwrite($fh, $file);
            fclose($fh); 
        }
    }
    /**
     * Extract RSS
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.2
     * @param [type] $url
     * @return void
     */
    public static function rss($url){

        if(!$content = @file_get_contents($url)) return 'Invalid RSS';

        if(!$feed = new \SimpleXMLElement($content)) return 'Invalid RSS';

        $items = [];

        foreach($feed->channel->item as $item){
            $items[] = [
                'title' => $item->title ?? null,
                'link' => $item->link ?? null,
                'image' => $item->image ?? null,
                'description' => $item->description ? \Core\Helper::truncate(strip_tags($item->description), 150) : null,
                'date' => $item->pubDate ?? null
            ];
        }

        return $items;

    }
    /**
     * Get License Information
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.2.1
     * @return void
     */
    public static function license(){
        return \Core\Http::url("https://cdn.gempixel.com/verify/?detailed=true")
                            ->with('X-Authorization', 'TOKEN '.trim(config('purchasecode')))
                            ->body(['url' => url(), 'key' => trim(config('purchasecode'))])
                            ->post()
                            ->bodyObject();
    }
}