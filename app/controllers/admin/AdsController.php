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

class Ads {
    /**
     * Ads
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function index(){

        $ads = DB::ads()->orderByDesc('id')->paginate(15);

        View::set('title', e('Advertisement Manager'));

        return View::with('admin.ads.index', compact('ads'))->extend('admin.layouts.main');
    }
    /**
     * Add Ads
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function new(){
        
        View::set('title', e('New Advertisement'));

        CDN::load('codeeditor');
        View::push('<script type="text/javascript">
                        var editor = ace.edit("code-editor");
                            '.(request()->cookie('darkmode') ? 'editor.setTheme("ace/theme/dracula");' : 'editor.setTheme("ace/theme/chrome");').'
                            editor.getSession().setMode("ace/mode/html");
                            $("form[data-trigger=codeeditor]").submit(function(e){
                                $("#code").val(editor.getSession().getValue());                                
                            });
                    </script>', 'custom')->toFooter();

        return View::with('admin.ads.new')->extend('admin.layouts.main');
    }
    /**
     * Save Ads
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @return void
     */
    public function save(Request $request){
        
        \Gem::addMiddleware('DemoProtect');

        $request->save('name', $request->name);
        $request->save('code', $request->code);
        
        if(!$request->name || !$request->code) return Helper::redirect()->back()->with('danger', e('The name and the code are required.'));
        
        $ads = DB::ads()->create();
        $ads->name = Helper::clean($request->name, 3, true);
        $ads->type = (string) $request->type;
        $ads->code = $request->code;
        $ads->enabled = (string) Helper::clean($request->enabled);        
        
        $ads->save();
        $request->clear();
        return Helper::redirect()->to(route('admin.ads'))->with('success', e('Advertisement has been added successfully'));
    }
    /**
     * Edit Ads
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @param integer $id
     * @return void
     */
    public function edit(int $id){
        
        if(!$ad = DB::ads()->where('id', $id)->first()) return Helper::redirect()->back()->with('danger', e('Advertisement does not exist.'));

        CDN::load('codeeditor');
        View::push('<script type="text/javascript">
                        var editor = ace.edit("code-editor");
                            '.(request()->cookie('darkmode') ? 'editor.setTheme("ace/theme/dracula");' : 'editor.setTheme("ace/theme/chrome");').'
                            editor.getSession().setMode("ace/mode/html");
                            $("form[data-trigger=codeeditor]").submit(function(e){
                                $("#code").val(editor.getSession().getValue());
                            });
                    </script>', 'custom')->toFooter();

        $ad->code = htmlentities($ad->code);

        return View::with('admin.ads.edit', compact('ad'))->extend('admin.layouts.main');
    }
    /**
     * Update Ads
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, int $id){
        \Gem::addMiddleware('DemoProtect');

        if(!$ad = DB::ads()->where('id', $id)->first()) return Helper::redirect()->back()->with('danger', e('Advertisement does not exist.'));

        if(!$request->name || !$request->code) return Helper::redirect()->back()->with('danger', e('The name and the code are required.'));
        
        $ad->name = Helper::clean($request->name, 3, true);
        $ad->type = (string) $request->type;
        $ad->code = $request->code;
        $ad->enabled = (string) Helper::clean($request->enabled);
        
        $ad->save();

        return Helper::redirect()->back()->with('success', e('Advertisement has been updated successfully.'));
    }
    /**
     * Delete Ads
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

        if(!Helper::validateNonce($nonce, 'ads.delete')){
            return Helper::redirect()->back()->with('danger', e('An unexpected error occurred. Please try again.'));
        }

        if(!$ad = DB::ads()->where('id', $id)->first()){
            return Helper::redirect()->back()->with('danger', e('Advertisement not found. Please try again.'));
        }
        
        $ad->delete();
        return Helper::redirect()->back()->with('success', e('Advertisement has been deleted.'));
    }
}