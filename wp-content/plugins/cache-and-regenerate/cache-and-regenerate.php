<?php
/*
Plugin Name: Cache & Regenerate
Plugin URI: https://github.com/Matthewpco/WP-Plugin-Cache-and-Regenerate
Description: Clear cache and regenerate thumbnails
Version: 1.2.0
Author: Gary Matthew Payne
Author URI: https://www.wpwebdevelopment.com
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


// Add submenu item to Tools menu
add_action('admin_menu', 'my_plugin_admin_menu');
function my_plugin_admin_menu() {
    add_submenu_page(
        'tools.php',
        'Cache & Regenerate',
        'Cache & Regenerate',
        'manage_options',
        'cache-and-regenerate',
        'cache_and_regenerate_admin_page'
    );
}


// Display admin page content
function cache_and_regenerate_admin_page() {
    if (isset($_POST['clear_cache'])) {
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
            echo '<div class="updated"><p>Cache cleared successfully.</p></div>';
        } else {
            echo '<div class="error"><p>Failed to clear cache.</p></div>';
        }
    }
	
	if (isset($_POST['regenerate'])) {
		if (function_exists('candr_regenerate_thumbnails')) {
			candr_regenerate_thumbnails();
			echo '<div class="updated"><p>Regenerated thumbnails successfully.</p></div>';
		} else {
			echo '<div class="error"><p>Failed to regenerate thumbnails</p></div>';
		}
	}

    echo '<div class="wrap" >';
    echo '<h1>Clear cache and regenerate thumbnails</h1>';
    echo '<form style="display: flex" method="post">';
    echo '<p><input type="submit" name="clear_cache" class="button button-primary" value="Clear Cache"></p>';
	echo '<p style="margin-left: 2%"><input type="submit" name="regenerate" class="button button-primary" value="Regenerate Thumbnails"></p>';
    echo '</form>';
    echo '</div>';
}


function candr_regenerate_thumbnails() {
    // Get all image attachments
    $images = get_posts( array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => -1,
    ) );

    // Loop through the images and regenerate thumbnails
    foreach ( $images as $image ) {
        $fullsizepath = get_attached_file( $image->ID );
        if ( false === $fullsizepath || ! file_exists( $fullsizepath ) ) {
            continue;
        }
        wp_generate_attachment_metadata( $image->ID, $fullsizepath );
    }
}