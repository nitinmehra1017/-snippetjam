<?php
/**
 * Title: Snippet Jam Workshops footer
 * Slug: snippetjam/workshops-footer
 * Inserter: no
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!-- wp:html -->
<div class="sjw-footer-grid"><a class="sjw-wordmark" href="<?php echo esc_url( home_url( '/' ) ); ?>">SNIPPET<br />JAM<span aria-hidden="true">↗</span></a><nav aria-label="Footer navigation"><a href="<?php echo esc_url( home_url( '/#sj-events' ) ); ?>">All events</a><a href="<?php echo esc_url( snippetjam_event_link( 'hackathon' ) ); ?>">Hackathon</a><a href="<?php echo esc_url( snippetjam_event_link( 'coding-competition' ) ); ?>">Coding Competition</a><a href="<?php echo esc_url( snippetjam_event_link( 'robotics-challenge' ) ); ?>">Robotics Challenge</a><a href="<?php echo esc_url( snippetjam_event_link( 'schedule' ) ); ?>">Schedule</a><a href="<?php echo esc_url( snippetjam_event_link( 'registration' ) ); ?>">Registration</a><a href="<?php echo esc_url( snippetjam_event_link( 'venue-contact' ) ); ?>">Venue &amp; contact</a><a href="#sjw-main">Back to top ↑</a></nav></div>
<p class="sjw-footer-note">SNIPPET JAM / MAKE ROOM FOR A NEW IDEA.</p>
<!-- /wp:html -->
