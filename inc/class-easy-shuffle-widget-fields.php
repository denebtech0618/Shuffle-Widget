<?php

/**
* Easy_Shuffle_Widget_Fields Class
*
* Handles generation of widget form fields.
* All methods are static, this is basically a namespacing class wrapper.
*
* @package Easy_Shuffle_Widget
* @subpackage Easy_Shuffle_Widget_Fields
*
* @since 1.0.0
*/

class Easy_Shuffle_Widget_Fields
{

	public function __construct(){}


	/**
	 * Loads fields for a specific fieldset for widget form
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param string $fieldset Name (slug) of fieldset.
	 * @param array  $fields   Fields to load.
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function load_fieldset( $fieldset = 'general', $fields, $instance, $widget )
	{
		if( ! is_array( $fields ) ) {
			return;
		}

		$keys        = array_keys( $fields );
		$first_field = reset( $keys );
		$last_field  = end( $keys );

		ob_start();

		foreach ( $fields as $name => $field ) {

			if ( $first_field === $name ) {
				do_action( "eshuflw_form_before_fields_{$fieldset}", $instance, $widget );
			}

			do_action( "eshuflw_form_before_field_{$name}", $instance, $widget );

			// output the actual field
			echo apply_filters( "eshuflw_form_field_{$name}", $field, $instance, $widget ) . "\n";

			do_action( "eshuflw_form_after_field_{$name}", $instance, $widget );

			if ( $last_field === $name ) {
				do_action( "eshuflw_form_after_fields_{$fieldset}", $instance, $widget );
			}

		}

		$fieldset = ob_get_clean();

		echo $fieldset;
	}


	/**
	 * Build section header for widget form
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param string $fieldset Slug of fieldset.
	 * @param array  $title    Name of fieldset.
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_section_header( $fieldset = 'general', $title = 'General Settings', $instance, $widget )
	{
		ob_start();
		?>

		<div class="widgin-section-top" data-fieldset="<?php echo $fieldset; ?>">
			<div class="widgin-top-action">
				<a class="widgin-action-indicator hide-if-no-js" data-fieldset="<?php echo $fieldset; ?>" href="#"></a>
			</div>
			<div class="widgin-section-title">
				<h4 class="widgin-section-heading" data-fieldset="<?php echo $fieldset; ?>">
					<?php printf( __( '%s', 'advanced-posts-widget' ), $title ); ?>
				</h4>
			</div>
		</div>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: title
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_title( $instance, $widget )
	{
		ob_start();
		?>

		<p>
			<label for="<?php echo $widget->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'easy-shuffle-widget' ); ?></label>
			<input class="widefat" id="<?php echo $widget->get_field_id( 'title' ); ?>" name="<?php echo $widget->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: item_type
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_item_type( $instance, $widget )
	{
		$_types = Easy_Shuffle_Widget_Utils::get_allowed_item_types();
		$types = Easy_Shuffle_Widget_Utils::sanitize_select_array( $_types );

		if( ! $types ){
			return '';
		}
		ob_start();
		?>

		<p>
			<label for="<?php echo $widget->get_field_id('item_type'); ?>">
				<?php _e( 'Item Type:', 'easy-shuffle-widget' ); ?>
			</label>
			<select name="<?php echo $widget->get_field_name('item_type'); ?>" id="<?php echo $widget->get_field_id('item_type'); ?>" class="widefat">
				<?php foreach( $types as $query_var => $label  ) { ?>
					<option value="<?php echo esc_attr( $query_var ); ?>" <?php selected( $instance['item_type'] , $query_var ); ?>><?php echo esc_html( $label ); ?></option>
				<?php } ?>
			</select>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: show_thumb
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_show_thumb( $instance, $widget )
	{
		ob_start();
		?>

		<p>
			<input class="checkbox" type="checkbox" id="<?php echo $widget->get_field_id( 'show_thumb' ); ?>" name="<?php echo $widget->get_field_name( 'show_thumb' ); ?>" <?php checked( $instance['show_thumb'], 1 ); ?>/>
			<label for="<?php echo $widget->get_field_id( 'show_thumb' ); ?>">
				<?php _e( 'Display Thumbnail/Avatar?', 'easy-shuffle-widget' ); ?>
			</label>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form fields: thumb_size_w / thumb_size_h
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_thumb_custom( $instance, $widget )
	{
		ob_start();
		?>
		<div class="widgin-thumbsize-wrap">

			<p>
				<label><?php _e( 'Set Custom Size:', 'advanced-posts-widget' ); ?></label><br />

				<label for="<?php echo $widget->get_field_id( 'thumb_size_w' ); ?>">
					<?php _e( 'Width:', 'advanced-posts-widget' ); ?>
				</label>
				<input class="small-text widgin-thumb-size widgin-thumb-width" id="<?php echo $widget->get_field_id( 'thumb_size_w' ); ?>" name="<?php echo $widget->get_field_name( 'thumb_size_w' ); ?>" type="number" value="<?php echo absint( $instance['thumb_size_w'] ); ?>" />

				<br />

				<label for="<?php echo $widget->get_field_id( 'thumb_size_h' ); ?>">
					<?php _e( 'Height:', 'advanced-posts-widget' ); ?>
				</label>
				<input class="small-text widgin-thumb-size widgin-thumb-height" id="<?php echo $widget->get_field_id( 'thumb_size_h' ); ?>" name="<?php echo $widget->get_field_name( 'thumb_size_h' ); ?>" type="number" value="<?php echo absint( $instance['thumb_size_h'] ); ?>" />
			</p>

			<p>
				<?php _e( 'Preview Custom Size:', 'easy-shuffle-widget' ); ?><br />
				<span class="widgin-preview-container">
					<span class="widgin-thumbnail-preview" style="font-size: <?php echo absint( $instance['thumb_size_h'] ); ?>px; height:<?php echo absint( $instance['thumb_size_h'] ); ?>px; width:<?php echo absint( $instance['thumb_size_w'] ); ?>px">
						<i class="widgin-preview-image dashicons dashicons-format-image"></i>
					</span>
				</span>
			</p>

		</div>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: excerpt_length
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_excerpt_length( $instance, $widget )
	{
		ob_start();
		?>

		<div class="widgin-excerptsize-wrap">

			<p>
				<label for="<?php echo $widget->get_field_id( 'excerpt_length' ); ?>">
					<?php _e( 'Excerpt Length:', 'easy-shuffle-widget' ); ?>
				</label>
				<input class="widefat widgin-excerpt-length" id="<?php echo $widget->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $widget->get_field_name( 'excerpt_length' ); ?>" type="number" step="1" min="0" value="<?php echo absint( $instance['excerpt_length'] ); ?>" />
			</p>

			<p>
				<?php _e( 'Preview Excerpt Size:', 'easy-shuffle-widget' ); ?><br />

				<span class="widgin-preview-container">
					<span class="widgin-excerpt-preview">
						<span class="widgin-excerpt"><?php echo wp_trim_words( Easy_Shuffle_Widget_Utils::sample_excerpt(), 15, '&hellip;' ); ?></span>
						<span class="widgin-excerpt-sample" aria-hidden="true" role="presentation"><?php echo Easy_Shuffle_Widget_Utils::sample_excerpt(); ?></span>
					</span>
				</span>
			</p>

		</div>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: css_default
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_css_default( $instance, $widget )
	{
		ob_start();
		?>

		<p>
			<input id="<?php echo $widget->get_field_id( 'css_default' ); ?>" name="<?php echo $widget->get_field_name( 'css_default' ); ?>" type="checkbox" <?php checked( $instance['css_default'], 1 ); ?> />
			<label for="<?php echo $widget->get_field_id( 'css_default' ); ?>">
				<?php _e( 'Use Default Styles?', 'easy-shuffle-widget' ); ?>
			</label>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}

}
