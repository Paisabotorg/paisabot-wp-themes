<?php
/**
 * PaisaBot — Auto-activate the aivartha theme.
 *
 * Dropped into wp-content/mu-plugins/ by the deploy pipeline.
 * Runs once, switches to aivartha, then self-deletes so it never
 * fires again.  Safe to deploy repeatedly — the theme switch is
 * idempotent and the file removes itself on success.
 */
defined('ABSPATH') || exit;

add_action('init', function () {
    if (get_stylesheet() === 'aivartha') {
        // Already active — nothing to do; remove this file so it
        // doesn't run on every request.
        @unlink(__FILE__);
        return;
    }

    if (!function_exists('switch_theme')) {
        require_once ABSPATH . 'wp-admin/includes/theme.php';
    }

    switch_theme('aivartha');

    // Persist to options directly in case switch_theme() needs a redirect
    update_option('template',   'aivartha');
    update_option('stylesheet', 'aivartha');

    @unlink(__FILE__);
}, 1);
