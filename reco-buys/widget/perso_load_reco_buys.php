<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds My_Widget widget.
 */
class perso_load_reco_buy {
	
	private $db;
	private $table_name;
	private $product_id;
	private $product_record;
	private $is_product_detail_page;


	public function __construct($post_id){
		global $wpdb;

		$this->product_record = null;
		$this->db = $wpdb;
		$this->table_name = $wpdb->prefix . 'perso_product_buy';
		$this->is_product_detail_page = false;

		if ($post_id > 0){
			$this->product_id = $post_id;
			$this->is_product_detail_page = true;
		}
	}

	public function is_reco_exists(){
		
		if(!$this->is_product_detail_page){
			return false;
		}

		$this->product_record = $this->db->get_row(
							$this->db->prepare("SELECT COUNT(*) AS exist 
												FROM $this->table_name
												WHERE product_id = %d", 
												$this->product_id));

		if ($this->product_record->exist == 0){
			return false;
		}else{
			return true;
		}
	}

	public function has_reco(){
		
		if(!$this->is_product_detail_page){
			return false;
		}

		$this->product_record = $this->db->get_row(
							$this->db->prepare("SELECT * 
												FROM $this->table_name
												WHERE product_id = %d", 
												$this->product_id));

		if ($this->product_record == null){
			return false;
		}else{
			return true;
		}
	}

	private function build_reco($also_views){
		
		
	}

	
	public function getReco(){
		return $this->getProducts($this->getProductIdViews());
	}

	public function getProductIdViews(){
		$prod_reco = json_decode($this->product_record->also_buys);

		$prod_id_views = array();

		foreach ($prod_reco as $element) {
			$prod_id_views[$element->product_id] = $element->buys;
		}

		arsort($prod_id_views);

		//take only n-number of items according to the setting max items
        $settings_options = get_option( 'perso_option_name' );
        $max_item = ($settings_options['max_reco_other_buys'] != '') ? $settings_options['max_reco_other_buys'] : 5;

        if(count($prod_id_views) > $max_item){
			$prod_id_views = array_slice($prod_id_views, 0, $max_item, true);// the $max_item-nth element is not included        	
        }

        return $prod_id_views;
	}


	private function getProducts($prod_id_views){

		$result = array();

       	foreach ($prod_id_views as $key => $value) {
       		$product = wc_get_product($key);

       		if(!is_null($product)){
	       		array_push($result, $product);
       		}
       	}

		return $result;		
	}

}