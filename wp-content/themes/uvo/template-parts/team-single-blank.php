<?php
/**
 * Template part for displaying team single
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package uvo
 */

?>

<?php
while ( have_posts() ) :
	the_post();
	the_content();
endwhile; // End of the loop.
