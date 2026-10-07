<?php
/** Homepage presentation. Important content is rendered by WordPress, not JavaScript. */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$data = snippetjam_homepage_data();
$links = $data['links'];
$journey = array(
    array( 'Register', 'Choose your event and enter the challenge.' ),
    array( 'Form Your Team', 'Find collaborators and bring different skills together.' ),
    array( 'Choose the Challenge', 'Understand the problem and decide what you want to build.' ),
    array( 'Build', 'Code, prototype, experiment, and turn the idea into something real.' ),
    array( 'Get Mentored', 'Use feedback and guidance to sharpen the project.' ),
    array( 'Present', 'Show what you built and explain the thinking behind it.' ),
    array( 'Celebrate', 'Share the result, meet other builders, and celebrate the work.' ),
);
?>
<!-- wp:html -->
<section class="sj-hero" aria-labelledby="sj-hero-title">
    <div class="sj-hero-top"><p class="sj-eyebrow">SNIPPET JAM / 2026</p><p class="sj-eyebrow sj-hero-edition">CAMPUS TECHNOLOGY / IN THE MAKING</p></div>
    <h1 id="sj-hero-title" class="sj-hero-title"><span>CODE.</span><span class="sj-outline">COMPETE.</span><span class="sj-yellow">CREATE.</span></h1>
    <div class="sj-hero-aside">
        <div class="sj-poster-mark" aria-hidden="true"><span class="sj-mark-corner">SJ—26</span><svg viewBox="0 0 240 240" fill="none" stroke="currentColor" stroke-width="3"><path d="M56 48L8 120L56 192M184 48L232 120L184 192M152 16L88 224"/><path d="M28 28H212V212H28Z" stroke-width="1"/></svg><span class="sj-mark-bottom">IDEAS → INTO THINGS</span></div>
        <p><?php echo esc_html( $data['hero_context'] ); ?></p>
        <a class="sj-text-link" href="#sj-events">Find your event <span aria-hidden="true">↓</span></a>
    </div>
    <div class="sj-hero-bottom"><p>ONE IDEA IS A START.<br />WHAT YOU MAKE OF IT IS THE JAM.</p><a href="#sj-about" class="sj-scroll-link"><span>SCROLL TO EXPLORE</span><span aria-hidden="true">↓</span></a></div>
</section>

<section id="sj-about" class="sj-about sj-section" aria-labelledby="sj-about-title" data-reveal>
    <p class="sj-section-label"><span class="sj-section-number">01</span> / THE IDEA</p>
    <div class="sj-about-grid"><h2 id="sj-about-title" class="sj-section-title">Good ideas<br />need a place<br />to <em>collide.</em></h2><div class="sj-about-copy"><p class="sj-lead"><?php echo esc_html( $data['about'] ); ?></p><p>From a line of code to a working prototype, the interesting part is what happens when different ways of thinking meet.</p><a class="sj-text-link" href="<?php echo esc_url( $links['about'] ); ?>">About Snippet Jam <span aria-hidden="true">↗</span></a><p class="sj-about-note">A UNIVERSITY TECHNOLOGY-EVENT PROJECT<br />BUILT AROUND CAMPUS CURIOSITY.</p></div></div>
    <div class="sj-verbs" aria-hidden="true"><span>BUILD</span><span>×</span><span>SOLVE</span><span>×</span><span>LEARN</span></div>
</section>

<section id="sj-events" class="sj-events sj-section" aria-labelledby="sj-events-title" data-reveal>
    <div class="sj-section-heading"><p class="sj-section-label"><span class="sj-section-number">02</span> / THE EVENT INDEX</p><h2 id="sj-events-title" class="sj-section-title">Pick your<br />playground.</h2><p>Four ways into the same curiosity.<br />Explore an event to find out more.</p></div>
    <div id="snippetjam-react-root"><?php snippetjam_render_event_index( $data['events'] ); ?></div>
</section>

<section id="sj-journey" class="sj-journey sj-section" aria-labelledby="sj-journey-title" data-reveal>
    <p class="sj-section-label"><span class="sj-section-number">03</span> / YOUR JOURNEY</p>
    <div class="sj-journey-grid"><div class="sj-journey-aside"><h2 id="sj-journey-title" class="sj-section-title">FROM IDEA<br />TO DEMO.</h2><p>Follow the Snippet Jam journey from registration to the final showcase.</p><div id="snippetjam-journey-progress"><div class="sj-journey-meter"><span class="sj-progress-number" aria-hidden="true">01</span><span class="sj-progress-label">OF 07<br />JOURNEY STEPS</span></div></div><a href="#sj-journey-01" class="sj-text-link">Follow the journey <span aria-hidden="true">↓</span></a></div>
        <ol class="sj-journey-list"><?php foreach ( $journey as $index => $step ) : ?><li id="sj-journey-<?php echo esc_attr( sprintf( '%02d', $index + 1 ) ); ?>" data-journey-step><span class="sj-step-number"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span><div><h3><button type="button" class="sj-journey-trigger" aria-label="<?php echo esc_attr( sprintf( 'Select step %02d: %s', $index + 1, $step[0] ) ); ?>" aria-describedby="sj-journey-description-<?php echo esc_attr( sprintf( '%02d', $index + 1 ) ); ?>" disabled><span><?php echo esc_html( $step[0] ); ?></span></button></h3><p id="sj-journey-description-<?php echo esc_attr( sprintf( '%02d', $index + 1 ) ); ?>"><?php echo esc_html( $step[1] ); ?></p></div></li><?php endforeach; ?></ol>
    </div>
</section>

<section id="sj-schedule" class="sj-schedule sj-section" aria-labelledby="sj-schedule-title" data-reveal>
    <div class="sj-section-heading"><p class="sj-section-label"><span class="sj-section-number">04</span> / THE PROGRAMME</p><h2 id="sj-schedule-title" class="sj-section-title">Make room<br />for the jam.</h2><div><p class="sj-schedule-status">Schedule coming soon.</p><a class="sj-text-link" href="<?php echo esc_url( $links['schedule'] ); ?>">Full schedule <span aria-hidden="true">↗</span></a></div></div>
    <div class="sj-schedule-key" aria-hidden="true"><span>INDEX</span><span>EVENT</span><span>FORMAT</span></div>
    <?php
    $programme_formats = array(
        'hackathon' => 'BUILD / TEAM',
        'coding-competition' => 'SOLVE / CODE',
        'robotics-challenge' => 'DESIGN / BUILD',
        'workshops' => 'LEARN / MAKE',
    );
    ?>
    <ol class="sj-schedule-list"><?php foreach ( $data['events'] as $event ) : ?><li><span class="sj-schedule-number"><?php echo esc_html( $event['number'] ); ?></span><a href="<?php echo esc_url( $event['url'] ); ?>"><?php echo esc_html( $event['title'] ); ?><span aria-hidden="true">↗</span></a><span class="sj-schedule-format"><?php echo esc_html( $programme_formats[ $event['slug'] ] ); ?></span></li><?php endforeach; ?></ol>
    <p class="sj-schedule-note">Four ways in. One place to build.</p>
</section>

<section class="sj-why sj-section" aria-labelledby="sj-why-title" data-reveal>
    <p class="sj-section-label"><span class="sj-section-number">05</span> / WHY SHOW UP</p><h2 id="sj-why-title" class="sj-section-title">Less watching.<br /><span>More making.</span></h2>
    <p class="sj-why-intro"><?php echo esc_html( $data['why'] ); ?></p>
    <div class="sj-reasons"><div><span class="sj-reason-symbol" aria-hidden="true">+</span><h3>Think together.</h3><p>Share a perspective. Encounter another one. Give an idea somewhere to go.</p></div><div><span class="sj-reason-symbol" aria-hidden="true">↗</span><h3>Try something.</h3><p>Take code off the screen and into a problem, a prototype, or a new experiment.</p></div><div><span class="sj-reason-symbol" aria-hidden="true">×</span><h3>Make it yours.</h3><p>Find the track that interests you and explore what you could create.</p></div></div>
</section>

<section id="sj-registration" class="sj-registration sj-section" aria-labelledby="sj-registration-title" data-reveal>
    <div><p class="sj-section-label"><span class="sj-section-number">06</span> / YOUR NEXT MOVE</p><h2 id="sj-registration-title" class="sj-section-title">BRING<br />YOUR<br /><span>NEXT IDEA.</span></h2></div><div class="sj-registration-aside"><span class="sj-registration-arrow" aria-hidden="true">↗</span><p><?php echo esc_html( $data['registration'] ); ?></p><a class="sj-button sj-button-dark" href="<?php echo esc_url( $links['registration'] ); ?>">View registration <span aria-hidden="true">↗</span></a><p class="sj-registration-footnote">START WITH CURIOSITY.</p></div>
</section>

<section id="sj-venue" class="sj-venue sj-section" aria-labelledby="sj-venue-title" data-reveal>
    <p class="sj-section-label"><span class="sj-section-number">07</span> / MEET ON CAMPUS</p><div class="sj-venue-grid"><h2 id="sj-venue-title" class="sj-section-title">A place<br />to connect.</h2><div class="sj-venue-copy"><p class="sj-lead"><?php echo esc_html( $data['venue'] ); ?></p><p>Confirmed information will be published on the venue &amp; contact page.</p><a class="sj-text-link" href="<?php echo esc_url( $links['venue-contact'] ); ?>">Venue &amp; contact <span aria-hidden="true">↗</span></a></div><div class="sj-campus-mark" aria-hidden="true"><span>ON<br />CAMPUS.</span><svg viewBox="0 0 240 200" fill="none" stroke="currentColor" stroke-width="2"><path d="M32 168H208M50 168V76L120 28L190 76V168M30 84L120 12L210 84M80 168V96M120 168V96M160 168V96M50 96H190"/></svg><small>LOCATION / TO BE ANNOUNCED</small></div></div>
</section>

<section id="sj-faq" class="sj-faq sj-section" aria-labelledby="sj-faq-title" data-reveal>
    <div class="sj-faq-aside"><p class="sj-section-label"><span class="sj-section-number">08</span> / A FEW QUESTIONS</p><h2 id="sj-faq-title" class="sj-section-title">Before<br />you jump in.</h2><a class="sj-text-link" href="<?php echo esc_url( $links['faq'] ); ?>">Full FAQ <span aria-hidden="true">↗</span></a></div><div class="sj-faq-list"><?php foreach ( $data['faq'] as $index => $item ) : ?><details name="sj-faq"><summary><span class="sj-faq-number"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span><h3><?php echo esc_html( $item['question'] ); ?></h3><span class="sj-faq-icon" aria-hidden="true">+</span></summary><div class="sj-faq-answer"><?php echo wp_kses_post( $item['answer'] ); ?></div></details><?php endforeach; ?></div>
</section>
<script id="snippetjam-homepage-data" type="application/json"><?php echo wp_json_encode( array( 'events' => $data['events'] ), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?></script>
<!-- /wp:html -->
<?php
// The existing Home copy is incorporated above; do not append its old cards/H1.
// Preserve WordPress's posts index when that is the site's existing reading mode.
if ( 'posts' === get_option( 'show_on_front' ) ) :
    ?>
<!-- wp:group {"className":"sj-cms-content","layout":{"type":"constrained"}} -->
<div class="wp-block-group sj-cms-content">
    <!-- wp:heading --><h2 class="wp-block-heading">From campus</h2><!-- /wp:heading -->
    <!-- wp:pattern {"slug":"twentytwentyfive/template-query-loop"} /-->
</div>
<!-- /wp:group -->
<?php endif; ?>
