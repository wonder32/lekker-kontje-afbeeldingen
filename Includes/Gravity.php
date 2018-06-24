<?php
/**
 * Created by PhpStorm.
 * User: sscho
 * Date: 02/02/2018
 * Time: 23:03
 */

namespace Lka\Includes;


class Gravity {

	private $filter;


	public function __construct() {
		$this->filter = new Filter();

		// first we add a random number to the empty form
		$this->filter->add_action('gform_pre_submission_1', $this, 'add_random_value');
		// Set upload field
		$this->filter->add_filter('gform_field_input_1_12', $this, 'accept_images', 30);

		$this->filter->run();
	}

	public function add_random_value( $form ){
		foreach($form["fields"] as &$field)
			if($field["id"] == 14){
				/* Set the variable you want here - in some cases you might need a switch based on the page ID.
				* $page_id = get_the_ID();
				*/
				$random_id = rand ( 1000000 , 9999999 );
				/* Do a View Source on the page with the Gravity Form and look for the name="" for the field you want */
				$_POST["input_14"] = $random_id;
			}
		return $form;
	}

	/**
	 * Set upload field
	 *
	 * Changes the upload field to have android 'accept' attribute
	 *
	 * @param $input
	 *
	 * @return string
	 */
	public function accept_images($input) {

		$input = '<input name="input_12" id="input_1_12" type="file" class="large" aria-describedby="extensions_message_1_12" onchange="javascript:gformValidateFileSize( this, 5242880 );" tabindex="2" accept="images/*"  capture="camera">';
		return $input;
	}

}