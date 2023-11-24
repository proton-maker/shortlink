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
namespace User;

use Core\Request;
use Core\Helper;
use Core\Auth;
use Core\DB;
use Core\View;
use Models\User;

class Pixels {

    use \Traits\Pixels;
    /**
     * Verify Permission
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     */
    public function __construct(){

        if(User::where('id', Auth::user()->rID())->first() === false){
            return \Models\Plans::notAllowed();
        }
    }

    /**
     * List Pixels Page
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function index(){
        
        $pixels = DB::pixels()->where('userid', Auth::user()->rID())->orderByDesc('id')->paginate(15);

        $count = DB::pixels()->where('userid', Auth::user()->rID())->count();

        $total = Auth::user()->hasLimit('pixels');    
        
        View::set('title', e('Tracking Pixels'));

        return View::with('pixels.index', compact('pixels', 'count', 'total'))->extend('layouts.dashboard');
    }

    /**
     * Add Pixels Page
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function create(){

        if(Auth::user()->teamPermission('pixel.create') == false){
			return back()->with('danger', e('You do not have this permission. Please contact your team administrator.'));
		}


        $pixels = DB::pixels()->where('userid', Auth::user()->rID())->orderByDesc('id')->find();

        $count = DB::pixels()->where('userid', Auth::user()->rID())->count();

        $total = Auth::user()->hasLimit('pixels');
                
        \Models\Plans::checkLimit($count, $total);

        View::set('title', e('New Pixel'));     

        $providers = self::pixels();   

        return View::with('pixels.new', compact('count', 'total', 'providers'))->extend('layouts.dashboard');
    }

    /**
     * Save Pixels Page
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @return void
     */
    public function save(Request $request){

        \Gem::addMiddleware('DemoProtect');

        if(Auth::user()->teamPermission('pixel.create') == false){
			return back()->with('danger', e('You do not have this permission. Please contact your team administrator.'));
		}

        $providers = self::pixels();

        if(!isset($providers[$request->type])) return back()->with('danger', e('Pixel provider is currently not supported.'));

        $user = Auth::user();

        $pixels = DB::pixels()->where('userid', Auth::user()->rID())->orderByDesc('id')->find();

        $count = DB::pixels()->where('userid', Auth::user()->rID())->count();

        $total = Auth::user()->hasLimit('pixels');        

        \Models\Plans::checkLimit($count, $total);

        if(strlen($request->tag) < 3) {
            return Helper::redirect()->back()->with("danger",e("Please enter valid id."));
        }
        
        try{
            self::validate($request->type, $request->tag);
        } catch(\Exception $e){
            return Helper::redirect()->back()->with("danger", $e->getMessage());
        }

        if($pixel = DB::pixels()->where('userid', $user->rID())->where('type', $request->type)->where('tag', clean($request->tag))->first()){
            return Helper::redirect()->back()->with("danger", e('A pixel with this provider and tag already exists.'));
        }  
        
        $pixel = DB::pixels()->create();
        
        $pixel->userid =  Auth::user()->rID();
        $pixel->type = clean($request->type);
        $pixel->name = clean($request->pixel);
        $pixel->tag = clean($request->tag);
        $pixel->created_at = Helper::dtime('now');
        $pixel->save();

        return Helper::redirect()->to(route('pixel'))->with('success', e('Pixel has been added successfully'));
    }

    /**
     * Edit Pixels
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @param integer $id
     * @return void
     */
    public function edit(int $id){

        if(Auth::user()->teamPermission('pixel.edit') == false){
			return back()->with('danger', e('You do not have this permission. Please contact your team administrator.'));
		}
        
        if(!$pixel = DB::pixels()->where('userid', Auth::user()->rID())->where('id', $id)->first()){
            return Helper::redirect()->back()->with('danger', e('Pixel not found. Please try again.'));
        }
            
        View::set('title', e('Edit Pixel'));

        return View::with('pixels.edit', compact('pixel'))->extend('layouts.dashboard');
    }
    
    /**
     * Update Existing Pixels
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, int $id){

        if(Auth::user()->teamPermission('pixel.edit') == false){
			return back()->with('danger', e('You do not have this permission. Please contact your team administrator.'));
		}

        \Gem::addMiddleware('DemoProtect');

        if(!$pixel = DB::pixels()->where('userid', Auth::user()->rID())->where('id', $id)->first()){
            return Helper::redirect()->back()->with('danger', e('Pixel not found. Please try again.'));
        }             


        if(strlen($request->tag) < 3) {
            return Helper::redirect()->back()->with("danger",e("Please enter valid id."));
        }

        try{
            self::validate($pixel->type, $request->tag);
        } catch(\Exception $e){
            return Helper::redirect()->back()->with("danger", $e->getMessage());
        }   

        $pixel->name = clean($request->pixel);
        $pixel->tag = clean($request->tag);

        $pixel->save();

        return Helper::redirect()->back()->with('success', e('Pixel has been updated successfully.'));
    }

    /**
     * Delete Domain
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @param integer $id
     * @param string $nonce
     * @return void
     */
    public function delete(int $id, string $nonce){
        \Gem::addMiddleware('DemoProtect');

        if(Auth::user()->teamPermission('pixel.delete') == false){
			return back()->with('danger', e('You do not have this permission. Please contact your team administrator.'));
		}

        if(!Helper::validateNonce($nonce, 'pixel.delete')){
            return Helper::redirect()->back()->with('danger', e('An unexpected error occurred. Please try again.'));
        }
        
        if(!$pixel = DB::pixels()->where('userid', Auth::user()->rID())->where('id', $id)->first()){
            return Helper::redirect()->back()->with('danger', e('Pixel not found. Please try again.'));
        }

        foreach(DB::url()->whereLike('pixels', '%'.$pixel->type.'-'.$pixel->id.'%')->where('userid', Auth::user()->rID())->findMany() as $url){
            $url->pixels = trim(str_replace( $pixel->type.'-'.$pixel->id, '', $url->pixels), ',');
            $url->save();
        }

        $pixel->delete();
            
        return Helper::redirect()->back()->with('success', e('Pixel has been deleted.'));
    }

}