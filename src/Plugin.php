<?php


namespace Palasthotel\WordPress;


/**
 * @property string path
 * @property string url
 */
abstract class Plugin {

	public function __construct() {
		$this->path = plugin_dir_path(__FILE__);
		$this->url = plugin_dir_url(__FILE__);

	}

	private static $instance;
	public static function instance(){
		if(!self::$instance){
			self::$instance = new static();
		}
		return self::$instance;
	}
}