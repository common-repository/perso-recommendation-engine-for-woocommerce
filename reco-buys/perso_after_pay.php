<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class perso_after_pay {

	private $db;
	private $table_name;
	private $current_date;

	public function __construct(){
		global $wpdb;

		$this->db = $wpdb;
		$this->table_name = $this->db->prefix . 'perso_product_buy';
	}

	public function record_buy($order_id){

		$order = new WC_Order( $order_id );
		$items = $order->get_items();


		if(count($items) > 1){
			$this->current_date = date("Y-m-d H:i:s");
			$this->process_items($items);
		}

	}

	private function process_items($items){

		$num_items = count($items);

		if($num_items == 2){
			//process 2 items
			$this->process_two_items($items);
		}else{
			//more than 2 items
			//process $items - 1
			$first_items = array_slice($items, 0, $num_items - 1);
			$this->process_items($first_items);

			//process last_item
			$last_item_id = $last_key = key(array_slice($items, -1, 1, TRUE ));
			$this->process_last_item($last_item_id, $items, $first_items);
		}
	}

	private function process_last_item($last_item_id, $items, $first_items){

		$last_product_id = $items[$last_item_id]['product_id'];
		
		$buy_record = $this->db->get_row($this->db->prepare("SELECT * 
											FROM $this->table_name
											WHERE product_id = %d", $last_product_id));

		if($buy_record == null){
			$num_result = 0;
		}else{
			$num_result = count($buy_record);
		}

		if($num_result == 0){
			//none exist
			$this->new_last_item($last_product_id, $first_items);
		}else{
			$this->update_last_item($buy_record, $first_items);
		}

		//update first items
		// insert/add also_buys of last_item
		$this->update_first_items($last_product_id, $first_items);
	}

	private function update_last_item($buy_record, $first_items){

		$also_buys = json_decode($buy_record->also_buys);
		$buys = array();

		foreach ($also_buys as $buy) {
			$buys[$buy->product_id] = $buy;	
		}

		foreach($first_items as $item_id=>$item) {
			if(array_key_exists($item['product_id'], $buys)) {
				$buys[$item['product_id']]->buys = $buys[$item['product_id']]->buys + 1;
			}else{
				$pb = new perso_product_buy();
				$pb->product_id = $item['product_id'];
				$pb->buys = 1;
				$buys[$pb->product_id] = $pb;
			}
		}

		$values = array_values($buys);

		$this->db->update( 
						$this->table_name, 
							array( 
								 	'last_buy_time' => $this->current_date,
									'also_buys' => json_encode($values) 
							), 
							array( 'product_id' => $buy_record->product_id ), 
							array( 
								'%s',
								'%s'
							), 
						array( '%d') 
					);
	}


	private function new_last_item($last_product_id, $first_items){

			$also_buys = array();

			foreach ($first_items as $item_id => $item) {
				$product_id = $item['product_id'];

				$npb = new perso_product_buy();
				$npb->product_id = $product_id;
				$npb->buys = 1;
				$also_buys[] = $npb;					
			}

			$this->db->insert($this->table_name, 
								array( 
									'last_buy_time' => $this->current_date, 
									'product_id' => $last_product_id,
									'also_buys' => json_encode($also_buys)
								) 
							);
	}

	private function update_first_items($last_product_id, $first_items){

		$product_buys = $this->db->get_results($this->build_parameters($last_product_id, $first_items));

		foreach ($product_buys as $buy) {
			$also_buys = json_decode($buy->also_buys);

			$found = false;
			foreach ($also_buys as $item) {
				if($item->product_id == $last_product_id){
					$item->buys = $item->buys + 1;
					$found = true;
					break;
				}
			}

			if($found == false){
				$pb = new perso_product_buy();
				$pb->product_id = $last_product_id;
				$pb->buys = 1;
				$also_buys[] = $pb;
			}

			$this->db->update( 
						$this->table_name, 
							array( 
								 	'last_buy_time' => $this->current_date,
									'also_buys' => json_encode($also_buys) 
							), 
							array( 'product_id' => $buy->product_id), 
							array( 
								'%s',
								'%s'
							), 
							array( '%d') 
						);
		}
	}

	private function build_parameters($last_product_id, $items){
		
		$param = 'SELECT product_id, also_buys 
				FROM '.$this->table_name.' 
				where product_id in (';
					
		$first = true;

		foreach ($items as $item_id=>$item) {
			if($first == true){
				$first = false;
			}else{
				$param .= ',';
			}

			$param .= $item['product_id'];			
			
		}

		$param .= ")";

		return $param;
	}


	private function new_buy($product_id){
		$this->db->insert($this->table_name, 
								array( 
									'last_buy_time' => $this->current_date, 
									'product_id' => $product_id
								) 
							);
	}

	private function insert_buy($product_id_host, $product_id_guest){
		$buy_record = $this->db->get_row(
							$this->db->prepare("SELECT * 
												FROM $this->table_name
												WHERE product_id = %d", 
												$product_id_host));

		if($buy_record->also_buys == null || $buy_record->also_buys == ''){
			$pb = new perso_product_buy();
			$pb->product_id = $product_id_guest;
			$pb->buys = 1;

			$also_buys[] = $pb;
		}else{
			$also_buys = json_decode($buy_record->also_buys);
			$found = false;

			foreach ($also_buys as $pb) {
				if($pb->product_id == $product_id_guest){
					$found = true;
					$pb->buys = $pb->buys + 1;
				}	
			}

			if($found == false){
				$pb = new perso_product_buy();
				$pb->product_id = $product_id_guest;
				$pb->buys = 1;	
				$also_buys[] = $pb;
			}
		}

		$this->db->update($this->table_name, 
							array( 
								'last_buy_time' => $this->current_date,
								'also_buys' => json_encode($also_buys)
							), 
							array( 'product_id' => $product_id_host ), 
							array( 
								'%s',
								'%s'
							), 
							array( '%d') 
						);
	}	

	private function process_two_items($items){
		$products_ids = array();

		foreach ($items as $item_id => $item) {
			$products_ids[] = $item['product_id'];
		}

		
		$product_id_1 = $products_ids[0];
		$product_id_2 = $products_ids[1];

		$buy_record = $this->db->get_results("SELECT * 
												FROM $this->table_name
												WHERE product_id = $product_id_1 
												OR product_id = $product_id_2");

		if($buy_record == null){
			$num_result = 0;
		}else{
			$num_result = count($buy_record);
		}

		
		if($num_result == 0){
			//none exist
			
			$this->new_buy($product_id_2);				
			$this->new_buy($product_id_1);	

		}elseif ($num_result == 1) {
			// one is missing
			// get existing

			foreach ($buy_record as $br) {
				$existing = $br->product_id;				
			}

			if ($existing == $product_id_1){
				//missing second product
					$this->new_buy($product_id_2);
			}else{
				//missing first product
					$this->new_buy($product_id_1);				
			}
		}

		$this->insert_buy($product_id_1, $product_id_2);
		$this->insert_buy($product_id_2, $product_id_1);
	}
}