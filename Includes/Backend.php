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
		$this->filter->add_action('rewrite_rules_array', $this, 'add_rewrite_rules');

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
			'parent'	=>	'Folder',
			'date'		=>	'Date',
		);
		return $columns;
	}



	/**
	 * Pechhulp
	 */

	public function lekker_media_columns_content($column, $id)
	{
		switch ($column) {

			case 'group':

				$group = get_field('img_group', $id);
				if ($group) {

					echo $group;
				}
				break;

			case 'tag':

				$type = get_field('img_tag', $id);
				if ($type) {

					echo $type;
				}


				break;

		}
	}




	public function lekker_sortable_media_columns( $columns ) {
		$columns['tag'] = 'tag';
		$columns['group'] = 'group';

		//To make a column 'un-sortable' remove it from the array
		//unset($columns['date']);

		return $columns;
	}



	public function lekker_sortable_media_columns_function( $vars ) {
		if ( isset( $vars['orderby'] ) && 'Width' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => 'img_group',
				'orderby' => 'meta_value_num'
			) );
		}
		return $vars;
	}

}