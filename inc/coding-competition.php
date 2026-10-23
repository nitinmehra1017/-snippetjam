<?php
/** Competition presentation. Official page content stays in the native Post Content block. */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$registration = snippetjam_event_link( 'registration' );
$rounds = array(
    array( 'Read', 'Find the real question.', 'Before writing code, identify the input, the expected output and the constraints. Try a small example by hand.', 'Write down the assumptions you are making.', 'input → constraints → output' ),
    array( 'Solve', 'Make the reasoning work.', 'Sketch a straightforward approach. Compare it with another, then choose the data structures that fit the problem.', 'Explain why your approach produces the right answer.', 'idea → algorithm → implementation' ),
    array( 'Verify', 'Look for the breaking point.', 'Try boundary values, repeated inputs and the smallest valid case. Revisit time and space usage as the input grows.', 'Find a case that challenges your first solution.', 'test → inspect → refine' ),
);
$questions = array(
    array( 'Who can enter?', 'Eligibility requirements have not been confirmed here. Check the published competition information and registration page before entering.' ),
    array( 'Which languages can I use?', 'The permitted languages and execution environment are still to be confirmed. Practise with tools you know, then check the official rules when published.' ),
    array( 'Are these the official competition rounds?', 'No. Read, Solve and Verify are a practice workflow for approaching a problem. The official round format, timings and scoring are still to be announced.' ),
    array( 'Do I enter alone or with a team?', 'The entry format is still to be confirmed. Do not assume an individual or team entry until the registration requirements are published.' ),
    array( 'Where can I find registration information?', 'Use the registration link below for published entry information. Registration availability and event logistics have not been confirmed on this page.' ),
);
?>
<!-- wp:group {"tagName":"section","className":"sjc-hero","layout":{"type":"default"}} -->
<section class="wp-block-group sjc-hero">
    <!-- wp:html --><div class="sjc-hero-meta"><a href="<?php echo esc_url( home_url( '/#sj-events' ) ); ?>"><span aria-hidden="true">↖</span> All events</a><p class="sjc-label">SNIPPET JAM / EVENT 02 / SOLVE</p></div><!-- /wp:html -->
    <!-- wp:group {"className":"sjc-hero-grid","layout":{"type":"default"}} -->
    <div class="wp-block-group sjc-hero-grid">
        <!-- wp:group {"className":"sjc-title-column","layout":{"type":"default"}} -->
        <div class="wp-block-group sjc-title-column">
            <!-- wp:post-title {"level":1,"className":"sjc-title"} /-->
            <!-- wp:html --><p class="sjc-deck">A good solution starts<br />before the first line.</p><a class="sjc-link" href="#sjc-rounds">Open the problem desk <span aria-hidden="true">↓</span></a><!-- /wp:html -->
        </div>
        <!-- /wp:group -->
        <!-- wp:html -->
        <div class="sjc-worksheet" aria-hidden="true"><div class="sjc-sheet-top"><span>THE PROBLEM DESK</span><span>02 / C</span></div><div class="sjc-brackets"><span>{</span><span>?</span><span>}</span></div><p>THINK.<br />WRITE.<br /><span>RETHINK.</span></p><div class="sjc-sheet-bottom"><span>LESS GUESSWORK.<br />MORE REASONING.</span><span>↗</span></div></div>
        <!-- /wp:html -->
    </div>
    <!-- /wp:group -->
    <!-- wp:html --><div class="sjc-hero-foot"><span class="sjc-label">CURIOSITY IS A GOOD STARTING POINT.</span><a href="#sjc-overview">Read the brief ↓</a></div><!-- /wp:html -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","anchor":"sjc-overview","className":"sjc-section sjc-overview","layout":{"type":"default"}} -->
<section id="sjc-overview" class="wp-block-group sjc-section sjc-overview">
    <!-- wp:html --><div><p class="sjc-label">01 / THE BRIEF</p><h2 class="sjc-heading">Different paths.<br />One clear<br /><em>solution.</em></h2></div><!-- /wp:html -->
    <!-- wp:group {"className":"sjc-brief","layout":{"type":"default"}} -->
    <div class="wp-block-group sjc-brief">
        <!-- wp:paragraph {"className":"sjc-intro"} --><p class="sjc-intro">Explore code as a way of thinking. Break a problem down, make your reasoning visible, and learn from the cases you missed.</p><!-- /wp:paragraph -->
        <!-- wp:post-content {"className":"sjc-cms-content","layout":{"type":"default"}} /-->
        <!-- wp:paragraph {"className":"sjc-note"} --><p class="sjc-note">Check the official competition information before registering. Round format, eligibility and event logistics remain pending unless confirmed in the published rules.</p><!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:html -->
<section id="sjc-rounds" class="sjc-section sjc-rounds" aria-labelledby="sjc-rounds-title">
    <div class="sjc-section-top"><p class="sjc-label">02 / COMPETITION ROUNDS</p><span class="sjc-status">OFFICIAL FORMAT / TO BE ANNOUNCED</span></div>
    <div class="sjc-round-intro"><h2 id="sjc-rounds-title" class="sjc-heading">Work the problem.<br /><em>Then work it again.</em></h2><p>Explore a problem-solving workflow while the official rounds are being confirmed. These are practice prompts, not competition rules.</p></div>
    <div class="sjc-round-desk">
        <aside class="sjc-round-aside"><span class="sjc-label">YOUR WORKING NOTES</span><div id="snippetjam-coding-status"><p class="sjc-round-status"><span class="sjc-status-number" aria-hidden="true">↗</span><span aria-live="polite" aria-atomic="true">Read. Solve. Verify.</span></p></div><p>Open a prompt.<br />Give the idea a second look.</p></aside>
        <div class="sjc-round-list"><?php foreach ( $rounds as $index => $round ) : ?>
            <details name="sjc-rounds" data-coding-round="<?php echo esc_attr( (string) $index ); ?>"<?php if ( 0 === $index ) : ?> open<?php endif; ?>>
                <summary><span class="sjc-round-number"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span><span class="sjc-round-name"><?php echo esc_html( $round[0] ); ?></span><span class="sjc-plus" aria-hidden="true">+</span></summary>
                <div class="sjc-round-copy"><h3><?php echo esc_html( $round[1] ); ?></h3><p><?php echo esc_html( $round[2] ); ?></p><p class="sjc-prompt"><span class="sjc-label">TRY THIS</span><?php echo esc_html( $round[3] ); ?></p><p class="sjc-flow"><?php echo esc_html( $round[4] ); ?></p></div>
            </details>
        <?php endforeach; ?></div>
    </div>
</section>

<section id="sjc-eligibility" class="sjc-section sjc-eligibility" aria-labelledby="sjc-eligibility-title"><div><p class="sjc-label">03 / ELIGIBILITY</p><h2 id="sjc-eligibility-title" class="sjc-heading">Before<br />you enter.</h2></div><div class="sjc-eligibility-copy"><p class="sjc-intro">Bring an interest in problem-solving. Check the requirements before making your entry.</p><p>Eligibility is still to be confirmed. Student status, entry format, permitted languages and any other restrictions must come from the published competition rules.</p><dl class="sjc-requirements"><div><dt>Who can enter</dt><dd>To be confirmed</dd></div><div><dt>Individual or team</dt><dd>To be confirmed</dd></div><div><dt>Languages &amp; tools</dt><dd>To be confirmed</dd></div></dl><a class="sjc-link" href="<?php echo esc_url( $registration ); ?>">Check registration information <span aria-hidden="true">↗</span></a></div></section>

<section id="sjc-preparation" class="sjc-section sjc-preparation" aria-labelledby="sjc-preparation-title"><div class="sjc-section-top"><p class="sjc-label">04 / BEFORE THE FIRST LINE</p><span class="sjc-label">SUGGESTIONS, NOT REQUIREMENTS</span></div><div class="sjc-prep-grid"><h2 id="sjc-preparation-title" class="sjc-heading">Build your<br /><em>thinking kit.</em></h2><ol class="sjc-prep-list"><li><h3>Revisit the fundamentals.</h3><p>Practise reading input, working with common data structures and explaining time and space complexity.</p></li><li><h3>Keep a mistake log.</h3><p>Save a few problems that surprised you. Note the assumption that failed and the test that exposed it.</p></li><li><h3>Explain it out loud.</h3><p>Describe an approach before implementing it. If the explanation gets tangled, return to a smaller example.</p></li></ol></div></section>

<section id="sjc-faq" class="sjc-section sjc-faq" aria-labelledby="sjc-faq-title"><div><p class="sjc-label">05 / OPEN QUESTIONS</p><h2 id="sjc-faq-title" class="sjc-heading">A little<br />clarity.</h2><a class="sjc-link" href="<?php echo esc_url( snippetjam_event_link( 'faq' ) ); ?>">Visit the event FAQ <span aria-hidden="true">↗</span></a></div><div class="sjc-faq-list"><?php foreach ( $questions as $question ) : ?><details><summary><?php echo esc_html( $question[0] ); ?><span class="sjc-plus" aria-hidden="true">+</span></summary><p><?php echo esc_html( $question[1] ); ?></p></details><?php endforeach; ?></div></section>

<section id="sjc-registration" class="sjc-section sjc-registration" aria-labelledby="sjc-registration-title"><div><p class="sjc-label">06 / YOUR NEXT MOVE</p><h2 id="sjc-registration-title">GIVE IT<br />SOME<br /><span>THOUGHT.</span></h2></div><div class="sjc-entry"><span class="sjc-entry-arrow" aria-hidden="true">↗</span><p>Ready to explore the challenge?</p><p class="sjc-entry-note">Check the published registration information for availability and entry requirements.</p><a class="sjc-register" href="<?php echo esc_url( $registration ); ?>">View registration <span aria-hidden="true">↗</span></a><a class="sjc-link" href="<?php echo esc_url( snippetjam_event_link( 'schedule' ) ); ?>">Check the schedule <span aria-hidden="true">↗</span></a></div></section>
<!-- /wp:html -->
