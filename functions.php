<?php
/**
 * Snippet Jam child-theme assets.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once get_stylesheet_directory() . '/inc/homepage-content.php';
require_once get_stylesheet_directory() . '/inc/event-index.php';
require_once get_stylesheet_directory() . '/inc/event-links.php';

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
 * Load child overrides after the parent and keep event assets page-specific.
 */
function snippetjam_enqueue_assets() {
    wp_enqueue_style(
        'snippetjam-style',
        get_stylesheet_uri(),
        array( 'twentytwentyfive-style' ),
        snippetjam_asset_version( 'style.css' )
    );

    if ( ! is_front_page() && is_page( 'hackathon' ) ) {
        $event_css = snippetjam_asset_version( 'dist/hackathon/hackathon.css' );
        $event_js  = snippetjam_asset_version( 'dist/hackathon/hackathon.js' );
        if ( false !== $event_css ) {
            wp_enqueue_style(
                'snippetjam-hackathon',
                get_stylesheet_directory_uri() . '/dist/hackathon/hackathon.css',
                array( 'snippetjam-style' ),
                $event_css
            );
        }
        if ( false !== $event_css && false !== $event_js ) {
            wp_enqueue_script_module(
                'snippetjam-hackathon',
                get_stylesheet_directory_uri() . '/dist/hackathon/hackathon.js',
                array(),
                $event_js
            );
        }
        return;
    }

    if ( ! is_front_page() && is_page( 'coding-competition' ) ) {
        $coding_css = snippetjam_asset_version( 'dist/coding-competition/coding-competition.css' );
        $coding_js  = snippetjam_asset_version( 'dist/coding-competition/coding-competition.js' );
        if ( false !== $coding_css ) {
            wp_enqueue_style(
                'snippetjam-coding',
                get_stylesheet_directory_uri() . '/dist/coding-competition/coding-competition.css',
                array( 'snippetjam-style' ),
                $coding_css
            );
        }
        if ( false !== $coding_css && false !== $coding_js ) {
            wp_enqueue_script_module(
                'snippetjam-coding',
                get_stylesheet_directory_uri() . '/dist/coding-competition/coding-competition.js',
                array(),
                $coding_js
            );
        }
        return;
    }

    if ( ! is_front_page() && is_page( 'robotics-challenge' ) ) {
        $robotics_css = snippetjam_asset_version( 'dist/robotics-challenge/robotics-challenge.css' );
        $robotics_js  = snippetjam_asset_version( 'dist/robotics-challenge/robotics-challenge.js' );
        if ( false !== $robotics_css ) {
            wp_enqueue_style(
                'snippetjam-robotics',
                get_stylesheet_directory_uri() . '/dist/robotics-challenge/robotics-challenge.css',
                array( 'snippetjam-style' ),
                $robotics_css
            );
        }
        if ( false !== $robotics_css && false !== $robotics_js ) {
            wp_enqueue_script_module(
                'snippetjam-robotics',
                get_stylesheet_directory_uri() . '/dist/robotics-challenge/robotics-challenge.js',
                array(),
                $robotics_js
            );
        }
        return;
    }

    if ( ! is_front_page() && is_page( 'workshops' ) ) {
        $workshops_css = snippetjam_asset_version( 'dist/workshops/workshops.css' );
        $workshops_js  = snippetjam_asset_version( 'dist/workshops/workshops.js' );
        if ( false !== $workshops_css ) {
            wp_enqueue_style(
                'snippetjam-workshops',
                get_stylesheet_directory_uri() . '/dist/workshops/workshops.css',
                array( 'snippetjam-style' ),
                $workshops_css
            );
        }
        if ( false !== $workshops_css && false !== $workshops_js ) {
            wp_enqueue_script_module(
                'snippetjam-workshops',
                get_stylesheet_directory_uri() . '/dist/workshops/workshops.js',
                array(),
                $workshops_js
            );
        }
        return;
    }

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

/** The Hackathon canvas is isolated from the homepage and other page templates. */
function snippetjam_hackathon_body_class( $classes ) {
    if ( ! is_front_page() && is_page( 'hackathon' ) ) {
        $classes[] = 'snippetjam-hackathon';
    }
    return $classes;
}
add_filter( 'body_class', 'snippetjam_hackathon_body_class' );

/** Keep competition layout overrides confined to its own page. */
function snippetjam_coding_body_class( $classes ) {
    if ( ! is_front_page() && is_page( 'coding-competition' ) ) {
        $classes[] = 'snippetjam-coding';
    }
    return $classes;
}
add_filter( 'body_class', 'snippetjam_coding_body_class' );

/** Scope robotics overrides to its event page. */
function snippetjam_robotics_body_class( $classes ) {
    if ( ! is_front_page() && is_page( 'robotics-challenge' ) ) {
        $classes[] = 'snippetjam-robotics';
    }
    return $classes;
}
add_filter( 'body_class', 'snippetjam_robotics_body_class' );

/** Keep learning-journal presentation on the Workshops page. */
function snippetjam_workshops_body_class( $classes ) {
    if ( ! is_front_page() && is_page( 'workshops' ) ) {
        $classes[] = 'snippetjam-workshops';
    }
    return $classes;
}
add_filter( 'body_class', 'snippetjam_workshops_body_class' );
