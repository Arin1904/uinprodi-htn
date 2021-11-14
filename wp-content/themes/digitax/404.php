<?php get_header('404');  ?>

<div class="digitax_404_page">
	
	<section class="error-page-single">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="pnf-content">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/404_error.png');?>" alt="<?php esc_attr_e( 'Page Not Found', 'digitax' ) ?>">
						<h2 class="second_font"><?php esc_html_e( 'Ohh! Page Not Found', 'digitax' ); ?></h2>
						<p><?php esc_html_e( 'It looks like nothing was found at this location. Click the button below to return home.', 'digitax' ); ?></p>
						<a href="<?php echo get_home_url(); ?>" class="elementor-button-link elementor-button btn btn1 style1"><?php esc_html_e( 'GO BACK HOME', 'digitax' ); ?></a>
					</div>
				</div>
			</div>
		</div><!--/.container-->
	</section>
</div>

<?php get_footer(); ?>