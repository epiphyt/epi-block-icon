<?php
namespace epiphyt\Block_Icon;

/*
Plugin Name:	Block Icon
Description:	An enhanced icon block.
Author:			Epiphyt
Author URI:		https://epiph.yt/en/
Version:		1.0.0-dev
License:		GPL2
License URI:	https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:	epi-block-icon
Domain Path:	/languages

Block Icon is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Block Icon is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Block Icon. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

// exit if ABSPATH is not defined
\defined( 'ABSPATH' ) || exit;

\define( 'EPI_BLOCK_ICON_VERSION', '1.0.0-dev' );

if ( ! \defined( 'EPI_BLOCK_ICON_BASE' ) ) {
	\define( 'EPI_BLOCK_ICON_BASE', WP_PLUGIN_DIR . '/epi-block-icon/' );
}

if ( ! \defined( 'EPI_BLOCK_ICON_FILE' ) ) {
	\define( 'EPI_BLOCK_ICON_FILE', __FILE__ );
}

if ( ! \defined( 'EPI_BLOCK_ICON_URL' ) ) {
	\define( 'EPI_BLOCK_ICON_URL', \plugin_dir_url( EPI_BLOCK_ICON_FILE ) );
}

/**
 * Autoload all necessary classes.
 * 
 * @param	string		$class The class name of the autoloaded class
 */
\spl_autoload_register( function( string $class ) {
	$namespace = \strtolower( __NAMESPACE__ . '\\' );
	$path = \explode( '\\', $class );
	$filename = \str_replace( '_', '-', \strtolower( \array_pop( $path ) ) );
	$class = \str_replace(
		[ $namespace, '\\', '_' ],
		[ '', '/', '-' ],
		\strtolower( $class )
	);
	$string_position = \strrpos( $class, $filename );
	
	if ( $string_position !== false ) {
		$class = \substr_replace( $class, 'class-' . $filename, $string_position, \strlen( $filename ) );
	}
	
	$maybe_file = __DIR__ . '/inc/' . $class . '.php';
	
	if ( \file_exists( $maybe_file ) ) {
		require_once $maybe_file;
	}
} );

\add_action( 'plugins_loaded', [ Plugin::get_instance(), 'init' ] );
