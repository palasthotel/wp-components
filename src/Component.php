<?php


namespace Palasthotel\WordPress;

/**
 * Class Component
 *
 * @package Palasthotel\WordPress
 * @version 0.1.3
 */
abstract class Component {
	var Plugin $plugin;

	/**
	 * _Component constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct(Plugin $plugin) {
		$this->plugin = $plugin;
		$this->onCreate();
	}

	/**
	 * overwrite this method in component implementations
	 */
	public function onCreate(){
		// init your hooks and stuff
	}
}
