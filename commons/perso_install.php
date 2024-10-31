<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class perso_install {
	
	public static function perso_setup_table() {
		global $wpdb;
		global $perso_db_version;

		$table_name_visitor_view = $wpdb->prefix . 'perso_visitor_views';
		$table_name_product_view = $wpdb->prefix . 'perso_product_views';
		$table_name_product_buy  = $wpdb->prefix . 'perso_product_buy';
		
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name_visitor_view (
					uid varchar(25) NOT NULL PRIMARY KEY,
					product_views text NOT NULL,
					last_product_id bigint(20) NOT NULL, 
					last_product_view datetime NOT NULL
				) $charset_collate;
				CREATE TABLE $table_name_product_view (
					product_id  bigint(20) NOT NULL PRIMARY KEY,
					last_product_view datetime NOT NULL,
					also_views text NOT NULL
				) $charset_collate;
				CREATE TABLE $table_name_product_buy (
					product_id  bigint(20) NOT NULL PRIMARY KEY,
					last_buy_time datetime NOT NULL,
					also_buys text NOT NULL
				) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'perso_db_version', $perso_db_version );
	}
}