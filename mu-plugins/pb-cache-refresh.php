<?php
/**
 * PaisaBot — keep FTP-uploaded template files fresh under OPcache.
 *
 * This host runs OPcache with validate_timestamps effectively off, so a template
 * file edited and re-uploaded via FTP keeps serving OLD compiled bytecode (menu
 * changes don't show; a half-uploaded file can even fatal). A *new* mu-plugin
 * file, however, compiles fresh — so this one:
 *
 *  1) Durable: force-recompiles the header/footer right before they render, so
 *     future edits to those files always take effect. Origin PHP sits behind
 *     Cloudflare's HTML cache, so this runs rarely — negligible cost.
 *  2) One-shot: clears the whole OPcache once (per version) so the *current*
 *     batch of edits loads immediately. Bump the filename + version to force
 *     another full clear on a later deploy.
 */
defined('ABSPATH') || exit;

if (function_exists('opcache_invalidate')) {
    foreach (['get_header' => 'header.php', 'get_footer' => 'footer.php'] as $hook => $file) {
        add_action($hook, function () use ($file) {
            @opcache_invalidate(get_template_directory() . '/' . $file, true);
        }, 0);
    }
}

if (get_option('pb_cache_refresh') !== 'v1' && function_exists('opcache_reset')) {
    @opcache_reset();
    update_option('pb_cache_refresh', 'v1');
}
