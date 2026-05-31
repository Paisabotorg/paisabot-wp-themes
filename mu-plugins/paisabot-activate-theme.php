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
