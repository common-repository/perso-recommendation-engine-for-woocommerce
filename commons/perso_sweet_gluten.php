<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class perso_sweet_gluten {

	public static $SWEET_NAME = "persouid";

	public static function check_give_sweet() {

		if(empty($_COOKIE[perso_sweet_gluten::$SWEET_NAME])){
			$uid = uniqid();
			setcookie( perso_sweet_gluten::$SWEET_NAME,$uid, 0, COOKIEPATH, COOKIE_DOMAIN );
		}else{
			$uid = $_COOKIE[perso_sweet_gluten::$SWEET_NAME];
		}

		return $uid;
		
	}

}
