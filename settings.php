<?php
/**
 * RSS Shortcode settings page
 * This file displays all of the available settings
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package RSSShortcode
 */
?>
<div style="float:right; width:33%;">

<div class="postbox open">

<h3>About This Plugin</h3>

<div class="inside">
	<table class="form-table">

	<tr>
		<th style="width:20%;">Description:</th>
		<td><?php echo $plugin_data[ 'Description' ]; ?></td>
	</tr>
	<tr>
		<th style="width:20%;">Version:</th>
		<td><strong><?php echo $plugin_data[ 'Version' ]; ?></strong></td>
	</tr>
	<tr>
		<th style="width:20%;">Documentation:</th>
		<td>Check out the <a class="thickbox" href="<?php echo EVENTBRITE_ATTENDEE_URL . '/readme.html' ?>">readme.html</a> file.</td>
	</tr>
	<tr>
		<th style="width:20%;">Support:</th>
		<td><a href="http://wpcult.com/forum" title="Get support for this plugin">Visit the support forums.</a></td>
	</tr>

	</table>
</div>
</div>

<div class="postbox open">

<h3>Support This Plugin</h3>

<div class="inside">
	<table class="form-table">

	<tr>
		<th style="width:20%;">Donate:</th>
		<td><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7329157" title="Donate on PayPal">PayPal</a>.</td>
	</tr>
	<tr>
		<th style="width:20%;">Rate:</th>
		<td><a href="http://www.wordpress.org/extend/plugins/eventbrite-attendees-shortcode/" title="WordPress.org Rating">This plugin on WordPress.org</a>.</td>
	</tr>
    
	</table>
</div>
</div>

<div class="postbox open">

<h3>About The Author</h3>

<div class="inside">

	<ul>
    
		<li><?php echo $plugin_data[ 'Author' ]; ?>: Freelance web design / developer &amp; WordPress guru. Also head orginizer of <a href="http://wordcamp.la">WordCamp.LA</a></li>
        
		<li><a href="http://twitter.com/TheFrosty" title="Austin Passy on Twitter">Follow me on twitter</a>.</li>
        
		<li>Need a WP expert? <a href="http://frostywebdesigns.com/" title="Frosty Web Designs">Hire me</a>.</li>
        
	</ul>
    
</div>
</div>

<div class="postbox open">

<h3>WPCult Feed</h3>

<div class="inside">

	<?php if ( function_exists( 'ev_feed' ) ) ev_feed( 'http://wpcult.com/feed' );	?>
    
</div>
</div>

</div> <!-- /float:right -->

<div style="float:left; width:66%;">

<div class="postbox open">

<h3>Eventbrite Attendees Preview</h3>

<div class="inside">
	<table class="form-table">
        <tr>
            <th>
            <label for="<?php echo $data['eventbrite_feed']; ?>">Eventbrite Attendee RSS:</label> 
            </th>
            <td>
                <input id="<?php echo $data['eventbrite_feed']; ?>" name="<?php echo $data['eventbrite_feed']; ?>" value="<?php echo $val['eventbrite_feed']; ?>" size="60" /><br />
                Enter the feed address like so:<br />
                <code>http://www.eventbrite.com/rss/event_list_attendees/<strong>384870157</strong></code>
            </td>
   		</tr>
    </table>
    
    <div class="postbox open">
    
    <div style="padding:8px 10px; max-height:540px; overflow-x:none; overflow-y:auto;">
    
		<?php if ( function_exists( 'eventbrite_attendees_preview' ) && ( $val['eventbrite_feed'] != '' ) ) echo eventbrite_attendees_preview( $val['eventbrite_feed'] ); ?>
    
    </div>
    </div>
    
</div>
</div>

</div> <!-- /float:right -->
