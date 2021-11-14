<?php 
// events custom date format creator function 
function ect_custom_date_formats($date_format,$template,$event_id,$ev_time){
        /*Date Format START*/
        $ev_day=tribe_get_start_date($event_id, false, 'd' );
        $ev_month=tribe_get_start_date($event_id, false, 'M' );
        $ev_full_month=tribe_get_start_date($event_id, false, 'F' );
        $ev_year=tribe_get_start_date($event_id, false, 'Y' );
        $output='';
 
				if($date_format=="DM") {
					$event_schedule='<span class="ev-day">'. $ev_day.'</span>
					<span class="ev-mo">'.$ev_month.'</span>';
				}
				else if($date_format=="MD") {
					$event_schedule='<span class="ev-mo">'.$ev_month.'</span>
									<span class="ev-day">'.$ev_day.'</span>';
				}
				else if($date_format=="FD") {
					$event_schedule='<span class="ev-mo">'.$ev_full_month.'</span>
									<span class="ev-day">'.$ev_day.'</span>
									';
				}
				else if($date_format=="DF") {
					$event_schedule='<span class="ev-day">'.$ev_day.'</span>
									<span class="ev-mo">'.$ev_full_month.'</span>
									';
				}
				else if($date_format=="FD,Y") {
					$event_schedule='<span class="ev-mo">'.$ev_full_month.'</span>
									<span class="ev-day">'.$ev_day.'<i class="date-comma">, </i></span>
									<span class="ev-yr">'.$ev_year.'</span>
									';
				}
				else if($date_format=="MD,Y") {
					$event_schedule='<span class="ev-mo">'.$ev_month.'</span>
									<span class="ev-day">'.$ev_day.'<i class="date-comma">, </i></span>
									<span class="ev-yr">'.$ev_year.'</span>
									';
				}
				else if($date_format=="MD,YT") {
					$event_schedule='<span class="ev-mo">'.$ev_month.'</span>
									<span class="ev-day">'.$ev_day.'<i class="date-comma">, </i></span>
									<span class="ev-yr">'.$ev_year.'</span>
									<span class="ev-time"><span class="ect-icon"><i class="ect-icon-clock"></i></span> '.$ev_time.'</span>
									';
				}
				else if($date_format=="DML") {
					$event_schedule='<span class="ev-day">'.$ev_day.'</span>
									<span class="ev-mo">'.$ev_month.'</span>
									<span class="ev-time">'.tribe_get_start_date($event_id, false, 'l' ).'</span>
									';
				}
				else if($date_format=="D.M.Y") {
					$event_schedule='<span class="ev-day">'.$ev_day.'<i class="date-comma">. </i></span>
									<span class="ev-mo">'.tribe_get_start_date($event_id, false, 'm' ).'<i class="date-comma">. </i></span>
									<span class="ev-yr">'.$ev_year.'</span>
									';
				}
				else if($date_format=="full") {
					$event_schedule='<span class="ev-day">'.$ev_day.'</span>
									<span class="ev-mo">'.$ev_full_month.'</span>
									<span class="ev-yr">'.$ev_year.'</span>
									<span class="ev-time"><span class="ect-icon"><i class="ect-icon-clock"></i></span> '.$ev_time.'</span>
									';
				}
				else if($date_format=="jMl") {
					$event_schedule='<span class="ev-day">'.tribe_get_start_date($event_id, false, 'j' ).'</span>
									<span class="ev-mo">'.$ev_month.'</span>
									<span class="ev-weekday">'.tribe_get_start_date($event_id, false, 'l' ).'</span>
									';
				}
				else if($date_format=="d.FY") {
					$event_schedule='<span class="ev-day">'.$ev_day.'. </span>
									<span class="ev-mo">'.$ev_full_month.'</span>
									<span class="ev-yr">'.$ev_year.'</span>
									';
				}
				else if($date_format=="d.F") {
					$event_schedule='<span class="ev-day">'.$ev_day.'. </span>
									<span class="ev-mo">'.$ev_full_month.'</span>
									';
                }
                else if($date_format=="d.Ml") {
					$event_schedule='<span class="ev-day">'.$ev_day.'. </span>
									<span class="ev-mo">'.$ev_month.'</span>
									<span class="ev-yr">'.tribe_get_start_date($event_id, false, 'l' ).'</span>
									';
                }
                else if($date_format=="ldF") {
					$event_schedule='<span class="ev-day">'.tribe_get_start_date($event_id, false, 'l' ).'</span>
									<span class="ev-mo">'.$ev_day.'</span>
									<span class="ev-yr">'.$ev_full_month.'</span>';
                }     
				else if($date_format=="dFT") {
					$event_schedule='<span class="ev-day">'.$ev_day.'</span>
									<span class="ev-mo">'.$ev_full_month.'</span>
									<span class="ev-time"><span class="ect-icon"><i class="ect-icon-clock" aria-hidden="true"></i></span> '.$ev_time.'</span>
									';
				}
				else if($date_format=="Mdl") {
                $event_schedule='<span class="ev-day">'.$ev_month.'</span>
                        <span class="ev-mo">'.$ev_day.'</span>
                        <span class="ev-yr">'.tribe_get_start_date($event_id, false, 'l' ).'</span>
                        ';
                }
				else {
					$event_schedule='<span class="ev-day">'.$ev_day.'</span>
					<span class="ev-mo">'.$ev_full_month.'</span>
					<span class="ev-yr">'.$ev_year.'</span>';
				}
				 $set_template = esc_attr($template);
                $output.='<div class="ect-date-area '.$set_template.'-schedule">';
                $output.=$event_schedule;
                $output.='</div>';
                return $output;
                /*Date Format END*/
}
    //grab event image
    function ect_get_event_image($event_id,$size){
	
		$default_img = ECT_PLUGIN_URL."assets/images/event-template-bg.png";
	
	
		// ect_default_img
        $ev_post_img='';
        $feat_img_url = wp_get_attachment_image_src(get_post_thumbnail_id($event_id),$size);
        if(!empty($feat_img_url) && $feat_img_url[0] !=false){
            $ev_post_img = $feat_img_url[0];
            }elseif ($feat_img_url==''|| $feat_img_url==false){
				$tect_settings = get_option('ects_options');
				$non_feat_img = !empty($tect_settings['ect_no_featured_img'])?$tect_settings['ect_no_featured_img']:'';
				//$non_feat_img_url = $non_feat_img['id'];
				if(is_array($non_feat_img)){
					$non_feat_img_url = $non_feat_img['id'];
				}
				else{
					$non_feat_img_url = $non_feat_img;
				}
               // $tect_settings = TitanFramework::getInstance( 'ect' );
                //$non_feat_img_url = $tect_settings->getOption( 'ect_no_featured_img' );
                if ($non_feat_img_url!='' && is_numeric( $non_feat_img_url ) )
                 {
                $imageAttachment = wp_get_attachment_image_src( $non_feat_img_url,$size);
                $ev_post_img= $imageAttachment[0];
                }else{
                    $ev_post_img=$default_img;
                }
            }else{
                $ev_post_img=$default_img;
            }
            return $ev_post_img;
    }
    // custom css for admin menu
  function ect_admin_menu_custom_styles() {
		echo '<style>
		tr[data-slug="template-events-calendar"] .plugin-description a[href*="plugins/the-events-calendar"] {
			background: rgb(0, 0, 0, 0.2);
			padding: 2px;
			border-radius: 3px;
			font-weight: bold;
			font-size: 12px;
			color: #222;
			text-decoration: none !IMPORTANT;
		}
		</style>';
   }
  