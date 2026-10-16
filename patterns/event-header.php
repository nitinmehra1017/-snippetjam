<?php
/**
 * Title: Snippet Jam event header
 * Slug: snippetjam/event-header
 * Inserter: no
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$home = home_url( '/' );
?>
<!-- wp:html -->
<a class="sjh-logo" href="<?php echo esc_url( $home ); ?>" aria-label="Snippet Jam home"><span aria-hidden="true">[ / ]</span><span>SNIPPET<br />JAM</span></a>
<nav class="sjh-desktop-nav" aria-label="Main navigation"><a href="<?php echo esc_url( $home . '#sj-events' ); ?>">All events</a><a href="<?php echo esc_url( $home . '#sj-journey' ); ?>">The journey</a><a href="<?php echo esc_url( snippetjam_event_link( 'schedule' ) ); ?>">Schedule</a></nav>
<a class="sjh-header-cta" href="<?php echo esc_url( snippetjam_event_link( 'registration' ) ); ?>">Registration <span aria-hidden="true">↗</span></a>
<details class="sjh-menu"><summary>Menu <span aria-hidden="true">+</span></summary><nav aria-label="Mobile navigation"><a href="<?php echo esc_url( $home ); ?>">Home</a><a href="<?php echo esc_url( $home . '#sj-events' ); ?>">All events</a><a href="<?php echo esc_url( $home . '#sj-journey' ); ?>">The journey</a><a href="<?php echo esc_url( snippetjam_event_link( 'schedule' ) ); ?>">Schedule</a><a href="<?php echo esc_url( snippetjam_event_link( 'registration' ) ); ?>">Registration</a><a href="<?php echo esc_url( snippetjam_event_link( 'venue-contact' ) ); ?>">Venue &amp; contact</a><a href="<?php echo esc_url( snippetjam_event_link( 'faq' ) ); ?>">FAQ</a></nav></details>
<!-- /wp:html -->
