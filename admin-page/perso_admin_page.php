<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds My_Widget widget.
 */
class perso_admin_page{

    public function __construct()
    {

    }

    public function chart_add_admin_page() {
    	
	    add_menu_page('Perso', 
	    				'Perso', 
	    				'manage_options', 
	    				'perso-top-level-menu', 
	    				array($this,'perso_report'),
	    				plugins_url('/admin-page/img/perso-admin-icon.png',dirname(__FILE__)));

   		add_submenu_page( 'perso-top-level-menu', 
						'Perso', 
						'Report', 
						'manage_options', 
						'perso-top-level-menu');

    	add_submenu_page( 'perso-top-level-menu', 
   						'Settings', 
   						'Settings', 
   					 	'manage_options', 
   					 	'settings-menu-handle', 
   					 	array($this,'perso_settings'));

    	add_action( 'admin_enqueue_scripts', array($this, 'chart_register_scripts') );
	}

	function chart_register_scripts($hook) {
	    wp_register_script( 'chart-core', 
				    	plugins_url('/admin-page/js/charts/Chart.Core.js', dirname(__FILE__) ));

        wp_register_script( 'chart-doughnut', 
        				plugins_url('/admin-page/js/charts/Chart.Doughnut.js', dirname(__FILE__) ));
	    
	    wp_register_script( 'chart-line', 
	    				plugins_url('/admin-page/js/charts/Chart.Line.js', dirname(__FILE__) ));
    	
    	wp_register_script( 'chart-polar', 
    					plugins_url('/admin-page/js/charts/Chart.PolarArea.js', dirname(__FILE__) ));
    	
    	wp_register_script( 'chart-radar', 
    					plugins_url('/admin-page/js/charts/Chart.Radar.js', dirname(__FILE__) ));
    	
    	wp_register_script( 'chart-min', 
    					plugins_url('/admin-page/js/charts/Chart.min.js', dirname(__FILE__) ));
    	
    	wp_register_script( 'chart-stacked', 
    					plugins_url('/admin-page/js/charts/Chart.StackedBar.js', dirname(__FILE__) ), 
    					array('jquery') );

	    wp_register_style('chart-layout', 
	    				plugins_url('/admin-page/css/perso-admin-layout.css', dirname(__FILE__) ));

        wp_register_script( 'display-data', plugins_url('/admin-page/js/dummy-data.js', dirname(__FILE__) ) );
	

	    if ( 'toplevel_page_perso-top-level-menu' == $hook){
//	    if ( 'perso_page_settings-menu-handle' == $hook){
		    wp_enqueue_script( 'chart-core' );
		    wp_enqueue_script( 'chart-doughnut' );
		    wp_enqueue_script( 'chart-line' );
		    wp_enqueue_script( 'chart-polar' );
		    wp_enqueue_script( 'chart-radar' );
		    wp_enqueue_script( 'chart-min' );
		    wp_enqueue_script( 'chart-stacked' );
            wp_enqueue_style('chart-layout');

		    wp_enqueue_script( 'display-data' );

	    }
        
	}



	public function perso_settings(){
		echo 'settings';
	}

	public function perso_report(){
		include_once('perso_report_overview.php');
	}


}