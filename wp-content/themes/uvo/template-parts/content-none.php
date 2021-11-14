<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package uvo
 */

?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
	<section class="no-results not-found">
		<header class="page-header">
			<h3 class="page-title"><?php esc_html_e( 'Oops! Nothing Found', 'uvo' ); ?></h3>
			<p><?php esc_html_e( 'Sorry! but nothing matched your search terms. Please try again with some different keywords.', 'uvo' ); ?></p>
		</header><!-- .page-header -->
		<div class="clearfix"></div>
		<!-- radiantthemes-search-form -->
		<div class="radiantthemes-search-form">
			<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<div class="form-row">
					<input type="search" name="s" placeholder="<?php echo esc_attr__( 'Search Here...', 'uvo' ); ?>" >
				</label>
				<button type="submit"><i class="lnr lnr-magnifier"></i></button>
			</form>
		</div>
		<!-- radiantthemes-search-form -->
	</section><!-- .no-results -->
</div>
