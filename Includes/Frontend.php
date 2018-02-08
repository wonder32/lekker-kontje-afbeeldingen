<?php
/**
 * Created by PhpStorm.
 * User: sscho
 * Date: 31/01/2018
 * Time: 22:03
 */

namespace Lka\Includes;


class Frontend {

	private $filter;
	private $registration;

	public function __construct() {

		global $lekkerKontje;
		$lekkerKontje = new Image;
		$gravity = new Gravity;
		$this->filter = new Filter();
		$this->registration = new Registration();
		// add rating script
		$this->filter->add_action('wp_enqueue_scripts', $this, 'lekker_kontjes_filter');
		$this->filter->add_action('wp', $this, 'image');
		$this->filter->run();

	}

	public function lekker_kontjes_filter()    {

		global $post;

		// first we create the array and put the admin-ajax.php in it
		// and the nonce a situation key that must be the same (website // php function)
		$value = array(
			'ajax_url' => base64_encode(admin_url( 'admin-ajax.php' )),
			'nonce' => base64_encode(wp_create_nonce('kontje_nonce')),
			'id'    => base64_encode($post->ID)
		);
		// if a session exists we put that in it
		// page_clicked should match some variabele names in the javascript file(4)
		if (isset($_SESSION["page_clicked"])) {
			$value['page_clicked'] = $_SESSION["page_clicked"];
		}


		// we only load the script on a page
		if (is_attachment()) {
			// you can rename the my-plugin.js but it should be the same as the file name
			wp_register_script('kontjes-filter', plugin_dir_url(LEKKERFILE) . 'js/kontjes.js', array('jquery'));
			wp_enqueue_script('kontjes-filter');
			// now we 'give'the $value (from above) to the javascript file
			wp_localize_script('kontjes-filter', 'kontje', $value);
		}

	}

	public function image() {

		global $lekkerKontje;
		$lekkerKontje = new Image;
		
	}

}