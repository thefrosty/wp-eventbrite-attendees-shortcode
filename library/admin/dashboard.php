<?php

if ( !class_exists( 'Extendd_Dashboard_Widget' ) ) :
	class Extendd_Dashboard_Widget {
		
		const domain = 'extendd';
		
		public function __construct() {			
			add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widget' ) );
		}
		
		function add_dashboard_widget() {		
			wp_add_dashboard_widget( 'extendd_dashboard', __( 'Extendd.com <em>feeds</em>', self::domain ), array( $this, 'widget' ) );
		}
		
		/**
		 * Dashboard widget
		 */
		function widget( $args ) {
			$defaults = array(
				'items' => 4,
				'feed' 	=> 'http://extendd.com/feed/?post_type=download',
			);
			
			$args = wp_parse_args( $args, $defaults );
						
			$rss_items = $this->fetch_rss_items( $args['items'], $args['feed'] );
			
			$content = '<ul>';
			if ( !$rss_items ) {
				$content .= '<li>' . __( 'Error fetching feed', $this->domain ) . '</li>';
			} else {
				foreach ( $rss_items as $item ) {
					$url = preg_replace( '/#.*/', '', esc_url( $item->get_permalink(), null, 'display' ) );
					$content .= '<li>';
					$content .= '<a class="rsswidget" href="' . $url . 'utm_medium=wpadmin_dashboard&utm_term=newsitem&utm_campaign=eventbrite-attendees-shortocode">' .
						esc_html( $item->get_title() ) . '</a> ';
					$content .= '</li>';
				}
			}
			$content .= '</ul>';
			$content .= '<ul class="social">';
				$content .= sprintf( 
					'<li>%s <span class="genericon genericon-facebook"></span><a href="https://www.facebook.com/WPExtendd">%s</a> | ' .
					'%s <span class="genericon genericon-twitter"></span><a href="http://twitter.com/WPExtendd">@WPExtendd</a></li>',
						__( 'Like Extendd on', self::domain ),
						__( 'Facebook', self::domain ),
						__( 'Follow', self::domain )
				);
				
				$content .= sprintf(
					'<li class="twitter"><span class="genericon genericon-twitter"></span> %s <a href="https://twitter.com/TheFrosty">@TheFrosty</a></li>',
					__( 'Follow', self::domain )
				);	
			$content .= '</ul>';
			
			$this->postbox( 'extenddlatest', __( 'Latest plugins from Extendd.com', Eventbrite_Attendees_Shortcode::domain ), $content );
		}
		
		/**
		 * Create a potbox widget.
		 *
		 * @param 	string $id      ID of the postbox.
		 * @param 	string $title   Title of the postbox.
		 * @param 	string $content Content of the postbox.
		 */
		private function postbox( $id, $title, $content, $group = false ) { ?>
            <div class="inside"><?php echo $content; ?></div><?php
		}
		
		/**
		 * Fetch RSS items from the feed.
		 *
		 * @param 	int    $num  Number of items to fetch.
		 * @param 	string $feed The feed to fetch.
		 * @return 	array|bool False on error, array of RSS items on success.
		 */
		private function fetch_rss_items( $num, $feed ) {
			if ( !function_exists( 'fetch_feed' ) )
				include_once( ABSPATH . WPINC . '/feed.php' );
			
			add_filter( 'wp_feed_cache_transient_lifetime', function() {
				return WEEK_IN_SECONDS;
			});	
			$rss = fetch_feed( $feed );
			remove_all_filters( 'wp_feed_cache_transient_lifetime' );
	
			// Bail if feed doesn't work
			if ( !$rss || is_wp_error( $rss ) )
				return false;
	
			$rss_items = $rss->get_items( 0, $rss->get_item_quantity( $num ) );
	
			// If the feed was erroneous 
			if ( !$rss_items ) {
				$md5 = md5( $feed );
				delete_transient( 'feed_' . $md5 );
				delete_transient( 'feed_mod_' . $md5 );
				$rss       = fetch_feed( $feed );
				$rss_items = $rss->get_items( 0, $rss->get_item_quantity( $num ) );
			}
	
			return $rss_items;
		}
		
	}
	new Extendd_Dashboard_Widget;
endif;