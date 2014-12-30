<?php

/**
 * Modify which columns display in the admin views
 * --> manage_{post type}_posts_columns
 */
add_filter('manage_cpt_post_type_posts_columns', 'cpt_post_type_posts_columns');
function cpt_post_type_posts_columns($posts_columns) 
{
	$tmp = array();

	$tmp['cb'] = '<input type="checkbox" />';
	$tmp['cpt_thumbnail'] = '';
	$tmp['title'] = "Title";
	$tmp['cpt_input_text'] = 'Input Text';
	$tmp['cpt_taxonomy'] = 'Sample Taxonomy';
	$tmp['date'] = 'Date';

	return $tmp;
}


/**
 * Custom column output when admin is viewing the post type
 * --> manage_{post type}_posts_custom_column
 */
add_action('manage_cpt_post_type_posts_custom_column', 'cpt_post_type_posts_custom_column');
function cpt_post_type_posts_custom_column($column_name) 
{
	global $post;

	if ($column_name == 'cpt_thumbnail') 
	{
		$thumb_img = get_the_post_thumbnail($post -> ID, array(60,60));		

		$thumb_img = str_replace('<img', '<img style="width:60px"', $thumb_img);

		if(! empty($thumb_img)) {
			echo "<a href='" . get_edit_post_link($post -> ID) . "'>" . $thumb_img . "</a>";
		}
		else {
			echo "<img src='" . plugin_dir_url( __FILE__ ) . "assets/no-image.png' style='width:60px' />";
		}
	}
	else if ($column_name == 'cpt_input_text') 
	{
		$cpt_input_text =  get_post_meta($post -> ID, 'cpt_input_text',true);
	
		if($cpt_input_text != '') {
			echo $cpt_input_text;
		}
		else {
			echo "<i>empty</i>";
		}
	}
	else if ($column_name == 'cpt_taxonomy') 
	{
		echo get_the_term_list($post -> ID, 'cpt_taxonomy', '', ', ', '');
	}
}


/**
 * Sorting custom column 
 * --> manage_edit-{post type}_sortable_columns
 */
add_filter( 'manage_edit-cpt_post_type_sortable_columns', 'cpt_post_type_sortable_columns' );
function cpt_post_type_sortable_columns( $columns ) 
{
    $columns['cpt_input_text'] = 'cpt_input_text';
 
    //To make a column 'un-sortable' remove it from the array
    //unset($columns['date']);
 
    return $columns;
}


/**
 * Enable custom post meta filter sorting
 */
add_filter( 'request', 'filter_column_orderby' );
function filter_column_orderby( $vars ) 
{
	if ( isset( $vars['orderby'] ) && 'cpt_input_text' == $vars['orderby'] ) 
	{
		$vars = array_merge( $vars, array(
			'meta_key' => 'cpt_input_text',
			'orderby' => 'meta_value'
		) );
	}

	return $vars;
}