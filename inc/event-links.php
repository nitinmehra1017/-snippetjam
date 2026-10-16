<?php
/** Read-only links for event templates. WordPress owns page URLs. */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/** Resolve a published page without changing its slug or permalink. */
function snippetjam_event_link( $slug ) {
    $page = get_page_by_path( $slug, OBJECT, 'page' );
    $url  = $page && 'publish' === $page->post_status
        ? get_permalink( $page )
        : home_url( '/' . $slug . '/' );
    return wp_make_link_relative( $url );
}
