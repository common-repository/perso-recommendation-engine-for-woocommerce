<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class perso_callback_reco_buys {

	public function __construct(){

	}

	function perso_buys_action_callback() {
		$post_id = $_REQUEST['post_id'];

		$plr = new perso_load_reco_buy_footer();
		$has_reco = $plr->has_reco($post_id);

		if($has_reco){
			$recos = $plr->getReco($post_id);

			$prwp = new perso_widget_part($recos);
	        echo $prwp->get_widget_list();	
		}else{
			echo 'empty';
		}

		wp_die();
	}

	
}
