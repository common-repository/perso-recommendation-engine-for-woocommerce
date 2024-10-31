<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class perso_reco_buy_footer {


	function __construct() {

	}

    private function query($piv){

        
    }

    private function writeFooter($title, $products){

      if(!$displayTitle){$displayTitle=__( $title, 'perso' );}

      ?>
            <div class="related products">                   
                <h2><?php echo $displayTitle; ?></h2>
                <?php // Start the loop     
                 woocommerce_product_loop_start();
                 while ( $products->have_posts() ) : $products->the_post(); ?>
                     <?php wc_get_template_part( 'content', 'product' ); ?>
                 <?php endwhile; ?>
                 <?php woocommerce_product_loop_end(); ?>
            </div>
        <?php
    }

	public function customer_also_bought( $atts, $content = null ) {

        $settings_options = get_option( 'perso_option_name' );

        $display = ($settings_options['display_footer_reco_buy'] == 'yes')?true:false;
        
        if($display){
            global $post;
            $piv = new perso_load_reco_buy_footer();
            
            if ($piv->has_reco($post->ID)){

                $prods = $piv->load_data_from_order_table($post->ID);

                $query_args = array('no_found_rows'  => 1, 
                                    'post_status'    => 'publish', 
                                    'post_type'      => 'product',
                                    'post__in'       => $prods                       
                                   );

                $products = new WP_Query($query_args);
                
                $title = ($settings_options['title_footer_reco_buy'] != '')?
                     $settings_options['title_footer_reco_buy']:
                     "Customer who bought this also bought";

                $this->writeFooter($title, $products);
            }
            
        }


        wp_reset_postdata();

    }

}
