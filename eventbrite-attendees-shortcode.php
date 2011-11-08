<?php
/**
 * Plugin Name: Eventbrite Attendees Shortcode
 * Plugin URI: http://austinpassy.com/wordpress-plugins/eventbrite-attendees-shortcode/
 * Description: Adds your attendee list from your eventbrite RSS feed.
 * Version: 0.3.3
 * Author: Austin &ldquo;Frosty&rdquo; Passy
 * Author URI: http://austinpassy.com
 *
 * Developers can learn more about the WordPress shortcode API:
 * @link http://codex.wordpress.org/Shortcode_API
 *
 * @copyright 2009 - 2011
 * @author Austin Passy
 * @link http://austinpassy.com/2009/08/20/eventbrite-attendee-shortcode-plugin
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package EventbriteAttendeesShortcode
 */

add_action( 'plugins_loaded', 'eventbrite_attendees_shortcode' );

function eventbrite_attendees_shortcode() {
	$plugin = new EventbriteAttendeesShortcode();
}

if( !class_exists( 'EventbriteAttendeesShortcode' ) ) {
class EventbriteAttendeesShortcode {
	
	const version = '0.3.3';
	const domain  = 'eventbrite-attendees';
	
	function EventbriteAttendeesShortcode() {
		$this->__construct();
	}
	
	function __construct() {
		register_activation_hook( __FILE__, array( __CLASS__, 'activate' ) );
		
		add_action( 'init', array( __CLASS__, 'activate' ) );
		add_action( 'init', array( __CLASS__, 'locale' ) );
		
		add_action( 'admin_init', array( __CLASS__, 'scripts' ) );
		add_action( 'admin_init', array( __CLASS__, 'styles' ) );
		
		add_filter( 'plugin_action_links', array( __CLASS__, 'plugin_action' ), 10, 2 ); //Add a settings page to the plugin menu
		
		add_shortcode( 'eventbrite-attendees', array( __CLASS__, 'shortcode' ) );
	}
	
	function activate() {
		define( 'EVENTBRITE_ATTENDEE_DIR', plugin_dir_path( __FILE__ ) );
		define( 'EVENTBRITE_ATTENDEE_ADMIN', plugin_dir_path( __FILE__ ) . '/library/admin/' );
		
		define( 'EVENTBRITE_ATTENDEE_CSS', plugins_url( 'library/css', __FILE__ ) );
		define( 'EVENTBRITE_ATTENDEE_JS', plugins_url( 'library/js', __FILE__ ) );
		
		if ( is_admin() ) {
			require_once( EVENTBRITE_ATTENDEE_ADMIN . 'admin.php' );
			require_once( EVENTBRITE_ATTENDEE_ADMIN . 'dashboard.php' );
		}
	}
	
	function locale() {
		load_plugin_textdomain( EventbriteAttendeesShortcode::domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	function scripts() {
		wp_register_script( EventbriteAttendeesShortcode::domain, plugins_url( 'library/js/eventbrite-attendees.js', __FILE__ ), array( 'jquery' ), EventbriteAttendeesShortcode::version, true );
	}
	
	function styles() {
		wp_register_style( EventbriteAttendeesShortcode::domain . '-admin', plugins_url( 'library/css/admin.css', __FILE__ ), false, EventbriteAttendeesShortcode::version, 'screen' );
		wp_register_style( EventbriteAttendeesShortcode::domain . '-tabs', plugins_url( 'library/css/tabs.css', __FILE__ ), false, EventbriteAttendeesShortcode::version, 'screen' );
	}

	/**
	 * RSS shortcode function
	 *
	 * @since 0.1
	 * @use [eventbrite-attendees feed="http://www.eventbrite.com/rss/event_list_attendees/384870157"]
	 */
	function shortcode( $attr ) {
		global $wpdb;
			
		extract( shortcode_atts( array( 
			'feed' => '',
		), $attr ) );
		
		include_once( ABSPATH . WPINC . '/class-simplepie.php' );
		$feed = new SimplePie();
		$feed->set_feed_url( $attr['feed'] );
		$feed->enable_cache( false );
		$feed->init();
		$feed->handle_content_type();
		
		// Lets not set a cache location for localhosts.
		$domain = preg_replace( '|https?://([^/]+)|', '$1', get_option( 'siteurl' ) );
		if ( false !== strpos( $domain, '/' ) || 'localhost' == $domain || preg_match( '|[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+|', $domain ) );
		else
			$feed->set_cache_location( plugin_dir_path( __FILE__ ) . 'cache' );
	
		$items = $feed->get_item();
			
		$out = '<div id="eventbrite-attendees-list" style="clear:both;">';	
		
		if ( empty( $items ) ) :		
			$out .= '<ul>';		
				$out .= '<li>No items to display, please check your <a href="http://www.eventbrite.com/r/thefrosty" rel="external" target="_blank" title="Eventbrite">eventbrite</a> list.</li>';		
			$out .= '</ul>';	
		else :		
			$out .= '<ul>';			
			foreach( $feed->get_items( 0 ) as $item ) :
				$out .= '<li>';		
					//$rss_html .= '<a href="' . $item->get_permalink() . '">' . $item->get_title() . '"</a><br />';
					$out .= '<span>' . $item->get_description() . '</span>';
					$out .= '<hr />';
				$out .= '</li>';
			endforeach;
			
			//$rss_html .= $item[ 'content' ][ 'encoded' ];	   
			$out .= '</ul>';
		
		endif;
		
		$out .= '</div>';
		
		return $out;
	}

	
	/**
	 * RSS shortcode function
	 *
	 * @since 0.1
	 * @use [eventbrite-attendees feed="http://www.eventbrite.com/rss/event_list_attendees/384870157"]
	 */
	function preview( $attr ) {
		global $wpdb;
			
		include_once( ABSPATH . WPINC . '/class-simplepie.php' );
		$feed = new SimplePie();
		$feed->set_feed_url( $attr );
		$feed->enable_cache( false );
		$feed->init();
		$feed->handle_content_type();
		
		// Lets not set a cache location for localhosts.
		$domain = preg_replace( '|https?://([^/]+)|', '$1', get_option( 'siteurl' ) );
		if ( false !== strpos( $domain, '/' ) || 'localhost' == $domain || preg_match( '|[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+|', $domain ) );
		else
			$feed->set_cache_location( plugin_dir_path( __FILE__ ) . 'cache' );
	
		$items = $feed->get_item();
			
		//echo '<pre>';
			//print_r( $items );
		//echo '</pre>';
			
		$out  = '';
		$out .= '<h2>' . __( 'This is a preview of the feed', EventbriteAttendeesShortcode::domain ) . '<br />';
		$out .= '<small>' . __( 'Note: only twelve attendees show in the preview.', EventbriteAttendeesShortcode::domain ) . '</small></h2>';
		$out .= '<p id="eas-toggle" style="font-size:24px; text-align:right"><em>+</em><em style="display:none;">&minus;</em></p>';
		$out .= '<div id="eventbrite-attendees-list" style="clear:both;">';
		
		if ( empty( $items ) ) :		
			$out .= '<ul>';		
				$out .= '<li>No items to display, please check your <a href="http://www.eventbrite.com/r/thefrosty" rel="external" target="_blank" title="Eventbrite">eventbrite</a> list.</li>';		
			$out .= '</ul>';	
		else :		
			$out .= '<ul>';			
			foreach( $feed->get_items( 0, 12 ) as $item ) :
				$out .= '<li>';		
					//$rss_html .= '<a href="' . $item->get_permalink() . '">' . $item->get_title() . '"</a><br />';
					$out .= '<span>' . $item->get_description() . '</span>';
					$out .= '<hr />';
				$out .= '</li>';
			endforeach;
			
			//$rss_html .= $item[ 'content' ][ 'encoded' ];	   
			$out .= '</ul>';
		
		endif;
		
		$out .= '</div>';
		
		return $out;	
	}
	
	/**
	 * Plugin Action /Settings on plugins page
	 * @since 0.2
	 * @package plugin
	 */
	function plugin_action( $links, $file ) {
		if ( $file == 'eventbrite-attendees-shortcode/eventbrite-attendees-shortcode.php' && function_exists( 'admin_url' ) ) {
			$settings_link = '<a href="' . admin_url( 'options-general.php?page=eventbrite-attendees' ) . '">' . __('Settings') . '</a>';
			array_unshift( $links, $settings_link ); // before other links
		}
		return $links;
	}
	
}
};

?>