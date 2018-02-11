<?php
/**
 * Created by PhpStorm.
 * User: sscho
 * Date: 27/02/2017
 * Time: 18:34
 */

function lekker_kontje_afbeeldingen() {

    global $wp_query;

    $group = $wp_query->get('lka_image_group');
    $tag = $wp_query->get('lka_image_tag');
    $groups = ['female' => 'vrouwen', 'male' => 'mannen', 'cars' => 'autos', 'motor' => 'motor'];

    if (! ( $group = array_search($group, $groups))) {
        $tag = $group;
        $group = false;
    }
    


    $args = array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'post_parent' => '71',
        //'orderby' => 'rand',
        'posts_per_page' => 50
    );

    $meta = array();

    if ($group && $tag) {
        $meta[] = ['relation' => 'AND'];
    }

    if ($group) {
        $meta[] = [
            'key'     => 'lka_image_group',
            'value'   => $group,
            'compare' => '='
        ];
    }
    if ($tag) {
        $meta[] = [
            'key'     => 'lka_image_tag',
            'value'   => $tag,
            'compare' => 'like'
        ];
    }

    if (!empty($meta)) {
        $args['meta_query'] = $meta;
    }
       

    $classes = '';
    $classes .= !empty($tag) ? ' tag-' . $tag : '';
    $classes .= !empty($group) ? ' group-' . $group : '';

    echo "<div class='lekker-galery {$classes}'>";

    $posts = get_posts( $args );
    $unordered_posts = array();
    $number_of_posts = count($posts);

    if ( $posts ) {
        foreach ($posts as $post) :

            $image_meta = wp_get_attachment_metadata( $post->ID );
            $orientation = $image_meta['width'] / $image_meta['height'] > 1 ? 'wide' : 'tall';
            $height = wp_is_mobile() ? '138' : '160';

            $alt = rwmb_meta('img_tag', '', $post->ID);
            $image = wp_get_attachment_image($post->ID, array('', $height), "", array("class" => "img-responsive afbeelding-section", "rel" => "lightbox", "alt" => 'kontje, ' . $alt));
            $link = get_page_link($post->ID, false);
            $title = get_the_title($post->ID);


            $block = "<div class='image-block' id='image-frame-{$post->post_name}'>";
            $block .= "<a href='{$link}' title='{$title}' alt='{$alt}'>{$image}</a>";
            $block .= '<div class="image-info">';
            $block .= $post->post_title;
            $block .= '</div>';
            $block .= '</div>';

            $unordered_posts[$orientation][] = $block;

        endforeach;

        $orient = 'tall';
        $orient_val['tall'] = 0;
        $orient_val['wide'] = 0;

        for ($x = 1; $x <= $number_of_posts; $x++) :

            $de_orient = $orient == 'tall' ? 'wide' : 'tall';

            if (isset($unordered_posts[$orient][$orient_val[$orient]])) {
                echo $unordered_posts[$orient][$orient_val[$orient]];
                $orient_val[$orient]++;
            } else {
                echo $unordered_posts[$de_orient][$orient_val[$de_orient]];
                $orient_val[$de_orient]++;
            }

            $orient = $orient == 'wide' ? 'tall' : 'wide';

        endfor;



    } else {
        echo "Sorry, geen afbeeldingen gevonden met de combinatie {$group} {$tag}";
    }

    echo '</div>';
}