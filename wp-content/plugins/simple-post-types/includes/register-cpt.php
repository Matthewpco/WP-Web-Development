<?php

function register_new_cpt($cpt_name) {
    $labels = array(
        'name' => $cpt_name,
        'singular_name' => $cpt_name,
        'add_new' => 'Add New',
        'add_new_item' => 'Add New ' . $cpt_name,
        'edit_item' => 'Edit ' . $cpt_name,
        'new_item' => 'New ' . $cpt_name,
        'view_item' => 'View ' . $cpt_name,
        'search_items' => 'Search ' . $cpt_name,
        'not_found' =>  'No ' . strtolower($cpt_name) . ' found',
        'not_found_in_trash' => 'No ' . strtolower($cpt_name) . ' found in Trash', 
        'parent_item_colon' => '',
        'menu_name' => $cpt_name
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true, 
        'show_in_menu' => true, 
        'query_var' => true,
        'rewrite' => array( 'slug' => $cpt_name ),
        'capability_type' => 'post',
        'has_archive' => true, 
		'exclude_from_search' => false, // Excludes posts of this type in the front-end search result page if set to true, include them if set to false
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
    ); 
    register_post_type(strtolower($cpt_name), $args);
}

function my_register_cpts_on_init() {
    // Retrieve the custom post type data from the WordPress database
    $custom_post_types = get_option('my_custom_post_types', array());
    foreach ($custom_post_types as $cpt_name) {
        register_new_cpt($cpt_name);
    }
}