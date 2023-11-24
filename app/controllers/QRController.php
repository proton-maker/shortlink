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

use Core\Request;
use Core\DB;
use Core\Auth;
use Core\Helper;
use Core\View;
use Models\User;

class QR {

    /**
     * Generate QR
     *
     * @author Xsantana <https://piliruma.co.id> 
     * @version 6.0
     * @param string $alias
     * @return void
     */
    public function generate(string $alias){

        if(!$qr = DB::qrs()->where('alias', $alias)->first()){
            die();
        }
        
        $qr->data = json_decode($qr->data);
        if($qr->urlid && $url = DB::url()->where('id', $qr->urlid)->first()){        
            $data = ['type' => 'link', 'data' =>  \Helpers\App::shortRoute($url->domain, $url->alias.$url->custom)];
        } else {        
            $data = ['type' => $qr->data->type, 'data' => $qr->data->data];
        }

        try {

            if($qr->filename && file_exists(appConfig('app.storage')['qr']['path'].'/'.$qr->filename)){
                header('Location: '.uploads($qr->filename, 'qr'));
                exit;
            }

            $data = \Helpers\QR::factory($data, 400)->format('png');

            if(isset($qr->data->gradient) && $qr->data->gradient == 'gradient'){
                if(isset($qr->data->eyecolor) && $qr->data->eyecolor){
                    $qr->data->gradient[] = $qr->data->eyecolor;
                }

                $data->gradient(...$qr->data->gradient);

            } else {
                $data->color($qr->data->color->fg, $qr->data->color->bg, $qr->data->eyecolor ?? null);
            }

            if(isset($qr->data->matrix)){
                $data->module($qr->data->matrix);
            }

            if(isset($qr->data->eye)){
                $data->eye($qr->data->eye);
            }
            

            if(isset($qr->data->definedlogo) && $qr->data->definedlogo){
                $data->withLogo(PUB.'/static/images/'.$qr->data->definedlogo, 80);
            }  

            if(isset($qr->data->custom) && $qr->data->custom){
                $data->withLogo(appConfig('app.storage')['qr']['path'].'/'.$qr->data->custom, 80);
            }

            $qr->filename = $qr->alias.\Core\Helper::rand(6).'.png';
            $qr->data = json_encode($qr->data);
            $qr->save();

            $data->create('file', appConfig('app.storage')['qr']['path'].'/'.$qr->filename);
            
            $data->create('raw');

        } catch(\Exception $e){
            return \Core\Response::factory($e->getMessage())->send();
        }
    }

    /**
	 * Download QR
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 6.0
	 * @param \Core\Request $request
	 * @param string $alias
	 * @param string $format
	 * @param integer $size
	 * @return void
	 */
	public function download(Request $request, string $alias, string $format, int $size = 300){
		
        if(!$qr = DB::qrs()->where('alias', $alias)->first()){
            stop(404);
        }
        
        $qr->data = json_decode($qr->data);

        if($qr->urlid && $url = DB::url()->where('id', $qr->urlid)->first()){        
            $data = ['type' => 'link', 'data' =>  \Helpers\App::shortRoute($url->domain, $url->alias.$url->custom)];
        } else {        
            $data = ['type' => $qr->data->type, 'data' => $qr->data->data];
        }
		
        $qrsize = 300;

		if(is_numeric($size) && $size > 50 && $size <= 1000) $qrsize = $size;
		
		$data = \Helpers\QR::factory($data, $qrsize)->format($format);

        if(isset($qr->data->gradient) == 'gradient'){
            $data->gradient(...$qr->data->gradient);
        } else {
            $data->color($qr->data->color->fg, $qr->data->color->bg);
        }

        if($qr->data->matrix){
            $data->module($qr->data->matrix);
        }

        if($qr->data->eye){
            $data->eye($qr->data->eye);
        }

        if(isset($qr->data->definedlogo) && $qr->data->definedlogo){
            $data->withLogo(PUB.'/static/images/'.$qr->data->definedlogo, 80 * $qrsize/300);
        }  

        if(isset($qr->data->custom) && $qr->data->custom){
            $data->withLogo(appConfig('app.storage')['qr']['path'].'/'.$qr->data->custom, 80 * $qrsize/300);
        }

		return \Core\File::contentDownload('QR-code-'.$alias.'.'.$data->extension(), function() use ($data) {
			return $data->string();
		});
	}
}