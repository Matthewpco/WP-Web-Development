<?php
/**
 * Plugin Name: Github API Content
 * Plugin URI: https://www.wpwebdevelopment.com
 * Description: Display content using a shortcode to insert API data in a page or post
 * Version: 1.3 
 * Text Domain: github-shortcode-content
 * Author: Gary Matthew Payne
 * Author URI: https://www.wpwebdevelopment.com
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Includes
include( 'inc/api-func.php' );
include( 'enqueue-scripts.php');
	
// Hooks
add_action( 'wp_enqueue_scripts', 'github_shortcode_content_enqueue_scripts');

// Register shortcode
add_shortcode('wp_plugin_github', 'wp_plugin_github_function'); 