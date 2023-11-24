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

use Core\DB;
use Core\View;
use Core\Request;
use Core\Auth;
use Core\Response;
use Core\Helper;
use Core\Email;
use Models\User;

class Stats {	
    /**
     * Stats page 
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function index(){
       
        View::set('title', e('Statistics'));

        \Helpers\CDN::load("daterangepicker");

		View::push("<script>$(document).ready(function(){ 		
            $('input[name=customreport]').daterangepicker({
                locale: {
                    'applyLabel': '".e('Apply')."',
                    'cancelLabel': '".e('Cancel')."',
                    'fromLabel': '".e('From')."',
                    'toLabel': '".e('To')."',
                    'customRangeLabel': '".e('Custom')."',
                    'daysOfWeek': ['".e('Su')."','".e('Mo')."','".e('Tu')."','".e('We')."','".e('Th')."','".e('Fr')."','".e('Sa')."'],
                    'monthNames': ['".e('January')."','".e('February')."','".e('March')."','".e('April')."','".e('May')."','".e('June')."','".e('July')."','".e('August')."','".e('September')."','".e('October')."','".e('November')."','".e('December')."'],
                },
                maxDate: moment(),
                startDate: moment().subtract(14, 'days'),
                endDate: moment(),
                autoUpdateInput: true,
                ranges: {
                    '".e("Last 7 Days")."': [moment().subtract(6, 'days'), moment()],
                    '".e("Last 30 Days")."': [moment().subtract(29, 'days'), moment()],
                    '".e("This Month")."': [moment().startOf('month'), moment().endOf('month')],
                    '".e("Last Month")."': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    '".e("Last 3 Months")."': [moment().subtract(2, 'month').startOf('month'), moment()]
                }
			});
		});</script>", "custom")->tofooter();
    
        return View::with('user.stats')->extend('layouts.dashboard');
    }
    /**
     * Get Stats Links Ajax
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function statsLinks(){
        $response = ['label' => e('Links')];

        $timestamp = strtotime('now');
        for ($i = 12 ; $i >= 0; $i--) {
            $d = $i*28;
            $timestamp = \strtotime("-{$d} days");            
            $response['data'][date('F', $timestamp)] = 0;
        }
        
        $results = Helper::cacheGet('stats.chartlinks'.Auth::user()->rID());

        if($results === null){
            $results = DB::url()->selectExpr('COUNT(MONTH(date))', 'count')->selectExpr('DATE_FORMAT(date, "%Y-%m")', 'newdate')->where("userid", Auth::user()->rID())->whereRaw('(date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH))')->groupByExpr('newdate')->findArray();
            Helper::cacheSet('chartlinks', $results,  60 * 60);
        }

        foreach($results as $data){
            $response['data'][Helper::dtime($data['newdate'], 'F')] = (int) $data['count'];
        }
        
        return (new Response($response))->json();
    }        
    /**
     * Generate Clicks Graphs
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function statsClicks(){

        $response = ['label' => e('Clicks')];

        $timestamp = strtotime('now');
        for ($i = 12 ; $i >= 0; $i--) {
            $d = $i*28;
            $timestamp = \strtotime("-{$d} days");            
            $response['data'][date('F', $timestamp)] = 0;
        }
        
        
       $results = Helper::cacheGet('stats.chartclicks'.Auth::user()->rID());

        if($results === null){
            $results = DB::stats()->selectExpr('COUNT(MONTH(date))', 'count')->selectExpr('DATE_FORMAT(date, "%Y-%m")', 'newdate')->where("urluserid", Auth::user()->rID())->whereRaw('(date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH))')->groupByExpr('newdate')->findArray();
            // Helper::cacheSet('chartclicks', $results,  60 * 60);
        }

        foreach($results as $data){
            $response['data'][Helper::dtime($data['newdate'], 'F')] = (int) $data['count'];
        }   
        
        return (new Response($response))->json(); 
    }

    /**
     * Get Clicks Map
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function clicksMap(){

        $countries = Helper::cacheGet("stats.countrymaps".Auth::user()->rID());

        if($countries == null){
          $countries = DB::stats()->selectExpr('COUNT(country)', 'count')->selectExpr('country', 'country')->where("urluserid", Auth::user()->rID())->groupByExpr('country')->orderByDesc('count')->findArray();
          Helper::cacheSet("countrymaps", $countries, 60*60);
        }

        $i = 0;
        $topCountries = [];
        $country  = [];

        foreach ($countries as $list) {
          
            $country[Helper::Country(ucwords($list["country"]), false, true)] = $list["count"];

            if($i <= 10){
                if(!empty($list["country"])) $topCountries[ucwords($list["country"])] = $list["count"];
            }
            $i++;
        }    

        return (new Response(['list' => $country, 'top' => $topCountries]))->json();  
    }      
}