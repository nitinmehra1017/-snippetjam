<?php
/** Server-rendered event index; React enhances this island only. */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function snippetjam_render_event_index( $events ) {
    $active = $events[0];
    echo '<div class="sj-events-layout"><div class="sj-event-list">';
    foreach ( $events as $index => $event ) {
        echo '<div class="sj-event-row" data-active="' . ( 0 === $index ? 'true' : 'false' ) . '">';
        echo '<a class="sj-event-link" href="' . esc_url( $event['url'] ) . '"><span class="sj-event-number">' . esc_html( $event['number'] ) . '</span><span class="sj-event-title">' . esc_html( $event['title'] ) . '</span></a>';
        echo '<button class="sj-event-switch" type="button" hidden="" aria-label="Preview ' . esc_attr( $event['title'] ) . '" aria-pressed="' . ( 0 === $index ? 'true' : 'false' ) . '"><span aria-hidden="true">↗</span></button></div>';
    }
    echo '</div><div class="sj-event-preview" aria-live="polite" aria-atomic="true">';
    echo '<div class="sj-event-visual" data-event="' . esc_attr( $active['slug'] ) . '" aria-hidden="true">';
    readfile( get_stylesheet_directory() . '/assets/event-diagram.svg' );
    echo '</div><div class="sj-event-caption"><p class="sj-eyebrow">' . esc_html( $active['number'] ) . ' / ' . esc_html( $active['mode'] ) . '</p><h3>' . esc_html( $active['title'] ) . '</h3><p>' . esc_html( $active['description'] ) . '</p><a class="sj-text-link" href="' . esc_url( $active['url'] ) . '">Explore event <span aria-hidden="true">↗</span></a></div></div></div>';
}
