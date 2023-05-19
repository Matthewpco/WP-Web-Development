<?php
/*
Plugin Name: Custom Image Size
Description: Adds a custom image size for portfolio images and a shortcode for displaying images with the custom size.
Version: 1.0
Author: Gary Matthew Payne
*/

// Register the custom image size
function my_add_custom_image_size() {
    add_image_size( 'portfolio-image', 1920, 4800, true );
}
add_action( 'after_setup_theme', 'my_add_custom_image_size' );

// Define the portfolio_image shortcode
function my_portfolio_image_shortcode( $atts ) {
    // Extract shortcode attributes
    extract( shortcode_atts( array(
        'url' => '',
    ), $atts ) );

    // Return the image HTML
    return '<img class="portfolio-image" src="' . esc_url( $url ) . '" alt="">';
}
add_shortcode( 'portfolio_image', 'my_portfolio_image_shortcode' );