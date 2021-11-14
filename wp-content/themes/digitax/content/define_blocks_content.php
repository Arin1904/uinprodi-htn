<?php

/* This is functions define blocks to display post */

if ( ! function_exists( 'digitax_content_thumbnail' ) ) {
	function digitax_content_thumbnail( $size ) {
		if ( has_post_thumbnail()  && ! post_password_required() || has_post_format( 'image') )  :
			the_post_thumbnail( $size, array('class'=> 'img-responsive' ));
	endif;
}
}

if ( ! function_exists( 'digitax_postformat_video' ) ) {
	function digitax_postformat_video( ) { ?>
		<?php if(has_post_format('video') && wp_oembed_get(get_post_meta(get_the_id(), "digitax_met_embed_media", true))){ ?>
			<div class="js-video postformat_video">
				<?php echo wp_oembed_get(get_post_meta(get_the_id(), "digitax_met_embed_media", true)); ?>
			</div>
		<?php } ?>
	<?php }
}

if ( ! function_exists( 'digitax_postformat_audio ') ) {
	function digitax_postformat_audio( ) { ?>
		<?php if(has_post_format('audio') && wp_oembed_get(get_post_meta(get_the_id(), "digitax_met_embed_media", true))){ ?>
			<div class="js-video postformat_audio">
				<?php echo wp_oembed_get(get_post_meta(get_the_id(), "digitax_met_embed_media", true)); ?>
			</div>
		<?php } ?>
	<?php }
}

if ( ! function_exists( 'digitax_content_title' ) ) {
	function digitax_content_title() { ?>

		<?php if ( is_single() ) : ?>
			<h1 class="post-title">
				<?php the_title(); ?>
			</h1>
			<?php else : ?>
				<h2 class="post-title">
					<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
						<?php the_title(); ?>
					</a>
				</h2>
			<?php endif; ?>

		<?php }
	}


	if ( ! function_exists( 'digitax_content_meta' ) ) {
		function digitax_content_meta( ) { ?>
			<span class="post-meta-content">
				<span class=" post-date">
					<span class="left"><i class="fa fa-clock-o"></i></span>
					<span class="right"><?php the_time( get_option( 'date_format' ));?></span>
				</span>
				<span class="slash">/</span>
				<span class=" post-author">
					<span class="left"><i class="fa fa-pencil-square-o"></i></span>
					<span class="right"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author_meta( 'display_name' ); ?></a></span>
				</span>
				<span class="slash">/</span>
				<span class=" comment">
					<span class="left"><i class="fa fa-commenting-o"></i></span>
					<span class="right"><a href="<?php the_permalink();?>">                    
						<?php comments_popup_link(
							esc_html__(' 0 comment', 'digitax'), 
							esc_html__(' 1 comment', 'digitax'), 
							' % '.esc_html__('comments', 'digitax'),
							'',
							esc_html__( 'Comment off', 'digitax' )
						); ?>
					</a></span>                
				</span>
			</span>
		<?php }
	}

	if ( ! function_exists( 'digitax_content_meta_clone' ) ) {
		function digitax_content_meta_clone( ) { ?>
			<span class="post-meta-content">
				<span class="wp-author">
					<span class="slash-text"><?php esc_html_e('Posted By: ', 'digitax') ?></span>
					<span class=" post-author">
						<span class="right"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author_meta( 'display_name' ); ?></a></span>
					</span>
				</span>

				<?php if(has_category( )) : ?>
					<span class="wp-categories">
						<span class="slash"></span>
						<span class="slash-text"><?php esc_html_e('Categories: ', 'digitax') ?></span>
						<span class=" categories">
							<span class="right"><?php the_category('&sbquo;&nbsp;'); ?></span><!-- end right -->             
						</span><!-- end categories -->
					</span><!-- end wp-category -->
				<?php endif ?>

				<span class=" post-date">
					<span class="slash"></span>
					<span class="slash-text"><?php esc_html_e('Posted: ', 'digitax') ?></span>
					<span class="right"><?php the_time( get_option( 'date_format' ) );?></span>
				</span>

			</span>
		<?php }
	}
	
if ( ! function_exists ('digitax_content_share_clone') ) {
	function digitax_content_share_clone () { 
		?>
		<?php if ( has_filter('digitax_share_social') ) : ?>
			<div class="tag-share">
				<?php 
				$link = get_the_permalink();
				$title = get_the_title(); 
				?>
				<?php echo apply_filters( 'digitax_share_social', $link, $title  ); ?>
			</div>
		<?php endif ?>
		<?php
	}
}

if ( ! function_exists( 'digitax_content_body' ) ) {
	function digitax_content_body( ) { ?>
		<div class="post-excerpt">
			<?php if(is_single()){
				the_content();
				wp_link_pages( array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'digitax' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					'pagelink'    => '%',
					'separator'   => '',
				) );             
			}else{
				the_excerpt();
			}?>
		</div>

		<?php 
	}
}

if ( ! function_exists( 'digitax_content_readmore' ) ) {
	function digitax_content_readmore( ) { ?>
		<div class="post-footer">
			<div class="post-readmore">
				<a class="btn btn-theme btn-theme-transparent" href="<?php the_permalink(); ?>"><?php  esc_html_e('Read More', 'digitax'); ?></a>
			</div>
		</div>
	<?php }
}

if ( ! function_exists( 'digitax_content_tag' ) ) {
	function digitax_content_tag( ) { ?>

		<footer class="post-tag">
			<?php if(has_tag()){ ?>
				<span class="post-tags">
					<span class="ovatags"><?php esc_html_e('Tags: ', 'digitax'); ?></span>
					<?php the_tags('','&nbsp;&nbsp;',''); ?>
				</span>
			<?php } ?>
			<div class="clearboth"></div>
			<?php if(has_category( )){ ?>
				<span class="post-categories">
					<span class="ovacats"><?php esc_html_e('Categories: ', 'digitax'); ?></span>
					<?php the_category('&nbsp;&nbsp;'); ?>
				</span>
			<?php } ?>

			<?php if( has_filter( 'digitax_share_social' ) ){ ?>
				<div class="share_social">
					<span class="ova_label"><?php esc_html_e('Share: ', 'digitax'); ?></span>
					<?php echo apply_filters('digitax_share_social', get_the_permalink(), get_the_title() ); ?>
				</div>
			<?php } ?>
		</footer>

	<?php }
}

if ( ! function_exists( 'digitax_content_gallery' ) ) {
	function digitax_content_gallery( ) {

		$gallery = get_post_meta(get_the_ID(), 'digitax_met_file_list', true) ? get_post_meta(get_the_ID(), 'digitax_met_file_list', true) : '';

		$k = 0;
		if($gallery){ $i=0; ?>

			<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
				<!-- Indicators -->
				<ol class="carousel-indicators">
					<?php foreach ($gallery as $key => $value) { ?>
						<li data-target="#carousel-example-generic" data-slide-to="<?php echo esc_attr($i); ?>" class="<?php if ($i==0) { echo esc_attr('active'); }  ?>"></li>
						<?php $i++; } ?>
					</ol>

					<!-- Wrapper for slides -->
					<div class="carousel-inner" role="listbox">
						<?php foreach ($gallery as $key => $value) { ?>
							<div class="item <?php echo esc_attr($k==0)?'active':'';$k++; ?>">
								<img class="img-responsive" src="<?php  echo esc_attr($value); ?>" alt="<?php the_title_attribute(); ?>">
							</div>
						<?php } ?>
					</div>

				</div>


				<?php
			}
		}
	}






//Custom comment List:
	if ( ! function_exists( 'digitax_theme_comment' ) ) {
		function digitax_theme_comment($comment, $args, $depth) {

			$GLOBALS['comment'] = $comment; ?>   
			<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
				<article class="comment_item" id="comment-<?php comment_ID(); ?>">

					<header class="comment-author">
						<?php echo get_avatar($comment,$size='70', $default = 'mysteryman' ); ?>
					</header>

					<section class="comment-details">

						<div class="author-name">

							<div class="name">
								<?php printf('%s', get_comment_author_link()) ?>	
							</div>

							<div class="date">
								<?php printf(get_comment_date()) ?>	
							</div>


						</div> 

						<div class="comment-body clearfix comment-content">
							<?php comment_text() ?>
						</div>

						<div class="ova_reply">
							<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
							<?php edit_comment_link( esc_html__( '(Edit)', 'digitax' ), '  ', '' );?>
						</div>

					</section>

					<?php if ($comment->comment_approved == '0') : ?>
						<em><?php esc_html_e('Your comment is awaiting moderation.', 'digitax') ?></em>
						<br />
					<?php endif; ?>

				</article>
				<?php
			}
		}








