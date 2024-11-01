<?php

/**** CALLS ****/
/* FUNCTION FILTER CALLS */

// add_filter( 'posts_where', 'tdcsc_imgsearch_posts_where', 10, 2 );

/* FUNCTION OPTION CALLS */

/*
if (isset($default_tdcsc_general_options)) {
	add_option('tdcsc_xxx_options', $default_tdcsc_xxx_options);
}
*/

/* FUNCTION ACTION CALLS */

add_action('parse_request', 'tdcsc_json_output', 4);
add_action('admin_menu', 'add_tdcsc_options_menu', 3);

add_action('add_meta_boxes', 'tdcsc_meta_boxes');
add_action('save_post', 'tdcsc_meta_all_update');

add_action('wp_ajax_nopriv_tdcsc_ajax_function', 'tdcsc_ajax_function');
add_action('wp_ajax_tdcsc_ajax_function', 'tdcsc_ajax_function');

/* FUNCTION SHORTCODE CALLS */

// add_shortcode('tdcscone', 'tdcscone_shortcode');


/**** FUNCTIONS ****/
/* FUNCTION INIT FUNCTIONS */

/* naming convention exception */
function tdcsc_init() {
    $tdcsc_options['general'] = get_option('tdcsc_options_general');
	$tdcsc_options['docs'] = get_option('tdcsc_options_docs');
    $tdcsc_options['support'] = get_option('tdcsc_options_support');
    $tdcsc_options['license'] = get_option('tdcsc_options_license');
}

/**
 * Adds share-cluster options
 * @return null
 */
function tdcsc_admin_init(){
    register_setting( 'tdcsc_general_options', 'tdcsc_general_options' );
    register_setting( 'tdcsc_about_options', 'tdcsc_about_options' );
}

/* FUNCTION FILTER FUNCTIONS */

function tdcsc_filter_x() {

}

/* FUNCTION OPTION FUNCTIONS */

function tdcsc_option_x() {

}

/* FUNCTION ACTION FUNCTIONS */

function tdcsc_action_x() {

}

/* FUNCTION SHORTCODE FUNCTIONS */

function tdcsc_shortcode_x() {

}

/* FUNCTION ADMIN FUNCTIONS */

/**
 * Adds admin menu page(s)
 * @return null
 */
function tdcsc_admin_menu() {
	global $tdcsc_tabs;
	foreach ($tdcsc_tabs as $slug => $value) {
		if ($value['type'] == 'menu') {
			add_menu_page($value['long_name'], $value['short_name'], $value['access'], $value['slug'], $value['function'], tdcsc_get_asset('icon32.png'), $value['position']);	
		}
	}

	foreach ($tdcsc_tabs as $slug => $value) {
		if ($value['type'] == 'submenu') {
			add_submenu_page($value['parent'], $value['long_name'], $value['short_name'], $value['access'], $value['slug'], $value['function']);
		}
	}
}

// the header common for all use cases of admin screens
function tdcsc_options_admin_head() { 
$output = <<<EOD
<style type="text/css">
.container {width: 95%; margin: 10px 0px; font-family: "Lucida Grande", Verdana, Arial, "Bitstream Vera Sans", sans-serif;}
ul.tabs {margin: 0;padding: 0;float: left;list-style: none;height: 25px;border-bottom: 1px solid #e3e3e3;border-left: 1px solid #e3e3e3;width: 100%;}
ul.tabs li {float: left;margin: 0;padding: 0;	height: 24px;line-height: 24px;border: 1px solid #e3e3e3;border-left: none;margin-bottom: -1px;background:#EBEBEB;overflow: hidden;position: relative; background-repeat:repeat-x;}
ul.tabs li a {text-decoration: none;color: #21759b;display: block;font-size: 12px;padding: 0 20px;border: 1px solid #fff;outline: none;}
ul.tabs li a:hover {color: #d54e21;}
html ul.tabs li.active, html ul.tabs li.active a:hover  {background: #fff;border-bottom: 1px solid #fff;}
.tab_container {border: 1px solid #e3e3e3;border-top: none;clear: both;float: left; width: 100%;background: #fff;font-size:11px;}
.tab_content {padding: 20px;font-size: 1.2em;}
.tab_content h3 {margin-top:0px;margin-bottom:10px;}
.tab_content .head-description{font-style:italic;}
.tab_content .description{padding-left:15px}
.tab_content ul li{list-style:square outside; margin-left:20px}

div.postbox { padding: 10px; display: block; float: left; margin: 0 0px 6px 0; }

a.delete_source { background-color: red; color: white; padding: 6px; -webkit-border-radius: 4px; border-radius: 4px; -webkit-box-shadow:  1px 2px 3px 3px rgba(90, 80, 80, 0.6); text-decoration: none; box-shadow:  1px 2px 3px 3px rgba(90, 80, 80, 0.6); border-bottom: #aa0000 1px solid; border-right: #bb0000 1px solid; }
a.add_source { background-color: #44aa44; color: white; padding: 6px; -webkit-border-radius: 4px; border-radius: 4px; -webkit-box-shadow:  1px 2px 3px 3px rgba(70, 90, 80, 0.6); text-decoration: none; box-shadow:  1px 2px 3px 3px rgba(70, 90, 80, 0.6); border-bottom: #00aa00 1px solid; border-right: #00bb00 1px solid; }

.zebra-1 td { background-color: #eeeeee; }
.zebra-0 td, .zebra-2 td { background-color: #ffffff; }

form div.filter_where, form div.filter_order { display: block; float: left; min-width: 15%; padding-right: 16px; }
form div.filter_submit, form div.filter_reset { display: block; float: left; min-width: 6%; padding-right: 0px; }

form div.filter_submit input { display: block; float: left; padding: 3px 10px 5px 10px; margin: 0px !important; border: 1px solid #888; border-top: 1px solid #aaa; background-color: #ccc; -webkit-border-radius: 4px; border-radius: 4px; font-weight: 400; color: #333; text-decoration: none; height: 30px !important; }

form div.filter_reset a { display: block; float: left; padding: 4px 10px 4px 10px; margin: 0px !important; border: 1px solid #888; border-top: 1px solid #aaa; background-color: #ccc; -webkit-border-radius: 4px; border-radius: 4px; font-weight: 400; color: #333; text-decoration: none; height: 20px !important; }

</style>
<script type="text/javascript">
jQuery(document).ready(function() {
	//On Click Event
	jQuery("ul.tabs li").click(function() {
			var activeTab = jQuery(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
			jQuery(activeTab).show();
			return false;
	});
});
</script>
EOD;
	return $output;
}

function tdcsc_top_element($current_tab = 'cluster-general') {
	global $tdcsc_tabs;

	$output = '<div class="container"><ul class="tabs">';
	foreach ($tdcsc_tabs as $slug => $value) {
		if ($value['type'] == 'menu') {
			$output .= '<li class="nav-tab '.(($current_tab == $slug) ? 'active' : '').'"><a href="'.site_url('/wp-admin/admin.php').'?page='.$value['slug'].'">'.translate($value['short_name'], 'share-cluster').'</a></li>';
		}
		if ($value['type'] == 'submenu') {
			$output .= '<li class="nav-tab '.(($current_tab == $slug) ? 'active' : '').'"><a href="'.site_url('/wp-admin/admin.php').'?page='.$value['slug'].'">'.translate($value['short_name'], 'share-cluster').'</a></li>';
		}
		if ($value['type'] == 'option') {
			$output .= '<li class="nav-tab '.(($current_tab == $slug) ? 'active' : '').'"><a href="'.site_url('/wp-admin/options-general.php').'?page='.$value['slug'].'">'.translate($value['short_name'], 'share-cluster').'</a></li>';		
		}
	}
	$output .= '</ul><div class="tab_container"><div id="tab1" class="tab_content">';
	return $output;
}

function tdcsc_beg_element($current_tab = 'cluster-general') {
	global $tdcsc_options;
	$output = '<div class="beg" style="clear: both; margin-top: 20px; margin-bottom; 40px;">';
	$status = get_option( 'tdcsc_license_status' );
	if ($status != 'valid') {
		// hide the beg if valid
$output .= <<<EOD
<div class="postbox" id="paypal-box" style="width: 30% !important; min-height: 290px; float: left; margin: 10px 10px 10px 0; padding: 10px;">
<h3 class="hndle"><span>Show Your Love</span></h3>
<div class="inside">
<p><b>We put a lot of time and effort into this plugin to make it right for you.</b> When you donate to us, it underwrites our ability to support this and other plugins.</p>
<p align="center"><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="QRMC38YYFP8LA"><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"></form></p>
</div>
</div>
EOD;

$output .= <<<EOD
<div class="postbox" id="rate-box" style="width: 30% !important; min-height: 290px; float: left; margin: 10px; padding: 10px;">
<h3 class="hndle"><span>Rate Share Cluster</span></h3>
<div class="inside">
EOD;

$output .= 'You can help us by doing two simple things: <a href="'.$tdcsc_options['wordpress_source'].'" target="_blank">Go to WordPress.org now and give this plugin a 5-star rating</a>. Blog about Share Cluster and link to the <a href="'.$tdcsc_options['wordpress_source'].'" target="_blank">plugin page</a>. Spreading the word helps us grow.<br/><br/>';
$output .= '<strong>Help Yourself! </strong>Upgrade to Share Cluster Prime! <a href="'.$tdcsc_options['home_page'].'">Click here</a> to get the extra features.';

$output .= <<<EOD
</div>
EOD;
	}
$output .= <<<EOD
</div>
<div class="postbox" id="about-box" style="width: 30% !important; min-height: 290px; float: left; margin: 10px; padding: 10px;">
<h3 class="hndle"><span>About Share Cluster</span></h3>
<div class="inside">
<p><b>Version 
EOD;

$output .= $tdcsc_options['release_version'];

$output .= <<<EOD
</b><br>
<small>Release Date:  
EOD;

$output .= $tdcsc_options['release_date'];

$output .= <<<EOD
</small><br/>
<small>Share Cluster Home Page:
EOD;

$output .= '<a href="'.$tdcsc_options['home_page'].'">'.$tdcsc_options['home_page'].'</a>';

$output .= <<<EOD
</small></p>

<p><b>Minimum Requirements</b><br />
<small>WordPress 3.4</small><br />
<small>PHP 5.2.6</small><br />
<small>MySQL 5.0.45</small><br /></p>

<p><small>Developed and maintained by 
EOD;

$output .= $tdcsc_options['author'];

$output .= <<<EOD
<br /><a href="http://www.thosedewolfes.com/" target="_blank">http://www.thosedewolfes.com</a></small></p>
</div>
</div>
EOD;
	$output .= "</div>";
	return $output;
}


function tdcsc_btm_element($current_tab = 'cluster-general') {
	global $tdcsc_options;
	$output = "</div></div>";
	$output .= '<div style="width: 200px; position: absolute; right: 10px; bottom: 30px;">Version '.$tdcsc_options['release_version'].' - '.$tdcsc_options['release_date'].'</div>';
	return $output;
}


function tdcsc_general_page() {
	// add in the interactivity call
	tdcsc_options_form_submit();
	print tdcsc_options_admin_head();
	print tdcsc_top_element('cluster-general');
	require_once(WP_PLUGIN_DIR.'/share-cluster/cluster-admin/general_tab.php');
	print tdcsc_btm_element('cluster-general');
}

function tdcsc_docs_page() {
	print tdcsc_options_admin_head();
	print tdcsc_top_element('cluster-docs');
	require_once(WP_PLUGIN_DIR.'/share-cluster/cluster-admin/docs_tab.php');
	print tdcsc_beg_element('cluster-docs');
	print tdcsc_btm_element('cluster-docs');
}

function tdcsc_support_page() {
	print tdcsc_options_admin_head();
	print tdcsc_top_element('cluster-support');
	require_once(WP_PLUGIN_DIR.'/share-cluster/cluster-admin/support_tab.php');
	print tdcsc_beg_element('cluster-support');
	print tdcsc_btm_element('cluster-support');
}

function tdcsc_license_page($msg = "") {
	print tdcsc_options_admin_head();
	print tdcsc_top_element('cluster-license');
	print $msg;
	require_once(WP_PLUGIN_DIR.'/share-cluster/cluster-admin/license_tab.php');
	print tdcsc_btm_element('cluster-license');
}

/* CUSTOM POST TYPE - ADS */

// Register Custom Post Type
function tdcsc_post_type() {

	$labels = array(
		'name'                => _x( 'Shared Posts', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Shared Post', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Shared Posts', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Post:', 'text_domain' ),
		'all_items'           => __( 'All Posts', 'text_domain' ),
		'view_item'           => __( 'View Post', 'text_domain' ),
		'add_new_item'        => __( 'Add New Shared Post', 'text_domain' ),
		'add_new'             => __( 'New Post', 'text_domain' ),
		'edit_item'           => __( 'Edit Post', 'text_domain' ),
		'update_item'         => __( 'Update Post', 'text_domain' ),
		'search_items'        => __( 'Search Post', 'text_domain' ),
		'not_found'           => __( 'No shared posts found', 'text_domain' ),
		'not_found_in_trash'  => __( 'No posts found in Trash', 'text_domain' ),
	);

	$args = array(
		'labels' => $labels,
		'description'         => __( 'Shared Content', 'text_domain' ),
		'public' => true,
		'exclude_from_search' => true,
		'publickeyly_queryable'  => false,
		'show_ui' => true, 
		'show_in_nav_menus'   => false,
		'show_in_menu' => true,
		'show_in_admin_bar' => true,
		'menu_position'       => 20,
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array('title','editor', 'thumbnail', 'revisions',),
		'has_archive' => true,
		'rewrite' => array('slug' => 'sharead', 'with_front' => true),
		'query_var' => true,
		'can_export' => true
	); 

	register_post_type('sharead', $args );
	flush_rewrite_rules();
}

/*

Meta box information

*/

function tdcsc_meta_boxes() {
	global $post;
	add_meta_box( 'tdcsc_dimensions', __( 'Ad Details', 'share-cluster' ), 'tdcsc_meta_box_dimensions', 'sharead', 'side', 'high');
}

/**
 * Display the dimensions meta box.
 *
 * @access publickey
 * @param mixed $post
 * @return void
 */
function tdcsc_meta_box_dimensions( $post ) {
	global $wpdb;
	$tdcsc_dimension = get_post_meta($post->ID, 'tdcsc_dimension', TRUE); 
	$tdcsc_source = max(0,intval(get_post_meta($post->ID, 'tdcsc_source', TRUE))); 	
	if ($tdcsc_source === 0) {
		$tdcsc_uuid = "-1";
	}
	else {
		$tdcsc_uuid = get_post_meta($post->ID, 'tdcsc_uuid', TRUE); 	
	}
	$sources = tdcsc_source_list();
	?>
	<strong>Ad Source</strong>: <?php print $sources[@$tdcsc_source]['url']; ?><br/>
	<input type="hidden" name="tdcsc_source" value="<?php echo @$tdcsc_source ?>"/>
	<input type="hidden" name="tdcsc_uuid" value="<?php echo @$tdcsc_uuid ?>"/>

	<ul class="dimensions submitbox">
		<li class="wide" id="actions">
			<select name="tdcsc_dimension">
				<option value=""><?php _e( 'Choose A Dimension', 'share-cluster' ); ?></option>
					<?php
					$dimensions = tdcsc_dimension_list();

					if ( ! empty( $dimensions ) ) {
						foreach ( $dimensions as $k => $v ) {
							echo '<option value="'. esc_attr( $k ) .'"'.($k == $tdcsc_dimension ? " selected" : "").'>' . esc_html( $v ) . '</option>';
						}
					}
					?>
			</select>
		</li>
	</ul>
	<?php
}

function tdcsc_meta_all_update($post_id) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;
    if ( 'sharead' == @$_POST['post_type'] ) {
		if ( !current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		if ($_POST['tdcsc_uuid'] == -1) {
			$tdcsc_uuid = md5(strtolower(site_url()))."-".$post_id;
		}
		else {
			$tdcsc_uuid = $_POST['tdcsc_uuid'];
		}
		if (!update_post_meta($post_id, 'tdcsc_uuid', $tdcsc_uuid)) {
			add_post_meta($post_id, 'tdcsc_uuid', $tdcsc_uuid);
		}

		if (!empty($_POST['tdcsc_dimension'])) {
			$dimensions = tdcsc_dimension_list();
			if (array_key_exists($_POST['tdcsc_dimension'], $dimensions)) {
				if (!update_post_meta($post_id, 'tdcsc_dimension', $_POST['tdcsc_dimension'])) {
					add_post_meta($post_id, 'tdcsc_dimension', $_POST['tdcsc_dimension']);
				}
			}
		}
		if (intval($_POST['tdcsc_source']) > -1) {
			if (!update_post_meta($post_id, 'tdcsc_source', $_POST['tdcsc_source'])) {
				add_post_meta($post_id, 'tdcsc_source', $_POST['tdcsc_source']);
			}
		}
	}
}

/*

updata_post_meta($post->ID, 'tdcsc_source') set on import 
updata_post_meta($post->ID, 'tdcsc_lastupdate') set on import or overwritten with editing locally it's always derived from the post's local save time

*/

/* JSON / API calls */

/* JSON absue precautions */

/* ask: is this an abused call? */
function tdcsc_json_ask_abuse() {
	global $wpdb;
	$table_name = SHARECLUSTER_ABUSETABLE;
	$query = "SELECT remote, hits, lastcheck FROM $table_name WHERE remote LIKE '".$_SERVER['REMOTE_ADDR']."' AND TIMESTAMPDIFF(SECOND,lastcheck,TIMESTAMP(NOW())) < 600";
	$abuses = $wpdb->get_results($query);
	foreach ($abuses as $abuse) {
		return $abuse->hits;
	}
}

/* say: this looks like an abused call? */
function tdcsc_json_say_abuse($hits) {
	global $wpdb;
	$table_name = SHARECLUSTER_ABUSETABLE;
	$rows_affected = $wpdb->replace( $table_name, array('remote' => $_SERVER['REMOTE_ADDR'], 'hits' => intval($hits), 'lastcheck' => current_time('mysql')));
}

function tdcsc_json_output() {
	$hits = 1;
	if (strlen(@$_GET['publickey']) < 3) {
		// that's too short, just go back
		return true;
	}

	if ((strlen(@$_GET['publickey']) > 1) && ($hits = tdcsc_json_ask_abuse())) {
		// more than 100 hits in the last 10 minutes from the same IP address?
		// that's not normal, so the request gets an empty response
		if ($hits > 100) {
			die("Content not available");
		}
	}
	if (@$_GET['publickey'] == SHARECLUSTER_PUBLICKEY) {
		// carry out the action
		switch($_REQUEST['action']) {
			case 'authenticate':
				$output = tdcsc_request_authentication();
				print json_encode($output);
				exit;
				break;
			case 'get_ads':
				$output = tdcsc_request_remote_ads();
				print json_encode($output);
				exit;
				break;
		}
	}
	else {
		if (strlen(@$_GET['publickey']) > 1) {
			tdcsc_json_say_abuse($hits);
		}
	}
	return true;
}

function tdcsc_ajax_function() {
	switch($_REQUEST['fn']) {
		case 'get_posts':
			$output = tdcsc_get_latest_posts();
		break;
		default:
			$output = 'No function specified, check your call';
		break;
	}

	$output=json_encode($output);

	if (is_array($output)) {
		print_r($output);   
	}
	else{
		echo $output;
	}
	die;
}

function tdcsc_put_click() {
	// pro version
	return true;
}

function tdcsc_get_latest_posts() {
    $args = array( 'numberposts' => 5, 'order' => 'DESC');
    $post = wp_get_recent_posts( $args );
    if( count($post) ) {
        return $post;
    }
    return false;
}


// get the list of ads from a source
function tdcsc_ad_list($index, $output_format = 'html') {
	if (!is_numeric($index)) {
		$sources = tdcsc_source_list();
		foreach ($sources as $key => $value) {
			if ($index == $value['url']) {
				$index = $key;
				break;
			}
		}
	}

	$args = array(
	'post_type'		=> 'sharead',
	'post_status'	=> 'published',
	'meta_query'	=> array(
		array(
		'key' => 'tdcsc_source',
		'value' => $index,
		'compare' => '='
			)
		)
	);

	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		$output = '<ul>';
		while ( $query->have_posts() ) {
			$query->the_post();
			$output .= '<li><a href="'.get_permalink().'" title="'.str_replace('"', '&quot;', get_the_title()).'">'.tdcsc_ellipsis(get_the_title(), 20).'</a></li>';
		}
		$output .= '</ul>';
	}
	else {
		$output = "none";
	}
	return $output;
}

// get the whole list of ads from a source
function tdcsc_ad_lists($output_format = 'html', $title_length = 40) {
	$args = array(
	'post_type'		=> 'sharead',
	'post_status'	=> 'published',
	);

	$query = new WP_Query( $args );
	$output = array();

	if ( $query->have_posts() ) {
		if ($output_format == 'html') {
			$output = '<ul>';
			while ( $query->have_posts() ) {
				$query->the_post();
				$output .= '<li><a href="'.get_permalink().'" title="'.str_replace('"', '&quot;', get_the_title()).'">'.tdcsc_ellipsis(get_the_title(),intval($title_length / 2)).'</a></li>';
			}
			$output .= '</ul>';
		}
		if ($output_format == 'array') {
			while ( $query->have_posts() ) {
				$query->the_post();
				$post = $query->post;
				$output[$post->ID] = tdcsc_ellipsis(get_the_title(), $title_length);
			}
		}
	}
	return $output;
}

/*
The options editor
*/
function tdcsc_options_form_submit() {
	if (isset($_REQUEST['action'])) {
		// let's do this!
		switch ($_REQUEST['action']) {
			case 'edit':
				tdcsc_options_edit();
			break;
			case 'update':
				if ($_GET['index'] > -1) {
					// fetch the remote posts related to this
					tdcsc_get_remote_ads($_GET['index']);
				}
			break;
			case 'delete':
				if ($_GET['index'] > -1) {
					// delete this entry
					tdcsc_options_delete_data($_GET['index']);
				}
			break;			
		}
	}
}

function tdcsc_options_edit() {	
	foreach ($_POST as $key => $value) {
		list($element, $index) = explode("_", $key);
		if ($element == "url") {
			if (strlen($_POST['url_'.$index]) > 6) {
				$input = array( 
					'url' => $_POST['url_'.$index],	
					'publickey' => $_POST['publickey_'.$index],	
					'status' => $_POST['status_'.$index]
				);
				$new_id = tdcsc_options_update_data($input, $index);
				// new record pull it over
				if ($index == -1) {
					tdcsc_get_remote_ads($new_id);
				}
			}
		}
	}
}

function tdcsc_options_update_data($input, $index) {
	global $wpdb;
	$table_name = SHARECLUSTER_SOURCETABLE;

	if ($index == "-1") {
		$rows_affected = $wpdb->insert( $table_name, array('url' => $input['url'], 'publickey' => $input['publickey'], 'created' => current_time('mysql'), 'lastcheck' => current_time('mysql'), 'status' => $input['status'] ));
		return $wpdb->insert_id;
	}
	else {
		$rows_affected = $wpdb->update( $table_name, array('url' => $input['url'], 'publickey' => $input['publickey'], 'created' => current_time('mysql'), 'lastcheck' => current_time('mysql'), 'status' => $input['status'] ), array('indexid' => $index));
		return $index;
	}
}

function tdcsc_options_delete_data($index) {
	global $wpdb;
	$table_name = SHARECLUSTER_SOURCETABLE;
	$rows_affected = $wpdb->delete($table_name, array('indexid' => $index));
	return $index;
}

/*
this gets the initial private key from the remote site
CALLS to REMOTE SOURCE
*/

function tdcsc_get_authenticate_source() {
	$url = $_REQUEST['url'];
	$publickey = $_REQUEST['publickey'];
	$auth_url = $url."?";
	$auth_url .= "publickey=".$publickey."&";
	$auth_url .= "action=authenticate";

	if ($authresp = @file_get_contents($auth_url)) {

	}
}

/* get the list of remote ads and then as for them one-by-one one */
function tdcsc_get_remote_ads($index = 0) {
	$sources = tdcsc_source_list();
	foreach ($sources as $indexid => $value) {
		// checks some not all
		// doesn't check home base
		// skips is age is too old
		if ((($index == $indexid) || ($index == 0)) && ($value['url'] != site_url('')) && (strtotime($value['lastcheck']) < (time() - SHARECLUSTER_AGING))) {
			tdcsc_get_remote_ad($value);
			tdcsc_options_update_source($value['indexid']);
			// do one at a time or all of them?
			// break;
		}
		else {
			$dbug = '$index is '.$index.' - $indexid is '.$indexid.' - $value[url] is '.$value['url'].' $value[lastcheck] is '.$value['lastcheck'];
		}
	}
}

/* get one set of sources from the remote location */
function tdcsc_get_remote_ad($source) {
	// make the file_get_contents request to the remote source

	$auth_url = $source['url'].'?publickey='.$source['publickey'].'&action=get_ads';

	if ($source_response = @file_get_contents($auth_url)) {
		// we have the source!
		$array_response = json_decode($source_response);
		foreach ($array_response as $value) {
			/* do a meta query on the uuid - start */
			$args = array(
			'post_type'		=> 'sharead',
			'post_status'	=> 'published',
			'meta_query'	=> array(
				array(
				'key' => 'tdcsc_uuid',
				'value' => $value->uuid,
				'compare' => '='
					)
				)
			);
			$query = new WP_Query( $args );

			$sources = tdcsc_source_list();
			foreach ($sources as $tdcsc_key => $tdcsc_value) {
				if ($source['url'] == $tdcsc_value['url']) {
					$tdcsc_source = $tdcsc_key;
					break;
				}
			}

			if ( $query->have_posts() ) {
				global $user_ID;

				// we have a listing to update
				while ( $query->have_posts() ) {
					if ($query->get_the_date < $value->lastupdate) {
						// delete the old attachments
						$query->the_post();
						$post = $query->post;
						tdcsc_delete_attach_image($post->ID);

						// add the media
						$media_refs = array();
						foreach ($value->media as $media) {
							if (isset($media->url)) {
								// go and get, then post the media
								$media_refs[$media->url] = tdcsc_upload_attach_image($media->url, $post->ID);
								// returns array('id' => $attach_id, 'data' => $attach_data);
								$value->post = str_replace($media->url, $media_refs[$media->url]['file'], $value->post); 
							}
						}


						$updated_post = array(
							'ID' => $post->ID,
							'post_title' => $value->title,
							'post_content' => $value->post,
							'post_status' => 'publish',
							'post_date' => date('Y-m-d H:i:s', $value->lastupdate),
							'post_author' => $user_ID,
							'post_type' => 'sharead',
							'post_category' => array(0)
						);

						if (wp_update_post($updated_post)) {
							// hooray!
						}
						update_post_meta($post->ID, 'tdcsc_source', $tdcsc_source);
					}
				}
			}
			else {
				// this is a new one to add

				global $user_ID;
				$new_post = array(
					'post_title' => $value->title,
					'post_content' => $value->post,
					'post_status' => 'publish',
					'post_date' => date('Y-m-d H:i:s', $value->lastupdate),
					'post_author' => $user_ID,
					'post_type' => 'sharead',
					'post_category' => array(0)
				);
				$post_id = wp_insert_post($new_post);

				// set the meta
				add_post_meta($post_id, 'tdcsc_uuid', $value->uuid);
				add_post_meta($post_id, 'tdcsc_dimension', $value->dimensions);
				add_post_meta($post_id, 'tdcsc_source', $tdcsc_source);

				// add the media
				$media_refs = array();
				foreach ($value->media as $media) {
					if (isset($media->url)) {
						// go and get, then post the media
						$media_refs[$media->url] = tdcsc_upload_attach_image($media->url, $post_id);
						// returns array('id' => $attach_id, 'data' => $attach_data);
						$value['post'] = str_replace($media->url, $media_refs[$media->url]['file'], $value->post); 
					}
				}

				// update / repair the post content to take out the FQDN of the image calls
				if ($media_refs != array()) {
					$updated_post = array(
						'ID' => $post_id,
						'post_title' => $value->title,
						'post_content' => $value->post,
						'post_status' => 'publish',
						'post_date' => date('Y-m-d H:i:s', $value->lastupdate),
						'post_author' => $user_ID,
						'post_type' => 'sharead',
						'post_category' => array(0)
					);

					if (wp_update_post($updated_post)) {
						// hooray!
					}
				}
			}
		}
		/* do a meta query on the uuid - end */
	}
}

/* take the remote information and update it if the data is new */
function tdcsc_options_update_ad($value) {

}

/* with each call, update the lastcheck */
function tdcsc_options_update_source($indexid) {
	global $wpdb;
	$table_name = SHARECLUSTER_SOURCETABLE;
	$wpdb->update($table_name, array('lastcheck' => date("Y-m-d H:i:s", time())), array('indexid' => $indexid ));	
}


/*
ANSWERS TO CALLS
*/

/*

By default we are going to pass in no arguments

*/
function tdcsc_request_authentication() {
	$output = array("auth" => "-1");
	if ($_GET['publickey'] == SHARECLUSTER_PUBLICKEY) {
		$output = array("auth" => "1");
	}
	return $output;
}

/*

By default we are going to pass in no arguments

*/
function tdcsc_request_remote_ads() {
	// WP_Query arguments
	$args = array (
		'post_type'		=> 'sharead',
		'post_status'	=> 'published',
		'meta_query'	=> array(
							array(
							'key' => 'tdcsc_source',
							'value' => 0,
							'compare' => '='
								)
							)
	);

	// The Query
	$query = new WP_Query( $args );
	$output = array();
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$post = $query->post;

			$row = array();
			$row['title'] = get_the_title();
			$row['post'] = get_the_content();
			$row['uuid'] = get_post_meta($post->ID, 'tdcsc_uuid', TRUE); 
			$row['uuid'] = ($row['uuid'] == -1)	? md5(strtolower(site_url()))."-".$post->ID : $row['uuid']; 
			$row['lastupdate'] = get_the_modified_time('G'); 
			$row['dimensions'] = get_post_meta($post->ID, 'tdcsc_dimension', TRUE); 
			$row['media'] = tdcsc_get_media($post->ID);
			$output[] = $row;
		}
	}
	return $output;
}


/* LICENSING FUNCTIONALITY */

function tdcsc_license_form_submit() {
	// submit the changes
}

function tdcsc_license_options() {
	// listen for our activate button to be clicked
	// run a quick security check 

	$msg = "";
	if ( isset($_POST['tdcsc_license_key']) ) {
		if ( !isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'],'tdcsc_nonce') )
			return; // get out if we didn't click the Activate button

		$options['tdcsc_license_key'] = $_POST['tdcsc_license_key'];
		update_option('tdcsc_license_key', $options['tdcsc_license_key']);

		$options['tdcsc_registered_email'] = $_POST['tdcsc_registered_email'];
		update_option('tdcsc_registered_email', $options['tdcsc_registered_email']);

		$check = false;
		$result_text = 'The license key is not valid';

		if ($check == 'valid') {
			update_option( 'tdcsc_license_status', $check );
			$result_text = 'The license key was validated';		
		}
		$msg = '<div style="color: #22cc22; border: 1px solid #bbbbbb; padding: 10px; margin: 10px;">'.$result_text.'</div>';
	}
	// load the license key
	tdcsc_license_page($msg);
}

function add_tdcsc_options_menu() {
	global $tdcsc_tabs;
	foreach ($tdcsc_tabs as $slug => $value) {
		if ($value['type'] == 'option') {
			add_options_page($value['long_name'], $value['short_name'], $value['access'], $value['slug'], $value['function']);
		}
	}
}

function tdcsc_register_option() {
	// creates our settings in the options table
	register_setting('tdcsc_license_key', 'tdcsc_license_status', 'tdcsc_sanitize_license' );
}
 
function tdcsc_sanitize_license( $new ) {
	$old = get_option( 'tdcsc_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'tdcsc_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}

function tdcsc_check_license($license, $email) {
	return 'invalid'; 
}

/* FUNCTION ETC/SPECIFIC FUNCTIONS */

function tdcsc_ellipsis($input, $maxlen = 40) {
	if (strlen($input) > $maxlen) {
		$characters = floor($maxlen / 2) - 2;
		return substr($input, 0, $characters) . '...' . substr($input, -1 * $characters);
	}
	else {
		return $input;
	}
}

function tdcsc_dimension_list() {
	/* Pro version

	$dimensions = array(
		"468 x 60" => "468 x 60 - Full Banner",	
		"728 x 90" => "728 x 90 - Leaderboard",	
		"336 x 280" => "336 x 280 - Square",		
		"300 x 250" => "300 x 250 - Square",	
		"250 x 250" => "250 x 250 - Square",	
		"160 x 600" => "160 x 600 - Skyscraper",	
		"120 x 600" => "120 x 600 - Skyscraper",	
		"120 x 240" => "120 x 240 - Small Skyscraper",
		"240 x 400" => "240 x 400 - Fat Skyscraper",	
		"234 x 60" => "234 x 60 - Half Banner",			
		"180 x 150" => "180 x 150 - Rectangle",			
		"125 x 125" => "125 x 125 - Square Button",			
		"120 x 90" => "120 x 90 - Button",			
		"120 x 60" => "120 x 60 - Button",			
		"88 x 31" => "88 x 31 - Button",			
		"120 x 30" => "120 x 30 - Button",			
		"230 x 33" => "230 x 33 - Small Banner",	
		"728 x 210" => "728 x 210 - Large Leaderboard",	
		"720 x 300" => "720 x 300 - Large Leaderboard",	
		"500 x 350" => "500 x 350 - Pop-up",
		"550 x 480" => "550 x 480 - Pop-up",
		"300 x 600" => "300 x 600 - Half Page Banner",	
		"94 x 15" => "94 x 15 - Blog Button",
		"0 x 0" => "0 x 0 - Custom",
	);	
	*/

	$dimensions = array(
		"468 x 60" => "468 x 60 - Full Banner",	
		"728 x 90" => "728 x 90 - Leaderboard",	
		"250 x 250" => "250 x 250 - Square",	
		"120 x 240" => "120 x 240 - Small Skyscraper",
		"240 x 400" => "240 x 400 - Fat Skyscraper",	
		"234 x 60" => "234 x 60 - Half Banner",			
		"125 x 125" => "125 x 125 - Square Button",			
		"120 x 90" => "120 x 90 - Button",			
		"88 x 31" => "88 x 31 - Button",			
		"500 x 350" => "500 x 350 - Pop-up",
		"300 x 600" => "300 x 600 - Half Page Banner",	
		"94 x 15" => "94 x 15 - Blog Button",
		"0 x 0" => "0 x 0 - Custom",
	);
	return $dimensions;
}

function tdcsc_source_list() {
	$sources = array();
	// look up the sources and return a list
	// free version - max 5 sources + own site
	// pro version - no max to sources 

	global $wpdb;
	$table_name = SHARECLUSTER_SOURCETABLE;
	$query = "SELECT indexid, url, publickey, created, lastcheck, status FROM $table_name ORDER BY indexid LIMIT 6";

	$ads = $wpdb->get_results($query);
	foreach ($ads as $ad) {
		$sources[$ad->indexid]['indexid'] = $ad->indexid;
		$sources[$ad->indexid]['url'] = $ad->url;
		$sources[$ad->indexid]['publickey'] = $ad->publickey;
		$sources[$ad->indexid]['created'] = $ad->created;
		$sources[$ad->indexid]['lastcheck'] = $ad->lastcheck;
		$sources[$ad->indexid]['status'] = $ad->status;
	}

	return $sources;
}

function tdcsc_report_there($where = array(), $also = 0, $output = "ARRAY") {
	// not used in the free version

	$there = array();
	return $there;
}

function tdcsc_report_orders($output = "ARRAY") {
	// not used in the free version

	$orders = array();
	return $orders;
}

function tdcsc_report_list($where = array(0), $also = 0, $order = 0, $pagination = 0) {
	// not used in the free version
	$reports = array();
	return $reports;
}

function tdcsc_report_pagination($where = array(0), $also = 0) {
	// not used in the free version
	return false;
}


function tdcsc_time_mysql_html($time) {
	if ($timestamp = strtotime($time)) {
		return date("Y-m-d H:i:s", $timestamp);
	}
	else {
		return "n/a";	
	}
}


/* FILE MANAGEMENT : START */

function tdcsc_get_asset($file, $return = 'url') {
	$output = "";
	if ($asset = get_option('tdcsc_'.$file)) {		
		if ($return == 'url') {
			$output = site_url('/wp-content/uploads/').$asset['data']['file'];
		}
	}
	return $output;
}

function tdcsc_upload_attach_image($url, $post_id) {
	if ( ! function_exists( 'wp_handle_upload' ) ) 
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		$upload_dir = wp_upload_dir();

	if ($uploadedfile = @file_get_contents($url)) {
		if (strpos($url,"?")) {
			list($url, $toss) = explode("?", $url);
		}
		$tmp_dir = $upload_dir['path'];
		$tmp = $tmp_dir."/".md5(basename($url));

		$fpc = file_put_contents($tmp, $uploadedfile);

		if ($fpc) {
			$file_info = stat($tmp);
			$file_info['tmp_name'] = $tmp;
			$file_info['name'] = basename($url);
			
			$upload_overrides = array('test_size' => false, 'test_upload' => false, 'test_form' => false);
			$filename = tdcsc_handle_upload($file_info, $upload_overrides);

			if ($filename['file']) {
				// exit;
				$wp_filetype = wp_check_filetype(basename($filename['file']), null);
				$wp_upload_dir = wp_upload_dir();
				$attachment = array(
					'guid' => $wp_upload_dir['url'] . '/' . basename($filename['file']), 
					'post_mime_type' => $wp_filetype['type'],
					'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename['file'])),
					'post_content' => '',
					'post_status' => 'inherit'
				);
				$attach_id = wp_insert_attachment( $attachment, $filename['file'], $post_id );
				// you must first include the image.php file
				// for the function wp_generate_attachment_metadata() to work
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				$attach_data = wp_generate_attachment_metadata( $attach_id, $filename['file']);
				wp_update_attachment_metadata( $attach_id, $attach_data );
				return array('id' => $attach_id, 'data' => $attach_data);
			}
		} else {
			// echo "\n".__LINE__." - could not upload and store $url !\n";
		}
	}
	return false;
}

function tdcsc_get_media($post_id) {
	$output = array();
	$args = array(
		'numberposts' => -1,
		'order' => 'ASC',
		'post_mime_type' => 'image',
		'post_parent' => $post_id,
		'post_status' => null,
		'post_type' => 'attachment',
	);
	$attachments = get_children( $args );
	if ( $attachments ) {
		foreach ( $attachments as $attachment ) {
			$image_attributes = wp_get_attachment_image_src( $attachment->ID, 'full' )  ? wp_get_attachment_image_src( $attachment->ID, 'full' ) : wp_get_attachment_image_src( $attachment->ID, 'thumbnail' );
			$output[] = array('url' => $image_attributes[0], 'width' => $image_attributes[1], 'height' => $image_attributes[2]);
		}
	}
	return $output;
}

function tdcsc_handle_upload(&$file, $overrides = false, $time = null) {
	list($name, $toss) = explode(".", $file['name']);
	$uploaded_args = array(
		'post_type' => 'attachment',
		'posts_per_page' => -1,
		'tdcsc_imgsearch_title' => $name,
		'post_parent' => null, // any parent
		); 

	$the_query = new WP_Query( $uploaded_args );

	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$post_id = $the_query->post->ID;

			// the minimum of what we need
			$img = wp_get_attachment_metadata($post_id);
			$output = array(
				'data' => array (
					'file' => $img['file'],
					'sizes' => array (
						'thumbnail' => array (
							'file' => $img['file'],
							'width' => $img['width'],
							'height' => $img['height'],
						)
					)						
				)	
			);
			wp_reset_postdata();
			return $output;
		}
	}
	/* Restore original Post Data */
	wp_reset_postdata();

	// The default error handler.
	if ( ! function_exists( 'wp_handle_upload_error' ) ) {
		function wp_handle_upload_error( &$file, $message ) {
			return array( 'error'=>$message );
		}
	}

	$file = apply_filters( 'wp_handle_upload_prefilter', $file );
	// You may define your own function and pass the name in $overrides['upload_error_handler']
	$upload_error_handler = 'wp_handle_upload_error';

	// You may have had one or more 'wp_handle_upload_prefilter' functions error out the file. Handle that gracefully.
	if ( isset( $file['error'] ) && !is_numeric( $file['error'] ) && $file['error'] )
		return $upload_error_handler( $file, $file['error'] );

	// You may define your own function and pass the name in $overrides['unique_filename_callback']
	$unique_filename_callback = null;

	// $_POST['action'] must be set and its value must equal $overrides['action'] or this:
	$action = 'wp_handle_upload';

	// Courtesy of php.net, the strings that describe the error indicated in $_FILES[{form field}]['error'].
	$upload_error_strings = array( false,
		__( "The uploaded file exceeds the upload_max_filesize directive in php.ini." ),
		__( "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form." ),
		__( "The uploaded file was only partially uploaded." ),
		__( "No file was uploaded." ),
		'',
		__( "Missing a temporary folder." ),
		__( "Failed to write file to disk." ),
		__( "File upload stopped by extension." ));

	// All tests are on by default. Most can be turned off by $overrides[{test_name}] = false;
	$test_form = true;
	$test_size = true;
	$test_upload = true;

	// If you override this, you must provide $ext and $type!!!!
	$test_type = true;
	$mimes = false;

	// Install user overrides. Did we mention that this voids your warranty?
	if ( is_array( $overrides ) )
		extract( $overrides, EXTR_OVERWRITE );

	// A correct form post will pass this test.
	if ( $test_form && (!isset( $_POST['action'] ) || ($_POST['action'] != $action ) ) )
		return call_user_func($upload_error_handler, $file, __( 'Invalid form submission.' ));

	// A successful upload will pass this test. It makes no sense to override this one.
	if ( $file['error'] > 0 )
		return call_user_func($upload_error_handler, $file, $upload_error_strings[$file['error']] );

	// A non-empty file will pass this test.
	if ( $test_size && !($file['size'] > 0 ) ) {
		if ( is_multisite() )
			$error_msg = __( 'File is empty. Please upload something more substantial.' );
		else
			$error_msg = __( 'File is empty. Please upload something more substantial. This error could also be caused by uploads being disabled in your php.ini or by post_max_size being defined as smaller than upload_max_filesize in php.ini.' );
		return call_user_func($upload_error_handler, $file, $error_msg);
	}

	// A properly uploaded file will pass this test. There should be no reason to override this one.
	if ( $test_upload && ! @ is_uploaded_file( $file['tmp_name'] ) )
		return call_user_func($upload_error_handler, $file, __( 'Specified file failed upload test.' ));

	// A correct MIME type will pass this test. Override $mimes or use the upload_mimes filter.
	if ( $test_type ) {
		$wp_filetype = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], $mimes );

		extract( $wp_filetype );

		// Check to see if wp_check_filetype_and_ext() determined the filename was incorrect
		if ( $proper_filename )
			$file['name'] = $proper_filename;

		if ( ( !$type || !$ext ) && !current_user_can( 'unfiltered_upload' ) )
			return call_user_func($upload_error_handler, $file, __( 'Sorry, this file type is not permitted for security reasons.' ));

		if ( !$ext )
			$ext = ltrim(strrchr($file['name'], '.'), '.');

		if ( !$type )
			$type = $file['type'];
	} else {
		$type = '';
	}

	// A writable uploads dir will pass this test. Again, there's no point overriding this one.
	if ( ! ( ( $uploads = wp_upload_dir($time) ) && false === $uploads['error'] ) )
		return call_user_func($upload_error_handler, $file, $uploads['error'] );

	$filename = wp_unique_filename( $uploads['path'], $file['name'], $unique_filename_callback );

	// Move the file to the uploads dir
	$new_file = $uploads['path'] . "/$filename";
	if ( false === @rename( $file['tmp_name'], $new_file ) ) {
		if ( 0 === strpos( $uploads['basedir'], ABSPATH ) )
			$error_path = str_replace( ABSPATH, '', $uploads['basedir'] ) . $uploads['subdir'];
		else
			$error_path = basename( $uploads['basedir'] ) . $uploads['subdir'];

		return $upload_error_handler( $file, sprintf( __('The uploaded file could not be moved to %s.' ), $error_path ) );
	}

	// Set correct file permissions
	$stat = stat( dirname( $new_file ));
	$perms = $stat['mode'] & 0000666;
	@ chmod( $new_file, $perms );

	// Compute the URL
	$url = $uploads['url'] . "/$filename";

	if ( is_multisite() )
		delete_transient( 'dirsize_cache' );

	return apply_filters( 'wp_handle_upload', array( 'file' => $new_file, 'url' => $url, 'type' => $type ), 'upload' );
}

function tdcsc_delete_attach_image($post_id) {
	$args = array(
		'numberposts' => -1,
		'post_mime_type' => 'image',
		'post_parent' => $post_id,
		'post_status' => null,
		'post_type' => 'attachment',
	);
	$attachments = get_children( $args );
	if ( $attachments ) {
		foreach ( $attachments as $attachment ) {
			wp_delete_attachment( $attachment->ID, TRUE );
		}
	}
}


/* FILE MANAGEMENT : END */