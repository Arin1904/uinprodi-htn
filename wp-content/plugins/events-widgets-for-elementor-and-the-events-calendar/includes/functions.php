<?php
function ectbe_get_tags($args = array())
    {
        $options = [];
        $tags = get_tags($args);
        $options['all'] = 'All';
        if (is_wp_error($tags)) {
            return [];
        }
        
        foreach ($tags as $tag) {
          
            $options[$tag->term_id] = $tag->name;
           
        }

        return $options;
}	
	
function ectbe_get_the_events_calendar_events ($settings) {
    
        if (!function_exists('tribe_get_events')) {
            return [];
        }
    $meta_date_compare = '>=';
        if ($settings['ectbe_type']=='past') {
            $meta_date_compare = '<';
        }
        else if($settings['ectbe_type']=='all'){
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
        $ect_args=apply_filters( 'ectbe_args_filter', array(
            'post_status' => 'publish',
            'posts_per_page' => $settings['ectbe_max_events'],
            'meta_key' => $attribute['key'],
             'orderby' => 'event_date',
            'order' =>  $settings['ectbe_order'],
           // 'featured'=>$featured_only,
            'meta_query' =>$attribute['meta_date'],
        ), $attribute, $meta_date_date, $meta_date_compare );
        
        if (!empty($settings['ectbe_ev_category'])) {
           if(!in_array('all',$settings['ectbe_ev_category'])){ 
               $ect_args['tax_query'] = [
                    [
                        'taxonomy' => 'tribe_events_cat', 'field' => 'id',
                        'terms'    => $settings['ectbe_ev_category']
                    ]
                ];
            }
        } 

            if (!empty( $settings['ectbe_date_range_start'])) {
                $newStartDate = date("Y-m-d", strtotime($settings['ectbe_date_range_start']));  
                $ect_args['start_date'] = $newStartDate;
            }
            if (!empty( $settings['ectbe_date_range_end'])) {
                $newEndDate = date("Y-m-d", strtotime($settings['ectbe_date_range_end']));
                $ect_args['end_date'] =  $newEndDate;
            }
           
        $date_format = $settings['ectbe_date_formats'];
        $events = tribe_get_events($ect_args);
       if (empty($events)) {
            return [];
        }
        $calendar_data = [];
        foreach ($events as $key => $event) {
           // $date_format = 'd M y';
            $all_day = 'yes';
            if (!tribe_event_is_all_day($event->ID)) {
               // $date_format .= ' H:i';
                $all_day = '';
            } 
            $description = mb_strimwidth($event->post_content ,0,150,"...");
            $imgurl = ectbe_get_event_image($event->ID);
            $eventCost = tribe_get_cost($event->ID,true);
            $template = '';
            $event_schedule= ectbe_event_schedule($event->ID,$date_format,$template);
            $ev_time= ectbe_tribe_event_time($event->ID,false);

            $calendar_data[] = [
                'id'          => $event->ID,
                'title'       => !empty($event->post_title) ? $event->post_title : __('No Title',
                    'ectbe'),                       
                
                'start'       => tribe_get_start_date($event->ID, true, $date_format),
                'end'         => tribe_get_end_date($event->ID, true, $date_format),
                //'borderColor' => !empty($settings['ectbe_event_global_popup_ribbon_color']) ? $settings['ectbe_event_global_popup_ribbon_color'] : '#10ecab',
                'textColor'   => $settings['ectbe_calendar_text_color'],
                'color'       => $settings['ectbe_calendar_bg_color'],
                'url'         => ($settings['ectbe_hide_read_more_link']!=='yes')?get_the_permalink($event->ID):'',
                'allDay'      => $all_day,
                'description' => $description,       
                'eventimgurl'      => $imgurl,
                'eventcost'   =>  $eventCost,
                'event_schedule' => $event_schedule,
                'ev_time' => $ev_time,
              
            ];
        }
        return $calendar_data;
    }

    // get event featured image url
    function ectbe_get_event_image($event_id){
        $ev_post_img='';
        $feat_img_url = wp_get_attachment_image_src(get_post_thumbnail_id($event_id),'large');
        if(!empty($feat_img_url) && $feat_img_url[0] !=false){
            $ev_post_img = $feat_img_url[0];
        }
       
        return $ev_post_img;
    }
    

    function ectbe_display_category($event_id){
        $ectbe_cate = '';
        $ectbe_cate = get_the_term_list($event_id, 'tribe_events_cat', '<ul class="tribe_events_cat"><li>', '</li><li>', '</li></ul>' );
        
        return $ectbe_cate;
    }
    
		// generate events dates html
	 function ectbe_event_schedule($event_id,$date_format,$template){
            /*Date Format START*/
            $event_schedule='';
            $template = 'default';
            $ev_time= ectbe_tribe_event_time($event_id,false);
            if($date_format=="DM") {
                $event_schedule='<div class="ectbe-date-area '.$template.'-schedule"  itemprop="startDate" content="'.tribe_get_start_date($event_id, false, 'Y-m-dTg:i').'">
                                <span class="ev-day">'.tribe_get_start_date($event_id, false, 'd' ).'</span>
                                <span class="ev-mo">'.tribe_get_start_date($event_id, false, 'M' ).'</span>
                                </div>
                                <meta itemprop="endDate" content="'.tribe_get_end_date($event_id, false, 'Y-m-dTg:i').'">';
            }
            else if($date_format=="MD") {
                $event_schedule='<div class="ectbe-date-area '.$template.'-schedule"  itemprop="startDate" content="'.tribe_get_start_date($event_id, false, 'Y-m-dTg:i').'">
                                <span class="ev-mo">'.tribe_get_start_date($event_id, false, 'M' ).'</span>
                                <span class="ev-day">'.tribe_get_start_date($event_id, false, 'd' ).'</span>
                                </div>
                                <meta itemprop="endDate" content="'.tribe_get_end_date($event_id, false, 'Y-m-dTg:i').'">';
            }
            else if($date_format=="FD") {
                $event_schedule='<div class="ectbe-date-area '.$template.'-schedule"  itemprop="startDate" content="'.tribe_get_start_date($event_id, false, 'Y-m-dTg:i').'">
                                <span class="ev-mo">'.tribe_get_start_date($event_id, false, 'F' ).'</span>
                                <span class="ev-day">'.tribe_get_start_date($event_id, false, 'd' ).'</span>
                                </div>
                                <meta itemprop="endDate" content="'.tribe_get_end_date($event_id, false, 'Y-m-dTg:i').'">';
            }
            else if($date_format=="DF") {
                $event_schedule='<div class="ectbe-date-area '.$template.'-schedule"  itemprop="startDate" content="'.tribe_get_start_date($event_id, false, 'Y-m-dTg:i').'">
                                <span class="ev-day">'.tribe_get_start_date($event_id, false, 'd' ).'</span>
                                <span class="ev-mo">'.tribe_get_start_date($event_id, false, 'F' ).'</span>
                                </div>
                                <meta itemprop="endDate" content="'.tribe_get_end_date($event_id, false, 'Y-m-dTg:i').'">';
            }
            else if($date_format=="FD,Y") {
                $event_schedule='<div class="ectbe-date-area '.$template.'-schedule"  itemprop="startDate" content="'.tribe_get_start_date($event_id, false, 'Y-m-dTg:i').'">
                                <span class="ev-mo">'.tribe_get_start_date($event_id, false, 'F' ).'</span>
                                <span class="ev-day">'.tribe_get_start_date($event_id, false, 'd' ).', </span>
                                <span class="ev-yr">'.tribe_get_start_date($event_id, false, 'Y' ).'</span>
                                </div>
                                <meta itemprop="endDate" content="'.tribe_get_end_date($event_id, false, 'Y-m-dTg:i').'">';
            }
            else if($date_format=="MD,Y") {
                $event_schedule='<div class="ectbe-date-area '.$template.'-schedule"  itemprop="startDate" content="'.tribe_get_start_date($event_id, false, 'Y-m-dTg:i').'">
                                <span class="ev-mo">'.tribe_get_start_date($event_id, false, 'M' ).'</span>
                                <span class="ev-day">'.tribe_get_start_date($event_id, false, 'd' ).', </span>
                                <span class="ev-yr">'.tribe_get_start_date($event_id, false, 'Y' ).'</span>
                                </div>
                                <meta itemprop="endDate" content="'.tribe_get_end_date($event_id, false, 'Y-m-dTg:i').'">';
            }
            else if($date_format=="MD,YT") {
                $event_schedule='<div class="ectbe-date-area '.$template.'-schedule" itemprop="startDate" content="'.tribe_get_start_date($event_id, false, 'Y-m-dTg:i').'">
                                <span class="ev-mo">'.tribe_get_start_date($event_id, false, 'M' ).'</span>
                                <span class="ev-day">'.tribe_get_start_date($event_id, false, 'd' ).', </span>
                                <span class="ev-yr">'.tribe_get_start_date($event_id, false, 'Y' ).'</span>
                                <span class="ev-time"><span class="ectbe-icon"><i class="ectbe-icon-clock" aria-hidden="true"></i></span> '.$ev_time.'</span>
                                </div>
                                <meta itemprop="endDate" content="'.tribe_get_end_date($event_id, false, 'Y-m-dTg:i').'">';
            }
            else if($date_format=="jMl") {
                $event_schedule='<div class="ectbe-date-area '.$template.'-schedule" itemprop="startDate" content="'.tribe_get_start_date($event_id, false, 'Y-m-dTg:i').'">
                                <span class="ev-day">'.tribe_get_start_date($event_id, false, 'j' ).'</span>
                                <span class="ev-mo">'.tribe_get_start_date($event_id, false, 'M' ).'</span>
                                <span class="ev-weekday">'.tribe_get_start_date($event_id, false, 'l' ).'</span>
                                </div>
                                <meta itemprop="endDate" content="'.tribe_get_end_date($event_id, false, 'Y-m-dTg:i').'">';
            }
            else if($date_format=="full") {
                $event_schedule='<div class="ectbe-date-area '.esc_attr($template).'-schedule" itemprop="startDate" content="'.tribe_get_start_date($event_id, false, 'Y-m-dTg:i').'">
                                <span class="ev-day">'.tribe_get_start_date($event_id, false, 'd' ).'</span>
                                <span class="ev-mo">'.tribe_get_start_date($event_id, false, 'F' ).'</span>
                                <span class="ev-yr">'.tribe_get_start_date($event_id, false, 'Y' ).'</span>
                                <span class="ev-time"><span class="ectbe-icon"><i class="ectbe-icon-clock" aria-hidden="true"></i></span> '.$ev_time.'</span>
                                </div>
                                <meta itemprop="endDate" content="'.tribe_get_end_date($event_id, false, 'Y-m-dTg:i').'">';
            }
            else if($date_format=="d.FY") {
                $event_schedule='<div class="ectbe-date-area '.esc_attr($template).'-schedule" itemprop="startDate" content="'.tribe_get_start_date($event_id, false, 'Y-m-dTg:i').'">
                                <span class="ev-day">'.tribe_get_start_date($event_id, false, 'd' ).'. </span>
                                <span class="ev-mo">'.tribe_get_start_date($event_id, false, 'F' ).'</span>
                                <span class="ev-yr">'.tribe_get_start_date($event_id, false, 'Y' ).'</span>
                                </div>
                                <meta itemprop="endDate" content="'.tribe_get_end_date($event_id, false, 'Y-m-dTg:i').'">';
            }
            else if($date_format=="d.F") {
                $event_schedule='<div class="ectbe-date-area '.esc_attr($template).'-schedule" itemprop="startDate" content="'.tribe_get_start_date($event_id, false, 'Y-m-dTg:i').'">
                                <span class="ev-day">'.tribe_get_start_date($event_id, false, 'd' ).'. </span>
                                <span class="ev-mo">'.tribe_get_start_date($event_id, false, 'F' ).'</span>
                                </div>
                                <meta itemprop="endDate" content="'.tribe_get_end_date($event_id, false, 'Y-m-dTg:i').'">';
            }
            else if($date_format=="d.Ml") {
                $event_schedule='<div class="ectbe-date-area '.esc_attr($template).'-schedule" itemprop="startDate" content="'.tribe_get_start_date($event_id, false, 'Y-m-dTg:i').'">
                                <span class="ev-day">'.tribe_get_start_date($event_id, false, 'd' ).'. </span>
                                <span class="ev-mo">'.tribe_get_start_date($event_id, false, 'M' ).'</span>
                                <span class="ev-yr">'.tribe_get_start_date($event_id, false, 'l' ).'</span>
                                </div>
                                <meta itemprop="endDate" content="'.tribe_get_end_date($event_id, false, 'Y-m-dTg:i').'">';
            }
            else if($date_format=="ldF") {
                $event_schedule='<div class="ectbe-date-area '.esc_attr($template).'-schedule" itemprop="startDate" content="'.tribe_get_start_date($event_id, false, 'Y-m-dTg:i').'">
                                <span class="ev-day">'.tribe_get_start_date($event_id, false, 'l' ).'</span>
                                <span class="ev-mo">'.tribe_get_start_date($event_id, false, 'd' ).'</span>
                                <span class="ev-yr">'.tribe_get_start_date($event_id, false, 'F' ).'</span>
                                </div>
                                <meta itemprop="endDate" content="'.tribe_get_end_date($event_id, false, 'Y-m-dTg:i').'">';
            }
            else if($date_format=="Mdl") {
                $event_schedule='<div class="ectbe-date-area '.esc_attr($template).'-schedule" itemprop="startDate" content="'.tribe_get_start_date($event_id, false, 'Y-m-dTg:i').'">
                                <span class="ev-day">'.tribe_get_start_date($event_id, false, 'M' ).'</span>
                                <span class="ev-mo">'.tribe_get_start_date($event_id, false, 'd' ).'</span>
                                <span class="ev-yr">'.tribe_get_start_date($event_id, false, 'l' ).'</span>
                                </div>
                                <meta itemprop="endDate" content="'.tribe_get_end_date($event_id, false, 'Y-m-dTg:i').'">';
            }
            else if($date_format=="dFT") {
                $event_schedule='<div class="ectbe-date-area '.esc_attr($template).'-schedule" itemprop="startDate" content="'.tribe_get_start_date($event_id, false, 'Y-m-dTg:i').'">
                                <span class="ev-day">'.tribe_get_start_date($event_id, false, 'd' ).'</span>
                                <span class="ev-mo">'.tribe_get_start_date($event_id, false, 'F' ).'</span>
                                <span class="ev-time"><span class="ectbe-icon"><i class="ectbe-icon-clock" aria-hidden="true"></i></span> '.$ev_time.'</span>
                                </div>
                                <meta itemprop="endDate" content="'.tribe_get_end_date($event_id, false, 'Y-m-dTg:i').'">';
            }
            elseif($date_format=="custom"){
                $event_schedule = '<span class="ectbe-custom-schedule">'.tribe_events_event_schedule_details($event_id).'</span>';
                }
            else {
            
                $event_schedule='<div class="ectbe-date-area '.esc_attr($template).'-schedule" itemprop="startDate" content="'.tribe_get_start_date($event_id, false, 'Y-m-dTg:i').'">
                                <span class="ev-day">'.tribe_get_start_date($event_id, false, 'd' ).'</span>
                                <span class="ev-mo">'.tribe_get_start_date($event_id, false, 'F' ).'</span>
                                <span class="ev-yr">'.tribe_get_start_date($event_id, false, 'Y' ).'</span>
                                </div>
                                <meta itemprop="endDate" content="'.tribe_get_end_date($event_id, false, 'Y-m-dTg:i').'">';
            }
            /*Date Format END*/
            return $event_schedule;
    }

    // grab events time for later use
   function ectbe_tribe_event_time($post_id, $display = true ) {
        $event =$post_id;
        if ( tribe_event_is_all_day( $event ) ) { // all day event
            if ( $display ) {
                _e( 'All day', 'the-events-calendar' );
            }
            else {
                return __( 'All day', 'the-events-calendar' );
            }
        }
        elseif ( tribe_event_is_multiday( $event ) ) { // multi-date event
            $start_date = tribe_get_start_date(  $event, false, false );
            $end_date = tribe_get_end_date(  $event, false, false );
            if ( $display ) {
                printf( __( '%s - %s', 'ect' ), $start_date, $end_date );
            }
            else {
                return sprintf( __( '%s - %s', 'ect' ), $start_date, $end_date );
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