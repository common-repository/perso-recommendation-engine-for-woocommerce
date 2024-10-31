<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds My_Widget widget.
 */
class perso_load_reco_buy_footer {
	
	private $db;
	private $product_record;
	private $is_product_detail_page;
	private $table_prefix;

	public function __construct(){
		global $wpdb;

		$this->db = $wpdb;
		$this->table_prefix = $wpdb->prefix;
	}

	public function has_reco($pid){
		
		$data = $this->load_data_from_order_table($pid);

		if(empty($data)){
			return false;
		}else{
			return true;
		}
	}

	public function getReco($post_id){
		$result = array();

		$prod_id_views = $this->load_data_from_order_table($post_id);
		
       	foreach ($prod_id_views as $key) {
       		$product = wc_get_product($key);

       		if(!is_null($product)){
	       		array_push($result, $product);
       		}
       	}

		return $result;		
	}

	public function load_data_from_order_table($pid){
		$cache_time = 3600; //1 hour

		$all_products = wp_cache_get( 'perso_also_bought_'.$pid, 'perso' );
        
        if(!$all_products){
			$subsql = "SELECT oim.order_item_id FROM ".$this->table_prefix."woocommerce_order_itemmeta oim where oim.meta_key='_product_id' and oim.meta_value in (".$pid.")";

	        $sql = "SELECT oi.order_id from  ".$this->table_prefix."woocommerce_order_items oi where oi.order_item_id in (".$subsql.") limit 100";

	        
	        $all_orders = $this->db->get_col($sql);
	        
	        if($all_orders){
				$all_orders_str = implode(',',$all_orders);

				$subsql2 = "select oi.order_item_id FROM ".$this->table_prefix."woocommerce_order_items oi where oi.order_id in ($all_orders_str) and oi.order_item_type='line_item'";

				$sub_exsql2 = " and oim.meta_value not in ($pid)";

				$settings_options = get_option( 'perso_option_name' );
		       		$max_item = ($settings_options['max_reco_other_buys'] != '') ? $settings_options['max_reco_other_buys'] : 5;


				$sql2 = "select oim.meta_value as product_id,count(oim.meta_value) as total_count from ".$this->table_prefix."woocommerce_order_itemmeta oim where oim.meta_key='_product_id' $sub_exsql2 and oim.order_item_id in ($subsql2) group by oim.meta_value order by total_count desc limit $max_item";

				$all_products = $this->db->get_col($sql2);
				
				wp_cache_add( 'perso_also_bought_'.$pid, $all_products, 'perso', $cache_time );

			}        	
        }

		
		return $all_products;

    } 

}
