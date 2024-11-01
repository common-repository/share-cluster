<?php
/**
 * @package tdcsc Share Cluster
 */

class tdcsc_widget_random extends WP_Widget {
	// constructor

	function __construct() {
		parent::__construct(
			'tdcsc_widget_random',
			__( 'Share Cluter Random Widget' ),
			array( 'description' => __( 'Display random single entries from Share Cluster.' ) )
		);
	}

	function tdcsc_widget_random() {
		/* ... */
	}

	// widget form creation
	function form( $instance ) {
		// start with the default
		// get the list of shareads

		$ads[-1] = "Use All Shared Content";
		$individual_ads = tdcsc_ad_lists('array');

		foreach ($individual_ads as $id => $title) {
			$tdcsc_dimension = get_post_meta($id, 'tdcsc_dimension', TRUE); 
			$individual_ads[$id] = $title." (".$tdcsc_dimension.")";
			$dimensions[$tdcsc_dimension] = TRUE;
		}

		$sizes = tdcsc_dimension_list();
		foreach ($sizes as $k => $v) {
			$key = str_replace(" ", "_", $k);
			if (isset($dimensions[$k])) {
				$ads[$key] = "Use All ".$v." Content";				
			}
		}

		$ads = $ads + $individual_ads;

		foreach ($ads as $id => $title) {
			$checked = "";
			if (isset($instance[$id])) {
				$checked = ' checked="checked"';
			}
	?>
		<p>
		<input class="widefat" id="<?php echo $this->get_field_id( $id ); ?>" name="<?php echo $this->get_field_name( $id ); ?>"<?php echo $checked; ?> type="checkbox" value="<?php echo $id; ?>" />
		<label for="<?php echo $id; ?>"><?php echo $title; ?></label> 
		</p>
	<?php 
		}
	}

	// widget update
	function update($new_instance, $old_instance) {
		$instance = $new_instance;
		return $instance;
	}

	// widget display
	function widget($args, $instance) {
		if (in_array(-1, $instance)) {
			// random for one

			$args = array(
				'post_type'		=> 'sharead',
				'post_status'	=> 'published',
				'orderby' => 'rand',
			);

			$query = new WP_Query( $args );
			$output = array();

			if ( $query->have_posts() ) {
				// tdcsc_click js not in freebie version

				$query->the_post();
				print '<div class="tdcsc">';
				$style_meta = get_post_meta($query->post->ID, 'tdcsc_dimension', TRUE);
				$style = "dimension_".str_replace(" ", "_", $style_meta); 
				print '<div class="tdcsc '.$style.'" id="sc_'.$new_instance[0].'">';
				the_content();
				print '</div>';
			}
		}
		else {
			// from a size or not
			foreach ($instance as $inst) {
				if (is_int($inst)) {
					// int
					$new_instance[] = $inst;
				}	
				else {
					// string
					$inst = str_replace("_", " ", $inst);
					$argss = array(
						'post_type'		=> 'sharead',
						'post_status'	=> 'published',
						'orderby' => 'rand',
						'meta_key' => 'tdcsc_dimension',
						'meta_query' => array(
						   array(
							   'key' => 'tdcsc_dimension',
							   'value' => array($inst),
							   'compare' => 'IN',
						   )
						)
					);
					$query2 = new WP_Query($argss);
					while ( $query2->have_posts() ) {
						$query2->the_post();
						$new_instance[] = $query2->post->ID;
					}
				}
			}

			// random from a small set
			shuffle($new_instance);

			$args = array(
				'post_type'		=> 'sharead',
				'post_status'	=> 'published',
				'p' => $new_instance[0],
			);

			$query = new WP_Query( $args );
			$output = array();

			if ( $query->have_posts() ) {
				$query->the_post();
				$style_meta = get_post_meta($new_instance[0], 'tdcsc_dimension', TRUE);
				$style = "dimension_".str_replace(" ", "_", $style_meta); 
				print '<div class="tdcsc '.$style.'" id="sc_'.$new_instance[0].'">';
				the_content();
				print '</div>';
			}
		}
	}
}

class tdcsc_widget_one extends WP_Widget {
	// constructor
	function __construct() {
		parent::__construct(
			'tdcsc_widget_one',
			__( 'Share Cluter Display Widget' ),
			array( 'description' => __( 'Displays a single entry from Share Cluster.' ) )
		);
	}

	function tdcsc_widget_one() {
		/* ... */
	}

	// widget form creation
	function form( $instance ) {
		// start with the default
		$ads = tdcsc_ad_lists('array');
		$defaults = array('post_id' => 0);
		$instance = wp_parse_args((array) $instance, $defaults );
	  ?>
	  <p>
		<select id="<?php echo $this->get_field_id('post_id'); ?>" name="<?php echo $this->get_field_name('post_id'); ?>" type="text">
		<?php 
		foreach ($ads as $id => $title) {
		?>
			<option value="<?php echo $id; ?>" <?php selected($instance['post_id'], $id); ?>><?php echo $title; ?></option>
		<?php 
			}
		?>
		</select>
	  </p>
	  <br/>
	<?php 
	}

	// widget update
	function update($new_instance, $old_instance) {
		$instance = $new_instance;
		return $instance;
	}

	// widget display
	function widget($args, $instance) {
		$args = array(
			'post_type'		=> 'sharead',
			'post_status'	=> 'published',
			'p' => $instance['post_id'],
		);

		$query = new WP_Query( $args );
		$output = array();

		if ( $query->have_posts() ) {
			// tdcsc_click js not in freebie version

			$query->the_post();
			print '<div class="tdcsc" id="'.$instance['post_id'].'">';
			the_content();
			print '</div>';

		}
	}
}








// register widget
add_action('widgets_init', create_function('', 'return register_widget("tdcsc_widget_random");'));
add_action('widgets_init', create_function('', 'return register_widget("tdcsc_widget_one");'));