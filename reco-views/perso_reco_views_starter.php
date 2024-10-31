<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class perso_reco_views_starter {

	public function __construct(){

	}

	public function start(){
		include_once('perso_script_reco_views.php');
		include_once('perso_callback_reco_views.php');
		include_once('perso_visitor_views.php');
		include_once('perso_product_matrix.php');
		include_once('perso_objects_views.php');

		include_once('widget/perso_load_reco_views.php');
		include_once('widget/perso_reco_widget_also_views.php');

		include_once('footer_reco/perso_reco_view_footer.php');

		$aps = new perso_script_reco_views();
		add_action('wp_enqueue_scripts', array($aps,'perso_enqueue_scripts'));

		$pac = new perso_callback_reco_views();
		add_action( 'wp_ajax_perso_views_action', array($pac,'perso_views_action_callback') );
		add_action( 'wp_ajax_perso_input_action', array($pac,'perso_input_action_callback') );
			
		add_action( 'wp_ajax_nopriv_perso_views_action', array($pac,'perso_views_action_callback') );
		add_action( 'wp_ajax_nopriv_perso_input_action', array($pac,'perso_input_action_callback') );

		add_action( 'widgets_init', function(){
			     register_widget( 'perso_reco_widget_also_views' );
			});

		$prf = new perso_reco_view_footer();
		add_action("woocommerce_after_single_product", array($prf,"customer_also_viewed"));

		
	}

}
