<?php

/**
 * Modify which columns display in the admin views 
 */
add_filter('manage_cpt_post_type_posts_columns', 'cpt_post_type_posts_columns');
function cpt_post_type_posts_columns($posts_columns) 
{
	$tmp = array();

	$tmp['cb'] = '<input type="checkbox" />';
	$tmp['cpt_thumbnail'] = '';
	$tmp['title'] = "Title";
	$tmp['cpt_meta_text'] = 'Custom Meta Text';
	$tmp['cpt_taxonomy'] = 'Sample Taxonomy';
	$tmp['date'] = 'Date';

	return $tmp;
}


/**
 * Custom column output when admin is viewing the post type.
 */
add_action('manage_posts_custom_column', 'cpt_custom_column');
function cpt_custom_column($column_name) 
{
	global $post;

	if ($column_name == 'cpt_thumbnail') 
	{
		$thumb_img = get_the_post_thumbnail($post -> ID, array(60,60));		

		$thumb_img = str_replace('<img', '<img style="width:60px"', $thumb_img);

		if(! empty($thumb_img))
		{
			echo "<a href='" . get_edit_post_link($post -> ID) . "'>" . $thumb_img . "</a>";
		}
		else
		{
			echo "<img src='" . plugin_dir_url( __FILE__ ) . "assets/no-image.png' style='width:60px' />";
		}
	}

	if ($column_name == 'cpt_meta_text') 
	{
		$postmeta =  get_post_meta($post -> ID, 'cpt_meta_box',true);
	
		if(isset($postmeta['input_text']) && $postmeta['input_text'] != '')
		{
			echo $postmeta['input_text'];
		}
		else
		{
			echo "<i>empty</i>";
		}
	}

	if ($column_name == 'cpt_taxonomy') 
	{
		echo get_the_term_list($post -> ID, 'cpt_taxonomy', '', ', ', '');
	}
}