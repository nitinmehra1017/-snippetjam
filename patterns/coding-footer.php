<?php
/**
 * Title: Snippet Jam Coding Competition footer
 * Slug: snippetjam/coding-footer
 * Inserter: no
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!-- wp:html -->
<div class="sjc-footer-grid"><a class="sjc-wordmark" href="<?php echo esc_url( home_url( '/' ) ); ?>">SNIPPET<br />JAM<span aria-hidden="true">↗</span></a><nav aria-label="Footer navigation"><a href="<?php echo esc_url( home_url( '/#sj-events' ) ); ?>">All events</a><a href="<?php echo esc_url( snippetjam_event_link( 'hackathon' ) ); ?>">Hackathon</a><a href="<?php echo esc_url( snippetjam_event_link( 'schedule' ) ); ?>">Schedule</a><a href="<?php echo esc_url( snippetjam_event_link( 'registration' ) ); ?>">Registration</a><a href="<?php echo esc_url( snippetjam_event_link( 'venue-contact' ) ); ?>">Venue &amp; contact</a><a href="#sjc-main">Back to top ↑</a></nav></div>
<p class="sjc-footer-note">SNIPPET JAM / THINK IT THROUGH. WRITE IT DOWN.</p>
<!-- /wp:html -->
