<?php
/**
 * Title: SnippetJam Registration
 * Slug: snippetjam/registration-content
 * Inserter: no
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!-- wp:html -->
<section class="sjr-content" aria-labelledby="sjr-title">
  <div class="sjr-hero">
    <p class="sjr-eyebrow">SNIPPET JAM / GET INVOLVED</p>
    <h1 id="sjr-title">YOUR<br>MOVE<span>.</span></h1>
    <div class="sjr-hero-bottom">
      <p>Choose your challenge.<br>Make something happen.</p>
      <span>REGISTRATION / 2026</span>
    </div>
  </div>

  <div class="sjr-grid">
    <div class="sjr-sidebar">
      <p class="sjr-eyebrow">01 / PARTICIPATE</p>
      <h2>FIND YOUR<br>PLACE.</h2>
      <p>Explore four ways to take part in SnippetJam. Registration availability and event details will be confirmed here.</p>

      <nav aria-label="Explore SnippetJam events">
        <a href="/hackathon/">01 — Hackathon <span>?</span></a>
        <a href="/coding-competition/">02 — Coding Competition <span>?</span></a>
        <a href="/robotics-challenge/">03 — Robotics Challenge <span>?</span></a>
        <a href="/workshops/">04 — Workshops <span>?</span></a>
      </nav>
    </div>

    <div class="sjr-form-panel">
      <p class="sjr-eyebrow">02 / REGISTER</p>
      <h2>JOIN THE<br>JAM.</h2>
      <div class="sjr-form-holder">
<!-- /wp:html -->
<!-- wp:html -->
<?php echo snippetjam_registration_form_shortcode(); ?>
<!-- /wp:html -->
<!-- wp:html -->
      </div>
    </div>
  </div>

  <div class="sjr-bottom">
    <p>NEED MORE<br>DETAILS?</p>
    <a href="/schedule/">VIEW SCHEDULE ?</a>
  </div>
</section>
<!-- /wp:html -->
