<?php
/**
 * Plugin Name: Perso
 * Plugin URI: http://getperso.com
 * Description: This plugin provides product recommendations woocommerce based on visitors behaviours.
 * Version: 2.1.1
 * Author: Rahadian Bayu Permadi
 * Author URI: http://getperso.com
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Perso' ) ) :

	final class Perso {

		protected static $_instance = null;
		private $manager = null;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->initiate_install_module();
			$this->initiate_manager();
		}

		function initiate_install_module(){
			include_once( 'commons/perso_install.php' );
			register_activation_hook( __FILE__, array('perso_install','perso_setup_table'));
		}


		function initiate_manager(){
	    	include_once( 'perso_manager.php' );
			$this->manager = new perso_manager();

			add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 1 );
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 
				array($this,'add_action_links') );
		}

		function admin_footer_text( $footer_text ) {
			$footer_text = sprintf( __( 'If you like <strong>Perso - Recommendation Engine</strong> please give us a %s&#9733;&#9733;&#9733;&#9733;&#9733;%s rating. 5 stars rating would give us enough energy to develop this further', 'perso' ), '<a href="https://wordpress.org/support/view/plugin-reviews/perso-recommendation-engine-for-woocommerce?filter=5#postform" target="_blank">', '</a>' );
				
			return $footer_text;
		}


		function add_action_links ( $links ) {
		     $settingslinks = array(
		     '<a href="' . admin_url( 'options-general.php?page=perso_settings' ) . '">Settings</a>',
		     );
		    return array_merge( $settingslinks, $links );
		}

	}

endif;

function Perso() {
	return Perso::instance();
}

// Global for backwards compatibility.
$GLOBALS['Perso'] = Perso();



