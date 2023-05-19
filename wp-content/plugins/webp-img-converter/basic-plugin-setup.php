<?php
/*
Plugin Name: Webp Image Converter
Plugin URI: https://github.com/Matthewpco/WP-Plugin-Webp-Image-Converter
Description: Wordpress plugin that adds a new submenu under tools with a form to either enter a url of an image to convert or upload an image and convert to webp.
Version: 1.4.0
Author: Gary Matthew Payne
Author URI: https://www.wpwebdevelopment.com
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Turn off size restrictions
add_filter( 'big_image_size_threshold', '__return_false' );


function webp_converter_admin_menu() {
    add_submenu_page( 'tools.php', 'WebP Converter', 'WebP Converter', 'manage_options', 'webp-converter', 'webp_converter_form' );
}
add_action( 'admin_menu', 'webp_converter_admin_menu' );


function webp_converter_form() {
    $batch_size = 10;
    $current_page = isset( $_POST['current_page'] ) ? intval( $_POST['current_page'] ) : 1;
    ?>
    <div class="wrap">
        <h1>WebP Converter</h1>
        <form method="post" enctype="multipart/form-data">
            <label for="image_url">Image URL:</label>
            <input type="url" name="image_url" id="image_url">
            <p>OR</p>
            <label for="image_file">Upload Image:</label>
            <input type="file" name="image_file" id="image_file" accept=".jpg,.jpeg,.png">
            <input type="submit" value="Convert to WebP">
        </form>
		<form method="post">
            <input type="hidden" name="convert_all_images" value="1">
            <input type="hidden" name="current_page" value="<?php echo esc_attr( $current_page + 1 ); ?>">
            <input type="submit" value="<?php echo $current_page > 1 ? 'Continue Converting Images' : 'Convert All Images'; ?>">
        </form>
        <?php
        if ( isset( $_POST['convert_all_images'] ) ) {
            global $wpdb;
            $offset = ( $current_page - 1 ) * $batch_size;
            $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_type = 'attachment' AND post_mime_type IN ('image/jpeg', 'image/png') ", $batch_size, $offset ) );
            if ( ! empty( $results ) ) {
                echo '<p>Converting batch ' . $current_page . '...</p>';
                foreach ( $results as $post ) {
                    $attachment_id = $post->ID;
                    $file_path = get_attached_file( $attachment_id );
                    $extension = pathinfo( $file_path, PATHINFO_EXTENSION );
                    if ( $extension === 'jpg' || $extension === 'jpeg' ) {
                        $image = imagecreatefromjpeg( $file_path );
                    } elseif ( $extension === 'png' ) {
                        $image = imagecreatefrompng( $file_path );
                    }
                    if ( isset( $image ) ) {
                        echo '<p>Created image resource for attachment ID ' . $attachment_id . '</p>';
                        $webp_image_path = str_replace( ".$extension", '.webp', $file_path );
                        imagewebp( $image, $webp_image_path );
                        imagedestroy( $image );
                        update_attached_file( $attachment_id, $webp_image_path );
                        wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $webp_image_path ) );
                        wp_update_post( array(
                            'ID' => $attachment_id,
                            'post_mime_type' => 'image/webp',
                        ) );
                        echo '<p>Image ID ' . $attachment_id . ' converted and replaced successfully!</p>';
                    } 
					else {
                        echo '<p>Failed to create image resource for attachment ID ' . $attachment_id . '</p>';
                    }
                }
            } 
			else {
                echo '<p>No attachment posts found.</p>';
            }
        }
			elseif ( isset( $_POST['image_url'] ) && ! empty( $_POST['image_url'] ) ) {
				$url = esc_url_raw( $_POST['image_url'] );
				$extension = pathinfo( $url, PATHINFO_EXTENSION );
				$response = wp_remote_get( $url );
				if ( is_wp_error( $response ) ) {
					echo '<p>Error: ' . $response->get_error_message() . '</p>';
				} else {
					$image_data = wp_remote_retrieve_body( $response );
					$image = imagecreatefromstring( $image_data );
					$upload_dir = wp_upload_dir();
					$webp_image_path = $upload_dir['path'] . '/' . str_replace( ".$extension", '.webp', basename( $url ) );
					imagewebp( $image, $webp_image_path );
					imagedestroy( $image );
					$attachment = array(
						'guid' => $upload_dir['url'] . '/' . basename( $webp_image_path ),
						'post_mime_type' => 'image/webp',
						'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $webp_image_path ) ),
						'post_content' => '',
						'post_status' => 'inherit'
					);
					$attach_id = wp_insert_attachment( $attachment, $webp_image_path );
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					$attach_data = wp_generate_attachment_metadata( $attach_id, $webp_image_path );
					wp_update_attachment_metadata( $attach_id,  $attach_data );
					echo '<p>Image converted successfully! <a href="' . esc_url( wp_get_attachment_url( $attach_id ) ) . '">Download WebP image</a></p>';
				}
			} 
			elseif ( isset( $_FILES['image_file'] ) ) {
				// Handle uploaded image file...
				$file = $_FILES['image_file'];
				$extension = pathinfo( $file['name'], PATHINFO_EXTENSION );
				if ( $extension === 'jpg' || $extension === 'jpeg' ) {
					$image = imagecreatefromjpeg( $file['tmp_name'] );
				} elseif ( $extension === 'png' ) {
					$image = imagecreatefrompng( $file['tmp_name'] );
				}
				$upload_dir = wp_upload_dir();
				$webp_image_path = $upload_dir['path'] . '/' . str_replace( ".$extension", '.webp', $file['name'] );
				imagewebp( $image, $webp_image_path );
				imagedestroy( $image );
				$attachment = array(
					'guid' => $upload_dir['url'] . '/' . basename( $webp_image_path ),
					'post_mime_type' => 'image/webp',
					'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $webp_image_path ) ),
					'post_content' => '',
					'post_status' => 'inherit'
				);
				$attach_id = wp_insert_attachment( $attachment, $webp_image_path );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $webp_image_path );
				wp_update_attachment_metadata( $attach_id,  $attach_data );
				echo '<p>Image converted successfully! <a href="' . esc_url( wp_get_attachment_url( $attach_id ) ) . '">Download WebP image</a></p>';
			}
        ?>
    </div>
    <?php
}