<?php


namespace Palasthotel\WordPress;


abstract class Update {

	/**
	 * version of the final data structure
	 * @return int
	 */
	abstract function getVersion(): int;

	/**
	 * version of the data structure at this moment
	 * @return int
	 */
	abstract function getCurrentVersion(): int;

	/**
	 * @param int $version
	 *
	 * @return int
	 */
	abstract function setCurrentVersion(int $version): int;

	/**
	 * check for updates
	 */
	function check_updates() {
		$current_version = $this->getCurrentVersion();

		for ( $i = $current_version + 1; $i <= $this->getVersion(); $i ++ ) {
			$method = "update_{$i}";
			if ( method_exists( $this, $method ) ) {
				$this->$method();
				$this->setCurrentVersion( $i );
			}
		}

	}

}