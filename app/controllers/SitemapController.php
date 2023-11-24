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

use Core\View;
use Core\File;
use Core\Helper;
use Core\Request;
use Core\Response;
use Core\Localization;
use Core\DB;

class Sitemap {

	/**
	 * Number of links to display
	 *
	 * @author Xsantana 
	 * @version 6.3
	 */
	private $numlinks = 50;

	/**
	 * Track count
	 *
	 * @author Xsantana 
	 * @version 6.3
	 */
    protected $count = 0;

   /**
     * Generate Site map
     *
     * @author Xsantana 
     * @version 6.0
     * @return void
     */
    public function index(){
            
        $this->setHeader();

		echo $this->url(route('home'), date("c", filemtime(View::$path."/index.php")), 1);

		foreach(DB::page()->findArray() as $page){
            echo $this->url(route('page', [$page['seo']]),  date("c", strtotime($page['lastupdated'])), 0.6);
        }

        foreach(DB::posts()->where('published', 1)->findArray() as $post){
            echo $this->url(route('blog.post', [$post['slug']]),  date("c", strtotime($post['date'])), 0.8);
        }

        if($faq = DB::faqs()->orderByDesc('created_at')->first()){
			echo $this->url(route('faq'),  date("c", strtotime($faq->created_at)));
		} 

		if(config('contact')){
			echo $this->url(route('contact'),  date("c", strtotime('2021-11-21 00:00:00')));
		}
		
		if(config('report')){
			echo $this->url(route('report'),  date("c", strtotime('2021-11-21 00:00:00')));
		}        
        
		if(config('api')) {
        	echo $this->url(route('apidocs'),  date("c", strtotime('2021-11-21 00:00:00')));
		}

        foreach(DB::url()->where('public', '1')->limit($this->numlinks)->orderByDesc('date')->findArray() as $url){
            echo $this->url(\Helpers\App::shortRoute($url['domain'], $url['alias'].$url['custom']),  date("c", strtotime($url['date'])), 0.5);
        }
		
		$this->setFooter();
    }

    /**
	 * [setHeader description]
	 * @author KBRmedia <https://gempixel.com>
	 * @version 1.0
	 */
	protected function setHeader(){
		header('Content-type: application/xml');
		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\nhttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">";
	}
	/**
	 * [setFooter description]
	 * @author KBRmedia <https://gempixel.com>
	 * @version 1.0
	 */
	protected function setFooter(){
		echo "\n<!-- Generated {$this->count} urls -->\n</urlset>";
	}
	/**
	 * [url description]
	 * @author KBRmedia <https://gempixel.com>
	 * @version 1.0
	 * @param   [type] $loc      [description]
	 * @param   [type] $lastmod  [description]
	 * @param   [type] $priority [description]
	 * @return  [type]           [description]
	 */
	protected function url($loc, $lastmod, $priority = 1){
		$this->count++;
		$lastmod = date("c", strtotime($lastmod));
		return "\n\t<url>\n\t\t<loc>$loc</loc>\n\t\t<lastmod>$lastmod</lastmod>\n\t\t<priority>$priority</priority>\n\t</url>";
	}        
}