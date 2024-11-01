<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$tdcsc_tabs = array();
$tdcsc_tabs['cluster-general'] = array(
	'long_name' => 'Share Cluster Settings', 
	'short_name' => 'Share Cluster', 
	'function' => 'tdcsc_general_page', 
	'access' => 'manage_options', 
	'slug' => 'cluster-general', 
	'parent' => '',
	'position' => 23,
	'type' => 'menu',
	);
$tdcsc_tabs['cluster-support'] = array(
	'long_name' => 'Share Cluster Support', 
	'short_name' => 'Support', 
	'function' => 'tdcsc_support_page', 
	'access' => 'manage_options', 
	'slug' => 'cluster-support', 
	'parent' => 'cluster-general',
	'position' => 3,
	'type' => 'submenu',
	);
$tdcsc_tabs['cluster-docs'] = array(
	'long_name' => 'Share Cluster Docs', 
	'short_name' => 'Documentation', 
	'function' => 'tdcsc_docs_page', 
	'access' => 'manage_options', 
	'slug' => 'cluster-docs', 
	'parent' => 'cluster-general',
	'position' => 4,
	'type' => 'submenu',
	);
$tdcsc_tabs['cluster-license'] = array(
	'long_name' => 'Share Cluster Licensing', 
	'short_name' => 'License', 
	'function' => 'tdcsc_license_options', 
	'access' => 'manage_options', 
	'slug' => 'tdcsc_license_options', 
	'parent' => 'cluster-general',
	'position' => -1,
	'type' => 'submenu',
	);

$tdcsc_options = array();
$tdcsc_options['release_version'] = "1.02";
$tdcsc_options['release_date'] = "August 5, 2014";
$tdcsc_options['home_page'] = "http://www.thosedewolfes.com/product/share-cluster.html";
$tdcsc_options['wordpress_source'] = "http://wordpress.org/extend/plugins/share-cluster/";

$tdcsc_options['plugin_name'] = "Share Cluster";
$tdcsc_options['author'] = "Shawn DeWolfe";

?>