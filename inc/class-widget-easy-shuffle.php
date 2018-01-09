<?php
/**
 * Widget_Easy_Shuffle Class
 *
 * Adds a widget to randomly display content.
 *
 * @package Easy_Shuffle_Widget
 * @subpackage Widget_Easy_Shuffle
 *
 * @since 1.0.0
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


/**
 * Core class used to implement a Posts widget.
 *
 * @since 1.0.0
 *
 * @see WP_Widget
 */
class Widget_Easy_Shuffle extends WP_Widget
{


	/**
	 * Sets up a new widget instance.
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$widget_options = array(
			'classname'                   => 'widget_easy_shuffle easy-shuffle-widget widgin-widget',
			'description'                 => __( 'Display random content.' ),
			'customize_selective_refresh' => true,
			);

		$control_options = array();

		parent::__construct(
			'easy-shuffle-widget', // $this->id_base
			__( 'Easy Shuffle' ),  // $this->name
			$widget_options,       // $this->widget_options
			$control_options       // $this->control_options
		);

		$this->alt_option_name = 'widget_easy_shuffle';
	}


	/**
	 * Outputs the content for the current widget instance.
	 *
	 * Use 'eshuflw_widget_title' to filter the widget title.
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance )
	{
		if ( ! isset( $args['widget_id'] ) ){
			$args['widget_id'] = $this->id;
		}

		$_defaults = Easy_Shuffle_Widget_Utils::instance_defaults();
		$instance = wp_parse_args( (array) $instance, $_defaults );

		// build out the instance for devs
		$instance['id_base']       = $this->id_base;
		$instance['widget_number'] = $this->number;
		$instance['widget_id']     = $this->id;

		// what are we displaying?
		$selected_type = ( ! empty( $instance['item_type'] ) ) ? $instance['item_type'] : 'any' ;

		// if any was selected in the widget, grab a random item type (comment, user, post type)
		if( 'any' === $selected_type) {
			$item_type = Easy_Shuffle_Widget_Utils::get_random_item_type( $instance, $this );
		} else {
			$item_type = $selected_type;
		}

		// grab a random object (comment, user, post)
		$item_obj = Easy_Shuffle_Widget_Utils::get_random_item_obj( $item_type, $instance, $this );

		if( ! empty( $item_obj ) ) {
			$item_id = Easy_Shuffle_Widget_Utils::get_item_obj_id( $item_obj, $item_type );

			Easy_Shuffle_Widget_Utils::stick_item(
				$instance['widget_id'],
				$item = array(
					'item_instance' => $instance['widget_id'],
					'item_type'     => $item_type,
					'item_id'       => $item_id,
				)
			);

		}

		// widget title
		$_title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$_title = apply_filters( 'eshuflw_widget_title', $_title, $instance, $this->id_base );

		echo $args['before_widget'];

		if( $_title ) {
			echo $args['before_title'] . $_title . $args['after_title'];
		};

		do_action( 'eshuflw_widget_title_after', $instance );

		/**
		 * Prints out the css url only if in Customizer
		 *
		 * Actual stylesheet is enqueued if the user selects to use default styles
		 *
		 * @since 1.0.0
		 */
		if( ! empty( $instance['css_default'] ) && is_customize_preview() ) {
			echo Easy_Shuffle_Widget_Utils::css_preview( $instance, $this );
		}
		?>

		<div class="easy-shuffle-widget easy-shuffle-wrap">

			<?php
			do_action( 'eshuflw_before', $instance );

			if( ! empty( $item_obj ) ) {
				Easy_Shuffle_Widget_Views::item( $item_obj, $item_type, $instance, $this );
			}

			do_action( 'eshuflw_after', $instance );
			?>

		</div><!-- /.easy-shuffle-wrap -->

		<?php Easy_Shuffle_Widget_Views::colophon(); ?>

		<?php echo $args['after_widget']; ?>


	<?php
	}


	/**
	 * Handles updating settings for the current widget instance.
	 *
	 * Use 'eshuflw_update_instance' to filter updating/sanitizing the widget instance.
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 *
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;

		// general
		$instance['title']     = sanitize_text_field( $new_instance['title'] );
		$instance['item_type'] = sanitize_text_field( $new_instance['item_type'] );

		// thumbnails
		$instance['show_thumb']   = isset( $new_instance['show_thumb'] ) ? 1 : 0 ;

		$_thumb_size_w            = absint( $new_instance['thumb_size_w'] );
		$instance['thumb_size_w'] = ( $_thumb_size_w < 1 ) ? 55 : $_thumb_size_w ;

		$_thumb_size_h            = absint( $new_instance['thumb_size_h'] );
		$instance['thumb_size_h'] = ( $_thumb_size_h < 1 ) ? $_thumb_size_w : $_thumb_size_h ;

		// excerpts
		$instance['excerpt_length'] = absint( $new_instance['excerpt_length'] );

		// styles & layout
		$instance['css_default'] = isset( $new_instance['css_default'] ) ? 1 : 0 ;

		// build out the instance for devs
		$instance['id_base']       = $this->id_base;
		$instance['widget_number'] = $this->number;
		$instance['widget_id']     = $this->id;

		$instance = apply_filters('eshuflw_update_instance', $instance, $new_instance, $old_instance, $this );

		do_action( 'eshuflw_update_widget', $this, $instance, $new_instance, $old_instance );

		return $instance;
	}


	/**
	 * Outputs the settings form for the Posts widget.
	 *
	 * Applies 'eshuflw_form_defaults' filter on form fields to allow extension by plugins.
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance )
	{
		$defaults = Easy_Shuffle_Widget_Utils::instance_defaults();

		$instance = wp_parse_args( (array) $instance, $defaults );

		include( Easy_Shuffle_Widget_Utils::get_plugin_sub_path('inc') . 'widget-form.php' );
	}

}