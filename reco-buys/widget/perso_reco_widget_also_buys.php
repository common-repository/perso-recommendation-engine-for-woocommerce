<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class perso_reco_widget_also_buys extends WP_Widget {

	function __construct() {
        
        $widget_ops = array( 
            'class_name' => 'PersoRecoWidgetAlsoBuys',
            'description' => 'Perso Recommendation Widget (What others also bought)',
        );

        parent::__construct( 'Perso_Also_Buy_Widget', 'Perso Also Buy Widget', $widget_ops );

    }

	 /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        global $post;

        //$plr = new perso_load_reco_buy($post->ID);
        $plr = new perso_load_reco_buy_footer();


        if(is_single() && $plr->has_reco($post->ID)){


            echo $args['before_widget'];

            echo '<div id="perso-others-buys-title">';

            if ( ! empty( $instance['title'] ) ) {
                echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). 
                    $args['after_title'];
            }

            echo '</div>';   

            echo '<img id="ajax-buys-loader" 
                    style="display:none;"
                    src="' . plugins_url( 'img/ajax-loader.gif', dirname(__FILE__) ) . '" > ';

            echo '<div id="perso-others-buys" style="display:none;" class="widget">';
            echo '</div>';   

            echo $args['after_widget']; 

        }
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'text_domain' );
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
            	<?php _e( 'Title:' ); ?>
            </label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" 
                   name="<?php echo $this->get_field_name( 'title' ); ?>" 
                   type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php 
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? 
        						strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }
}