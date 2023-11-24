<?php 
/**
 * ====================================================================================
 *                           GemFramework (c) Xsantana
 * ----------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework owned by Xsantana Inc as such
 *  distribution or modification of this framework is not allowed before prior consent
 *  from Xsantana administrators. If you find that this framework is packaged in a 
 *  software not distributed by Xsantana or authorized parties, you must not use this
 *  software and contact Xsantana at https://piliruma.co.id/contact to inform them of this
 *  misuse otherwise you risk of being prosecuted in courts.
 * ====================================================================================
 *
 * @package Gem\Core\Email
 * @author Xsantana (http://Xsantana.com)
 * @copyright 2020 Xsantana
 * @license http://Xsantana.com/license
 * @link http://Xsantana.com  
 * @since 1.0
 */
namespace Core\Support;

use Core\Http;
use GemError;

final class Mailgun {

    private $url = null;
    /**
     * Sending Domain
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 1.0
     */
    private $domain = null;
    /**
     * Private Key
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 1.0
     */
    private $key = null;    

    /**
     * Data
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 1.0
     */
    private $data = ['to' => '', 'from' => ''];

    /**
     * Send as Mailgun
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 1.0
     * @param string $domain
     * @param string $key
     */
    public function __construct($config, $endpoint = null){

        if(is_array($config)) {
            $this->domain = $config['domain'];
            $this->key = $config['key'];
		}

		if(is_object($config)){
            $this->domain = $config->domain;
            $this->key = $config->key;
		}

        $this->url = $endpoint ?? 'https://api.mailgun.net/v3';
        return $this;
    }

	/**
	 * To user
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 1.0
	 * @param mixed $user
	 * @return void
	 */
	public function to($user){				
		if(is_array($user)){
            $this->data['to'] .= "{$user[1]} <{$user[0]}>";
        } else {
            $this->data['to'] .= $user;
        }
		return $this;
	}
	/**
	 * Sender information
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 1.0
	 * @param mixed $sender
	 * @return void
	 */
	public function from($sender){		
		if(is_array($sender)){
            $this->data['from'] .= "{$sender[1]} <{$sender[0]}>";
        } else {
            $this->data['from'] .= $sender;
        }
		return $this;
	}
   /**
    * Send as Mailgun
    *
    * @author Xsantana <https://piliruma.co.id> 
    * @version 1.0
    * @param array $data
    * @return void
    */
    public function send(array $data){

        $content = \http_build_query([
            'from' => $this->data['from'],
            'to' => $this->data['to'],
            'subject' => $data['subject'],
            'html' => $data['message']
        ]); 

        $http = Http::url($this->url.'/'.$this->domain.'/messages')->auth('api', $this->key)->body($content)->post();
        

        if($http->httpCode() == 200) return true;

        GemError::log('Mailgun API Error: '.$http->httpCode().' '.$http->getBody());

        return false;
    }

}