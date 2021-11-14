<?php
echo '<div class="ectbe-calendar-wrapper">';
    echo '<div class="ectbe_calendar_events_spinner"><img src="'.ECTBE_URL .'assets/images/ectbe-preloader.gif"></div>';
	 
    echo '<div id="ectbe-event-calendar-'.$this->get_id().'" class="ectbe-event-calendar-cls"
    data-cal_id = "'.$this->get_id().'"
    data-locale = "'.$local.'"
    data-defaultview = "'.$default_view.'"
    data-first_day="'.$settings['ectbe_calendar_first_day'].'"
    data-daterange = "'.$daterange.'"
    data-rangestart = "'.$rangeStart.'"
    data-rangeend = "'.$rangeEnd.'"
    data-max_events = "'.$max_events.'"
    data-ev_category="'.htmlspecialchars(json_encode($ev_category), ENT_QUOTES, 'UTF-8').'"
    data-textcolor = "'.$textColor.'"
    data-color = "'.$color.'">';
    echo '</div>';


    echo  '<div id="ectbe-popup-wraper" class="ectbe-modal ectbe-zoom-in">
            <div class="ectbe-ec-modal-bg"></div>
            <div class="ectbe-modal-content">
                <div class="ectbe-featured-img"></div>
                <div class="ectbe-modal-header">                    
                    <div class="ectbe-modal-close"><span>&#10006</span></div>
                    <h2 class="ectbe-ec-modal-title"></h2>
                    <span class="ectbe-event-date-start ectbe-event-popup-date"></span>
                    <span class="ectbe-event-date-end ectbe-event-popup-date"></span>
                </div>
                <div class="ectbe-modal-body">
                    <span class="ectbe-cost"></span>
                    <p></p>
                </div>';
                if($details_link!='yes'){
                   echo'<div class="ectbe-modal-footer">
                        <a class="ectbe-event-details-link">'.__("Read More","ectbe").'</a>
                    </div>';
                }
                
            echo'</div>
        </div>';
  

echo '</div>';