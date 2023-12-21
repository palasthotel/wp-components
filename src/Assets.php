<?php


namespace Palasthotel\WordPress;

/**
 * Class Assets
 * @version 0.2.0
 */
class Assets {

	private string $path;
	private string $url;

	public function __construct( string $basePath, string $baseUrl ) {
		$this->path = trailingslashit($basePath);
		$this->url = trailingslashit($baseUrl); 
	}

	public function registerStyle( string $handle, string $pluginPathToFile, array $dependencies = [], string $media = 'all' ): bool {
		$filePath = $this->path . $pluginPathToFile;
		$fileUrl  = $this->url . $pluginPathToFile;
		if ( ! file_exists( $filePath ) ) {
			error_log( "Style file does not exist: $filePath" );

			return false;
		}

		return wp_register_style( $handle, $fileUrl, $dependencies, filemtime( $filePath ), $media );

	}

	public function registerScript( string $handle, string $pluginPathToFile, array $dependencies = [], bool $footer = true ): bool {
		$filePath = $this->path . $pluginPathToFile;
		if ( ! file_exists( $filePath ) ) {
			error_log( "Script file does not exist: $filePath" );

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
			$this->url . $pluginPathToFile,
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
