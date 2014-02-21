<?php
/**
 * Plugin Name: Eventbrite Attendees Shortcode
 * Plugin URI: http://austin.passy.co/wordpress-plugins/eventbrite-attendees-shortcode/
 * Description: List your attendees from your <a href="http://www.eventbrite.com/r/thefrosty">Eventbrite</a> event. Get your API key <a href="https://www.eventbrite.com/api/key">here</a>.
 * Version: 1.0
 * Author: Austin Passy
 * Author URI: http://austin.passy.co
 *
 * Developers can learn more about the WordPress shortcode API:
 * @link http://codex.wordpress.org/Shortcode_API
 *
 * @copyright 2009 - 2014
 * @author Austin Passy
 * @link http://austinpassy.com/2009/08/20/eventbrite-attendee-shortcode-plugin
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package Eventbrite_Attendees_Shortcode
 */

if ( !class_exists( 'Eventbrite_Attendees_Shortcode' ) ) {
class Eventbrite_Attendees_Shortcode {

	/**
	 * Holds the instances of this class.
	 *
	 * @since  0.4
	 * @access private
	 * @var    object
	 */
	private static $instance;
	public static $eas_script;
	
	/* Constants */
	const version = '1.0',
		  domain  = 'eventbrite-attendees';
		  
	/* Vars */
	var $settings_page,
		$prefix;
	
	/**
	 * Private settings
	 */
	private $settings_api,
			$meta_box;

	/**
	 * Returns the instance.
	 *
	 * @since  0.4
	 * @access public
	 * @return object
	 */
	public static function instance() {

		if ( !self::$instance )
			self::$instance = new self;

		return self::$instance;
	}
	
	/**
	 * Sets up needed actions/filters for the plugin to initialize.
	 *
	 * @since  0.4
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		$this->prefix = 'eventbrite_attendees_shortcode';

		/* Set the constants needed by the plugin. */
		add_action( 'plugins_loaded', array( $this, 'constants' ), 1 );

		/* Load additional actions. */
		add_action( 'plugins_loaded', array( $this, 'add_actions' ), 3 );

		/* Load additional filters. */
		add_action( 'plugins_loaded', array( $this, 'add_filters' ), 3 );

		/* Internationalize the text strings used. */
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 2 );

		/* Load all files. */
		add_action( 'plugins_loaded', array( $this, 'includes' ), 4 );
	}

	/**
	 * Defines constants for the plugin.
	 *
	 * @since  0.4
	 * @access public
	 * @return void
	 */
	function constants() {
		
		/* Set constant file. */
		define( 'EVENTBRITE_ATTENDEES_FILE', __FILE__ );

		/* Set constant path to the plugin directory. */
		define( 'EVENTBRITE_ATTENDEES_DIR', trailingslashit( plugin_dir_path( EVENTBRITE_ATTENDEES_FILE ) ) );

		/* Set constant path to the plugin URI. */
		define( 'EVENTBRITE_ATTENDEES_URI', trailingslashit( plugin_dir_url( EVENTBRITE_ATTENDEES_FILE ) ) );
	}

	/**
	 * Add Actions.
	 *
	 * @since  0.4
	 * @access public
	 * @return void
	 */
	function add_actions() {		
		add_action( 'wp_print_footer_scripts',	array( $this, 'scripts' ) );
		
		/* Shortcode */
		add_action( 'init',						array( $this, 'add_shortcode' ), 19 );
				
		/* Settings */
		add_action( 'admin_init',				array( $this, 'admin_init' ), 9 );
		add_action( 'admin_menu',				array( $this, 'admin_menu' ), 9 );
	}
	
	/**
	 * Add shorcodes.
	 *
	 * @since  0.4
	 * @access public
	 * @return void
	 */
	function add_shortcode() {
		add_shortcode( 'eventbrite-attendees',	array( $this, 'shortcode' ) );
	}
	
	/**
	 * Add Filters.
	 *
	 * @since  0.4
	 * @access public
	 * @return void
	 */
	function add_filters() {		
		add_filter( 'plugin_action_links',		array( $this, 'plugin_action' ), 10, 2 );
	}

	/**
	 * Loads the translation files.
	 *
	 * @since  0.4
	 * @access public
	 * @return void
	 */
	function i18n() {
		load_plugin_textdomain( self::domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Loads admin files.
	 *
	 * @since  0.4
	 * @access public
	 * @return void
	 */
	function includes() {

		if ( is_admin() ) {
			
			// Settings API
			require_once( EVENTBRITE_ATTENDEES_DIR . 'library/admin/class.settings-api.php' );
				$this->settings_api = new Eventbrite_Attendees_Shortcode_Settings_API;
				$this->settings_api->set_prefix( $this->prefix );
				$this->settings_api->set_domain( self::domain );
				$this->settings_api->set_version( self::version );
				
			require_once( EVENTBRITE_ATTENDEES_DIR . 'library/admin/class.meta-box.php' );
				$this->meta_box = new Eventbrite_Attendees_Meta_Box;
				$this->meta_box->set_domain( self::domain );
				$this->meta_box->set_version( self::version );
				
			// Dashboard widget
			if ( 'on' !== $this->get_option( 'dashboard', $this->prefix . '_help' ) )
				require_once( EVENTBRITE_ATTENDEES_DIR . 'library/admin/dashboard.php' );
		}
		
		// Eventbrite
		require_once( EVENTBRITE_ATTENDEES_DIR . 'library/classes/Eventbrite.php' );
	}
	
	/**
	 * Register the plugin scripts.
	 *
	 * @since  0.4
	 * @access public
	 * @return void
	 */
	function scripts() {
		if ( !self::$eas_script )
			return;
		
		// Style
		wp_enqueue_style( self::domain, plugins_url( 'library/css/eventbrite-attendees.css', EVENTBRITE_ATTENDEES_FILE ), null, self::version, 'screen' );
		wp_print_styles( self::domain );
		
		// Script
		wp_enqueue_script( self::domain, plugins_url( 'library/js/eventbrite-attendees.js', EVENTBRITE_ATTENDEES_FILE ), array( 'jquery' ), self::version, false );
		wp_print_scripts( self::domain );
	}
	
	/** 
	 * Registers settings section and fields
 	 */
    function admin_init() {
				
        $this->sections = array(
            array(
                'id'	=> $this->prefix . '_developer',
                'title' => __( 'Settings', self::domain )
            ),
			array(
                'id'	=> $this->prefix . '_help',
                'title' => __( 'Help', self::domain )
            ),
        );

        $fields = array(
            $this->prefix . '_developer' => array(
                array(
                    'name'		=> 'app_key',
                    'label'		=> __( 'APP Key', self::domain ),
                    'desc'		=> sprintf( __( 'Enter your app key. Get one here %s', self::domain ),
						make_clickable( 'https://www.eventbrite.com/api/key' ) ),
                    'type' 		=> 'text',
					'size'		=> 'regular',
                    'default' 	=> '',
					'sanitize_callback' => 'esc_attr',
                ),
			),
			$this->prefix . '_help' => array(
                array(
                    'name'		=> 'help',
                    'label'		=> '',
                    'desc'		=> sprintf( '
						<p><strong>%s</strong></p>
						<p><code>[eventbrite-attendees id="YOUR_EVENT_ID" sort="true|false" clickable="true|false" app_key="APP_KEY(IF_NOT_SET_IN_SETTINGS)"]</code></p>
						<p>%s<ul>
							<li>%s</li>
							<li>%s</li>
							<li>%s</li>
							<li>%s</li>
						</ul></p>',
							__( 'Use the shortcode in your page or posts like this:', self::domain ),
							__( 'Shortcode args:', self::domain ),
							sprintf( __( 'Replacing the "id" with your <a href="%s" rel="external" target="_blank" title="Eventbrite">Eventbrite</a> event id.', self::domain ),
								'http://www.eventbrite.com/r/thefrosty' ),
							__( 'sort: Should the attendee list be sorted by puchase date?', self::domain ),
							__( 'clickable: Should links be clickable?', self::domain ),
							__( 'app_key: Your developer app key if not saved in the settings.', self::domain )
						),
                    'type' 		=> 'html',
                ),
                array(
                    'name'		=> 'dashboard',
                    'label'		=> __( 'Hide Dashboard', self::domain ),
                    'desc'		=> __( 'Check to disable the dashboard widget.', self::domain ),
                    'type' 		=> 'checkbox',
                    'default' 	=> false,
                ),
			),
        );
		
        //set sections and fields
        $this->settings_api->set_sections( $this->sections );
		$this->settings_api->set_fields( $fields );

        //initialize them
        $this->settings_api->admin_init();
		
		add_action( $this->prefix . '_settings_sidebars', array( $this, 'sidebar' ), 1 );
		
		return $this;
    }

    /**
	 * Register the plugin page
	 */
    function admin_menu() {
		$options_page = add_options_page( __( 'Eventbrite Attendees Shortcode', self::domain ), __( 'Eventbrite Attendees', self::domain ), 'edit_plugins', self::domain, array( $this, 'plugin_page' ) );		
		
		add_action( 'admin_footer-' . $options_page, array( $this->settings_api, 'inline_jquery' ) );
    }	

	/**
	 * Display the plugin settings options page
	 */
    function plugin_page() {
        echo '<div class="wrap">';
			$this->settings_api->show_navigation();
			$this->settings_api->show_forms();
        echo '</div>';		
    }

	/**
	 * Shortcode function
	 *
	 * @since 0.1
	 * @use [eventbrite-attendees
	 *			id="384870157"
	 *			app_key="OPTIONAL(IF NOT SET IN SETTINGS)"
	 *			user_key="OPTIONAL"
	 *			sort="true|false"
	 *			clickable="true|false"]
	 */
	function shortcode( $args ) {
		
		$defaults = array (
			'app_key'	=> $this->get_option( 'app_key', $this->prefix . '_developer' ),
			'user_key'	=> $this->get_option( 'user_key', $this->prefix . '_developer' ),
			'id'		=> '',
			'sort'		=> 'true',
			'clickable'	=> 'true',
		);
		
		// Parse incoming $args into an array and merge it with $defaults
		$args = wp_parse_args( $args, $defaults );
		
		// Bail early
		if ( empty( $args['id'] ) )
			return __( 'Please enter a valid "id".', self::domain );
		
		$transient = 'event_list_attendees_id_' . md5( $args['id'] );
		
//		delete_transient( $transient );
		
		if ( false === ( $attendees = get_transient( $transient ) ) ) :
			// Initialize the API client
			// Eventbrite API / Application key (REQUIRED)
			// http://www.eventbrite.com/api/key/
			// Eventbrite user_key (OPTIONAL, only needed for reading/writing private user data)
			// http://www.eventbrite.com/userkeyapi
			$auth = array(
				'app_key'	=> $args['app_key'],
				'user_key'	=> $args['user_key'],
			);
			$eventbrite = new Eventbrite( $auth );
			
			try {
				$attendees = $eventbrite->event_list_attendees( array( 'id' => $args['id'] ) );
			} catch ( Exception $e ) {
				$attendees = null;
			}
			
			if ( is_null( $attendees ) ) {
				delete_transient( $transient );
				return sprintf( __( 'An error has occurred%s', self::domain ), is_user_logged_in() && current_user_can('edit_pages') ? '<br><pre>' . $e . '</pre>' : '' );
			}
				
			self::$eas_script = true;
			
			$sort	= filter_var( $args['sort'], FILTER_VALIDATE_BOOLEAN );
			$click	= filter_var( $args['clickable'], FILTER_VALIDATE_BOOLEAN );
			
			set_transient( $transient, $attendees, DAY_IN_SECONDS );
		endif;
		
		return $this->attendee_list_to_html( $attendees, $sort, $click );
	}
	
	/**
     * Helper function to print attendee HTML from Object Arrray.
     *
     * @param object	$attendee the attendee meta fields.
     * @param boolean	$sort should we resort the order?
     * @param boolean	$clickable should the value be clickable
     * @return string
	 */
	function attendee_list_to_html( $attendees, $sort = true, $clickable = true ) {
		$html  = "<div class='eb-attendees-list'>\n";
		
		if ( isset( $attendees->attendees ) ) {
			if ( $sort ) {
				usort( $attendees->attendees, array( $this, 'sort_attendees_by_created_date' ) );
			}
			//sort by name
			//usort( $attendees->attendees, array( $this, 'sort_attendees_by_name' ) );
			//render the attendee as HTML
			foreach( $attendees->attendees as $attendee ) {
				$html .= $this->attendee_to_html( $attendee->attendee, $clickable );
			}
		}
		else {
			$html .= '<ul><li class="eb-attendee-list-item">' . __( 'You can be the first to register for this event.', self::somain ) . "</li></ul>\n";
		}
		
		$html .= "</div>\n";
			
		return $html;
	}

	/**
     * Helper function to print attendee HTML.
     *
     * @param object	$attendee the attendee meta fields.
     * @param boolean	$clickable should the value be clickable
     * @return string
	 */
	function attendee_to_html( $attendee, $clickable ) {
//		return '<pre>' . print_r( $attendee, true ) . '</pre>';

		$html = "<ul>\n";
		
		foreach( $attendee as $key => $val ) {
			unset(
				$attendee->suffix,
				$attendee->event_id,
				$attendee->answers,
				$attendee->prefix,
				$attendee->id
			);
				
			switch( $key ) {
				case 'first_name':
					$html .= '<li class="eb-attendee-list-item ' . sanitize_html_class( $key ) . '">';
					$html .= $val;
					$html .= "&nbsp;</li>\n\t";
					break;
				default:
					if ( !empty( $val ) ) {
						$html .= '<li class="eb-attendee-list-item ' . sanitize_html_class( $key ) . '">';
						$html .= $clickable ? make_clickable( $val ) : $val;
						$html .= "</li>\n\t";
					}
					break;
			}
		}
		
		$html .= "</ul>\n";
		
		return $html;
	}
	
	/**
     * If value created exists return order by purchase date.
     *
     * @return int
	 */
	function sort_attendees_by_created_date( $x, $y ) {
		if ( isset( $x->attendee->created ) && isset( $y->attendee->created ) ) {
			if ( $x->attendee->created == $y->attendee->created ) {
				return 0;
			}
			return ( $x->attendee->created > $y->attendee->created ) ? -1 : 1;
		}
		return 0;
	}
	
	/**
     * Return order by first_name
     *
     * @return string
	 */
	function sort_attendees_by_name( $x, $y ) {
		return strcmp( $x->attendee->first_name, $y->attendee->first_name );
	}

	/**
	 * Sidebar info about this plugin
	 *
	 * @since	2.0
	 * @return	string
	 */
	function sidebar( $args ) {
		$content  = '<ul class="social">';
		$content .= '<li><span class="genericon genericon-user"></span>&nbsp;<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7329157">' . __( 'Support this plugin and buy me a beer', self::domain ) . '</a></li>';
		$content .= '<li><span class="genericon genericon-star"></span>&nbsp;<a href="http://wordpress.org/plugins/eventbrite-attendees-shortcode/">' . __( 'Rate this plugin on WordPress.org', self::domain ) . '</a></li>';
		$content .= '<li><span class="genericon genericon-wordpress"></span>&nbsp;<a href="http://wordpress.org/support/plugin/eventbrite-attendees-shortcode/">' . __( 'Get support on WordPress.org', self::domain ) . '</a></li>';

		$content .= '</ul>';
		$this->settings_api->postbox( $this->prefix . '_sidebar', sprintf( __( '<a href="%s">%s</a> | <code>version %s</code>', self::domain ), 'http://austin.passy.co/wordpress-plugins/eventbrite-attendees-shortcode/', ucwords( str_replace( '-', ' ', self::domain ) ), self::version ), $content, false );
	}

    /**
     * Get the value of a settings field
     *
     * @param string  $option  settings field name
     * @param string  $section the section name this field belongs to
     * @param string  $default default text if it's not found
     * @return string
     */
    function get_option( $option, $section, $default = '' ) {

        $options = get_option( $section );

        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }

        return $default;
    }
	
	/**
	 * Plugin Action /Settings on plugins page
	 * @since 0.2
	 * @package plugin
	 */
	function plugin_action( $links, $file ) {
		if ( $file === plugin_basename( EVENTBRITE_ATTENDEES_FILE ) ) {
			$settings_link = '<a href="' . sprintf( admin_url( 'options-general.php?page=%s' ), self::domain ) . '">' . __( 'Settings' ) . '</a>';
			array_unshift( $links, $settings_link ); // before other links
		}
		return $links;
	}
	
}
};

/**
 * The main function responsible for returning the one true
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $eas = EVENTBRITE_ATTENDEES_SHORTCODE(); ?>
 *
 * @return The one true Instance
 */
if ( !function_exists( 'EVENTBRITE_ATTENDEES_SHORTCODE' ) ) {
	function EVENTBRITE_ATTENDEES_SHORTCODE() {
		return Eventbrite_Attendees_Shortcode::instance();
	}
}

// Out of the frying pan, and into the fire.
EVENTBRITE_ATTENDEES_SHORTCODE();