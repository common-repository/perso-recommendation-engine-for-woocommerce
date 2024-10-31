<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class perso_visitor_views {

	private $current_post;
	private $db;
	private $table_name;
	private $post_id;

	private static $SESSION_TIME_LIMIT = 7200; //two hours


	public function __construct(){
		
	}

	public function record_view($post_id) {

		global $wpdb;

		$this->post_id = $post_id;
		$this->db = $wpdb;
		$this->table_name = $this->db->prefix . 'perso_visitor_views';
		
		//at this point a cookie with peroid should already exist
		$uid = $_COOKIE[perso_sweet_gluten::$SWEET_NAME];
		$this->update_visitor_record($uid);

	}

	private function update_visitor_record($uid){

		$current_date = date("Y-m-d H:i:s");
		
		$user_record = $this->get_visitor_record($uid);

		if($user_record == null){
			//New user and never been recorded
			$this->insert_new_visitor($uid, $this->post_id, $current_date);
		}else{

			if(!$this->check_same_session($user_record, $current_date)){
				//create new session because the session time has passed
				$this->insert_new_visitor_session($user_record, $current_date, $this->post_id);

			}else{
				//update existing session because the previous session has not passed
				//check if the product is in session already

				//separate last session and other session
				$separated_sessions = $this->separate_last_session($user_record, $current_date);

				if(!$this->check_product_in_session($separated_sessions['last_session'], 
													$this->post_id)){
					//product not in session then insert the product in the session

					$new_last_session = $this->insert_new_product_in_session($separated_sessions['last_session'], 
																  				$this->post_id, $current_date);
					
					//update product view table
					//only update product matrix when a new product is inserted in the session
					$this->update_product_view_table($new_last_session, $this->post_id, $current_date);
					

				}else{
					//product in session just change the view tima of that product in the session
					$new_last_session = $this->update_product_view_time_in_session($separated_sessions['last_session'], 
																  				$this->post_id, $current_date);
					
					
				}

				$separated_sessions['non_last_sessions'][] = $new_last_session;

				$this->update_session_record($user_record->uid, $separated_sessions['non_last_sessions'], 
												$this->post_id, $current_date);
				
			}
		}

	}

	private function update_product_view_table($last_session, $product_id, $current_date){
		$product_views = $last_session->product_views;

		$is_new_session = false;

		if(count($product_views) == 2){//inserted to product view table when the product as minimum as 2
			$is_new_session = true;
		}

		$ppv = new perso_product_matrix($product_views, $product_id, $current_date, $is_new_session);
		$ppv->insert_product_view();
	}

	private function update_product_view_time_in_session($last_session, $product_id, $current_date){
		$vv = new perso_product_view($current_date);
		$vv->product_id = 0;

		$sv = new perso_session_view($last_session->start);
		
		foreach($last_session->product_views as $pv){
			if($pv->product_id == $product_id){
				$vv = $pv;
			}else{
				$sv->product_views[] = $pv;
			}
		}

		if($vv->product_id != 0){
			$vv->view_time = $current_date;
		
			$sv->product_views[] = $vv;
			$sv->end = $current_date;
		}

		return $sv;
	}

	private function update_session_record($uid, $sessions, $product_id, $current_date){
		$this->db->update($this->table_name, 
							array( 
								'last_product_view' => $current_date,
								'last_product_id' => $product_id,
								'product_views' => json_encode($sessions)
							), 
							array( 'uid' => $uid ), 
							array( 
								'%s',
								'%d',
								'%s'
							), 
							array( '%s') 
						);
	}

	

	private function insert_new_product_in_session($last_session, $product_id, $current_date){
		$vv = new perso_product_view($current_date);
		$vv->product_id = $product_id;

		$last_session->product_views[] = $vv;
		$last_session->end = $current_date;

		return $last_session;
	}

	private function check_product_in_session($last_session, $product_id){

		$in_session = false;

		foreach ($last_session->product_views as $vv) {
			if($vv->product_id == $product_id){
				$in_session = true;
				break;
			}
		}

		return $in_session;
	}


	private function separate_last_session($user_record, $current_date){
		$psvArr = json_decode($user_record->product_views);

		$newPsvArr = array();
		$last_psv = new perso_session_view($current_date);

		foreach ($psvArr as $psv) {
		    if($user_record->last_product_view == $psv->end){
		    	//last session
		    	$last_psv = $psv;
		    }else{
		    	$newPsvArr[] = $psv;
		    }
		}

		$returnArr['non_last_sessions'] = $newPsvArr;
		$returnArr['last_session'] = $last_psv;

		return $returnArr;
	}

	private function insert_new_visitor_session($user_record, $current_date, $product_id){
		// visitor already exist in table, just create a new session


		$vv = new perso_product_view($current_date);
		$vv->product_id = $product_id;

		$psv = new perso_session_view($current_date);
		$psv->end   = $current_date;
		$psv->product_views[] = $vv;

		$prod_views = json_decode($user_record->product_views);
		$prod_views[] = $psv;

		$this->db->update( 
								$this->table_name, 
								array( 
									'last_product_view' => $current_date, 
									'product_views' => json_encode($prod_views), 
									'last_product_id' => $product_id
								), 
								array( 'uid' => $user_record->uid ), 
								array( 
									'%s',
									'%s',
									'%d'
								), 
								array( '%s' ) 
							);
	}

	private function check_same_session($user_record, $current_date){

		$last_date = date_create($user_record->last_product_view);

		$to_time = strtotime($user_record->last_product_view);
		$from_time = strtotime($current_date);

		$diff = round(abs($to_time - $from_time));

		$same_session = true;

		if($diff >= perso_visitor_views::$SESSION_TIME_LIMIT){
			$same_session = false;
		}

		return $same_session;
	}

	private function insert_new_visitor($uid, $product_id, $current_date){

		$vv = new perso_product_view($current_date);
		$vv->product_id = $product_id;

		$psv = new perso_session_view($current_date);
		$psv->end   = $current_date;
		$psv->product_views[] = $vv;

		$psvArr[] = $psv;

		$this->db->insert($this->table_name, 
								array( 
									'last_product_view' => $current_date, 
									'uid' => $uid, 
									'product_views' => json_encode($psvArr), 
									'last_product_id' => $vv->product_id
								) 
							);
	}

	private function get_visitor_record($uid){
		$user_record = $this->db->get_row(
							$this->db->prepare("SELECT * 
												FROM $this->table_name
												WHERE uid = %s", 
												$uid));

		return $user_record;
	}
}

