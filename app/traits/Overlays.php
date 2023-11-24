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

namespace Traits;

use Core\Request;
use Core\DB;
use Core\Helper;
use User\Overlay;

trait Overlays {
    /**
	 * CTA Types
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 6.0
	 * @return void
	 */
	public static function types($type = null, $action = null){
		$list = [
			'contact' => [
				'icon' => 'mail',
				'name' => e('CTA Contact'),
				'description' => e('Create a contact form where users will be able to contact you via email.'),
				'create' => [Overlay::class, 'contactCreate'],
				'save' => [Overlay::class, 'contactSave'],
				'edit' => [Overlay::class, 'contactEdit'],
				'update' => [Overlay::class, 'contactUpdate'],
				'view' => [self::class, 'contactView']
			],
			'poll' => [
				'icon' => 'check-square',     
				'name' => e('CTA Poll'),
				'description' => e('Create a quick poll where users will be able to answer it upon visit.'),           
				'create' => [Overlay::class, 'pollCreate'],
				'save' => [Overlay::class, 'pollSave'],
				'edit' => [Overlay::class, 'pollEdit'],
				'update' => [Overlay::class, 'pollUpdate'],
				'view' => [self::class, 'pollView']
			],
			'message' => [
				'icon' => 'message-square',  
				'name' => e('CTA Message'),
				'description' => e('Create a small popup with a message and a link to a page or a product.'),              
				'create' => [Overlay::class, 'messageCreate'],
				'save' => [Overlay::class, 'messageSave'],
				'edit' => [Overlay::class, 'messageEdit'],
				'update' => [Overlay::class, 'messageUpdate'],
				'view' => [self::class, 'messageView']
			],
			'newsletter' => [
				'icon' => 'send',                
				'name' => e('CTA Newsletter'),
				'description' => e('Create a small popup form to collect emails from users.'),
				'create' => [Overlay::class, 'newsletterCreate'],
				'save' => [Overlay::class, 'newsletterSave'],
				'edit' => [Overlay::class, 'newsletterEdit'],
				'update' => [Overlay::class, 'newsletterUpdate'],
				'view' => [self::class, 'newsletterView']
			],
			'image' => [
				'icon' => 'image',
				'name' => e('CTA Image'),
				'description' => e('Create a small popup with an image of your choice.'),
				'create' => [Overlay::class, 'imageCreate'],
				'save' => [Overlay::class, 'imageSave'],
				'edit' => [Overlay::class, 'imageEdit'],
				'update' => [Overlay::class, 'imageUpdate'],
				'view' => [self::class, 'imageView']
			],
			'coupon' => [
				'icon' => 'percent',
				'name' => e('Coupon'),
				'description' => e('Create a small popup with a coupon code that users can use.'),
				'create' => [Overlay::class, 'couponCreate'],
				'save' => [Overlay::class, 'couponSave'],
				'edit' => [Overlay::class, 'couponEdit'],
				'update' => [Overlay::class, 'couponUpdate'],
				'view' => [self::class, 'couponView']
			]
		];
		if($extended = \Core\Plugin::dispatch('overlay.extend')){
			foreach($extended as $fn){
				$list = array_merge($list, $fn);
			}
		}

		if($type && $action && isset($list[$type][$action])) return $list[$type][$action];

		if(isset($list[$type])) return $list[$type];

		return $list;
	}
	/**
	 * Return contact view
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 6.0
	 * @param [type] $overlay
	 * @return void
	 */
	public static function contactView($overlay, $url){
		\Core\View::push('<script>$(".contact-event").click(function(e) { 
			e.preventDefault(); 
			$(this).hide(); 
			$(".contact-box").fadeIn(); 
		});  
		$(".contact-close").click(function(e){
			e.preventDefault(); 
			$(".contact-box").hide();
			$(".contact-event").fadeIn();
		});
		$("#contact-form").submit(function(e){
			e.preventDefault();
			if(validateForm($(this)) == false ) return false;
			$.ajax({
				type: "POST",
				url: "'.\Helpers\App::shortRoute($url->domain, 'server/contact').'",
				data: $(this).serialize(),
				success: function (response) { 
					$("#contact-form input").val("");
					$(".contact-box").hide();
					$(".contact-event").fadeIn();
					$("#contact-form").trigger("reset");
					let style = $(".contact-event i").attr("style");
					$(".contact-event i").removeClass("fa-question").addClass("fa-check").attr("style", "background-color:#82e26f;color:#fff");
					setTimeout(function(){
						$(".contact-event i").removeClass("fa-check").addClass("fa-question").attr("style", style);
						$(".contact-overlay").fadeOut();
					}, 5000);
				}
			}); 
		});</script>', 'custom')->tofooter();

		return '<div class="contact-overlay '.$overlay->data->position.'">
				<a style="color:'.$overlay->data->color.';background-color:'.$overlay->data->bg.' !important" href="#contact-box" class="contact-event mt-4"><i class="fa fa-question" style="color:'.$overlay->data->btncolor.' ;background-color:'.$overlay->data->btnbg.' !important"></i> <span>'.$overlay->data->label.'</span></a>        
				<div class="collapse contact-box mx-0 w-100" id="contact-box" style="color:'.$overlay->data->color.';background-color:'.$overlay->data->bg.' !important">
					<a href="" class="contact-close"  style="color:'.$overlay->data->color.'"><i class="fa fa-times"></i></a>    
					<h1 class="contact-label">'.$overlay->data->label.'</h1>
					<p class="contact-description">'.$overlay->data->content.'</p>
					<form id="contact-form">
						<div class="form-group">
							<label for="contact-name" class="form-label">'.$overlay->data->lang->name.'</label>
							<input data-required type="text" name="name" class="form-control" id="contact-name" placeholder="John Smith" style="color:'.$overlay->data->inputcolor.';background-color:'.$overlay->data->inputbg.' !important">
						</div>
						<div class="form-group">
							<label for="contact-email" class="form-label">'.$overlay->data->lang->email.'</label>
							<input data-required type="text" name="email" class="form-control" id="contact-email" placeholder="johnsmith@company.com" style="color:'.$overlay->data->inputcolor.';background-color:'.$overlay->data->inputbg.' !important">
						</div>		
						<div class="form-group">
							<label for="contact-message" class="form-label">'.$overlay->data->lang->message.'</label>
							<textarea data-required class="form-control" name="message" id="contact-message" placeholder="..." style="color:'.$overlay->data->inputcolor.';background-color:'.$overlay->data->inputbg.' !important"></textarea>
						</div>
						'.\Helpers\Captcha::display().'
						'.csrf().'
						<input type="hidden" name="integrity" value="'.str_replace("=", "", base64_encode(\Core\Helper::rand(5).".".$overlay->id)).'">
						<button type="submit" class="contact-btn mt-3" style="color:'.$overlay->data->btncolor.';background-color:'.$overlay->data->btnbg.' !important">'.$overlay->data->lang->button.'</button>
					</form>
				</div>
			</div>';
	}	
	/**
	 * Image View
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 6.0
	 * @param [type] $overlay
	 * @return void
	 */
	public static function imageView($overlay, $url){
		return '<div class="custom-message custom-bg '.$overlay->data->position.'" style="background-color: '.$overlay->data->bg.';background-image:url('.uploads($overlay->data->bgimage).')">
					<div class="clickable">
						<div class="d-flex">
							<div class="custom-img"><img src="'.uploads($overlay->data->image).'"></div>
						</div>
					</div>
					<a href="'.$url->url.'" class="remove"><i class="fa fa-times-circle"></i></a>
				</div>';
	}

	/**
	 * Message View
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 6.0
	 * @param [type] $overlay
	 * @return void
	 */
    public static function messageView($overlay, $url){
        return '<div class="custom-message '.$overlay->data->position.'" style="background-color:'.$overlay->data->bg.' !important">
					<div class="'.(!$overlay->data->link ? 'notclickable' : 'clickable').'">
						'.(!empty($overlay->data->label) ? '
							<div class="custom-label" style="background-color:'.$overlay->data->labelbg.';color:'.$overlay->data->labelcolor.'">'.$overlay->data->label.'</div>
						' : '').'
						'.(isset($overlay->data->image) && $overlay->data->image ? '
							<span class="custom-img"><img src="'.uploads($overlay->data->image).'" alt="'.$overlay->data->message.'"></span>
						' : '').'
						<p style="color:'.$overlay->data->color.'">
						<span class="custom-text" style="color:'.$overlay->data->color.' !important">'.$overlay->data->message.'</span> 
						'.($overlay->data->text ? '
							<a href="'.$overlay->data->link.'" class="btn btn-xs" style="background-color: '.$overlay->data->btnbg.';color: '.$overlay->data->btncolor.'">'.$overlay->data->text .'</a>
						' : '').'
						</p>
					</div>
					<a href="'.$url->url.'" class="remove"><i class="fa fa-times-circle"></i></a>
				</div>';
    }

	/**
	 * Poll View
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 6.0
	 * @param [type] $overlay
	 * @return void
	 */
    public static function pollView($overlay, $url){

		\Core\View::push('<script>$(".poll-form").submit(function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "'.\Helpers\App::shortRoute($url->domain, 'server/vote').'",
                data: $(this).serialize(),       
                success: function (response) { 
                    if(response){
                        $(".poll-box").html("<p><i class=\"fa fa-check mr-2\"></i> "+response+"</p>");
                    } else {
                        $(".poll-box").html("<p><i class=\"fa fa-check mr-2\"></i> 👍</p>");
                    }
                  $(".poll-form").remove();
                  let style = $(".contact-event i").attr("style");
                  setTimeout(function(){
                    $(".poll-overlay").remove();
                  }, 2000);
                }
            }); 
          });</script>', 'custom')->tofooter();

		$html = '<div class="poll-overlay '.$overlay->data->position.'">
				<div class="poll-box" style="color: '.$overlay->data->color.';background-color:'.$overlay->data->bg.' !important">
					<p class="poll-question" style="color: '.$overlay->data->color.'">'.$overlay->data->question.'</p>
					<form class="poll-form">
						<ol class="poll-answers">';
						foreach ($overlay->data->answers as $key => $el){
							$html .='
							<li style="color: '.$overlay->data->color.'"><label>
								<div class="custom-control custom-radio">
									<input type="radio" id="'.$key.'" name="answer" value="'.$key.'" class="custom-control-input">
									<label class="custom-control-label" for="'.$key.'">'.$el->option.'</label>
								</div>
							</li>';
						}
				$html .='</ol>
						'.csrf().'
						<input type="hidden" name="integrity" value="'.str_replace("=", "", base64_encode(\Core\Helper::rand(5).".".$overlay->id)).'">
						<button type="submit" class="poll-btn" style="color:'.$overlay->data->btncolor.';background-color:'.$overlay->data->btnbg.' !important">'.(isset($overlay->data->votetext) ? $overlay->data->votetext : e("Vote")).'</button>	
					</form>
				</div>
			</div>';
		return $html;
    }
	/**
	 * Newsletter
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 6.0
	 * @param [type] $overlay
	 * @return void
	 */
    public static function newsletterView($overlay, $url){
		
		\Core\View::push('<script>
            $("#newsletter-form").submit(function(e){
                e.preventDefault();
                if(validateForm($(this)) == false ) return false;
                $.ajax({
                    type: "POST",
                    url: "'.\Helpers\App::shortRoute($url->domain, 'server/subscribe').'",
                    data: $(this).serialize(),
                    success: function (response) { 
                        $(".contact-box").text(response.message);                        
                        setTimeout(function(){
                            $(".contact-overlay").fadeOut();
                        }, 5000);
                    }
                }); 
            });</script>', 'custom')->tofooter();

		return '<div class="contact-overlay '.$overlay->data->position.'">
				<div class="contact-box mx-0 d-block w-100" style="color:'.$overlay->data->color.';background-color:'.$overlay->data->bg.' !important">
					<h1 class="contact-label" style="color:'.$overlay->data->color.'">'.$overlay->data->label.'</h1>
					<p class="contact-description" style="color:'.$overlay->data->color.'">'.$overlay->data->content.'</p>
					<form id="newsletter-form">
						<div class="d-flex align-items-center border rounded bg-white p-1">
							<div>                         
								<input type="text" class="form-control border-0" name="email" id="contact-email" placeholder="johnsmith@company.com" data-required>
							</div>		
							<div class="ms-auto">				
								<button type="submit" class="btn btn-dark btn-lg" style="color:'.$overlay->data->btncolor.';background-color:'.$overlay->data->btnbg.' !important">'.$overlay->data->button.'</button>																
							</div>
						</div>    
						'.\Helpers\Captcha::display().'
						'.csrf().'
						<input type="hidden" name="integrity" value="'.str_replace("=", "", base64_encode(\Core\Helper::rand(5).".".$overlay->id)).'">                                    
					</form>
				</div>
			</div>';
	}
	/**
	 * Coupon View
	 *
	 * @author Xsantana <https://piliruma.co.id> 
	 * @version 6.2
	 * @param [type] $overlay
	 * @param [type] $url
	 * @return void
	 */
	public static function couponView($overlay, $url){
		
		\Core\View::push(assets('frontend/libs/clipboard/dist/clipboard.min.js'), 'js')->toFooter();
		\Core\View::push('<script>
							$("[data-toggle=remove]").click(function(){ $(".contact-overlay").fadeOut() });
							
							new ClipboardJS(".btn-copy").on("success", function(){
								$(".btn-copy").text("✔");
							}); 

						</script>', 'custom')->tofooter();

		return '<div class="contact-overlay '.$overlay->data->position.'" style="max-width:350px">
				<div class="contact-box mx-0 d-block w-100" style="color:'.$overlay->data->color.';background-color:'.$overlay->data->bg.' !important">
					<a href="#" data-toggle="remove" class="text-white position-absolute top-1 right-1"><i class="fa fa-times-circle"></i></a>
					<p class="contact-description" style="color:'.$overlay->data->color.'">'.$overlay->data->message.'</p>
					<div class="d-flex align-items-center border rounded bg-white p-1">
						<div>                         
							<h4 class="mx-2 mb-0">'.$overlay->data->coupon.'</h4>
						</div>		
						<div class="ml-auto">
							<button type="button" class="btn btn-dark btn-lg btn-copy" data-clipboard-text="'.$overlay->data->coupon.'" style="color:'.$overlay->data->btncolor.';background-color:'.$overlay->data->btnbg.' !important">'.$overlay->data->text ?? e('Copy').'</button>
						</div>
					</div>
				</div>
			</div>';
	}
}