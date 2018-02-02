<?php
/**
 * Created by PhpStorm.
 * User: sscho
 * Date: 31/01/2018
 * Time: 22:03
 */

namespace Lka\Includes;


class Plugin {

	private $filter;

	private $frontend;

	private $backend;

	private $metabox;


	public function __construct() {

		if (!is_admin()) {
			$this->frontend = new Frontend;
		} else {
			$this->backend = new Backend;
		}

		$this->metabox = new MetaBox;

		$this->filter = new Filter();
		// hook add_rewrite_rules function into rewrite_rules_array
		$this->filter->add_action('rewrite_rules_array', $this, 'add_rewrite_rules');
		// hook add_query_vars function into query_vars
		$this->filter->add_action('query_vars', $this, 'add_query_vars');
		$this->filter->run();

	}

	public function add_query_vars($aVars) {
		array_push($aVars, 'lka_image_group', 'lka_image_tag');
		//$aVars[] = "img_group"; // represents the name of the product category as shown in the URL
		return $aVars;
	}



	public function add_rewrite_rules($aRules) {
		$aNewRules1 = array('kontjes/([^/]+)/([^/]+)/?$' => 'index.php?pagename=kontjes&lka_image_group=$matches[1]&lka_image_tag=$matches[2]');
		$aNewRules2 = array('kontjes/([^/]+)/?$' => 'index.php?pagename=kontjes&lka_image_group=$matches[1]');

		$aRules = $aNewRules1 + $aNewRules2 + $aRules;
		return $aRules;
	}

}