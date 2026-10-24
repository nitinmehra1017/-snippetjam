<?php
/** Render theme PHP with isolated stubs, not a full WordPress integration preview. */
require __DIR__ . '/wordpress-stubs.php';
$event = isset( $argv[1] ) && 'hackathon' === $argv[1] ? 'hackathon' : 'coding-competition';
$prefix = 'hackathon' === $event ? 'sjh' : 'sjc';
$wrapper = 'hackathon' === $event ? 'sj-hackathon' : 'sj-coding';
$title = 'hackathon' === $event ? 'Hackathon' : 'Coding Competition';
function expand_theme_blocks( $html ) {
    global $theme_root;
    return preg_replace_callback( '/<!-- wp:(pattern|template-part) (\{.*?\}) \/-->/s', function ( $match ) use ( $theme_root ) {
        $attributes = json_decode( $match[2], true );
        if ( 'pattern' === $match[1] ) {
            $slug = basename( $attributes['slug'] );
            ob_start();
            require $theme_root . '/patterns/' . $slug . '.php';
            return expand_theme_blocks( ob_get_clean() );
        }
        $tag = $attributes['tagName'];
        $class = $attributes['className'];
        $part = file_get_contents( $theme_root . '/parts/' . $attributes['slug'] . '.html' );
        return '<' . $tag . ' class="' . $class . '">' . expand_theme_blocks( $part ) . '</' . $tag . '>';
    }, $html );
}
$html = expand_theme_blocks( file_get_contents( $theme_root . '/templates/page-' . $event . '.html' ) );
if ( isset( $argv[2] ) && 'raw' === $argv[2] ) {
    echo $html;
    exit;
}
$html = preg_replace_callback( '/<!-- wp:post-title (\{.*?\}) \/-->/', function ( $match ) use ( $title ) {
    $attributes = json_decode( $match[1], true );
    return '<h1 class="wp-block-post-title ' . $attributes['className'] . '">' . $title . '</h1>';
}, $html );
$html = preg_replace_callback( '/<!-- wp:post-content (\{.*?\}) \/-->/', function ( $match ) {
    $attributes = json_decode( $match[1], true );
    return '<div class="wp-block-post-content ' . $attributes['className'] . '"><p data-fixture-content>Published WordPress body: this copy stays in the CMS.</p><p><a href="/existing-page/?source=wordpress">An existing content link</a></p></div>';
}, $html );
$asset = 'hackathon' === $event ? 'hackathon/hackathon' : 'coding-competition/coding-competition';
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?php echo esc_html( $title ); ?> fixture</title><style>body{margin:0}.wp-site-blocks>*+*,.wp-block-group>*+*{margin-block-start:1.2rem}.wp-block-group{max-width:none}</style><link rel="stylesheet" href="/<?php echo $asset; ?>.css"></head><body class="snippetjam-<?php echo 'hackathon' === $event ? 'hackathon' : 'coding'; ?>"><div class="wp-site-blocks"><?php echo $html; ?></div><script type="module" src="/<?php echo $asset; ?>.js"></script></body></html>
