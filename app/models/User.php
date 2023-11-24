<?php

namespace Models;

use Gem;
use Core\Model;
use Core\Helper;

class User extends Model {

	/**
	 * Table Name
	 */
	public static $_table = DBprefix.'user';

	/**
	 * Auth Key Name
	 */
	const AUTHKEY = 'auth_key';

	public function rID(){
		return $this->teamid ?: $this->id;
	}
	/**
	 * User avatar
	 *
	 * @author Xsantana
	 * @version 6.0
	 * @return void
	 */
	public function avatar(){

		if($this->avatar) {
			return \Core\View::uploads($this->avatar, 'avatar');
		}

		if($this->auth == "facebook" && !empty($this->auth_id)){
			return "https://graph.facebook.com/".$this->auth_id."/picture?type=large";
		}else{
			return "https://www.gravatar.com/avatar/".md5(trim($this->email))."?s=64&d=identicon";
		}
	}
	/**
	 * Refresh Plans
	 *
	 * @author Xsantana
	 * @version 6.1.6
	 * @return void
	 */
	public function refresh(){
		unset(Gem::$App['userplan']);
		return $this;
	}
	/**
	 * Get User Plan
	 *
	 * @author Xsantana
	 * @version 6.0
	 * @return void
	 */
	public function plan($limit = null){

		if(!isset(Gem::$App['userplan'])) {
			if($this->planid && $data = \Core\DB::plans()->where('id', $this->planid)->first()) {
				$plan = $data->asArray();
			} else {
				$plan = [];
			}
			Gem::$App['userplan'] = !config('pro') || $this->admin || is_null($this->planid) ? \Helpers\App::defaultPlan() : $plan;
		}

		if($limit) {
			return isset(Gem::$App['userplan'][$limit]) ? Gem::$App['userplan'][$limit] : false;
		}

		return Gem::$App['userplan'];
	}
	/**
	 * Check User Permission
	 *
	 * @author Xsantana
	 * @version 6.0
	 * @param [type] $permission
	 * @return boolean
	 */
	public function has($permission){

		if(!config('pro')) return true;

		if(!$this->admin && !$this->planid) return false;

		$plan = $this->plan();

		if(!$plan) return false;

		$plan["permission"] = json_decode($plan["permission"]);

		if(isset($plan["permission"]->{$permission}) && $plan["permission"]->{$permission}->enabled){
			return true;
		}
		return false;
	}
	/**
	 * Count User Permission
	 *
	 * @author Xsantana
	 * @version 6.0
	 * @param [type] $permission
	 * @return boolean
	 */
	public function hasLimit($permission){

		if(!config('pro')) return 0;

		$plan = $this->plan();

		if(!$plan) return false;

		$plan["permission"] = json_decode($plan["permission"]);
		if(isset($plan["permission"]->{$permission}) && $plan["permission"]->{$permission}->enabled){
			if(isset($plan["permission"]->{$permission}->count)){
				return $plan["permission"]->{$permission}->count;
			}
		}

		return false;
	}
	/**
	 * Check if user is in a team
	 *
	 * @author Xsantana
	 * @version 6.0
	 * @return void
	 */
	public function team(){
		return $this->teamid ? true : false;
	}
	/**
	 * View team permission
	 *
	 * @author Xsantana
	 * @version 6.0
	 * @param string $permission
	 * @return void
	 */
	public function teamPermission(string $permission){

		if(!$this->teamid) return true;

		if(empty($this->teampermission)) return true;

		$permissions = json_decode($this->teampermission, true);

		if(in_array($permission, $permissions)) return true;

		return false;
	}
	/**
	 * Get user pixels list
	 *
	 * @author Xsantana
	 * @version 6.0
	 * @return void
	 */
	public function pixels(){
		$list = [];

		foreach(\Core\DB::pixels()->where('userid', $this->rID())->orderByDesc('type')->find() as $pixel){

			$list[\Helpers\App::pixelName($pixel->type)][] = $pixel;
		}

		return $list;
	}
	/**
	 * Check if user is pro
	 *
	 * @author Xsantana
	 * @version 1.0
	 * @return void
	 */
	public function pro(){

		if(!config('pro')) return true;

		if($this->admin || $this->pro) return true;

		return false;
	}
}
