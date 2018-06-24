<?php
/**
 * Created by PhpStorm.
 * User: sscho
 * Date: 31/01/2018
 * Time: 22:03
 */

namespace Lka\Includes;


class Backend {

	private $filter;

	public function __construct() {

		$this->filter = new Filter();

		// admin columns media
		$this->filter->add_action('manage_media_columns', $this, 'lekker_media_columns');
		$this->filter->add_action('manage_media_custom_column', $this, 'lekker_media_columns_content', 10, 10);
		$this->filter->add_action('manage_upload_sortable_columns', $this, 'lekker_sortable_media_columns');
		$this->filter->add_action('request', $this, 'lekker_sortable_media_columns_function');

		$this->filter->run();

	}

	public function lekker_media_columns($columns)
	{
		$columns = array(
			'cb'	 	=> '<input type="checkbox" />',
			'title' 	=> 'Title',
			'author'	=>	'Author',
			'group'	    =>	'Group',
			'tag'	    =>	'Tag',
			'validate'	=>	'Validated',
			'parent'	=>	'Folder',
			'date'		=>	'Date',
		);
		return $columns;
	}




	public function lekker_media_columns_content($column, $id)
	{
		switch ($column) {

			case 'group':

				$group = rwmb_meta( 'lka_image_group', '', $id );
				if ($group) {
					echo $group;
				}
				break;

			case 'tag':

				$tag = rwmb_meta( 'lka_image_tag', '', $id );
				if ($tag) {

					echo $tag;
				}
				break;

			case 'validate':

				$validate = rwmb_meta( 'lka_image_validate', '', $id );
				if ($validate) {
					echo $validate;
				}
				break;

		}
	}




	public function lekker_sortable_media_columns( $columns ) {
		$columns['tag'] = 'tag';
		$columns['group'] = 'group';
		$columns['validate'] = 'validate';

		//To make a column 'un-sortable' remove it from the array
		//unset($columns['date']);

		return $columns;
	}



	public function lekker_sortable_media_columns_function( $vars ) {
		if ( isset( $vars['orderby'] ) && 'group' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array('meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => 'lka_image_group',
					'compare' => 'NOT EXISTS', // doesn't work
				     'value' => 'lka_image_group'
				),
				array(
					'key' => 'lka_image_group',
					'value'   => array(''),
					'compare' => 'NOT IN'
				)
			),
//			'meta_key' => 'lka_image_group',
			'orderby' => 'meta_value'
			));
		}
		if ( isset( $vars['orderby'] ) && 'tag' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array('meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => 'lka_image_tag',
					'compare' => 'NOT EXISTS', // doesn't work
					'value' => 'lka_image_tag'
				),
				array(
					'key' => 'lka_image_tag',
					'value'   => array(''),
					'compare' => 'NOT IN'
				)
			),
//			'meta_key' => 'lka_image_tag',
				'orderby' => 'meta_value'
			) );
		}
		if ( isset( $vars['orderby'] ) && 'validate' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array('meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => 'lka_image_validate',
					'compare' => 'NOT EXISTS', // doesn't work
					'value' => 'lka_image_validate'
				),
				array(
					'key' => 'lka_image_validate',
					'value'   => array(''),
					'compare' => 'NOT IN'
				)
			),
//				'meta_key' => 'lka_image_validate',
				'orderby' => 'meta_value'
			) );
		}
		return $vars;
	}

}