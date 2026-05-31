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

// Disable comments and pings site-wide
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open',    '__return_false', 20, 2);
add_filter('comments_array', '__return_empty_array', 10, 2);
add_action('init', function () {
    remove_post_type_support('post', 'comments');
    remove_post_type_support('post', 'trackbacks');
    remove_post_type_support('page', 'comments');
    remove_post_type_support('page', 'trackbacks');
});
// Remove default widgets that pollute sidebar-1
add_action('widgets_init', function () {
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Search');
}, 11);

// Inline critical CSS fixes — bypasses OPcache/CDN caching on style.css
add_action('wp_head', function () {
    echo '<style id="pb-critical-fixes">'
        . '.site-header .hamburger{display:none!important}'
        . '@media(max-width:768px){.site-header .hamburger{display:flex!important;flex-shrink:0!important}}'
        . '.art-head-inner{max-width:none!important;margin:0!important}'
        . '.art-body{margin-left:0!important;margin-right:0!important}'
        . '</style>';
}, 99);
