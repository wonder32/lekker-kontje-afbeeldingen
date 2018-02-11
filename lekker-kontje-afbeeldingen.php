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

use Lka\Includes\Plugin;
//use Npl\Includes\Backend;
//use Npl\Includes\Front;

if ( ! defined('WPINC')) {
	die;
}


require_once 'vendor/autoload.php';


define('LEKKERDIR', plugin_dir_path(__FILE__));
define('LEKKERFILE', __FILE__);


require_once LEKKERDIR . 'Includes/image-loop.php';
require_once LEKKERDIR . 'Includes/ajax-rating.php';

//see image loop . php
add_shortcode('lekker-kontje-afbeeldingen', 'lekker_kontje_afbeeldingen');


add_image_size( 'grid', '', 300, true);
add_image_size( 'single', '', 600, true);

//see ajax rating . php
add_action( 'wp_ajax_rate_kontje', 'kontjes_rate_kontje' ); // for loggedin users
add_action( 'wp_ajax_nopriv_rate_kontje', 'kontjes_rate_kontje' ); // for guests


new Plugin;