<?php


namespace Palasthotel\WordPress;

/**
 * Class Component
 *
 * @package Palasthotel\WordPress
 * @version 0.1.3
 */
abstract class Component {
	protected Plugin $plugin;

	/**
	 * _Component constructor.
	 */
	public function __construct(Plugin $plugin) {
		$this->plugin = $plugin;
		$this->onCreate();
	}

	public function getPlugin(): Plugin {
		return $this->plugin;
	}

	/**
	 * overwrite this method in component implementations
	 */
	public function onCreate(){
		// init your hooks and stuff
	}
}
