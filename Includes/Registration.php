<?php
/**
 * Created by PhpStorm.
 * User: sscho
 * Date: 07/02/2018
 * Time: 19:16
 */

namespace Lka\Includes;


class Registration {

	public function __construct() {
		$this->make_shortcode();
	}

	public function add_to_media($entry) {
		$monthlyFolder = ABSPATH . "wp-content/uploads/" . date("Y") . '/' . date("m") . '/';
		$monthlyFolderSub = date("Y") . '/' . date("m") . '/';

		if (!is_dir($monthlyFolder)) {
			mkdir($monthlyFolder);
		}

		// Get the path to the upload directory.
		$wp_upload_dir = wp_upload_dir();
		$file_path = str_replace($wp_upload_dir['baseurl'], $wp_upload_dir['basedir'], $entry[12]);
		$base = basename($entry[12]);
		$base_array = explode('.', $base);
		$slug = sanitize_title_with_dashes($entry[10]);
		$ext = end($base_array);

		$new_file = $monthlyFolder . $slug . '.' . $ext;
		$slugAddition = $slug;

		for ($x = 0; $x < 1000; $x++) {
			if (file_exists($new_file)) {
				$slug = $slugAddition . '-' . $x;
				$new_file = $monthlyFolder . $slug . '.' . $ext;
				continue;
			}
		}
		
		$file = file_get_contents($file_path);
		file_put_contents($new_file, $file);
		$size = getimagesize($file_path);
		$entry['size'] = $size[0] > $size[1] ? 'landscape' : 'portrait';

		// Check the type of file. We'll use this as the 'post_mime_type'.
		$filetype = wp_check_filetype( basename( $new_file ), null );

		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . $slug . '.' .$ext,
			'post_mime_type' => $filetype['type'],
			'post_title'     => $entry['10'],
			'post_content'   => '',
			'post_parent'    => 71,
			'post_status'    => 'inherit'
		);

		if (!empty($entry['created_by'])) {
			$attachment['post_author'] = $entry['created_by'];
		}

		if ($attchment_id = wp_insert_attachment( $attachment, $new_file)) {
			include_once( ABSPATH . 'wp-admin/includes/image.php' );
			if ($attach_data = wp_generate_attachment_metadata( $attchment_id, $new_file)) {
				wp_update_attachment_metadata($attchment_id, $attach_data);
			}

			$result = \GFAPI::update_entry_field( $entry['id'], 13, $attchment_id );
			$entry[13] = $attchment_id;
			$this->validate($entry);
		}
	}

	function ShowMsg() {




		$error = 'You do not have the right credentials';
		$args = [
			'id'    =>      FILTER_SANITIZE_NUMBER_INT,
			'pic'  =>      FILTER_SANITIZE_NUMBER_INT,
			'state' =>      FILTER_SANITIZE_STRING
		];
		$get = filter_input_array(INPUT_GET, $args);
		

		if (!is_user_logged_in() || empty($get['id']) || empty($get['pic'])) {
			return $error;
		}

		if (!in_array($get['state'], array('approve')) ) {

		}
		$entry = \GFAPI::get_entry( $get['id'] );


		if (!is_array($entry) || !isset($entry[15])) {
			return $error;
		}

		if ($entry[14] !== $get['pic']) {
			return $error;
		}
//		$result = \GFAPI::update_entry_field( $entry['id'], 15, 'x34o' );

//		switch ($entry[15]) {
//			case 'x34o':
				$this->add_to_media($entry);
//				break;
//			case 'y12o':
//				return 'You have already validated this';
//				break;
//			default:
//				return $error;
//		}

		$image = '<img src="' . $entry[12] . '">';

		return $image;

	}

	public function make_shortcode() {
		add_shortcode('view-submission', array($this,'ShowMsg'));
	}

	public function validate($entry) {

//		if ($metadata = wp_update_post(
//			array(
//				'ID' => $entry[13],
//				'post_parent' => 71
//			)
//		));
//		if ($metadata) {


			$email = update_post_meta( $entry[13], 'lka_image_email', $entry[2] );
			$validate = update_post_meta( $entry[13], 'lka_image_validate', 'valid' );
			$validate = update_post_meta( $entry[13], 'lka_image_orientation', $entry['size'] );
			$result = \GFAPI::update_entry_field( $entry['id'], 15, 'y12o' );
//		}
	}
}