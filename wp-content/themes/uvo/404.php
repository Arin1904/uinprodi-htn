<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package uvo
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<!-- wraper_error_main -->
		<div class="wraper_error_main style-one">
			<!-- START OF 404 STYLE ONE CONTENT -->
			<div class="container">
					<!-- row -->
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<!-- error_main -->
							<div class="error_main">
								<!-- radiantthemes-counterup -->
								<div class="radiantthemes-counterup odometer" data-counterup-value="<?php echo esc_attr__( '404', 'uvo' ); ?>"></div>
								<!-- radiantthemes-counterup -->

								<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) { ?>
									<!-- radiantthemes-content -->
									<?php if ( radiantthemes_global_var( '404_error_one_content', '', false ) ) { ?>
								<p>
									<?php echo wp_kses( radiantthemes_global_var( '404_error_one_content', '', false ), 'rt-content' ); ?>
								</p>
									<?php } ?>
									<!-- radiantthemes-content -->

									<!-- radiantthemes-button-link -->
									<?php if ( radiantthemes_global_var( '404_error_one_button_link', '', false ) != '' ) { ?>
										<a class="btn" href="<?php echo esc_url( radiantthemes_global_var( '404_error_one_button_link', '', false ) ); ?>"><span class="ti-arrow-left"></span> <?php echo esc_html( radiantthemes_global_var( '404_error_one_button_text', '', false ) ); ?></a>
									<?php } ?>
									<!-- radiantthemes-button-link -->
								<?php } else { ?>
									<p><?php echo esc_html__( 'Oops! It could be you or us, there is no page here. It might have been moved or deleted.', 'uvo' ); ?></p>
									<a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="ti-arrow-left"></span> <?php echo esc_html__( 'Back To Home', 'uvo' ); ?></a>
								<?php } ?>
							</div>
							<!-- error_main -->
						</div>
					</div>
					<!-- row -->
				</div>
				<!-- END OF 404 STYLE ONE CONTENT -->
		</div>
		<!-- wraper_error_main -->
	</main><!-- #main -->
</div><!-- #primary -->

<?php
	get_footer();
