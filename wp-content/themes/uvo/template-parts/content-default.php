<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package uvo
 */

?>
<div class="row">
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'style-default' ); ?>>
		<?php if ( has_post_thumbnail() ) { ?>
			<div class="post-thumbnail ">
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'full' ); ?></a>
			</div><!-- .post-thumbnail -->
		<?php } ?>


			<div class="entry-extra-item">
			<div class="author-box">
			<div class="author-pic"><?php echo get_avatar( get_the_author_meta( 'email' ), '150' ); ?></div>
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




		<header class="entry-header">
			<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
		</header><!-- .entry-header -->
		<div class="entry-main">
			<div class="entry-content">
				<?php echo wp_kses_post( substr( wp_strip_all_tags( get_the_excerpt() ), 0, 300 ) . '...' ); ?>
				<div class="post-meta">
					<!-- .entry-content -->

				</div>
			</div><!-- .entry-main -->
		</div>
	</article><!-- #post-## -->
</div>
