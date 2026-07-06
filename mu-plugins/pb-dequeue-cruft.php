<?php
/**
 * PaisaBot — remove WordPress default front-end cruft (JS/CSS with no benefit
 * on these sites). Trims a bit of the homepage's blocking JS.
 *   - wp-emoji: the emoji-detection script + inline styles
 *   - wp-embed: oEmbed discovery links + wp-embed.min.js
 */
defined('ABSPATH') || exit;

add_action('init', function () {
    // Emoji detection
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    // oEmbed
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
});

add_action('wp_footer', function () {
    wp_dequeue_script('wp-embed');
}, 100);

add_filter('tiny_mce_plugins', function ($plugins) {
    return is_array($plugins) ? array_diff($plugins, ['wpemoji']) : $plugins;
});
