<?php

if (!defined( 'ABSPATH' )) {
	exit;
}
if (!class_exists( 'Digitax_Customize' )){

	class Digitax_Customize {

		public function __construct() {
			add_action( 'customize_register', array( $this, 'digitax_customize_register' ) );
		}

		public function digitax_customize_register($wp_customize) {

			$this->digitax_init_remove_setting( $wp_customize );
			$this->digitax_init_ova_typography( $wp_customize );
			$this->digitax_init_ova_layout( $wp_customize );
			$this->digitax_init_ova_header( $wp_customize );
			$this->digitax_init_ova_footer( $wp_customize );
			$this->digitax_init_ova_blog( $wp_customize );
			$this->init_ova_countdown( $wp_customize );


			

			do_action( 'digitax_customize_register', $wp_customize );
		}

		public function digitax_init_remove_setting( $wp_customize ){
			/* Remove Colors &  Header Image Customize */
			$wp_customize->remove_section('colors');
			$wp_customize->remove_section('header_image');
		}



		/* Typo */
		public function digitax_init_ova_typography($wp_customize){




			/* Body Pane ******************************/
			$wp_customize->add_section( 'typo_general' , array(
				'title'      => esc_html__( 'Typography', 'digitax' ),
				'priority'   => 1,
			    // 'panel' => 'typo_panel',
			) );


			/* General Typo */
			$wp_customize->add_setting( 'general_heading', array(
				'default' => '',
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );

			$wp_customize->add_control(
				new Digitax_Customize_Control_Heading( 
					$wp_customize, 
					'general_heading', 
					array(
						'label'          => esc_html__('General Typo','digitax'),
						'section'        => 'typo_general',
						'settings'       => 'general_heading',
					) )
			);


			/* General Font */
			$wp_customize->add_setting( 'primary_font',
				array(
					'default' => digitax_default_primary_font(),
					'sanitize_callback' => 'digitax_google_font_sanitization'
				)
			);
			$wp_customize->add_control( new Digitax_Google_Font_Select_Custom_Control( $wp_customize, 'primary_font',
				array(
					'label' => esc_html__( 'Primary Font', 'digitax' ),
					'section' => 'typo_general',
					'input_attrs' => array(
						'font_count' => 'all',
						'orderby' => 'popular',
					),
				)
			) );


			/* Font Size */
			$wp_customize->add_setting( 'general_font_size', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '16px',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );

			$wp_customize->add_control('general_font_size', array(
				'label' => esc_html__('Font Size','digitax'),
				'description' => esc_html__('Example: 16px, 1.2em','digitax'),
				'section' => 'typo_general',
				'settings' => 'general_font_size',
				'type' 		=>'text'
			));

			/* Line Height */
			$wp_customize->add_setting( 'general_line_height', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '26px',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );

			$wp_customize->add_control('general_line_height', array(
				'label' => esc_html__('Line height','digitax'),
				'description' => esc_html__('Example: 23px, 1.6em','digitax'),
				'section' => 'typo_general',
				'settings' => 'general_line_height',
				'type' 		=>'text'
			));


			/* Letter Space */
			$wp_customize->add_setting( 'general_letter_space', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '0px',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );

			$wp_customize->add_control('general_letter_space', array(
				'label' => esc_html__('Letter Spacing','digitax'),
				'description' => esc_html__('Example: 0px, 0.5em','digitax'),
				'section' => 'typo_general',
				'settings' => 'general_letter_space',
				'type' 		=>'text'
			));


			$wp_customize->add_setting( 'general_color', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '#43464b',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize, 
					'general_color', 
					array(
						'label'          => esc_html__("Content Color",'digitax'),
						'section'        => 'typo_general',
						'settings'       => 'general_color',
					) ) 
			);





			$wp_customize->add_setting( 'color_message', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );

			$wp_customize->add_control(
				new Digitax_Customize_Control_Heading( 
					$wp_customize, 
					'color_message', 
					array(
						'label'          => esc_html__('General Color','digitax'),
						'section'        => 'typo_general',
						'settings'       => 'color_message',
					) )
			);


			$wp_customize->add_setting( 'primary_color', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '#f16334',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize, 
					'primary_color', 
					array(
						'label'          => esc_html__("Primary color",'digitax'),
						'section'        => 'typo_general',
						'settings'       => 'primary_color',
					) ) 
			);





			/* Custom Font */
			/* Message */
			$wp_customize->add_setting( 'custom_font_message', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
			$wp_customize->add_control(
				new Digitax_Customize_Control_Heading( 
					$wp_customize, 
					'custom_font_message', 
					array(
						'label'          => esc_html__('Custom Font','digitax'),
						'section'        => 'typo_general',
						'settings'       => 'custom_font_message',
					) )
			);


			$wp_customize->add_control(
				new Digitax_Customize_Control_Heading( 
					$wp_customize, 
					'custom_font_message', 
					array(
						'label'          => esc_html__('Custom Font','digitax'),
						'section'        => 'typo_general',
						'settings'       => 'custom_font_message',
					) )
			);

			$wp_customize->add_setting( 'ova_custom_font', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );

			$wp_customize->add_control('ova_custom_font', array(
				'label' => esc_html__('Custom Font','digitax'),
				'description' => esc_html__('Step 1: Insert font-face in style.css file: Refer https://www.w3schools.com/cssref/css3_pr_font-face_rule.asp. Step 2: Insert font-family and font-weight like format: 
					["Perpetua", "Regular:Bold:Italic:Light"]. Step 3: Refresh customize page to display new font in dropdown font field.','digitax'),
				'section' => 'typo_general',
				'settings' => 'ova_custom_font',
				'type' =>'textarea'
			));


			

		}


		/* Layout */
		public function digitax_init_ova_layout( $wp_customize ){

			$wp_customize->add_section( 'layout_section' , array(
				'title'      => esc_html__( 'Layout', 'digitax' ),
				'priority'   => 2,
			) );


			$wp_customize->add_setting( 'global_preload', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => 'yes',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_preload', array(
				'label' => esc_html__('Preload','digitax'),
				'section' => 'layout_section',
				'settings' => 'global_preload',
				'type' =>'select',
				'choices' => array(
					'yes' => esc_html__('Yes', 'digitax'),
					'no' => esc_html__('No', 'digitax')
				)
			));

			$wp_customize->add_setting( 'global_layout', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => 'layout_2r',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_layout', array(
				'label' => esc_html__('Layout','digitax'),
				'section' => 'layout_section',
				'settings' => 'global_layout',
				'type' =>'select',
				'choices' => apply_filters( 'digitax_define_layout', '' )
			));

			$wp_customize->add_setting( 'global_sidebar_width', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => '320',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_sidebar_width', array(
				'label' => esc_html__('Sidebar Width (px)','digitax'),
				'section' => 'layout_section',
				'settings' => 'global_sidebar_width',
				'type' =>'number'
			));
			

			$wp_customize->add_setting( 'global_width_content', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => '1170',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_width_content', array(
				'label' => esc_html__('Width Content (px)','digitax'),
				'section' => 'layout_section',
				'settings' => 'global_width_content',
				'type' =>'number',
				'default' => '1170'
			));

			$wp_customize->add_setting( 'global_width_site', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => 'wide',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_width_site', array(
				'label' => esc_html__('Width Site','digitax'),
				'section' => 'layout_section',
				'settings' => 'global_width_site',
				'type' =>'select',
				'choices' => apply_filters('digitax_define_wide_boxed', '')
			));

			$wp_customize->add_setting( 'global_boxed_container_width', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => '1170',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_boxed_container_width', array(
				'label' => esc_html__('Boxed Container Width (px)','digitax'),
				'section' => 'layout_section',
				'settings' => 'global_boxed_container_width',
				'type' =>'number',
				'default' => '1170'
			));
			$wp_customize->add_setting( 'global_boxed_offset', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => '20',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_boxed_offset', array(
				'label' => esc_html__('Boxed Offset (px)','digitax'),
				'section' => 'layout_section',
				'settings' => 'global_boxed_offset',
				'type' =>'number',
				'default' => '20'
			));

		}

		/* Header */
		public function digitax_init_ova_header( $wp_customize ){

			$wp_customize->add_section( 'header_section' , array(
				'title'      => esc_html__( 'Header', 'digitax' ),
				'priority'   => 3,
			) );

			$wp_customize->add_setting( 'global_header', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => 'default',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_header', array(
				'label' => esc_html__('Header Default','digitax'),
				'description' => esc_html__('This isn\'t effect in Blog' ,'digitax'),
				'section' => 'header_section',
				'settings' => 'global_header',
				'type' =>'select',
				'choices' => apply_filters('digitax_list_header', '')
			));

		}

		/* Footer */
		public function digitax_init_ova_footer( $wp_customize ){

			$wp_customize->add_section( 'footer_section' , array(
				'title'      => esc_html__( 'Footer', 'digitax' ),
				'priority'   => 4,
			) );

			$wp_customize->add_setting( 'global_footer', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => 'default',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('global_footer', array(
				'label' => esc_html__('Footer Default','digitax'),
				'description' => esc_html__('This isn\'t effect in Blog' ,'digitax'),
				'section' => 'footer_section',
				'settings' => 'global_footer',
				'type' =>'select',
				'choices' => apply_filters('digitax_list_footer', '')
			));

		}


		/* Blog */
		public function digitax_init_ova_blog( $wp_customize ){

			$wp_customize->add_panel( 'blog_panel', array(
				'title'      => esc_html__( 'Blog', 'digitax' ),
				'priority' => 5,
			) );

			$wp_customize->add_section( 'blog_section' , array(
				'title'      => esc_html__( 'Archive', 'digitax' ),
				'priority'   => 30,
				'panel' => 'blog_panel',
			) );

			$wp_customize->add_setting( 'blog_template', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => 'default',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
			$wp_customize->add_control('blog_template', array(
				'label' => esc_html__('Type','digitax'),
				'section' => 'blog_section',
				'settings' => 'blog_template',
				'type' =>'select',
				'default' => 'default',
				'choices' => array(
					'default' => esc_html__('Default', 'digitax'),
				)
			));

			$wp_customize->add_setting( 'blog_layout', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => 'layout_2r',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
			$wp_customize->add_control('blog_layout', array(
				'label' => esc_html__('Layout','digitax'),
				'section' => 'blog_section',
				'settings' => 'blog_layout',
				'type' =>'select',
				'choices' => apply_filters( 'digitax_define_layout', '' )
			));

			$wp_customize->add_setting( 'blog_sidebar_width', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '320',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
			$wp_customize->add_control('blog_sidebar_width', array(
				'label' => esc_html__('Sidebar Width (px)','digitax'),
				'section' => 'blog_section',
				'settings' => 'blog_sidebar_width',
				'type' =>'number'
			));








			$wp_customize->add_setting( 'blog_header', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => 'default',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
			$wp_customize->add_control('blog_header', array(
				'label' => esc_html__('Header','digitax'),
				'section' => 'blog_section',
				'settings' => 'blog_header',
				'type' =>'select',
				'choices' => apply_filters('digitax_list_header', '')
			));

			$wp_customize->add_setting( 'blog_footer', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => 'default',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
			$wp_customize->add_control('blog_footer', array(
				'label' => esc_html__('Footer','digitax'),
				'section' => 'blog_section',
				'settings' => 'blog_footer',
				'type' =>'select',
				'choices' => apply_filters('digitax_list_footer', '')
			));


			$wp_customize->add_section( 'single_section' , array(
				'title'      => esc_html__( 'Single', 'digitax' ),
				'priority'   => 30,
				'panel' => 'blog_panel',
			) );	

			$wp_customize->add_setting( 'single_layout', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => 'layout_2r',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
			$wp_customize->add_control('single_layout', array(
				'label' => esc_html__('Layout','digitax'),
				'section' => 'single_section',
				'settings' => 'single_layout',
				'type' =>'select',
				'choices' => apply_filters( 'digitax_define_layout', '' )
			));

			$wp_customize->add_setting( 'single_sidebar_width', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => '320',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
			$wp_customize->add_control('single_sidebar_width', array(
				'label' => esc_html__('Sidebar Width (px)','digitax'),
				'section' => 'single_section',
				'settings' => 'single_sidebar_width',
				'type' =>'number'
			));




			$wp_customize->add_setting( 'single_header', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => 'default',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
			$wp_customize->add_control('single_header', array(
				'label' => esc_html__('Header','digitax'),
				'section' => 'single_section',
				'settings' => 'single_header',
				'type' =>'select',
				'choices' => apply_filters('digitax_list_header', '')
			));

			$wp_customize->add_setting( 'single_footer', array(
				  'type' => 'theme_mod', // or 'option'
				  'capability' => 'edit_theme_options',
				  'theme_supports' => '', // Rarely needed.
				  'default' => 'default',
				  'transport' => 'refresh', // or postMessage
				  'sanitize_callback' => 'sanitize_text_field' // Get function name 
				  
				) );
			$wp_customize->add_control('single_footer', array(
				'label' => esc_html__('Footer','digitax'),
				'section' => 'single_section',
				'settings' => 'single_footer',
				'type' =>'select',
				'choices' => apply_filters('digitax_list_footer', '')
			));

		}

	


		/* Countdown */
		public function init_ova_countdown( $wp_customize ){

			$wp_customize->add_section( 'countdown_language', array(
				'title'      => esc_html__( 'Language Countdown', 'digitax' ),

			) );
			$wp_customize->add_setting( 'choice_language', array(
			  'type' => 'theme_mod', // or 'option'
			  'capability' => 'edit_theme_options',
			  'theme_supports' => '', // Rarely needed.
			  'default' => '',
			  'transport' => 'refresh', // or postMessage
			  'sanitize_callback' => 'sanitize_text_field' // Get function name 
			  
			) );
			$wp_customize->add_control('choice_language', array(
				'label' => esc_html__('Language','digitax'),
				'section' => 'countdown_language',
				'settings' => 'choice_language',
				'type' =>'select',
				'choices' => array(
					''   => 'English',
					'ar' => 'Arabic',
					'bg' => 'Bulgarian',
					'bn' => 'Bengali/Bangla',
					'bs' => 'Bosnian',
					'ca' => 'Catalan',
					'cs' => 'Czech',
					'cy' => 'Welsh',
					'da' => 'Danish',
					'de' => 'German',
					'el' => 'Greek',
					'es' => 'Spanish',
					'et' => 'Estonian',
					'fa' => 'Farsi/Persian',
					'fi' => 'Finnish',
					'fo' => 'Faroese',
					'fr' => 'French',
					'gl' => 'Galician',
					'gu' => 'Gujarati',
					'he' => 'Hebrew',
					'hr' => 'Croatian',
					'hu' => 'Hungarian',
					'hy' => 'Armenian',
					'id' => 'Indonesian',
					'is' => 'Icelandic',
					'it' => 'Italian',
					'ja' => 'Japanese',
					'kn' => 'Kannada',
					'ko' => 'Korean',
					'lt' => 'Lithuanian',
					'lv' => 'Latvian',
					'mk' => 'Macedonian',
					'ml' => 'Malayalam',
					'ms' => 'Malaysian',
					'my' => 'Burmese',
					'nb' => 'Norwegian',
					'nl' => 'Dutch',
					'pl' => 'Polish',
					'pt-BR' => 'Portuguese/Brazilian',
					'ro' => 'Romanian',
					'ru' => 'Russian',
					'sk' => 'Slovak',
					'sl' => 'Slovenian',
					'sq' => 'Albanian',
					'sr-SR' => 'Serbian Latin',
					'sr' => 'Serbian Cyrillic',
					'sv' => 'Swedish',
					'th' => 'Thai',
					'tr' => 'Turkish',
					'uk' => 'Ukrainian',
					'ur' => 'Urdu',
					'uz' => 'Uzbek',
					'vi' => 'Vietnamese',
					'zh-CN' => 'Chinese/Simplified',
					'zh-TW' => 'Chinese/Traditional',
				),
			));
		}


	}

}

new Digitax_Customize();






