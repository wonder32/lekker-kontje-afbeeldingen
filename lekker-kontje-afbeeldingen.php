<?php
/*
Plugin Name: Lekker Kontje
Plugin URI:  https://wordpress.org/plugins/puddinq-dashboard/
Description: functionaliteit voor lekker kontje
Version:     0.0.1
Author:      wonder32
Author URI:  http://www.puddinq.nl/wip/stefan-schotvanger/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: lekker-kontje
Domain path: /lang/
*/

define('LEKKERDIR', plugin_dir_path(__FILE__));
define('LEKKERFILE', __FILE__);

require_once LEKKERDIR . 'includes/functions.php';
require_once LEKKERDIR . 'includes/permalinks.php';
require_once LEKKERDIR . 'includes/admin-columns.php';
require_once LEKKERDIR . 'includes/image-loop.php';
require_once LEKKERDIR . 'includes/ajax-rating.php';

//see image loop . php
add_shortcode('lekker-kontje-afbeeldingen', 'lekker_kontje_afbeeldingen');

add_image_size( 'breed', 800, '', true );

//see ajax rating . php
add_action( 'wp_ajax_rate_kontje', 'kontjes_rate_kontje' ); // for loggedin users
add_action( 'wp_ajax_nopriv_rate_kontje', 'kontjes_rate_kontje' ); // for guests

function lekker_kontjes_filter()    {

    global $post;

    // first we create the array and put the admin-ajax.php in it
    // and the nonce a situation key that must be the same (website // php function)
    $value = array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce('kontje_nonce'),
        'id'    => $post->ID
    );
    // if a session exists we put that in it
    // page_clicked should match some variabele names in the javascript file(4)
    if (isset($_SESSION["page_clicked"])) {
        $value['page_clicked'] = $_SESSION["page_clicked"];
    }

    
    // we only load the script on a page
    if (is_attachment()) {
        // you can rename my-plugin filter but it has to be the same as in the rows below
        // you can rename the my-plugin.js but it should be the same as the file name
        wp_register_script('kontjes-filter', plugin_dir_url(__FILE__) . 'js/kontjes.js', array('jquery'));
        wp_enqueue_script('kontjes-filter');
        // now we 'give'the $value (from above) to the javascript file
        wp_localize_script('kontjes-filter', 'kontje', $value);
    }
}
add_action('wp_enqueue_scripts', 'lekker_kontjes_filter');