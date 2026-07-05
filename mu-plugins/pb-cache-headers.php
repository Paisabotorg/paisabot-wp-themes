<?php
/**
 * PaisaBot — sane front-end cache TTLs.
 *
 * The Hostinger platform pins front-end pages with `Cache-Control: public,
 * max-age=604800` (7 DAYS). That staled everything: content/menu edits took a
 * week to appear, and — worse — the homepage's *live* market tickers/movers were
 * frozen for up to 7 days. Override with a short TTL so pages refresh (movers +
 * menu stay current) while still absorbing load behind the cache.
 *
 * Runs late (send_headers, prio 99) with replace=true so it wins over the
 * earlier header. Logged-in users + admin are never cached.
 */
defined('ABSPATH') || exit;

add_action('send_headers', function () {
    if (is_admin() || is_user_logged_in()) return;
    // 5 min for the homepage/archives (live tickers), 1 h for articles.
    $ttl = (is_front_page() || is_home() || is_category() || is_archive()) ? 300 : 3600;
    header("Cache-Control: public, max-age={$ttl}, stale-while-revalidate=60", true);
}, 99);
