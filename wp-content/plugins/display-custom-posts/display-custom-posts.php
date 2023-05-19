<?php
/*
Plugin Name: Display Custom Posts
Plugin URI: https://github.com/Matthewpco/
Description: Creates shortcode to display custom posts featured images in a container
Version: 1.1.0 * Added css 
Author: Gary Matthew Payne
Author URI: https://www.wpwebdevelopment.com
*/


// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


function dcp_enqueue_stylesheet(){
    wp_enqueue_style('dcp-style', plugins_url('/css/dcp-style.css', __FILE__), false, filemtime( plugin_dir_path( __FILE__ ) . '/css/dcp-style.css' ));
}
add_action('wp_enqueue_scripts', 'dcp_enqueue_stylesheet');


// display custom posts
function portfolio_shortcode() {
    if ( ! post_type_exists( 'portfolio' ) ) {
        return 'Error: The "portfolio" post type does not exist.';
    }

    $args = array(
        'post_type' => 'portfolio',
		'posts_per_page' => 6,
		'orderby' => 'meta_value',
    	'meta_key' => 'display_priority'
        
    );
    $loop = new WP_Query( $args );

    if ( ! $loop->have_posts() ) {
        return 'No portfolio items found.';
    }

    ob_start();
	echo '<div class="portfolio-wrapper">';
    while ( $loop->have_posts() ) : $loop->the_post();
		$post_url = get_permalink();
        echo '<a class="portfolio-link" href="' . $post_url . '"><div class="portfolio-item">';
        if ( has_post_thumbnail() ) {
            the_post_thumbnail();
        } else {
            echo 'No featured image set for this portfolio item.';
        }
        echo '</div></a>';
    endwhile;
	echo '</div>';
    return ob_get_clean();
}
add_shortcode( 'portfolio', 'portfolio_shortcode' );

// Adds a value to set sort priority for posts
function add_display_priority_meta_box() {
    add_meta_box(
        'display_priority_meta_box', // id
        'Display Priority', // title
        'show_display_priority_meta_box', // callback
        'portfolio', // screen (post type)
        'side', // context
        'high' // priority
    );
}
add_action('add_meta_boxes', 'add_display_priority_meta_box');

function show_display_priority_meta_box($post) {
    $display_priority = get_post_meta($post->ID, 'display_priority', true);
    wp_nonce_field('save_display_priority', 'display_priority_nonce');
    ?>
    <p>
        <label for="display-priority">Display Priority:</label>
        <input type="number" id="display-priority" name="display_priority" value="<?php echo esc_attr($display_priority); ?>">
    </p>
    <?php
}

function save_display_priority($post_id) {
    if (!isset($_POST['display_priority_nonce']) || !wp_verify_nonce($_POST['display_priority_nonce'], 'save_display_priority')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['display_priority'])) {
        update_post_meta($post_id, 'display_priority', sanitize_text_field($_POST['display_priority']));
    }
}
add_action('save_post', 'save_display_priority');