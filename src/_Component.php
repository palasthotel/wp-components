<?php


namespace Palasthotel\WordPress;

/**
 * @property Plugin plugin
 */
class _Component {

	public function __construct(Plugin $plugin) {
		$this->plugin = $plugin;
		$this->onCreate();
	}

	public function onCreate(){

	}
}