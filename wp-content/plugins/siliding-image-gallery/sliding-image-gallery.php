<?php
/*
Plugin Name: Sliding Image Gallery
Plugin URI: https://github.com/Matthewpco/
Description: Shortcode to add a gallery of sliding images anywhere
Version: 1.1.0 *Added cs
Author: Gary Matthew Payne
Author URI: https://www.wpwebdevelopment.com
*/


// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


function sliding_image_gallery_enqueue_scripts() {
    wp_enqueue_script('sliding-image-gallery', plugin_dir_url(__FILE__) . '/inc/js/sig-script.js', array('jquery'), '1.0', true);
    wp_enqueue_style( 'sliding-image-gallery', plugin_dir_url( __FILE__ ) . '/inc/css/sig-style.css', false, filemtime( plugin_dir_path( __FILE__ ) . '/inc/css/sig-style.css' ) );

}
add_action( 'wp_enqueue_scripts', 'sliding_image_gallery_enqueue_scripts' );


function sliding_image_gallery_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'images' => '',
    ), $atts );

    $images = explode( ',', $atts['images'] );

    ob_start();
    ?>
    <div class="slider">
        <?php foreach ( $images as $image ): ?>
            <img class="slider-img" src="<?php echo esc_url( $image ); ?>" alt="">
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'sliding_image_gallery', 'sliding_image_gallery_shortcode' );