<?php


namespace Palasthotel\WordPress\Service;

/**
 * Class PostMetaStore
 * @package Palasthotel\WordPress\Service
 * @version 0.1.1
 */
class PostMetaStore implements StoreInterface {

	private $metaKey;

	public function __construct($metaKey) {
		$this->metaKey = $metaKey;
	}

	public function set( $id, $value ) {
		update_post_meta($id, $this->metaKey, $value);
	}

	public function get( $id ) {
		return get_post_meta($id, $this->metaKey, true);
	}

	public function delete( $id ) {
		delete_post_meta($id, $this->metaKey);
	}
}