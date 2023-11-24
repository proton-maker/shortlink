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
use Core\Plugin;

Gem::post('/admin/themes/settings/update', 'themeSettings@update')->name('admin.themes.update')->middleware('Auth@admin');

Plugin::register('admin.theme.getoptions', [themeSettings::class, 'settings']);

Plugin::register('homemenu', [themeSettings::class, 'menu']);