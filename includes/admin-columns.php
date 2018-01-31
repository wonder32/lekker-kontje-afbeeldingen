<?php

/**
 * Columns
 */

function lekker_media_columns($columns)
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

add_filter('manage_media_columns', 'lekker_media_columns');

/**
 * Pechhulp
 */

function lekker_media_columns_content($column, $id)
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


add_action("manage_media_custom_column", "lekker_media_columns_content", 10, 2);



add_filter( 'manage_upload_sortable_columns', 'lekker_sortable_media_columns' );

function lekker_sortable_media_columns( $columns ) {
    $columns['tag'] = 'tag';
    $columns['group'] = 'group';

    //To make a column 'un-sortable' remove it from the array
    //unset($columns['date']);

    return $columns;
}

add_filter( 'request', 'lekker_sortable_media_columns_function' );

function lekker_sortable_media_columns_function( $vars ) {
    if ( isset( $vars['orderby'] ) && 'Width' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'img_group',
            'orderby' => 'meta_value_num'
        ) );
    }
    return $vars;
}