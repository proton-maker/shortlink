<?php
/**
 * =======================================================================================
 *                           GemFramework (c) Xsantana                                     
 * ---------------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework as such distribution
 *  or modification of this framework is not allowed before prior consent from
 *  Xsantana. If you find that this framework is packaged in a software not distributed 
 *  by Xsantana or authorized parties, you must not use this software and contact Xsantana
 *  at https://piliruma.co.id/contact to inform them of this misuse.
 * =======================================================================================
 *
 * @package Xsantana\Premium-URL-Shortener
 * @author Xsantana (https://piliruma.co.id) 
 * @license https://piliruma.co.id/licenses
 * @link https://piliruma.co.id  
 */

namespace Admin;

use Core\DB;
use Core\View;
use Core\Request;
use Core\Response;
use Core\Helper;
use Core\Email;
use Models\User;

class Stats {	
    /**
     * Stats page 
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function index(){
       
        View::set('title', e('Statistics'));

        $counts = [];
        $counts['urls'] = ['name' => e('Links'), 'count' => DB::url()->count(), 'count.today' => DB::url()->whereRaw('`date` >= CURDATE()')->count()];
        $counts['clicks'] = ['name' => e('Clicks'), 'count' => \Core\DB::url()->selectExpr('SUM(click) as click')->first()->click, 'count.today' => DB::stats()->whereRaw('`date` >= CURDATE()')->count()];
        $counts['bio'] = ['name' => e('Bio Pages'), 'count' => DB::profiles()->count(), 'count.today' => DB::profiles()->whereRaw('`created_at` >= CURDATE()')->count()];
        $counts['qr'] = ['name' => e('QR Codes'), 'count' => DB::qrs()->count(), 'count.today' => DB::qrs()->whereRaw('`created_at` >= CURDATE()')->count()];
    
        return View::with('admin.stats', compact('counts'))->extend('admin.layouts.main');
    }
    /**
     * Get Stats Links Ajax
     *
     * @author Xsantana <https://piliruma.co.id> 
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
        
        $results = Helper::cacheGet('chartlinks');

        if($results === null){
            $results = DB::url()->selectExpr('COUNT(MONTH(date))', 'count')->selectExpr('DATE_FORMAT(date, "%Y-%m")', 'newdate')->whereRaw('(DATE(date) >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH))')->groupByExpr('newdate')->findArray();
            Helper::cacheSet('chartlinks', $results,  60 * 60);
        }

        foreach($results as $data){
            $response['data'][Helper::dtime($data['newdate'], 'F')] = (int) $data['count'];
        }
        
        return (new Response($response))->json();
    }    
    /**
     * Generate Users Graphs
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function statsUsers(){

        $response = ['label' => e('Users')];

        $timestamp = strtotime('now');
        for ($i = 12 ; $i >= 0; $i--) {
            $d = $i*28;
            $timestamp = \strtotime("-{$d} days");            
            $response['data'][date('F', $timestamp)] = 0;
        }
        
        
       $results = Helper::cacheGet('chartusers');

        if($results === null){
            $results = DB::user()->selectExpr('COUNT(MONTH(date))', 'count')->selectExpr('DATE_FORMAT(date, "%Y-%m")', 'newdate')->whereRaw('(date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH))')->groupByExpr('newdate')->findArray();
            Helper::cacheSet('chartusers', $results,  60 * 60);
        }

        foreach($results as $data){
            $response['data'][Helper::dtime($data['newdate'], 'F')] = (int) $data['count'];
        }   
        
        return (new Response($response))->json(); 
    }
    /**
     * Generate Clicks Graphs
     *
     * @author Xsantana <https://piliruma.co.id> 
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
        
        
       $results = Helper::cacheGet('chartclicks');

        if($results === null){

            $results = DB::stats()->selectExpr('COUNT(MONTH(date))', 'count')->selectExpr('DATE_FORMAT(date, "%Y-%m")', 'newdate')->whereRaw('(DATE(date) >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH))')->groupByExpr('newdate')->findArray();
            Helper::cacheSet('chartclicks', $results,  60 * 60);
        }

        foreach($results as $data){
            $response['data'][Helper::dtime($data['newdate'], 'F')] = (int) $data['count'];
        }   
        
        return (new Response($response))->json(); 
    }

    /**
     * Get Clicks Map
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function clicksMap(){

        $countries = Helper::cacheGet("countrymaps");

        if($countries == null){
          $countries = DB::stats()->selectExpr('COUNT(country)', 'count')->selectExpr('country', 'country')->groupByExpr('country')->orderByDesc('count')->findArray();
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
    /**
     * Membership Stats
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function memberships(){

        $response = [];

        $response['Free'] = DB::user()->where('pro', 0)->count();

        $response['Paid'] = DB::user()->where('pro', 1)->count();

        return (new Response($response))->json();  
    }
}