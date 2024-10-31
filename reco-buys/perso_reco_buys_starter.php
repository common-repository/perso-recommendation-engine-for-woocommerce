<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class perso_reco_buys_starter {

	public function __construct(){

	}

	public function start(){
		include_once('perso_callback_reco_buys.php');
		include_once('perso_script_reco_buys.php');

		include_once('footer_reco/perso_reco_buy_footer.php');
		include_once('footer_reco/perso_load_reco_buy_footer.php');

		include_once('widget/perso_reco_widget_also_buys.php');

		$aps = new perso_script_reco_buys();
		add_action('wp_enqueue_scripts', array($aps,'perso_enqueue_scripts'));

		$pac = new perso_callback_reco_buys();
		add_action( 'wp_ajax_perso_buys_action', array($pac,'perso_buys_action_callback') );
		add_action( 'wp_ajax_nopriv_perso_buys_action', array($pac,'perso_buys_action_callback') );
		
		add_action( 'widgets_init', function(){
			     register_widget( 'perso_reco_widget_also_buys' );
			});

		$prf = new perso_reco_buy_footer();
		add_action("woocommerce_after_single_product", array($prf,"customer_also_bought"));

	}
}