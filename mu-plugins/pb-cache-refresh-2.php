<?php
/**
 * PaisaBot — OPcache freshness for all theme templates (v2).
 *
 * Extends pb-cache-refresh.php (which only handled header/footer) to recompile
 * the whole theme + mu-plugin PHP set on each request, so FTP-uploaded edits to
 * index.php, template-parts, category templates, etc. actually take effect on a
 * host that ignores mtimes. New filename → compiles fresh (OPcache had no copy).
 *
 * Cost is negligible: origin PHP sits behind Cloudflare + the 5-min page cache,
 * so this runs only on cache misses. Bump the filename + version to force a
 * one-shot full reset again on a later deploy.
 */
defined('ABSPATH') || exit;

if (function_exists('opcache_invalidate')) {
    add_action('template_redirect', function () {
        $t = get_template_directory();
        $files = array_merge(
            glob($t . '/*.php') ?: [],
            glob($t . '/template-parts/*.php') ?: [],
            glob($t . '/template-parts/*/*.php') ?: [],
            glob(WPMU_PLUGIN_DIR . '/*.php') ?: []
        );
        foreach ($files as $f) { @opcache_invalidate($f, true); }
    }, -9999);
}

if (get_option('pb_cache_refresh_2') !== 'v1' && function_exists('opcache_reset')) {
    @opcache_reset();
    update_option('pb_cache_refresh_2', 'v1');
}
