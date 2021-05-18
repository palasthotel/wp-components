<?php


namespace Palasthotel\WordPress;


abstract class Assets {
	private Plugin $plugin;

	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
		add_action( 'wp_enqueue_scripts', function ( $hook ) {
			$this->onEnqueue( false, $hook );
		}, 1 );
		add_action( 'admin_enqueue_scripts', function ( $hook ) {
			$this->onEnqueue( true, $hook );
		}, 1 );
	}

	abstract function onEnqueue( bool $isAdmin, string $hook );

	public function register( string $handle, string $pluginPathToFile, array $dependencies = [], bool $footer = true ): bool {
		$filePath = $this->plugin->path . $pluginPathToFile;
		if ( ! file_exists( $filePath ) ) {
			error_log( "Asset file does not exist: $filePath" );

			return false;
		}
		$assetsFilePath = "";
		if ( $this->endsWithJS( $filePath ) ) {
			$assetsFilePath = str_replace( ".js", ".asset.php", $filePath );
		}
		if ( ! empty( $assetsFilePath ) && file_exists( $assetsFilePath ) ) {
			$info = include $assetsFilePath;
		} else {
			$info["dependencies"] = [];
			$info["version"]      = filemtime( $filePath );
		}

		return wp_register_script(
			$handle,
			$this->plugin->url . $pluginPathToFile,
			array_merge( $info["dependencies"], $dependencies ),
			$info["version"],
			$footer
		);
	}

	private function endsWithJS( $haystack ): bool {
		$length = strlen( ".js" );
		if ( ! $length ) {
			return true;
		}

		return substr( $haystack, - $length ) === ".js";
	}
}