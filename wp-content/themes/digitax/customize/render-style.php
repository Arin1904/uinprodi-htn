<?php
function digitax_customize_css() {

    
    $layout_global = include DIGITAX_URL . '/customize/settings/layout-global.php';
    $general = include DIGITAX_URL . '/customize/settings/general.php';

    $allcss = <<<CSS
{$layout_global}{$general}
CSS;
    return $allcss;
    
}



add_action('wp_enqueue_scripts', 'digitax_render_style');

function digitax_render_style(){
    wp_enqueue_style( 'ova-google-fonts', digitax_customize_google_fonts(), array(), null );
    wp_add_inline_style( 'digitax-style', digitax_customize_css() );
}

function digitax_customize_google_fonts(){

    $fonts_url = '';

    $default_primary_font = json_decode( digitax_default_primary_font() );
    

    $custom_fonts = get_theme_mod('ova_custom_font','');

    // Primary Font 
    $primary_font = json_decode( get_theme_mod( 'primary_font' ) ) ? json_decode( get_theme_mod( 'primary_font' ) ) : $default_primary_font;
    $primary_font_family = $primary_font->font;
    $primary_font_weights_string = $primary_font->regularweight ? $primary_font->regularweight : '100,200,300,400,500,600,700,800,900';
    $is_custom_primary_font = $custom_fonts != '' ? !strpos($primary_font_family, $custom_fonts) : true;
    
    $general_flag = _x( 'on', $primary_font_family.': on or off', 'digitax');
 
    if ( 'off' !== $general_flag ) {
        $font_families = array();
 
        if ( 'off' !== $general_flag && $is_custom_primary_font ) {
            $font_families[] = $primary_font_family.':'.$primary_font_weights_string;
        }
 
       

        if($font_families != null){
          $query_args = array(
              'family' => urlencode( implode( '|', $font_families ) )
          );  
          $fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );
        }
        
    }
 
    return esc_url_raw( $fonts_url );
}