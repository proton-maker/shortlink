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
use Core\Helper;
Use Helpers\CDN;
use Models\User;

class Affiliates {
    /**
     * Affiliates Refs
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function index(){

        $sales = []; 
        foreach(DB::affiliates()->orderByDesc('id')->paginate(15) as $sale){
            $sale->user = User::where('id', $sale->refid)->first();
            $sale->referred = User::where('id', $sale->userid)->first();
            $sales[] = $sale;
        }

        View::set('title', e('Affiliates'));

        return View::with('admin.affiliates', compact('sales'))->extend('admin.layouts.main');
    }

    /**
     * Payments Due
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function payments(){

        $users = User::whereRaw('pendingpayment >= ?', config('affiliate')->payout)->findMany();

        View::set('title', e('Affiliate Payments'));

        return View::with('admin.affiliatepayments', compact('users'))->extend('admin.layouts.main');
    }    

    /**
     * Update Affiliate
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, int $id, string $action){
        \Gem::addMiddleware('DemoProtect');

        if(!$affiliate = DB::affiliates()->where('id', $id)->first()) return Helper::redirect()->back()->with('danger', e('Referral does not exist.'));

        if($action == 'approve'){
            $user = User::where('id', $affiliate->refid)->first();
            $user->pendingpayment = $user->pendingpayment + $affiliate->commission;
            $user->save();
            
            $affiliate->status = 1; 
            $affiliate->save();

            return Helper::redirect()->back()->with('success', e('Referral status has been approved successfully and user has been awarded $'.$affiliate->comission.'.'));
        }    
        
        if($action == 'reject'){

            $affiliate->status = 2; 
            $affiliate->save();

            return Helper::redirect()->back()->with('success', e('Referral status has been rejected and no commission was awarded.'));
        } 
    }
    /**
     * Pay user
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @param [type] $id
     * @return void
     */
    public function pay($id){
        
        if(!$user = User::where('id', $id)->first()){
            stop(404);
        }
        
        DB::affiliates()->where('refid', $user->id)->where('status', '1')->update(['paid_on' => Helper::dtime('now'), 'status' => '3']);
        
        \Helpers\Emails::affiliatePayment($user, \Helpers\App::currency(config('currency'), $user->pendingpayment));

        $user->pendingpayment = 0;
        $user->save();

        return Helper::redirect()->back()->with('success', e('User affiliate commissions have been marked as paid and user has been notified of the payment.'));
    }
    /**
     * Delete Post
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @param integer $id
     * @param string $nonce
     * @return void
     */
    public function delete(Request $request, int $id, string $nonce){
        
        \Gem::addMiddleware('DemoProtect');

        if(!Helper::validateNonce($nonce, 'affiliate.delete')){
            return Helper::redirect()->back()->with('danger', e('An unexpected error occurred. Please try again.'));
        }

        if(!$affiliate = DB::affiliates()->where('id', $id)->first()){
            return Helper::redirect()->back()->with('danger', e('Referral not found. Please try again.'));
        }
        
        $affiliate->delete();
        return Helper::redirect()->back()->with('success', e('Referral has been deleted.'));
    }
}