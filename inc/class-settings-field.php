<?php
namespace epiphyt\Block_Icon;

/**
 * Settings field functions.
 * 
 * @author	Epiphyt
 * @license	GPL2
 * @package	epiphyt\Block_Icon
 */
final class Settings_Field {
	/**
	 * Render a select field.
	 * 
	 * @param	array	$attributes Field attributes
	 */
	public static function render_select( array $attributes ): void {
		$option = (array) \get_option( $attributes['option'], [] );
		
		// get default
		if ( $option === [] && isset( $attributes['default'] ) ) {
			$option = (array) $attributes['default'];
		}
		?>
		<select id="<?php echo \esc_attr( $attributes['option'] ); ?>" name="<?php echo \esc_attr( $attributes['option'] ) . ( ! empty( $attributes['multiple'] ) ? '[]' : '' ); ?>"<?php echo ( ! empty( $attributes['multiple'] ) ? ' multiple' : '' ); ?>>
			<?php
			foreach ( $attributes['options'] as $key => $value ) {
				if ( \is_array( $value ) ) {
					?>
					<option value="<?php echo \esc_attr( $value['ID'] ); ?>"<?php \selected( \in_array( (string) $key, $option, true ) ); ?>><?php echo \esc_html( $value['title'] ); ?></option>
					<?php
					if ( ! empty( $value['children'] ) ) {
						self::render_select_option( $value['children'], $option );
					}
				}
				else {
					?>
					<option value="<?php echo \esc_attr( $key ); ?>"<?php \selected( \in_array( (string) $key, $option, true ) ); ?>><?php echo \esc_html( $value ); ?></option>
					<?php
				}
			}
			?>
		</select>
		<?php
		if ( ! empty( $attributes['description'] ) ) {
			echo '<p class="field-description">' . \esc_html( $attributes['description'] ) . '</p>';
		}
	}
	
	/**
	 * Render a select option.
	 * 
	 * @param	array	$children All child items
	 * @param	array	$option The current selected item
	 * @param	int		$depth The item's depth
	 */
	private static function render_select_option( array $children, array $option, int $depth = 1 ): void {
		foreach ( $children as $child ) {
			?>
			<option value="<?php echo \esc_attr( $child['ID'] ); ?>"<?php \selected( \in_array( (string) $child['ID'], $option, true ) ); ?>><?php echo \esc_html( \str_repeat( ' ', $depth * 4 ) . $child['title'] ); ?></option>
			<?php
			if ( ! empty( $child['children'] ) ) {
				$depth++;
				self::render_select_option( $child['children'], $option, $depth );
				$depth--;
			}
		}
	}
}
