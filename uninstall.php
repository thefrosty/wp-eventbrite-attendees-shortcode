<?php

	//removes database entries from the plugin on deletion

	if ( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

	delete_option( 'eventbrite_attendees_settings' );
	
?>