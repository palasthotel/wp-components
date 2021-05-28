<?php


namespace Palasthotel\WordPress\Service;

/**
 * Interface StoreInterface
 * @package Palasthotel\WordPress\Service
 * @version 0.1.1
 */
interface StoreInterface {
	public function set($id, $value);
	public function get($id);
	public function delete($id);
}