<?php
/**
 * Plugin Name: Events Widgets For Elementor And The Events Calendar
 * Description: Events Calendar Templates Builder For Elementor to create a beautiful calendar in  page and post.
 * Plugin URI:  https://coolplugins.net
 * Version:     1.4.2
 * Author:      Cool Plugins
 * Author URI:  https://coolplugins.net/
 * Text Domain: ectbe
 * Elementor tested up to: 3.4.6
 * Elementor Pro tested up to: 3.4.2
*/
if (!defined('ABSPATH')) {
    exit;
}
if (defined('ECTBE_VERSION')) {
    return;
}
define('ECTBE_VERSION', '1.4.2');
define('ECTBE_FILE', __FILE__);
define('ECTBE_PATH', plugin_dir_path(ECTBE_FILE));
define('ECTBE_URL', plugin_dir_url(ECTBE_FILE));

register_activation_hook(ECTBE_FILE, array('Events_Calendar_Addon', 'ectbe_activate'));
register_deactivation_hook(ECTBE_FILE, array('Events_Calendar_Addon', 'ectbe_deactivate'));

/**
 * Class Events_Calendar_Addon
 */
final class Events_Calendar_Addon
{

    /**
     * Plugin instance.
     *
     * @var Events_Calendar_Addon
     * @access private
     */
    private static $instance = null;

    /**
     * Get plugin instance.
     *
     * @return Events_Calendar_Addon
     * @static
     */
    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Constructor.
     *
     * @access private
     */
    private function __construct()
    {
        $this->include_files();
        //Load the plugin after Elementor (and other plugins) are loaded. 
        add_action( 'plugins_loaded', array($this, 'ectbe_plugins_loaded') );
    }
   
    function include_files(){
        require_once __DIR__ . "/admin/events-addon-page/events-addon-page.php";
        cool_plugins_events_addon_settings_page('the-events-calendar','cool-plugins-events-addon' ,'📅 Events Addons For The Events Calendar');
    }

    /**
     * Code you want to run when all other plugins loaded.
    */
	function ectbe_plugins_loaded() {
		
		// Notice if the Elementor is not active
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array($this, 'ectbe_fail_to_load') );
			return;
		}
        if ( ! class_exists( 'Tribe__Events__Main' ) or ! defined( 'Tribe__Events__Main::VERSION' )) {
            add_action( 'admin_notices', array( $this, 'ectbe_Install_ECT_Notice' ) );
        }
        load_plugin_textdomain('ectbe', false, ECTBE_FILE . 'languages');	
		
        // Require the main plugin file      
        require( __DIR__ . '/includes/functions.php' );
        require( __DIR__ . '/includes/class-ectbe.php' );

        if(is_admin()){
			
            require  __DIR__  .'/includes/class-review-notice.php';
            new ReviewNotice();
            require_once __DIR__ . "/feedback/admin-feedback-form.php";
        }
       
       	
	
    }	// end of ctla_loaded()
    
    	
		// notice for installation TEC parent plugin installation
		public function ectbe_Install_ECT_Notice() {
			if ( current_user_can( 'activate_plugins' ) ) {
				$url = 'plugin-install.php?tab=plugin-information&plugin=the-events-calendar&TB_iframe=true';	
				$title = __( 'The Events Calendar', 'tribe-events-ical-importer' );
				echo '<div class="error CTEC_Msz"><p>' . sprintf( __( 'In order to use this addon, Please first install the latest version of <a href="%s" class="thickbox" title="%s">%s</a> and add an event.', 'ect' ), esc_url( $url ), esc_attr( $title ),esc_attr( $title ) ) . '</p></div>';
			}
		}

    
	function ectbe_fail_to_load() { 
        
        if (!is_plugin_active( 'elementor/elementor.php' ) ) : ?>
			<div class="notice notice-warning is-dismissible">
				<p><?php echo sprintf( __( '<a href="%s"  target="_blank" >Elementor Page Builder</a>  must be installed and activated for "<strong>Events Calendar Templates Builder For Elementor</strong>" to work' ),'https://wordpress.org/plugins/elementor/'); ?></p>
			</div>
        <?php endif;
        
    }

    /**
     * Run when activate plugin.
     */
    public static function ectbe_activate()
    {
        update_option("ectbe-v",ECTBE_VERSION);
		update_option("ectbe-type","FREE");
		update_option("ectbe-installDate",date('Y-m-d h:i:s') );
    }

    /**
     * Run when deactivate plugin.
     */
    public static function ectbe_deactivate()
    {

    }
}

function Events_Calendar_Addon()
{
    return Events_Calendar_Addon::get_instance();
}
Events_Calendar_Addon();
