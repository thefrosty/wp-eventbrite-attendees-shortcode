===Eventbrite Attendees Shortcode ===
Contributors: austyfrosty
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7329157
Tags: eventbrite, widget-only, event, attendee, shortcode, json 
Requires at least: 3.7
Tested up to: 3.9
Stable tag: trunk

A shortcode to output your Eventbrite attendee list.

== Description ==

A shortcode to pull in your [Eventbrite](http://www.eventbrite.com/r/thefrosty) attendees list.

Example shortcode useage:

`[eventbrite-attendees id="384870157"]`

More options: `[eventbrite-attendees id="YOUR_EVENT_ID" sort="true|false" clickable="true|false" app_key="APP_KEY(IF_NOT_SET_IN_SETTINGS)"]`

Shortcode args:

1. *id*: with your Eventbrite event id.
2. *sort*: Should the attendee list be sorted by puchase date?
3. *clickable*: Should links be clickable?
4. *app_key*: Your developer app key if not saved in the settings.

Leave any comments about the [Eventbrite Attendees Shortcode](http://austin.passy.co/wordpress-plugins/eventbrite-attendees-shortcode/) [here](http://austin.passy.co/wordpress-plugins/eventbrite-attendees-shortcode/).

== Installation ==

Follow the steps below to install the plugin.

1. Upload the `eventbrite-attendees-shortcode` directory to the /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings/eventbrite-attendees to enter your App Key.
4. Visit any post page and enter the shorcode or use the shortcode generator.

== Frequently Asked Questions ==

= Donations? =

Please! Or support my by visting [Extendd.com](http://extendd.com); A premium WordPress plugin marketplace.

= Why create this plugin? =

I created this plugin to easily show your attendees from any event you've created on [Eventbrite](http://www.eventbrite.com/r/thefrosty).

== Screenshots ==

1. Eventbrite Attendees Shortcode Settings page.
2. Shortcode generator on post page.

== Changelog ==

= Version 1.0 (2/20/14) =

* Well hello there! Everything is new. 
* Be sure to get your developer API Key and enter it in the settings.

= Version 0.3.3 (11/8/11) =

* Feeds updated.
* WordPress 3.3 check.

= Version 0.3.2 (9/8/11) =

* Dashboard fix.

= Version 0.3.1 (6/23/11) =

* [BUG FIX] An error in the dashboard widget is casuing some large images. Sorry. Always escape.

= Version 0.3 = 

* Complete rewrite and overhaul.

= Version 0.2.1&alpha; = 

* Removed javscript link causing hang-ups.

= Version 0.2&alpha; =

* `array_slice` fix.
* Spelling fixes

= Version 0.1 =

* Admin upgrade.
* RSS feed changed to *list* items in **one** listed element.

= Version 0.1&alpha; =

* Initial release.

== Upgrade Notice ==

= Version 1.0 =

* Complete code rewrite. Everything is new! Now using the Eventbrite Developer API.
