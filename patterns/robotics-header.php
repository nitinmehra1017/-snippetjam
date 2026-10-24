<?php
/**
 * Title: Snippet Jam Robotics Challenge header
 * Slug: snippetjam/robotics-header
 * Inserter: no
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!-- wp:html -->
<a class="sjr-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Snippet Jam home"><span aria-hidden="true">[ / ]</span><span>SNIPPET<br />JAM</span></a>
<nav class="sjr-nav" aria-label="Robotics navigation"><a href="#sjr-explore">Explore</a><a href="#sjr-eligibility">Eligibility</a><a href="#sjr-faq">FAQ</a></nav>
<a class="sjr-header-register" href="<?php echo esc_url( snippetjam_event_link( 'registration' ) ); ?>">Registration <span aria-hidden="true">↗</span></a>
<details class="sjr-menu"><summary>Menu <span aria-hidden="true">+</span></summary><nav aria-label="Mobile robotics navigation"><a href="<?php echo esc_url( home_url( '/#sj-events' ) ); ?>">All events</a><a href="#sjr-explore">Explore</a><a href="#sjr-eligibility">Eligibility</a><a href="#sjr-preparation">Preparation</a><a href="#sjr-faq">FAQ</a><a href="<?php echo esc_url( snippetjam_event_link( 'registration' ) ); ?>">Registration</a></nav></details>
<!-- /wp:html -->
