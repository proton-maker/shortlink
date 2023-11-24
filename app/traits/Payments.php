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

namespace Traits;

use Core\DB;
use Core\Helper;
use Core\Request;
use Helpers\Payments\Bank;
use Helpers\Payments\Paypal;
use Helpers\Payments\Stripe;
use Helpers\Payments\PaypalApi;
use Helpers\Payments\CoinPayments;

trait Payments {

  /**
   * Payment List
   *
   * @author Xsantana 
   * @version 6.0
   * @param string|null $type
   * @param string|null $action
   * @return void
   */
    public function processor($type = null, $action = null){

        $list = [			 
			'paypal' => [
				'provider' => 'Paypal Single Payment',
				'name' => e('PayPal'),
                'config' => ['single' => true, 'subscription' => false],
				'settings' => [PayPal::class, 'settings'],
				'save' => null,
				'checkout' => [PayPal::class, 'checkout'],
				'payment' => [PayPal::class, 'payment'],
				'subscription' => null,
				'webhook' => [PayPal::class, 'webhook'],
				'createplan' => null,
				'updateplan' => null,
				'syncplan' => null,
				'cancel' => null,
				'createcoupon' => null,
				'createtax' => null
			],
            'stripe' => [
				'provider' => 'Stripe',
				'name' => e('Credit Card'),
                'config' => ['single' => true, 'subscription' => true],
				'settings' => [Stripe::class, 'settings'],
				'save' => null,
				'checkout' => [Stripe::class, 'checkout'],
				'payment' => [Stripe::class, 'payment'],				
				'subscription' => [Stripe::class, 'subscription'],
				'webhook' => [Stripe::class, 'webhook'],
				'createplan' => [Stripe::class, 'createplan'],
				'updateplan' => [Stripe::class, 'updateplan'],
				'syncplan' => [Stripe::class, 'syncplan'],
				'cancel' => [Stripe::class, 'cancel'],
				'createcoupon' => [Stripe::class, 'createcoupon'],
				'createtax' => [Stripe::class, 'createtax'],
			],							
			'paypalapi' => [
				'provider' => 'PayPal API',
				'name' => e('PayPal'),
                'config' => ['single' => true, 'subscription' => true],
				'settings' => [PaypalApi::class, 'settings'],
				'save' => [PaypalApi::class, 'save'],
				'checkout' => [PaypalApi::class, 'checkout'],
				'payment' => [PaypalApi::class, 'payment'],
				'subscription' => [PaypalApi::class, 'subscription'],
				'webhook' => [PaypalApi::class, 'webhook'],
				'createplan' => [PaypalApi::class, 'createplan'],
				'updateplan' => [PaypalApi::class, 'updateplan'],
				'syncplan' => [PaypalApi::class, 'syncplan'],
				'cancel' => [PaypalApi::class, 'cancel'],
				'createcoupon' => [PaypalApi::class, 'createcoupon'],
				'createtax' => null
			],
			'bank' => [
				'provider' => e('Bank Transfer'),
				'name' => e('Bank Transfer'),
				'description' => e('Transfer payments via your bank'),
                'config' => ['single' => true, 'subscription' => false],
				'settings' => [Bank::class, 'settings'],
				'save' => null,
				'checkout' => [Bank::class, 'checkout'],
				'payment' => [Bank::class, 'payment'],
				'subscription' => null,
				'webhook' => null,
				'createplan' => null,
				'updateplan' => null,
				'syncplan' => null,
				'cancel' => null,
				'createcoupon' => null,
				'createtax' => null
            ]
		];

		if($extended = \Core\Plugin::dispatch('payment.extend')){
			foreach($extended as $fn){
				$list = array_merge($list, $fn);
			}
		}

		if($type && $action && isset($list[$type][$action])) return $list[$type][$action];

		if(isset($list[$type])) return $list[$type];

		return $list;
    }

}