<?php
/* Register Menu */
add_action( 'init', 'digitax_register_menus' );
function digitax_register_menus() {
  register_nav_menus( array(
    'primary'   => esc_html__( 'Primary Menu', 'digitax' )

  ) );
}

/* Register Widget */
add_action( 'widgets_init', 'ovaframework_second_widgets_init' );
function ovaframework_second_widgets_init() {
  
  $args_blog = array(
    'name' => esc_html__( 'Main Sidebar', 'digitax'),
    'id' => "main-sidebar",
    'description' => esc_html__( 'Main Sidebar', 'digitax' ),
    'class' => '',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => "</div>",
    'before_title' => '<h4 class="widget-title">',
    'after_title' => "</h4>",
  );
  register_sidebar( $args_blog );

}