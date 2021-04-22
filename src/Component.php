<?php


namespace Palasthotel\WordPress;

/**
 * @property Plugin plugin
 */
class Component {

	public function __construct(Plugin $plugin) {
		$this->plugin = $plugin;
		$this->onCreate();
	}

	public function onCreate(){

	}
}