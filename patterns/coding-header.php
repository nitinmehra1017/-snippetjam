<?php
/**
 * Title: Snippet Jam Coding Competition header
 * Slug: snippetjam/coding-header
 * Inserter: no
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!-- wp:html -->
<a class="sjc-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Snippet Jam home"><span aria-hidden="true">[ / ]</span><span>SNIPPET<br />JAM</span></a>
<nav class="sjc-nav" aria-label="Competition navigation"><a href="#sjc-rounds">Round desk</a><a href="#sjc-eligibility">Eligibility</a><a href="#sjc-faq">FAQ</a></nav>
<a class="sjc-header-register" href="<?php echo esc_url( snippetjam_event_link( 'registration' ) ); ?>">Registration <span aria-hidden="true">↗</span></a>
<details class="sjc-menu"><summary>Menu <span aria-hidden="true">+</span></summary><nav aria-label="Mobile competition navigation"><a href="<?php echo esc_url( home_url( '/#sj-events' ) ); ?>">All events</a><a href="#sjc-rounds">Round desk</a><a href="#sjc-eligibility">Eligibility</a><a href="#sjc-preparation">Preparation</a><a href="#sjc-faq">FAQ</a><a href="<?php echo esc_url( snippetjam_event_link( 'registration' ) ); ?>">Registration</a></nav></details>
<!-- /wp:html -->
