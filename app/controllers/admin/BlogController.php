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

namespace Admin;

use Core\DB;
use Core\View;
use Core\Request;
use Core\Helper;
Use Helpers\CDN;

class Blog {
    /**
     * Blog posts
     *
     * @author Xsantana 
     * @version 6.0
     * @return void
     */
    public function index(){

        $posts = DB::posts()->orderByDesc('date')->paginate(15);

        View::set('title', e('Posts'));

        return View::with('admin.blog.index', compact('posts'))->extend('admin.layouts.main');
    }
    /**
     * Add Post
     *
     * @author Xsantana 
     * @version 6.0
     * @return void
     */
    public function new(){
        
        View::set('title', e('New Post'));

        CDN::load('editor');
        View::push("<script>                        
                        CKEDITOR.replace('editor', {
                            allowedContent: true                            
                        });
                    </script>", "custom")->toFooter();

        return View::with('admin.blog.new')->extend('admin.layouts.main');
    }
    /**
     * Save post
     *
     * @author Xsantana 
     * @version 6.0
     * @param \Core\Request $request
     * @return void
     */
    public function save(Request $request){
        
        \Gem::addMiddleware('DemoProtect');

        $request->save('title', $request->title);
        $request->save('content', $request->content);
        $request->save('meta_title', $request->meta_title);
        $request->save('meta_description', $request->meta_description);

        if(!$request->title || !$request->content) return Helper::redirect()->back()->with('danger', e('The title and the content are required.'));

        if($request->slug && DB::posts()->where('slug', $request->slug)->first()) return Helper::redirect()->back()->with('danger', e('This slug is already taken, please use another one.'));

        $post = DB::posts()->create();
        $post->title = Helper::clean($request->title, 3, true);
        $post->slug = $request->slug ? $request->slug : Helper::slug($post->title);
        $post->content = $request->content;
        $post->meta_title = Helper::clean($request->meta_title, 3, true);
        $post->meta_description = Helper::clean($request->meta_description, 3, true);
        $post->date = Helper::dtime();
        $post->published = Helper::clean($request->published);
        
        if($image = $request->file('image')){
            if(!$image->mimematch || !in_array($image->ext, ['jpg', 'png'])) return Helper::redirect()->back()->with('danger', e('The image is not valid. Only a JPG or PNG are accepted.'));
            $post->image = $image->name;            
            $request->move($image, appConfig('app.storage')['blog']['path']);
        }

        $post->save();
        $request->clear();
        return Helper::redirect()->to(route('admin.blog'))->with('success', e('Blog post has been added successfully'));
    }
    /**
     * Edit Post
     *
     * @author Xsantana 
     * @version 6.3
     * @param integer $id
     * @return void
     */
    public function edit(int $id){
        
        if(!$post = DB::posts()->where('id', $id)->first()) return Helper::redirect()->back()->with('danger', e('Post does not exist.'));

        CDN::load('editor');
        View::push("<script>                        
                        CKEDITOR.replace('editor', {
                            allowedContent: true                            
                        });
                    </script>", "custom")->toFooter();

        View::set('title', e('Edit Post'));

        return View::with('admin.blog.edit', compact('post'))->extend('admin.layouts.main');
    }
    /**
     * Update Post
     *
     * @author Xsantana 
     * @version 6.0
     * @param \Core\Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, int $id){
        \Gem::addMiddleware('DemoProtect');

        if(!$post = DB::posts()->where('id', $id)->first()) return Helper::redirect()->back()->with('danger', e('Post does not exist.'));

        if(!$request->title || !$request->content) return Helper::redirect()->back()->with('danger', e('The title and the content are required.'));

        if($request->slug && DB::posts()->where('slug', $request->slug)->whereNotEqual('id', $post->id)->first()) return Helper::redirect()->back()->with('danger', e('This slug is already taken, please use another one.'));

        $post->title = Helper::clean($request->title, 3, true);
        $post->slug =  Helper::clean($request->slug);
        $post->content = $request->content;
        $post->meta_title = Helper::clean($request->meta_title, 3, true);
        $post->meta_description = Helper::clean($request->meta_description, 3, true);
        $post->date = Helper::dtime();
        $post->published = Helper::clean($request->published);
        
        if($image = $request->file('image')){
            if(!$image->mimematch || !in_array($image->ext, ['jpg', 'png'])) return Helper::redirect()->back()->with('danger', e('The image is not valid. Only a JPG or PNG are accepted.'));
            $post->image = $image->name;            
            $request->move($image, appConfig('app.storage')['blog']['path']);
        }

        $post->save();

        return Helper::redirect()->back()->with('success', e('Blog post has been update successfully'));
    }
    /**
     * Delete Post
     *
     * @author Xsantana 
     * @version 6.0
     * @param \Core\Request $request
     * @param integer $id
     * @param string $nonce
     * @return void
     */
    public function delete(Request $request, int $id, string $nonce){
        
        \Gem::addMiddleware('DemoProtect');

        if(!Helper::validateNonce($nonce, 'blog.delete')){
            return Helper::redirect()->back()->with('danger', e('An unexpected error occurred. Please try again.'));
        }

        if(!$post = DB::posts()->where('id', $id)->first()){
            return Helper::redirect()->back()->with('danger', e('Blog post not found. Please try again.'));
        }
        
        if($post->image && file_exists(PUB."/content/blog/{$post->image}")) unlink(PUB."/content/blog/{$post->image}");
        $post->delete();
        return Helper::redirect()->back()->with('success', e('Post has been deleted.'));
    }
}