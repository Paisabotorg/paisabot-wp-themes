<?php
/**
 * PaisaBot — Live market movers & indices on the homepage / markets page.
 *
 * Makes the theme's existing data hooks live from api.paisabot.com instead of
 * static placeholders — no theme edits needed:
 *   aiv_mktd_gainers / aiv_mktd_losers  (Markets category page)
 *   aiv_stock_list                      (homepage "Top Stocks")
 *   aiv_pulse_indices                   (homepage "Key Indices")
 *
 * Responses are cached in a WP transient (90s), and the API itself is
 * nginx-cached, so the homepage stays fast under load. If the API is
 * unreachable, filters return the theme's original static array (graceful
 * degradation) — the site never shows an empty/broken widget.
 *
 * Uploaded to wp-content/mu-plugins/ by the deploy pipeline.
 */
defined('ABSPATH') || exit;

const PB_MM_API   = 'https://api.paisabot.com';
const PB_MM_TTL   = 90;     // transient seconds
const PB_MM_STALE = 900;    // seconds after which we badge data "Delayed"

/** GET a JSON path from the API, cached in a transient. Returns array|null. */
function pb_mm_get(string $path): ?array {
    $key  = 'pb_mm_' . md5($path);
    $hit  = get_transient($key);
    if ($hit !== false) return $hit ?: null;

    $res = wp_remote_get(PB_MM_API . $path, [
        'timeout' => 3,
        'headers' => ['Accept' => 'application/json'],
    ]);
    if (is_wp_error($res) || wp_remote_retrieve_response_code($res) !== 200) {
        set_transient($key, [], 20);   // brief negative cache; keep fallback fast
        return null;
    }
    $data = json_decode(wp_remote_retrieve_body($res), true);
    if (!is_array($data)) { set_transient($key, [], 20); return null; }
    set_transient($key, $data, PB_MM_TTL);
    return $data;
}

/** Format a numeric close for display, currency by market. */
function pb_mm_price($close, string $market): string {
    $sym = ($market === 'NSE') ? '₹' : (in_array($market, ['NASDAQ', 'NYSE'], true) ? '$' : '');
    return $sym . number_format((float) $close, 2);
}

/** Format a signed percent like the theme expects: "+1.23%" / "−0.42%". */
function pb_mm_pct($pct): string {
    $p = (float) $pct;
    return ($p >= 0 ? '+' : '−') . number_format(abs($p), 2) . '%';
}

/** Fetch movers of one type, mapped to the given shape. */
function pb_mm_movers(string $type, int $limit): array {
    $d = pb_mm_get("/movers?type={$type}&limit={$limit}");
    return ($d && !empty($d['items'])) ? $d['items'] : [];
}

// ── Markets category page: Top Gainers / Top Losers ──────────────────────────
add_filter('aiv_mktd_gainers', function ($fallback) {
    $items = pb_mm_movers('gainers', 5);
    if (!$items) return $fallback;
    return array_map(fn($m) => [
        'name'  => $m['company_name'] ?: $m['symbol'],
        'sym'   => $m['symbol'],
        'price' => pb_mm_price($m['close'], $m['market']),
        'chg'   => pb_mm_pct($m['change_pct']),
    ], $items);
});
add_filter('aiv_mktd_losers', function ($fallback) {
    $items = pb_mm_movers('losers', 5);
    if (!$items) return $fallback;
    return array_map(fn($m) => [
        'name'  => $m['company_name'] ?: $m['symbol'],
        'sym'   => $m['symbol'],
        'price' => pb_mm_price($m['close'], $m['market']),
        'chg'   => pb_mm_pct($m['change_pct']),
    ], $items);
});

// ── Homepage "Top Stocks" grid → live top gainers ────────────────────────────
add_filter('aiv_stock_list', function ($fallback) {
    $items = pb_mm_movers('gainers', 8);
    if (!$items) return $fallback;
    return array_map(fn($m) => [
        'ticker' => $m['symbol'],
        'name'   => $m['company_name'] ?: $m['symbol'],
        'price'  => pb_mm_price($m['close'], $m['market']),
        'chg'    => pb_mm_pct($m['change_pct']),
        'up'     => (bool) $m['is_up'],
        'sector' => $m['market'],
    ], $items);
});

// ── Homepage "Key Indices" → live macro tiles (exclude sector rows) ───────────
add_filter('aiv_pulse_indices', function ($fallback) {
    $d = pb_mm_get('/indices');
    if (!$d || empty($d['items'])) return $fallback;
    $out = [];
    foreach ($d['items'] as $i) {
        if (($i['grp'] ?? '') === 'sector') continue;   // sectors → heatmap, not tiles
        $out[] = [
            'name'  => $i['name'],
            'value' => $i['value'],
            'chg'   => $i['pct_change'],
            'up'    => (bool) $i['is_up'],
        ];
    }
    return $out ?: $fallback;
});

// ── Homepage "Markets Pulse" sector heatmap → live NSE sector indices ─────────
add_filter('aiv_heatmap', function ($fallback) {
    $d = pb_mm_get('/indices');
    if (!$d || empty($d['items'])) return $fallback;
    $sectors = array_values(array_filter($d['items'], fn($i) => ($i['grp'] ?? '') === 'sector'));
    if (!$sectors) return $fallback;
    return array_map(function ($i, $n) {
        $cell = [
            'name' => $i['name'],
            'pct'  => $i['pct_change'],
            'up'   => (bool) $i['is_up'],
        ];
        if ($n === 0) { $cell['large'] = true; $cell['sub'] = $i['name'] . ' · ' . $i['value']; }
        return $cell;
    }, $sectors, array_keys($sectors));
});

// ── Freshness note: inject "As of HH:MM IST" (+ Delayed badge) after the
// homepage LIVE tag, without editing the theme (matches the existing
// output-buffer pattern used by paisabot-activate-theme.php). ────────────────
add_action('template_redirect', function () {
    if (!(is_home() && !is_paged())) return;
    ob_start(function ($html) {
        if (strpos($html, 'class="live-tag"') === false) return $html;
        $d = pb_mm_get('/status');
        $label = '';
        if ($d && !empty($d['last_updated'])) {
            $ts    = strtotime($d['last_updated']);
            $stale = (time() - $ts) > PB_MM_STALE;
            $ist   = gmdate('H:i', $ts + 5 * 3600 + 1800);   // IST = UTC+5:30
            $label = ' <span class="pb-asof' . ($stale ? ' pb-stale' : '') . '">'
                   . 'As of ' . esc_html($ist) . ' IST'
                   . ($stale ? ' · Delayed' : '') . '</span>';
        }
        // insert right after the first LIVE tag
        return preg_replace('/(<span class="live-tag">.*?<\/span>)/s', '$1' . $label, $html, 1);
    });
}, 1);

add_action('wp_head', function () {
    echo '<style id="pb-mm-css">'
        . '.pb-asof{font-size:11px;font-weight:600;color:#6b7280;margin-left:8px;'
        . 'letter-spacing:.02em;text-transform:none;white-space:nowrap}'
        . '.pb-asof.pb-stale{color:#b45309}'
        . '</style>';
}, 100);
