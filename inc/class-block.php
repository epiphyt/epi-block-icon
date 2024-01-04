<?php
namespace epiphyt\Block_Icon;

/**
 * Block functionality.
 * 
 * @author	Epiphyt
 * @license	GPL2
 * @package	epiphyt\Block_Icon
 */
final class Block {
	/**
	 * Initialize functions.
	 */
	public static function init(): void {
		\add_action( 'enqueue_block_editor_assets', [ self::class, 'enqueue_block_assets' ] );
		\add_action( 'init', [ self::class, 'register' ] );
	}
	
	/**
	 * Enqueue block assets.
	 */
	public static function enqueue_block_assets(): void {
		\wp_set_script_translations( 'epi-icon-editor-script', 'epi-block-icon', \EPI_BLOCK_ICON_BASE . 'languages' );
		\wp_add_inline_script(
			'epi-icon-editor-script',
			'var epiBlockIcon = ' . \wp_json_encode( [
				'assetUrl' => \EPI_BLOCK_ICON_URL . '/assets/images/',
				'defaultVariant' => \esc_js( Plugin::get_icon_variant() ),
				'iconSet' => \esc_js( Plugin::get_icon_set() ),
			] ),
			'before'
		);
	}
	
	/**
	 * Register the block(s).
	 */
	public static function register(): void {
		\register_block_type( \EPI_BLOCK_ICON_BASE . '/build/blocks/icon-list' );
		\register_block_type(
			\EPI_BLOCK_ICON_BASE . '/build/blocks/icon',
			[
				'render_callback' => [ self::class, 'render_icon' ],
			]
		);
	}
	
	/**
	 * Render an icon block.
	 * 
	 * @param	array	$attributes Block attributes
	 * @return	string Rendered icon block
	 */
	public static function render_icon( array $attributes ): string {
		$attributes = \wp_parse_args(
			$attributes,
			[
				'icon' => '',
				'linkTarget' => '',
				'rel' => '',
				'size' => '64px',
				'title' => '',
				'url' => '',
			]
		);
		$aria_attributes = ( ! empty( $attributes['title'] ) ? ' aria-label="' . \esc_attr( $attributes['title'] ) . '"' : ' aria-hidden="true"' );
		$icon = '<svg aria-hidden="true"><use href="#' . \esc_attr( $attributes['icon'] ) . '"></use></svg>';
		$style = ' style="height: ' . $attributes['size'] . '; width: ' . $attributes['size'] . ';"';
		$markup = '<div %1$s%2$s>%3$s%4$s%5$s</div>';
		$maybe_link_end = (
			! empty( $attributes['url'] )
			? '</a>'
			: '</span>'
		);
		$maybe_link_start = (
			! empty( $attributes['url'] )
			? '<a class="link" href="' . \sanitize_url( $attributes['url'] ) . '"' . ( ! empty( $attributes['linkTarget'] ) ? ' target="' . \esc_attr( $attributes['linkTarget'] ) . '"' : '' ) . ( ! empty( $attributes['rel'] ) ? ' rel="' . \esc_attr( $attributes['rel'] ) . '"' : '' ) . $style . '>'
			: '<span class="no-link"' . $style . '>'
		);
		$wrapper_attributes = \str_replace(
			'wp-block-epi-icon',
			'wp-block-epi-icon is-icon-' . \sanitize_html_class( $attributes['icon'] ),
			\get_block_wrapper_attributes()
		);
		$markup = \sprintf(
			$markup,
			$wrapper_attributes,
			$aria_attributes,
			$maybe_link_start,
			$icon,
			$maybe_link_end
		);
		
		Plugin::get_instance()->register_icon( $attributes['icon'] );
		
		return $markup;
	}
}
