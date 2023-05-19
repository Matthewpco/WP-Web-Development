<?php
/*
Plugin Name: Include Extra Scripts
Plugin URI: https://github.com/Matthewpco/
Description: Adds a submenu under Tools in the dashboard called Extra Scripts, where you can enter multiple lines of JavaScript code to be enqueued as a script on the WordPress website.
Version: 1.2.0
Author: Gary Matthew Payne | BuiltMighty
Author URI: https://www.wpwebdevelopment.com
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add submenu page under Tools
function ies_add_submenu_page() {
    add_submenu_page(
        'tools.php',
        'Extra Scripts',
        'Extra Scripts',
        'manage_options',
        'custom-scripts',
        'ies_render_submenu_page'
    );
}
add_action( 'admin_menu', 'ies_add_submenu_page' );

// Render the submenu page
function ies_render_submenu_page() {
    // Check if the user has submitted the form
    if ( isset( $_POST['ies_scripts_nonce'] ) && wp_verify_nonce( $_POST['ies_scripts_nonce'], 'ies_scripts_save' ) ) {
        // Save the entered JavaScript code as an option
        update_option( 'ies_scripts_code', stripslashes( $_POST['ies_scripts_code'] ) );

        // Display a success message
        echo '<div class="updated"><p>JavaScript code saved successfully.</p></div>';
    }

    // Get the saved JavaScript code
    $ies_scripts_code = get_option( 'ies_scripts_code', '' );
    ?>
    <div class="wrap">
        <h1>Scripts</h1>
        <form method="post">
            <?php wp_nonce_field( 'ies_scripts_save', 'ies_scripts_nonce' ); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="ies_scripts_code">JavaScript Code</label></th>
                    <td><textarea name="ies_scripts_code" id="ise_scripts_code" class="large-text code" rows="20"><?php echo esc_textarea( $ies_scripts_code ); ?></textarea></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}


// Enqueue the entered JavaScript code as a script on the WordPress website
function ies_enqueue_script() {
    // Get the saved JavaScript code
    $ies_scripts_code = get_option( 'ies_scripts_code', '' );

    // Enqueue the script
    wp_add_inline_script( 'jquery-core', $ies_scripts_code );
}
add_action( 'wp_enqueue_scripts', 'ies_enqueue_script' );