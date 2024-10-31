<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class perso_script_reco_buys {

	public function __construct(){

	}

	public function perso_enqueue_scripts() {
		if (is_single()){
			global $post;

			wp_enqueue_script( 'perso-reco-buys-script', plugins_url( '/reco-buys/js/perso-reco-buys.js', dirname(__FILE__) ), array('jquery') );
						
			// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
			wp_localize_script( 'perso-reco-buys-script', 'ajax_object',
		            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'post_id' => $post->ID ) );
			
	    }
	}
}
