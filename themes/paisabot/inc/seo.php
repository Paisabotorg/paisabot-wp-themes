<?php
/**
 * PaisaBot theme SEO layer.
 *
 * Emits the page-level SEO the audit found missing: meta description,
 * canonical (home + archives), Open Graph / Twitter cards, hreflang across
 * the four language editions, and Organization/WebSite JSON-LD on the front
 * page. Steps aside entirely when a dedicated SEO plugin is active so
 * nothing is emitted twice.
 *
 * NewsArticle structured data is intentionally NOT emitted here — single.php
 * carries schema.org microdata and the sites already output NewsArticle
 * JSON-LD; a second block would trigger duplicate-schema warnings.
 */

if (!defined('ABSPATH')) exit;

function pb_seo_plugin_active(): bool {
    return defined('WPSEO_VERSION')            // Yoast
        || defined('RANK_MATH_VERSION')        // RankMath
        || defined('AIOSEO_VERSION');          // All in One SEO
}

/* Hide the WP version fingerprint regardless of SEO plugin. */
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');

/* ── Edition map (hreflang cluster for homepages/sections) ─────────── */
function pb_seo_editions(): array {
    return [
        'en' => 'https://www.paisabot.com',
        'hi' => 'https://hi.paisabot.com',
        'ml' => 'https://ml.paisabot.com',
        'te' => 'https://tel.paisabot.com',
    ];
}

function pb_seo_current_lang(): string {
    $lang = substr(get_locale(), 0, 2);
    return in_array($lang, ['hi', 'ml', 'te'], true) ? $lang : 'en';
}

/* ── Description / canonical / URL helpers ─────────────────────────── */
function pb_seo_description(): string {
    if (is_singular()) {
        $post = get_queried_object();
        $text = has_excerpt($post) ? get_the_excerpt($post) : wp_strip_all_tags($post->post_content ?? '');
        $text = trim(preg_replace('/\s+/', ' ', $text));
        return mb_substr($text, 0, 158);
    }
    if (is_category() || is_tag() || is_tax()) {
        $desc = term_description();
        if ($desc) return mb_substr(trim(wp_strip_all_tags($desc)), 0, 158);
        return sprintf('%s — latest %s news, analysis and market coverage from PaisaBot.',
            single_term_title('', false), strtolower(single_term_title('', false)));
    }
    $tagline = get_bloginfo('description');
    return $tagline && $tagline !== 'Just another WordPress site'
        ? $tagline . ' — trusted economic and financial news, markets and analysis in your language.'
        : 'Trusted economic and financial news, live markets, and stock analysis from PaisaBot.';
}

function pb_seo_current_url(): string {
    if (is_singular()) return get_permalink();
    if (is_category() || is_tag() || is_tax()) {
        $link = get_term_link(get_queried_object());
        if (!is_wp_error($link)) return $link;
    }
    return home_url(add_query_arg([], $GLOBALS['wp']->request ?? ''));
}

/* ── Head output ────────────────────────────────────────────────────── */
add_action('wp_head', 'pb_seo_head', 5);
function pb_seo_head(): void {
    if (pb_seo_plugin_active()) return;
    if (is_404() || is_search()) {
        echo '<meta name="robots" content="noindex,follow">' . "\n";
        return;
    }

    $desc  = esc_attr(pb_seo_description());
    $url   = esc_url(pb_seo_current_url());
    $title = esc_attr(wp_get_document_title());

    echo "\n<!-- PaisaBot SEO -->\n";
    if ($desc) echo '<meta name="description" content="' . $desc . '">' . "\n";

    /* Canonical: WP core covers singular; cover home + archives here. */
    if (!is_singular()) echo '<link rel="canonical" href="' . $url . '">' . "\n";

    /* hreflang — homepages link every edition; articles link their
       translated siblings when the pipeline has stored them in post meta. */
    $lang = pb_seo_current_lang();
    if (is_front_page() || is_home()) {
        foreach (pb_seo_editions() as $code => $home) {
            echo '<link rel="alternate" hreflang="' . esc_attr($code) . '" href="' . esc_url($home . '/') . '">' . "\n";
        }
        echo '<link rel="alternate" hreflang="x-default" href="https://www.paisabot.com/">' . "\n";
    } elseif (is_singular('post')) {
        $map = get_post_meta(get_the_ID(), '_pb_hreflang', true);
        $map = is_string($map) ? json_decode($map, true) : $map;
        if (is_array($map) && count($map) > 1) {
            foreach ($map as $code => $href) {
                if (!preg_match('/^[a-z]{2}$/', (string)$code)) continue;
                if (!preg_match('#^https://([a-z]+\.)?paisabot\.com/#', (string)$href)) continue;
                echo '<link rel="alternate" hreflang="' . esc_attr($code) . '" href="' . esc_url($href) . '">' . "\n";
            }
            if (!empty($map['en'])) {
                echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($map['en']) . '">' . "\n";
            }
        }
    }

    /* Open Graph + Twitter */
    $og_locales = ['en' => 'en_US', 'hi' => 'hi_IN', 'ml' => 'ml_IN', 'te' => 'te_IN'];
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    echo '<meta property="og:locale" content="' . esc_attr($og_locales[$lang] ?? 'en_US') . '">' . "\n";
    echo '<meta property="og:type" content="' . (is_singular('post') ? 'article' : 'website') . '">' . "\n";
    echo '<meta property="og:title" content="' . $title . '">' . "\n";
    if ($desc) echo '<meta property="og:description" content="' . $desc . '">' . "\n";
    echo '<meta property="og:url" content="' . $url . '">' . "\n";

    $img = '';
    if (is_singular() && has_post_thumbnail()) {
        $img = get_the_post_thumbnail_url(get_the_ID(), 'large');
    }
    if ($img) echo '<meta property="og:image" content="' . esc_url($img) . '">' . "\n";

    if (is_singular('post')) {
        echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '">' . "\n";
        echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '">' . "\n";
        $cat = get_the_category();
        if (!empty($cat[0])) echo '<meta property="article:section" content="' . esc_attr($cat[0]->name) . '">' . "\n";
    }

    echo '<meta name="twitter:card" content="' . ($img ? 'summary_large_image' : 'summary') . '">' . "\n";
    echo '<meta name="twitter:title" content="' . $title . '">' . "\n";
    if ($desc) echo '<meta name="twitter:description" content="' . $desc . '">' . "\n";
    if ($img)  echo '<meta name="twitter:image" content="' . esc_url($img) . '">' . "\n";

    /* Organization + WebSite (front page only). */
    if (is_front_page()) {
        $schema = [
            '@context' => 'https://schema.org',
            '@graph'   => [
                [
                    '@type' => 'NewsMediaOrganization',
                    '@id'   => 'https://www.paisabot.com/#org',
                    'name'  => 'PaisaBot',
                    'url'   => 'https://www.paisabot.com/',
                    'slogan'=> 'Economic Intelligence',
                    'sameAs'=> array_values(pb_seo_editions()),
                ],
                [
                    '@type'     => 'WebSite',
                    'name'      => get_bloginfo('name'),
                    'url'       => home_url('/'),
                    'inLanguage'=> $og_locales[$lang] ?? 'en_US',
                    'publisher' => ['@id' => 'https://www.paisabot.com/#org'],
                    'potentialAction' => [
                        '@type'       => 'SearchAction',
                        'target'      => home_url('/?s={search_term_string}'),
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
            ],
        ];
        echo '<script type="application/ld+json">' .
            wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) .
            '</script>' . "\n";
    }
    echo "<!-- /PaisaBot SEO -->\n";
}

/* ── Legacy sitemap paths → WP core sitemap (tools probe the Yoast path
      and currently pull a ~75 KB themed 404) ──────────────────────────── */
add_action('template_redirect', function () {
    $path = wp_parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    if (in_array($path, ['/sitemap.xml', '/sitemap_index.xml'], true)) {
        wp_redirect(home_url('/wp-sitemap.xml'), 301);
        exit;
    }
});

/* ── robots.txt: point crawlers at the sitemap ─────────────────────── */
add_filter('robots_txt', function ($output) {
    if (strpos($output, 'Sitemap:') === false) {
        $output .= "\nSitemap: " . home_url('/wp-sitemap.xml') . "\n";
    }
    return $output;
}, 20);

/* ── Title: make sure the brand, not the edition name, suffixes titles ── */
add_filter('document_title_parts', function ($parts) {
    if (pb_seo_plugin_active()) return $parts;
    $site = get_bloginfo('name');
    // Historic misconfiguration: sites titled "English"/"Hindi"… read badly
    // in tabs and SERPs. Brand every title with PaisaBot instead.
    if (in_array($site, ['English', 'Hindi', 'Malayalam', 'Telugu'], true)) {
        $parts['site'] = 'PaisaBot';
    }
    return $parts;
});
