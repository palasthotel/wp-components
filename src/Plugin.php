<?php


namespace Palasthotel\WordPress;


/**
 * @property string path
 * @property string url
 */
class Plugin {

	private function __construct() {
		$this->path = plugin_dir_path(__FILE__);
		$this->url = plugin_dir_url(__FILE__);

	}

	private static $instance;
	public static function instance(){
		if(!self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}
}