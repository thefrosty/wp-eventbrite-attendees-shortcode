<?php if ( !empty( $value ) ) : ?>
	<li class="eb-attendee-list-item eb-<?php echo sanitize_html_class( $name ); ?>">
	<?php echo isset( $clickable ) && $clickable ? make_clickable( $value ) : esc_attr( $value ); ?>
	</li>
<?php endif; ?>