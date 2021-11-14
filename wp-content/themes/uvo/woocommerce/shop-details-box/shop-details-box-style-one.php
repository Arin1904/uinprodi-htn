<?php
/**
 * Shop Details Box Style One Template
 *
 * @package uvo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
} ?>

<?php
while ( have_posts() ) :
	the_post();
	?>
		<!-- shop_single -->
		<div id="product-<?php the_ID(); ?>" <?php post_class( 'shop_single' ); ?>>
			<?php do_action( 'woocommerce_before_single_product' ); ?>
			<div class="rt-product-gallery">
				<!-- START OF PRODUCT IMAGE / GALLERY -->
				<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
				<!-- END OF PRODUCT IMAGE / GALLERY -->
			</div>

			<!-- START OF PRODUCT SUMMARY -->
			<div class="summary entry-summary">
				<?php do_action( 'woocommerce_single_product_summary' ); ?>
			</div>

			<!-- END OF PRODUCT SUMMARY -->
			<div class="clearfix"></div>

			<?php
							$tabs = apply_filters( 'woocommerce_product_tabs', array() );
			if ( ! empty( $tabs ) ) :
				?>
								<!-- shop_single_tabs -->
								<div class="shop_single_tabs">
									<ul class="nav nav-tabs" id="myTab" role="tablist">
						<?php
						$i = 0;
						foreach ( $tabs as $key => $tab ) :
							$i++;
							?>
											<li class="nav-item waves-effect waves-light">
												<a class="nav-link
												<?php
												if ( 1 == $i ) {
													echo esc_html( 'active' ); }
												?>
											" id="home-tab-<?php echo esc_attr( $i ); ?>" data-toggle="tab" href="#woo-<?php echo esc_attr( $key ); ?>" role="tab" aria-selected="true"><p>
									<?php echo esc_html( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ); ?></p></a>

											</li>
										<?php endforeach; ?>
									</ul>
									<div class="tab-content" id="myTabContent">
										<?php
										$i = 0;
										foreach ( $tabs as $key => $tab ) :
											$i++;
											?>
											<div class="tab-pane fade
											<?php
											if ( 1 == $i ) {
												echo esc_attr( ' active show' ); }
											?>
											 " id="woo-<?php echo esc_attr( $key ); ?>" role="tabpanel">

												<?php
												if ( isset( $tab['callback'] ) ) {
													call_user_func( $tab['callback'], $key, $tab ); }
												?>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
								<!-- shop_single_tabs -->
							<?php endif; ?>
		</div>
		<!-- shop_single -->

		<!-- shop_related -->
		<div class="shop_related">
			<?php woocommerce_output_related_products(); ?>
		</div>
		<!-- shop_related -->
<?php endwhile; ?>
