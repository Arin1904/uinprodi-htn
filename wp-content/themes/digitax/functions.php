<?php
	if(defined('DIGITAX_URL') 	== false) 	define('DIGITAX_URL', get_template_directory());
	if(defined('DIGITAX_URI') 	== false) 	define('DIGITAX_URI', get_template_directory_uri());

	load_theme_textdomain( 'digitax', DIGITAX_URL . '/languages' );
	
	// require libraries, function
	require( DIGITAX_URL.'/inc/init.php' );

	// Add js, css
	require( DIGITAX_URL.'/extend/add_js_css.php' );
	
	// require walker menu
	require_once (DIGITAX_URL.'/inc/ova_walker_nav_menu.php');
	

	// register menu, widget
	require( DIGITAX_URL.'/extend/register_menu_widget.php' );

	// require content
	require_once (DIGITAX_URL.'/content/define_blocks_content.php');
	
	// require breadcrumbs
	require( DIGITAX_URL.'/extend/breadcrumbs.php' );

	// Hooks
	require( DIGITAX_URL.'/inc/class_hook.php' );

	
	/* Customize */
	if( current_user_can('customize') ){
	    require_once DIGITAX_URL.'/customize/custom-control/google-font.php';
	    require_once DIGITAX_URL.'/customize/custom-control/heading.php';
	}
	require_once DIGITAX_URL.'/customize/class-customize.php';
    require_once DIGITAX_URL.'/customize/render-style.php';
    
    
    
	
	// Require metabox
	if( is_admin() ){
		// Require TGM
		require_once ( DIGITAX_URL.'/install_resource/active_plugins.php' );		
	}