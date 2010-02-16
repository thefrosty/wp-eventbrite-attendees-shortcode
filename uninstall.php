<?php

	//removes database entries from the plugin on deletion

	//if ( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
	//exit();

	//delete_option( 'eventbrite_attendees_settings' );
	
?>
<html>
<head>
<title>Eventbrite Attendees Uninstall Script</title>
</head>
<body>
<?php
/* Include the bootstrap for setting up WordPress environment */
include( '../../../wp-load.php' );

if ( !is_user_logged_in() )
	wp_die( 'You must be logged in to run this script.' );

if ( !current_user_can( 'install_plugins' ) )
	wp_die( 'You do not have permission to run this script.' );

if ( defined( 'UNINSTALL_EVENTBRITE_ATTENDEES' ) )
	wp_die( 'UNINSTALL_EVENTBRITE_ATTENDEES set somewhere else! It must only be set in uninstall.php' );

define( 'UNINSTALL_EVENTBRITE_ATTENDEES', '' );

if ( !defined( 'UNINSTALL_EVENTBRITE_ATTENDEES' ) || constant( 'UNINSTALL_EVENTBRITE_ATTENDEES' ) == '' ) 
	wp_die( 'UNINSTALL_EVENTBRITE_ATTENDEES must be set to a non-blank value in uninstall.php on line 29' );

?>
<p>This script will uninstall all options created by the <a href='http://austinpassy.com/wordpress-plugins/eventbrite-attendees/'>Eventbrite Attendees</a> plugin.</p>
<?php
if ( $_POST[ 'uninstall' ] ) {
	$plugins = (array)get_option( 'active_plugins' );
	$key = array_search( 'eventbrite-attendees/eventbrite-attendees-shortcode.php', $plugins );
	if ( $key !== false ) {
		unset( $plugins[ $key ] );
		delete_option( 'eventbrite_attendees_settings' ); //Delete options!!
		update_option( 'active_plugins', $plugins );
		echo "Disabled Eventbrite Attendees plugin : <strong>DONE</strong><br />";
	}

	if ( in_array( 'eventbrite-attendees/eventbrite-attendees-shortcode.php', get_option( 'active_plugins' ) ) )
		wp_die( 'Eventbrite Attendees Shortcode is still active. Please disable it on your plugins page first.' );
	echo "<p><strong>Please comment out the UNINSTALL_EVENTBRITE_ATTENDEES <em>define()</em> on line 29 in this file!</strong></p>";
	wp_mail( $current_user->user_email, 'Eventbrite Attendees Shortcode Uninstalled', '' );
} else {
	?>
	<form action='uninstall.php' method='POST'>
	<p>Click UNINSTALL to delete the following options:
	<ol>
	<li>get_option( 'eventbrite_attendees_settings' )</li>
	</ol>
	<input type='hidden' name='uninstall' value='1' />
	<input type='submit' value='UNINSTALL' />
	</form>
	<?php
}

?>
</body>
</html>