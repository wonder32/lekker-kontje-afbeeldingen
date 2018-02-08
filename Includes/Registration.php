<?php
/**
 * Created by PhpStorm.
 * User: sscho
 * Date: 07/02/2018
 * Time: 19:16
 */

namespace Lka\Includes;


class Registration {

	function __construct() {
		$this->make_shortcode();
	}

	function ShowMsg() {

		$error = 'You do not have the right credentials';
		$args = [
			'id'    =>      FILTER_SANITIZE_NUMBER_INT,
			'pic'  =>      FILTER_SANITIZE_NUMBER_INT
		];
		$get = filter_input_array(INPUT_GET, $args);
		

		if (!is_user_logged_in() || empty($get['id']) || empty($get['pic'])) {
			return $error;
		}

		$entry = \GFAPI::get_entry( $get['id'] );

		if (!is_array($entry) || !isset($entry[15])) {
			return $error;
		}

		if ($entry[14] !== $get['pic']) {
			return $error;
		}
//		$result = \GFAPI::update_entry_field( $entry['id'], 15, 'x34o' );


		switch ($entry[15]) {
			case 'x34o':
				$this->validate($entry);
				break;
			case 'y12o':
				return 'You have already validated this';
				break;
			default:
				return $error;
		}

		$image = '<img src="' . $entry[12] . '">';

		return $image;

	}

	function make_shortcode() {
		add_shortcode('view-submission', array($this,'ShowMsg'));
	}

	public function validate($entry) {

		if ($metadata = wp_update_post(
			array(
				'ID' => $entry[13],
				'post_parent' => 71
			)
		));
		if ($metadata) {
			$result = \GFAPI::update_entry_field( $entry['id'], 15, 'y12o' );
		}
	}
}