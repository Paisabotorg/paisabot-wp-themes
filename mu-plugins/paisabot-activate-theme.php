<?php
/**
 * PaisaBot — Force aivartha theme on all sites.
 *
 * Uploaded to wp-content/mu-plugins/ by the deploy pipeline.
 * Uses filters so it works regardless of what the WP database
 * says is the active theme — no DB write needed, no race condition.
 */
defined('ABSPATH') || exit;

add_filter('template',   fn() => 'aivartha');
add_filter('stylesheet', fn() => 'aivartha');

// Inline critical CSS fixes — bypasses OPcache/CDN caching on style.css
add_action('wp_head', function () {
    echo '<style id="pb-critical-fixes">'
        . '.site-header .hamburger{display:none!important}'
        . '@media(max-width:768px){.site-header .hamburger{display:flex!important;flex-shrink:0!important}}'
        . '.art-head-inner{max-width:none!important;margin:0!important}'
        . '.art-body{margin-left:0!important;margin-right:0!important}'
        . '</style>';
}, 99);
