<?php

class EventsShortcode
{
    /**
     * @var array
     */
    private $options;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

     /**
     * Register all hooks 
     *
     */

    public static function registers()
    {
        $thisPlugin = new self();
     	/*** ECT main shortcode */
		add_shortcode('events-calendar-templates', array(  $thisPlugin,'ect_shortcodes'));
      
        require_once(ECT_PLUGIN_DIR.'includes/ect-styles.php');
        EctStyles::registers();
    }

    	/*** ECT main shortcode */	
    public function ect_shortcodes($atts){
            if ( !function_exists( 'tribe_get_events' ) ) {
             return;
         }
         global $wp_query, $post;
         global $more;
         $more = false;
     
         /*** Set shortcode default attributes */
         $attribute = shortcode_atts( apply_filters( 'ect_shortcode_atts', array(
             'template' => 'default',
             'style' => 'style-1',
             'category' => 'all',
             'date_format' => 'default',
             'start_date' => '',
             'end_date' => '',
             'time' => 'future',
             'order' => 'ASC',
             'limit' => '10',
             'hide-venue' => 'no',
             'event_tax' => '',
             'month' => '',
             'tags' => '',
             'icons' => '',
             'layout' => '',
             'title' => '',
             'design' => '',
             'socialshare' =>'',	
         ), $atts ), $atts);


         /*** Default var for later use */
         $output='';
         $events_html='';
         $template=isset($attribute['template'])?$attribute['template']:'default';
         $style=isset($attribute['style'])?$attribute['style']:'style-1';
         $enable_share_button=isset($attribute['socialshare'])?$attribute['socialshare']:'no';
         $time= $attribute['time'];
         /*** Load CSS styles based on template. */
         EctStyles::ect_load_requried_assets($template,$style);

         /*** create query args based on shortcode attributes */
         if($attribute['category']!="all"){
             if ( $attribute['category'] ) {
                 if ( strpos( $attribute['category'], "," ) !== false ) {
                     $attribute['category'] = explode( ",", $attribute['category'] );
                     $attribute['category'] = array_map( 'trim',$attribute['category'] );
                 } else {
                     $attribute['category'] = $attribute['category'];
                 }

                 $attribute['event_tax'] = array(
                     'relation' => 'OR',
                     array(
                         'taxonomy' => 'tribe_events_cat',
                         'field' => 'name',
                         'terms' =>$attribute['category'],
                     ),
                     array(
                         'taxonomy' => 'tribe_events_cat',
                         'field' => 'slug',
                         'terms' =>$attribute['category'],
                     )
                 );
             }
         }

         $prev_event_month='';
         $prev_event_year='';
         $meta_date_compare = '>=';
         $attribute['key']='_EventStartDate'; 
         if ($attribute['time']=='past') {
             $meta_date_compare = '<';
         }
         else if($attribute['time']=='all'){
             $meta_date_compare = '';
         }
         $attribute['key'] = '_EventStartDate' ;
         $attribute['meta_date'] = '';
         $meta_date_date = '';
         if($meta_date_compare!=''){
         $meta_date_date = current_time( 'Y-m-d H:i:s' );
         $attribute['key']='_EventStartDate';
         $attribute['meta_date'] = array(
             array(
                 'key' =>'_EventEndDate',
                 'value' => $meta_date_date,
                 'compare' => $meta_date_compare,
                 'type' => 'DATETIME'
             ));	
         }	
         // var_Dump($attribute['order']);
         /*** Fetch events based upon mentioned values */
         $ect_args = apply_filters( 'ect_args_filter', array(
             'post_status' => 'publish',
               'hide_upcoming' => true,
             'posts_per_page' => $attribute['limit'],
              'tax_query'=> $attribute['event_tax'],
             'meta_key' => $attribute['key'],
             'orderby' => 'event_date',
             'order' => $attribute['order'],
             'meta_query' =>$attribute['meta_date'],
         ), $attribute, $meta_date_date, $meta_date_compare ) ;
         
         if (!empty($attribute['start_date'])) {
             $ect_args['start_date'] =$attribute['start_date'];
          }
          if (!empty($attribute['end_date'])) {
             $ect_args['end_date'] =$attribute['end_date'];
          } 
          $all_events = tribe_get_events($ect_args);
         $i=0;
         if ($all_events) {		
             foreach( $all_events as $post ):setup_postdata( $post );
             $event_title='';
             $event_content='';
             $event_img='';
             $event_schedule='';
             $event_day='';
             $event_cost='';
             $event_venue='';			
             $events_date_header='';
             $no_events='';
             $event_type = tribe( 'tec.featured_events' )->is_featured( $post->ID ) ? 'ect-featured-event' : 'ect-simple-event';
             $event_id=$post->ID;
             
             $share_buttons='';
             if($enable_share_button=="yes"){
                 wp_enqueue_style('ect-sharebutton-css');
                 wp_enqueue_script('ect-sharebutton');
                 $share_buttons= ect_share_button($event_id);
             }
         
             /*** Event date headers for timeline template */
             $show_headers = apply_filters( 'tribe_events_list_show_date_headers', true );
             if ( $show_headers ) {
                 $event_year= tribe_get_start_date( $post, false, 'Y' );
                 $event_month= tribe_get_start_date( $post, false, 'm' );
                 $month_year_format= tribe_get_date_option( 'monthAndYearFormat', 'M Y' );
                 if ($prev_event_month != $event_month || ( $prev_event_month == $event_month && $prev_event_year != $event_year ) ) {		
                     $prev_event_month=$event_month;
                     $prev_event_year= $event_year;
                     $date_header= sprintf( "<span class='month-year-box'>%s</span>", tribe_get_start_date( $post, false,'M Y'));	
                     $events_date_header.='<!-- Month / Year Headers -->';
                     $events_date_header.=$date_header;	
                 }
             }

             /*** Event venue details */
             $venue_details_html='';
             $venue_details = tribe_get_venue_details();
             $has_venue_address = (!empty( $venue_details['address'] ) ) ? ' location' : '';				
             /*** Setup an array of venue details for use later in the template */
             if($attribute['hide-venue']!="yes") {
                 if($template=="classic-list" || $template=="modern-list" || $template=="default" || $template=="minimal-list") {
                     $venue_details_html.='<div class="ect-list-venue '.$template.'-venue">';
                 }
                 else {
                     $venue_details_html.='<div class="'.$template.'-venue">';
                 }

                 if (tribe_has_venue()) :
                 if(!empty($venue_details['address']) && isset($venue_details['linked_name'])){
                     $venue_details_html.='<span class="ect-icon"><i class="ect-icon-location"></i></span>';
                 }		
                 $venue_details_html.='<!-- Event Venue Info -->
                 <span class="ect-venue-details ect-address">
                 <div>';
                 $venue_details_html.=implode(',', $venue_details );
                 $venue_details_html.='</div>';
                 if ( tribe_get_map_link() ) {
                     $venue_details_html.='<span class="ect-google">'.tribe_get_map_link_html().'</span>';
                 }
                 $venue_details_html.='</span>';
                 endif ;

                 $venue_details_html.='</div>';
             }

             /*** Event Cost */
             if ( tribe_get_cost() ) : 
                 $event_cost='<!-- Event Ticket Price Info -->
                 <div class="ect-rate-area">
                 <span class="ect-icon"><i class="ect-icon-ticket"></i></span>
                 <span class="ect-rate">'.tribe_get_cost(null, true ).'</span>
                 </div>';
             endif;
             /*** event day */
             $event_day='<span class="event-day">'.tribe_get_start_date($event_id, true, 'l').'</span>';
             $ev_time=$this->ect_tribe_event_time(false);

             $event_schedule=ect_custom_date_formats($attribute['date_format'],$template,$event_id,$ev_time);

             // Organizer
             $organizer = tribe_get_organizer();

             /*** Event title */
             $event_title='<a class="ect-event-url" href="'.esc_url( tribe_get_event_link()).'" rel="bookmark">'. get_the_title().'</a>';
                 
             /*** Event description - content */
             $event_content='<!-- Event Content --><div class="ect-event-content">';
             $event_content.=tribe_events_get_the_excerpt($event_id, wp_kses_allowed_html( 'post' ) );
             $event_content.='<a href="'.esc_url( tribe_get_event_link($event_id) ).'" class="ect-events-read-more" rel="bookmark">'.esc_html__( 'Find out more', 'the-events-calendar' ).' &raquo;</a></div>';
             
         

             /*** Load templates based on shortcode */
             if(in_array($template,array("timeline","classic-timeline",'timeline-view'))) {
                 include(ECT_PLUGIN_DIR.'/templates/timeline/timeline.php');	
             }
             else if(in_array($template,array("default","classic-list",'modern-list'))) {
                 include(ECT_PLUGIN_DIR.'/templates/list/list.php');	
             }
             else if($template=="minimal-list"){
                 include(ECT_PLUGIN_DIR.'/templates/minimal-list/minimal-list.php');	
             }
             else {
                 include(ECT_PLUGIN_DIR.'/templates/list/list.php');	
             }
                 
             endforeach;
             wp_reset_postdata();
         }
         else { 
            $tect_settings = get_option('ects_options');
            $no_event_found_text = !empty($tect_settings['events_not_found'])?$tect_settings['events_not_found']:'';
            
            // $tect_settings = TitanFramework::getInstance( 'ect' );
           //  $no_event_found_text = $tect_settings->getOption( 'events_not_found' );
        //    var_dump($no_event_found_text);
             $not_found_msz='';
             if(!empty($no_event_found_text)){
                 $not_found_msz=filter_var($no_event_found_text,FILTER_SANITIZE_STRING);
             }else{
                 $not_found_msz='<div class="ect-no-events"><p>'.__('There are no upcoming events at this time.','ect').'</p></div>';
             }
             $no_events='<span class="ect-icon"><i class="ect-icon-bell"></i></span>'.$not_found_msz;
         } 


         $catCls=(is_array($attribute['category']))?implode(" ",$attribute['category']):$attribute['category'];
     
         /*** Generate output based on template */
         if($no_events){
             $output.='<div id="ect-no-events"><p>'.$no_events.'</p></div>';
         }
         else {
             if(in_array($template,array("timeline","classic-timeline",'timeline-view'))){
                 if($template=="timeline") {
                     $style='style-1';
                 }
                 else if($template=="classic-timeline") {
                     $style='style-2';
                 }
 
                 $output .='<!=========Events Timeline Template '.ECT_VERSION.'=========>';
                 $output .= '<div id="event-timeline-wrapper" class="'. $catCls.' '.$style.'">';
                 $output .= '<div class="cool-event-timeline">';
                 $output .=$events_html;
                 $output .= '</div></div>';
             }
             else if($template=="minimal-list"){
                 $output .='<!=========Events Static list Template '.ECT_VERSION.'=========>';
                 $output.='<div id="ect-events-minimal-list-content">';
                 $output.='<div id="ect-minimal-list-wrp" class="ect-minimal-list-wrapper '. $catCls.'">';
                 $output.=$events_html;
                 $output.='</div></div>';	
             }
             else {	
                 $output .='<!=========Events list Template '.ECT_VERSION.'=========>';
                 $output.='<div id="ect-events-list-content">';
                 $output.='<div id="list-wrp" class="ect-list-wrapper '. $catCls.'">';
                 $output.=$events_html;
                 $output.='</div></div>';		
             }
         }

         return $output;
     }






      
		// get events dates and time
		public function ect_tribe_event_time( $display = true ) {
			global $post;
			$event = $post;
			if ( tribe_event_is_multiday( $event ) ) { // multi-date event
				$start_date = tribe_get_start_date( null, false );
				$end_date = tribe_get_end_date( null, false );
				if ( $display ) {
					printf( __( '%s - %s', 'ect' ), $start_date, $end_date );
				}
				else {
					return sprintf( __( '%s - %s', 'ect' ), $start_date, $end_date );
				}
			}
			elseif ( tribe_event_is_all_day( $event ) ) { // all day event
				if ( $display ) {
					_e( 'All day', 'the-events-calendar' );
				}
				else {
					return __( 'All day', 'the-events-calendar' );
				}
			}
			else {
				$time_format = get_option( 'time_format' );
				$start_date = tribe_get_start_date( $event, false, $time_format );
				$end_date = tribe_get_end_date( $event, false, $time_format );
				if ( $start_date !== $end_date ) {
					if ( $display ) {
						printf( __( '%s - %s', 'ect' ), $start_date, $end_date );
					}
					else {
						return sprintf( __( '%s - %s', 'ect' ), $start_date, $end_date );
					}
				}
				else {
					if ( $display ) {
						printf( '%s', $start_date );
					}
					else {
						return sprintf( '%s', $start_date );
					}
				}
			}
		}

		// check event recurring event
		public function ect_tribe_event_recurringinfo( $before = '', $after = '', $link_all = true ) {
			if ( !function_exists('tribe_is_recurring_event') ) {
				return false;
			}
			global $post;
			$info = '';
			if ( tribe_is_recurring_event( $post->ID ) ) {
				if ( function_exists( 'tribe_get_recurrence_text' ) ) {
					$info .= tribe_get_recurrence_text( $post->ID );
				}
				if ( $link_all && function_exists( 'tribe_all_occurences_link' ) ) {
					$info .= sprintf( ' <a href="%s">%s</a>', esc_url( tribe_all_occurences_link( $post->ID, false ) ), __( '(See All)', 'ect' ) );
				}
			}
			if ( $info ) {
				$info = $before.$info.$after;
			}
			return $info;
		}

}