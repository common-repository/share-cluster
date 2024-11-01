<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<div class="wrap">
	<h2><?php _e('Share Cluster Support'); ?></h2>
	<div class="postbox">
	<!-- list the topics and link to the home site -->
	<h4>Basic Support</h4>
	You can get to the WP support section by going to this <a href="http://www.thosedewolfes.com/wp/share-cluster/support.html">Link</a> that redirect you to the site.
	<br/>
	<?php 
	$status = get_option( 'tdcsc_license_status' );
	if ($status == 'valid') {
		// show if valid
	?>
	<br/>
	<h4>Prime Support</h4>
	You can get to the Prime WP support section by going to this <a href="http://www.thosedewolfes.com/wp/share-cluster/support-prime/">Link</a>.
	<?
	}
	?>
	</div>
</div>