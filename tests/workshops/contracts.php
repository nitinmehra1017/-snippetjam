<?php
require __DIR__ . '/../coding-competition/wordpress-stubs.php';
$checks = 0;
function check( $condition, $description ) {
    global $checks;
    if ( ! $condition ) {
        fwrite( STDERR, 'FAIL: ' . $description . PHP_EOL );
        exit( 1 );
    }
    ++$checks;
}
foreach ( array( 'workshops' => 'workshops', 'robotics-challenge' => 'robotics', 'coding-competition' => 'coding', 'hackathon' => 'hackathon', 'other' => null ) as $slug => $handle ) {
    $test_slug = $slug;
    $test_front = false;
    $test_assets = array();
    snippetjam_enqueue_assets();
    $expected = array( 'snippetjam-style' );
    if ( $handle ) {
        $expected[] = 'snippetjam-' . $handle;
        $expected[] = 'snippetjam-' . $handle . '-js';
    }
    check( array_keys( $test_assets ) === $expected, $slug . ' asset isolation' );
    check( snippetjam_workshops_body_class( array() ) === ( 'workshops' === $handle ? array( 'snippetjam-workshops' ) : array() ), $slug . ' body class isolation' );
    if ( $handle ) {
        check( $test_assets[ 'snippetjam-' . $handle ]['dependencies'] === array( 'snippetjam-style' ), 'parent-child-event stylesheet chain' );
    }
}
$test_slug = 'workshops';
$test_front = true;
$test_assets = array();
snippetjam_enqueue_assets();
check( array_keys( $test_assets ) === array( 'snippetjam-style', 'snippetjam-react', 'snippetjam-react-js' ), 'homepage takes precedence' );
check( snippetjam_workshops_body_class( array() ) === array(), 'no workshops body class on homepage' );
$test_front = false;
$test_assets = array();
snippetjam_enqueue_assets();
check( $test_assets['snippetjam-workshops']['version'] === hash_file( 'sha256', $theme_root . '/dist/workshops/workshops.css' ), 'CSS content hash' );
check( $test_assets['snippetjam-workshops-js']['version'] === hash_file( 'sha256', $theme_root . '/dist/workshops/workshops.js' ), 'module content hash' );
check( snippetjam_event_link( 'registration' ) === '/entry-information/', 'published registration permalink' );
$registration_status = 'draft';
check( snippetjam_event_link( 'registration' ) === '/registration/', 'draft registration URL is not exposed' );
$registration_status = 'publish';
$original_root = $theme_root;
$theme_root = __DIR__;
$test_assets = array();
snippetjam_enqueue_assets();
check( array_keys( $test_assets ) === array( 'snippetjam-style' ), 'missing assets omit workshops CSS and JS' );
$theme_root = $original_root;
$content = file_get_contents( $theme_root . '/inc/workshops.php' );
check( substr_count( $content, '<!-- wp:post-title ' ) === 1, 'one native title' );
check( substr_count( $content, '<!-- wp:post-content ' ) === 1, 'one native page content block' );
check( strpos( $content, '<h1' ) === false, 'no extra H1' );
foreach ( array( 'tracks', 'preparation', 'eligibility', 'faq', 'registration' ) as $section ) {
    check( strpos( $content, 'id="sjw-' . $section . '"' ) !== false, $section . ' exists' );
}
check( preg_match( '/^Version: ([0-9.]+)/m', file_get_contents( $theme_root . '/style.css' ), $theme_version ) && version_compare( $theme_version[1], '1.0.4', '>=' ), 'pattern metadata cache release increment' );
echo $checks . ' workshops PHP architecture contracts passed.' . PHP_EOL;
