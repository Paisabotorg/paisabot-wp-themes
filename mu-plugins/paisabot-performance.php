<?php
/**
 * PaisaBot — front-end performance (WebP + image decoding hints).
 *
 * Zero-cost, safe & additive:
 *  1. On upload, generate a WebP copy of each JPEG/PNG size (GD).
 *  2. In content/thumbnails, wrap <img> in <picture> with a WebP <source>
 *     ONLY when a sibling .webp exists — otherwise the markup is untouched,
 *     so nothing can break if a WebP is missing.
 *  3. Add decoding="async" to images (cheap paint win).
 *
 * Existing media has no .webp yet; run a one-time regeneration
 * (e.g. WP-CLI `wp media regenerate`, or re-save) to backfill WebP.
 */
defined('ABSPATH') || exit;

/** Create a .webp next to a JPEG/PNG if one doesn't exist. */
function pb_make_webp(string $path): void {
    if (!preg_match('/\.(jpe?g|png)$/i', $path) || !is_file($path)) return;
    $webp = preg_replace('/\.(jpe?g|png)$/i', '.webp', $path);
    if (is_file($webp) || !function_exists('imagewebp')) return;
    $img = preg_match('/\.png$/i', $path)
        ? @imagecreatefrompng($path)
        : @imagecreatefromjpeg($path);
    if (!$img) return;
    if (preg_match('/\.png$/i', $path)) { imagepalettetotruecolor($img); imagealphablending($img, true); }
    @imagewebp($img, $webp, 82);
    imagedestroy($img);
}

// 1. Generate WebP for the full image + every generated size on upload.
add_filter('wp_generate_attachment_metadata', function ($meta, $attach_id) {
    $file = get_attached_file($attach_id);
    if (!$file) return $meta;
    $dir = dirname($file);
    pb_make_webp($file);
    if (!empty($meta['sizes'])) {
        foreach ($meta['sizes'] as $s) {
            if (!empty($s['file'])) pb_make_webp($dir . '/' . $s['file']);
        }
    }
    return $meta;
}, 10, 2);

/** Map an uploads URL back to an absolute path (for existence checks). */
function pb_url_to_path(string $url): ?string {
    $up = wp_get_upload_dir();
    if (strpos($url, $up['baseurl']) === 0) {
        return $up['basedir'] . substr($url, strlen($up['baseurl']));
    }
    return null;
}

/** Add decoding=async and, when a .webp sibling exists, wrap in <picture>. */
function pb_webp_pictures(string $html): string {
    if (stripos($html, '<img') === false) return $html;
    return preg_replace_callback('/<img\b[^>]*>/i', function ($m) {
        $img = $m[0];
        if (stripos($img, 'decoding=') === false) {
            $img = preg_replace('/<img\b/i', '<img decoding="async"', $img, 1);
        }
        if (!preg_match('/\ssrc=["\']([^"\']+\.(?:jpe?g|png))["\']/i', $img, $s)) return $img;
        $webp = preg_replace('/\.(jpe?g|png)$/i', '.webp', $s[1]);
        $path = pb_url_to_path($webp);
        if (!$path || !is_file($path)) return $img;   // no WebP → leave untouched
        return '<picture><source srcset="' . esc_url($webp) . '" type="image/webp">'
             . $img . '</picture>';
    }, $html);
}
add_filter('the_content', 'pb_webp_pictures', 20);
add_filter('post_thumbnail_html', 'pb_webp_pictures', 20);
