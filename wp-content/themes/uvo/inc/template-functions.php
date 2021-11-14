<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package uvo
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function radiantthemes_body_classes( $classes ) {
	$classes[] = 'radiantthemes';
	$classes[] = 'radiantthemes-' . get_template();

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( ! is_user_logged_in() && ! empty( radiantthemes_global_var( 'coming_soon_switch', '', false ) ) ) {
		$classes[] = 'rt-coming-soon';
		if ( ! empty( radiantthemes_global_var( 'coming_soon_custom_background_style', '', false ) ) ) {
			$classes[] = 'coming-soon-' . radiantthemes_global_var( 'coming_soon_custom_background_style', '', false );
		}
	} elseif ( ! is_user_logged_in() && ! empty( radiantthemes_global_var( 'maintenance_mode_switch', '', false ) ) ) {
		$classes[] = 'rt-maintenance-mode';
	} elseif ( ! is_user_logged_in() && ! empty( radiantthemes_global_var( 'coming_soon_switch', '', false ) ) && ! empty( radiantthemes_global_var( 'maintenance_mode_switch', '', false ) ) ) {
		$classes[] = 'rt-coming-soon';
	}

	if ( is_404() && ! empty( radiantthemes_global_var( 'error_custom_background_style', '', false ) ) ) {
		$classes[] = 'error-404-' . radiantthemes_global_var( 'error_custom_background_style', '', false );
	}

	if ( ! empty( radiantthemes_global_var( 'scrollbar_switch', '', false ) ) ) {
		$classes[] = 'infinity-scroll';
	}

	return $classes;
}
add_filter( 'body_class', 'radiantthemes_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function radiantthemes_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'radiantthemes_pingback_header' );

/**
 * Customize the title for the 404 page.
 *
 * @param string $title The original title.
 * @return string The title to use.
 */
function radiantthemes_change_404_title($title) {
    if (is_404()) {
        $title = esc_html__( 'Page Not Found', 'uvo' );
    }
    return $title;
}
add_filter( 'pre_get_document_title', 'radiantthemes_change_404_title', 50 );

/**
 * Adjust the quantity input values for Simple Products
 *
 * @param array $args Arguments.
 * @param array $product Product.
 *
 * @return array
 */
function radiantthemes_woocommerce_quantity_input_args( $args, $product ) {
	$args['input_value'] = empty( $args['input_value'] ) ? 1 : $args['input_value'];
	$args['max_value']   = 100; // Maximum value.
	return $args;
}
add_filter( 'woocommerce_quantity_input_args', 'radiantthemes_woocommerce_quantity_input_args', 10, 2 ); // Simple products.

/**
 * Adjust the quantity input values for Variable Products
 *
 * @param array $args Arguments.
 * @return array
 */
function radiantthemes_woocommerce_available_variation( $args ) {
	$args['max_qty'] = 100; // Maximum value (variations).
	return $args;
}
add_filter( 'woocommerce_available_variation', 'radiantthemes_woocommerce_available_variation' ); // Variations.

/**
 * Comments
 *
 * @param [type] $comment comment.
 * @param [type] $args arguments.
 * @param [type] $depth depth.
 * @return void
 */
function radiantthemes_comment( $comment, $args, $depth ) {
	if ( $args['style'] === 'div' ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li ';
		$add_below = 'div-comment';
	}
	?>

	<<?php echo esc_attr( $tag ); ?><?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
	<?php endif; ?>
	<footer class="comment-meta">
		<div class="comment-author vcard">
			<?php
			if ( $args['avatar_size'] != 0 ) {
				echo get_avatar( $comment, $args['avatar_size'] );
			}
			?>
			<?php printf( '<b class="fn">%s</b>', get_comment_author_link() ); ?>
		</div>
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'uvo' ); ?></em>
			<br/>
		<?php endif; ?>
		<div class="comment-meta comment-metadata">
			<a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>">
				<div class="comment-date-and-time">
						<?php
						/* translators: 1: date, 2: time */
						printf( esc_html__( '%1$s at %2$s', 'uvo' ), get_comment_date(), get_comment_time() );
						?>
						<?php
						edit_comment_link( esc_html__( 'Edit', 'uvo' ), '  ', '' );
						?>
				</div>
			</a>
			<div class="reply">
				<?php
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below'  => $add_below,
							'reply_text' => esc_html__( 'Reply', 'uvo' ),
							'depth'      => $depth,
							'max_depth'  => $args['max_depth'],
						)
					)
				);
				?>
			</div>
		</div>
	</footer>

	<div class="comment-content">
		<?php comment_text(); ?>
	</div>

	<?php if ( 'div' != $args['style'] ) : ?>
	</article>
		<?php
	endif;
}
