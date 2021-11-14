<?php
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Controls_Manager;
// use Elementor\Scheme_Color;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
// use Elementor\Scheme_Typography;
class ECTBE_Widget extends \Elementor\Widget_Base {
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);		
			wp_register_style( 'ectbe-calendar-main-css', ECTBE_URL  . 'lib/css/calendar-main.min.css',null,null,'all');	
			wp_register_style( 'ectbe-custom-css', ECTBE_URL  . 'assets/css/custom-styles.css',null,null,'all');	
			wp_register_script( 'ectbe-calendar-locales', ECTBE_URL  . 'lib/js/calendar-locales-all.min.js',[ 'elementor-frontend' ],null, true );				
			wp_register_script( 'ectbe-calendar-js', ECTBE_URL  . 'assets/js/calendar.js',[ 'elementor-frontend','wp-api-request' ],null, true );
			wp_register_script( 'ectbe-calendar-main', ECTBE_URL  . 'lib/js/calendar-main.min.js', [ 'elementor-frontend' ],null, true );	
			wp_register_script( 'ectbe-moment-js', ECTBE_URL  . 'lib/js/moment.min.js', [ 'elementor-frontend' ],null, true );	
			wp_localize_script( 'ectbe-calendar-js', 'ectbe_callback_ajax', array( 'ajax_url' => admin_url('admin-ajax.php')) );
			wp_register_style( 'ectbe-list-css', ECTBE_URL  . 'assets/css/list.css',null,null,'all');
			wp_register_style( 'ectbe-common-styles', ECTBE_URL  . 'assets/css/ectbe-common-styles.css',null,null,'all');
			wp_register_style('ectbe-minimal-list', ECTBE_URL  . 'assets/css/ectbe-minimal-list.css',null,null,'all');	
    }
	public function get_script_depends() {
		if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
			return [ 'ectbe-calendar-main','ectbe-calendar-locales','ectbe-moment-js','ectbe-calendar-js' ];
		} 
		$settings = $this->get_settings_for_display();
		$layout = $settings['ectbe_layout'];
		$scripts = [];
		if($layout == 'calendar'){
			array_push($scripts, 'ectbe-calendar-main','ectbe-calendar-locales','ectbe-moment-js','ectbe-calendar-js');
		}
       return $scripts;
    }
	public function get_style_depends() {
		if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
			return [ 'ectbe-calendar-main-css','ectbe-custom-css','ectbe-list-css','ectbe-minimal-list','ectbe-common-styles' ];
		}
		$settings = $this->get_settings_for_display();
		$layout = $settings['ectbe_layout'];
		$styles = [];
		if($layout == 'calendar'){
			array_push($styles, 'ectbe-calendar-main-css','ectbe-custom-css');
		}
		elseif($layout == 'list'){
			array_push($styles, 'ectbe-list-css', 'ectbe-common-styles');
		}
		elseif($layout == 'minimal-list'){
			array_push($styles, 'ectbe-minimal-list', 'ectbe-common-styles');
		}
       return $styles;
    }
	public function get_name() {
		return 'the-events-calendar-addon';
	}
	public function get_title() {
		return __( 'Events Widgets', 'ectbe' );
	}
	public function get_icon() {
		return 'ectbe-eicons-logo';
	}
	public function get_categories() {
		return [ 'general' ];
	}
	protected function _register_controls() {	
		$this->start_controls_section(
            'ectbe_the_events_calendar_addon',
                [
                    'label'     => __('Events Widgets', 'ectbe'),
                    'tab'       => Controls_Manager::TAB_CONTENT,
                ]
            );
			
			$this->add_control(
				'ectbe_type',
				[
					'label' => __( 'Type of Events', 'ectbe' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'future',
					'options' => [
						'future' => __( 'Upcoming Events', 'ectbe' ),
						'past' => __( 'Past Events', 'ectbe' ),
						'all' => __( 'All (Upcoming + Past)', 'ectbe' )
					],
					'condition' => [
						'ectbe_layout!' => 'calendar',
					],
				]
			);
			$this->add_control(
                'ectbe_ev_category',
                [
                    'label'       => __('Event Category', 'ectbe'),
                    'type'        => Controls_Manager::SELECT2,
                    'multiple'    => true,
                    'label_block' => true,
                    'default'     => ['all'],
					'options'     => ectbe_get_tags(['taxonomy' => 'tribe_events_cat', 'hide_empty' => false]),
                ]
            );
            $this->add_control(
                'ectbe_event_source',
                [
                    'label'       => __('Events Time', 'ectbe'),
                    'type'        => Controls_Manager::SELECT,
                    'label_block' => true,
                    'default'     => 'all',
                    'options'     => [
                        'all'        => __('All Events', 'ectbe'),
                        'date_range' => __('Events in between Date Range', 'ectbe'),
                    ],
					'render_type' => 'none',
				]
            );
			$this->add_control(
                'ectbe_date_range_start',
                [
                    'label'     => __('Start Date', 'ectbe'),
                    'type'      => Controls_Manager::DATE_TIME,
                    'default'   => date('Y-m-d H:i', current_time('timestamp', 0)),
                    'condition' => [
						'ectbe_event_source' => 'date_range',
					],
					'description'  => __('Start date of date range','ectbe'),
                ]
            );
			$this->add_control(
                'ectbe_date_range_end',
                [
                    'label'     => __('End Date', 'ectbe'),
                    'type'      => Controls_Manager::DATE_TIME,
                    'default'   => date('Y-m-d H:i', strtotime("+6 months", current_time('timestamp', 0))),
                    'condition' => [
                        'ectbe_event_source' => 'date_range',
					],
					'description'  => __('End Date of Date Range','ectbe'),
                ]
			);
		$this->add_control(
            'ectbe_layout',
                [
                    'label'       => __('Layout', 'ectbe'),
                    'type'        => Controls_Manager::SELECT,
                    'label_block' => true,
                    'default'     => 'list',
                    'options'     => [						    
						'list' => __('List','ectbe'),
						'minimal-list' => __('Minimal List', 'ectbe'),
						'calendar' => __('Calendar', 'ectbe')
						               
                	],
                ]
			);
			$this->add_control(
				'ectbe_styles',
				[
					'label'   => __('Select Style', 'ectbe'),
					'type'    => Controls_Manager::SELECT,
					'options' => [
						'style-1' => __('Style 1', 'ectbe'),
						'style-2' => __('Style 2', 'ectbe'),
					],
					'default' => 'style-1',
					 'condition' => [
						'ectbe_layout!' => [
							'calendar',
							'minimal-list',
					],
					 ],
				]
			);
			
			$this->add_control(
                'ectbe_max_events',
                [
                    'label'   => __('Number of Events', 'ectbe'),
                    'type'    => Controls_Manager::NUMBER,
                    'min'     => 1,
					'default' => 25,
					'description'  => __('Maximum number of events to display','ectbe'),
                ]
            );
			$this->add_control(
				'ectbe_calendar_default_view',
				[
					'label'   => __('Default View', 'ectbe'),
					'type'    => Controls_Manager::SELECT,
					'options' => [
						'dayGridMonth' => __('Month', 'ectbe'),
						'timeGridDay'  => __('Day', 'ectbe'),
						'timeGridWeek' => __('Week', 'ectbe'),						
						'listMonth'    => __('List', 'ectbe'),
					],
					'default' => 'dayGridMonth',
					'condition' => [
						'ectbe_layout' => 'calendar',
					],
				]
			);
			$this->add_control(
				'ectbe_calendar_first_day',
				[
					'label'   => __('First Day of Week', 'ectbe'),
					'type'    => Controls_Manager::SELECT,
					'options' => [
						'0' => __('Sunday', 'ectbe'),
						'1' => __('Monday', 'ectbe'),
						'2' => __('Tuesday', 'ectbe'),
						'3' => __('Wednesday', 'ectbe'),
						'4' => __('Thursday', 'ectbe'),
						'5' => __('Friday', 'ectbe'),
						'6' => __('Saturday', 'ectbe'),
					],
					'default' => '0',
					'condition' => [
						'ectbe_layout' => 'calendar',
					],
				]
			);
			$this->add_control(
				'ectbe_hide_read_more_link',
				[
					'label'        => __('Hide Read More Link', 'ectbe'),
					'type'         => Controls_Manager::SWITCHER,
					'label_block'  => false,
					'return_value' => 'yes',
					'description'  => __('Hide Read More link in event popup','ectbe'),
					'condition' => [
						'ectbe_layout' => 'calendar',
					],
				]
			);
			$this->add_control(
				'ectbe_calendar_bg_color',
				[
					'label'     => __('Background Color', 'ectbe'),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#5725ff',
					'description'  => __('Background Color of Multidays Event','ectbe'),	
					'condition' => [
						'ectbe_layout' => 'calendar',
					],				
				]
				
			);
			$this->add_control(
				'ectbe_calendar_text_color',
				[
					'label'     => __('Text Color', 'ectbe'),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#ffffff',	
					'condition' => [
						'ectbe_layout' => 'calendar',
					],				
				]
			);				
			$this->add_control(
				'ectbe_date_formats',
				[
					'label' => __( 'Date Format', 'ectbe' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'default',
					'options' => [
			 			'default' => __( 'Default (01 January 2019)', 'ectbe' ),
						'MD,Y' => __( 'Md,Y (Jan 01, 2019)', 'ectbe' ),
						'FD,Y' => __( 'Fd,Y (January 01, 2019)', 'ectbe' ),
						'DM' => __( 'dM (01 Jan))', 'ectbe' ),
						'DML' => __( 'dMl (01 Jan Monday)', 'ectbe' ),
						'DF' => __( 'dF (01 January)', 'ectbe' ),
						'MD' => __( 'Md (Jan 01)', 'ectbe' ),
						'MD,YT' => __( 'Md,YT (Jan 01, 2019 8:00am-5:00pm)', 'ectbe' ),
						'full' => __( 'Full (01 January 2019 8:00am-5:00pm)', 'ectbe' ),
						'jMl' => __( 'jMl', 'ectbe' ),
						'd.FY' => __( 'd.FY (01. January 2019)', 'ectbe' ),
						'd.F' => __( 'd.F (01. January)', 'ectbe' ),
						'ldF' => __( 'ldF (Monday 01 January)', 'ectbe' ),
						'Mdl' => __( 'Mdl (Jan 01 Monday)', 'ectbe' ),
						'd.Ml' => __( 'd.Ml (01. Jan Monday)', 'ectbe' ),
						'dFT' => __( 'dFT (01 January 8:00am-5:00pm)', 'ectbe' ),
					],
					'condition' => [
						'ectbe_layout!' => [
							'calendar',
							'minimal-list',
					],
					],
				]
			);
			$this->add_control(
					'ectbe_order',
					[
						'label' => __( 'Events Order', 'ectbe' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'ASC',
						'options' => [
							'ASC' => __( 'ASC', 'ectbe' ),
							'DESC' => __( 'DESC', 'ectbe' ),
						],
						'condition' => [
							'ectbe_layout!' => 'calendar',
						],
					]
				); 
				$this->add_control(
					'ectbe_venue',
					[
						'label' => __( 'Hide Venue', 'ectbe' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'no',
						'options' => [
							'no' => __( 'NO', 'ectbe' ),
							'yes' => __( 'Yes', 'ectbe' ),
						],
						'condition'   => [
							'ectbe_layout!'   => [
								'calendar',
								'minimal-list'
							],
						
						],
						
						
						]
				);
				$this->add_control(
					'ectbe_display_desc',
					[
						'label' => __( 'Display Description', 'ectbe' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'yes',
						'options' => [
							'yes' => __( 'Yes', 'ectbe' ),
							'no' => __( 'NO', 'ectbe' ),
						],
						'condition' => [
							'ectbe_layout!' => [
								'calendar',
								'minimal-list'
							],
						],
					]
				);
				$this->add_control(
					'ectbe_display_cate',
					[
						'label' => __( 'Display Categoery', 'ectbe' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'yes',
						'options' => [
							'yes' => __( 'Yes', 'ectbe' ),
							'no' => __( 'NO', 'ectbe' ),
						],
						'condition' => [
							'ectbe_layout!' => [
								'calendar',
								 'minimal-list',
						],
						],
					]
				);
		   	$this->end_controls_section();
			$this->start_controls_section(
				'ectbe_style_section',
				[
					'label' => __( 'Color & Typography Settings', 'ectbe' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					'condition' => [
						'ectbe_layout!' => 'calendar',
					],
				]
			);
			$this->add_control(
				'ectbe_main_skin_section',
				[
					'label' => __( 'Main Skin', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'condition' => [
						'ectbe_layout!' => 'minimal-list',
					],
				]
			);
			$this->add_control(
				'ectbe_main_skin_color',
				[
					'label' => __( 'Color', 'ectbe' ),
					'type' => Controls_Manager::COLOR,
					// 'scheme' => [
					// 	'type' => Scheme_Color::get_type(),
					// 	'value' => Scheme_Color::COLOR_2,
					// ],
					'condition' => [
					 	'ectbe_layout!' => 'minimal-list',
					],
					'selectors' => [
						'{{WRAPPER}} #ectbe-events-list-content .style-1.ectbe-simple-event .ectbe-clslist-event-date' => 'background-color: {{ectbe_main_skin_color}}',
						'{{WRAPPER}} #ectbe-events-list-content .style-1.ectbe-simple-event .ectbe-clslist-event-details a:hover' => 'background-color: {{ectbe_main_skin_color}}',
						'{{WRAPPER}} #ectbe-events-list-content .ectbe-list-posts.style-2.ectbe-simple-event .ectbe-event-details' => 'border-left: 3px solid{{ectbe_main_skin_color}}',
						'{{WRAPPER}} .ectbe-simple-event .ectbe-event-datetimes span'=> 'color: {{ectbe_date_color}}',
						'{{WRAPPER}} .ectbe-simple-event .ectbe-event-datetimes span'=> 'color: {{ectbe_main_skin_color}}',
						'{{WRAPPER}} #ectbe-events-list-content .ect-month-header.ectbe-simple-event .ectbe-header-year' => 'color:{{ectbe_main_skin_color}}',
						'{{WRAPPER}} #ectbe-events-list-content .ect-month-header.ectbe-simple-event:after' => 'background-color:{{ectbe_main_skin_color}}',
					],
					// 'default' => '#dbf5ff',
				]
			);
			$this->add_control(
				'ectbe_featured_skin_section',
				[
					'label' => __( 'Featured Event', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			$this->add_control(
				'ectbe_featured_skin_color',
				[
					'label' => __( 'Skin Color', 'ectbe' ),
					'type' => Controls_Manager::COLOR,
					// 'scheme' => [
					// 	'type' => Scheme_Color::get_type(),
					// 	'value' => Scheme_Color::COLOR_2,
					// ],
					'selectors' => [
						'{{WRAPPER}} #ectbe-events-list-content .style-1.ectbe-featured-event .ectbe-clslist-event-date,{{WRAPPER}} #ectbe-events-list-content .style-1.ectbe-featured-event .ectbe-clslist-event-details a:hover' => 'background-color: {{ectbe_featured_skin_color}}',
						'{{WRAPPER}} #ect-events-minimal-list-content .ectbe-list-posts.style-1.ectbe-featured-event,
						{{WRAPPER}} #ectbe-events-list-content .ectbe-list-posts.style-2.ectbe-featured-event .ectbe-event-details' => 'border-left: 3px solid{{ectbe_featured_skin_color}}',
						
					],
					// 'default' => '#f19e59',
					
				]
			);
			
			$this->add_control(
				'ectbe_featured_font_color',
				[
					'label' => __( 'Font Color', 'ectbe' ),
					'type' => Controls_Manager::COLOR,
					// 'scheme' => [
					// 	'type' => Scheme_Color::get_type(),
					// 	'value' => Scheme_Color::COLOR_2,
					// ],
				
					'selectors' => [
						'{{WRAPPER}} #ectbe-events-list-content .style-1.ectbe-featured-event .ectbe-clslist-event-details a' => 'color: {{ectbe_featured_font_color}}',
						'{{WRAPPER}} #ectbe-events-list-content .style-1.ectbe-featured-event .ectbe-clslist-event-date' => 'color: {{ectbe_featured_font_color}}',
						'{{WRAPPER}} .ectbe-featured-event .ectbe-event-datetimes' => 'color: {{ectbe_featured_font_color}}',	
						'{{WRAPPER}} .ect-month-header.ectbe-featured-event' => 'color:{{ectbe_featured_font_color}}',
						'{{WRAPPER}} .ect-month-header.ectbe-featured-event:after' => 'background-color:{{ectbe_featured_font_color}}',

						 
					],
					// 'default' => '#3a2201',
					
					
				]
			);
			$this->add_control(
				'ectbe_bg_color_section',
				[
					'label' => __( 'Event Background ', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'ectbe_layout!' => 'minimal-list',
					],
				]
			);
			$this->add_control(
				'ectbe_event_bgcolor',
				[
					'label' => __( 'Color', 'ectbe' ),
					'type' => Controls_Manager::COLOR,
					// 'scheme' => [
					// 	'type' => Scheme_Color::get_type(),
					// 	'value' => Scheme_Color::COLOR_2,
					// ],
					'condition' => [
						'ectbe_layout!' => 'minimal-list',
					],
					'selectors' => [
						'{{WRAPPER}} #ectbe-events-list-content .style-1 .ectbe-clslist-event-info' => 'background-color:{{ectbe_event_bgcolor}}',						
						'{{WRAPPER}} #ectbe-events-list-content .style-1 .ectbe-clslist-event-details' => 'background-color:{{ectbe_event_bgcolor}}',
					],
					// 'default' => '#f4fcff',
					
				]
			);
			$this->add_control(
				'ectbe_date_section',
				[
					'label' => __( 'Event Date', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					
				]
			);
			$this->add_control(
				'ectbe_date_color',
				[
					'label' => __( 'Color', 'ectbe' ),
					'type' => Controls_Manager::COLOR,
					// 'scheme' => [
					// 	'type' => Scheme_Color::get_type(),
					// 	'value' => Scheme_Color::COLOR_2,
					// ],
					'selectors' => [
						'{{WRAPPER}} #ectbe-events-list-content .style-1.ectbe-simple-event .ectbe-clslist-event-date' => 'color: {{ectbe_date_color}}',
						'{{WRAPPER}} .ectbe-event-datetime,#ect-events-minimal-list-content .ectbe-simple-event .ectbe-event-datetimes span' => 'color: {{ectbe_date_color}}',
						
					
					],
					// 'default' => '#00445e',
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'ectbe_date_typography',
					'label' => __( 'Typography', 'ectbe' ),
					// 'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => 
						'{{WRAPPER}} #ectbe-events-list-content .style-1 .ectbe-date-area,
						{{WRAPPER}} .ectbe-event-datetime span',
					'fields_options' => [
						// first mimic the click on Typography edit icon
						'typography' => ['default' => 'yes'],
						// then redifine the Elementor defaults
						'font_size' => ['default' => [ 'unit' => 'px', 'size' => '' ]],
						'font_weight' => ['default' => 600]
					],
					// 'separator' => 'before',
				]
			);
			/*---- Date / Custom Label ----*/
			$this->add_control(
				'ectbe_title_section',
				[
					'label' => __( 'Event Title ', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					
				]
			);
			$this->add_control(
				'ectbe_title_color',
				[
					'label' => __( 'Color', 'ectbe' ),
					'type' => Controls_Manager::COLOR,
					// 'scheme' => [
					// 	'type' => Scheme_Color::get_type(),
					// 	'value' => Scheme_Color::COLOR_2,
					// ],
					'selectors' => [
						'{{WRAPPER}} #ectbe-events-list-content .style-1 h2.ectbe-list-title' => 'color: {{ectbe_title_color}}',
						'{{WRAPPER}} .ectbe-events-title' => 'color: {{ectbe_title_color}}',
						'{{WRAPPER}} #ectbe-events-list-content .ectbe-list-cost' => 'color: {{ectbe_title_color}}',
					],
					// 'default' => '#00445e',
					
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'ectbe_title_typography',
					'label' => __( 'Typography', 'ectbe' ),
					// 'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => 
						'{{WRAPPER}} #ectbe-events-list-content .style-1 h2.ectbe-list-title,
						{{WRAPPER}} #ectbe-events-list-content .ectbe-list-posts .ectbe-events-title,	
						{{WRAPPER}} #ect-events-minimal-list-content .ectbe-list-posts .ectbe-events-title',					
					'fields_options' => [
						// first mimic the click on Typography edit icon
						'typography' => ['default' => 'yes'],
						// then redifine the Elementor defaults
						'font_size' => ['default' => [ 'unit' => 'px', 'size' => 16 ]],
						'font_weight' => ['default' => 'bold']
					],
					'separator' => 'before',
				]
			);
			$this->add_control(
				'ectbe_desc_section',
				[
					'label' => __( 'Event Description', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'ectbe_layout!' => 'minimal-list',
					],
					
				]
			);
			$this->add_control(
				'ectbe_desc_color',
				[
					'label' => __( 'Color', 'ectbe' ),
					'type' => Controls_Manager::COLOR,
					// 'scheme' => [
					// 	'type' => Scheme_Color::get_type(),
					// 	'value' => Scheme_Color::COLOR_2,
					// ],
					'condition' => [
						'ectbe_layout!' => 'minimal-list',
					],
					'selectors' => [
						'{{WRAPPER}} #ectbe-events-list-content .style-1 .ectbe-style1-desc' => 'color: {{ectbe_desc_color}}',
						'{{WRAPPER}} #ectbe-events-list-content .style-1 .ectbe-clslist-time' => 'color: {{ectbe_desc_color}}',
						'{{WRAPPER}} #ectbe-events-list-content  .ectbe-minimal-list-desc' => 'color: {{ectbe_desc_color}}',
						'{{WRAPPER}} #ect-events-minimal-list-content .ect-minimal-list-wrapper .ectbe-minimal-list-desc' => 'color: {{ectbe_desc_color}}',
					],
					// 'default' => '#515d64',
					
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'ectbe_desc_typography',
					'label' => __( 'Typography', 'ectbe' ),
					// 'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					 'condition' => [
					 	'ectbe_layout!' => 'minimal-list',
					 ],
				
					'selectors' =>[
						'{{WRAPPER}} #ectbe-events-list-content .style-1 .ectbe-style1-desc,
						{{WRAPPER}} #ectbe-events-list-content .style-1 .ectbe-clslist-time,
						{{WRAPPER}} #ectbe-events-list-content .style-1 .ectbe-clslist-time span,
						{{WRAPPER}} #ectbe-events-list-contentt .ectbe-minimal-list-desc,
						{{WRAPPER}} #ect-events-minimal-list-content .ect-minimal-list-wrapper .ectbe-minimal-list-desc',
					],
					'fields_options' => [
						// first mimic the click on Typography edit icon
						'typography' => ['default' => 'yes'],
						// then redifine the Elementor defaults
						'font_size' => ['default' => [ 'unit' => 'px', 'size' => 15 ]],
						'font_weight' => ['default' => 'normal']
					],
					'separator' => 'before',
				]
			);
			$this->add_control(
				'ectbe_venue_section',
				[
					'label' => __( 'Event Venue', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'ectbe_layout!' => 'minimal-list',
					],
				]
			);
			$this->add_control(
				'ectbe_venue_color',
				[
					'label' => __( 'Color', 'ectbe' ),
					'type' => Controls_Manager::COLOR,
					// 'scheme' => [
					// 	'type' => Scheme_Color::get_type(),
					// 	'value' => Scheme_Color::COLOR_2,
					// ],
					'condition' => [
						'ectbe_layout!' => 'minimal-list',
					],
					'selectors' => [
						'{{WRAPPER}} #ectbe-events-list-content .style-1 .ectbe-list-venue' => 'color: {{ectbe_venue_color}}',
						'{{WRAPPER}} #ectbe-events-list-content .style-1 .ectbe-list-venue .ectbe-google a' => 'color: {{ectbe_venue_color}}',
						'{{WRAPPER}} #ectbe-events-list-content .style-1 .ectbe-rate-area' => 'color: {{ectbe_venue_color}}',
						'{{WRAPPER}} #ectbe-events-list-content .style-2 span.ectbe-venue-details.ectbe-address'=> 'color: {{ectbe_venue_color}}',
						'{{WRAPPER}} #ectbe-events-list-content .style-2 .ectbe-list-venue'=> 'color: {{ectbe_venue_color}}',
						'{{WRAPPER}} #ectbe-events-list-content .style-2 span.ectbe-google a'=> 'color: {{ectbe_venue_color}}',
					],
					// 'default' => '#00445e',
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'ectbe_venue_typography',
					'label' => __( 'Typography', 'ectbe' ),
					// 'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					 'condition' => [
					 	'ectbe_layout!' => 'minimal-list',
					 ],
					'selector' => '{{WRAPPER}} #ectbe-events-list-content .style-1 .ectbe-list-venue span,
				{{WRAPPER}} #ectbe-events-list-content .style-2 span.ectbe-venue-details.ectbe-address,
				{{WRAPPER}} #ectbe-events-list-content .style-2 .ectbe-list-venue',
					'fields_options' => [
						// first mimic the click on Typography edit icon
						'typography' => ['default' => 'yes'],
						// then redifine the Elementor defaults
						'font_size' => ['default' => [ 'unit' => 'px', 'size' => 15 ]],
						'font_weight' => ['default' => 'normal']
					],
					'separator' => 'before',
				]
				
			);
			
		
		$this->add_control(
			'ectbe_read_more_section',
			[
				'label' => __( 'Read More', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				
			]
		);
			$this->add_control(
				'ectbe_read_more_color',
				[
					'label' => __( 'Color', 'ectbe' ),
					'type' => Controls_Manager::COLOR,
					// 'scheme' => [
					// 	'type' => Scheme_Color::get_type(),
					// 	'value' => Scheme_Color::COLOR_2,
					// ],
					'selectors' => [
						'{{WRAPPER}} #ectbe-events-list-content .style-1.ectbe-simple-event .ectbe-clslist-event-details a' => 'color: {{ectbe_read_more_color}}',	
						'{{WRAPPER}} #ect-events-minimal-list-content .ectbe-style-1-more a,
						{{WRAPPER}} #ectbe-events-list-content .ectbe-style-2-more a' => 'color: {{ectbe_read_more_color}}',					
					],
					// 'default' => '#007bff',
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'ectbe_read_more_typography',
					'label' => __( 'Typography', 'ectbe' ),
					//'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' =>'{{WRAPPER}} #ectbe-events-list-content .style-1 .ectbe-clslist-event-details a,
					{{WRAPPER}} #ect-events-minimal-list-content .ectbe-style-1-more a,
					{{WRAPPER}} #ectbe-events-list-content .ectbe-style-2-more a',
					'fields_options' => [
						// first mimic the click on Typography edit icon
						'typography' => ['default' => 'yes'],
						// then redifine the Elementor defaults
						'font_size' => ['default' => [ 'unit' => 'px', 'size' => 15 ]],
						'font_weight' => ['default' => 'normal']
					],
					'separator' => 'before',
				]
			);
			$this->end_controls_section();
			$this->start_controls_section(
				'ectbe_advanced_section',
				[
					'label' => __( 'Extra Info', 'ectbe' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);
			$this->add_control(
				'ectbe_pro_features_1',
				[
					'label' => __( '', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::RAW_HTML,
					'raw' => '<div class="ectbe-pro-features">
								<a href="https://eventscalendartemplates.com/the-events-calendar-widgets-for-elementor/" target="_blank">'.__('View Demo','twae').'</a>
							</div>',
					'content_classes' => 'ectbe-pro-features-list',
				]
			);		
			$this->add_control(
				'ectbe_pro_features_2',
				[
					'label' => __( '', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::RAW_HTML,
					'raw' => '<div class="ectbe-review-list" style="
						letter-spacing: 0px;
						line-height: 20px;
						background: #dddd;
						padding: 15px;
					">Thanks for using <b>The Events Calendar Widgets For Elementor</b> WordPress plugin.<br> We hope it meets your expectations!<br>
							Please give us a quick rating, it works as a boost for us <br>
							<strong><a class="button button-primary" href="https://wordpress.org/support/plugin/events-widgets-for-elementor-and-the-events-calendar/reviews/#new-post" target="_blank">Share Review</a></strong><br/>	
							</div>',
					'content_classes' => 'ectbe-review-list',
				]
			);
			$this->end_controls_section();
		}
	// for frontend
	protected function render() {
     	$settings = $this->get_settings_for_display();
		$layout = $settings['ectbe_layout'];
		$fetchevnts = $settings['ectbe_event_source'];		
        $rangeStart= $settings['ectbe_date_range_start'];
		$rangeEnd =  $settings['ectbe_date_range_end'];
		$max_events = $settings['ectbe_max_events'];
		$ev_category = $settings['ectbe_ev_category'];
		$local = get_bloginfo("language");
        $default_view = $settings['ectbe_calendar_default_view'];        
		$details_link = $settings['ectbe_hide_read_more_link'];
		$textColor   = $settings['ectbe_calendar_text_color'];
		$color       = $settings['ectbe_calendar_bg_color'];
		$ectbe_venue       = $settings['ectbe_venue'];
		$daterange = '';
		if($fetchevnts == "date_range"){
			$daterange ='yes';
		}
		$events_html = '';
		$event_output='';
		$ectbe_cost = '';
		$evt_desc='';
		$display_cate = $settings['ectbe_display_cate'];
		$display_desc = $settings['ectbe_display_desc'];
		$display_year='';
		$style = isset($settings['ectbe_styles'])?$settings['ectbe_styles']:'style-1';
		if($layout!='calendar'){
			$all_events = ectbe_get_the_events_calendar_events($settings);
		}
		if($layout=='calendar'){
			require ECTBE_PATH . 'widgets/layouts/ectbe-'.$layout.'.php';
		}
		else{
		if($layout=='list'){
			global $post;
			$event_output .='<!=========Events Default List Template '.ECTBE_VERSION.'=========>';
			if ($all_events && class_exists( 'Tribe__Events__JSON_LD__Event' )){
				$event_output .= Tribe__Events__JSON_LD__Event::instance()->get_markup($post);
			}
			$event_output .= '<div id="ectbe-events-list-content" class="ectt-list-wrapper"><div id="list-wrp" class="ectbe-list-wrapper all">';
		}
		else if($layout=='minimal-list'){
			global $post;
			$event_output .='<!=========Events Minimal List Template '.ECTBE_VERSION.'=========>';
			if ($all_events && class_exists( 'Tribe__Events__JSON_LD__Event' )){
				$event_output .= Tribe__Events__JSON_LD__Event::instance()->get_markup($post);
			}
			$event_output .='<div id="ect-events-minimal-list-content" class="ectt-simple-list-wrapper">';
			$event_output .='<div id="ect-minimal-list-wrp-'.rand(1,10000).'" class="ect-minimal-list-wrapper">';
		}
		if(!empty($all_events)){
			foreach( $all_events as $event){
				$event_id=$event['id'];
				$event_title=$event['title'];
				$event_schedule=$event['event_schedule'];
				$ev_time=$event['ev_time'];
				$url=$event['url'];
				$allDay=$event['allDay'];
				$description=$event['description'];
				$eventimgurl=$event['eventimgurl'];
				$eventcost=$event['eventcost'];
				$template = '';
				$date_format = '';
				// $ev_cost = '';
				$ectbe_cate = ectbe_display_category($event_id);
				$venue_details_html = '';
				$event_type = tribe( 'tec.featured_events' )->is_featured( $event_id ) ? 'ectbe-featured-event' : 'ectbe-simple-event';
				 if ( tribe_get_cost($event_id) ) : 
					$ev_cost ='<div class="ectbe-rate-area" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
					<span class="ectbe-icon"><i class="ectbe-icon-ticket" aria-hidden="true"></i></span>
					<span class="ectbe-rate" itemprop="price" content="'.tribe_get_cost($event_id, false ).'">'.tribe_get_cost($event_id, true ).'</span>
					<meta itemprop="priceCurrency" content="'.tribe_get_event_meta( $event_id, '_EventCurrencySymbol', true ).'" />
					</div>';
				 endif;
				//Address
				$venue_details = tribe_get_venue_details($event_id);
				$venue_details_html = '<span class="ectbe-icon"><i class="ectbe-icon-location" aria-hidden="true"></i></span>
				<!-- Venue Display Info -->
					<span class="ectbe-venue-details ectbe-address" itemprop="location" itemscope itemtype="http://schema.org/Place">
					<meta itemprop="name" content="'.tribe_get_venue($event_id).'">
					<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
					<meta itemprop="name" content="'.tribe_get_venue($event_id).'">
					'. implode(',', $venue_details ).'
					</div></span>';
				$ev_post_img=ectbe_get_event_image($event_id,$size='large');
				$event_address = (!empty( $venue_details['address'] ) ) ?$venue_details['address'] : '';
				$evt_desc ='<div class="ect-event-content" itemprop="description" content="'.esc_attr(wp_strip_all_tags( tribe_events_get_the_excerpt($event_id), true )).'">'.tribe_events_get_the_excerpt($event_id, wp_kses_allowed_html( 'post' ) ).'</div>';
				$ectbe_cost = '<div class="ectbe-rate-area" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <span class="ectbe-icon"><i class="ectbe-icon-ticket" aria-hidden="true"></i></span>
                <span class="ectbe-rate" itemprop="price" content="'.tribe_get_cost($event_id, false ).'">'.tribe_get_cost($event_id, true ).'</span>
                <meta itemprop="priceCurrency" content="'.tribe_get_event_meta( $event_id, '_EventCurrencySymbol', true ).'" />
                </div>';
				require ECTBE_PATH . 'widgets/layouts/ectbe-'.$layout.'.php';		
			}
		}
		else{
			$event_output .= '<h3>'.__("There is no Event","ectbe").'</h3>';
		}
		$event_output .= $events_html;
		$event_output .= '</div></div>';
		echo $event_output;
	}	
	}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ECTBE_Widget() );

