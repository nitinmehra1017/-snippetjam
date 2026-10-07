<?php

/**
 * Snippet Jam Child Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Load Snippet Jam styles and interactions.
 */
function snippetjam_enqueue_assets() {

    wp_enqueue_style(
        'snippetjam-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get( 'Version' )
    );

    wp_enqueue_style(
        'snippetjam-custom',
        get_stylesheet_directory_uri() . '/assets/css/site.css',
        array( 'snippetjam-style' ),
        '1.0.0'
    );

    wp_enqueue_script(
        'snippetjam-interactions',
        get_stylesheet_directory_uri() . '/assets/js/site.js',
        array(),
        '1.0.0',
        true
    );
}

add_action( 'wp_enqueue_scripts', 'snippetjam_enqueue_assets' );
