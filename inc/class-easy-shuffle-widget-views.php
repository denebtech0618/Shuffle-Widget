<?php

/**
 * Easy_Shuffle_Widget_Views Class
 *
 * Handles generation of all front-facing html.
 * All methods are static, this is basically a namespacing class wrapper.
 *
 * @package Easy_Shuffle_Widget
 * @subpackage Easy_Shuffle_Widget_Views
 *
 * @since 1.0.0
 */

class Easy_Shuffle_Widget_Views
{

	private function __construct(){}

	/**
	 * Builds each list item for the current widget instance.
	 *
	 * Use "eshuflw_{$item_type}_item_html" to filter to filter $html before output.
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param object $item_obj  WP Object: comment, post, user.
	 * @param string $item_type Slug of the item type to retrieve; e.g., 'comment', 'post', 'user'.
	 * @param array  $instance  Settings for the current widget instance.
	 * @param object $widget    WP Object: comment, post, user.
	 * @param bool   $echo      Flag to echo or return the method's output.
	 *
	 * @return string $html HTML markup for the list item.
	 */
	public static function item( $item_obj = '', $item_type = 'post', $instance = array(), $widget = '', $echo = true )
	{
		if ( empty( $item_obj ) ) {
			return '';
		}

		$item_id      = Easy_Shuffle_Widget_Utils::get_item_id( $instance, $item_type, $item_obj );
		$item_class   = Easy_Shuffle_Widget_Utils::get_item_class( $instance, $item_type, $item_obj );
		$item_excerpt = Easy_Shuffle_Widget_Utils::get_item_excerpt( $instance, $item_type, $item_obj );
		$item_thumb   = Easy_Shuffle_Widget_Utils::get_item_image( $instance, $item_type, $item_obj );

		$thumb_div = ( ! empty( $instance['show_thumb'] ) )
			? self::the_item_thumbnail_div( $item_thumb, $item_obj, $item_type, $instance, false )
			: '' ;
		$title_div = self::the_item_title_div( $item_obj, $item_type, $instance, false );

		ob_start();

		do_action( "eshuflw_{$item_type}_item_before", $item_obj, $item_type, $instance, $widget );
		?>

		<div id="div-<?php echo $item_type ;?>-<?php echo $item_id ;?>" class="<?php echo $item_class ;?>" data-item-type="<?php echo $item_type ;?>">

			<?php do_action( "eshuflw_{$item_type}_item_top", $item_obj, $item_type, $instance, $widget ); ?>

				<div class="eshuflw-item-header">
					<?php echo $thumb_div; ?>
					<?php echo $title_div; ?>
					<?php do_action( "eshuflw_{$item_type}_item_header", $item_obj, $item_type, $instance, $widget ); ?>
				</div>

				<?php if( ! empty( $item_excerpt ) ) : ?>
					<div class="eshuflw-item-summary">
						<?php echo wpautop( $item_excerpt ); ?>
					</div><!-- /.item-summary -->
				<?php endif; ?>

			<?php do_action( "eshuflw_{$item_type}_item_bottom", $item_obj, $item_type, $instance, $widget ); ?>

		</div> <!-- /#div-$item_type-## -->

		<?php
		do_action( "eshuflw_{$item_type}_item_after", $item_obj, $item_type, $instance, $widget );

		$_html = ob_get_clean();

		$html = apply_filters( "eshuflw_{$item_type}_item_html", $_html, $instance );

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}


	/**
	 * Builds html for thumbnail section
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param string $item_thumb `<img>` tag for item thumbnail.
	 * @param object $item_obj   WP Object: comment, post, user.
	 * @param string $item_type  Slug of the item type to retrieve; e.g., 'comment', 'post', 'user'.
	 * @param array  $instance   Settings for the current widget instance.
	 * @param bool   $echo       Flag to echo or return the method's output.
	 *
	 * @return string $html HTML markup for the item thumbnail section.
	 */
	public static function the_item_thumbnail_div( $item_thumb = '', $item_obj = '', $item_type = 'post', $instance = array(), $echo = true )
	{
		if ( empty( $item_obj ) ) {
			return '';
		}

		$_html = '';
		$link_str = $item_thumb;

		switch( $item_type ){
			case 'comment' :
				$url = get_comment_author_url( $item_obj );
				$rel = 'nofollow';
				break;
			case 'user' :
				$url = get_author_posts_url( $item_obj->ID );
				$rel = 'author archive';
				break;
			default :
				$url = get_permalink( $item_obj->ID );
				$rel = 'bookmark';
				break;
		}

		$_classes = array();
		$_classes[] = 'eshuflw-item-thumbnail';

		$classes = apply_filters( 'eshuflw_thumbnail_div_class', $_classes, $item_thumb, $item_obj, $item_type, $instance );
		$classes = ( ! is_array( $classes ) ) ? (array) $classes : $classes ;
		$classes = array_map( 'sanitize_html_class', $classes );

		$class_str = implode( ' ', $classes );

		if( '' !== $url ){
			$link_str = sprintf('<a href="%s" rel="%s">%s</a>',
				esc_url( $url ),
				esc_attr( $rel ),
				$item_thumb
			);
		}

		if( '' !== $item_thumb ) {
			$_html .= sprintf( '<span class="%1$s">%2$s</span>',
				$class_str,
				$link_str
			);
		};

		$html = apply_filters( 'eshuflw_item_thumbnail_div', $_html, $item_thumb, $item_obj, $item_type, $instance );

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}


	/**
	 * Builds html for title section
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param object $item_obj  WP Object: comment, post, user.
	 * @param string $item_type Slug of the item type to retrieve; e.g., 'comment', 'post', 'user'.
	 * @param array  $instance  Settings for the current widget instance.
	 * @param bool   $echo      Flag to echo or return the method's output.
	 *
	 * @return string $html HTML markup for the item title.
	 */
	public static function the_item_title_div( $item_obj = '', $item_type = 'post', $instance = array(), $echo = true )
	{
		if ( empty( $item_obj ) ) {
			return '';
		}

		switch( $item_type ){
			case 'comment' :
				$_title = sprintf(
					_x( '<span class="eshuflw-item-title eshuflw-comment-title">%1$s <span class="on">on</span> %2$s</span>', 'widgets' ),
					'<span class="comment-author eshuflw-comment-author">' . get_comment_author_link( $item_obj ) . '</span>',
					'<span class="comment-link eshuflw-comment-link"><a class="comment-link eshuflw-comment-link" href="' . esc_url( get_comment_link( $item_obj ) ) . '">' . get_the_title( $item_obj->comment_post_ID ) . '</a></span>'
					);
				break;
			case 'user' :
				$_title = sprintf( '<h3 class="eshuflw-item-title eshuflw-author-title"><a href="%1$s" rel="author archive" title="View all posts by %2$s">%2$s</a></h3>',
					esc_url( get_author_posts_url( $item_obj->ID ) ),
					sprintf( __( '%s', 'easy-shuffle-widget' ), $item_obj->display_name )
					);
				break;
			default :
				$_title = sprintf( '<h3 class="eshuflw-item-title eshuflw-entry-title"><a href="%s" rel="bookmark">%s</a></h3>',
					esc_url( get_permalink( $item_obj ) ),
					sprintf( __( '%s', 'easy-shuffle-widget' ), get_the_title( $item_obj ) )
					);
				break;
		}

		$html = apply_filters( 'eshuflw_item_thumbnail_div', $_title, $item_obj, $item_type, $instance );

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}



	/**
	 * Outputs plugin attribution
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @return string Plugin attribution.
	 */
	public static function colophon( $echo = true )
	{
		$attribution = '<!-- Easy Shuffle Widget generated by http://darrinb.com/plugins/easy-shuffle-widget -->';

		if ( $echo ) {
			echo $attribution;
		} else {
			return $attribution;
		}
	}

}