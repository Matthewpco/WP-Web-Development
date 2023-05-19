<?php
require_once( 'wp-load.php' );
$query = new WP_Query( array(
    'post_type' => 'attachment',
    'post_mime_type' => array( 'image/jpeg', 'image/png' ),
    'posts_per_page' => -1,
) );
if ( $query->have_posts() ) {
    echo '<p>Found ' . $query->found_posts . ' attachment posts.</p>';
    while ( $query->have_posts() ) {
        $query->the_post();
        echo '<p>Attachment ID: ' . get_the_ID() . '</p>';
    }
} else {
    echo '<p>No attachment posts found.</p>';
}
wp_reset_postdata();