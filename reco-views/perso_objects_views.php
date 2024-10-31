<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class perso_session_view{
	public $start;
	public $end;
	public $product_views;

	public function __construct($current_date){		
		$this->start = $current_date;
		$product_views = array();
	}	

	public function set_end_date($current_date){
		$this->end = $current_date;
	}
}

class perso_product_view{
	public $product_id;
	public $view_time;

	public function __construct($current_date){		
		$this->view_time = $current_date;
	}
}

class perso_product_session_view{
	public $product_id;
	public $views;

	public function __construct(){	
	}
}
