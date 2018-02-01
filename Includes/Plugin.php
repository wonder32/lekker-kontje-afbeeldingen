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



		$this->filter->run();

	}

}