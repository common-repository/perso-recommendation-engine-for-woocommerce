<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class perso_reco_view_footer {


	function __construct() {

	}

    private function query($piv){
        $prod_key_value = $piv->getProductIdViews();

        $query_args = array('no_found_rows'  => 1, 
                            'post_status'    => 'publish', 
                            'ignore_sticky_posts'  => 1,
                            'post_type'      => 'product',
                            'post__in'       => array_keys($prod_key_value)                         
                           );

        return new WP_Query($query_args);
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

	public function customer_also_viewed( $atts, $content = null ) {

        $settings_options = get_option( 'perso_option_name' );

        $display = ($settings_options['display_footer_reco_view'] == 'yes')?true:false;

        if($display){
            global $post;
            $piv = new perso_load_reco_views($post->ID);
        
            if ($piv->has_reco()){

                $products = $this->query($piv);

                $title = ($settings_options['title_footer_reco_view'] != '')?
                     $settings_options['title_footer_reco_view']:
                     "Customer who viewed this also viewed";

                $this->writeFooter($title,$products);
            }
            
        }



        wp_reset_postdata();

    }

}
