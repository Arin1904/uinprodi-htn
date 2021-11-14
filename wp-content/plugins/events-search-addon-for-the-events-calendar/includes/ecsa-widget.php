<?php
// Register and load the widget
function ecsa_load_widget() {
    register_widget( 'EventsCalendarSearchAddonWidget' );
}

add_action( 'widgets_init', 'ecsa_load_widget' );
 
// Creating the widget 
class EventsCalendarSearchAddonWidget extends WP_Widget {
 
	//this function registers widget with wordpress 
	function __construct() {
		parent::__construct(
		 
		// Base ID of your widget
		'EventsCalendarSearchAddonWidget', 
		 
		// Widget name will appear in UI
		__('Events Search Addon', 'ecsa'), 
		 
		// Widget description
		array( 'description' => __( 'Events Search Addon For The Events Calendar', 'ecsa' ), )

		);
	}
	 
	 
	// Creating widget front-end	 
	public function widget( $args, $instance ) {
		$show_events=( ! empty( $instance['show_events'] ) ) ?( $instance['show_events']) : '5';	
		$disable_past_events=( isset( $instance['disable_past_events'] ) )? $instance['disable_past_events'] : 'false';	
		$layout = ( isset( $instance['layout'] ) ) ?( $instance['layout']) : 'small';
		$title = apply_filters( 'widget_title', $instance['title'] );
		$placeholder=( ! empty( $instance['placeholder'] ) ) ?( $instance['placeholder']) : 'Search Events';
	
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		echo ecsa_generate_html($placeholder,$show_events,$disable_past_events,$layout);
		echo $args['after_widget'];
	}
			 
	// Widget Backend 
	public function form( $instance ) {
		// Load css for widget only
		wp_enqueue_style('ecsa-widget-style', ECSA_URL.'assets/css/ecsa-widgets.css');
		
		if(!isset($instance['placeholder']) || empty($instance['placeholder'])) { 
			$placeholder = "Search Events"; 
		}
		else {
			$placeholder = $instance['placeholder'];	
		}
		if(!isset($instance['show_events']) || empty($instance['show_events'])) { 
			$show_events = "5"; 
		}		
		else {
			$show_events = $instance['show_events'];
		}
		
		if(!isset($instance['disable_past_events'])) { 
			$instance['disable_past_events'] = "false"; }
		else {
			$instance['disable_past_events'] = $instance['disable_past_events'];	
		}

		if(!isset($instance['layout'])) { 
			$instance['layout'] = "small"; }
		else {
			$instance['layout'] = $instance['layout'];	
		}

		if ( isset( $instance[ 'title' ] ) ) {
		$title = $instance[ 'title' ];
		}
		else {
		$title = __( 'Events Search', 'ecsa' );
		}

		// Widget admin form
		?>

		<p>
			<label class="ecsa-label" for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title :' );?></label> 
			<input class="ecsa-input" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label class="ecsa-label" for="<?php echo $this->get_field_id( 'placeholder' ); ?>"><?php _e( 'Placeholder :' ); ?></label>
			<input class="ecsa-input" type="text" id="<?php echo $this->get_field_id( 'placeholder' ); ?>" name="<?php echo $this->get_field_name( 'placeholder' ); ?>" value="<?php echo esc_attr($placeholder );?>">
		</p>
		   
		<p>
			<label class="ecsa-label" for="<?php echo $this->get_field_id( 'show_events' ); ?>"><?php  _e( 'Show Events :' );?></label>
			<input class="ecsa-input"  type="text" id="<?php echo $this->get_field_id( 'show_events' ); ?>" name="<?php echo $this->get_field_name( 'show_events' ); ?>" value="<?php echo esc_attr($show_events );?>" >		
		</p> 

		<p>
			<label class="ecsa-label" for="<?php echo $this->get_field_id( 'disable_past_events' ); ?>"><?php _e( 'Disable Past Events :' );?></label>
			<select class="ecsa-input" id="<?php echo $this->get_field_id( 'disable_past_events' ); ?>" name="<?php echo $this->get_field_name( 'disable_past_events' ); ?>" >
				<option <?php selected($instance['disable_past_events'],'false') ?> value="false">False</option>
				<option <?php selected($instance['disable_past_events'],'true') ?> value="true">True</option>	
			</select>
		</p>

		<p>
			<label class="ecsa-label" for="<?php echo $this->get_field_id( 'layout' ); ?>"><?php _e( 'Layout :' );?></label>
			<select class="ecsa-input" id="<?php echo $this->get_field_id( 'layout' ); ?>" name="<?php echo $this->get_field_name( 'layout' ); ?>" >
				<option <?php selected($instance['layout'],'small') ?> value="small">Small</option>
				<option <?php selected($instance['layout'],'medium') ?> value="medium">Medium</option>
				<option <?php selected($instance['layout'],'large') ?> value="large">Large</option>
			</select>
		</p>
				
		<?php 
	}
		 
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance[ 'placeholder' ] = strip_tags( $new_instance[ 'placeholder' ] );
		$instance[ 'show_events' ] = strip_tags( $new_instance[ 'show_events' ] );
		$instance[ 'disable_past_events' ] = strip_tags( $new_instance[ 'disable_past_events' ] );
		$instance[ 'layout' ] = strip_tags( $new_instance[ 'layout' ] );

		return $instance;
	}
	
	
} // Class wpb_widget ends here

