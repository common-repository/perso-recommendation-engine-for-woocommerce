<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class perso_product_matrix {

	private $table_name;
	private $product_views;
	private $product_id;
	private $db;
	private $current_date;
	private $is_new_session;

	public function __construct($product_views, $product_id, $current_date, $is_new_session){
		global $wpdb;

		$this->db = $wpdb;
		$this->table_name = $wpdb->prefix . 'perso_product_views';
		$this->product_id = $product_id;
		$this->product_views = $this->map_and_clean_products($product_views);
		$this->current_date = $current_date;
		$this->is_new_session = $is_new_session;
	}

	private function map_and_clean_products($product_views){
		//remove current product from the product views
		//create map as the representative of cleaned product views
		
		$cleaned_map = array();

		foreach ($product_views as $pv) {
			if($pv->product_id != $this->product_id){
				$cleaned_map[$pv->product_id] = $pv;
			}
		}

		return $cleaned_map;
	}

	public function insert_product_view(){

		$this->process_non_current_products();

		$this->process_current_product();

	}

	private function process_current_product(){
		$pr = $this->get_product_record($this->product_id);

		if($pr == null){
			//insert current product in the table

			$this->insert_current_product();

		}else{
			//update also views of current propduct

			$this->update_current_product($pr);
		}
	}

	private function update_current_product($pr){
		$also_views = json_decode($pr->also_views);
		$avs = array();


		foreach ($also_views as $av) {
			$avs[$av->product_id] = $av;	
		}

		foreach ($this->product_views as $pv) {
			if(array_key_exists($pv->product_id, $avs)){
				//non current product exists
				$avs[$pv->product_id]->views = $avs[$pv->product_id]->views + 1;
						
			}else{
				//non current product doesn't exist
				$sv = new perso_product_session_view();
				$sv->product_id = $pv->product_id;
				$sv->views = 1;//initial view number	
				$avs[$sv->product_id] = $sv;		
			}
		}

		$avs = array_values($avs);

		$this->db->update( 
						$this->table_name, 
							array( 
								 	'last_product_view' => $this->current_date,
									'also_views' => json_encode($avs) 
							), 
							array( 'product_id' => $pr->product_id ), 
							array( 
								'%s',
								'%s'
							), 
							array( '%d') 
						);

	}

	private function insert_current_product(){

		$svs = array();

		foreach ($this->product_views as $pv) {
			$sv = new perso_product_session_view();
			$sv->product_id = $pv->product_id;
			$sv->views = 1;//initial view number			
			$svs[] = $sv;
		}

		$this->db->insert($this->table_name, 
								array( 
									'product_id' => $this->product_id, 
									'last_product_view' => $this->current_date,
									'also_views' => json_encode($svs) 
								) 
							);
	}

	private function process_non_current_products(){

		foreach ($this->product_views as $pv) {
		  	$this->process_each_non_current_product($pv);
		} 
	}

	private function process_each_non_current_product($ncp){
		
		$pr = $this->get_product_record($ncp->product_id);

		if($pr == null){
			//insert new non-current product, and put the current producct in also views and the value is one
			$this->insert_non_current_product($ncp);
		}else{
			//non current product exist, then just update the views of current product in also_views 
			$this->update_non_current_product($pr);
		}
	}

	private function update_non_current_product($pr){
		$also_views = json_decode($pr->also_views);

		$found = false;

		foreach ($also_views as $av) {
			if ($av->product_id == $this->product_id){
				//if it exist then just increment the views
				$found = true;
				$av->views = $av->views + 1;
				break;
			}
		}

		if(!$found){
			$sv = new perso_product_session_view();
			$sv->product_id = $this->product_id;
			$sv->views = 1;//initial view number
			
			$also_views[] = $sv;
		}

		$this->db->update( 
						$this->table_name, 
							array( 
								 	'last_product_view' => $this->current_date,
									'also_views' => json_encode($also_views) 
							), 
							array( 'product_id' => $pr->product_id ), 
							array( 
								'%s',
								'%s'
							), 
							array( '%d') 
						);
	}

	private function insert_non_current_product($ncp){
		$sv = new perso_product_session_view();
		$sv->product_id = $this->product_id;
		$sv->views = 1;//initial view number

		$svs[] = $sv;

		$this->db->insert($this->table_name, 
								array( 
									'product_id' => $ncp->product_id, 
									'last_product_view' => $this->current_date,
									'also_views' => json_encode($svs) 
								) 
							);
	}

	private function get_product_record($product_id){
		$product_record = $this->db->get_row(
							$this->db->prepare("SELECT * 
												FROM $this->table_name
												WHERE product_id = %d", 
												$product_id));

		return $product_record;
	}
}