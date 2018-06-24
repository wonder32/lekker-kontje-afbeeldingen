<?php
/**
 * Created by PhpStorm.
 * User: sscho
 * Date: 02/02/2018
 * Time: 23:03
 */

namespace Lka\Includes;


class Image {

	private $filter;
	private $next;
	private $previous;
	private $rating;
	private $orientation;
	private $group;
	private $description;
	private $tag;
	private $validate;
	private $email;

	public function __construct() {

		if ( get_post_type() === 'attachment' ) {
			$post        = get_post();
			$this->previous = $this->pu_adjacent_image_link();
			$this->next     = $this->pu_adjacent_image_link(true);
			$this->rating   = $this->retrieveRating($post);
			$this->orientation   = get_post_meta($post->ID, 'lka_image_orientation', true);
			$this->group   = get_post_meta($post->ID, 'lka_image_group', true);
			$this->tag     = get_post_meta($post->ID, 'lka_image_tag', true);
			$this->email   = get_post_meta($post->ID, 'lka_image_email', true);
			$this->validate   = get_post_meta($post->ID, 'lka_image_validate', true);
			$this->description   = apply_filters( 'the_description' , $post->post_excerpt );

		}
	}

	/**
	 * @return false|string
	 */
	public function getNext() {
		return $this->next;
	}

	/**
	 * @return false|string
	 */
	public function getPrevious() {
		return $this->previous;
	}

	/**
	 * @return mixed|void
	 */
	public function getRating() {
		return $this->rating;
	}

	/**
	 * @return mixed|void
	 */
	public function getDescription() {
		return $this->description;
	}

	private function retrieveRating($post) {

		$rating_meta = get_post_meta($post->ID, 'kontjes');

		if (!$rating_meta) return;

		$rating_array = array_slice($rating_meta, 0, 1);

		$rating = array_shift($rating_array);

		return $rating;
	}

	public function pu_adjacent_image_link( $prev = true, $size = 'thumbnail', $text = false ) {
		$post        = get_post();
		$attachments = array_values( get_children( array( 'post_parent'    => $post->post_parent,
		                                                  'post_status'    => 'inherit',
		                                                  'post_type'      => 'attachment',
		                                                  'post_mime_type' => 'image',
		                                                  'order'          => 'ASC',
		                                                  'orderby'        => 'menu_order ID'
		) ) );

		foreach ( $attachments as $k => $attachment ) {
			if ( $attachment->ID == $post->ID ) {
				break;
			}
		}

		$output        = '';
		$attachment_id = 0;

		if ( $attachments ) {
			$k = $prev ? $k - 1 : $k + 1;

			if ( isset( $attachments[ $k ] ) ) {
				$attachment_id = $attachments[ $k ]->ID;
				$output        = wp_get_attachment_link( $attachment_id, $size, true, false, $text );
			}
		}

		return get_the_permalink( $attachment_id );
	}

	/**
	 * @return mixed
	 */
	public function getEmail() {
		if (strpos($this->email, 'otvanger') === false) {
			return $this->email;
		}
	}

	/**
	 * @return mixed
	 */
	public function getGroup() {
		return $this->group;
	}

	/**
	 * @return mixed
	 */
	public function getTag() {
		return $this->tag;
	}

	/**
	 * @return mixed
	 */
	public function getOrientation() {
		return $this->orientation;
	}

	/**
	 * @return mixed
	 */
	public function getValidate() {
		return $this->validate;
	}
}