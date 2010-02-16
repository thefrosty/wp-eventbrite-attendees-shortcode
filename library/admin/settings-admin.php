<?php
/**
 * RSS Shortcode administration settings
 * These are the functions that allow users to select options
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package RSSShortcode
 */

/**
 * Returns an array of all the settings defaults
 * Other admin functions can grab this to use
 *
 * @since 0.1
 */
function eventbrite_attendees_settings_args() {
	$settings_arr = array(
		'eventbrite_feed'	 => '',
		'eventbrite_hide_ad' => '',
	);
	return $settings_arr;
}

/**
 * Handles the main plugin settings
 *
 * @since 0.1
 */
function eventbrite_attendees_theme_page() {

	/*
	* Main settings variables
	*/
	$plugin_name = 'Eventbrite Attendees Shortcode';
	$settings_page_title = 'Eventbrite Attendees Shortcode Settings';
	$hidden_field_name = 'eventbrite_attendees_submit_hidden';
	$plugin_data = get_plugin_data( EVENTBRITE_ATTENDEE . '/eventbrite-attendees-shortcode.php');

	/*
	* Grabs the default plugin settings
	*/
	$settings_arr = eventbrite_attendees_settings_args();

	/*
	* Add a new option to the database
	*/
	add_option( 'eventbrite_attendees_settings', $settings_arr );

	/*
	* Set form data IDs the same as settings keys
	* Loop through each
	*/
	$settings_keys = array_keys( $settings_arr );
	foreach ( $settings_keys as $key ) :
		$data[$key] = $key;
	endforeach;

	/*
	* Get existing options from database
	*/
	$settings = get_option( 'eventbrite_attendees_settings' );

	foreach ( $settings_arr as $key => $value ) :
		$val[$key] = $settings[$key];
	endforeach;

	/*
	* If any information has been posted, we need
	* to update the options in the database
	*/
	if ( $_POST[$hidden_field_name] == 'Y' ) :

		/*
		* Loops through each option and sets it if needed
		*/
		foreach ( $settings_arr as $key => $value ) :
			$settings[$key] = $val[$key] = $_POST[$data[$key]];
		endforeach;

		/*
		* Update plugin settings
		*/
		update_option( 'eventbrite_attendees_settings', $settings );
	
	elseif ($_POST[$hidden_field_name] == 'R') :

		foreach($settings_arr as $key => $value) :
			$settings[$key] = $val[$key] = $_POST[$data[$key]];
		endforeach;

		delete_option( 'eventbrite_attendees_settings', $settings );

	/*
	* Output the settings page
	*/
	?>

		<div class="wrap">
			<h2><?php echo $settings_page_title; ?></h2>

		<div class="updated" style="margin: 15px 0;">
			<p><strong>Don&prime;t you feel good. You just saved me!</strong></p>
		</div>

	<?php else : ?>

		<div class="wrap">
        <?php if ( function_exists( 'screen_icon' ) ) screen_icon(); ?>
			<h2><?php echo $settings_page_title; ?></h2>
	<?php
	endif;
?>

			<div id="poststuff">

				<form name="form0" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>" style="border:none;background:transparent;">

					<?php require_once( EVENTBRITE_ATTENDEE_ADMIN . '/settings.php' ); ?>

					<p class="submit" style="clear:both; float:left;">
						<input type="submit" name="Submit"  class="button-primary" value="Save Changes" />
						<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y" />
					</p>

				</form>
                
                <form name="form0" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" style="border:none;background:transparent;">
                
                    <p class="submit" style="float:left; margin-left:10px;">
                        <input type="submit" name="Reset" class="swg_warning" value="Delete/Reset" onclick="return confirm('Do you really want to delete/reset the plugin settings?');" />
                        <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="R" />
                    </p>
            
                </form>

			</div>
			<br style="clear:both;" />

		</div>
<?php
}

?>