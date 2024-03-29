<?php
/**
 * Created by PhpStorm.
 * User: sscho
 * Date: 01/02/2018
 * Time: 17:50
 */

namespace Lka\Includes;


class MetaBox {

	private $filter;

	public function __construct() {

		$this->filter = new Filter();
		// add rating script
		$this->filter->add_filter('rwmb_meta_boxes', $this, 'addMetaBox');
		$this->filter->add_action('save_post', $this, 'validateMetaBox');
		$this->filter->run();

	}

	public function addMetaBox() {
		$prefix = 'lka_';

		$meta_boxes[] = array(
			'id' => 'lka_meta',
			'title' => esc_html__( 'Lekker kontje meta', 'lekker-kontje' ),
			'context' => 'side',
			'post_types' => array( 'attachment' ),
			'priority' => 'high',
			'autosave' => true,
			'fields' => array(
				array(
					'id' => $prefix . 'image_orientation',
					'name' => esc_html__( 'Image orientation', 'lekker-kontje' ),
					'type' => 'button_group',
					'desc' => esc_html__( 'Select orientation (landscape or portrait)', 'lekker-kontje' ),
					'options' => array(
						'landscape' => esc_html__( 'Landscape', 'lekker-kontje' ),
						'portrait' => esc_html__( 'Portrait', 'lekker-kontje' )
					),
				),
				array(
					'id' => $prefix . 'image_group',
					'name' => esc_html__( 'Image group', 'lekker-kontje' ),
					'type' => 'button_group',
					'desc' => esc_html__( 'Select object (male. female, motocycle or car)', 'lekker-kontje' ),
					'options' => array(
						'male' => esc_html__('Male', 'lekker-kontje' ),
						'female' => esc_html__('Female', 'lekker-kontje' ),
						'cars' => esc_html__('Cars', 'lekker-kontje' ),
						'motor' => esc_html__('Motorcycle', 'lekker-kontje' ),
					),
					'female' => 'Female'
				),
				array(
					'id' => $prefix . 'image_tag',
					'type' => 'text',
					'name' => esc_html__( 'Image tag', 'lekker-kontje' ),
					'desc' => esc_html__( 'Add a tag: blue, big, unique.', 'lekker-kontje' ),
					'size' => 20,
				),
				array(
					'id' => $prefix . 'image_validate',
					'name' => esc_html__( 'Image validation', 'lekker-kontje' ),
					'type' => 'button_group',
					'desc' => esc_html__( 'Validate image', 'lekker-kontje' ),
					'options' => array(
						'valid' => esc_html__( 'Validated', 'lekker-kontje' ),
						'wait' => esc_html__( 'Waiting', 'lekker-kontje' ),
						'invalid' => esc_html__( 'invalid', 'lekker-kontje' )
					),
				),
				array(
					'id' => $prefix . 'image_email',
					'type' => 'text',
					'name' => esc_html__( 'Image email', 'lekker-kontje' ),
					'desc' => esc_html__( 'Email used to upload image.', 'lekker-kontje' ),
					'size' => 20,
				),
				array(
					'id' => $prefix . 'image_focus',
					'name' => esc_html__( 'Image focus', 'lekker-kontje' ),
					'type' => 'button_group',
					'desc' => esc_html__( 'Set focus point', 'lekker-kontje' ),
					'options' => array(
						'top' => esc_html__( 'Top', 'lekker-kontje' ),
						'center' => esc_html__( 'Midden', 'lekker-kontje' ),
						'bottom' => esc_html__( 'Onder', 'lekker-kontje' )
					),
				),
			),
		);

		return $meta_boxes;
	}

	public function validateMetaBox() {

	}

}
