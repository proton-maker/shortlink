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

use \Core\Helper;
use \Core\View;
use \Core\DB;
use \Core\Auth;
use \Core\Request;

class Export {     
    /**
     * Check if user can export
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     */
    public function __construct(){
        if(\Models\User::where('id', Auth::user()->rID())->first()->has('export') === false){
			return \Models\Plans::notAllowed();
		}
    }
    /**
     * Export Data
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @return void
     */
    public function links(Request $request){      

        if(Auth::user()->teamPermission('export') == false){
			return Helper::redirect()->to(route('dashboard'))->with('danger', e('You do not have this permission. Please contact your team administrator.'));
		}  

        $links = DB::url()
                    ->where('userid', Auth::user()->id)
                    ->whereNull('qrid')
                    ->whereNull('profileid')
                    ->orderByDesc('date')
                    ->findArray();

        return \Core\File::contentDownload('MyLinks-'.date('d-m-Y').'.csv', function() use ($links) {

			echo "Short URL,Long URL,Campaign,Date,Clicks,Unique Clicks\n";
            foreach($links as $url){
                $name = null;
                if($url['bundle']){
                    if($campaign = DB::bundle()->first($url['bundle'])){
                        $name = $campaign->name;
                    }
                }
                echo ($url['domain'] ? $url['domain'] : config('url'))."/".$url['alias'].$url['custom'].",\"{$url['url']}\",{$name},{$url['date']},{$url['click']},{$url['uniqueclick']}\n";
            }
		});
    }
    /**
     * Export Single Stats
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param integer $id
     * @return void
     */
    public function single(int $id){

        if(Auth::user()->teamPermission('export') == false){
			return Helper::redirect()->to(route('dashboard'))->with('danger', e('You do not have this permission. Please contact your team administrator.'));
		}  

        if(!$url = DB::url()->select('alias')->select('custom')->select('domain')->where('id', $id)->first()) {
            return Helper::redirect()->back()->with('danger', e('Link does not exist.'));
        }

        $stats = DB::stats()->where("urluserid", Auth::user()->rID())->where('urlid', $id)->orderByDesc('date')->findArray();

        $content = "Short URL,Date,City,Country,Browser,Platform,Language,Domain,Referer\n";
        
        foreach($stats as $data){

            $content .= ($url->domain ? $url->domain : config('url'))."/".$url->alias.$url->custom.",{$data['date']},{$data['city']},{$data['country']},{$data['browser']},{$data['os']},{$data['language']},{$data['domain']},{$data['referer']}\n";
        }

        $response = new \Core\Response($content, 200, ['content-type' => 'text/csv', 'content-disposition' => 'attachment;filename=ReportLink_'.Helper::dtime('now', 'd-m-Y').'.csv']);
        
        return $response->send();
    }

    /**
     * Export Campaign Stats
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @param integer $id
     * @return void
     */
    public function stats(Request $request){

        if(Auth::user()->teamPermission('export') == false){
			return Helper::redirect()->to(route('dashboard'))->with('danger', e('You do not have this permission. Please contact your team administrator.'));
		}  

        
        if(!$request->customreport) return Helper::redirect()->back()->with('danger', e('Please specify a range.')); 

        $range = explode(' - ', $request->customreport);       

        $stats = DB::stats()->where("urluserid", Auth::user()->rID())->orderByDesc('date')->findArray();

        $content = "Short URL,Date,City,Country,Browser,Platform,Language,Domain,Referer\n";
        
        foreach($stats as $data){

            if(!$url = DB::url()->select('alias')->select('custom')->select('domain')->where('id', $data['urlid'])->first()) continue;

            $content .= ($url->domain ? $url->domain : config('url'))."/".$url->alias.$url->custom.",{$data['date']},{$data['city']},{$data['country']},{$data['browser']},{$data['os']},{$data['language']},{$data['domain']},{$data['referer']}\n";
        }

        $response = new \Core\Response($content, 200, ['content-type' => 'text/csv', 'content-disposition' => 'attachment;filename=ReportAll_'.Helper::dtime('now', 'd-m-Y').'.csv']);
        
        return $response->send();
    }  
    /**
     * Export Campaign Stats
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @param integer $id
     * @return void
     */
    public function campaign(Request $request, int $id){

        if(Auth::user()->teamPermission('export') == false){
			return Helper::redirect()->to(route('dashboard'))->with('danger', e('You do not have this permission. Please contact your team administrator.'));
		}  

        
        if(!\Core\Auth::user()->has('export')){
            return \Models\Plans::notAllowed();
        }

        if(!$request->customreport) return Helper::redirect()->back()->with('danger', e('Please specify a range.')); 

        $range = explode(' - ', $request->customreport);

        if(!$bundle = DB::bundle()->where('id', $id)->where('userid', Auth::user()->rID())->first()){
            return Response::factory('', 404)->json();
        }

        $urls = DB::url()->select('id', 'urlid')->where('bundle', $bundle->id)->findArray();

        $stats = DB::stats()->where("urluserid", Auth::user()->rID())->whereAnyIs($urls)->orderByDesc('date')->findArray();

        $content = "Short URL,Date,City,Country,Browser,Platform,Language,Domain,Referer\n";
        
        foreach($stats as $data){
            if(!$url = DB::url()->select('alias')->select('custom')->select('domain')->where('id', $data['urlid'])->first()) continue;

            $content .= ($url->domain ? $url->domain : config('url'))."/".$url->alias.$url->custom.",{$data['date']},{$data['city']},{$data['country']},{$data['browser']},{$data['os']},{$data['language']},{$data['domain']},{$data['referer']}\n";
        }

        $response = new \Core\Response($content, 200, ['content-type' => 'text/csv', 'content-disposition' => 'attachment;filename=ReportCampaign_'.Helper::dtime('now', 'd-m-Y').'.csv']);
        
        return $response->send();
    }
}