<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package uvo
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>


	<?php if ( '' !== get_the_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<?php the_post_thumbnail( 'full' ); ?>
		</div> <!-- .post-thumbnail -->
	<?php endif; ?>
	<?php if ( is_single() ) : ?>
	<div class="entry-extra-item">
			<div class="author-box">
			<div class="author-pic radiantthemes-retina"><?php echo get_avatar( get_the_author_meta( 'email' ), '34' ); ?></div>
			<span class="author"><i class="fa fa-user"></i><?php the_author(); ?></span>
			</div>




			<span class="date">
			<i class="lnr lnr-calendar-full" aria-hidden="true"></i>
			<?php echo get_the_time( 'F d, Y' ); ?>
			</span>



			<span class="category">
			<i class="lnr lnr-chart-bars" aria-hidden="true"></i>
			<?php
			$category_detail = get_the_category( get_the_id() );
			$result          = '';
			foreach ( $category_detail as $item ) :
			$category_link = get_category_link( $item->cat_ID );
			$result       .= '<a href = "' . esc_url( $category_link ) . '">' . $item->name . '</a>';
			endforeach;
			echo wp_kses( $result, 'post' );
			?>
			</span>



			<span class="comment">
				<i class="lnr lnr-bubble" aria-hidden="true"></i>
				<?php
				if ( 0 === get_comments_number() || '0' === get_comments_number() ) {
					echo esc_html__( '0 Comment', 'uvo' );
				} elseif ( 1 === get_comments_number() || '1' === get_comments_number() ) {
					echo esc_html__( '1 Comment', 'uvo' );
				} else {
					echo get_comments_number() . ' ' . esc_html__( 'Comments', 'uvo' );
				}
				?>
			</span>
			</div>
	<?php endif; ?>
	<?php if ( ! is_single() ) : ?>
				<header class="entry-header">
					<?php
					the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
					?>
				</header><!-- .entry-header -->
	<?php endif; ?>
	<div class="entry-main">
		<div class="entry-content default-page">
			<?php
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. */
						__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'uvo' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				)
			);
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'uvo' ),
					'after'  => '</div>',
				)
			);
			?>
			<div class="clearfix"></div>
		</div><!-- .entry-content -->
	</div><!-- .entry-main -->
</article><!-- #post-## -->
