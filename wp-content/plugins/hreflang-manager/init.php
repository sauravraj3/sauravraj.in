<?php
/*
Plugin Name: Hreflang Manager
Description: Set language and regional URL for better SEO performance.
Version: 1.09
Author: DAEXT
Author URI: https://daext.com
*/

//Prevent direct access to this file
if ( ! defined( 'WPINC' ) ) { die(); }

//Class shared across public and admin
require_once( plugin_dir_path( __FILE__ ) . 'shared/class-dahm-shared.php' );

//Public
require_once( plugin_dir_path( __FILE__ ) . 'public/class-dahm-public.php' );
add_action( 'plugins_loaded', array( 'Dahm_Public', 'get_instance' ) );

//Perform the Gutenberg related activities only if Gutenberg is present
if ( function_exists( 'register_block_type' ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'blocks/src/init.php' );
}

//Admin
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
    
    //Admin
    require_once( plugin_dir_path( __FILE__ ) . 'admin/class-dahm-admin.php' );
    add_action( 'plugins_loaded', array( 'Dahm_Admin', 'get_instance' ) );
    
    //Activate
    register_activation_hook( __FILE__, array( Dahm_Admin::get_instance(), 'ac_activate' ) );
    
}