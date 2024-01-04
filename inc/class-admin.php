<?php
namespace epiphyt\Block_Icon;

/**
 * Admin functions.
 * 
 * @author	Epiphyt
 * @license	GPL2
 * @package	epiphyt\Block_Icon
 */
final class Admin {
	/**
	 * Initialize functions.
	 */
	public static function init(): void {
		\add_action( 'admin_init', [ self::class, 'register_settings' ] );
	}
	
	/**
	 * Register settings.
	 */
	public static function register_settings(): void {
		\add_settings_section(
			'epi_block_icon',
			\__( 'Icons', 'epi-block-icon' ),
			null,
			'general'
		);
		\add_settings_field(
			'epi_block_icon_set',
			\__( 'Icon set', 'epi-block-icon' ),
			[ Settings_Field::class, 'render_select' ],
			'general',
			'epi_block_icon',
			[
				'default' => 'phosphor',
				'option' => 'epi_block_icon_set',
				'options' => [
					'phosphor' => __( 'Phosphor', 'epi-block-icon' ),
				],
			],
		);
		\register_setting( 'general', 'epi_block_icon_set' );
		\add_settings_field(
			'epi_block_icon_variant',
			\__( 'Icon variant', 'epi-block-icon' ),
			[ Settings_Field::class, 'render_select' ],
			'general',
			'epi_block_icon',
			[
				'default' => 'regular',
				'option' => 'epi_block_icon_variant',
				'options' => [
					'bold' => __( 'Bold', 'epi-block-icon' ),
					'duotone' => __( 'Duotone', 'epi-block-icon' ),
					'fill' => __( 'Fill', 'epi-block-icon' ),
					'light' => __( 'Light', 'epi-block-icon' ),
					'regular' => __( 'Regular', 'epi-block-icon' ),
					'thin' => __( 'Thin', 'epi-block-icon' ),
				],
			],
		);
		\register_setting( 'general', 'epi_block_icon_variant' );
	}
}
