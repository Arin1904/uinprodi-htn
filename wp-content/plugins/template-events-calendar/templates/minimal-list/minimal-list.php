<?php
$ev_day=tribe_get_start_date($event_id, false, 'd' );
$ev_month=tribe_get_start_date($event_id, false, 'M' );
$ev_full_month=tribe_get_start_date($event_id, false, 'F' );
$ev_year=tribe_get_start_date($event_id, false, 'Y' );
$ev_post_img='';
$feat_img_url = wp_get_attachment_image_src(get_post_thumbnail_id($event_id),'thumbnail');
$ev_post_img= isset($feat_img_url[0])?$feat_img_url[0]:'';
$list_style=$attribute['style'];
if($list_style=="style-1"){
    $events_html.='<div id="event-'.esc_attr($event_id).'" class="ect-list-posts '.esc_attr($list_style).' '.esc_attr($event_type).' '.$time.'">';
	$events_html.='<div class="ect-event-date-tag"><div class="ect-event-datetimes">
			        <span class="ev-mo">'.$ev_month.'</span>
			        <span class="ev-day">'.$ev_day.'</span>
			        </div></div>';
	$events_html.='<div class="ect-event-details">
                <div class="ect-event-datetime"><i class="ect-icon-clock"></i>
                <span class="ect-minimal-list-time">'.$ev_time.'</span></div>
				<div class="ect-events-title">'.$event_title.'</div>
			    <div class="ect-'.$style.'-more"><a href="'.esc_url( tribe_get_event_link($event_id) ).'" class="ect-events-read-more" rel="bookmark">'.esc_html__( 'Find out more', 'the-events-calendar' ).' &raquo;</a></div>';
	$events_html.='</div></div>';
}
else if($list_style=="style-2"){
        $events_html.='<div id="event-'.esc_attr($event_id).'" class="ect-list-posts '.esc_attr($list_style).' '.esc_attr($event_type).' '.$time.'">';
        $events_html.='<div class="ect-event-date ect-schedule-wrp ect-date-viewport">
                <span class="ect-date-viewport">'.$ev_day.'</span>
                <span class="ect-month">'.$ev_month.'</span>
                </div>';
        $events_html.= '<div class="ect-right-wrapper">';
        $events_html.='<span class="ect-event-title">'.$event_title.'</span>';
        $events_html.='<div class="ect-'.$style.'-more"><a href="'.esc_url( tribe_get_event_link($event_id) ).'" class="ect-events-read-more" rel="bookmark">'.esc_html__( 'Find out more', 'the-events-calendar' ).' &raquo;</a></div>';
	    $events_html.='</div></div>';
}
else{
    $events_html.='<div id="event-'.esc_attr($event_id).'" class="ect-list-posts '.esc_attr($list_style).' '.esc_attr($event_type).' '.$time.'">';
    $events_html.='<div class="ect-left-wrapper">';
    $events_html.='<div class="ect-event-dates"><div class="ect-event-datetimes">
                <span class="ev-day">'.$ev_day.'</span>
                 <span class="ev-mo">'.$ev_month.'</span>
                <span class="ev-time">'.tribe_get_start_date($event_id, false, 'D' ).'</span>
                </div></div>';
    $events_html.='</div>'; 
    $events_html.='<div class="ect-right-wrapper">';
                    $events_html.='<div class="ect-events-title">'.$event_title.'</div> 
                        <div class="ect-event-details"><span class="ect-minimal-list-time"><i class="ect-icon-clock"></i>
                    <span class="ect-minimal-list-time">'.$ev_time.'</span></span></div>';
    $events_html.='<div class="ect-'.$style.'-more"><a href="'.esc_url( tribe_get_event_link($event_id) ).'" class="ect-events-read-more" rel="bookmark">'.esc_html__( 'Find out more', 'the-events-calendar' ).' &raquo;</a></div>';
    $events_html .='</div>';
   $events_html.='</div>';
}
	