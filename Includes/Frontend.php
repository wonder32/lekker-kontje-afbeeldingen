<?php
/**
 * Created by PhpStorm.
 * User: sscho
 * Date: 31/01/2018
 * Time: 22:03
 */

namespace Lka\Includes;


class Frontend {

	public function __construct() {

		$this->filter = new Filter();
		// add rating script
		$this->filter->add_action('wp_enqueue_scripts', $this, 'lekker_kontjes_filter');
		// hook add_rewrite_rules function into rewrite_rules_array
		$this->filter->add_action('rewrite_rules_array', $this, 'add_rewrite_rules');
		// hook add_query_vars function into query_vars
		$this->filter->add_action('query_vars', $this, 'add_query_vars');
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
			// you can rename my-plugin filter but it has to be the same as in the rows below
			// you can rename the my-plugin.js but it should be the same as the file name
			wp_register_script('kontjes-filter', plugin_dir_url(LEKKERFILE) . 'js/kontjes.js', array('jquery'));
			wp_enqueue_script('kontjes-filter');
			// now we 'give'the $value (from above) to the javascript file
			wp_localize_script('kontjes-filter', 'kontje', $value);
		}
	}

	public function add_query_vars($aVars) {
		array_push($aVars, 'img_group', 'img_tag');
		//$aVars[] = "img_group"; // represents the name of the product category as shown in the URL
		return $aVars;
	}



	public function add_rewrite_rules($aRules) {
		$aNewRules1 = array('kontjes/([^/]+)/([^/]+)/?$' => 'index.php?pagename=kontjes&img_group=$matches[1]&img_tag=$matches[2]');
		$aNewRules2 = array('kontjes/([^/]+)/?$' => 'index.php?pagename=kontjes&img_group=$matches[1]');

		$aRules = $aNewRules1 + $aNewRules2 + $aRules;
		return $aRules;
	}
}