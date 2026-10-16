<?php
/** Event presentation; the original page body is rendered by the Post Content block. */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$steps = array(
    array( 'Register', 'Choose your event and enter the challenge.' ),
    array( 'Form Your Team', 'Find collaborators and bring different skills together.' ),
    array( 'Choose the Challenge', 'Understand the problem and decide what you want to build.' ),
    array( 'Build', 'Code, prototype, experiment, and turn the idea into something real.' ),
    array( 'Get Mentored', 'Use feedback and guidance to sharpen the project.' ),
    array( 'Present', 'Show what you built and explain the thinking behind it.' ),
    array( 'Celebrate', 'Share the result, meet other builders, and celebrate the work.' ),
);
$registration = snippetjam_event_link( 'registration' );
?>
<!-- wp:group {"tagName":"section","anchor":"sjh-top","className":"sjh-hero","layout":{"type":"default"}} -->
<section id="sjh-top" class="wp-block-group sjh-hero">
    <!-- wp:html -->
    <div class="sjh-hero-top"><a class="sjh-back" href="<?php echo esc_url( home_url( '/#sj-events' ) ); ?>"><span aria-hidden="true">↖</span> ALL EVENTS</a><p class="sjh-eyebrow">SNIPPET JAM / EVENT 01</p></div>
    <!-- /wp:html -->
    <!-- wp:post-title {"level":1,"className":"sjh-title"} /-->
    <!-- wp:html -->
    <div class="sjh-hero-grid">
        <div class="sjh-hero-copy"><p class="sjh-hero-statement">A small idea.<br />A working thing.</p><p class="sjh-hero-note">A place to build together.<br />Bring your curiosity. See where it goes.</p><a class="sjh-link" href="#sjh-overview">Explore the Hackathon <span aria-hidden="true">↓</span></a></div>
        <div class="sjh-build-mark" aria-hidden="true"><div class="sjh-mark-top"><span>INPUT / IDEA</span><span>01</span></div><svg viewBox="0 0 360 220" fill="none" stroke="currentColor"><path d="M20 35H340M20 185H340M65 15V205M295 15V205" stroke-width="1" opacity=".3"/><path d="M115 65L65 110L115 155M245 65L295 110L245 155M205 42L155 178" stroke-width="5"/><path d="M32 24H12V44M328 24H348V44M32 196H12V176M328 196H348V176" stroke-width="2"/><path d="M320 92V128M302 110H338" stroke-width="2"/></svg><p>BUILD<br />TOGETHER.</p><div class="sjh-mark-bottom"><span>OUTPUT / POSSIBILITY</span><span>↗</span></div></div>
    </div>
    <div class="sjh-hero-foot"><p class="sjh-eyebrow">FROM FIRST THOUGHT → FIRST PROTOTYPE</p><a href="#sjh-journey">Follow the build <span aria-hidden="true">↓</span></a></div>
    <!-- /wp:html -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","anchor":"sjh-overview","className":"sjh-section sjh-overview","layout":{"type":"default"}} -->
<section id="sjh-overview" class="wp-block-group sjh-section sjh-overview">
    <!-- wp:html --><p class="sjh-section-label"><span>01</span> / THE OVERVIEW</p><h2 class="sjh-heading">The idea is yours.<br />The making<br />is together.</h2><!-- /wp:html -->
    <!-- wp:post-content {"className":"sjh-cms-content","layout":{"type":"default"}} /-->
</section>
<!-- /wp:group -->

<!-- wp:html -->
<section id="sjh-journey" class="sjh-section sjh-journey" aria-labelledby="sjh-journey-title">
    <p class="sjh-section-label"><span>02</span> / THE BUILD LOG</p>
    <div class="sjh-journey-grid"><div class="sjh-journey-aside"><h2 id="sjh-journey-title" class="sjh-heading">ONE IDEA.<br />MANY MOVES.</h2><p>Follow the Snippet Jam journey from registration to the final showcase.</p><div id="snippetjam-hackathon-progress"><div class="sjh-progress" data-enhanced="false" style="--sjh-progress:1"><div class="sjh-progress-count"><span aria-hidden="true">07</span><p><span class="sjh-eyebrow">SEVEN STAGES / YOUR JOURNEY</span><span class="sjh-progress-name" aria-live="polite" aria-atomic="true">From idea to demo</span></p></div><div class="sjh-progress-track" aria-hidden="true"><span></span></div></div></div><p class="sjh-journey-instruction">Open a step to explore it.</p></div>
        <div class="sjh-journey-list"><?php foreach ( $steps as $index => $step ) : ?><details name="sjh-journey" data-hackathon-step="<?php echo esc_attr( (string) $index ); ?>"<?php if ( 0 === $index ) : ?> open<?php endif; ?>><summary><span class="sjh-step-number"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span><h3><?php echo esc_html( $step[0] ); ?></h3><span class="sjh-step-arrow" aria-hidden="true">↗</span></summary><div class="sjh-step-copy"><p><?php echo esc_html( $step[1] ); ?></p></div></details><?php endforeach; ?></div>
    </div>
</section>

<section id="sjh-preparation" class="sjh-section sjh-preparation" aria-labelledby="sjh-preparation-title"><p class="sjh-section-label"><span>03</span> / BEFORE YOU BUILD</p><div class="sjh-preparation-grid"><div><h2 id="sjh-preparation-title" class="sjh-heading">Bring your<br /><em>curiosity.</em></h2><p class="sjh-preparation-intro">Some starting points for your thinking.</p><a class="sjh-link" href="<?php echo esc_url( snippetjam_event_link( 'faq' ) ); ?>">Explore the FAQ <span aria-hidden="true">↗</span></a></div><ol class="sjh-preparation-list"><li><span class="sjh-eyebrow">01 / THE QUESTION</span><h3>Start with a problem.</h3><p>Think about a problem you want to explore and who it could help.</p></li><li><span class="sjh-eyebrow">02 / THE TOOLKIT</span><h3>Know your tools.</h3><p>Take stock of the languages, software, or prototyping tools you enjoy working with.</p></li><li><span class="sjh-eyebrow">03 / THE POSSIBILITY</span><h3>Leave room to learn.</h3><p>Write down what you’d like to try, ask, or understand.</p></li></ol></div><div class="sjh-details-note"><p>Event details coming soon.</p><a href="<?php echo esc_url( snippetjam_event_link( 'schedule' ) ); ?>">View schedule <span aria-hidden="true">↗</span></a></div></section>

<section id="sjh-registration" class="sjh-section sjh-registration" aria-labelledby="sjh-registration-title"><div><p class="sjh-section-label"><span>04</span> / YOUR NEXT MOVE</p><h2 id="sjh-registration-title" class="sjh-heading">PUT YOUR<br />IDEA IN<br />MOTION.</h2></div><div class="sjh-registration-aside"><span class="sjh-registration-arrow" aria-hidden="true">↗</span><p>Registration details coming soon.</p><a class="sjh-register" href="<?php echo esc_url( $registration ); ?>">View registration <span aria-hidden="true">↗</span></a><a class="sjh-registration-faq" href="<?php echo esc_url( snippetjam_event_link( 'faq' ) ); ?>">Questions? Explore the FAQ <span aria-hidden="true">↗</span></a></div></section>
<script id="snippetjam-hackathon-data" type="application/json"><?php echo wp_json_encode( array( 'steps' => array_column( $steps, 0 ) ), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?></script>
<!-- /wp:html -->
