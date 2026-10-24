<?php
/** Robotics presentation. WordPress renders the original title and page body. */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$registration = snippetjam_event_link( 'registration' );
$modules = array(
    array( 'sense', 'Sense', 'Turn the world into a signal.', 'A sensor offers an observation, not the whole story. Think about what you need to detect, what might introduce noise, and what an unexpected reading could mean.', 'Compare repeated observations. What would make you trust a reading?', 'WORLD → OBSERVATION' ),
    array( 'decide', 'Decide', 'Give the signal some meaning.', 'A controller connects observations to an action. Sketch the conditions that change a decision and consider what should happen when information is missing.', 'Draw a simple decision flow before writing the control logic.', 'OBSERVATION → DECISION' ),
    array( 'move', 'Move', 'Close the loop with feedback.', 'An actuator turns a decision into a physical response. Consider how you would observe the result, test a small change and adjust your approach.', 'Describe how you would tell whether a response matched your intention.', 'DECISION → RESPONSE → OBSERVATION' ),
);
$questions = array(
    array( 'Who can take part?', 'Eligibility has not been confirmed here. Check the official page information and published registration requirements before entering.' ),
    array( 'Do I need to bring a robot or a kit?', 'Equipment provision and permitted hardware are still to be confirmed. Do not assume a kit is supplied or purchase equipment based on the concept illustration.' ),
    array( 'What is the actual challenge?', 'The official task, format, judging and technical constraints are still to be announced. Sense, Decide and Move are learning concepts, not competition stages or rules.' ),
    array( 'Is this an individual or team event?', 'The entry format and any team-size requirements remain unconfirmed. Refer to the published registration information when available.' ),
    array( 'Where will registration details appear?', 'Follow the registration link below for published entry information. This page does not confirm registration availability, dates or event logistics.' ),
);
?>
<!-- wp:group {"tagName":"section","className":"sjr-hero","layout":{"type":"default"}} -->
<section class="wp-block-group sjr-hero">
    <!-- wp:html --><div class="sjr-hero-meta"><a class="sjr-back" href="<?php echo esc_url( home_url( '/#sj-events' ) ); ?>"><span aria-hidden="true">↖</span> All events</a><p class="sjr-label">SNIPPET JAM / 03 / EXPERIMENT</p></div><!-- /wp:html -->
    <!-- wp:post-title {"level":1,"className":"sjr-title"} /-->
    <!-- wp:html -->
    <div class="sjr-hero-grid"><div class="sjr-hero-copy"><p class="sjr-kicker">CODE MEETS<br /><em>THE PHYSICAL<br />WORLD.</em></p><p>Between an instruction and a movement,<br />there is a lot to discover.</p><a class="sjr-link" href="#sjr-explore">Explore the system <span aria-hidden="true">↓</span></a></div><figure class="sjr-hero-drawing"><div class="sjr-drawing-label"><span>FIELD NOTES / ROBOTICS</span><span aria-hidden="true">+ R</span></div><?php require get_stylesheet_directory() . '/assets/robotics-schematic.svg'; ?><figcaption>Illustrative concept. Not a competition specification.</figcaption></figure></div>
    <div class="sjr-hero-foot"><span class="sjr-label">OBSERVE. REASON. MAKE A MOVE.</span><a href="#sjr-overview">Read the brief ↓</a></div>
    <!-- /wp:html -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","anchor":"sjr-overview","className":"sjr-section sjr-overview","layout":{"type":"default"}} -->
<section id="sjr-overview" class="wp-block-group sjr-section sjr-overview">
    <!-- wp:html --><div><p class="sjr-label">01 / THE FIELD NOTES</p><h2 class="sjr-heading">Thinking<br />outside<br /><em>the screen.</em></h2></div><!-- /wp:html -->
    <!-- wp:group {"className":"sjr-brief","layout":{"type":"default"}} -->
    <div class="wp-block-group sjr-brief">
        <!-- wp:paragraph {"className":"sjr-intro"} --><p class="sjr-intro">Robotics brings code, observation and physical response into the same conversation. Start with a question. Learn from what happens next.</p><!-- /wp:paragraph -->
        <!-- wp:post-content {"className":"sjr-cms-content","layout":{"type":"default"}} /-->
        <!-- wp:paragraph {"className":"sjr-note"} --><p class="sjr-note">Check the published event information for confirmed details. The challenge format, technical requirements and logistics remain pending unless stated in the official rules.</p><!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:html -->
<section id="sjr-explore" class="sjr-section sjr-explore" aria-labelledby="sjr-explore-title"><div class="sjr-section-top"><p class="sjr-label">02 / EXPLORE THE SYSTEM</p><span class="sjr-stamp">CONCEPT STUDY / NOT EVENT RULES</span></div><div class="sjr-explore-intro"><h2 id="sjr-explore-title" class="sjr-heading">Not just parts.<br /><em>A conversation.</em></h2><p>Open a concept to trace the relationship between observation, control and movement. The official Robotics Challenge task is still to be confirmed.</p></div>
    <div class="sjr-lab"><div class="sjr-module-list"><?php foreach ( $modules as $index => $module ) : ?><details name="sjr-modules" data-robotics-module="<?php echo esc_attr( $module[0] ); ?>"<?php if ( 0 === $index ) : ?> open<?php endif; ?>><summary><span class="sjr-module-number"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span><span class="sjr-module-name"><?php echo esc_html( $module[1] ); ?></span><span class="sjr-plus" aria-hidden="true">+</span></summary><div class="sjr-module-copy"><h3><?php echo esc_html( $module[2] ); ?></h3><p><?php echo esc_html( $module[3] ); ?></p><p class="sjr-prompt"><span class="sjr-label">A QUESTION TO TRY</span><?php echo esc_html( $module[4] ); ?></p><p class="sjr-signal"><?php echo esc_html( $module[5] ); ?></p></div></details><?php endforeach; ?></div>
    <figure class="sjr-system"><div class="sjr-drawing-label"><span>CONCEPT MAP / OPEN A MODULE</span><span aria-hidden="true">+ R</span></div><?php require get_stylesheet_directory() . '/assets/robotics-schematic.svg'; ?><figcaption><div id="snippetjam-robotics-focus"><p class="sjr-focus"><span class="sjr-label">SYSTEM VIEW</span><span aria-live="polite" aria-atomic="true">Sense. Decide. Move.</span></p></div><p class="sjr-system-note">Illustrative only. Hardware and challenge requirements are not specified by this drawing.</p></figcaption></figure></div>
</section>

<section id="sjr-preparation" class="sjr-section sjr-preparation" aria-labelledby="sjr-preparation-title"><div class="sjr-prep-heading"><p class="sjr-label">03 / BEFORE YOU BUILD</p><h2 id="sjr-preparation-title" class="sjr-heading">Bring questions.<br /><em>Keep notes.</em></h2><p>Starting points for learning.<br />Not an equipment list or entry requirements.</p></div><ol class="sjr-prep-list"><li><span class="sjr-label">01 / MAP</span><div><h3>Sketch the whole loop.</h3><p>Draw what a system observes, how it decides and what it changes. Paper is a useful place to begin.</p></div></li><li><span class="sjr-label">02 / LEARN</span><div><h3>Know the tools you use.</h3><p>Review the basics of the programming tools you already know. Consult equipment documentation before any practical work.</p></div></li><li><span class="sjr-label">03 / REFLECT</span><div><h3>Change one thing.</h3><p>For a practice exercise, record what you expected, what happened and what you would investigate next.</p></div></li></ol></section>

<section id="sjr-eligibility" class="sjr-section sjr-eligibility" aria-labelledby="sjr-eligibility-title"><div><p class="sjr-label">04 / BEFORE YOU ENTER</p><h2 id="sjr-eligibility-title" class="sjr-heading">Curiosity first.<br /><em>Check the brief.</em></h2></div><div><p class="sjr-intro">Eligibility and technical requirements are still to be confirmed.</p><p class="sjr-eligibility-note">Read the official event and registration information before making plans or buying equipment.</p><dl class="sjr-requirements"><div><dt>Participant eligibility</dt><dd>To be confirmed</dd></div><div><dt>Entry &amp; team format</dt><dd>To be confirmed</dd></div><div><dt>Hardware &amp; software</dt><dd>To be confirmed</dd></div></dl><a class="sjr-link" href="<?php echo esc_url( $registration ); ?>">Check registration information <span aria-hidden="true">↗</span></a></div></section>

<section id="sjr-faq" class="sjr-section sjr-faq" aria-labelledby="sjr-faq-title"><div><p class="sjr-label">05 / OPEN QUESTIONS</p><h2 id="sjr-faq-title" class="sjr-heading">Before<br /><em>the first move.</em></h2><a class="sjr-link" href="<?php echo esc_url( snippetjam_event_link( 'faq' ) ); ?>">More event questions <span aria-hidden="true">↗</span></a></div><div class="sjr-faq-list"><?php foreach ( $questions as $question ) : ?><details><summary><?php echo esc_html( $question[0] ); ?><span class="sjr-plus" aria-hidden="true">+</span></summary><p><?php echo esc_html( $question[1] ); ?></p></details><?php endforeach; ?></div></section>

<section id="sjr-registration" class="sjr-section sjr-registration" aria-labelledby="sjr-registration-title"><div><p class="sjr-label">06 / YOUR NEXT SIGNAL</p><h2 id="sjr-registration-title">MAKE<br /><em>THE NEXT</em><br />CONNECTION.</h2></div><div class="sjr-entry"><div class="sjr-connection" aria-hidden="true"><span></span><i></i><span></span></div><p>Explore where code can take you.</p><p class="sjr-entry-note">Registration availability and entry details will come from the published event information.</p><a class="sjr-register" href="<?php echo esc_url( $registration ); ?>">View registration <span aria-hidden="true">↗</span></a><a class="sjr-link" href="<?php echo esc_url( snippetjam_event_link( 'schedule' ) ); ?>">Check the schedule <span aria-hidden="true">↗</span></a></div></section>
<!-- /wp:html -->
