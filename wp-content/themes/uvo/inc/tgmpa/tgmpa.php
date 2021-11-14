<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.4.1
 * @author     Thomas Griffin
 * @author     Gary Jones
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    //opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       //github.com/thomasgriffin/TGM-Plugin-Activation
 */

require_once get_template_directory() . '/inc/tgmpa/class-tgm-plugin-activation.php';

/**
 * Undocumented function
 */
function radiantthemes_register_required_plugins() {
	$plugins = array(
		// Redux Framework.
		array(
			'name'     => esc_html__( 'Redux Framework', 'uvo' ),
			'slug'     => 'redux-framework',
			'required' => true,
		),
		// Elementor.
		array(
			'name'     => esc_html__( 'Elementor', 'uvo' ),
			'slug'     => 'elementor',
			'required' => true,
		),
		// Contact Form 7.
		array(
			'name'     => esc_html__( 'Contact Form 7', 'uvo' ),
			'slug'     => 'contact-form-7',
			'required' => true,
		),
		// Contact Form7 Widget For Elementor Page Builder.
		array(
			'name'     => esc_html__( 'CF7 Widget Elementor', 'uvo' ),
			'slug'     => 'cf7-widget-elementor',
			'required' => true,
		),
		// RT Custom Post Type.
		array(
			'name'     => esc_html__( 'RadiantThemes Custom Post Type', 'uvo' ),
			'slug'     => 'radiantthemes-custom-post-type',
			'source'   => 'https://api.radiantthemes.com/plugins/uvo/radiantthemes-custom-post-type.zip',
			'required' => true,
		),
		// RadiantThemes Addons.
		array(
			'name'     => esc_html__( 'RadiantThemes Addons', 'uvo' ),
			'slug'     => 'radiantthemes-addons',
			'source'   => 'https://api.radiantthemes.com/plugins/uvo/radiantthemes-addons.zip',
			'required' => true,
		),
		// Unyson.
		array(
			'name'     => esc_html__( 'Unyson', 'uvo' ),
			'slug'     => 'unyson',
			'required' => true,
		),
		// WooCommerce.
		array(
			'name'     => esc_html__( 'WooCommerce', 'uvo' ),
			'slug'     => 'woocommerce',
			'required' => true,
		),
		// Advanced Custom Fields.
		array(
			'name'     => esc_html__( 'Advanced Custom Fields', 'uvo' ),
			'slug'     => 'advanced-custom-fields',
			'required' => true,
		),
		// Date Time Picker Field.
		array(
			'name'     => esc_html__( 'Date Time Picker Field', 'uvo' ),
			'slug'     => 'date-time-picker-field',
			'required' => true,
		),
		// Mailchimp for WordPress.
		array(
			'name'     => esc_html__( 'Mailchimp for WordPress', 'uvo' ),
			'slug'     => 'mailchimp-for-wp',
			'required' => true,
		),
		// Smash Balloon Instagram Feed.
		array(
			'name'     => esc_html__( 'Smash Balloon Instagram Feed', 'uvo' ),
			'slug'     => 'instagram-feed',
			'required' => true,
		),
		// Slider Revolution.
		array(
			'name'     => esc_html__( 'Slider Revolution', 'uvo' ),
			'slug'     => 'revslider',
			'source'   => 'https://api.radiantthemes.com/plugins/@3d!S58hndj-5d5&-fg8/revslider--4cLsaCdwDzB4jxfMDiKyn8w6aaGoxSAuARhrNfm6.zip',
			'required' => true,
		),
	);

	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => true,                    // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );

}
add_action( 'tgmpa_register', 'radiantthemes_register_required_plugins' );
