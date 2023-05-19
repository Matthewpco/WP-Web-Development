<?php

function github_shortcode_content_enqueue_scripts(){

/**
 * Registers a stylesheet.
 */
	wp_register_style( 'wp-plugin-github', plugins_url ( '/inc/css/styles.css', __FILE__ ), false, filemtime( plugin_dir_path( __FILE__ ) . '/inc/css/styles.css' ));
	wp_enqueue_style( 'wp-plugin-github' );
	
	wp_register_script( 'wp-plugin-github-script' , plugins_url ('/inc/js/script.js', __FILE__ ));
	wp_enqueue_script( 'wp-plugin-github-script' );
}

// Register style sheet.
add_action( 'wp_enqueue_scripts', 'github_shortcode_content_enqueue_scripts' );


function defer_scripts( $tag, $handle, $src ) {
  $defer = array( 
    'wp-plugin-github-script'
  );
  if ( in_array( $handle, $defer ) ) {
     return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";
  }
    
    return $tag;
} 
add_filter( 'script_loader_tag', 'defer_scripts', 10, 3 );