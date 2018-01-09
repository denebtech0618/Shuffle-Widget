<?php

/**
 * Easy_Shuffle_Widget_Utils Class
 *
 * All methods are static, this is basically a namespacing class wrapper.
 *
 * @package Easy_Shuffle_Widget
 * @subpackage Easy_Shuffle_Widget_Utils
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
 * Easy_Shuffle_Widget_Utils Class
 *
 * Group of utility methods for use by Easy_Shuffle_Widget
 *
 * @since 1.0.0
 */
class Easy_Shuffle_Widget_Utils
{

	/**
	 * Plugin root file
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	public static $base_file = EASY_SHUFFLE_WIDGET_FILE;


	private function __construct(){}


	/**
	 * Generates path to plugin root
	 *
	 * @uses WordPress plugin_dir_path()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @return string $path Path to plugin root.
	 */
	public static function get_plugin_path()
	{
		$path = plugin_dir_path( self::$base_file );
		return $path;
	}


	/**
	 * Generates path to subdirectory of plugin root
	 *
	 * @see Easy_Shuffle_Widget_Utils::get_plugin_path()
	 *
	 * @uses WordPress trailingslashit()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param string $directory The name of the requested subdirectory.
	 *
	 * @return string $sub_path Path to requested sub directory.
	 */
	public static function get_plugin_sub_path( $directory )
	{
		if( ! $directory ){
			return false;
		}

		$path = self::get_plugin_path();

		$sub_path = $path . trailingslashit( $directory );

		return $sub_path;
	}


	/**
	 * Generates url to plugin root
	 *
	 * @uses WordPress plugin_dir_url()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @return string $url URL of plugin root.
	 */
	public static function get_plugin_url()
	{
		$url = plugin_dir_url( self::$base_file );
		return $url;
	}


	/**
	 * Generates url to subdirectory of plugin root
	 *
	 * @see Easy_Shuffle_Widget_Utils::get_plugin_url()
	 *
	 * @uses WordPress trailingslashit()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param string $directory The name of the requested subdirectory.
	 *
	 * @return string $sub_url URL of requested sub directory.
	 */
	public static function get_plugin_sub_url( $directory )
	{
		if( ! $directory ){
			return false;
		}

		$url = self::get_plugin_url();

		$sub_url = $url . trailingslashit( $directory );

		return $sub_url;
	}


	/**
	 * Generates basename to plugin root
	 *
	 * @uses WordPress plugin_basename()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @return string $basename Filename of plugin root.
	 */
	public static function get_plugin_basename()
	{
		$basename = plugin_basename( self::$base_file );
		return $basename;
	}


	/**
	 * Sets default parameters
	 *
	 * Use 'eshuflw_instance_defaults' filter to modify accepted defaults.
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @return array $defaults The default values for the widget.
	 */
	public static function instance_defaults()
	{
		$_defaults = array(
			'title'          => __( 'Shuffle', 'easy-shuffle-widget' ),
			'item_type'      => 'any',
			'show_thumb'     => 1,
			'thumb_size_w'   => 55,
			'thumb_size_h'   => 55,
			'excerpt_length' => 15,
			'css_default'    => 0,
		);

		$defaults = apply_filters( 'eshuflw_instance_defaults', $_defaults );

		return $defaults;
	}



	/**
	 * Builds a sample excerpt
	 *
	 * Use 'eshuflw_sample_excerpt' filter to modify excerpt text.
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @return string $excerpt Excerpt text.
	 */
	public static function sample_excerpt()
	{
		$excerpt = __( 'The point of the foundation is to ensure free access, in perpetuity, to the software projects we support. People and businesses may come and go, so it is important to ensure that the source code for these projects will survive beyond the current contributor base, that we may create a stable platform for web publishing for generations to come. As part of this mission, the Foundation will be responsible for protecting the WordPress, WordCamp, and related trademarks. A 501(c)3 non-profit organization, the WordPress Foundation will also pursue a charter to educate the public about WordPress and related open source software.', 'easy-shuffle-widget' );

		return apply_filters( 'eshuflw_sample_excerpt', $excerpt );
	}


	/**
	 * Retrieves post types to use in widget
	 *
	 * Use 'eshuflw_get_post_type_args' to filter arguments for retrieving post types.
	 * Use 'eshuflw_allowed_post_types' to filter post types that can be selected in the widget.
	 *
	 * @uses WordPress get_post_types()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @return array $types Allowed post types.
	 */
	public static function get_allowed_post_types()
	{
		$args = apply_filters( 'eshuflw_get_post_type_args', array( 'public' => true) );
		$post_types = get_post_types( $args, 'objects' );

		if( empty( $post_types ) ){
			return false;
		}

		foreach( $post_types as $post_type ){
			$query_var = ( ! $post_type->query_var ) ? $post_type->name : $post_type->query_var ;
			$_ptypes[ $query_var ] = $post_type->labels->singular_name;
		}

		$types = apply_filters( 'eshuflw_allowed_post_types', $_ptypes );

		return $types;
	}


	/**
	 * Retrieves item types to use in widget
	 *
	 * Use 'eshuflw_allowed_item_types' to filter item types that can be selected in the widget.
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @return array $types Allowed post types.
	 */
	public static function get_allowed_item_types()
	{
		$post_types = self::get_allowed_post_types();

		$_types = array();
		$_types['any']     = __( 'Any', 'easy-shuffle-widget' );
		$_types['comment'] = __( 'Comment', 'easy-shuffle-widget' );
		$_types['user']    = __( 'User Bio', 'easy-shuffle-widget' );
		#$_types['image']   = __( 'Image', 'easy-shuffle-widget' );

		$types = array_merge( $_types, (array) $post_types );
		$types = array_unique( $types );

		// unset the attachment type; we're only allowing images as of 1.0.0
		// update 05/10/16: We're not allowing attachments as of 1.0.0 ~db
		if( ! empty( $types['attachment'] ) ) {
			unset( $types['attachment'] );
		}

		// unset the page type; plugins can always add it back in.
		if( ! empty( $types['page'] ) ) {
			unset( $types['page'] );
		}

		$types = apply_filters( 'eshuflw_allowed_item_types', $types );

		return $types;
	}



	/**
	 * Selects a random item type from the allowed items types
	 *
	 * @see Easy_Shuffle_Widget::widget()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param array  $instance Current widget settings.
	 * @param object $widget   Widget Object.
	 *
	 * @return string $type Slug of the item type to retrieve, e.g., 'comment', 'post', 'user'.
	 */
	public static function get_random_item_type( $instance, $widget )
	{
		$_types = Easy_Shuffle_Widget_Utils::get_allowed_item_types();
		$_types = Easy_Shuffle_Widget_Utils::sanitize_select_array( $_types );

		if( ! empty( $_types['any'] ) ){
			unset( $_types['any'] );
		}

		$_types = array_keys( $_types );

		shuffle( $_types ); // (where the name of the widget comes from!)

		$_type = $_types[0];

		$type = apply_filters( 'eshuflw_random_item_type', $_type );

		return $type;
	}



	/**
	 * Retrieves a random item object for the current widget instance
	 *
	 * @see Easy_Shuffle_Widget::widget()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param string $item_type Slug of the item type to retrieve; e.g., 'comment', 'post', 'user'.
	 * @param array  $instance  Current widget settings.
	 * @param object $widget    Widget Object.
	 *
	 * @return object $item The retrieved object; e.g., comment, user, post[type].
	 */
	public static function get_random_item_obj( $item_type, $instance, $widget )
	{
		$exclude_id = 0;
		$ls_item_type = '';

		$last_shown = self::get_last_shown_item( $instance, $widget );

		// if $last_shown is empty, it means nothing has been shown previously
		if( ! empty( $last_shown ) ) {
			$exclude_id = absint( $last_shown['item_id'] );
			$ls_item_type = $last_shown['item_type'];
		}

		switch( $item_type ){
			case 'comment' :
				$exclude_id = ( 'comment' !== $ls_item_type ) ? 0 : $exclude_id ;
				$item = self::get_random_comment( $exclude_id, $instance, $widget );
				break;
			case 'user' :
				$exclude_id = ( 'user' !== $ls_item_type ) ? 0 : $exclude_id ;
				$item = self::get_random_user( $exclude_id, $instance, $widget );
				break;
			default :
				$item = self::get_random_post( $item_type, $exclude_id, $instance, $widget );
				break;
		}

		if( is_null( $item ) || ! $item || empty( $item ) ) {
			$item = array();
		}

		return $item;
	}

	/**
	 * Retrieves a random comment for the current widget instance
	 *
	 * @see Easy_Shuffle_Widget_Utils::get_random_item_obj()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param int    $exclude_id The id of the previously shown object.
	 * @param array  $instance   Current widget settings.
	 * @param object $widget     Widget Object.
	 *
	 * @return object $item The retrieved object; e.g., comment, user, post[type].
	 */
	public static function get_random_comment( $exclude_id, $instance, $widget )
	{
		$args = array(
			'number' => '',
			'status' => 'approve',
			'type'   => 'comment',
			'fields' => 'ids',
		);

		$args = apply_filters( 'eshuflw_get_random_comment_args', $args );

		$ids = get_comments( $args );

		if( is_array( $ids ) ) {
			if( ( $key = array_search( $exclude_id, $ids ) ) !== false ) {
				unset( $ids[$key] );
			}
			$ids = array_values( $ids );
			$id = (int) $ids[mt_rand(0, count($ids) - 1)];
		}

		$item = get_comment( $id );

		return $item;
	}


	/**
	 * Retrieves a random user for the current widget instance
	 *
	 * @see Easy_Shuffle_Widget_Utils::get_random_item_obj()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param int    $exclude_id The id of the previously shown object.
	 * @param array  $instance   Current widget settings.
	 * @param object $widget     Widget Object.
	 *
	 * @return object $item The retrieved object; e.g., comment, user, post[type].
	 */
	public static function get_random_user( $exclude_id, $instance, $widget )
	{
		$args = array(
			'number'  => '',
			'role'    => '',
			'fields'  => 'ids',
			'orderby' => 'ID',
		);

		$args = apply_filters( 'eshuflw_get_random_user_args', $args );

		$ids = get_users( $args );

		if( is_array( $ids ) ) {
			if( ( $key = array_search( $exclude_id, $ids ) ) !== false ) {
				unset( $ids[$key] );
			}
			$ids = array_values( $ids );
			$id = (int) $ids[mt_rand(0, count($ids) - 1)];
		}

		$item = get_user_by( 'id', $id );

		return $item;
	}


	/**
	 * Retrieves a random post for the current widget instance
	 *
	 * @see Easy_Shuffle_Widget_Utils::get_random_item_obj()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param string $post_type  The post type.
	 * @param int    $exclude_id The id of the previously shown object.
	 * @param array  $instance   Current widget settings.
	 * @param object $widget     Widget Object.
	 *
	 * @return object $item The retrieved object; e.g., comment, user, post[type].
	 */
	public static function get_random_post( $post_type, $exclude_id, $instance, $widget )
	{

		if( ! post_type_exists( $post_type ) ) {
			return array();
		}

		$ids = array();

		$args = array(
			'posts_per_page' => -1,
			'status'         => 'publish',
			'post_type'      => $post_type,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'cache_results'  => false,
		);

		$args = apply_filters( 'eshuflw_get_random_post_args', $args, $post_type, $exclude_id, $instance, $widget );

		$q = new WP_Query( $args );

		if( isset( $q->posts ) && ! empty( $q->posts ) ) {
			$ids = $q->posts;
		}

		if( ! empty( $ids ) ) {
			if( ( $key = array_search( $exclude_id, $ids ) ) !== false ) {
				unset( $ids[$key] );
			}
			$ids = array_values( $ids );
			$id = (int) $ids[mt_rand(0, count($ids) - 1)];
		}

		$item = get_post( $id );

		return $item;
	}


	/**
	 * Retrieves the ID of item object for the current widget instance
	 *
	 * @see Easy_Shuffle_Widget::widget()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param object $item_obj  WP Object: comment, post, user.
	 * @param string $item_type Slug of the item type to retrieve; e.g., 'comment', 'post', 'user'.
	 *
	 * @return int $item_id The ID of the object.
	 */
	public static function get_item_obj_id( $item_obj, $item_type )
	{
		$item_id = 0;

		switch( $item_type ){
			case 'comment' :
				$item_id = $item_obj->comment_ID;
				break;
			case 'user' :
				$item_id = $item_obj->ID;
				break;
			default :
				$item_id = $item_obj->ID;
				break;
		}

		return absint( $item_id );
	}



	/**
	 * Retrieves the last shown item for the current widget instance
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param array  $instance Current widget settings.
	 * @param object $widget   Widget Object.
	 *
	 * @return array $shown_item The last shown item or an empty array.
	 */
	public static function get_last_shown_item( $instance, $widget )
	{
		$shown_items = get_option( 'eshuflw_shown_items' );

		if( ! $shown_items ) {
			return array();
		}

		$widget_id = $instance['widget_id'];

		if ( empty( $shown_items[ $widget_id ] ) ) {
			return array();
		}

		$shown_item = $shown_items[ $widget_id ];

		return $shown_item;
	}


	/**
	 * Adds a widget to the eshuflw_shown_items option
	 *
	 * Stores a reference to the widget instance and the object displayed on the front end.  This
	 * prevents the same item from showing up consecutively.
	 *
	 * Sample item: array(
	 *                  'item_instance' => 'easy-shuffle-widget-6',
	 *                  'item_type' => 'comment',
	 *                  'item_id'  => 99
	 *              )
	 *
	 * @uses WordPress get_option()
	 * @uses WordPress update_option()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param string $widget_id Widget instance ID.
	 * @param array  $shown_item The item object displayed.
	 */
	public static function stick_item( $widget_id, $shown_item = array() )
	{
		$widgets = get_option( 'eshuflw_shown_items' );

		if ( ! is_array( $widgets ) ) {
			$widgets = array();
		}

		$widgets[ $widget_id ] = $shown_item ;

		update_option('eshuflw_shown_items', $widgets);
	}


	/**
	 * Remove a widget added to the eshuflw_shown_items option
	 *
	 * Removes the reference to the widget instance and the object displayed on the front end.
	 * This prevents the same item from showing up consecutively.
	 *
	 * @uses WordPress get_option()
	 * @uses WordPress update_option()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param string $widget_id Widget instance ID.
	 * @param array $shown_item The item object displayed.
	 */
	public static function unstick_item( $widget_id )
	{
		$widgets = get_option( 'eshuflw_shown_items' );

		if ( ! is_array( $widgets ) ) {
			return;
		}

		if ( empty( $widgets[ $widget_id ] ) ) {
			return;
		}

		unset( $widgets[ $widget_id ] );

		update_option( 'eshuflw_shown_items', $widgets );
	}


	/**
	 * Adds a widget to the eshuflw_use_css option
	 *
	 * If css_default option is selected in the widget, this will add a reference to that
	 * widget instance in the eshuflw_use_css option.  The 'eshuflw_use_css' option determines if the
	 * default stylesheet is enqueued on the front end.
	 *
	 * @uses WordPress get_option()
	 * @uses WordPress update_option()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param string $widget_id Widget instance ID.
	 */
	public static function stick_css( $widget_id )
	{
		$widgets = get_option( 'eshuflw_use_css' );

		if ( ! is_array( $widgets ) ) {
			$widgets = array( $widget_id );
		}

		if ( ! in_array( $widget_id, $widgets ) ) {
			$widgets[] = $widget_id;
		}

		update_option('eshuflw_use_css', $widgets);
	}


	/**
	 * Removes a widget from the eshuflw_use_css option
	 *
	 * If css_default option is unselected in the widget, this will remove (if applicable) a
	 * reference to that widget instance in the eshuflw_use_css option. The 'eshuflw_use_css' option
	 * determines if the default stylesheet is enqueued on the front end.
	 *
	 * @uses WordPress get_option()
	 * @uses WordPress update_option()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param string $widget_id Widget instance ID.
	 */
	public static function unstick_css( $widget_id )
	{
		$widgets = get_option( 'eshuflw_use_css' );

		if ( ! is_array( $widgets ) ) {
			return;
		}

		if ( ! in_array( $widget_id, $widgets ) ) {
			return;
		}

		$offset = array_search($widget_id, $widgets);

		if ( false === $offset ) {
			return;
		}

		array_splice( $widgets, $offset, 1 );

		update_option( 'eshuflw_use_css', $widgets );
	}


	/**
	 * Prints link to default widget stylesheet
	 *
	 * Actual stylesheet is enqueued if the user selects to use default styles.
	 *
	 * @see Easy_Shuffle_Widget::widget()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param array  $instance Current widget settings.
	 * @param object $widget   Widget Object.
	 * @param bool   $echo     Flag to echo|return output.
	 *
	 * @return string $css_url Stylesheet link.
	 */
	public static function css_preview( $instance, $widget, $echo = true )
	{
		$_css_url =  self::get_plugin_sub_url( 'css' ) . 'front.css' ;

		$css_url = sprintf('<link rel="stylesheet" href="%s" type="text/css" media="all" />',
			esc_url( $_css_url )
		);

		if( $echo ) {
			echo $css_url;
		} else {
			return $css_url;
		}
	}


	/**
	 * Generates unique list-item id based on widget instance
	 *
	 * Use 'eshuflw_item_id' filter to modify item ID before output.
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param array  $instance  Widget instance.
	 * @param string $item_type Slug of the item type to retrieve; e.g., 'comment', 'post', 'user'.
	 * @param object $item_obj  WP Object: comment, post, user.
	 *
	 * @return string $item_id Filtered item ID.
	 */
	public static function get_item_id( $instance = array(), $item_type = 'post', $item_obj = '' )
	{
		if( empty( $item_obj ) ){
			return '';
		}

	    // get the WP object ID
		$item_obj_id = self::get_item_obj_id( $item_obj, $item_type );

		// widgetize the ID
		$_item_id = $instance['widget_id'] . '-' . $item_type . '-' . $item_obj_id;

		// filter the ID
		$item_id = apply_filters( 'eshuflw_item_id', $_item_id, $instance, $item_type, $item_obj );

		// clean the ID
		$item_id = sanitize_html_class( $_item_id );

		return $item_id;
	}


	/**
	 * Generate list-item classes
	 *
	 * Use 'eshuflw_item_class' filter to modify item classes before output.
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param array  $instance  Widget instance.
	 * @param string $item_type Slug of the item type to retrieve; e.g., 'comment', 'post', 'user'.
	 * @param object $item_obj  WP Object: comment, post, user.
	 *
	 * @return string $class_str CSS classes.
	 */
	public static function get_item_class( $instance = array(), $item_type = 'post', $item_obj = '' )
	{
		if( empty( $item_obj ) ){
			return '';
		}

		$_classes = array();
		$_classes[] = 'widgin-item';
		$_classes[] = 'eshuflw-item';
		$_classes[] = $item_type;
		$_classes[] = 'eshuflw-item-' . $item_type;

		$classes = apply_filters( 'eshuflw_item_class', $_classes, $instance, $item_type, $item_obj );
		$classes = ( ! is_array( $classes ) ) ? (array) $classes : $classes ;
		$classes = array_map( 'sanitize_html_class', $classes );

		$class_str = implode( ' ', $classes );

		return $class_str;
	}


	/**
	 * Retrieves list-item excerpt
	 *
	 * Use 'eshuflw_item_excerpt' to modify the text before output.
	 * Use 'eshuflw_excerpt_length' to modify the text length before output.
	 * Use 'eshuflw_excerpt_more' to modify the readmore text before output.
	 *
	 * @uses WordPress strip_shortcodes()
	 * @uses WordPress wp_html_excerpt()
	 * @uses WordPress wp_trim_words()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param array  $instance  Settings for the current Categories widget instance.
	 * @param string $item_type Slug of the item type to retrieve; e.g., 'comment', 'post', 'user'.
	 * @param object $item_obj  WP Object: comment, post, user.
	 * @param string $trim      Flag to trim by word or character.
	 *
	 * @return string $text Filtered description.
	 */
	public static function get_item_excerpt( $instance = array(), $item_type = 'post', $item_obj = '', $trim = 'words' )
	{
		if( empty( $item_obj ) ){
			return '';
		}

		$_text = self::get_item_obj_content( $item_type, $item_obj );

		$text = apply_filters( 'eshuflw_item_excerpt', $_text, $item_type, $item_obj, $instance );

		if( '' === $_text ) {
			return '';
		}

		// let's clean it up
		$_text = strip_shortcodes( $_text );
		$_text = str_replace(']]>', ']]&gt;', $_text);

		$_length = ( ! empty( $instance['excerpt_length'] ) ) ? absint( $instance['excerpt_length'] ) : 55 ;
		$length = apply_filters( 'eshuflw_excerpt_length', $_length );

		$_more = ( ! empty( $instance['excerpt_more'] ) ) ? $instance['excerpt_more'] : '&hellip;' ;
		$more = apply_filters( 'eshuflw_excerpt_more', $_more );

		if( 'chars' === $trim ){
			$text = wp_html_excerpt( $text, $length, $more );
		} else {
			$text = wp_trim_words( $text, $length, $more );
		}

		return $text;
	}


	/**
	 * Retrieves the content of item object for the current widget instance
	 *
	 * Comments will pull comment_content, users will pull user description, post{type]s will pull
	 * post_excerpt|post_content.
	 *
	 * Use 'eshuflw_get_item_obj_content' to modify content.
	 *
	 * @see Easy_Shuffle_Widget_Utils::get_item_excerpt()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param string $item_type Slug of the item type to retrieve; e.g., 'comment', 'post', 'user'.
	 * @param object $item_obj  WP Object: comment, post, user.
	 *
	 * @return in $item_id The ID of the object.
	 */
	public static function get_item_obj_content( $item_type = 'post', $item_obj = '' )
	{
		if( empty( $item_obj ) ){
			return '';
		}

		switch( $item_type ){
			case 'comment' :
				$_text = $item_obj->comment_content;
				break;
			case 'user' :
				$_text = get_the_author_meta( 'description', $item_obj->ID );
				break;
			default :
				$_text = ( isset( $item_obj->post_excerpt ) && ! empty( $item_obj->post_excerpt ) )
					? $item_obj->post_excerpt
					: $item_obj->post_content ;
				break;
		}

		$text = apply_filters( 'eshuflw_get_item_obj_content', $_text, $item_type, $item_obj );

		return $text;
	}




	/**
	 * Generate list-item thumbnail/avatar
	 *
	 * Use 'eshuflw_item_class' filter to modify item classes before output.
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param array  $instance  Widget instance.
	 * @param string $item_type Slug of the item type to retrieve; e.g., 'comment', 'post', 'user'.
	 * @param object $item_obj  WP Object: comment, post, user.
	 *
	 * @return string $src `<img>` tag of either: post thumb, author avatar, commenter avatar.
	 */
	public static function get_item_image( $instance = array(), $item_type = 'post', $item_obj = '' )
	{
		if( empty( $item_obj ) ){
			return '';
		}

		$src = self::get_item_obj_image( $item_type, $item_obj, $instance );

		return $src;
	}


	/**
	 * Builds src for thumbnail
	 *
	 * Use 'eshuflw_item_thumb_class' to modify image classes.
	 * Use 'eshuflw_item_thumb_html' to modify thumbnail output.
	 *
	 * @see Advanced_Categories_Widget_Utils::get_image_size()
	 *
	 * @uses WordPres wp_get_attachment_image()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param string $item_type Slug of the item type to retrieve; e.g., 'comment', 'post', 'user'.
	 * @param object $item_obj  WP Object: comment, post, user.
	 * @param array  $instance  Settings for the current Categories widget instance.
	 *
	 * @return string $thumb `<img>` tag for the avatar|thumbnail. False on failure.
	 */
	public static function get_item_obj_image( $item_type = 'post', $item_obj = '', $instance = array() )
	{
		if( empty( $item_obj ) ){
			return '';
		}

		$_thumb = '';

		$_classes = array();
		$_classes[] = 'eshuflw-thumbnail';

		$_w = absint( $instance['thumb_size_w'] );
		$_h = absint( $instance['thumb_size_h'] );
		$_size = "eshuflw-thumbnail-{$_w}-{$_h}";

		$_classes[] = "size-{$_size}";

		$_get_size = array( $_w, $_h);

		$classes = apply_filters( 'eshuflw_item_thumb_class', $_classes, $instance, $item_type, $item_obj );
		$classes = ( ! is_array( $classes ) ) ? (array) $classes : $classes ;
		$classes = array_map( 'sanitize_html_class', $classes );

		$class_str = implode( ' ', $classes );

		// comments and users get avatars
		if( 'comment' === $item_type || 'user' === $item_type ) {
			$_thumb = get_avatar( $item_obj, $size = $_w, $default = '', $alt = '', $args = array( 'class' => $classes ) );
		} else {
			$_thumbnail_id = get_post_thumbnail_id( $item_obj );

			if( $_thumbnail_id ) {
				$_thumb = get_the_post_thumbnail(
					$item_obj,
					$_get_size,
					array(
						'class' => $class_str,
						'alt' => the_title_attribute( 'echo=0' )
						)
					);
			} else {
				$_thumb = '';
			}
		}

		$thumb = apply_filters( 'eshuflw_item_thumb_html', $_thumb, $instance, $item_type, $item_obj );

		return $thumb;
	}



	/**
	 * Retrieves specific image size
	 *
	 * @see Advanced_Categories_Widget_Utils::get_allowed_image_sizes()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @return string Name of image size.
	 *         array  Image size settings; name, width, height, crop.
	 *		   bool   False if size doesn't exist.
	 */
	public static function get_image_size( $size = 'thumbnail', $fields = 'all' )
	{
		$sizes = self::get_allowed_image_sizes( $_fields = 'all' );

		if( count( $sizes ) && isset( $sizes[$size] ) ) :
			if( 'all' === $fields ) {
				return $sizes[$size];
			} else {
				return $sizes[$size]['name'];
			}
		endif;

		return false;
	}


	/**
	 * Retrieves registered image sizes
	 *
	 * Use 'eshuflw_allowed_image_sizes' to filter image sizes that can be selected in the widget.
	 *
	 * @see Advanced_Categories_Widget_Utils::sanitize_select_array()
	 *
	 * @global $_wp_additional_image_sizes
	 *
	 * @uses WordPress get_intermediate_image_sizes()
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @return array $_sizes Filtered array of image sizes.
	 */
	public static function get_allowed_image_sizes( $fields = 'name' )
	{
		global $_wp_additional_image_sizes;
		$wp_defaults = array( 'thumbnail', 'medium', 'medium_large', 'large' );

		$_sizes = get_intermediate_image_sizes();

		if( count( $_sizes ) ) {
			sort( $_sizes );
			$_sizes = array_combine( $_sizes, $_sizes );
		}

		$_sizes = apply_filters( 'eshuflw_allowed_image_sizes', $_sizes );
		$sizes = self::sanitize_select_array( $_sizes );

		if( count( $sizes )&& 'all' === $fields ) {

			$image_sizes = array();
			asort( $sizes, SORT_NATURAL );

			foreach ( $sizes as $_size ) {
				if ( in_array( $_size, $wp_defaults ) ) {
					$image_sizes[$_size]['name']   = $_size;
					$image_sizes[$_size]['width']  = absint( get_option( "{$_size}_size_w" ) );
					$image_sizes[$_size]['height'] = absint(  get_option( "{$_size}_size_h" ) );
					$image_sizes[$_size]['crop']   = (bool) get_option( "{$_size}_crop" );
				} else if( isset( $_wp_additional_image_sizes[ $_size ] )  ) {
					$image_sizes[$_size]['name']   = $_size;
					$image_sizes[$_size]['width']  = absint( $_wp_additional_image_sizes[ $_size ]['width'] );
					$image_sizes[$_size]['height'] = absint( $_wp_additional_image_sizes[ $_size ]['height'] );
					$image_sizes[$_size]['crop']   = (bool) $_wp_additional_image_sizes[ $_size ]['crop'];
				}
			}

			$sizes = $image_sizes;

		};

		return $sizes;
	}


	/**
	 * Cleans array of keys/values used in select drop downs
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param array $options Values used for select options
	 * @param bool  $sort    Flag to sort the values alphabetically.
	 *
	 * @return array $options Sanitized values.
	 */
	public static function sanitize_select_array( $options, $sort = true )
	{
		$options = ( ! is_array( $options ) ) ? (array) $options : $options ;

		// Clean the values (since it can be filtered by other plugins)
		$options = array_map( 'esc_html', $options );

		// Flip to clean the keys (used as `<option>` values in `<select>` field on form)
		$options = array_flip( $options );
		$options = array_map( 'sanitize_key', $options );

		// Flip back
		$options = array_flip( $options );

		if( $sort ) {
			asort( $options );
		};

		return $options;
	}

}