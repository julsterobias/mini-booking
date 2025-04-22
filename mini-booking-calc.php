<?php 
/**
*
*
*
* Plugin Name: Mini Booking Calculator
* Description: Test project
* Author:      Juls Terobias
* Contributors: julsterobias
* Plugin Type: Test Project
* Author URI: #
* Plugin URI: #
* Version: 0.0.0.1
* Text Domain: minibookcalc
* License:     GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl.html
*
*
*
*/


defined( 'ABSPATH' ) or die( 'No access area' );
define('MINIBOOKCAL_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('MINIBOOKCAL_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('MINIBOOKCAL_PLUGIN_VERSION','0.0.0.1');
define('MINIBOOKCAL_PLUGIN_VERSION_CODE','Alpha');
define('MINIBOOKCAL_NAMESPACES', ['includes','admin/includes']);
define('MINIBOOKCAL_TABLE_NAME', 'mini_booking');

/**
*
*
* Load text domain from languages
* @since 1.0.0
*
*
*/
add_action( 'init', 'minibookcalc_load_text_domain' );

function minibookcalc_load_text_domain() {
	load_plugin_textdomain( 'minibookcalc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}


/**
 * 
 * minibookcalc_activate_plugin
 * trigger code during activation
 * @since 1.0.0
 * 
 */
register_activation_hook( __FILE__, 'minibookcalc_activate_plugin' );

if (!function_exists('minibookcalc_activate_plugin')) {

	function minibookcalc_activate_plugin()
	{
        //do all activate hooks here
		//register database here
		if ( function_exists('minibookcalc_prepare_table') ) {
			minibookcalc_prepare_table();
		} else {
			add_action('admin_notices', 'my_custom_admin_error_notice');
			function my_custom_admin_error_notice() {
				?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e('Failed to create the required database table.', 'minibookcalc'); ?></p>
				</div>
				<?php
			}
		}
	}
}

/**
 * 
 * 
 * minibookcalc_deactivate_plugin
 * trigger code during deactivation
 * @since 1.0.0
 * 
 * 
 */
register_deactivation_hook( __FILE__, 'minibookcalc_deactivate_plugin' );

if (!function_exists('minibookcalc_deactivate_plugin')) {
	function minibookcalc_deactivate_plugin()
	{
        //do all inactivation hooks here
	}
}

/**
 * 
 * 
 * autoloader
 * load required files
 * @since 1.0.0
 * @param
 * @return
 * 
 * 
 */
function minibookcalc_init_classes()
{
    //load in priority
    include_once 'includes/class-utils.php';

    foreach (MINIBOOKCAL_NAMESPACES as $path) {
        $fullpath = MINIBOOKCAL_PLUGIN_PATH.$path;
        if (is_dir($fullpath)) {
            //get files
            $files = scandir($fullpath);
            
            if (!empty($files)) { 
                foreach ($files as $file) {
                    // Check if it's a file
                    $fullfile = $fullpath . '/' . $file;
                    if (is_file($fullfile)) {
                        $file_extension = pathinfo($file, PATHINFO_EXTENSION); // Get the file extension
                        // Load the php only
                        if ($file_extension === 'php') {
                            include_once $fullfile;
                        }
                    } 
                }
            } 

        }  

    }


}


/**
 * 
 * minibookcalc_plugin_loaded
 * load plugin hook
 * @since 1.0.0
 * 
 */
add_action('plugins_loaded', 'minibookcalc_plugin_loaded');
function minibookcalc_plugin_loaded(){
    if (function_exists('minibookcalc_init_classes')) {
        minibookcalc_init_classes();
        new minibookcalc\admin\includes\minibookcalc_admin; 
    }
}

/**
 * 
 * create the custom DB table
 * 
 */
if ( !function_exists('minibookcalc_prepare_table') ) {
	
	function minibookcalc_prepare_table() {
		
		global $wpdb;
		$table_name = $wpdb->prefix . MINIBOOKCAL_TABLE_NAME; 
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
				ID mediumint(9) NOT NULL AUTO_INCREMENT,
				name varchar(100) NOT NULL,
				address text NOT NULL,
				distance float DEFAULT 0,
				number_of_rooms int DEFAULT 0,
				PRIMARY KEY  (ID)
		) $charset_collate;";
		dbDelta( $sql );

	}

}


?>