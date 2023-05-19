<?php
/*
Plugin Name: Simple Post Types
Plugin URI: https://github.com/Matthewpco/WP-Plugin-Simple-Post-Types
Description: Adds a new tab in the WordPress dashboard under Tools called Manage CPT where you can add or remove custom post types.
Version: 1.5.0
Author: Gary Matthew Payne
Author URI: https://wpwebdevelopment.com/
*/
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


// Define plugin constants
define('MY_PLUGIN_VERSION', '1.0.0');
define('MY_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MY_PLUGIN_URL', plugin_dir_url(__FILE__));


// Include plugin files
require_once MY_PLUGIN_DIR . 'html/form-display.php';
require_once MY_PLUGIN_DIR . 'includes/register-cpt.php';


// Register new menu under tools called Manage CPT
function manage_cpt_menu() {
    add_management_page('Manage CPT', 'Manage CPT', 'manage_options', 'manage-cpt', 'display_manage_cpt_page');
}


// Manage cpt form data 
function manage_cpt_page() {
    if (isset($_POST['new_cpt'])) {
        $new_cpt = sanitize_text_field($_POST['new_cpt']);
        register_new_cpt($new_cpt);
        // Store or remove the custom post type data in/from the WordPress database
        $custom_post_types = get_option('my_custom_post_types', array());
        if (in_array($new_cpt, $custom_post_types)) {
            $custom_post_types = array_diff($custom_post_types, array($new_cpt));
            update_option('my_custom_post_types', $custom_post_types);
            // Redirect before any output is sent
            wp_redirect(admin_url('/tools.php?page=manage-cpt&message=removed'));
            exit;
        } else {
            $custom_post_types[] = $new_cpt;
            update_option('my_custom_post_types', $custom_post_types);
            // Redirect before any output is sent
            wp_redirect(admin_url('/tools.php?page=manage-cpt&message=registered'));
            exit;
        }
    }
}


// Display custom post type registration code
function display_cpt_code() {
    if (isset($_POST['new_cpt'])) {
        $new_cpt = sanitize_text_field($_POST['new_cpt']);
        $code = "function my_custom_post_type() {
            register_post_type( '$new_cpt',
                array(
                    'labels' => array(
                        'name' => __( '$new_cpt' ),
                        'singular_name' => __( '$new_cpt' )
                    ),
                    'public' => true,
                    'has_archive' => true,
                )
            );
        }
        add_action( 'init', 'my_custom_post_type' );";
        echo "<pre><code>$code</code></pre>";
    }
	wp_die(); // Terminate script execution
}

// Hooks
add_action('admin_menu', 'manage_cpt_menu');
add_action('admin_post_register_cpt', 'manage_cpt_page');
add_action('wp_ajax_display_cpt_code', 'display_cpt_code');
add_action('init', 'my_register_cpts_on_init');