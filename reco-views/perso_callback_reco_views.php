<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class perso_callback_reco_views {

	public function __construct(){

	}

	function perso_views_action_callback() {
		$post_id = $_REQUEST['post_id'];

		$plr = new perso_load_reco_views($post_id);
		$has_reco = $plr->has_reco();

		if($has_reco){
			$recos = $plr->getReco();
			
			//echo print_r($recos);
			$prwp = new perso_widget_part($recos);
	        echo $prwp->get_widget_list();						
		}else{
			echo 'empty';
		}
		wp_die();
	}

	function perso_input_action_callback() {
		$post_id = $_REQUEST['post_id'];
		
		$pvv = new perso_visitor_views();
		$pvv->record_view($post_id);
				
		wp_die();
	}
	
}
