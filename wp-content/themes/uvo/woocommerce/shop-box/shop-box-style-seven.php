<?php
/**
 * Shop Box Style Seven Template
 *
 * @package uvo
 */

?>

<!-- radiantthemes-shop-box style-seven -->
<div <?php post_class( 'radiantthemes-shop-box  style-seven' ); ?>>
	<div class="holder">
		<div class="pic">
			<div class="product-image" style="background-image:url(<?php the_post_thumbnail_url( 'large' ); ?>)"></div>
			<a class="overlay" href="<?php the_permalink(); ?>"></a>
		</div>
		<div class="data">
			<?php
			/**
			 * Woocommerce Before Shop Loop Item hook.
			 * woocommerce_before_shop_loop_item hook.
			 *
			 * @hooked woocommerce_template_loop_product_link_open - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item' );
			?>
			<?php
			/**
			 * Woocommerce Shop Loop Item Title hook.
			 * woocommerce_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 */
			do_action( 'woocommerce_shop_loop_item_title' );
			?>
			</a>
			<div class="price-box-holder">
			<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
			<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
			<?php
			/**
			 * Woocommerce After Shop Loop Item Title hook.
			 * woocommerce_after_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			?>
			</div>
		</div>
	</div>
</div>
<!-- radiantthemes-shop-box style-seven -->
