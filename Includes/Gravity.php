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

		// after mail and entry registration we add media to library and get the id / set it in entry
		$this->filter->add_action('gform_after_submission_1', $this, 'send_approval');

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

	public function send_approval($entry, $form) {

		$monthlyFolder = ABSPATH . "wp-content/uploads/" . date("Y") . '/' . date("m") . '/';
		$monthlyFolderSub = date("Y") . '/' . date("m") . '/';

		if (!is_dir($monthlyFolder)) {
			mkdir($monthlyFolder);
		}

		// Get the path to the upload directory.
		$wp_upload_dir = wp_upload_dir();
		$file_end = str_replace($entry['source_url'], '', $entry[12]);
		$file_path = $wp_upload_dir[''] . $file_end;

		$slug = sanitize_title_with_dashes($entry[10]);
		$ext = end(explode('.', basename($entry[12])));

		$new_file = $monthlyFolder . $slug . '.' . $ext;
		$slugAddition = $slug;

		for ($x = 0; $x < 1000; $x++) {
			if (file_exists($new_file)) {
				$slug = $slugAddition . '-' . $x;
				$new_file = $monthlyFolder . $slug . '.' . $ext;
			}
		}
		$file = file_get_contents($file_path);
		file_put_contents($new_file, $file);

		// Check the type of file. We'll use this as the 'post_mime_type'.
		$filetype = wp_check_filetype( basename( $new_file ), null );

		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . $slug . '.' .$ext,
			'post_mime_type' => $filetype['type'],
			'post_title'     => $entry['10'],
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		if (!empty($entry['created_by'])) {
			$attachment['post_author'] = $entry['created_by'];
		}

		if ($attchment_id = wp_insert_attachment( $attachment, $new_file)) {
			if ($attach_data = wp_generate_attachment_metadata( $attchment_id, $new_file)) {
				wp_update_attachment_metadata($attchment_id, $attach_data);
			}
		}

		if ($attchment_id) {
			$result = \GFAPI::update_entry_field( $entry['id'], 13, $attchment_id );
		}
	}

}