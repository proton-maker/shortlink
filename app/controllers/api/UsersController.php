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

namespace API;

use \Core\Helper;
use \Core\Request;
use \Core\Response;
use \Core\DB;
use \Core\Auth;
use \Models\User;

class Users {
    /**
     * Check if is admin
     *
     * @author Xsantana 
     * @version 6.0
     */
    public function __construct(){

        if(!Auth::ApiUser()->admin){
            die(Response::factory(['error' => 1, 'message' => 'You do not have permission to access this endpoint.'], 403)->json());
        }        
    }
    /**
     * List all users
     *
     * @author Xsantana 
     * @version 6.0
     * @param \Core\Request $request
     * @return void
     */
    public function get(Request $request){

        $users = [];

        if( $request->filter ){
            if($request->filter == 'admin'){
                
                $query = User::where('admin', 1)->findMany();

            } elseif($request->filter == 'free'){
                
                $query = User::where('pro', 0)->findMany();

            } else {
                $query = User::where('pro', 1)->findMany();
            }

        }elseif($request->email){
            
            $query = User::where('email', clean($request->email))->findMany();

        }else {
            $query = User::findMany();
        }


        foreach($query as $user){

            $users[] = [
                'id' => (int) $user->id,
                'email' => $user->email,
                'username' => $user->username,
                'avatar' => $user->avatar(),
                'status' => $user->pro ? 'pro' : 'free',
                'planid' => $user->planid,
                'expires' => $user->expiration,
                'registered' => $user->date,
                'apikey' => $user->api
            ];
        }

        return Response::factory(['error' => 0, 'data' => $users])->json();

    }
    /**
     * Get a single user
     *
     * @author Xsantana 
     * @version 6.1.6
     * @param integer $id
     * @return void
     */
    public function single(int $id){

        if(!$user = User::first($id)){
            return Response::factory(['error' => 1, 'message' => 'User does not exist.'])->json();
        }

        $response = [
            'id' => (int) $user->id,
            'email' => $user->email,
            'username' => $user->username,
            'avatar' => $user->avatar(),
            'status' => $user->pro ? 'pro' : 'free',
            'planid' => $user->planid,
            'expires' => $user->expiration,
            'registered' => $user->date,
            'apikey' => $user->api
        ];

        return Response::factory(['error' => 0, 'data' => $response])->json();
    }
    /**
     * Create user
     *
     * @author Xsantana 
     * @version 6.0
     * @param \Core\Request $request
     * @return void
     */
    public function create(Request $request){

        $data = $request->getJSON();

        $user = DB::user()->create();

        if(isset($data->username)){

            if(!$request->validate($data->username, 'username'))  return Response::factory(['error' => 1, 'message' => 'Please enter a valid username.'])->json();

            if(DB::user()->where('username', $data->username)->first())  return Response::factory(['error' => 1, 'message' => 'Username already exists.'])->json();
    
            $user->username = Helper::RequestClean($data->username);
        }

        if(isset($data->email)){

            $data->email = clean($data->email);
            
            if(!$request->validate($data->email, 'email')) return Response::factory(['error' => 1, 'message' => 'Please enter a valid email.'])->json();

            if(DB::user()->where('email', $data->email)->first()) return Response::factory(['error' => 1,  'message' => 'An account is already associated with this email.'])->json();

            $user->email = clean($data->email);
        }

        if(isset($data->password)){

            $data->password = clean($data->password);
            
            if(strlen($data->password) < 5) return Response::factory(['error' => 1, 'message' => 'Password must be at least 5 characters.'])->json();
            
            Helper::set("hashCost", 8);
            $user->password = Helper::Encode($data->password);

        }

        if(isset($data->planid) && $data->planid && DB::plans()->first(clean($data->planid))){
            $user->planid = $data->planid;

            if(!isset($data->expiration)) return Response::factory(['error' => 1, 'message' => 'The expiration date is not valid.'])->json(); 
        }

        $user->save();

        return Response::factory(['error' => 0, 'message' => 'User has been successfully created.', 'userid' => (int) $user->id])->json();

    }
    /**
     * Delete user
     *
     * @author Xsantana 
     * @version 6.0
     * @param integer $id
     * @return void
     */
    public function delete(int $id){
        
        if(!$user = DB::user()->first($id)){
            return Response::factory(['error' => 1, 'message' => 'User does not exist.'])->json(); 
        }

        $user->delete();

        return Response::factory(['error' => 0, 'message' => 'User has been deleted successfully.'])->json(); 
    }
    /**
     * Login as user
     *
     * @author Xsantana 
     * @version 6.3
     * @param integer $id
     * @return void
     */
    public function login(int $id){
        if(!$user = DB::user()->first($id)){
            return Response::factory(['error' => 1, 'message' => 'User does not exist.'])->json(); 
        }

        $user->uniquetoken = strtolower(Helper::rand(32));
        $user->save();

        return Response::factory(['error' => 0, 'url' => route('login.sso', [$user->uniquetoken.'-'.md5(AuthToken.": Expires on".strtotime(date('Y-m-d H')))])])->json(); 
    }
}