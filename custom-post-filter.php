<?php

/**
 * Taxonomy filter on post list
 */
if(is_admin())
{
	add_filter('parse_request','convert_cpt_taxonomy_id_to_taxonomy_term_in_query');
	function convert_cpt_taxonomy_id_to_taxonomy_term_in_query($query) {
	   	global $pagenow;
	    $qv = &$query->query_vars;

	    if ($pagenow=='edit.php' && isset($qv['cpt_taxonomy']) && $qv['cpt_taxonomy'] != 0 && isset($qv['post_type']) && $qv['post_type']=='cpt_post_type') 
	    {
	    	$term = get_term_by('slug',$qv['cpt_taxonomy'],'cpt_taxonomy');

	    	if($term == null)
	    	{
	    		$term = get_term_by('id',$qv['cpt_taxonomy'],'cpt_taxonomy');
	        	
	    	}

	    	$qv['term'] = $term->term_id;
	        $qv['cpt_taxonomy'] = $term->slug;
	    }
	    else
	    {
	    	$qv['term'] = 0;
	    }
	}
}

add_action('restrict_manage_posts','restrict_cpt_post_type_by_cpt_taxonomy');
function restrict_cpt_post_type_by_cpt_taxonomy() {
    global $typenow;
    global $wp_query;
    if ($typenow=='cpt_post_type') {
        $taxonomy = 'cpt_taxonomy';
        $cpt_taxonomy_taxonomy = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' =>  __("All {$cpt_taxonomy_taxonomy->label}"),
            'taxonomy'        =>  $taxonomy,
            'name'            =>  'cpt_taxonomy',
            'orderby'         =>  'name',
            'selected'        =>  $wp_query->query['term'],
            'hierarchical'    =>  true,
            'depth'           =>  3,
            'show_count'      =>  true, // Show # listings in parens
            'hide_empty'      =>  true, // Don't show businesses w/o listings
        ));
    }
}