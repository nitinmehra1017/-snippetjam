<?php
/**
 * Read-only content adapter for the homepage. WordPress remains the source of truth.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/** Get a portable link to an existing WordPress page. */
function snippetjam_page_link( $slug, $pages ) {
    $url = isset( $pages[ $slug ] ) ? get_permalink( $pages[ $slug ] ) : home_url( '/' . $slug . '/' );
    return wp_make_link_relative( $url );
}

/** Use published excerpts/content without executing shortcodes or embedding media. */
function snippetjam_page_intro( $page, $fallback, $words = 42 ) {
    if ( ! $page ) {
        return $fallback;
    }
    $text = $page->post_excerpt ? $page->post_excerpt : $page->post_content;
    $text = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( strip_shortcodes( $text ) ) ) );
    $text = html_entity_decode( $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
    return '' !== $text ? wp_trim_words( $text, $words, '…' ) : $fallback;
}

/** Read paragraph copy under the existing Home page headings, without its old layout. */
function snippetjam_home_sections( $page ) {
    $sections = array();
    $heading = '';
    if ( ! $page || ! preg_match_all( '/<(h[1-6]|p)\b[^>]*>(.*?)<\/\1>/is', $page->post_content, $matches, PREG_SET_ORDER ) ) {
        return $sections;
    }
    foreach ( $matches as $match ) {
        $text = trim( html_entity_decode( wp_strip_all_tags( strip_shortcodes( $match[2] ) ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
        if ( 'p' !== strtolower( $match[1] ) ) {
            $heading = sanitize_title( $text );
        } elseif ( '' !== $text && '' !== $heading ) {
            $sections[ $heading ][] = $text;
        }
    }
    return $sections;
}

/** Collect published pages once per request; no settings or content are written. */
function snippetjam_homepage_data() {
    static $data;
    if ( isset( $data ) ) {
        return $data;
    }
    $slugs = array( 'about', 'hackathon', 'coding-competition', 'robotics-challenge', 'workshops', 'schedule', 'registration', 'venue-contact', 'faq', 'home' );
    $pages = array();
    foreach ( get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_name__in' => $slugs, 'posts_per_page' => count( $slugs ), 'no_found_rows' => true ) ) as $page ) {
        $pages[ $page->post_name ] = $page;
    }
    $links = array();
    foreach ( $slugs as $slug ) {
        $links[ $slug ] = snippetjam_page_link( $slug, $pages );
    }
    $home_page = get_queried_object();
    if ( ! ( $home_page instanceof WP_Post ) || ! isset( $home_page->post_type ) || 'page' !== $home_page->post_type ) {
        $home_page = isset( $pages['home'] ) ? $pages['home'] : null;
    }
    $home_sections = snippetjam_home_sections( $home_page );
    $definitions = array(
        array( 'hackathon', 'Hackathon', 'BUILD', 'Collaborate, solve real-world challenges, and turn an idea into a working solution.' ),
        array( 'coding-competition', 'Coding Competition', 'SOLVE', 'A space for code and problem-solving. Event details coming soon.' ),
        array( 'robotics-challenge', 'Robotics Challenge', 'EXPERIMENT', 'Explore the meeting point of code and machines. Event details coming soon.' ),
        array( 'workshops', 'Workshops', 'LEARN', 'Make room for practical learning and new ideas. Event details coming soon.' ),
    );
    $events = array();
    foreach ( $definitions as $index => $definition ) {
        list( $slug, $title, $mode, $fallback ) = $definition;
        if ( isset( $home_sections[ $slug ][0] ) ) {
            $fallback = $home_sections[ $slug ][0] . ' Event details coming soon.';
        }
        $page = isset( $pages[ $slug ] ) ? $pages[ $slug ] : null;
        $events[] = array(
            'number' => sprintf( '%02d', $index + 1 ),
            'slug' => $slug,
            'title' => $page ? html_entity_decode( wp_strip_all_tags( get_the_title( $page ) ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) : $title,
            'mode' => $mode,
            'description' => snippetjam_page_intro( $page, $fallback ),
            'url' => $links[ $slug ],
        );
    }
    $about_page = isset( $pages['about'] ) ? $pages['about'] : null;
    $schedule_page = isset( $pages['schedule'] ) ? $pages['schedule'] : null;
    $venue_page = isset( $pages['venue-contact'] ) ? $pages['venue-contact'] : null;
    $registration_page = isset( $pages['registration'] ) ? $pages['registration'] : null;
    $data = array(
        'links' => $links,
        'events' => $events,
        'about' => snippetjam_page_intro( $about_page, isset( $home_sections['about-snippet-jam'][0] ) ? $home_sections['about-snippet-jam'][0] : 'Snippet Jam is a campus technology-event concept: a place for students to collaborate, solve problems, experiment with technology, and make ideas tangible.', 65 ),
        'hero_context' => isset( $home_sections['code-compete-create'][0] ) ? $home_sections['code-compete-create'][0] : 'A campus gathering for people who build, solve, experiment, and learn.',
        'why' => isset( $home_sections['why-participate'][0] ) ? $home_sections['why-participate'][0] : 'Explore practical skills, collaborative problem-solving, and creative technology projects.',
        'schedule' => snippetjam_page_intro( $schedule_page, 'Schedule to be announced.', 70 ),
        'schedule_published' => $schedule_page && '' !== trim( wp_strip_all_tags( $schedule_page->post_content ) ),
        'venue' => snippetjam_page_intro( $venue_page, 'Venue and contact details coming soon.', 60 ),
        'registration' => snippetjam_page_intro( $registration_page, 'Registration details coming soon.', 60 ),
        'faq' => snippetjam_faq_items( isset( $pages['faq'] ) ? $pages['faq'] : null, $links ),
    );
    return $data;
}

/** Keep factual FAQ answers on the existing page when they are published. */
function snippetjam_faq_items( $page, $links ) {
    $items = array();
    if ( $page && preg_match_all( '/<h[2-4]\b[^>]*>(.*?)<\/h[2-4]>(.*?)(?=<h[2-4]\b|$)/is', $page->post_content, $matches, PREG_SET_ORDER ) ) {
        foreach ( $matches as $match ) {
            $question = trim( wp_strip_all_tags( $match[1] ) );
            $answer = wp_kses_post( $match[2] );
            if ( '' !== $question && '' !== trim( wp_strip_all_tags( $answer ) ) ) {
                $items[] = array( 'question' => $question, 'answer' => $answer );
            }
            if ( count( $items ) >= 5 ) {
                break;
            }
        }
    }
    if ( $items ) {
        return $items;
    }
    return array(
        array( 'question' => 'What is Snippet Jam?', 'answer' => '<p>A campus technology-event concept bringing together code, creative problem-solving, experimentation, and practical learning.</p>' ),
        array( 'question' => 'Which events can I explore?', 'answer' => '<p>Explore the <a href="' . esc_url( $links['hackathon'] ) . '">Hackathon</a>, <a href="' . esc_url( $links['coding-competition'] ) . '">Coding Competition</a>, <a href="' . esc_url( $links['robotics-challenge'] ) . '">Robotics Challenge</a>, and <a href="' . esc_url( $links['workshops'] ) . '">Workshops</a>. Individual event details are coming soon.</p>' ),
        array( 'question' => 'When and where will it happen?', 'answer' => '<p>Schedule and venue details are to be announced. Check the <a href="' . esc_url( $links['schedule'] ) . '">schedule</a> and <a href="' . esc_url( $links['venue-contact'] ) . '">venue &amp; contact</a> pages for published information.</p>' ),
        array( 'question' => 'How do I register?', 'answer' => '<p>Registration details are coming soon on the <a href="' . esc_url( $links['registration'] ) . '">registration page</a>.</p>' ),
        array( 'question' => 'Where can I find participation rules?', 'answer' => '<p>Event details coming soon. Check the individual event pages and the <a href="' . esc_url( $links['faq'] ) . '">full FAQ</a> for confirmed participation information.</p>' ),
    );
}
