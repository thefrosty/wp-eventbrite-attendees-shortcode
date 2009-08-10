<?php
/**
 * Plugin Name: Eventbrite Attendees Shortcode
 * Plugin URI: http://wpcult.com/eventbrite-attendees-shortcode-plugin
 * Description: Adds your attendee list from your eventbrite RSS feed.
 * Version: 0.1&alpha;
 * Author: Austin 'Frosty' Passy
 * Author URI: http://austinpassy.com
 *
 * Developers can learn more about the WordPress shortcode API:
 * @link http://codex.wordpress.org/Shortcode_API
 *
 * @copyright 2009
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

/**
 * Add the settings page to the admin menu.
 * @since 0.1
 */
	add_action( 'admin_menu', 'eventbrite_attendees_add_pages' );

/**
 * Load the RSS Shortcode settings if in the WP admin.
 * @since 0.1
 */
	if ( is_admin() )
		require_once( EVENTBRITE_ATTENDEE . '/settings-admin.php' );

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
 * Function to add the settings page
 * @since 0.1
 */
function eventbrite_attendees_add_pages() {
	if ( function_exists( 'add_options_page' ) ) 
		add_options_page( 'Eventbrite Attendees Shortcode Settings', 'Eventbrite Attendees', 10, 'eventbrite-attendees.php', eventbrite_attendees_theme_page );
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
			
			'feed'		=> '',
		
		), $atts ) );
		
		include_once( ABSPATH . WPINC . '/rss.php' );
		
		$rss = fetch_rss( $atts[ 'feed' ] );
		
		$items = array_slice( $rss->items, 0 );
		
		$rss_html = '<div id="eventbrite-attendees-list" style="clear:both;">';
		
		if ( empty( $items ) ) :
			
			$rss_html .= '<ul>';
			
				$rss_html .= '<li>No items.</li>';
			
			$rss_html .= '</ul>';
		
		else :
				
			foreach ( $items as $item ) :
			
			$rss_html .= '<ul>';
				
				$rss_html .= '<li>';
	
					$rss_html .= $item[ 'content' ][ 'encoded' ];
					
				$rss_html .= '<hr />';
				
				$rss_html .= '</li>';
		   
			$rss_html .= '</ul>';
			
			endforeach;
		
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
			
				$rss_html .= '<li>No items.</li>';
			
			$rss_html .= '</ul>';
		
		else :
				
			foreach ( $items as $item ) :
			
			$rss_html .= '<ul>';
				
				$rss_html .= '<li>';
	
					$rss_html .= $item[ 'content' ][ 'encoded' ];
					
				$rss_html .= '<hr />';
				
				$rss_html .= '</li>';
		   
			$rss_html .= '</ul>';
			
			endforeach;
		
		endif;
		
		$rss_html .= '</div>';
		
	return $rss_html;

}

/**
 * RSS WPCult Feed
 * @since 0.1
 * @package Admin
 */
function ev_feed( $attr ) {
	
	global $wpdb;
	
	include_once( ABSPATH . WPINC . '/rss.php' );
	
	$rss = fetch_rss( $attr );
	
	$items = array_slice( $rss->items, 0, 3 );
	
	echo '<ul id="wpcult-feed">';
	
	if ( empty( $items ) ) echo '<li>No items</li>';
	
	else
	
	foreach ( $items as $item ) : ?>
    
	<li>
    
    <a href='<?php echo $item[ 'link' ]; ?>' title='<?php echo $item[ 'description' ]; ?>'><?php echo $item[ 'title' ]; ?></a><br /> 
    
	<span style="font-size:10px; color:#aaa;"><?php echo date( 'F, j Y', strtotime( $item[ 'pubdate' ] ) ); ?></span>
    
    </li>
    
	<?php endforeach;
	
	echo '</ul>';
	
}

?>