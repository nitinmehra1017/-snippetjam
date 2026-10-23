<?php
require __DIR__ . '/wordpress-stubs.php';
$checks = 0;
function check( $condition, $description ) {
    global $checks;
    if ( ! $condition ) {
        fwrite( STDERR, 'FAIL: ' . $description . PHP_EOL );
        exit( 1 );
    }
    ++$checks;
}
foreach ( array( 'coding-competition' => 'coding', 'hackathon' => 'hackathon', 'other' => null ) as $slug => $handle ) {
    $test_slug = $slug;
    $test_front = false;
    $test_assets = array();
    snippetjam_enqueue_assets();
    $expected = array( 'snippetjam-style' );
    if ( $handle ) {
        $expected[] = 'snippetjam-' . $handle;
        $expected[] = 'snippetjam-' . $handle . '-js';
    }
    check( array_keys( $test_assets ) === $expected, $slug . ' only enqueues its own assets' );
    if ( $handle ) {
        check( $test_assets[ 'snippetjam-' . $handle ]['dependencies'] === array( 'snippetjam-style' ), 'child stylesheet dependency' );
        check( strlen( $test_assets[ 'snippetjam-' . $handle ]['version'] ) === 64, 'SHA-256 cache version' );
    }
}
$test_front = true;
$test_slug = 'coding-competition';
$test_assets = array();
snippetjam_enqueue_assets();
check( array_keys( $test_assets ) === array( 'snippetjam-style', 'snippetjam-react', 'snippetjam-react-js' ), 'front-page precedence preserved' );
check( snippetjam_coding_body_class( array() ) === array(), 'no coding body class on front page' );
$test_front = false;
check( snippetjam_coding_body_class( array( 'page' ) ) === array( 'page', 'snippetjam-coding' ), 'conditional coding body class' );
$test_slug = 'hackathon';
check( snippetjam_coding_body_class( array() ) === array(), 'no coding body class on Hackathon' );
check( snippetjam_hackathon_body_class( array() ) === array( 'snippetjam-hackathon' ), 'Hackathon body class preserved' );
check( snippetjam_event_link( 'registration' ) === '/entry-information/', 'uses published page permalink' );
$registration_status = 'draft';
check( snippetjam_event_link( 'registration' ) === '/registration/', 'does not use draft permalink' );
check( snippetjam_event_link( 'schedule' ) === '/schedule/', 'safe missing-page fallback' );
$registration_status = 'publish';
$original_root = $theme_root;
$theme_root = __DIR__; // No built assets in this fixture directory.
$test_slug = 'coding-competition';
$test_assets = array();
snippetjam_enqueue_assets();
check( array_keys( $test_assets ) === array( 'snippetjam-style' ), 'missing assets do not enqueue broken modules' );
$theme_root = $original_root;
$test_assets = array();
$template = file_get_contents( $theme_root . '/templates/page-coding-competition.html' );
check( substr_count( $template, '<main ' ) === 1, 'one main landmark' );
$content = file_get_contents( $theme_root . '/inc/coding-competition.php' );
check( substr_count( $content, '<!-- wp:post-title ' ) === 1, 'one native page title' );
check( substr_count( $content, '<!-- wp:post-content ' ) === 1, 'original page body rendered once' );
check( strpos( $content, '<h1' ) === false, 'no duplicate presentation H1' );
foreach ( array( 'rounds', 'eligibility', 'preparation', 'faq', 'registration' ) as $section ) {
    check( strpos( $content, 'id="sjc-' . $section . '"' ) !== false, $section . ' section exists' );
}
check( preg_match( '/^Version: ([0-9.]+)/m', file_get_contents( $theme_root . '/style.css' ), $theme_version ) && version_compare( $theme_version[1], '1.0.2', '>=' ), 'pattern cache release increment' );
echo $checks . ' PHP architecture contracts passed.' . PHP_EOL;
