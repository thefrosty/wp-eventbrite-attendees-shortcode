<?php
/**
 * Administration functions for loading and displaying the settings page and saving settings 
 * are handled in this file.
 *
 * @package EventbriteAttendeesShortcode
 */

/* Initialize the theme admin functionality. */
add_action( 'wp_loaded', 'eventbrite_attendees_admin_init' );

/**
 * Initializes the theme administration functions.
 *
 * @since 0.3
 */
function eventbrite_attendees_admin_init() {
	add_action( 'admin_menu', 'eventbrite_attendees_settings_page_init' );

	add_action( 'eventbrite_attendees_update_settings_page', 'eventbrite_attendees_save_settings' );
}

/**
 * Sets up the cleaner gallery settings page and loads the appropriate functions when needed.
 *
 * @since 0.3
 */
function eventbrite_attendees_settings_page_init() {
	global $eventbrite_attendees;

	/* Create the theme settings page. */
	$eventbrite_attendees->settings_page = add_options_page( __( 'Eventbrite Attendees Shortcode', EventbriteAttendeesShortcode::domain ), __( 'Eventbrite Attendees Shortcode', EventbriteAttendeesShortcode::domain ), 'edit_plugins', EventbriteAttendeesShortcode::domain, 'eventbrite_attendees_settings_page' );

	/* Register the default theme settings meta boxes. */
	add_action( "load-{$eventbrite_attendees->settings_page}", 'eventbrite_attendees_create_settings_meta_boxes' );

	/* Make sure the settings are saved. */
	add_action( "load-{$eventbrite_attendees->settings_page}", 'eventbrite_attendees_load_settings_page' );

	/* Load the JavaScript and stylehsheets needed for the theme settings. */
	add_action( "load-{$eventbrite_attendees->settings_page}", 'eventbrite_attendees_settings_page_enqueue_script' );
	add_action( "load-{$eventbrite_attendees->settings_page}", 'eventbrite_attendees_settings_page_enqueue_style' );
	add_action( "admin_head-{$eventbrite_attendees->settings_page}", 'eventbrite_attendees_settings_page_load_scripts' );
}

/**
 * Returns an array with the default plugin settings.
 *
 * @since 0.3
 */
function eventbrite_attendees_settings() {	
	$settings = array(
		'eventbrite_feed'	=> '',
	);
	return apply_filters( 'eventbrite_attendees_settings', $settings );
}

/**
 * Function run at load time of the settings page, which is useful for hooking save functions into.
 *
 * @since 0.3
 */
function eventbrite_attendees_load_settings_page() {

	/* Get theme settings from the database. */
	$settings = get_option( 'eventbrite_attendees_settings' );

	/* If no settings are available, add the default settings to the database. */
	if ( empty( $settings ) ) {
		add_option( 'eventbrite_attendees_settings', eventbrite_attendees_settings(), '', 'yes' );

		/* Redirect the page so that the settings are reflected on the settings page. */
		wp_redirect( admin_url( 'options-general.php?page=eventbrite-attendees' ) );
		exit;
	}

	/* If the form has been submitted, check the referer and execute available actions. */
	elseif ( isset( $_POST['eventbrite-attendees-settings-submit'] ) ) {

		/* Make sure the form is valid. */
		check_admin_referer( 'eventbrite-attendees-settings-page' );

		/* Available hook for saving settings. */
		do_action( 'eventbrite_attendees_update_settings_page' );

		/* Redirect the page so that the new settings are reflected on the settings page. */
		wp_redirect( admin_url( 'options-general.php?page=eventbrite-attendees&updated=true' ) );
		exit;
	}
}

/**
 * Validates the plugin settings.
 *
 * @since 0.3
 */
function eventbrite_attendees_save_settings() {

	/* Get the current theme settings. */
	$settings = get_option( 'eventbrite_attendees_settings' );

	$settings['eventbrite_feed'] = esc_url( $_POST['eventbrite_feed'] );

	/* Update the theme settings. */
	$updated = update_option( 'eventbrite_attendees_settings', $settings );
}

/**
 * Registers the plugin meta boxes for use on the settings page.
 *
 * @since 0.3
 */
function eventbrite_attendees_create_settings_meta_boxes() {
	global $eventbrite_attendees;


	add_meta_box( 'eventbrite-attendees-howto-meta-box', __( 'How to use the shortcode', 'eventbrite-attendees' ), 'eventbrite_attendees_howto_meta_box', $eventbrite_attendees->settings_page, 'normal', 'high' );

	add_meta_box( 'eventbrite-attendees-announcement-meta-box', __( 'Announcements', 'eventbrite-attendees' ), 'eventbrite_attendees_announcement_meta_box', $eventbrite_attendees->settings_page, 'normal', 'high' );

	add_meta_box( 'eventbrite-attendees-general-meta-box', __( 'Shortcode Preview', 'eventbrite-attendees' ), 'eventbrite_attendees_general_meta_box', $eventbrite_attendees->settings_page, 'normal', 'high' );

	add_meta_box( 'eventbrite-attendees-about-meta-box', __( 'About', 'eventbrite-attendees' ), 'eventbrite_attendees_about_meta_box', $eventbrite_attendees->settings_page, 'advanced', 'high' );
	
	add_meta_box( 'eventbrite-attendees-support-meta-box', __( 'Support', 'eventbrite-attendees' ), 'eventbrite_attendees_support_meta_box', $eventbrite_attendees->settings_page, 'advanced', 'high' );
	
	add_meta_box( 'eventbrite-attendees-tabs-meta-box', __( 'TheFrosty Network', 'eventbrite-attendees' ), 'eventbrite_attendees_tabs_meta_box', $eventbrite_attendees->settings_page, 'side', 'low' );
}

/**
 * Displays activation meta box.
 *
 * @since 0.3
 */
function eventbrite_attendees_howto_meta_box() { ?>

	<table class="form-table">
        <tr>
            <th colspan="2">
            	<p><strong>Use the shortcode in your page or posts like this:</strong></p>
                <p><code>[eventbrite-attendees feed="<strong>http://www.eventbrite.com/rss/event_list_attendees/</strong>" /]</code></p>
				<p><small>Replacing the URL with your <a href="http://www.eventbrite.com/r/thefrosty" rel="external" target="_blank" title="Eventbrite">eventbrite</a> <em>RSS</em> URL.</small></p>
            </td>
   		</tr>
	</table><!-- .form-table --><?php
}

/**
 * Display an announcement meta box.
 *
 * @since 0.3
 */
function eventbrite_attendees_announcement_meta_box() { ?>

	<iframe allowtransparency="true" src="http://austinpassy.com/custom-login.php" scrolling="no" style="height:50px;width:100%;">
	</iframe><!-- .form-table --><?php
}



/**
 * Displays the settings meta box.
 *
 * @since 0.8
 */
function eventbrite_attendees_general_meta_box() { 
	/* Get the current theme settings. */
	$settings = get_option( 'eventbrite_attendees_settings' );  ?>

	<table class="form-table">
		<tr>
            <th>
            	<label for="eventbrite_feed">Feed URL:</label> 
            </th>
            <td>
                <input id="eventbrite_feed" name="eventbrite_feed" value="<?php echo $settings['eventbrite_feed']; ?>" size="40" style="width:98%" /><br />
                <span>Enter the feed address above like so:<br />
                <code>http://www.eventbrite.com/rss/event_list_attendees/<strong>384870157</strong></code>
                </span>
            </td>
   		</tr>
	</table><!-- .form-table -->
	<div style="padding:8px 10px;"><?php
    	if ( !empty( $settings['eventbrite_feed'] ) ) {
			$feed = new EventbriteAttendeesShortcode;
			echo $feed->preview( $settings['eventbrite_feed'] );
		} ?>
    
    </div><?php
}

/**
 * Displays the about meta box.
 *
 * @since 0.3
 */
function eventbrite_attendees_about_meta_box() {
	$plugin_data = get_plugin_data( EVENTBRITE_ATTENDEE_DIR . 'eventbrite-attendees-shortcode.php' ); ?>

	<table class="form-table">
		<tr>
			<th><?php _e( 'Plugin:', EventbriteAttendeesShortcode::domain ); ?></th>
			<td><?php echo $plugin_data['Title']; ?> <?php echo $plugin_data['Version']; ?></td>
		</tr>
		<tr>
			<th><?php _e( 'Author:', EventbriteAttendeesShortcode::domain ); ?></th>
			<td><?php echo $plugin_data['Author']; ?> &ndash; @<a href="http://twitter.com/TheFrosty" title="Follow me on Twitter">TheFrosty</a></td>
		</tr>
		<tr style="display: none;">
			<th><?php _e( 'Description:', EventbriteAttendeesShortcode::domain ); ?></th>
			<td><?php echo $plugin_data['Description']; ?></td>
		</tr>
	</table><!-- .form-table --><?php
}

/**
 * Displays the support meta box.
 *
 * @since 0.3
 */
function eventbrite_attendees_support_meta_box() { ?>

	<table class="form-table">
        <tr>
            <th><?php _e( 'Donate:', EventbriteAttendeesShortcode::domain ); ?></th>
            <td><?php _e( '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7329157">PayPal</a>.', EventbriteAttendeesShortcode::domain ); ?></td>
        </tr>
        <tr>
            <th><?php _e( 'Rate:', EventbriteAttendeesShortcode::domain ); ?></th>
            <td><?php _e( '<a href="http://wordpress.org/extend/plugins/eventbrite-attendees-shortcode/">This plugin on WordPress.org</a>.', EventbriteAttendeesShortcode::domain ); ?></td>
        </tr>
        <tr>
            <th><?php _e( 'Eventbrite:', EventbriteAttendeesShortcode::domain ); ?></th>
            <td><?php _e( 'Visit <a href="http://www.eventbrite.com/r/thefrosty" rel="external" target="_blank" title="Eventbrite">Eventbrite.com</a>.', EventbriteAttendeesShortcode::domain ); ?></td>
        </tr>
		<tr>
			<th><?php _e( 'Support:', EventbriteAttendeesShortcode::domain ); ?></th>
			<td><?php _e( '<a href="http://wordpress.org/tags/eventbrite-attendees-shortcode">WordPress support forums</a>.', EventbriteAttendeesShortcode::domain ); ?></td>
		</tr>
	</table><!-- .form-table --><?php
}

/**
 * Displays the support meta box.
 *
 * @since 0.3
 */
function eventbrite_attendees_tabs_meta_box() { ?>
	<table class="form-table">
        <div id="tab" class="tabbed inside">
    	
        <ul class="tabs">        
            <li class="t1 t"><a class="t1 tab">Austin Passy</a></li>
            <li class="t2 t"><a class="t2 tab">WordCamp<strong>LA</strong></a></li>
            <li class="t3 t"><a class="t3 tab">Themelit (WP themes)</a></li> 
            <li class="t4 t"><a class="t4 tab">wpWorkShop</a></li>  
            <li class="t5 t"><a class="t5 tab">Float-O-holics</a></li>  
            <li class="t6 t"><a class="t6 tab">Great Escape</a></li>   
            <li class="t7 t"><a class="t7 tab">PDXbyPix</a></li>      
            <li class="t8 t"><a class="t8 tab">Jeana Arter</a></li>             
        </ul>
        
		<?php 
		if ( function_exists( 'thefrosty_network_feed' ) ) {
        	thefrosty_network_feed( 'http://feeds.feedburner.com/TheFrosty', '1' );
			thefrosty_network_feed( 'http://feeds.feedburner.com/WordCampLA', '2' );
        	thefrosty_network_feed( 'http://feeds.feedburner.com/themelit', '3' ); 
       		thefrosty_network_feed( 'http://wpworkshop.la/feed', '4' );
        	thefrosty_network_feed( 'http://floatoholics.com/feed', '5' );
        	thefrosty_network_feed( 'http://greatescapecabofishing.com/feed', '6' ); 
        	thefrosty_network_feed( 'http://pdxbypix.com/feed', '7' );  
        	thefrosty_network_feed( 'http://feeds.feedburner.com/JeanaArter', '8' );  
		} ?>
        
    	</div>
	</table><!-- .form-table --><?php
}

/**
 * Displays a settings saved message.
 *
 * @since 0.3
 */
function eventbrite_attendees_settings_update_message() { ?>
	<p class="updated fade below-h2" style="padding: 5px 10px;">
		<strong><?php _e( 'Don&prime;t you feel good. You just saved me.', EventbriteAttendeesShortcode::domain ); ?></strong>
	</p><?php
}

/**
 * Outputs the HTML and calls the meta boxes for the settings page.
 *
 * @since 0.3
 */
function eventbrite_attendees_settings_page() {
	global $eventbrite_attendees;

	$plugin_data = get_plugin_data( EVENTBRITE_ATTENDEE_DIR . 'eventbrite-attendees-shortcode.php' ); ?>

	<div class="wrap">
		
        <?php if ( function_exists( 'screen_icon' ) ) screen_icon(); ?>
        
		<h2><?php _e( 'Eventbrite Attendees Shortcode Settings', 'eventbrite-attendees' ); ?></h2>

		<?php if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) eventbrite_attendees_settings_update_message(); ?>

		<div id="poststuff">

			<form method="post" action="<?php esc_url( admin_url( 'options-general.php?page=eventbrite-attendees' ) ); ?>">

				<?php wp_nonce_field( 'eventbrite-attendees-settings-page' ); ?>
				<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
				<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>

				<div class="metabox-holder">
					<div class="post-box-container column-1 normal"><?php do_meta_boxes( $eventbrite_attendees->settings_page, 'normal', $plugin_data ); ?></div>
					<div class="post-box-container column-2 advanced"><?php do_meta_boxes( $eventbrite_attendees->settings_page, 'advanced', $plugin_data ); ?></div>
					<div class="post-box-container column-3 side" style="clear:both;"><?php do_meta_boxes( $eventbrite_attendees->settings_page, 'side', $plugin_data ); ?></div>
				</div>

				<p class="submit" style="clear: both;">
					<input type="submit" name="Submit"  class="button-primary" value="<?php _e( 'Update Settings', 'eventbrite-attendees' ); ?>" />
					<input type="hidden" name="eventbrite-attendees-settings-submit" value="true" />
				</p><!-- .submit -->

			</form>

		</div><!-- #poststuff -->

	</div><!-- .wrap --><?php
}

/**
 * Loads the scripts needed for the settings page.
 *
 * @since 0.3
 */
function eventbrite_attendees_settings_page_enqueue_script() {	
	wp_enqueue_script( 'common' );
	wp_enqueue_script( 'wp-lists' );
	wp_enqueue_script( 'postbox' );
	wp_enqueue_script( EventbriteAttendeesShortcode::domain );
}

/**
 * Loads the stylesheets needed for the settings page.
 *
 * @since 0.3
 */
function eventbrite_attendees_settings_page_enqueue_style() {
	wp_enqueue_style( EventbriteAttendeesShortcode::domain . '-admin' );
	wp_enqueue_style( EventbriteAttendeesShortcode::domain . '-tabs' );
}

/**
 * Loads the metabox toggle JavaScript in the settings page head.
 *
 * @since 0.3
 */
function eventbrite_attendees_settings_page_load_scripts() {
	global $eventbrite_attendees; ?>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			postboxes.add_postbox_toggles( '<?php echo $eventbrite_attendees->settings_page; ?>' );
		});
		//]]>
	</script><?php
}

/**
 * TheFrosty Network Feed
 * @since 0.2
 * @package Admin
 */
if ( !function_exists( 'thefrosty_network_feed' ) ) {
	function thefrosty_network_feed( $attr, $count ) {		
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
		echo '<div class="t' . $count . ' tab-content postbox open feed">';		
		echo '<ul>';		
		if ( empty( $items ) ) { 
			echo '<li>No items</li>';		
		} else {
			foreach( $feed->get_items( 0, 3 ) as $item ) : ?>		
				<li>		
					<a href='<?php echo $item->get_permalink(); ?>' title='<?php echo $item->get_description(); ?>'><?php echo $item->get_title(); ?></a><br /> 		
					<span style="font-size:10px; color:#aaa;"><?php echo $item->get_date('F, jS Y | g:i a'); ?></span>		
				</li>		
			<?php endforeach;
		}
		echo '</ul>';		
		echo '</div>';
	}
}

?>