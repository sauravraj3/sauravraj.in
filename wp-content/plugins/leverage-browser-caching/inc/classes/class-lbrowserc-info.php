<?php
/**
 * Info class.
 *
 * @package 	Leverage Browser Caching
 */

if ( ! class_exists( 'Lbrowserc_Info' ) ) {
	/**
	 * Core class of plugin.
	 */
	class Lbrowserc_Info {

		/**
		 * This will add code, if not found. and also call deactivation_hook
		 */
		public function __construct() {
			add_action( 'admin_notices', array( $this, 'top_msg' ) );
			add_action( 'admin_init', array( $this, 'handle_msg' ) );
			add_action('switch_theme', array( $this, 'delete_user_meta_ignore_msg' ) );
		}

		public function top_msg() {
			$action = 'install-plugin';
			$slug = 'di-blocks';
			$installation_link = wp_nonce_url(
				add_query_arg(
					array(
						'action' => $action,
						'plugin' => $slug
					),
					admin_url( 'update.php' )
				),
				$action.'_'.$slug
			);

			global $current_user ;
			$user_id = $current_user->ID;
			/* Check that the user hasn't already clicked to ignore the message */
			if( ! get_user_meta( $user_id, 'lbrowserc_ignore_notice' ) ) {
				echo '<div class="updated"><p>';
				
				printf( __( 'Using new WordPress Editor? Let\'s try <a href="%1$s">Di Blocks Plugin</a>. Recommended by Leverage Browser Caching plugin. | <a href="%2$s">Hide it</a>', 'lbrowserc' ), esc_url( $installation_link ), esc_url( add_query_arg( 'lbrowserc_ignore_notice', '0' ) ) );
				
				echo "</p></div>";
			}
		}

		public function handle_msg() {
			global $current_user;
			$user_id = $current_user->ID;
			if( isset( $_GET['lbrowserc_ignore_notice']) && '0' == $_GET['lbrowserc_ignore_notice'] ) {
				add_user_meta( $user_id, 'lbrowserc_ignore_notice', 'true', true );
			}
		}

		public function delete_user_meta_ignore_msg() {
			global $current_user;
			$user_id = $current_user->ID;
			if( get_user_meta( $user_id, 'lbrowserc_ignore_notice' ) ) {
				delete_user_meta( $user_id, 'lbrowserc_ignore_notice' );
			}
		}
	}
}
