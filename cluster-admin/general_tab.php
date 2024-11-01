<?php

// your publickey key - MD5 of NONCE_SALT + URL


// table of remote sources
/*

- url source
- list of ads w/ last pull date
- publickey key
- status of authentication

one extra row

*/

$sources = tdcsc_source_list();

?>
<div class="wrap">
	<h2><?php _e('Share Cluster Options'); ?></h2>
	<h3><?php _e('Sources'); ?></h3>
<?php

print '<form action="'.$_SERVER['REQUEST_URI'].'" method="POST">';
print "<table>";

print tdcsc_build_general_header();

$index = 0;
foreach ($sources as $index => $data) {
	print tdcsc_build_general_row($index, $data);
}

// non-pro
if ($index < 5) {
	// add a new row
	print tdcsc_build_general_row(-1);
	print "</table>";
}
else {
	global $tdcsc_options;
	print "</table>";
	print 'This version has a limit of five ad sources. <a href="'.$tdcsc_options['home_page'].'">Update to '.$tdcsc_options['plugin_name'].' Pro</a> to use an unlimited number of sources';
}

print '<input type="hidden" name="action" value="edit" />';
print '<input type="submit" value="Update Sources" />';
print "</form>";

?>
<br/>
	<h3><?php _e('Your Public Key'); ?></h3>
	<div style="float: left; display: block; font-size: 14px; width: 100px;">Your URL</div>
	<div style="float: left; background-color: #dddddd; border: 1px solid #aaaaaa; padding: 5px; display: block; font-size: 14px; width: 360px;">
	<?php print site_url(); ?>
	</div>
	<br/><br/>
	<div style="float: left; display: block; font-size: 14px; width: 100px;">Public Key</div>
	<div style="float: left; background-color: #dddddd; border: 1px solid #aaaaaa; padding: 5px; display: block; font-size: 14px; width: 360px;">
	<?php print SHARECLUSTER_PUBLICKEY; ?>
	</div>
</div>
<br/>
<?php

// FUNCTIONS FOR GENERAL TAB

function tdcsc_build_general_header() {
	$header = "<tr>";
	$header .= "<th>Source</th>";
	$header .= "<th>Public Key</th>";
	$header .= "<th>Content</th>";
	$header .= '<th style="width: 100px;">Status</th>';
	$header .= "<th>Actions</th>";
	$header .= "</tr>";
	return $header;
}

/*

index is the data index
data is a single row of output

*/
function tdcsc_build_general_row($index, $input = array()) {
	$zebra = "zebra-".($index % 2);
	$row = '<tr class="'.$zebra.'">';
	$row .= '<td><input name="url_'.$index.'" size="60" type="text" value="'.esc_attr(@$input['url']).'"/></td>';
	$row .= '<td><input name="publickey_'.$index.'" size="34" maxlength="32" type="text" value="'.esc_attr(@$input['publickey']).'"/></td>';
	$row .= '<td>'.tdcsc_ad_list(@$input['url'], "html").'</td>';
	$row .= '<td nowrap><nobr><input name="status_'.$index.'" type="radio" value="1"'.((@$input['status'] == "1") ? " checked" : "").'/> Active</nobr><br/><nobr><input name="status_'.$index.'" type="radio" value="1"'.((@$input['status'] == "0") ? " checked" : "").'/> Hold</nobr></td>';

	$action = "";
	if ($index > -1) {
		if ($input['url'] != site_url('')) {
			$action = '<nobr><a href="'.$_SERVER['REQUEST_URI'].'&action=update&index='.$index.'">Update</a></nobr>';
			$action .= '&nbsp;';
			$action .= '<nobr><a href="'.$_SERVER['REQUEST_URI'].'&action=delete&index='.$index.'">Delete</a></nobr>';
		}
		else {
			$action = 'Homebase source';
		}
	}

	$row .= "<td>$action</td>";
	$row .= "</tr>";
	return $row;
}


?>