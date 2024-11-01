<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$license = false;
$email = get_option( 'tdcsc_registered_email' );
$status = get_option( 'tdcsc_license_status' );
?>
<div class="wrap">
	<h2><?php _e('Plugin License Options'); ?></h2>
	<?php 
		
	/*
	if ($result != '') {
		if ($result === TRUE) {
			$result_text = 'The license key is valid';
		}
		else {
			$result_text = 'The license key could not be validated';		
		}
		print '<div style="color: #00ff00; border: 1px solid #cccccc; padding: 10px; margin: 10px;">'.$result_text.'</div>';
	}
	*/

	$action_url = $_SERVER['REQUEST_URI'];
	
	?>
	<br/>
	There is a premium version of Share Cluster available, <a href="http://license.thosedewolfes.com/product/share-cluster/">Share Cluster Prime</a>, if you wanted to both use expanded functionality and, in doing so, help to support the product development. 
