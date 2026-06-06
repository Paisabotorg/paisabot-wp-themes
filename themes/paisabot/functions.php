<?php
defined('ABSPATH') || exit;

/**
 * AI Vartha Editorial — functions.php
 * v3.1.3 — slug-safe category URLs, focus-visible, reduced-motion, print styles
 */

/* ════════════════════════════════════════════════════════════════════════
   ONE-TIME SITE SETUP — runs on first page load, marks itself done
   ════════════════════════════════════════════════════════════════════════ */
add_action('init', 'aiv_auto_setup', 1);
function aiv_auto_setup(): void {
    if (get_option('aiv_setup_v2')) return;

    $lang = substr(get_locale(), 0, 2);  /* hi / ml / te */

    /* ── Categories ──────────────────────────────────────────────── */
    $cats = [
        ['slug'=>'markets',       'en'=>'Markets',        'hi'=>'बाज़ार',          'ml'=>'വിപണികൾ',         'te'=>'మార్కెట్లు'],
        ['slug'=>'policy',        'en'=>'Policy',         'hi'=>'नीति',           'ml'=>'നയം',              'te'=>'విధానం'],
        ['slug'=>'banking',       'en'=>'Banking',        'hi'=>'बैंकिंग',         'ml'=>'ബാങ്കിംഗ്',        'te'=>'బ్యాంకింగ్'],
        ['slug'=>'economy',       'en'=>'Economy',        'hi'=>'अर्थव्यवस्था',    'ml'=>'സമ്പദ്‌വ്യവസ്ഥ',  'te'=>'ఆర్థిక వ్యవస్థ'],
        ['slug'=>'global',        'en'=>'Global',         'hi'=>'वैश्विक',         'ml'=>'ആഗോളം',            'te'=>'గ్లోబల్'],
        ['slug'=>'foreign-policy','en'=>'Foreign Policy', 'hi'=>'विदेश नीति',      'ml'=>'വിദേശ നയം',        'te'=>'విదేశ విధానం'],
        ['slug'=>'technology',    'en'=>'Technology',     'hi'=>'प्रौद्योगिकी',    'ml'=>'സാങ്കേതികവിദ്യ',  'te'=>'సాంకేతికత'],
        ['slug'=>'opinion',       'en'=>'Opinion',        'hi'=>'राय',            'ml'=>'അഭിപ്രായം',        'te'=>'అభిప్రాయం'],
        ['slug'=>'breaking',      'en'=>'Breaking',       'hi'=>'ब्रेकिंग',        'ml'=>'ബ്രേക്കിംഗ്',      'te'=>'బ్రేకింగ్'],
    ];
    foreach ($cats as $c) {
        if (!term_exists($c['slug'], 'category')) {
            wp_insert_term($c[$lang] ?? $c['en'], 'category', ['slug' => $c['slug']]);
        }
    }

    /* ── Pages ───────────────────────────────────────────────────── */
    $pages = [
        ['slug'=>'about',           'en'=>'About AI Vartha',       'hi'=>'AI Vartha के बारे में',  'ml'=>'AI Vartha-യെ കുറിച്ച്', 'te'=>'AI Vartha గురించి',
         'body'=>'<h2>India\'s Trusted Economic Intelligence Platform</h2><p>AI Vartha is a multilingual economic and financial news platform delivering trusted, accurate, and timely reporting in Hindi, Malayalam, and Telugu.</p>'],
        ['slug'=>'contact',         'en'=>'Contact',               'hi'=>'संपर्क करें',            'ml'=>'ബന്ധപ്പെടുക',           'te'=>'సంప్రదించండి',
         'body'=>'<h2>Get in Touch</h2><p>Editorial: <strong>editorial@paisabot.com</strong></p><p>Advertising: <strong>partnerships@paisabot.com</strong></p>'],
        ['slug'=>'privacy-policy',  'en'=>'Privacy Policy',        'hi'=>'गोपनीयता नीति',          'ml'=>'സ്വകാര്യതാ നയം',       'te'=>'గోప్యతా విధానం',
         'body'=>'<h2>Privacy Policy</h2><p>AI Vartha collects minimal data and does not sell personal information. Contact editorial@paisabot.com to request data deletion.</p>'],
        ['slug'=>'disclaimer',      'en'=>'Disclaimer',            'hi'=>'अस्वीकरण',               'ml'=>'നിരാകരണം',             'te'=>'నిరాకరణ',
         'body'=>'<h2>Disclaimer</h2><p>Content on AI Vartha is for informational purposes only and does not constitute financial advice. Always consult a qualified advisor before making investment decisions.</p>'],
        ['slug'=>'editorial-policy','en'=>'Editorial Policy',      'hi'=>'संपादकीय नीति',          'ml'=>'എഡിറ്റോറിയൽ നയം',      'te'=>'సంపాదకీయ విధానం',
         'body'=>'<h2>Editorial Policy</h2><p>AI Vartha\'s editorial coverage is fully independent of advertising. We verify all facts before publication and publish corrections transparently.</p>'],
        ['slug'=>'subscribe',       'en'=>'Subscribe',             'hi'=>'सदस्यता लें',            'ml'=>'സബ്‌സ്‌ക്രൈബ് ചെയ്യൂ',  'te'=>'సభ్యత్వం పొందండి',
         'body'=>'<h2>Subscribe to AI Vartha</h2><p>Get daily economic and financial news in your language. Email <strong>subscribe@paisabot.com</strong>.</p>'],
        ['slug'=>'stock-analysis',  'en'=>'Stock Analysis',        'hi'=>'स्टॉक विश्लेषण',         'ml'=>'സ്റ്റോക്ക് വിശകലനം',    'te'=>'స్టాక్ విశ్లేషణ',
         'body'=>'<h2>Stock Analysis</h2><p><a href="https://analyse.paisabot.com" target="_blank" rel="noopener">Open Stock Analyser →</a></p><p>Technical charts, MACD, RSI, StochRSI, support and resistance levels.</p>'],
        ['slug'=>'markets-pro',     'en'=>'Markets Pro',           'hi'=>'मार्केट्स प्रो',          'ml'=>'മാർക്കറ്റ്സ് പ്രോ',     'te'=>'మార్కెట్స్ ప్రో',
         'body'=>'<h2>Markets Pro</h2><p><strong>Coming Soon.</strong> Join the waitlist: <strong>pro@paisabot.com</strong></p>'],
    ];
    foreach ($pages as $p) {
        if (!get_page_by_path($p['slug'])) {
            wp_insert_post([
                'post_title'     => $p[$lang] ?? $p['en'],
                'post_name'      => $p['slug'],
                'post_content'   => $p['body'],
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'comment_status' => 'closed',
            ]);
        }
    }

    /* ── Generate Application Password ───────────────────────────── */
    $user = get_user_by('login', 'admin');
    if ($user && class_exists('WP_Application_Passwords')) {
        $existing = WP_Application_Passwords::get_user_application_passwords($user->ID);
        $has = array_filter($existing, fn($p) => $p['name'] === 'aiv-api');
        if (!$has) {
            $result = WP_Application_Passwords::create_new_application_password($user->ID, ['name' => 'aiv-api']);
            if (!is_wp_error($result)) {
                /* Store once for admin to copy */
                update_option('aiv_new_app_pass', $result[0]);
            }
        }
    }

    update_option('aiv_setup_v2', '1');
}

/* Show generated app password in admin once */
add_action('admin_notices', function () {
    $pw = get_option('aiv_new_app_pass');
    if (!$pw || !current_user_can('manage_options')) return;
    echo '<div class="notice notice-success"><p>';
    printf(
        '<strong>AI Vartha Setup:</strong> Application password generated for <code>admin</code>: <code>%s</code> &mdash; copy this to <code>.wp-credentials</code>.',
        esc_html($pw)
    );
    echo '</p></div>';
    delete_option('aiv_new_app_pass');
});

/* ════════════════════════════════════════════════════════════════════════
   SETUP
   ════════════════════════════════════════════════════════════════════════ */
function aivartha_setup() {
    load_theme_textdomain('aivartha', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('automatic-feed-links');
    add_theme_support('custom-logo', ['height'=>60,'width'=>200,'flex-height'=>true,'flex-width'=>true]);

    set_post_thumbnail_size(800, 450, true);
    add_image_size('aiv-hero',    1200, 630, true);
    add_image_size('aiv-mid',     600,  450, true);
    add_image_size('aiv-card',    600,  338, true);
    add_image_size('aiv-banking', 800,  500, true);
    add_image_size('aiv-thumb',   180,  135, true);

    register_nav_menus([
        'primary' => __('Primary Navigation', 'aivartha'),
        'footer'  => __('Footer Navigation', 'aivartha'),
    ]);
}
add_action('after_setup_theme', 'aivartha_setup');

/* ════════════════════════════════════════════════════════════════════════
   FONT LOADING (Indic-aware)
   ════════════════════════════════════════════════════════════════════════ */
function aivartha_font_url(): string {
    $script = get_option('aivartha_script', '');
    $locale = get_locale();
    $g = 'Inter:wght@400;500;600;700;800;900&family=Source+Serif+4:opsz,wght@8..60,400;8..60,500;8..60,600;8..60,700&family=JetBrains+Mono:wght@400;500;600';

    if ($script === 'malayalam' || str_starts_with($locale, 'ml')) {
        $g .= '&family=Noto+Sans+Malayalam:wght@400;500;600;700;800';
        $v = "'Noto Sans Malayalam'";
    } elseif ($script === 'hindi' || str_starts_with($locale, 'hi')) {
        $g .= '&family=Noto+Sans+Devanagari:wght@400;500;600;700;800';
        $v = "'Noto Sans Devanagari'";
    } elseif ($script === 'telugu' || str_starts_with($locale, 'te')) {
        $g .= '&family=Noto+Sans+Telugu:wght@400;500;600;700;800';
        $v = "'Noto Sans Telugu'";
    } else {
        $v = "'Inter'";
    }
    wp_add_inline_style('aivartha-style', ":root{--regional-font:{$v};}");
    return "https://fonts.googleapis.com/css2?family={$g}&display=swap";
}

function aivartha_enqueue() {
    $ver = wp_get_theme()->get('Version');
    wp_enqueue_style('paisabot-fonts', aivartha_font_url(), [], null);
    wp_enqueue_style('paisabot-style', get_stylesheet_uri(), ['paisabot-fonts'], $ver);
    wp_enqueue_script('paisabot-js', get_template_directory_uri() . '/assets/js/main.js', [], $ver, true);
    if (is_singular()) wp_enqueue_script('comment-reply');
}
add_action('wp_enqueue_scripts', 'aivartha_enqueue');

/* ════════════════════════════════════════════════════════════════════════
   WIDGET AREAS
   - Main sidebar
   - 5 ad zones (paste any HTML/JS ad code, GAM, AdSense, etc.)
   ════════════════════════════════════════════════════════════════════════ */
function aivartha_widgets() {
    $args = [
        'before_widget' => '<section class="widget %2$s">',
        'after_widget'  => '</div></section>',
        'before_title'  => '<div class="widget-hd">',
        'after_title'   => '</div><div class="widget-body">',
    ];
    register_sidebar(array_merge($args, ['name' => __('Article Sidebar', 'aivartha'), 'id' => 'sidebar-1']));
    register_sidebar(array_merge($args, ['name' => __('Footer Column 4', 'aivartha'), 'id' => 'footer-4']));

    // Ad zones — empty wrappers; paste your GAM/AdSense code via Appearance > Widgets
    $ads = [
        ['ad-leaderboard',             __('Ad — Leaderboard (below front-page hero)',         'paisabot')],
        ['ad-leaderboard-pulse',       __('Ad — Wide sponsor (between Markets Pulse & Briefing)', 'paisabot')],
        ['ad-leaderboard-voices-top',  __('Ad — Wide sponsor (above Voices)',                  'paisabot')],
        ['ad-leaderboard-voices',      __('Ad — Wide sponsor (below Voices)',                  'paisabot')],
        ['ad-mpu',                     __('Ad — MPU 300×250 (in briefing sidebar)',           'paisabot')],
        ['ad-native',                  __('Ad — Native / Sponsored card',                      'paisabot')],
        ['ad-skyscraper',              __('Ad — Skyscraper 160×600 (article sidebar)',        'paisabot')],
        ['ad-sticky',                  __('Ad — Sticky footer bar',                            'paisabot')],
        ['ad-in-article',              __('Ad — In-article (after 3rd paragraph)',            'paisabot')],
    ];
    foreach ($ads as [$id, $name]) {
        register_sidebar([
            'name'          => $name,
            'id'            => $id,
            'description'   => __('Paste any HTML/JS ad code as a Custom HTML widget.', 'aivartha'),
            'before_widget' => '<div class="ad-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '',
            'after_title'   => '',
        ]);
    }
}
add_action('widgets_init', 'aivartha_widgets');

/* ════════════════════════════════════════════════════════════════════════
   ICON SPRITE HELPER (unchanged from v2)
   ════════════════════════════════════════════════════════════════════════ */
function aiv_icon(string $id, string $cls = ''): string {
    $a    = $cls ? " class=\"{$cls}\"" : '';
    $href = str_starts_with($id, 'i-') ? "#{$id}" : "#i-{$id}";
    return "<svg{$a} aria-hidden=\"true\"><use href=\"{$href}\"/></svg>";
}

/* Slug-safe category URL — works on all language sites (slug is always English) */
function aiv_cat_url(string $slug): string {
    $cat = get_category_by_slug($slug);
    return $cat ? get_category_link($cat->term_id) : home_url('/category/' . $slug);
}

/* Category badge — anchor (don't nest inside other <a>) */
function aiv_cat(int $id = 0, string $extra = ''): void {
    $cats = get_the_category($id ?: get_the_ID());
    if (!$cats) return;
    $c   = $cats[0];
    $cls = trim('cat-tag cat-' . sanitize_html_class($c->slug) . ' ' . $extra);
    echo '<a href="' . esc_url(get_category_link($c->term_id)) . '" class="' . esc_attr($cls) . '" rel="category">' . esc_html($c->name) . '</a>';
}

/* Category badge — span (safe inside <a>) */
function aiv_cat_span(int $id = 0, string $extra = ''): void {
    $cats = get_the_category($id ?: get_the_ID());
    if (!$cats) return;
    $c   = $cats[0];
    $cls = trim('cat-tag cat-' . sanitize_html_class($c->slug) . ' ' . $extra);
    echo '<span class="' . esc_attr($cls) . '">' . esc_html($c->name) . '</span>';
}

function aiv_read_time(int $id = 0): string {
    $text = wp_strip_all_tags(get_post_field('post_content', $id ?: get_the_ID()));
    $wpm  = in_array(get_option('aivartha_script', ''), ['malayalam','hindi','telugu']) ? 170 : 220;
    $wc   = str_word_count($text) ?: (mb_strlen($text) / 5);
    return max(1, (int) ceil($wc / $wpm)) . ' min';
}

function aiv_placeholder(): string {
    return '<div class="card-img-placeholder">' . aiv_icon('newspaper') . '</div>';
}

function aiv_initials(string $name): string {
    $parts = preg_split('/\s+/', trim($name));
    $first = mb_substr($parts[0] ?? '', 0, 1);
    $last  = mb_substr(end($parts) ?? '', 0, 1);
    return strtoupper($first . $last);
}

/* ════════════════════════════════════════════════════════════════════════
   CONTENT-CURATION HELPERS — replace mock data with real queries
   All return arrays of WP_Post (or stdClass for non-post data).
   Override these in a child theme to wire to your CMS structure.
   ════════════════════════════════════════════════════════════════════════ */

/** Top story (hero lead) — first sticky post, fallback to latest */
function aiv_top_story(): ?WP_Post {
    $sticky = get_option('sticky_posts');
    if ($sticky) {
        $p = get_post($sticky[0]);
        if ($p) return $p;
    }
    $q = new WP_Query(['posts_per_page' => 1, 'post_status' => 'publish', 'ignore_sticky_posts' => 1]);
    return $q->posts[0] ?? null;
}

/** Front-page MID story (right of lead) */
function aiv_mid_story(int $exclude = 0): ?WP_Post {
    $sticky = array_filter(get_option('sticky_posts', []), fn($id) => $id !== $exclude);
    if (count($sticky) > 1) {
        $p = get_post(array_values($sticky)[1] ?? null);
        if ($p) return $p;
    }
    $q = new WP_Query([
        'posts_per_page' => 1,
        'post__not_in'   => array_filter([$exclude]),
        'category_name'  => 'markets',
        'ignore_sticky_posts' => 1,
    ]);
    return $q->posts[0] ?? null;
}

/** Front-page RIGHT column — 4 stories from different categories */
function aiv_right_stories(array $exclude = [], int $n = 4): array {
    $cats = ['banking', 'global', 'opinion', 'economy', 'policy', 'foreign-policy', 'technology'];
    $out  = [];
    foreach ($cats as $slug) {
        if (count($out) >= $n) break;
        $q = new WP_Query([
            'posts_per_page'      => 1,
            'category_name'       => $slug,
            'post__not_in'        => array_merge($exclude, wp_list_pluck($out, 'ID')),
            'ignore_sticky_posts' => 1,
        ]);
        if (!empty($q->posts)) $out[] = $q->posts[0];
    }
    // Backfill from recent if categories aren't populated yet
    if (count($out) < $n) {
        $fill = new WP_Query([
            'posts_per_page'      => $n - count($out),
            'post__not_in'        => array_merge($exclude, wp_list_pluck($out, 'ID')),
            'ignore_sticky_posts' => 1,
        ]);
        $out = array_merge($out, $fill->posts);
    }
    return $out;
}

/** The Briefing — posts tagged "briefing" (fallback: top 5 recent) */
function aiv_briefing_posts(int $n = 5): array {
    $q = new WP_Query([
        'posts_per_page' => $n,
        'tag'            => 'briefing',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
    if ($q->have_posts()) return $q->posts;
    return get_posts(['numberposts' => $n, 'post_status' => 'publish']);
}

/** Opinion columns ("Voices") */
function aiv_voices_posts(int $n = 4): array {
    $q = new WP_Query([
        'posts_per_page' => $n,
        'category_name'  => 'opinion',
    ]);
    return $q->posts;
}

/** Banking feature + 3 secondary rows */
function aiv_banking_posts(int $n = 4): array {
    $q = new WP_Query(['posts_per_page' => $n, 'category_name' => 'banking']);
    return $q->posts;
}

/** Global cells (4 stories, ideally one per region) */
function aiv_global_posts(int $n = 4): array {
    $q = new WP_Query(['posts_per_page' => $n, 'category_name' => 'global']);
    return $q->posts;
}

/* ════════════════════════════════════════════════════════════════════════
   MARKET-DATA HELPERS — return static defaults, filterable
   To wire to a live feed, hook into these filters from a child theme
   or a small companion plugin.
   ════════════════════════════════════════════════════════════════════════ */

/** Top-bar ticker */
function aiv_market_items(): array {
    return apply_filters('aiv_market_items', [
        ['n'=>'NIFTY 50',   'p'=>'22,450.20', 'c'=>'+1.20%',  'up'=>true],
        ['n'=>'SENSEX',     'p'=>'73,888.45', 'c'=>'+0.95%',  'up'=>true],
        ['n'=>'BANK NIFTY', 'p'=>'48,120.10', 'c'=>'-0.31%',  'up'=>false],
        ['n'=>'USD/INR',    'p'=>'₹83.45',    'c'=>'-0.12%',  'up'=>false],
        ['n'=>'GOLD',       'p'=>'₹71,450',   'c'=>'+0.50%',  'up'=>true],
        ['n'=>'BRENT',      'p'=>'$85.60',    'c'=>'+1.10%',  'up'=>true],
        ['n'=>'IT INDEX',   'p'=>'38,240.00', 'c'=>'+2.30%',  'up'=>true],
        ['n'=>'MIDCAP 100', 'p'=>'41,200.55', 'c'=>'+0.80%',  'up'=>true],
    ]);
}

/** Dateline headline indices */
function aiv_dateline_data(): array {
    return apply_filters('aiv_dateline', [
        'vol'      => 'Vol. IV · No. ' . wp_date('z'),
        'edition'  => __('National Edition', 'aivartha'),
        'weather'  => __('Mumbai 32°C · Humid', 'aivartha'),
        'sensex'   => '+0.95%',
        'nifty'    => '+1.20%',
        'rupee'    => '83.45',
    ]);
}

/** Markets Pulse — sector heatmap (1 big + 14 smalls = 18 cells) */
function aiv_heatmap_data(): array {
    return apply_filters('aiv_heatmap', [
        ['name'=>'INFOTECH',  'pct'=>'+2.31%','up'=>true, 'large'=>true, 'sub'=>'Nifty IT · 38,240'],
        ['name'=>'BANK',      'pct'=>'+0.85%','up'=>true],
        ['name'=>'AUTO',      'pct'=>'+1.42%','up'=>true],
        ['name'=>'PHARMA',    'pct'=>'+0.95%','up'=>true],
        ['name'=>'CONSUMER',  'pct'=>'-0.30%','up'=>false],
        ['name'=>'METAL',     'pct'=>'+2.05%','up'=>true],
        ['name'=>'REALTY',    'pct'=>'-0.62%','up'=>false],
        ['name'=>'FMCG',      'pct'=>'+0.18%','up'=>true],
        ['name'=>'ENERGY',    'pct'=>'+1.15%','up'=>true],
        ['name'=>'MEDIA',     'pct'=>'-1.20%','up'=>false],
        ['name'=>'PSU BANK',  'pct'=>'-0.45%','up'=>false],
        ['name'=>'INFRA',     'pct'=>'+1.78%','up'=>true],
        ['name'=>'COMMODITY', 'pct'=>'+0.92%','up'=>true],
        ['name'=>'FIN SVC',   'pct'=>'+0.40%','up'=>true],
        ['name'=>'POWER',     'pct'=>'+1.32%','up'=>true],
    ]);
}

/** Key indices in pulse-band right column */
function aiv_pulse_indices(): array {
    return apply_filters('aiv_pulse_indices', [
        ['name'=>'NIFTY 50',   'value'=>'22,450.20', 'chg'=>'+1.20%', 'up'=>true],
        ['name'=>'BANK NIFTY', 'value'=>'48,120.10', 'chg'=>'-0.31%', 'up'=>false],
        ['name'=>'NIFTY IT',   'value'=>'38,240.00', 'chg'=>'+2.30%', 'up'=>true],
        ['name'=>'INDIA VIX',  'value'=>'11.84',     'chg'=>'-3.20%', 'up'=>false],
    ]);
}

/** "By the Numbers" sidebar card */
function aiv_numbers_today(): array {
    return apply_filters('aiv_numbers', [
        ['figure'=>'₹2.10','unit'=>'lakh crore', 'label'=>__('GST collections in April — an all-time record', 'aivartha'),       'accent'=>'up'],
        ['figure'=>'6.5%','unit'=>'',            'label'=>__('RBI repo rate · unchanged for the 11th consecutive meeting', 'aivartha'), 'accent'=>''],
        ['figure'=>'₹38,000','unit'=>'crore',    'label'=>__('Net FII inflow in May — third straight month of buying', 'aivartha'),    'accent'=>'up'],
        ['figure'=>'3.61%','unit'=>'',           'label'=>__('CPI inflation in April · lowest print in 19 months', 'aivartha'),        'accent'=>'down'],
    ]);
}

/** Index-led stories strip (5 cells) */
function aiv_index_stories(): array {
    return apply_filters('aiv_index_stories', [
        ['symbol'=>'NIFTY 50',   'value'=>'22,450','chg'=>'+1.20%','up'=>true,  'cat'=>'Markets',     'title'=>'IT rally lifts Nifty past 22,400 for first time'],
        ['symbol'=>'BANK NIFTY', 'value'=>'48,120','chg'=>'-0.31%','up'=>false, 'cat'=>'Banking',     'title'=>'PSU drag erases early gains in bank index'],
        ['symbol'=>'USD/INR',    'value'=>'83.45', 'chg'=>'-0.12%','up'=>false, 'cat'=>'Currency',    'title'=>'Rupee strengthens on RBI dollar inflows'],
        ['symbol'=>'GOLD',       'value'=>'71,450','chg'=>'+0.50%','up'=>true,  'cat'=>'Commodities', 'title'=>'Gold tests fresh peak as ETF flows resume'],
        ['symbol'=>'BRENT',      'value'=>'$85.6', 'chg'=>'+1.10%','up'=>true,  'cat'=>'Energy',      'title'=>'OMC stocks slip 2% as Brent crosses $87 mark'],
    ]);
}

/** Global region cells */
function aiv_global_cells(): array {
    return apply_filters('aiv_global_cells', [
        ['region'=>'United States','flag'=>'#B22234','title'=>'Fed dot plot signals two cuts in 2026; markets had priced three','stat'=>'S&P 500','up'=>true,  'pct'=>'+0.42%'],
        ['region'=>'China',        'flag'=>'#DE2910','title'=>'Industrial output rebounds 6.7% YoY, lifting iron-ore prices',   'stat'=>'Shanghai','up'=>true, 'pct'=>'+1.18%'],
        ['region'=>'Europe',       'flag'=>'#003399','title'=>'ECB holds at 3.25%; Lagarde says June cut "discussed but not decided"','stat'=>'EuroStoxx 50','up'=>false,'pct'=>'-0.22%'],
        ['region'=>'Japan',        'flag'=>'#BC002D','title'=>'BoJ delays QT timeline; yen weakens below 156 to dollar',        'stat'=>'Nikkei 225','up'=>true,'pct'=>'+0.65%'],
    ]);
}

/** Stock analysis data — stub with realistic defaults, filterable for live data */
function aiv_get_stock_data(string $ticker): array {
    $defaults = [
        'RELIANCE' => ['name'=>'Reliance Industries Ltd','sector'=>'Energy · Petrochemicals','price'=>'₹2,847.50','change'=>'+34.20','change_pct'=>'+1.22%','up'=>true,'open'=>'₹2,813.00','high'=>'₹2,861.40','low'=>'₹2,798.50','prev_close'=>'₹2,813.30','volume'=>'42,18,470','turnover'=>'₹1,201 Cr','vwap'=>'₹2,831.22','mktcap'=>'₹19.2L Cr','pe'=>'28.4x','eps'=>'₹100.26','beta'=>'0.92','div_yield'=>'0.38%','face_value'=>'₹10','w52_high'=>'₹3,024.90','w52_low'=>'₹2,220.15','chg_5d'=>'+2.8%','chg_21d'=>'+6.4%'],
        'INFY'     => ['name'=>'Infosys Ltd','sector'=>'Information Technology','price'=>'₹1,542.30','change'=>'+18.75','change_pct'=>'+1.23%','up'=>true,'open'=>'₹1,523.00','high'=>'₹1,549.90','low'=>'₹1,518.40','prev_close'=>'₹1,523.55','volume'=>'28,44,200','turnover'=>'₹438 Cr','vwap'=>'₹1,534.10','mktcap'=>'₹6.4L Cr','pe'=>'24.1x','eps'=>'₹63.98','beta'=>'0.78','div_yield'=>'2.10%','face_value'=>'₹5','w52_high'=>'₹1,903.00','w52_low'=>'₹1,358.35','chg_5d'=>'+1.4%','chg_21d'=>'+3.2%'],
        'HDFCBANK' => ['name'=>'HDFC Bank Ltd','sector'=>'Banking · Financial Services','price'=>'₹1,712.45','change'=>'+22.10','change_pct'=>'+1.31%','up'=>true,'open'=>'₹1,690.00','high'=>'₹1,718.80','low'=>'₹1,685.20','prev_close'=>'₹1,690.35','volume'=>'61,02,340','turnover'=>'₹1,044 Cr','vwap'=>'₹1,701.88','mktcap'=>'₹12.8L Cr','pe'=>'18.2x','eps'=>'₹94.09','beta'=>'0.88','div_yield'=>'1.24%','face_value'=>'₹1','w52_high'=>'₹1,880.00','w52_low'=>'₹1,363.55','chg_5d'=>'+3.1%','chg_21d'=>'+7.8%'],
    ];
    $base = $defaults[$ticker] ?? array_merge($defaults['RELIANCE'], ['name'=>$ticker.' Ltd','sector'=>'Equity']);

    return apply_filters('aiv_stock_data', array_merge($base, [
        'resistance' => [
            ['label'=>'R3','val'=>'₹2,920','pct'=>90,'strength'=>'strong'],
            ['label'=>'R2','val'=>'₹2,890','pct'=>75,'strength'=>''],
            ['label'=>'R1','val'=>'₹2,865','pct'=>60,'strength'=>''],
        ],
        'support' => [
            ['label'=>'S1','val'=>'₹2,820','pct'=>55],
            ['label'=>'S2','val'=>'₹2,795','pct'=>40],
            ['label'=>'S3','val'=>'₹2,760','pct'=>25],
        ],
        'moving_averages' => [
            ['name'=>'9 EMA',   'val'=>'₹2,831','above'=>true],
            ['name'=>'21 EMA',  'val'=>'₹2,810','above'=>true],
            ['name'=>'50 SMA',  'val'=>'₹2,756','above'=>true],
            ['name'=>'200 SMA', 'val'=>'₹2,612','above'=>true],
        ],
        'indicators' => [
            ['name'=>'RSI (14)',   'val'=>'62.4',  'signal'=>'NEUTRAL'],
            ['name'=>'MACD',       'val'=>'+12.3', 'signal'=>'BUY'],
            ['name'=>'Stoch RSI',  'val'=>'0.78',  'signal'=>'OVERBOUGHT'],
            ['name'=>'ADX',        'val'=>'28.1',  'signal'=>'TRENDING'],
            ['name'=>'OBV',        'val'=>'↑ Rising','signal'=>'BUY'],
            ['name'=>'ATR (14)',   'val'=>'₹42.6', 'signal'=>'NEUTRAL'],
        ],
        'momentum_verdict' => 'MODERATELY BULLISH',
        'alerts' => [
            ['type'=>'breakout','icon'=>'🟢','title'=>'BREAKOUT','desc'=>'Above 200 SMA resistance broken today at ₹2,831'],
            ['type'=>'pattern', 'icon'=>'🟡','title'=>'PATTERN', 'desc'=>'Bullish Engulfing detected (Daily TF) — Confirmation pending'],
            ['type'=>'volume',  'icon'=>'🔵','title'=>'VOLUME',  'desc'=>'2.1× avg volume — High conviction breakout signal'],
        ],
        'verdict' => [
            'rating'    => 'STRONG BUY',
            'target'    => '₹3,050',
            'upside'    => '+7.1%',
            'stop_loss' => '₹2,780',
            'risk'      => '-2.4%',
            'rr_ratio'  => '1 : 2.9',
            'note'      => $base['name'].' has broken out above its 6-month consolidation zone on strong volumes. Institutional buying is visible in OBV. Risk-reward favours longs with defined stop at ₹2,780.',
        ],
    ]), $ticker);
}

/** Stock list for markets page — filterable */
function aiv_stock_list(): array {
    return apply_filters('aiv_stock_list', [
        ['ticker'=>'RELIANCE', 'name'=>'Reliance Industries', 'price'=>'₹2,847','chg'=>'+1.22%','up'=>true, 'sector'=>'Energy'],
        ['ticker'=>'INFY',     'name'=>'Infosys',             'price'=>'₹1,542','chg'=>'+1.23%','up'=>true, 'sector'=>'IT'],
        ['ticker'=>'HDFCBANK', 'name'=>'HDFC Bank',           'price'=>'₹1,712','chg'=>'+1.31%','up'=>true, 'sector'=>'Banking'],
        ['ticker'=>'TCS',      'name'=>'TCS',                  'price'=>'₹3,890','chg'=>'-0.42%','up'=>false,'sector'=>'IT'],
        ['ticker'=>'ICICIBANK','name'=>'ICICI Bank',           'price'=>'₹1,128','chg'=>'+0.88%','up'=>true, 'sector'=>'Banking'],
        ['ticker'=>'AXISBANK', 'name'=>'Axis Bank',            'price'=>'₹1,204','chg'=>'-0.21%','up'=>false,'sector'=>'Banking'],
        ['ticker'=>'WIPRO',    'name'=>'Wipro',                'price'=>'₹512', 'chg'=>'+0.65%','up'=>true, 'sector'=>'IT'],
        ['ticker'=>'BHARTIARTL','name'=>'Bharti Airtel',      'price'=>'₹1,688','chg'=>'+2.10%','up'=>true, 'sector'=>'Telecom'],
    ]);
}

/** Breaking news bar */
function aiv_breaking(): array {
    $ps = get_posts(['numberposts'=>10, 'category_name'=>'breaking', 'post_status'=>'publish']);
    return $ps ?: get_posts(['numberposts'=>10, 'post_status'=>'publish']);
}

/* ════════════════════════════════════════════════════════════════════════
   BODY CLASS + LANG
   ════════════════════════════════════════════════════════════════════════ */
add_filter('body_class', function($c) {
    $c[] = 'script-' . sanitize_html_class(get_option('aivartha_script', 'latin'));
    return $c;
});

add_filter('language_attributes', function($out) {
    $map = ['malayalam'=>'ml', 'hindi'=>'hi', 'telugu'=>'te'];
    $s   = get_option('aivartha_script', '');
    if (isset($map[$s])) $out = preg_replace('/lang="[^"]*"/', 'lang="' . $map[$s] . '"', $out);
    return $out;
});

add_filter('excerpt_length', fn() => 22, 999);
add_filter('excerpt_more',   fn() => '…');

/* ════════════════════════════════════════════════════════════════════════
   IN-ARTICLE AD INJECTION
   Injects the "ad-in-article" widget area after the 3rd paragraph of every
   single post. If the widget area is empty, nothing renders.
   ════════════════════════════════════════════════════════════════════════ */
add_filter('the_content', function ($content) {
    if (!is_singular('post') || !is_main_query() || !is_active_sidebar('ad-in-article')) {
        return $content;
    }
    $needle = '</p>';
    $count  = 0; $offset = 0;
    while (($pos = strpos($content, $needle, $offset)) !== false) {
        $count++;
        if ($count === 3) {
            ob_start();
            get_template_part('template-parts/ads/in-article');
            $ad = ob_get_clean();
            return substr_replace($content, $needle . $ad, $pos, strlen($needle));
        }
        $offset = $pos + strlen($needle);
    }
    return $content;
});

/* ════════════════════════════════════════════════════════════════════════
   ADMIN — onboarding nudge
   ════════════════════════════════════════════════════════════════════════ */
add_action('admin_notices', function() {
    $screen = get_current_screen();
    if (!$screen || $screen->base !== 'dashboard') return;
    if (get_option('aivartha_setup_dismissed')) return;
    $url = admin_url('widgets.php');
    echo '<div class="notice notice-info is-dismissible">
        <p><strong>AI Vartha Editorial v3</strong> is active. Configure your editorial sections:</p>
        <ol style="margin-left:20px">
            <li>Create categories: <code>markets</code>, <code>policy</code>, <code>banking</code>, <code>economy</code>, <code>global</code>, <code>foreign-policy</code>, <code>technology</code>, <code>opinion</code>, <code>breaking</code></li>
            <li>Tag 5 articles with <code>briefing</code> to populate the homepage Briefing block</li>
            <li>Mark today\'s top story as <em>Sticky</em> for the Front Page lead</li>
            <li><a href="' . esc_url($url) . '">Paste ad codes</a> into the 6 ad zones (Leaderboard, MPU, Native, Skyscraper, Sticky, In-article)</li>
        </ol>
    </div>';
});
