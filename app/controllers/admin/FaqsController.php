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

class Faqs {
    /**
     * Custom FAQ
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function index(){

        $faqs = DB::faqs()->orderByDesc('id')->paginate(15);

        View::set('title', e('FAQs'));

        return View::with('admin.faq.index', compact('faqs'))->extend('admin.layouts.main');
    }
    /**
     * Add FAQ
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function new(){
        
        View::set('title', e('New FAQ'));

        CDN::load('editor');
        View::push("<script>                        
                        CKEDITOR.replace('editor');
                    </script>", "custom")->toFooter();

        return View::with('admin.faq.new')->extend('admin.layouts.main');
    }
    /**
     * Save page
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @return void
     */
    public function save(Request $request){
        
        \Gem::addMiddleware('DemoProtect');

        $request->save('question', $request->question);
        $request->save('answer', $request->answer);

        if(!$request->question || !$request->answer) return Helper::redirect()->back()->with('danger', e('The question and the answer are required.'));

        if($request->slug && DB::faqs()->where('slug', $request->slug)->first()) return Helper::redirect()->back()->with('danger', e('This slug is already taken, please use another one.'));

        $faq = DB::faqs()->create();
        $faq->question = Helper::clean($request->question, 3, true);
        $faq->slug = $request->slug ? $request->slug : Helper::slug($faq->question);
        $faq->answer = $request->answer;
        $faq->category = $request->category;
        $faq->pricing = is_numeric($request->pricing) ? $request->pricing : 0;
        $faq->created_at = Helper::dtime();

        $faq->save();
        $request->clear();
        return Helper::redirect()->to(route('admin.faq'))->with('success', e('FAQ has been added successfully.'));
    }
    /**
     * Edit FAQ
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param integer $id
     * @return void
     */
    public function edit(int $id){
        
        if(!$faq = DB::faqs()->where('id', $id)->first()) return Helper::redirect()->back()->with('danger', e('FAQ does not exist.'));
        
        View::set('title', e('Edit FAQ'));

        CDN::load('editor');    
        View::push("<script>                        
                        CKEDITOR.replace('editor');
                    </script>", "custom")->toFooter();        

        return View::with('admin.faq.edit', compact('faq'))->extend('admin.layouts.main');
    }
    /**
     * Update FAQ
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, int $id){
        \Gem::addMiddleware('DemoProtect');

        if(!$faq = DB::faqs()->where('id', $id)->first()) return Helper::redirect()->back()->with('danger', e('FAQ does not exist.'));

        if(!$request->question || !$request->answer) return Helper::redirect()->back()->with('danger', e('The name and the content are required.'));

        if($request->slug && DB::faqs()->where('slug', $request->slug)->whereNotEqual('id', $faq->id)->first()) return Helper::redirect()->back()->with('danger', e('This slug is already taken, please use another one.'));

        $faq->question = Helper::clean($request->question, 3, true);
        $faq->slug = $request->slug;
        $faq->answer = $request->answer;
        $faq->category = $request->category;
        $faq->pricing = is_numeric($request->pricing) ? $request->pricing : 0;
        $faq->created_at = Helper::dtime();

        $faq->save();

        return Helper::redirect()->back()->with('success', e('FAQ has been update successfully.'));
    }
    /**
     * Delete FAQ
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @param integer $id
     * @param string $nonce
     * @return void
     */
    public function delete(Request $request, int $id, string $nonce){
        
        \Gem::addMiddleware('DemoProtect');

        if(!Helper::validateNonce($nonce, 'faq.delete')){
            return Helper::redirect()->back()->with('danger', e('An unexpected error occurred. Please try again.'));
        }

        if(!$faq = DB::faqs()->where('id', $id)->first()){
            return Helper::redirect()->back()->with('danger', e('FAQ not found. Please try again.'));
        }
        
        $faq->delete();
        return Helper::redirect()->back()->with('success', e('FAQ has been deleted.'));
    }
    /**
     * FAQ Categories
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function categories(){
        
        $categories = config('faqcategories');

        View::set('title', e('FAQ Categories'));

        return View::with('admin.faq.categories', compact('categories'))->extend('admin.layouts.main');
    }
    /**
     * Add Category
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @return void
     */
    public function categoriesSave(Request $request){
        
        $categories = config('faqcategories');

        if(!$categories) $categories = [];

        if(!$request->title) return Helper::redirect()->back()->with('danger', e('Category title is required.')); 

        if(isset($categories->{Helper::slug($request->title)})) return Helper::redirect()->back()->with('danger', e('Category already exists.'));

        $categories->{Helper::slug($request->title)} = ['title' => Helper::RequestClean($request->title), 'description' => Helper::RequestClean($request->description)];
    
        $setting = DB::settings()->where('config', 'faqcategories')->first();

        $setting->var = json_encode($categories);
        $setting->save();
        return Helper::redirect()->back()->with('success', e('Category has been added.'));
    }
    /**
     * Update category
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @param string $key
     * @return void
     */
    public function categoriesUpdate(Request $request, string $key){
        
        $categories = config('faqcategories');

        if(!$categories) $categories = [];

        if(!$request->newtitle) return Helper::redirect()->back()->with('danger', e('Category title is required.')); 

        if(!isset($categories->{$key})) return Helper::redirect()->back()->with('danger', e('Category does not exist.'));

        $categories->{$key} = ['title' => Helper::RequestClean($request->newtitle), 'description' => Helper::RequestClean($request->newdescription)];

        

        $setting = DB::settings()->where('config', 'faqcategories')->first();

        $setting->var = json_encode($categories);
        $setting->save();
        return Helper::redirect()->back()->with('success', e('Category has been updated.'));
    }
    /**
     * Delete category
     *
     * @author gempixel <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @param integer $id
     * @param string $nonce
     * @return void
     */
    public function categoriesDelete(Request $request, string $key, string $nonce){
        
        \Gem::addMiddleware('DemoProtect');

        if(!Helper::validateNonce($nonce, 'category.delete')){
            return Helper::redirect()->back()->with('danger', e('An unexpected error occurred. Please try again.'));
        }
        
        $categories = config('faqcategories');

        if(!isset($categories->{$key})){
            return Helper::redirect()->back()->with('danger', e('Category not found. Please try again.'));
        }
        
        unset($categories->{$key});

        

        $setting = DB::settings()->where('config', 'faqcategories')->first();

        $setting->var = json_encode($categories);
        $setting->save();

        return Helper::redirect()->back()->with('success', e('FAQ has been deleted.'));
    }
}