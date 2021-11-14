<?php
$ev_day=tribe_get_start_date($event_id, false, 'd' );
$ev_month=tribe_get_start_date($event_id, false, 'M' );
$event_year = tribe_get_start_date( $event_id, true, 'F Y' );
if($style=='style-2'){
	if ($event_year != $display_year) {
		$display_year = $event_year;
		$events_html.='<div class="ect-month-header '.esc_attr($event_type).'"><span class="ectbe-header-year">'.$display_year.'</span></div>';
	}
	$events_html.='<div id="event-'.esc_attr($event_id).'" class="ectbe-list-posts '.esc_attr($style).' '.esc_attr($event_type).'">';
		$events_html.='<div class="ectbe-event-date-tag"><div class="ectbe-event-datetimes">
			<span class="ev-mo">'.$ev_month.'</span>
			<span class="ev-day">'.$ev_day.'</span>
			</div></div>';
		$events_html.='<div class="ectbe-event-details">';
			// if(!empty($ectbe_cate && $display_cate=='yes')){
			// 	$events_html .= '<div class="ectbe-event-category ectbe-list-category">'.$ectbe_cate.'</div>';
			// }
			$events_html.='<div class="ectbe-event-datetime"><span class="ectbe-minimal-list-time">'.$event_schedule.'</span></div>';
			$events_html.='<div class="ectbe-events-title">'.$event_title.'</div>'; 
			if ( tribe_has_venue($event_id) && isset($venue_details['linked_name']) && $ectbe_venue!='yes') {
				$events_html .= '<div class="ectbe-list-venue">'.$venue_details_html.'</div>';               
			}
			if($display_desc=='yes'){
				$events_html .= '<div class="ectbe-minimal-list-desc">'.$evt_desc.'</div>';
			}
			if ( tribe_get_cost($event_id) ) {
				$events_html .= '<div class="ectbe-list-cost">'.$ev_cost.'</div>';
			}
			$events_html.='<div class="ectbe-'.$style.'-more"><a href="'.esc_url( tribe_get_event_link($event_id) ).'" class="ectbe-events-read-more" rel="bookmark">'.esc_html__( 'Find out more', 'the-events-calendar' ).' &raquo;</a></div>';
		$events_html.='</div>';
		if($ev_post_img!=''){
			$events_html .= '<div class="ectbe-right-wrapper"><a class="ect-static-small-list-ev-img" href="'.esc_url( tribe_get_event_link($event_id)).'"><img src= "'.esc_url($ev_post_img).'"/></a></div>';
		}
	$events_html .= '</div>';
}
else{
	$events_html .='<div id="event-'.$event_id.'" class="ectbe-list-post '.esc_attr($style).' '.esc_attr($event_type).'" itemscope itemtype="http://schema.org/Event">
	<div class="ectbe-clslist-event-date ectbe-list-date">'.$event_schedule.'</div>';  
	$events_html .='<div class="ectbe-clslist-event-info">
			<div class="ectbe-clslist-inner-container">';
				if(!empty($ectbe_cate && $display_cate=='yes')){
					$events_html .= '<div class="ectbe-event-category ectbe-list-category">'.$ectbe_cate.'</div>';
				}
				$events_html .='<h2 class="ectbe-list-title">'.$event_title.'</h2>
					<div class="ectbe-clslist-time"><span class="ectbe-icon"><i class="ectbe-icon-clock"></i></span><span class="cls-list-time">'.$ev_time.'</span></div>';
            		if ( tribe_has_venue($event_id) && isset($venue_details['linked_name']) && $ectbe_venue!='yes')  {
                		$events_html .= '<div class="ectbe-list-venue '.$template.'-venue">'.$venue_details_html.'</div>';               
            		}
					if($display_desc=='yes'){
						$events_html .= '<div class="ectbe-style1-desc">'.$evt_desc.'</div>';
					}
			$events_html .='</div>';
		if ( tribe_get_cost($event_id) ) {
			$events_html .= '<div class="ectbe-list-cost">'.$ev_cost.'</div>';
		}
	$events_html .= '</div>';
    $events_html .= '<div class="ectbe-clslist-event-details"><a href="'.esc_url( $url).'" class="tribe-events-read-more" rel="bookmark">'.esc_html__( 'Find out more', 'the-events-calendar' ).'<i class="ectbe-icon-right-double"></i></a></div>';
$events_html .='</div>';
}
