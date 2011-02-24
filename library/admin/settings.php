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
    <!--
	<tr>
		<th style="width:20%;">Documentation:</th>
		<td>Check out the <a class="thickbox" href="<?php echo EVENTBRITE_ATTENDEE_URL . '/readme.html' ?>">readme.html</a> file.</td>
	</tr>
    -->
	<tr>
		<th style="width:20%;">Support:</th>
		<td><a href="http://wordpress.org/tags/eventbrite-attendees-shortcode" title="Get support for this plugin">Visit the support forums.</a></td>
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

<h3><a href="http://thefrosty.net">TheFrosty Network</a> feeds</h3>

<div id="tab" class="inside">

	<ul class="tabs">
    
    	<li class="t1 t"><a title="WordCampLA">WordCamp</a></li>
    	<li class="t2 t"><a title="Austin Passy's personal blog">Austin Passy</a></li>
    	<li class="t3 t"><a title="wpWorkShopLA">wpWorkShop</a></li>
        
	</ul>
    
		<?php if ( function_exists( 'thefrosty_network_feed' ) ) thefrosty_network_feed( 'http://wordcamp.la/feed', '1' ); ?>

		<?php if ( function_exists( 'thefrosty_network_feed' ) ) thefrosty_network_feed( 'http://austinpassy.com/feed', '2' );	?>

		<?php if ( function_exists( 'thefrosty_network_feed' ) ) thefrosty_network_feed( 'http://wpworkshop.la/feed', '3' ); ?>
    
</div>
</div>

<div id="uninstall" class="postbox open">

<h3>Uninstaller <span><abbr title="Click here to show the box below">Don't do it!</abbr></span><span class="watchingyou">:O You did it...</span></h3>  
        
<div class="inside">
    <p style="text-align:justify;">If you really have to, use this <a href="../wp-content/plugins/eventbrite-attendees-shortcode/uninstall.php" title="Uninstall the Eventbrite Attendees Shortcode plugin with this script">script</a> to uninstall the plugin and completly remove all options from your WordPress database.</p>
    
    <p><label for="<?php echo $data['eventbrite_hide_ad']; ?>">Hide ad?</label>
    	&nbsp;<input id="<?php echo $data['eventbrite_hide_ad']; ?>" name="<?php echo $data['eventbrite_hide_ad']; ?>" type="checkbox" <?php if ( $val['eventbrite_hide_ad'] ) echo 'checked="checked"'; ?> value="true" />	Please only hide the ad if you've <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7329157" title="Donate on PayPal" class="external">donated</a>.
    </p>
    
</div>
</div>

</div> <!-- /float:right -->

<div style="float:left; width:66%;">

<div class="postbox ad">
	<h3>How to use the shortcode</h3>

<div class="inside">
	<table class="form-table">
        <tr>
            <th colspan="2">
            	<p>
                <label for="example">Example:</label>
                <br />
                <strong>Use the shortcode in your page or posts like this:</strong><br /><br />
                    <code>[eventbrite-attendees feed="<strong>http://www.eventbrite.com/rss/event_list_attendees/</strong>" /]</code>
                    <br />
                    <small>Replacing the URL with your <a href="http://www.eventbrite.com/r/thefrosty" rel="external" target="_blank" title="Eventbrite">eventbrite</a> <em>RSS</em> URL.</small>
                </p>
            </td>
   		</tr>
    </table>
</div>
</div>

<?php if ( $val['eventbrite_hide_ad'] ) : '';
	else : ?>
<div class="postbox ad">
	<h3>
		Nothing to see people.
    </h3>
</div>
<?php endif; ?>


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
                Enter the feed address above like so:<br />
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
