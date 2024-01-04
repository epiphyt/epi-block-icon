<?php
namespace epiphyt\Block_Icon;

/**
 * The main plugin class.
 * 
 * @author	Epiphyt
 * @license	GPL2
 * @package	epiphyt\Block_Icon
 */
final class Plugin {
	/**
	 * @var		\epiphyt\Block_Icon\Plugin
	 */
	private static $instance;
	
	/**
	 * @var		array List of registered icons
	 */
	private $registered_icons = [];
	
	/**
	 * Initialize functions.
	 */
	public function init(): void {
		\add_action( 'init', [ self::class, 'load_textdomain' ], 0 );
		\add_action( 'wp_footer', [ $this, 'enqueue_icons' ] );
		
		Admin::init();
		Block::init();
	}
	
	/**
	 * Enqueue SVG icons.
	 */
	public function enqueue_icons(): void {
		global $wp_filesystem;
		
		// initialize the WP filesystem if not exists
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			
			\WP_Filesystem();
		}
		
		// get all used icons and embed them
		$content = '<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">';
		
		foreach ( \array_unique( $this->registered_icons ) as $icon ) {
			$icon_path = \EPI_BLOCK_ICON_BASE . 'assets/images/' . self::get_icon_set() . '/' . self::get_icon_variant() . '/' . $icon . '.svg';
			
			if ( ! \file_exists( $icon_path ) ) {
				continue;
			}
			
			$content .= str_replace(
				[ '<svg', '</svg>' ],
				[ '<symbol id="' . esc_attr( $icon ) . '"', '</symbol>' ],
				$wp_filesystem->get_contents( $icon_path )
			);
		}
		
		$content .= '</svg>';
		
		echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	
	/**
	 * Get the current active icon set.
	 * 
	 * @return	string Current active icon set
	 */
	public static function get_icon_set(): string {
		return \get_option( 'epi_block_icon_set', 'phosphor' );
	}
	
	/**
	 * Get the current active icon variant.
	 * 
	 * @return	string Current active icon variant
	 */
	public static function get_icon_variant(): string {
		return \get_option( 'epi_block_icon_variant', 'regular' );
	}
	
	/**
	 * Get a unique instance of the class.
	 * 
	 * @return	Plugin The instance
	 */
	public static function get_instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	/**
	 * Load translations.
	 */
	public static function load_textdomain(): void {
		\load_plugin_textdomain( 'epi-block-icon', false, \dirname( \plugin_basename( \EPI_BLOCK_ICON_FILE ) ) . '/languages' );
	}
	
	/**
	 * Register an icon to be enqueued.
	 * 
	 * @param	string	$icon The icon name
	 */
	public function register_icon( string $icon ): void {
		if ( empty( $icon ) ) {
			return;
		}
		
		if ( ! \in_array( $icon, $this->registered_icons, true ) ) {
			$this->registered_icons[] = $icon;
		}
	}
}
