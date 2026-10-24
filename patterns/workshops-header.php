<?php
/**
 * Title: Snippet Jam Workshops header
 * Slug: snippetjam/workshops-header
 * Inserter: no
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!-- wp:html -->
<a class="sjw-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Snippet Jam home"><span aria-hidden="true">[ / ]</span><span>SNIPPET<br />JAM</span></a>
<nav class="sjw-nav" aria-label="Workshops navigation"><a href="#sjw-tracks">Explore</a><a href="#sjw-eligibility">Eligibility</a><a href="#sjw-faq">FAQ</a></nav>
<a class="sjw-header-register" href="<?php echo esc_url( snippetjam_event_link( 'registration' ) ); ?>">Registration <span aria-hidden="true">↗</span></a>
<details class="sjw-menu"><summary>Menu <span aria-hidden="true">+</span></summary><nav aria-label="Mobile workshops navigation"><a href="<?php echo esc_url( home_url( '/#sj-events' ) ); ?>">All events</a><a href="#sjw-tracks">Explore</a><a href="#sjw-eligibility">Eligibility</a><a href="#sjw-preparation">Preparation</a><a href="#sjw-faq">FAQ</a><a href="<?php echo esc_url( snippetjam_event_link( 'registration' ) ); ?>">Registration</a></nav></details>
<!-- /wp:html -->
