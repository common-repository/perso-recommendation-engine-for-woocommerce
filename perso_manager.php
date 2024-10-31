<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class perso_manager{

    public function __construct(){
		$this->include_commons();
		$this->include_admin_page();
		$this->include_reco_views();
		$this->include_reco_buys();
		$this->initiate_tracker();
	}

    function include_commons(){
    	include_once( 'commons/perso_widget_part.php' );
    }

    function include_reco_views(){
		include_once( 'reco-views/perso_reco_views_starter.php' );

		$aps = new perso_reco_views_starter();
		$aps->start();
    }

    function include_reco_buys(){
		include_once( 'reco-buys/perso_reco_buys_starter.php' );

		$aps = new perso_reco_buys_starter();
		$aps->start();
    }

	function initiate_tracker(){
		include_once( 'commons/perso_sweet_gluten.php' );
		$uid = perso_sweet_gluten::check_give_sweet();
	}

    function include_admin_page(){
    	//include_once('admin-page/perso_admin_page.php' );
		
		//   		$adminPage = new perso_admin_page();
		//$adminPage = new perso_admin_page();
		//add_action( 'admin_menu', array($adminPage,'chart_add_admin_page') );

		include_once( 'admin-page/perso_reco_setting.php' );

		if( is_admin() ){
			$my_settings_page = new PersoRecoSetting();	
		}

    }

}