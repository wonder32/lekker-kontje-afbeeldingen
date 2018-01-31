<?php
/**
 * Created by PhpStorm.
 * User: sscho
 * Date: 04/03/2017
 * Time: 18:27
 */

function kontjes_rate_kontje() {
    // die if not page check (nonce) fails

    check_ajax_referer('kontje_nonce', 'filter_nonce');

    $rating = $_POST['rating'];
    $data = array();
    $attId = $_POST['attachment'];

    // start session if not started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["attachment_rate"][$_POST['attachment']] )) {

        $_SESSION["attachment_rate"][$attId] = $rating;

        $meta = get_post_meta($attId, 'kontjes');

        if (empty($meta)) {
            $meta = array();
            for ($i = 1; $i <= 5; ++$i) {
                $meta[0][$i] = 0;
            }
        }

        $meta[0][$rating] = ++$meta[0][$rating];


        $attachmentData = update_post_meta($attId, 'kontjes', $meta[0]);
        $data['data'] = get_post_meta($attId, 'kontjes');
        $data['response'] = 'succes';

    } else {
        $data['data'] = get_post_meta( $attId, 'kontjes');
        $data['response'] = 'failure';
    }

    wp_send_json($data);

    wp_die();
}