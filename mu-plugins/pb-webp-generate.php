<?php
/**
 * PaisaBot — one-time WebP backfill for existing media.
 *
 * Hostinger has no shell / WP-CLI, so this generates WebP copies of the existing
 * image library over HTTP, in resumable batches. Trigger:
 *     https://SITE/?pb_webp=gen2026            (processes one batch, reports remaining)
 * Loop it until "remaining: 0". Each attachment is marked done (_pb_webp meta) so
 * re-runs skip it. Uses pb_make_webp() from pb-performance.php. Safe/idempotent.
 *
 * Once every image has a .webp sibling, pb-performance.php serves <picture> with
 * a WebP source automatically. Remove this file after the backfill if you like.
 */
defined('ABSPATH') || exit;

add_action('init', function () {
    if (!isset($_GET['pb_webp'])) return;
    if ($_GET['pb_webp'] !== 'gen2026') { status_header(403); exit('forbidden'); }
    header('Content-Type: text/plain; charset=utf-8');

    if (!function_exists('imagewebp')) { exit('GD WebP not available on this host'); }
    if (!function_exists('pb_make_webp')) { exit('pb-performance.php not loaded'); }

    @set_time_limit(120);
    @ini_set('memory_limit', '512M');

    $batch = 40;
    $q = new WP_Query([
        'post_type'      => 'attachment',
        'post_mime_type' => ['image/jpeg', 'image/png'],
        'post_status'    => 'inherit',
        'posts_per_page' => $batch,
        'fields'         => 'ids',
        'no_found_rows'  => false,
        'meta_query'     => [['key' => '_pb_webp', 'compare' => 'NOT EXISTS']],
    ]);

    $done = 0;
    foreach ($q->posts as $id) {
        $file = get_attached_file($id);
        if ($file) {
            pb_make_webp($file);
            $meta = wp_get_attachment_metadata($id);
            if (!empty($meta['sizes'])) {
                $dir = dirname($file);
                foreach ($meta['sizes'] as $s) {
                    if (!empty($s['file'])) pb_make_webp($dir . '/' . $s['file']);
                }
            }
        }
        update_post_meta($id, '_pb_webp', 1);
        $done++;
    }

    $remaining = max(0, (int) $q->found_posts - $done);
    echo "processed {$done} this batch · remaining: {$remaining}\n";
    exit;
});
