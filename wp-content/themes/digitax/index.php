<?php get_header(); ?>
<?php $blog_layout = apply_filters( 'digitax_theme_sidebar','' ); 
$blog_template = apply_filters( 'digitax_blog_template', '' );

?>
<div class="wrap_site <?php echo esc_attr($blog_layout); ?>">
	<div id="main-content" class="main">
		
		<?php 
		if ( have_posts() ) : while ( have_posts() ) : the_post(); 
			get_template_part( 'content/blog', 'default' );
		endwhile; 
		?>

		<div class="pagination-wrapper">
			<?php digitax_pagination_theme(); ?>
		</div>

		<?php else : ?>
			<?php get_template_part( 'content/content', 'none' ); ?>
		<?php endif; ?>
		
	</div> <!-- #main-content -->
	<?php get_sidebar(); ?>
</div> <!-- .wrap_site -->

<?php get_footer(); ?>