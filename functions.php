<?php
/**
 * Snippet Jam child-theme assets.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once get_stylesheet_directory() . '/inc/homepage-content.php';
require_once get_stylesheet_directory() . '/inc/event-index.php';

/**
 * Use file contents so cache versions remain reliable across Git deployments.
 *
 * @param string $relative_path Asset path relative to the child theme.
 * @return string|false Content hash, or false when the asset is unavailable.
 */
function snippetjam_asset_version( $relative_path ) {
    $path = get_stylesheet_directory() . '/' . $relative_path;

    if ( ! is_file( $path ) || ! is_readable( $path ) ) {
        return false;
    }

    return hash_file( 'sha256', $path );
}

/**
 * Load child overrides after the parent and enhance only the front page.
 */
function snippetjam_enqueue_assets() {
    wp_enqueue_style(
        'snippetjam-style',
        get_stylesheet_uri(),
        array( 'twentytwentyfive-style' ),
        snippetjam_asset_version( 'style.css' )
    );

    if ( ! is_front_page() ) {
        return;
    }

    $css_version = snippetjam_asset_version( 'dist/snippetjam.css' );
    $js_version  = snippetjam_asset_version( 'dist/snippetjam.js' );

    // Styles also serve the WordPress fallback when JavaScript is unavailable.
    if ( false !== $css_version ) {
        wp_enqueue_style(
            'snippetjam-react',
            get_stylesheet_directory_uri() . '/dist/snippetjam.css',
            array( 'snippetjam-style' ),
            $css_version
        );
    }

    if ( false === $css_version || false === $js_version ) {
        return;
    }

    // Vite emits ES modules; WordPress prints the appropriate type="module" tag.
    wp_enqueue_script_module(
        'snippetjam-react',
        get_stylesheet_directory_uri() . '/dist/snippetjam.js',
        array(),
        $js_version
    );
}

add_action( 'wp_enqueue_scripts', 'snippetjam_enqueue_assets', 20 );

/** Scope the full-width homepage canvas without affecting other WordPress pages. */
function snippetjam_homepage_body_class( $classes ) {
    if ( is_front_page() ) {
        $classes[] = 'snippetjam-homepage';
    }
    return $classes;
}
add_filter( 'body_class', 'snippetjam_homepage_body_class' );
