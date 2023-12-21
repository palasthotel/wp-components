<?php


namespace Palasthotel\WordPress;

use wpdb;

/**
 * @version 0.1.2
 */
abstract class Database {

	protected wpdb $wpdb;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->init();
	}

	/**
	 * initialize table names and other properties
	 */
	abstract function init();

	public function createTables(){
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	}
}
