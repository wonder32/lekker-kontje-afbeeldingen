<?php
/**
 * Created by PhpStorm.
 * User: sscho
 * Date: 03/03/2017
 * Time: 19:25
 */

function get_prevnext($post) {


    $attachments = get_posts( array(
        'post_type' => 'attachment',
        'posts_per_page' => -1,
        'post_parent' => $post->post_parent
    ) );



    foreach ($attachments as $attachment => $info) {

        if ($info->ID == $post->ID) {
            $index = (int) $attachment;
        }
    }

    if (is_object($attachments[$index + 1])) {
        $prevNext['previous'] = get_page_link($attachments[$index + 1]->ID, false);
    } else {
        $prevNext['previous'] = '';
    }
    if (is_object($attachments[$index - 1])) {
        $prevNext['next'] = get_page_link($attachments[$index - 1]->ID, false);
    } else {
        $prevNext['next'] = '';
    }
    return $prevNext;

}


function kontjeGetPrevNext($post = null, $direction = null) {

    global $prevnext;


    if (!is_null($post) && !is_null($direction)) {

        if (!empty($prevnext[$direction])) {
            $sign = $direction == 'previous' ? '←' : '→';
            echo "<a href='{$prevnext[$direction]}#entry-title' title=''>{$sign}</a>";
        }
    }
}