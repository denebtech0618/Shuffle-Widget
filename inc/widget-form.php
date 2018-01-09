<?php
/**
 * Widget Form
 *
 * Builds out the html for the widget settings form.
 *
 * @uses Easy_Shuffle_Widget_Fields::build_field_{name-of-field}() to generate the individual form fields.
 * @uses Easy_Shuffle_Widget_Fields::load_fieldset() to output the actual fieldsets.
 *
 * @package Easy_Shuffle_Widget
 *
 * @since 1.0.0
 */
?>
<div class="easy-shuffle-widget-form">

	<div class="widgin-widget-form">

		<div class="widgin-section">

			<?php echo Easy_Shuffle_Widget_Fields::build_section_header( $fieldset = 'general', $title = 'General Settings', $instance, $this ); ?>

			<fieldset data-fieldset-id="general" class="widgin-settings widgin-fieldset settings-general">

				<legend class="screen-reader-text"><span><?php _e( 'General Settings', 'easy-shuffle-widget' ) ?></span></legend>

				<?php
				$_general_fields =  array(
					'title'        => Easy_Shuffle_Widget_Fields::build_field_title( $instance, $this ),
					'item_type'    => Easy_Shuffle_Widget_Fields::build_field_item_type( $instance, $this ),
				);
				$general_fields = apply_filters( 'eshuflw_form_fields_general', $_general_fields, $instance, $this );

				Easy_Shuffle_Widget_Fields::load_fieldset( 'general', $general_fields, $instance, $this );
				?>

			</fieldset>

		</div><!-- /.widgin-section -->

		<div class="widgin-section">

			<?php echo Easy_Shuffle_Widget_Fields::build_section_header( $fieldset = 'thumbnails', $title = 'Thumbnails/Avatars', $instance, $this ); ?>

			<fieldset data-fieldset-id="thumbnails" class="widgin-settings widgin-fieldset settings-thumbnails">

				<legend class="screen-reader-text"><span><?php _e( 'Thumbnail/Avatar', 'easy-shuffle-widget' ) ?></span></legend>

				<?php
				$_thumbnail_fields =  array(
					'show_thumb'   => Easy_Shuffle_Widget_Fields::build_field_show_thumb( $instance, $this ),
					'thumb_custom' => Easy_Shuffle_Widget_Fields::build_field_thumb_custom( $instance, $this ),
				);
				$thumbnail_fields = apply_filters( "eshuflw_form_fields_thumbnails", $_thumbnail_fields, $instance, $this );

				Easy_Shuffle_Widget_Fields::load_fieldset( 'thumbnails', $thumbnail_fields, $instance, $this );
				?>
			</fieldset>

		</div><!-- /.widgin-section -->

		<div class="widgin-section">

			<?php echo Easy_Shuffle_Widget_Fields::build_section_header( $fieldset = 'excerpts', $title = 'Excerpts', $instance, $this ); ?>

			<fieldset data-fieldset-id="excerpts" class="widgin-settings widgin-fieldset settings-excerpts">

				<legend class="screen-reader-text"><span><?php _e( 'Excerpts', 'easy-shuffle-widget' ) ?></span></legend>

				<?php
				$_excerpt_fields =  array(
					'excerpt_length' => Easy_Shuffle_Widget_Fields::build_field_excerpt_length( $instance, $this ),
				);
				$excerpt_fields = apply_filters( "eshuflw_form_fields_excerpts", $_excerpt_fields, $instance, $this );

				Easy_Shuffle_Widget_Fields::load_fieldset( 'excerpts', $excerpt_fields, $instance, $this );
				?>
			</fieldset>

		</div><!-- /.widgin-section -->

		<div class="widgin-section">

			<?php echo Easy_Shuffle_Widget_Fields::build_section_header( $fieldset = 'layout', $title = 'Styles & Layout', $instance, $this ); ?>

			<fieldset data-fieldset-id="layout" class="widgin-settings widgin-fieldset settings-layout">

				<legend class="screen-reader-text"><span><?php _e( 'Styles & Layout', 'easy-shuffle-widget' ) ?></span></legend>

				<?php
				$_intro = __( 'Selecting the Default Styles option below will give you a quick start to styling your banner.  Additionally, the widget has a number of classes available to further customize its appearance to match your theme.' );
				$intro = apply_filters( 'eshuflw_intro_text_layout', $_intro );
				?>

				<div class="description widgin-description">
					<?php echo wpautop( $intro ); ?>
				</div>

				<?php
				$_layout_fields =  array(
					'css_default' => Easy_Shuffle_Widget_Fields::build_field_css_default( $instance, $this ),
				);
				$layout_fields = apply_filters( 'eshuflw_form_fields_layout', $_layout_fields, $instance, $this );

				Easy_Shuffle_Widget_Fields::load_fieldset( 'layout', $layout_fields, $instance, $this );
				?>
			</fieldset>

		</div><!-- /.widgin-section -->

	</div>

</div>