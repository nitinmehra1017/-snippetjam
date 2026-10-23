<?php
/** WordPress owns all copy; React enhances only the example-track navigation. */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$registration = snippetjam_event_link( 'registration' );
$tracks = array(
    array( 'observe', 'Observe', 'Start with a better question.', 'Notice something you want to understand. Break it into smaller questions and decide what you would like to investigate first.', 'Choose an everyday interaction. Write down one thing you understand and one thing you would like to ask.', 'A question worth following.', 'LOOK → QUESTION → CONNECT' ),
    array( 'experiment', 'Experiment', 'Make room for a small attempt.', 'Turn a question into something you can try. Keep the first attempt small enough to inspect, change and try again.', 'Sketch a tiny experiment on paper. Describe what you expect to happen and what would surprise you.', 'An idea you can examine.', 'TRY → NOTICE → ADJUST' ),
    array( 'share', 'Share', 'Find the words for what you found.', 'Explaining a discovery helps reveal the gaps in it. Connect your approach, observations and next question in your own words.', 'Explain an idea without specialist language. Invite someone to point out what is still unclear.', 'A clearer explanation.', 'EXPLAIN → LISTEN → REFINE' ),
);
$questions = array(
    array( 'Are these confirmed workshop topics?', 'No. Observe, Experiment and Share are illustrative learning tracks: examples of approaches to learning, not announced workshops or session topics. The official programme is still to be confirmed.' ),
    array( 'Who can participate?', 'Participant eligibility and prerequisites have not been confirmed here. Check the published workshop information and registration requirements before making plans.' ),
    array( 'Should I bring a laptop or install software?', 'Equipment, software and preparation requirements will come from the official session information. Do not buy or install anything based on these example tracks.' ),
    array( 'Who is teaching, and when are the sessions?', 'Instructors, topics, schedules, locations and any fees remain unconfirmed. Refer to the published programme when it becomes available.' ),
    array( 'How do I register?', 'Use the registration link below to check published entry information. This page does not confirm that registration is open or that places are available.' ),
);
?>
<!-- wp:group {"tagName":"section","className":"sjw-hero","layout":{"type":"default"}} -->
<section class="wp-block-group sjw-hero">
    <!-- wp:html --><div class="sjw-hero-meta"><a class="sjw-back" href="<?php echo esc_url( home_url( '/#sj-events' ) ); ?>"><span aria-hidden="true">↖</span> All events</a><p class="sjw-label">SNIPPET JAM / 04 / LEARN</p></div><!-- /wp:html -->
    <!-- wp:post-title {"level":1,"className":"sjw-title"} /-->
    <!-- wp:html -->
    <div class="sjw-hero-grid">
        <p class="sjw-margin-note">AN OPEN NOTEBOOK<br /><span aria-hidden="true">↓</span></p>
        <div class="sjw-hero-copy"><p class="sjw-deck">A little practice.<br /><em>A new perspective.</em></p><p>Follow a question.<br />Try an idea.<br />See what stays with you.</p><a class="sjw-link" href="#sjw-tracks">Explore example tracks <span aria-hidden="true">↘</span></a></div>
        <div class="sjw-poster" aria-hidden="true"><div class="sjw-poster-top"><span>LEARNING / IN PROGRESS</span><span>04</span></div><p>MAKE<br />ROOM.</p><div class="sjw-paper-lines"><i></i><i></i><i></i></div><span class="sjw-poster-bottom">FOR THE THING<br />YOU HAVEN’T TRIED YET. <b>↗</b></span></div>
    </div>
    <nav class="sjw-section-index" aria-label="On this page"><a href="#sjw-overview">01 / Overview</a><a href="#sjw-tracks">02 / Example tracks</a><a href="#sjw-preparation">03 / Preparation</a><a href="#sjw-eligibility">04 / Eligibility</a><a href="#sjw-faq">05 / FAQ</a></nav>
    <!-- /wp:html -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","anchor":"sjw-overview","className":"sjw-section sjw-overview","layout":{"type":"default"}} -->
<section id="sjw-overview" class="wp-block-group sjw-section sjw-overview">
    <!-- wp:html --><div class="sjw-overview-head"><div><p class="sjw-label">01 / THE WORKSHOP OVERVIEW</p><h2 class="sjw-heading">There’s more<br /><em>to find out.</em></h2></div><p class="sjw-intro">Learning can begin with a question, a conversation or a first attempt. Give an idea a little space to grow.</p></div><!-- /wp:html -->
    <!-- wp:group {"className":"sjw-reading","layout":{"type":"default"}} -->
    <div class="wp-block-group sjw-reading">
        <!-- wp:post-content {"className":"sjw-cms-content","layout":{"type":"default"}} /-->
        <!-- wp:paragraph {"className":"sjw-note"} --><p class="sjw-note">Look to the published workshop information for confirmed session details. Topics, instructors, timing and entry requirements remain pending unless confirmed in the official programme.</p><!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:html -->
<section id="sjw-tracks" class="sjw-section sjw-tracks" aria-labelledby="sjw-tracks-title">
    <div class="sjw-section-top"><p class="sjw-label">02 / THE LEARNING-TRACK EXPLORER</p><span class="sjw-stamp">EXAMPLES ONLY / PROGRAMME UNCONFIRMED</span></div>
    <div class="sjw-track-intro"><h2 id="sjw-tracks-title" class="sjw-heading">Different ways<br /><em>into an idea.</em></h2><p>Choose an example to explore a learning approach. These are illustrative tracks, not confirmed workshop topics or sessions.</p></div>
    <div class="sjw-track-explorer">
        <fieldset class="sjw-track-selector"><legend>Choose an illustrative learning track</legend><?php foreach ( $tracks as $index => $track ) : ?>
            <div class="sjw-track-option"><input type="radio" name="sjw-example-track" id="sjw-track-<?php echo esc_attr( $track[0] ); ?>" value="<?php echo esc_attr( $track[0] ); ?>" aria-controls="sjw-panel-<?php echo esc_attr( $track[0] ); ?>"<?php if ( 0 === $index ) : ?> checked<?php endif; ?> /><label for="sjw-track-<?php echo esc_attr( $track[0] ); ?>"><span class="sjw-label">EXAMPLE <?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span><span class="sjw-track-name"><?php echo esc_html( $track[1] ); ?></span><span class="sjw-option-arrow" aria-hidden="true">↗</span></label></div>
        <?php endforeach; ?></fieldset>
        <div class="sjw-track-panels"><?php foreach ( $tracks as $index => $track ) : ?>
            <article id="sjw-panel-<?php echo esc_attr( $track[0] ); ?>" class="sjw-track-panel" data-example-panel="<?php echo esc_attr( $track[0] ); ?>" aria-labelledby="sjw-panel-title-<?php echo esc_attr( $track[0] ); ?>">
                <div class="sjw-sheet-meta"><p class="sjw-label">ILLUSTRATIVE TRACK / NOT A CONFIRMED SESSION</p><span aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span></div>
                <div class="sjw-panel-grid"><div><h3 id="sjw-panel-title-<?php echo esc_attr( $track[0] ); ?>"><?php echo esc_html( $track[2] ); ?></h3><p><?php echo esc_html( $track[3] ); ?></p></div><div class="sjw-track-art" aria-hidden="true"><svg viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="2" focusable="false"><?php if ( 'observe' === $track[0] ) : ?><circle cx="85" cy="85" r="50"/><circle cx="85" cy="85" r="27"/><path d="M122 122L173 173M85 20V52M20 85H52M85 118V150M118 85H150"/><?php elseif ( 'experiment' === $track[0] ) : ?><path d="M26 40H100V114H26ZM100 86H174V160H100ZM46 60H80V94H46ZM120 106H154V140H120ZM100 40H174M174 40V86M26 160H100"/><path d="M26 20V30M21 25H31M169 180H179M174 175V185"/><?php else : ?><path d="M22 40H148V113H80L48 140V113H22ZM148 78H178V152H154V176L126 152H86V130"/><path d="M48 64H123M48 86H104"/><?php endif; ?></svg></div></div>
                <p class="sjw-example-prompt"><span class="sjw-label">A PROMPT TO TRY</span><?php echo esc_html( $track[4] ); ?></p><div class="sjw-example-foot"><p><span class="sjw-label">A POSSIBLE TAKEAWAY</span><?php echo esc_html( $track[5] ); ?></p><span class="sjw-label"><?php echo esc_html( $track[6] ); ?></span></div>
            </article>
        <?php endforeach; ?></div>
    </div>
    <div id="snippetjam-workshops-navigation"><div class="sjw-explorer-footer"><p class="sjw-current"><span class="sjw-label">ILLUSTRATIVE LEARNING TRACKS</span><span aria-live="polite" aria-atomic="true">Observe. Experiment. Share.</span></p><button class="sjw-next" type="button" hidden>Next example <span aria-hidden="true">→</span></button></div></div>
</section>

<section id="sjw-preparation" class="sjw-section sjw-preparation" aria-labelledby="sjw-preparation-title">
    <div class="sjw-prep-heading"><p class="sjw-label">03 / YOUR PARTICIPANT NOTEBOOK</p><h2 id="sjw-preparation-title">Bring curiosity.<br /><em>Leave room.</em></h2><p>Suggestions for your own learning.<br />Official preparation requirements are still to be confirmed.</p></div>
    <ol class="sjw-prep-list"><li><span class="sjw-prep-number" aria-hidden="true">01</span><h3>Collect a question.</h3><p>Note something you want to understand. A specific question can help you notice what matters to you.</p></li><li><span class="sjw-prep-number" aria-hidden="true">02</span><h3>Make space for notes.</h3><p>Choose a way to capture ideas and things to revisit. Wait for official guidance before buying equipment or installing tools.</p></li><li><span class="sjw-prep-number" aria-hidden="true">03</span><h3>Keep a next step.</h3><p>After a learning exercise, write down one thing you would like to try, clarify or explore further.</p></li></ol>
</section>

<section id="sjw-eligibility" class="sjw-section sjw-eligibility" aria-labelledby="sjw-eligibility-title"><header><p class="sjw-label">04 / BEFORE YOU MAKE PLANS</p><h2 id="sjw-eligibility-title" class="sjw-heading">Check the<br /><em>small print.</em></h2></header><div class="sjw-eligibility-copy"><p class="sjw-intro">Eligibility and prerequisites are still to be confirmed.</p><p>Check the published workshop and registration information. Do not assume a required experience level, equipment list or entry format from the examples above.</p><a class="sjw-link" href="<?php echo esc_url( $registration ); ?>">Check registration information <span aria-hidden="true">↗</span></a></div><dl class="sjw-requirements"><div><dt>Who can participate</dt><dd>To be confirmed</dd></div><div><dt>Prerequisites &amp; equipment</dt><dd>To be confirmed</dd></div><div><dt>Registration availability</dt><dd>Not confirmed here</dd></div></dl></section>

<section id="sjw-faq" class="sjw-section sjw-faq" aria-labelledby="sjw-faq-title"><span class="sjw-q-mark" aria-hidden="true">Q/</span><div class="sjw-faq-body"><header><p class="sjw-label">05 / A FEW OPEN QUESTIONS</p><h2 id="sjw-faq-title" class="sjw-heading">Let’s make<br /><em>that clearer.</em></h2></header><div class="sjw-faq-list"><?php foreach ( $questions as $question ) : ?><details><summary><?php echo esc_html( $question[0] ); ?><span class="sjw-plus" aria-hidden="true">+</span></summary><p><?php echo esc_html( $question[1] ); ?></p></details><?php endforeach; ?></div><a class="sjw-link" href="<?php echo esc_url( snippetjam_event_link( 'faq' ) ); ?>">Read the event FAQ <span aria-hidden="true">↗</span></a></div></section>

<section id="sjw-registration" class="sjw-section sjw-registration" aria-labelledby="sjw-registration-title"><p class="sjw-label">06 / KEEP A LITTLE SPACE</p><h2 id="sjw-registration-title">FOR SOMETHING<br /><em>you haven’t tried.</em></h2><div class="sjw-entry"><p>Check published registration information for availability and entry details.</p><a class="sjw-register" href="<?php echo esc_url( $registration ); ?>">View registration <span aria-hidden="true">↗</span></a><a class="sjw-link" href="<?php echo esc_url( snippetjam_event_link( 'schedule' ) ); ?>">Check the schedule <span aria-hidden="true">↗</span></a></div><span class="sjw-registration-mark" aria-hidden="true">↗</span></section>
<!-- /wp:html -->
