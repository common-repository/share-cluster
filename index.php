<?php
/*
Plugin Name: Share Cluster
Plugin URI: http://products.shawndewolfe.com/product/share-cluster-1-0/
Description: You can post ads as a content type. These show up in the widget area. Links are clicked and people are sent through to a destination page. The links are shared to a network of other Share Cluster plugins active on other sites. Each site must name and be named to allow the transfer of content. Distribute news and information through the network of sites that you manage.
Author: Shawn DeWolfe
Version: 1.02
Author URI: http://www.shawndewolfe.com/
*/

/*

long phrase = share-cluster
short phrase tdcsc

use long phrase for init() call
use short phrase for other function naming builds

*/

define( 'SHARECLUSTER_PATH', plugin_dir_path( __FILE__ ) );  

define( 'SHARECLUSTER_URL', plugin_dir_url( __FILE__ ) );

define( 'SHARECLUSTER_INC', SHARECLUSTER_PATH . trailingslashit( 'inc' ), true );

global $wpdb;
define( 'SHARECLUSTER_SOURCETABLE', $wpdb->prefix . "tdcsc_sources");
define( 'SHARECLUSTER_ABUSETABLE', $wpdb->prefix . "tdcsc_abuses");
define( 'SHARECLUSTER_REPORTTABLE', $wpdb->prefix . "tdcsc_reports");


// your publickey key 
define( 'SHARECLUSTER_PUBLICKEY', md5(NONCE_SALT.site_url()));

// how old should these ads be?
define( 'SHARECLUSTER_AGING', (3600 * -24)); // one day old
define( 'SHARECLUSTER_CRON', "twicedaily"); // semi-daily old


/* Load the variables */
require_once('cluster-includes/variables.php');

/* Load the function relevant to the plugin */
require_once('cluster-includes/functions.php');

/* Load the widgets */
require_once('cluster-includes/widgets.php');

function tdcsc_register_activation_hook() {
	$dir = SHARECLUSTER_PATH.'/assets';
	$tdcsc_graft_assets = scandir($dir, 1);

	foreach ($tdcsc_graft_assets as $file) {
		$new_location = tdcsc_upload_attach_image($dir.'/'.$file, 1);
		update_option('tdcsc_'.$file, $new_location);		
	}
}

// Use the register_activation_hook to set default values
register_activation_hook(__FILE__, 'tdcsc_register_activation_hook');
register_activation_hook( __FILE__, 'tdcsc_install' );

add_action( 'wp', 'tdcsc_setup_schedule' );
/**
 * On an early action hook, check if the hook is scheduled - if not, schedule it.
 */
function tdcsc_setup_schedule() {
	if ( ! wp_next_scheduled( 'tdcsc_hourly_event' ) ) {
		wp_schedule_event( time(), SHARECLUSTER_CRON, 'tdcsc_cron_event');
	}
}


add_action( 'tdcsc_cron_event', 'tdcsc_do_the_cron' );
/**
 * On the scheduled action hook, run a function.
 */
function tdcsc_do_the_cron() {
	global $wpdb;
	$table_name = SHARECLUSTER_SOURCETABLE;
	$query = "SELECT indexid FROM $table_name WHERE UNIX_TIMESTAMP(lastcheck) < (UNIX_TIMESTAMP() + ".SHARECLUSTER_AGING.") ORDER BY indexid LIMIT 5";

	$ads = $wpdb->get_results($query);
	foreach ($ads as $ad) {
		tdcsc_get_remote_ads($ad->indexid);
	}
}

add_action('plugins_loaded', 'tdcsc_update_db_check' );

// Use the init action
add_action('init', 'tdcsc_init');
add_action( 'init', 'tdcsc_post_type', 9 );

/*
position:

2 Dashboard
4 Separator
5 Posts
10 Media
15 Links
20 Pages
25 Comments
59 Separator
60 Appearance
65 Plugins
70 Users
75 Tools
80 Settings
99 Separator
*/

add_action('admin_menu', 'tdcsc_admin_menu');

// Use the admin_init action to add register_setting
add_action('admin_init', 'tdcsc_admin_init' );


function tdcsc_update_db_check() {
    global $tdcsc_options;
    if (get_option("tdcsc_release_version") != $tdcsc_options['release_version']) {
        tdcsc_install();
    }
}



function tdcsc_check_for_new_version() {
	global $tdcsc_options;
    /* You probably shouldn't check for updates more than once a day, 
    for everyone's bandwidth's sake. */

    $last_check = get_option('tdcsc_lastcheck');

	// removing the phone home

    // Log that we've checked for an update now.
    update_option('tdcsc_lastcheck', time());
}

add_action('admin_notices', 'tdcsc_check_for_new_version');



function tdcsc_install() {
	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$table_name = SHARECLUSTER_SOURCETABLE;
	$sql = "CREATE TABLE $table_name (
		indexid mediumint(9) NOT NULL AUTO_INCREMENT,
		url VARCHAR(96) DEFAULT '' NOT NULL,
		publickey VARCHAR(32) DEFAULT '' NOT NULL,
		created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		lastcheck datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		`status` mediumint(9) NOT NULL,
		UNIQUE KEY `indexid` (`indexid`),
		UNIQUE KEY `url` (`url`)
	);";
	if (dbDelta( $sql )) {
		// great!
	}
	else {
		// then 
	}

	$abuse_name = SHARECLUSTER_ABUSETABLE;
	$sql = "CREATE TABLE $abuse_name (
		abuseid mediumint(9) NOT NULL AUTO_INCREMENT,
		remote VARCHAR(16) DEFAULT '' NOT NULL,
		hits mediumint(9) NOT NULL,
		lastcheck datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		UNIQUE KEY abuseid (abuseid)
	);";
	if (dbDelta( $sql )) {
		// great!
	}

	if (get_option( 'tdcsc_license_status') === 'valid') {
		tdcsc_updatepro();
	}

	$rows_affected = $wpdb->insert( $table_name, array('indexid' => 0, 'url' => site_url(''), 'publickey' => SHARECLUSTER_PUBLICKEY, 'created' => current_time('mysql'), 'lastcheck' => current_time('mysql'), 'status' => 1 ));
	// the TDC Example
	// $rows_affected = $wpdb->insert( $table_name, array('url' => 'http://www.thosedewolfes.com/share-cluster', 'publickey' => '3263827', 'created' => current_time('mysql'), 'lastcheck' => current_time('mysql'), 'status' => 1 ));

	global $tdcsc_options;
	foreach ($tdcsc_options as $key => $value) {
		update_option("tdcsc_".$key, $value); 
	}
}


function tdcsc_updatepro() {
	// not used in the free version
}

?>