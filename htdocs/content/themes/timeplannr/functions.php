<?php
/*
 * Themosis - A framework for WordPress developers.
 * Based on php 5.4 features and above.
 *
 * @author  Julien Lambé <julien@themosis.com>
 * @link 	http://www.themosis.com/
 */

/*----------------------------------------------------*/
// The directory separator.
/*----------------------------------------------------*/
defined('DS') ? DS : define('DS', DIRECTORY_SEPARATOR);

/**
 * Helper function to setup assets URL
 */
if (!function_exists('themosis_theme_assets'))
{
    /**
     * Return the application theme assets directory URL.
     *
     * @return string
     */
    function themosis_theme_assets()
    {
        if (is_multisite() && SUBDOMAIN_INSTALL)
        {
            $segments = explode('themes', get_template_directory_uri());
            $theme = (strpos($segments[1], DS) !== false) ? substr($segments[1], 1) : $segments[1];
            return get_site_url().'/'.CONTENT_DIR.'/themes/'.$theme.'/resources/assets';
        }

        return get_template_directory_uri().'/resources/assets';
    }
}

/*----------------------------------------------------*/
// Asset directory URL.
/*----------------------------------------------------*/
defined('THEMOSIS_ASSETS') ? THEMOSIS_ASSETS : define('THEMOSIS_ASSETS', themosis_theme_assets());

/*----------------------------------------------------*/
// Theme Textdomain.
/*----------------------------------------------------*/
defined('THEMOSIS_THEME_TEXTDOMAIN') ? THEMOSIS_THEME_TEXTDOMAIN : define('THEMOSIS_THEME_TEXTDOMAIN', 'themosis-theme');

/*----------------------------------------------------*/
// Themosis Theme class.
// Check if the framework is loaded. If not, warn the user
// to activate it before continuing using the theme.
/*----------------------------------------------------*/
if (!class_exists('THFWK_ThemosisTheme'))
{
    class THFWK_ThemosisTheme
    {
        /**
         * Theme class instance.
         *
         * @var \THFWK_ThemosisTheme
         */
        protected static $instance = null;
        
        /**
         * Switch that tell if core and datas plugins are loaded.
         *
         * @var bool
         */
        protected $pluginsAreLoaded = false;

        protected function __construct()
        {
            // Default path to Composer autoload file.
            $autoload = __DIR__.DS.'vendor'.DS.'autoload.php';

            // Check for autoload file in dev mode (vendor loaded into the theme)
            if (file_exists($autoload))
            {
                require($autoload);
            }

        	// Check if framework is loaded.
            $this->check();
        }
        
        /**
    	 * Init the class.
         *
         * @return \THFWK_ThemosisTheme
    	 */
    	public static function getInstance()
    	{
    		if (is_null(static::$instance))
            {
    	    	static::$instance = new static();  
    	    }
    	 	return static::$instance;
    	}
    	
    	/**
         * Trigger by the action hook 'after_switch_theme'.
         * Check if the framework and dependencies are loaded.
         *
         * @return void
    	 */
    	public function check()
    	{
            // Check if core application class is loaded...
            if (!class_exists('Themosis\Core\Application'))
            {
                // Message for the back-end
                add_action('admin_notices', [$this, 'displayMessage']);

                // Message for the front-end
                if (!is_admin())
                {
                    wp_die(__("The <strong>Themosis theme</strong> can't work properly. Please make sure the Themosis framework plugin is installed. Check also your <strong>composer.json</strong> autoloading configuration.", THEMOSIS_THEME_TEXTDOMAIN));
                }

                return;
            }
    	}
    	
    	/**
         * Display a notice to the user if framework is not loaded.
         *
         * @return void
    	 */
    	public function displayMessage()
    	{
    		?>
    		    <div id="message" class="error">
                    <p><?php _e("You first need to activate the <b>Themosis framework</b> in order to use this theme.", THEMOSIS_THEME_TEXTDOMAIN); ?></p>
                </div>
    		<?php
    	}
    }
}

/*----------------------------------------------------*/
// Instantiate the theme class.
/*----------------------------------------------------*/
THFWK_ThemosisTheme::getInstance();

/*----------------------------------------------------*/
// Set theme's paths.
/*----------------------------------------------------*/
// Theme base path.
$paths['base'] = __DIR__.DS;

// Application path.
$paths['theme'] = __DIR__.DS.'resources'.DS;

// Application admin directory.
$paths['admin'] = __DIR__.DS.'resources'.DS.'admin'.DS;

themosis_set_paths($paths);

/*----------------------------------------------------*/
// Start the theme.
/*----------------------------------------------------*/
require_once('bootstrap'.DS.'start.php');

/**
 * Output value of a variable for debugging purposes
 *
 * @param mixed $value
 * @param bool $stopExecution
 * @access public
 * @author Anton Zaroutski <anton@jobloggr.com
 *
 */
function dump($value, $stopExecution = true)
{
	echo '<pre>';
	if (is_bool($value) || empty($value)) {
		var_dump($value);
	} else {
		print_r($value);
	}
	echo '</pre>';

	if ($stopExecution) exit;
}

function is_login_page() {
	return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}

function jquery_cdn() {

	if (!is_admin() && !is_login_page()) {
		wp_deregister_script('jquery');
		// wp_register_script('jquery', 'jQuery_JS_PATH', false, '1.8.3');
		// wp_enqueue_script('jquery');
	}

}
add_action('init', 'jquery_cdn');

/**
 * Proper way to enqueue scripts and styles
 */
function theme_name_scripts() {

	/* Bootstrap JavaScript library */
	/*
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/resources/assets/js/bootstrap/dist/js/bootstrap.min.js', array('jquery') );
	wp_enqueue_script( 'npm', get_template_directory_uri() . '/resources/assets/js/bootstrap/dist/js/npm.js' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-widget' );
	wp_enqueue_script( 'jquery-ui-mouse' );
	wp_enqueue_script( 'jquery-ui-accordion' );
	wp_enqueue_script( 'jquery-ui-autocomplete' );
	wp_enqueue_script( 'jquery-ui-slider' );
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-droppable' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'jquery-ui-resize' );
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_script( 'jquery-ui-button' );
	*/
	/* END: Bootstrap JavaScript library */

	wp_enqueue_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js', array(), FALSE, FALSE);
	wp_enqueue_script( 'jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js', array('jquery'), FALSE, TRUE);
	wp_enqueue_script( 'jquery-ui', get_template_directory_uri(). '/resources/assets/js/custom.js', array('jquery'), FALSE, TRUE);
	wp_enqueue_script( 'app-config', get_template_directory_uri(). '/resources/assets/test/js/app.config.js', array(), FALSE, TRUE);
	wp_enqueue_script( 'jquery-ui-touch-punch', get_template_directory_uri(). '/resources/assets/test/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js', array('jquery'), FALSE , TRUE);
	wp_enqueue_script( 'bootstrap', get_template_directory_uri(). '/resources/assets/test/js/bootstrap/bootstrap.min.js', array(), FALSE, TRUE);
	wp_enqueue_script( 'smart-notification', get_template_directory_uri(). '/resources/assets/test/js/notification/SmartNotification.min.js', array(), FALSE, TRUE);
	wp_enqueue_script( 'jarvis-widget', get_template_directory_uri(). '/resources/assets/test/js/smartwidgets/jarvis.widget.min.js', array(), FALSE, TRUE);
	wp_enqueue_script( 'easy-pie-chart', get_template_directory_uri(). '/resources/assets/test/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js', array('jquery'), FALSE , TRUE);
	wp_enqueue_script( 'sparkline', get_template_directory_uri(). '/resources/assets/test/js/plugin/sparkline/jquery.sparkline.min.js', array('jquery'), FALSE , TRUE);
	wp_enqueue_script( 'jquery-validate', get_template_directory_uri(). '/resources/assets/test/js/plugin/jquery-validate/jquery.validate.min.js', array('jquery'), FALSE , TRUE);
	wp_enqueue_script( 'maskedinput', get_template_directory_uri(). '/resources/assets/test/js/plugin/masked-input/jquery.maskedinput.min.js', array('jquery'), FALSE , TRUE);
	wp_enqueue_script( 'select2', get_template_directory_uri(). '/resources/assets/test/js/plugin/select2/select2.min.js', array(), FALSE, TRUE);
	// wp_enqueue_script( 'bootstrap-slider', '/content/themes/timeplannr/resources/assets/test/js/plugin/bootstrap-slider/bootstrap-slider.min.js', array(), FALSE, TRUE);
	wp_enqueue_script( 'mb-browser', get_template_directory_uri(). '/resources/assets/test/js/plugin/msie-fix/jquery.mb.browser.min.js', array(), FALSE, TRUE);
	wp_enqueue_script( 'fastclick', get_template_directory_uri(). '/resources/assets/test/js/plugin/fastclick/fastclick.min.js', array(), FALSE, TRUE);
	// wp_enqueue_script( 'demo', '/content/themes/timeplannr/resources/assets/test/js/demo.min.js', array(), FALSE, TRUE);
	wp_enqueue_script( 'app', get_template_directory_uri(). '/resources/assets/test/js/app.min.js', array(), FALSE, TRUE);
	wp_enqueue_script( 'voicecommand', get_template_directory_uri(). '/resources/assets/test/js/speech/voicecommand.min.js', array(), FALSE, TRUE);
	wp_enqueue_script( 'smart-chat-ui', get_template_directory_uri(). '/resources/assets/test/js/smart-chat-ui/smart.chat.ui.min.js', array(), FALSE, TRUE);
	wp_enqueue_script( 'smart-chat-manager', get_template_directory_uri(). '/resources/assets/test/js/smart-chat-ui/smart.chat.manager.min.js', array(), FALSE, TRUE);
	wp_enqueue_script( 'flot-cust', get_template_directory_uri(). '/resources/assets/test/js/plugin/flot/jquery.flot.cust.min.js', array('jquery'), FALSE , TRUE);
	wp_enqueue_script( 'flot-resize', get_template_directory_uri(). '/resources/assets/test/js/plugin/flot/jquery.flot.resize.min.js', array('jquery'), FALSE , TRUE);
	wp_enqueue_script( 'flot-time', get_template_directory_uri(). '/resources/assets/test/js/plugin/flot/jquery.flot.time.min.js', array('jquery'), FALSE , TRUE);
	wp_enqueue_script( 'flot-tooltip', get_template_directory_uri(). '/resources/assets/test/js/plugin/flot/jquery.flot.tooltip.min.js', array('jquery'), FALSE , TRUE);
	wp_enqueue_script( 'jvectormap', get_template_directory_uri(). '/resources/assets/test/js/plugin/vectormap/jquery-jvectormap-1.2.2.min.js', array('jquery'), FALSE , TRUE);
	wp_enqueue_script( 'jvectormap-world-mill', get_template_directory_uri(). '/resources/assets/test/js/plugin/vectormap/jquery-jvectormap-world-mill-en.js', array('jquery'), FALSE , TRUE);
	wp_enqueue_script( 'moment', get_template_directory_uri(). '/resources/assets/test/js/plugin/moment/moment.min.js', array(), FALSE , TRUE);
	wp_enqueue_script( 'qtip', 'http://cdn.jsdelivr.net/qtip2/2.2.1/jquery.qtip.min.js', array('jquery'), FALSE , TRUE);
	// wp_enqueue_script( 'fullcalendar', '/content/themes/timeplannr/resources/assets/test/js/plugin/fullcalendar/jquery.fullcalendar.min.js', array('jquery'), FALSE , TRUE);

	// wp_enqueue_script( 'npm', get_template_directory_uri() . '/resources/assets/js/bootstrap/dist/js/npm.js' );
	// wp_enqueue_script( 'pace', '/content/themes/timeplannr/resources/assets/test/js/plugin/pace/pace.min.js');

	/* Smartadmin JavaScript library */
	// wp_enqueue_script( 'smartadmin-app', get_template_directory_uri() . '/resources/assets/js/smartadmin/app.min.js' );
	// wp_enqueue_script( 'smartadmin-config', get_template_directory_uri() . '/resources/assets/js/smartadmin/app.config.js' );
	// wp_enqueue_script( 'smartadmin-plugin-moment', get_template_directory_uri() . '/resources/assets/js/smartadmin/plugin/moment/moment.min.js' );
	wp_enqueue_script( 'smartadmin-plugin-fullcalendar', get_template_directory_uri() . '/resources/assets/js/fullcalendar/dist/fullcalendar.min.js', array(), FALSE , TRUE);
	wp_enqueue_script( 'smartadmin-plugin-fullcalendar-scheduler', get_template_directory_uri() . '/resources/assets/js/fullcalendar-scheduler/dist/scheduler.min.js', array(), FALSE , TRUE);
	/* END: Smartadmin JavaScript library */

	/* Bootstrap styles */
	// wp_enqueue_style( 'bootstrap-style-main', get_template_directory_uri() . '/resources/assets/js/bootstrap/dist/css/bootstrap.min.css' );
	// wp_enqueue_style( 'bootstrap-style-theme', get_template_directory_uri() . '/resources/assets/js/bootstrap/dist/css/bootstrap-theme.min.css' );
	// wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/resources/assets/css/smartadmin/font-awesome.min.css' );
	// wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/resources/assets/css/smartadmin/smartadmin-production-plugins.min.css' );
	// wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/resources/assets/css/smartadmin/smartadmin-production.min.css' );
	// wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/resources/assets/css/smartadmin/smartadmin-skins.min.css' );
	/* END: Bootstrap styles */

	/* Smartadmin styles */
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/resources/assets/css/smartadmin/font-awesome.min.css' );
	wp_enqueue_style( 'smartadmin-production-plugins', get_template_directory_uri() . '/resources/assets/css/smartadmin/smartadmin-production-plugins.min.css' );
	wp_enqueue_style( 'smartadmin-production', get_template_directory_uri() . '/resources/assets/css/smartadmin/smartadmin-production.min.css' );
	wp_enqueue_style( 'smartadmin-skins', get_template_directory_uri() . '/resources/assets/css/smartadmin/smartadmin-skins.min.css' );
	wp_enqueue_style( 'smartadmin-rtl', get_template_directory_uri() . '/resources/assets/css/smartadmin/smartadmin-rtl.min.css' );
	wp_enqueue_style( 'fullcalendar', get_template_directory_uri() . '/resources/assets/js/fullcalendar/dist/fullcalendar.min.css' );
	wp_enqueue_style( 'fullcalendar-scheduler', get_template_directory_uri() . '/resources/assets/js/fullcalendar-scheduler/dist/scheduler.min.css' );
	/* END: Smartadmin styles */

	/* jQuery timepicker JavaScript & styles */
	wp_enqueue_script( 'jquery-time-picker', get_template_directory_uri() . '/resources/assets/js/jqueryui-timepicker-addon/dist/jquery-ui-timepicker-addon.js', NULL ,NULL, TRUE );
	wp_enqueue_script( 'jquery-time-picker', get_template_directory_uri() . '/resources/assets/js/jqueryui-timepicker-addon/dist/i18n/jquery-ui-timepicker-addon-i18n.min.js', NULL ,NULL, TRUE );
	wp_enqueue_script( 'jquery-time-picker', get_template_directory_uri() . '/resources/assets/js/jqueryui-timepicker-addon/dist/jquery-ui-sliderAccess.js', NULL ,NULL, TRUE );
	// wp_enqueue_script( 'bootstrap-slider', get_template_directory_uri() . '/resources/assets/js/smartadmin/plugin/bootstrap-slider/bootstrap-slider.min.js', NULL ,NULL, TRUE );
	wp_enqueue_style( 'jquery-time-picker', get_template_directory_uri() . '/resources/assets/js/jqueryui-timepicker-addon/dist/jquery-ui-timepicker-addon.css' );
	wp_enqueue_style( 'form', get_template_directory_uri() . '/resources/assets/css/form.css' );
	wp_enqueue_style( 'header', get_template_directory_uri() . '/resources/assets/css/header.css' );
	wp_enqueue_style( 'qtip', 'http://cdn.jsdelivr.net/qtip2/2.2.1/jquery.qtip.min.css' );
	/* END: jQuery timepicker styles */

}

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );
add_action( 'wp_ajax_get_events', 'get_events_callback' );

function get_events_callback() {

	$filter =  isset( $_POST['filter'] ) ? $_POST['filter'] : NULL;

	global $wpdb; // this is how you get access to the database

	if (!isset($_GET['id'])) {

		$allVenues = VenueModel::all();
		$allVenuesArray = array();
		foreach ($allVenues as $venue) {
			$temp = array();
			$temp['ID'] = $venue['ID'];
			$temp['post_title'] = $venue['post_title'];
			$temp['post_name'] = $venue['post_name'];
			$allVenuesArray[$venue['ID']] = $temp;
		};

		$filteredVenues = VenueModel::all($filter);
		$filteredVenuesArray = array();
		foreach ($filteredVenues as $venue) {
			$temp = array();
			$temp['ID'] = $venue['ID'];
			$temp['post_title'] = $venue['post_title'];
			$temp['post_name'] = $venue['post_name'];
			$filteredVenuesArray[$venue['ID']] = $temp;
		};

	} else {

		$filteredVenuesArray[$_GET['id']] = VenueModel::details($_GET['id']);

	}

	// Get venue details
	// $venueDetails = VenueModel::details($_GET['id']);

	// dump($filteredVenuesArray);

	// Booked slots for the venue
	$bookedSlots = TimeslotModel::perVenue( array_keys($filteredVenuesArray) );
	$slots = array();

	// dump($bookedSlots, false);

	foreach ($bookedSlots as $slot) {

		$slot_array = array();

		// if ($count > 25) $count = 0;
		$date = strtotime( $slot['date'] );
		$day = date( "d", $date );

		$time_from = $slot['time_from'];
		$time_from_hour = $time_from;
		$time_from_minute = '00';

		if ($time_from > floor( $time_from ) ) {
			$time_from_hour = floor( $time_from );
			$time_from_minute = '30';
		}

		$time_to = $slot['time_to'];
		$time_to_hour = $time_to;
		$time_to_minute = '00';

		if ($time_to > floor( $time_to ) ) {
			$time_to_hour = floor( $time_to );
			$time_to_minute = '30';
		}

		$title = isset( $slot['title'] ) ? $slot['title'] : NULL;
		$colours = array(
			'greenLight',
			'red',
			'blue',
			'darken',
			'yellow',
			'purple',
			'orange',
		);

		$colour = $colours[ rand( 0, sizeof( $colours ) - 1 ) ];

		$slot_array = array();

		$slot_array['title'] = get_avatar( $slot['timeslot_user'], 20 ) . ' ' . $slot['first_name'] . ' ' . $slot['last_name'];
		// $slot_array['start'] = 'new Date(y, m, ' . $day . ', ' . $time_from_hour . ', 0)';
		// $slot_array['end'] = 'new Date(y, m, ' . $day . ', ' . $time_to_hour . ', 0)';
		// $slot_array['start'] = '2015-11-22T10:30:00';

		$slot_array['start'] = date( "Y-m-d", $date) . 'T' . $time_from_hour . ':' . $time_from_minute . ':00';
		$slot_array['end'] = date( "Y-m-d", $date) . 'T' . $time_to_hour . ':' . $time_to_minute . ':00';
		$slot_array['allDay'] = false;
		$slot_array['className'] = array( 'event', 'bg-color-' . $colour);
		$slot_array['description'] = $title;
		$slot_array['slotWidth'] = 50;
		$slot_array['resourceId'] = 'venue-' . $slot['timeslot_venue'];

		$slots[] = $slot_array;


		/*        start: new Date(y, m, '{{ $day }}' , '{{ $time_from_hour }}', 0),
				end: new Date(y, m, '{{ $day }}', '{{ $time_to_hour }}', 0),
				allDay: false,
				className: ["event", "bg-color-{{ $colour }}"],
				description: '{{ $title }}',
				slotWidth: 50,
				resourceId: 'venue-{{ $slot['timeslot_venue'] }}',*/


		// $count ++;

	}

	// dump($slots);

	echo json_encode(array('slots' => $slots, 'all_venues' => $allVenuesArray, 'filtered_venues' => $filteredVenuesArray));

	wp_die(); // this is required to terminate immediately and return a proper response
}

/**
 * Get full name of the logged in user
 *
 * @return string
 */
function get_user_name()
{
	$user = get_user_meta( get_current_user_id() );
	return $user['first_name'][0] . ' ' . $user['last_name'][0];
}

// Add first and last name fields to registration form
add_action( 'register_form', 'az_add_first_last_name_fields_to_registration_form', 1 );

/**
 * Add first and last name fields to registration field
 */
function az_add_first_last_name_fields_to_registration_form() { ?>

	<p>
		<label>First Name<br/>
			<input type="text" name="first_name" id="first_name" class="input" value="" size="25" tabindex="30" />
		</label>
	</p>

	<p>
		<label>Last Name<br/>
			<input type="text" name="last_name" id="last_name" class="input" value="" size="25" tabindex="40" />
		</label>
	</p>

<?php }


// Save first and last names on registration form
add_action( 'user_register', 'az_save_fist_last_name' );

/**
 * Save first and last names on registration form
 *
 * @param int $user_id
 */
function az_save_fist_last_name( $user_id ) {

	if ( ! empty( $_POST['first_name'] ) ) {
		update_user_meta( $user_id, 'first_name', trim( $_POST['first_name'] ) );
	}

	if ( ! empty( $_POST['last_name'] ) ) {
		update_user_meta( $user_id, 'last_name', trim( $_POST['last_name'] ) );
	}
}

/* redirect users to front page after login */
function redirect_to_front_page() {
	global $redirect_to;
	if (!isset( $_GET['redirect_to']) ) {
		$redirect_to = get_home_url();
	}
}
add_action( 'login_form', 'redirect_to_front_page' );


function my_custom_login() {
	echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/resources/assets/css/admin.css"/>';
}
add_action('login_head', 'my_custom_login');

function az_change_login_message( $message )
{
	// change messages that contain 'Register'
	if ( strpos( $message, 'Register' ) !== FALSE ) {
		$newMessage = "Fill the form below to start using Timeplannr.";
		return '<p class="message register">' . $newMessage . '</p>';
	}
	else {
		return $message;
	}
}

// add our new function to the login_message hook
add_action( 'login_message', 'az_change_login_message' );

/**
 * Switch the homepage for logged in users
 */
function az_switch_homepage() {

	if ( is_user_logged_in() ) {
		$page = url_to_postid('about'); // for logged in users
		update_option( 'page_on_front', $page );
		update_option( 'show_on_front', 'page' );
	} else {
		$page = url_to_postid('how-to-use'); // for logged in users
		update_option( 'page_on_front', $page );
		update_option( 'show_on_front', 'page' );
	}
}
add_action( 'init', 'az_switch_homepage' );