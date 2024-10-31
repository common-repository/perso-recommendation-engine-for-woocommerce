<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds My_Widget widget.
 */
class PersoRecoSetting{

	/**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Perso Settings', 
            'manage_options', 
            'perso_settings', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'perso_option_name' );
        ?>
        <div class="wrap">
            <h2>Perso Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'perso_option_group' );   
                do_settings_sections( 'perso-setting-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'perso_option_group', // Option group
            'perso_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Max Item in Recommendations Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'perso-setting-admin' // Page
        );  

        add_settings_field(
            'max_reco_other_views', // ID
            'Max Items in Recommendations (Others also Views)', // Title 
            array( $this, 'max_reco_other_views_callback' ), // Callback
            'perso-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'max_reco_other_buys', // ID
            'Max Items in Recommendations (Others also Buys)', // Title 
            array( $this, 'max_reco_other_buys_callback' ), 
            'perso-setting-admin', 
            'setting_section_id'
        );

        add_settings_field(
            'display_footer_reco_view', // ID
            "Display 'Customers Also Viewed Recommendation' on Footer of product page", // Title 
            array( $this, 'display_footer_reco_view' ), 
            'perso-setting-admin', 
            'setting_section_id'
        );      

        add_settings_field(
            'display_footer_reco_buy', // ID
            "Display 'Customers Also Bought' Recommendation on Footer of product page", // Title 
            array( $this, 'display_footer_reco_buy' ), 
            'perso-setting-admin', 
            'setting_section_id'
        );      

        add_settings_field(
            'title_footer_reco_view', // ID
            "Title for 'Customers Also Viewed' Recommendation on Footer of product page", // Title 
            array( $this, 'title_footer_reco_view' ), 
            'perso-setting-admin', 
            'setting_section_id'
        );      

        add_settings_field(
            'title_footer_reco_buy', // ID
            "Title for 'Customers Also Bought' Recommendation on Footer of product page", // Title 
            array( $this, 'title_footer_reco_buy' ), 
            'perso-setting-admin', 
            'setting_section_id'
        );      


    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['max_reco_other_views'] ) )
            $new_input['max_reco_other_views'] = absint( $input['max_reco_other_views'] );

        if( isset( $input['max_reco_other_buys'] ) )
            $new_input['max_reco_other_buys'] = absint( $input['max_reco_other_buys'] );

        $new_input['display_footer_reco_view'] = $input['display_footer_reco_view'];
        $new_input['display_footer_reco_buy'] = $input['display_footer_reco_buy'];
        $new_input['title_footer_reco_buy'] = trim($input['title_footer_reco_buy']);
        $new_input['title_footer_reco_view'] = trim($input['title_footer_reco_view']);
        
        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function max_reco_other_views_callback()
    {
        $select_component = '<select id="max_reco_other_views" name="perso_option_name[max_reco_other_views]">';
        $max_item = ($this->options['max_reco_other_views'] != '') ? $this->options['max_reco_other_views'] : 5;

        $max = 10;
        for ($i = 3; $i <= $max; $i++) {
            if ($i == $max_item){
                $select_component .= '<option value='.$i.' selected>'.$i.'</option>';
            }else{
                $select_component .= '<option value='.$i.'>'.$i.'</option>';
            }
        }

        $select_component .= '</select>';

        echo $select_component;

    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function max_reco_other_buys_callback()
    {
        $select_component = '<select id="max_reco_other_buys" name="perso_option_name[max_reco_other_buys]">';
        $max_item = ($this->options['max_reco_other_buys'] != '') ? $this->options['max_reco_other_buys'] : 5;

        $max = 10;
        for ($i = 3; $i <= $max; $i++) {
            if ($i == $max_item){
                $select_component .= '<option value='.$i.' selected>'.$i.'</option>';
            }else{
                $select_component .= '<option value='.$i.'>'.$i.'</option>';
            }
        }

        $select_component .= '</select>';

        echo $select_component;

    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function display_footer_reco_view()
    {
        $check_component = '<input type="checkbox" name="perso_option_name[display_footer_reco_view]"';

        if($this->options['display_footer_reco_view'] == 'yes'){
            $check_component .= ' value="yes" checked>';    
        }else{
            $check_component .= ' value="yes">';
        }
        
        echo $check_component;

    }

    public function display_footer_reco_buy()
    {
        $check_component = '<input type="checkbox" name="perso_option_name[display_footer_reco_buy]"';

        if($this->options['display_footer_reco_buy'] == 'yes'){
            $check_component .= ' value="yes" checked>';    
        }else{
            $check_component .= ' value="yes">';
        }
        
        echo $check_component;

    }

    public function title_footer_reco_view()
    {
        $check_component = '<input type="text" name="perso_option_name[title_footer_reco_view]" size=40';

        if($this->options['title_footer_reco_view'] != ''){
            $check_component .= ' value="'.$this->options['title_footer_reco_view'].'">';    
        }else{
            $check_component .= ' value="Customer who viewed this also viewed">';
        }
        
        echo $check_component;

    }

    public function title_footer_reco_buy()
    {
        $check_component = '<input type="text" name="perso_option_name[title_footer_reco_buy]" size=40';

        if($this->options['title_footer_reco_buy'] != ''){
            $check_component .= ' value="'.$this->options['title_footer_reco_buy'].'">';    
        }else{
            $check_component .= ' value="Customer who bought this also bought">';
        }
        
        echo $check_component;

    }
}