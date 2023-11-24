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

namespace Admin;

use Core\DB;
use Core\View;
use Core\Request;
use Core\Helper;
Use Helpers\CDN;
use Models\User;

class Bio {
    
    use \Traits\Links;

    /**
     * Links
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function index(Request $request){

        $query = DB::profiles();
        
        if($request->sort == "old") $query->orderByAsc('created_at');
        if(!$request->sort) $query->orderByDesc('created_at');

        $bios = [];
        foreach($query->paginate(is_numeric($request->perpage) ? $request->perpage : 15) as $bio){
            $bio->user = User::first($bio->userid);
            $bio->url = DB::url()->first($bio->urlid);
            $bios[] = $bio;
        }

        View::push(assets('frontend/libs/clipboard/dist/clipboard.min.js'), 'js')->toFooter();

        View::set('title', e('Bio Pages'));

        return View::with('admin.bio', compact('bios'))->extend('admin.layouts.main');
    }

     /**
     * Delete Profile
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param [type] $id
     * @return void
     */
    public function delete(int $id, string $nonce){

        \Gem::addMiddleware('DemoProtect');

        if(!Helper::validateNonce($nonce, 'bio.delete')){
            return Helper::redirect()->back()->with('danger', e('An unexpected error occurred. Please try again.'));
        }

        if(!$bio = DB::profiles()->where('id', $id)->first()){
            return back()->with('danger', e('Profile does not exist.'));
        }

        $bio->delete();

        if($url = DB::url()->where('profileid', $id)->first()){
            $this->deleteLink($url->id);
        }
        return back()->with('success', e('Profile has been successfully deleted.'));
    }
}