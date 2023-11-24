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

class Qr {
    
    use \Traits\Links;

    /**
     * Links
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function index(Request $request){
        $query = DB::qrs();
        
        if($request->sort == "old") $query->orderByAsc('created_at');
        if(!$request->sort) $query->orderByDesc('created_at');

        $qrs = [];
        foreach($query->paginate(is_numeric($request->perpage) ? $request->perpage : 15) as $qr){
            if(!$qr->user = User::first($qr->userid)) continue;
            $qr->url = DB::url()->first($qr->urlid);
            $qr->data= json_decode($qr->data, true);
            $qr->source = '';
            if(is_array($qr->data['data'] )){
                foreach($qr->data['data'] as $key => $data){
                    $qr->source .= "{$key} = {$data}\n";
                }
            }  else {
                $qr->source = $qr->data['data'];
            }        
            $qrs[] = $qr;
        }

        View::push(assets('frontend/libs/clipboard/dist/clipboard.min.js'), 'js')->toFooter();

        View::set('title', e('QR Codes'));

        return View::with('admin.qr', compact('qrs'))->extend('admin.layouts.main');
    }

     /**
     * Delete qr
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @param [type] $id
     * @return void
     */
    public function delete(int $id, string $nonce){

        \Gem::addMiddleware('DemoProtect');

        if(!Helper::validateNonce($nonce, 'qr.delete')){
            return Helper::redirect()->back()->with('danger', e('An unexpected error occurred. Please try again.'));
        }

        if(!$qr = DB::qrs()->where('id', $id)->first()){
            return back()->with('danger', 'QR does not exist.');
        }
        
        unlink( appConfig('app.storage')['qr']['path'].'/'.$qr->filename);

        $qr->delete();

        if($url = DB::url()->where('qrid', $id)->first()){
            $this->deleteLink($url->id);
        }
        
        return back()->with('success', 'QR has been successfully deleted.');
    }
}