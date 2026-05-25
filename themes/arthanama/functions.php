<?php
/**
 * ArthaNama — Economic News Theme
 * functions.php: setup, enqueue, widgets, helpers
 */

defined('ABSPATH') || exit;

/* ─── THEME SETUP ─────────────────────────────────────────────────────────── */
function arthanama_setup() {
    load_theme_textdomain('arthanama', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('automatic-feed-links');
    add_theme_support('customize-selective-refresh-widgets');

    set_post_thumbnail_size(800, 450, true);
    add_image_size('arthanama-hero', 1200, 630, true);
    add_image_size('arthanama-card', 600, 338, true);
    add_image_size('arthanama-thumb', 200, 150, true);

    register_nav_menus([
        'primary' => __('Primary Navigation', 'arthanama'),
        'footer'  => __('Footer Navigation', 'arthanama'),
    ]);
}
add_action('after_setup_theme', 'arthanama_setup');

/* ─── FONT DETECTION ──────────────────────────────────────────────────────── */
function arthanama_get_font_url(): string {
    $script = get_option('arthanama_language_script', '');
    $locale = get_locale();

    // Determine which Noto font to load
    if ($script === 'malayalam' || str_starts_with($locale, 'ml')) {
        $font = 'Noto+Sans+Malayalam:wght@400;600;700';
        $css_var = "'Noto Sans Malayalam'";
    } elseif ($script === 'hindi' || str_starts_with($locale, 'hi')) {
        $font = 'Noto+Sans+Devanagari:wght@400;600;700';
        $css_var = "'Noto Sans Devanagari'";
    } elseif ($script === 'telugu' || str_starts_with($locale, 'te')) {
        $font = 'Noto+Sans+Telugu:wght@400;600;700';
        $css_var = "'Noto Sans Telugu'";
    } else {
        $font = 'Noto+Sans:wght@400;600;700';
        $css_var = "'Noto Sans'";
    }

    // Store css_var for inline style
    wp_add_inline_style('arthanama-style', ":root { --regional-font: {$css_var}; }");

    return "https://fonts.googleapis.com/css2?family={$font}&family=DM+Mono:wght@400;500&display=swap";
}

/* ─── ENQUEUE ─────────────────────────────────────────────────────────────── */
function arthanama_enqueue() {
    // Google Fonts
    $font_url = arthanama_get_font_url();
    wp_enqueue_style('arthanama-fonts', $font_url, [], null);

    // Main stylesheet
    wp_enqueue_style('arthanama-style', get_stylesheet_uri(), ['arthanama-fonts'], '1.0.0');

    // Theme script
    wp_enqueue_script('arthanama-script',
        get_template_directory_uri() . '/assets/js/arthanama.js',
        [], '1.0.0', true
    );

    // Pass data to JS
    wp_localize_script('arthanama-script', 'arthanama', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'siteUrl' => get_site_url(),
    ]);

    // Comments
    if (is_singular() && comments_open()) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'arthanama_enqueue');

/* ─── WIDGETS ─────────────────────────────────────────────────────────────── */
function arthanama_widgets_init() {
    $shared = [
        'before_title'  => '<h3 class="widget-title"><svg width="13" height="13"><use href="#icon-star"/></svg>',
        'after_title'   => '</h3><div class="widget-body">',
        'before_widget' => '<section class="widget %2$s">',
        'after_widget'  => '</div></section>',
    ];

    register_sidebar(array_merge($shared, [
        'name'        => __('Sidebar', 'arthanama'),
        'id'          => 'sidebar-main',
        'description' => __('Main sidebar widgets', 'arthanama'),
    ]));
    register_sidebar(array_merge($shared, [
        'name'        => __('Footer Column 3', 'arthanama'),
        'id'          => 'footer-col3',
        'description' => __('Footer third column', 'arthanama'),
    ]));
}
add_action('widgets_init', 'arthanama_widgets_init');

/* ─── HELPER: READING TIME ────────────────────────────────────────────────── */
function arthanama_reading_time(int $post_id = 0): string {
    $post_id = $post_id ?: get_the_ID();
    $content = get_post_field('post_content', $post_id);
    $content = wp_strip_all_tags($content);

    // Estimate: ~200 words/min for Indic, ~250 for Latin
    $script = get_option('arthanama_language_script', '');
    $wpm    = in_array($script, ['malayalam','hindi','telugu']) ? 180 : 230;

    // Count words (works roughly for all scripts)
    $word_count = str_word_count($content) ?: (mb_strlen($content) / 5);
    $minutes    = max(1, (int) ceil($word_count / $wpm));

    return sprintf(_n('%d min read', '%d min read', $minutes, 'arthanama'), $minutes);
}

/* ─── HELPER: POST CATEGORY BADGE ────────────────────────────────────────── */
function arthanama_cat_badge(int $post_id = 0, string $extra_class = ''): void {
    $cats = get_the_category($post_id ?: get_the_ID());
    if (empty($cats)) return;

    $cat  = $cats[0];
    $name = esc_html($cat->name);
    $slug = sanitize_html_class($cat->slug);
    $link = esc_url(get_category_link($cat->term_id));
    $cls  = trim("cat-badge {$slug} {$extra_class}");

    echo "<a href=\"{$link}\" class=\"{$cls}\" rel=\"category\">{$name}</a>";
}

/* ─── HELPER: SVG ICON ────────────────────────────────────────────────────── */
function arthanama_icon(string $name, string $cls = ''): string {
    $cls_attr = $cls ? " class=\"{$cls}\"" : '';
    return "<svg{$cls_attr} aria-hidden=\"true\"><use href=\"#icon-{$name}\"/></svg>";
}

/* ─── HELPER: PLACEHOLDER IMAGE ──────────────────────────────────────────── */
function arthanama_placeholder(string $size = 'arthanama-card'): string {
    return '<div class="post-card-image-placeholder">'
         . arthanama_icon('newspaper')
         . '</div>';
}

/* ─── MARKET DATA (static, can be replaced by API) ───────────────────────── */
function arthanama_market_items(): array {
    return [
        ['label' => 'NIFTY 50',  'price' => '22,450.10', 'change' => '+1.2%',  'up' => true],
        ['label' => 'SENSEX',    'price' => '73,888.70', 'change' => '+0.95%', 'up' => true],
        ['label' => 'BANK NIFTY','price' => '48,120.35', 'change' => '-0.3%',  'up' => false],
        ['label' => 'USD/INR',   'price' => '83.45',     'change' => '-0.1%',  'up' => false],
        ['label' => 'GOLD',      'price' => '₹71,450',   'change' => '+0.5%',  'up' => true],
        ['label' => 'CRUDE OIL', 'price' => '$85.60',    'change' => '+1.1%',  'up' => true],
        ['label' => 'IT INDEX',  'price' => '38,240.50', 'change' => '+2.3%',  'up' => true],
        ['label' => 'NIFTY 50',  'price' => '22,450.10', 'change' => '+1.2%',  'up' => true],
        ['label' => 'SENSEX',    'price' => '73,888.70', 'change' => '+0.95%', 'up' => true],
        ['label' => 'BANK NIFTY','price' => '48,120.35', 'change' => '-0.3%',  'up' => false],
        ['label' => 'USD/INR',   'price' => '83.45',     'change' => '-0.1%',  'up' => false],
        ['label' => 'GOLD',      'price' => '₹71,450',   'change' => '+0.5%',  'up' => true],
    ];
}

/* ─── BREAKING NEWS POSTS ────────────────────────────────────────────────── */
function arthanama_breaking_posts(): array {
    $posts = get_posts([
        'numberposts' => 8,
        'post_status' => 'publish',
        'category_name' => 'breaking',
        'orderby' => 'date',
        'order' => 'DESC',
    ]);
    if (empty($posts)) {
        // Fallback: latest 8 posts
        $posts = get_posts(['numberposts' => 8, 'post_status' => 'publish']);
    }
    return $posts;
}

/* ─── DISABLE GUTENBERG COLORS (avoid conflicts) ──────────────────────────── */
add_theme_support('disable-custom-colors');
add_theme_support('editor-color-palette', []);

/* ─── EXCERPT LENGTH ─────────────────────────────────────────────────────── */
add_filter('excerpt_length', fn() => 25, 999);
add_filter('excerpt_more',   fn() => '…');

/* ─── BODY CLASS ──────────────────────────────────────────────────────────── */
function arthanama_body_class(array $classes): array {
    $script = get_option('arthanama_language_script', 'default');
    $classes[] = 'script-' . sanitize_html_class($script);
    return $classes;
}
add_filter('body_class', 'arthanama_body_class');

/* ─── LANG ATTRIBUTE ON HTML ─────────────────────────────────────────────── */
function arthanama_language_attributes(string $output): string {
    $script = get_option('arthanama_language_script', '');
    $map = ['malayalam' => 'ml', 'hindi' => 'hi', 'telugu' => 'te'];
    if (isset($map[$script])) {
        $output = preg_replace('/lang="[^"]*"/', 'lang="' . $map[$script] . '"', $output);
    }
    return $output;
}
add_filter('language_attributes', 'arthanama_language_attributes');
