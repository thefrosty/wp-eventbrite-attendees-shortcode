<?php
/**
 * Plugin Name: Eventbrite Attendees Shortcode
 * Plugin URI: http://austinpassy.com/wordpress-plugins/eventbrite-attendees-shortcode/
 * Description: Adds your attendee list from your eventbrite RSS feed.
 * Version: 0.2&alpha;
 * Author: Austin &ldquo;Frosty&rdquo; Passy
 * Author URI: http://austinpassy.com
 *
 * Developers can learn more about the WordPress shortcode API:
 * @link http://codex.wordpress.org/Shortcode_API
 *
 * @copyright 2009 - 2010
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

/**
 * Make sure we get the correct directory.
 * @since 0.1
 */
	if ( !defined( 'WP_CONTENT_URL' ) )
		define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
	if ( !defined( 'WP_CONTENT_DIR' ) )
		define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
	if ( !defined( 'WP_PLUGIN_URL' ) )
		define('WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
	if ( !defined( 'WP_PLUGIN_DIR' ) )
		define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

/**
 * Define constant paths to the plugin folder.
 * @since 0.1
 */
	define( EVENTBRITE_ATTENDEE, WP_PLUGIN_DIR . '/eventbrite-attendees-shortcode' );
	define( EVENTBRITE_ATTENDEE_URL, WP_PLUGIN_URL . '/eventbrite-attendees-shortcode' );
	
	define( EVENTBRITE_ATTENDEE_ADMIN, WP_PLUGIN_DIR . '/eventbrite-attendees-shortcode/library/admin' );
	define( EVENTBRITE_ATTENDEE_CSS, WP_PLUGIN_URL . '/eventbrite-attendees-shortcode/library/css' );
	define( EVENTBRITE_ATTENDEE_JS, WP_PLUGIN_URL . '/eventbrite-attendees-shortcode/library/js' );

/**
 * Add the settings page to the admin menu.
 * @since 0.1
 */
	add_action( 'admin_init', 'eventbrite_attendees_admin_init' );
	add_action( 'admin_menu', 'eventbrite_attendees_add_pages' );

/**
 * Filters.
 * @since 0.2
 */	
	add_filter( 'plugin_action_links', 'eventbrite_attendees_plugin_actions', 10, 2 ); //Add a settings page to the plugin menu
	
/**
 * Load the RSS Shortcode settings if in the WP admin.
 * @since 0.1
 */
	if ( is_admin() )
		require_once( EVENTBRITE_ATTENDEE_ADMIN . '/settings-admin.php' );

/**
 * If not in the WP admin, load the settings from the database.
 * @since 0.1
 */
	if ( !is_admin() )
		$eventbrite_attendees = get_option( 'eventbrite_attendees_settings' );
	
/**
 * Add Shortcode
 * @since 0.1
 */
	add_shortcode( 'eventbrite-attendees', 'eventbrite_attendees' );

 /**
 * Load the stylesheets
 * @since 0.2
 */   
function eventbrite_attendees_admin_init() {
	wp_register_style( 'eventbrite-attendees-tabs', EVENTBRITE_ATTENDEE_CSS . '/tabs.css' );
}

/**
 * Function to add the settings page
 * @since 0.1
 */
function eventbrite_attendees_add_pages() {
	if ( function_exists( 'add_options_page' ) ) 
		$page = add_options_page( 'Eventbrite Attendees Shortcode Settings', 'Eventbrite Attendees', 10, 'eventbrite-attendees.php', eventbrite_attendees_theme_page );
			add_action( 'admin_print_styles-' . $page, 'eventbrite_attendees_admin_style' );
			add_action( 'admin_print_scripts-' . $page, 'eventbrite_attendees_admin_script' );
}

/**
 * Function to add the style to the settings page
 * @since 0.2
 */
function eventbrite_attendees_admin_style() {
	wp_enqueue_style( 'thickbox' );
	wp_enqueue_style( 'eventbrite-attendees-tabs' );
}

/**
 * Function to add the script to the settings page
 * @since 0.2
 */
function eventbrite_attendees_admin_script() {
	wp_enqueue_script( 'eventbrite-attendees', EVENTBRITE_ATTENDEE_JS . '/eventbrite-attendees.js', array( 'jquery' ), '0.1', false );
}
	
/**
 * RSS shortcode function
 *
 * @since 0.1
 * @use [eventbrite-attendees feed="http://www.eventbrite.com/rss/event_list_attendees/384870157"]
 */
function eventbrite_attendees( $atts ) {
	
	global $wpdb;
		
		extract( shortcode_atts( array( 
			
			'feed' => '',
		
		), $atts ) );
		
		include_once( ABSPATH . WPINC . '/rss.php' );
		
		$rss = fetch_rss( $atts[ 'feed' ] );
		
		$items = array_slice( $rss->items, 0 );
		
		$rss_html = '<div id="eventbrite-attendees-list" style="clear:both;">';
		
		if ( empty( $items ) ) :
			
			$rss_html .= '<ul>';
			
				$rss_html .= '<li>No items to display, please check your <a href="http://www.eventbrite.com/r/thefrosty" rel="external" target="_blank" title="Eventbrite">eventbrite</a> idlist.</li>';
			
			$rss_html .= '</ul>';
		
		else :
			
			$rss_html .= '<ul>';
				
			foreach ( $items as $item ) :
				
				$rss_html .= '<li>';
	
					$rss_html .= $item[ 'content' ][ 'encoded' ];
					
				$rss_html .= '<hr />';
				
				$rss_html .= '</li>';
			
			endforeach;
		   
			$rss_html .= '</ul>';
		
		endif;
		
		$rss_html .= '</div>';
		
	return $rss_html;

}

/**
 * RSS shortcode function
 *
 * @since 0.1
 * @use [eventbrite-attendees feed="http://www.eventbrite.com/rss/event_list_attendees/384870157"]
 */
function eventbrite_attendees_preview( $atts ) {
	
	global $wpdb;
		
		include_once( ABSPATH . WPINC . '/rss.php' );
		
		$rss = fetch_rss( $atts );
		
		$items = array_slice( $rss->items, 0 );
		
		$rss_html = '<div id="eventbrite-attendees-list" style="clear:both;">';
		
		if ( empty( $items ) ) :
			
			$rss_html .= '<ul>';
			
				$rss_html .= '<li>No items to display, please check your <a href="http://www.eventbrite.com/r/thefrosty" rel="external" target="_blank" title="Eventbrite">eventbrite</a> list.</li>';
			
			$rss_html .= '</ul>';
		
		else :
			
			$rss_html .= '<ul>';
				
			foreach ( $items as $item ) :
				
				$rss_html .= '<li>';
	
					$rss_html .= $item[ 'content' ][ 'encoded' ];
					
				$rss_html .= '<hr />';
				
				$rss_html .= '</li>';
			
			endforeach;
		   
			$rss_html .= '</ul>';
		
		endif;
		
		$rss_html .= '</div>';
		
	return $rss_html;

}

/**
 * TheFrosty Network Feed
 * @since 0.2
 * @package Admin
 */
if ( !function_exists( 'thefrosty_network_feed' ) ) :
	function thefrosty_network_feed( $attr, $count ) {
		
		global $wpdb;
		
		include_once( ABSPATH . WPINC . '/rss.php' );
		
		$rss = fetch_rss( $attr );
		
		$items = array_slice( $rss->items, 0, 3 );
		
		echo '<div class="tab-content t' . $count . ' postbox open feed">';
		
		echo '<ul>';
		
		if ( empty( $items ) ) echo '<li>No items</li>';
		
		else
		
		foreach ( $items as $item ) : ?>
		
		<li>
		
		<a href='<?php echo $item[ 'link' ]; ?>' title='<?php echo $item[ 'description' ]; ?>'><?php echo $item[ 'title' ]; ?></a><br /> 
		
		<span style="font-size:10px; color:#aaa;"><?php echo date( 'F, j Y', strtotime( $item[ 'pubdate' ] ) ); ?></span>
		
		</li>
		
		<?php endforeach;
		
		echo '</ul>';
		
		echo '</div>';
		
	}
endif;

/**
 * Plugin Action /Settings on plugins page
 * @since 0.2
 * @package plugin
 */
function eventbrite_attendees_plugin_actions( $links, $file ) {
 	if( $file == 'eventbrite-attendees-shortcode/eventbrite-attendees-shortcode.php' && function_exists( "admin_url" ) ) {
		$settings_link = '<a href="' . admin_url( 'options-general.php?page=eventbrite-attendees.php' ) . '">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
}

?>