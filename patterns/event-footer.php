<?php
/**
 * Title: Snippet Jam event footer
 * Slug: snippetjam/event-footer
 * Inserter: no
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$home = home_url( '/' );
?>
<!-- wp:html -->
<div class="sjh-footer-top"><a class="sjh-wordmark" href="<?php echo esc_url( $home ); ?>" aria-label="Snippet Jam home">SNIPPET<br />JAM<span aria-hidden="true">↗</span></a><nav aria-label="Footer navigation"><a href="<?php echo esc_url( $home . '#sj-events' ); ?>">All events</a><a href="<?php echo esc_url( snippetjam_event_link( 'about' ) ); ?>">About</a><a href="<?php echo esc_url( snippetjam_event_link( 'schedule' ) ); ?>">Schedule</a><a href="<?php echo esc_url( snippetjam_event_link( 'registration' ) ); ?>">Registration</a><a href="<?php echo esc_url( snippetjam_event_link( 'venue-contact' ) ); ?>">Venue &amp; contact</a><a href="<?php echo esc_url( snippetjam_event_link( 'faq' ) ); ?>">FAQ</a><a href="#sjh-main">Back to top ↑</a></nav></div>
<div class="sjh-footer-bottom"><p>A UNIVERSITY TECHNOLOGY-EVENT WEBSITE PROJECT.</p><a href="<?php echo esc_url( $home ); ?>">BACK TO THE JAM <span aria-hidden="true">↗</span></a></div>
<!-- /wp:html -->
