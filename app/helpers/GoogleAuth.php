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

namespace Helpers;

use Core\View;
use Core\Response;
use Core\Request;
use Core\Helper;
use Core\Http;

final class GoogleAuth {

	private $client_id = NULL;
	private $client_secret = NULL;
	private $response = NULL;
	private $redirect_uri = NULL;
	private $auth_url = "https://accounts.google.com/o/oauth2/auth?";
	private $token_url = "https://accounts.google.com/o/oauth2/token?";
	private $info_url = "https://www.googleapis.com/userinfo/v2/me?";

    /**
     * Connect OAuth
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     */
    public function __construct($client_id, $client_secret, $redirect_uri){

		// Validate Code
        session_regenerate_id();

		if(empty($client_id) || empty($client_secret) || empty($redirect_uri)){
            throw new \Exception("Sorry, Google connect is not available right now.");			
		}

		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->redirect_uri = $redirect_uri;
    }
    /**
     * Generate Redirect URI
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @return void
     */
    public function redirectURI(Request $request){
        
        $state = md5(Helper::rand(32));

		$options = [
			"scope" => "email profile",
			"redirect_uri" => $this->redirect_uri,
			"response_type" => "code",
			"state" => $state,
			"client_id" => $this->client_id
        ];	

        $request->session('oauth_state', $state);

        return $this->auth_url.http_build_query($options);
    }
    /**
     * Get Access Token
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @param \Core\Request $request
     * @return void
     */
    public function getAccessToken(Request $request){

        if(!$request->state || $request->state != $request->session('oauth_state')){
            throw new \Exception("Security token doesn't match. Please try again.");
		}		

		$options = [
			"code" => $request->code,
			"client_secret" => $this->client_secret,
			"redirect_uri" => $this->redirect_uri,
			"client_id" => $this->client_id,
			"grant_type" => "authorization_code"
        ];

		$response = Http::url($this->token_url)->body($options)->post();

        $response = json_decode($response);

        // Validate Response
        if(!isset($response->access_token)) { 			
            throw new \Exception('Oops. The access token is not valid. Please try again.');		 			
        }

        $data = Http::url($this->info_url."access_token=".$response->access_token)->get();
        
        $this->response = json_decode($data);
    }
    /**
     * Get User
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @return void
     */
    public function getUser(){
        return $this->response;
    }
}        