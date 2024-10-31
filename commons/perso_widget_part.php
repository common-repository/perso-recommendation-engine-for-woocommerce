<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds My_Widget widget.
 */
class perso_widget_part{

	private $reco_list;

	function __construct($reco_list) {
		$this->reco_list = $reco_list;
	}

	public function get_widget_list(){
		$output = '<ul class="product_list_widget">';
        foreach ($this->reco_list as $product) {
            $output .= '<li>';
            //$output .= '<a href="'. esc_url( get_permalink($product->id) ).'" onClick="perso_reco_link_clicked();">';
			$output .= '<a href="'. esc_url( get_permalink($product->id) ).'">';
			$output .= $product->get_image();
            $output .= '<span class="product-title">'.$product->get_title().'</span>';
     
     		$output .= '</a>';
            
            $output .= '<span class="amount">'.wc_price($product->get_price()).'</span>';
            $output .= '</li>';
        }
		$output .= '</ul>';

		return $output;
	}
}