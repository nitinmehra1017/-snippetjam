<?php
/** Isolated test doubles. This fixture never bootstraps WordPress or a database. */
if ( 'cli' !== PHP_SAPI ) {
    http_response_code( 404 );
    exit;
}
define( 'ABSPATH', __DIR__ . '/' );
define( 'OBJECT', 'OBJECT' );
$theme_root = dirname( __DIR__, 2 );
$test_front = false;
$test_slug = 'coding-competition';
$test_assets = array();
function get_stylesheet_directory() { return $GLOBALS['theme_root']; }
function get_stylesheet_directory_uri() { return '/wp-content/themes/snippetjam-child'; }
function get_stylesheet_uri() { return get_stylesheet_directory_uri() . '/style.css'; }
function home_url( $path = '/' ) { return 'https://snippetjam.test' . $path; }
function esc_url( $value ) { return htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' ); }
function esc_html( $value ) { return htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' ); }
function esc_attr( $value ) { return htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' ); }
function wp_json_encode( $value, $flags = 0 ) { return json_encode( $value, $flags ); }
function is_front_page() { return $GLOBALS['test_front']; }
function is_page( $slug ) { return $GLOBALS['test_slug'] === $slug; }
function add_action( $hook, $callback, $priority = 10 ) {}
function add_filter( $hook, $callback ) {}
function wp_enqueue_style( $handle, $url, $dependencies, $version ) {
    $GLOBALS['test_assets'][ $handle ] = array( 'url' => $url, 'dependencies' => $dependencies, 'version' => $version );
}
function wp_enqueue_script_module( $handle, $url, $dependencies, $version ) {
    $GLOBALS['test_assets'][ $handle . '-js' ] = array( 'url' => $url, 'version' => $version );
}
function get_page_by_path( $slug, $output, $type ) {
    return 'registration' === $slug ? (object) array( 'post_status' => $GLOBALS['registration_status'] ?? 'publish' ) : null;
}
function get_permalink( $page ) { return 'https://snippetjam.test/entry-information/'; }
function wp_make_link_relative( $url ) { return str_replace( 'https://snippetjam.test', '', $url ); }
require $theme_root . '/functions.php';
