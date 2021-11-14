<?php
add_action('wp_enqueue_scripts', 'digitax_theme_scripts_styles');
add_action('wp_enqueue_scripts', 'digitax_theme_script_default');


function digitax_theme_scripts_styles() {

    // enqueue the javascript that performs in-link comment reply fanciness
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' ); 
    }
   
    /* Add Javascript  */
    wp_enqueue_script( 'bootstrap', DIGITAX_URI.'/assets/libs/bootstrap/js/bootstrap.bundle.min.js' , array( 'jquery' ), null, true );
    wp_enqueue_script( 'select2', DIGITAX_URI.'/assets/libs/select2/select2.min.js' , array( 'jquery' ), null, true );

    wp_enqueue_script('digitax-script', DIGITAX_URI.'/assets/js/script.js', array('jquery'),null,true);
    
    /* Add Css  */
    wp_enqueue_style('bootstrap', DIGITAX_URI.'/assets/libs/bootstrap/css/bootstrap.min.css', array(), null);
    wp_enqueue_style('aos', DIGITAX_URI.'/assets/libs/aos/aos.css', array(), null);
    wp_enqueue_style('normalize', DIGITAX_URI.'/assets/libs/normalize/normalize.css', array(), null);
    wp_enqueue_style( 'select2', DIGITAX_URI. '/assets/libs/select2/select2.min.css', array(), null );

    /* Font Class */
    wp_enqueue_style('v4-shims', DIGITAX_URI.'/assets/libs/fontawesome/css/v4-shims.min.css', array(), null);
    wp_enqueue_style('fontawesome', DIGITAX_URI.'/assets/libs/fontawesome/css/all.min.css', array(), null);
    wp_enqueue_style('elegant-font', DIGITAX_URI.'/assets/libs/elegant_font/ele_style.css', array(), null);
    wp_enqueue_style('themify-font', DIGITAX_URI.'/assets/libs/themify-icon/themify-icons.css', array(), null);
    wp_enqueue_style( 'flaticon', DIGITAX_URI.'/assets/libs/flaticon/font/flaticon.css', array(), null );

    
    wp_enqueue_style('digitax-theme', DIGITAX_URI.'/assets/css/theme.css', array(), null);

    

}

function digitax_theme_script_default(){

    if ( is_child_theme() ) {
      wp_enqueue_style( 'parent-style', trailingslashit( get_template_directory_uri() ) . 'style.css', array(), null );
    }

    wp_enqueue_style( 'digitax-style', get_stylesheet_uri(), array(), null );
}