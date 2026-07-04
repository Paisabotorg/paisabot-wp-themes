<?php
/**
 * PaisaBot — Force the paisabot theme on all sites.
 *
 * Uploaded to wp-content/mu-plugins/ by the deploy pipeline.
 * Uses filters so it works regardless of what the WP database
 * says is the active theme — no DB write needed, no race condition.
 */
defined('ABSPATH') || exit;

add_filter('template',   fn() => 'paisabot');
add_filter('stylesheet', fn() => 'paisabot');

// Force the site tagline to "Economic Intelligence" on every site (incl. QA).
// Uses pre_option_* so it overrides whatever is stored in Settings → General —
// no DB write, consistent branding everywhere the mu-plugin is deployed.
add_filter('pre_option_blogdescription', fn() => 'Economic Intelligence');

// Disable comments and pings site-wide
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open',    '__return_false', 20, 2);
add_filter('comments_array', '__return_empty_array', 10, 2);
add_action('init', function () {
    remove_post_type_support('post', 'comments');
    remove_post_type_support('post', 'trackbacks');
    remove_post_type_support('page', 'comments');
    remove_post_type_support('page', 'trackbacks');
});
// ── One-time menu bootstrap ──────────────────────────────────────────────────
// v3: rebuilds menu on all sites to add "Home" as the first item.
add_action('init', function () {
    if (get_option('pb_menus_initialized_v3')) return;

    $menu_name = 'Primary Navigation';
    $existing  = wp_get_nav_menu_object($menu_name);
    if ($existing) {
        wp_delete_nav_menu($existing->term_id);
    }
    $menu_id = wp_create_nav_menu($menu_name);
    if (is_wp_error($menu_id)) {
        return;
    }

    // Home — custom link so it always points to this site's own homepage
    wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-type'   => 'custom',
        'menu-item-url'    => home_url('/'),
        'menu-item-title'  => 'Home',
        'menu-item-status' => 'publish',
        'menu-item-position' => 1,
    ]);

    $nav_items = [
        'banking'        => 'Banking',
        'economy'        => 'Economy',
        'foreign-policy' => 'Foreign Policy',
        'global'         => 'Global',
        'markets'        => 'Markets',
        'opinion'        => 'Opinion',
        'policy'         => 'Policy',
        'technology'     => 'Technology',
    ];

    $order = 2;
    foreach ($nav_items as $slug => $label) {
        $cat = get_category_by_slug($slug);
        if (!$cat) {
            $cid = wp_create_category($label);
            $cat = get_category($cid);
        }
        if ($cat && !is_wp_error($cat)) {
            wp_update_nav_menu_item($menu_id, 0, [
                'menu-item-type'      => 'taxonomy',
                'menu-item-object'    => 'category',
                'menu-item-object-id' => $cat->term_id,
                'menu-item-position'  => $order++,
                'menu-item-status'    => 'publish',
                'menu-item-title'     => $label,
            ]);
        }
    }

    $locations            = get_theme_mod('nav_menu_locations', []);
    $locations['primary'] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations);

    update_option('show_on_front', 'posts');
    update_option('pb_menus_initialized_v3', true);
}, 20);

// ── Bridge: rewrite stale auth URLs in cached header.php output ──────────────
// header.php is stuck behind PHP OPcache on some sites (FTP upload doesn't
// invalidate compiled bytecode). The real fix is in header.php; this is a
// bridge until OPcache naturally recompiles it. Safe to remove once confirmed
// every site serves the corrected /login and /logout links directly.
add_action('template_redirect', function () {
    ob_start(function ($html) {
        return str_replace(
            ['auth.paisabot.com/auth/google', 'auth.paisabot.com/auth/logout'],
            ['auth.paisabot.com/login',        'auth.paisabot.com/logout'],
            $html
        );
    });
}, 1);

// ── One-time cleanup: trash the /subscribe/ page if it exists ────────────────
add_action('init', function () {
    if (get_option('pb_subscribe_page_removed')) return;
    $page = get_page_by_path('subscribe');
    if ($page) {
        wp_trash_post($page->ID);
    }
    update_option('pb_subscribe_page_removed', true);
}, 25);

// Remove default widgets that pollute sidebar-1
add_action('widgets_init', function () {
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Search');
}, 11);

// Inline critical CSS fixes — bypasses OPcache/CDN caching on style.css
add_action('wp_head', function () {
    echo '<style id="pb-critical-fixes">'
        . '.site-header .hamburger{display:none!important}'
        . '@media(max-width:768px){.site-header .hamburger{display:flex!important;flex-shrink:0!important}}'
        . '.art-head-inner{max-width:none!important;margin:0!important}'
        . '.art-body{margin-left:0!important;margin-right:0!important}'
        . '.lb-content{padding-left:24px!important;padding-right:24px!important}'
        // The ad <aside> also carries class "lb-sponsor"; the sponsor-name rule
        // leaked display:inline-block onto it and broke full-bleed. Force block.
        . '.leaderboard-ad.lb-sponsor,.leaderboard-ad.lb-house{display:block!important}'
        . '.leaderboard-ad .ad-tag{right:max(24px,calc(50% - var(--max-w)/2 + 24px))!important}'
        . '@media(max-width:768px){.leaderboard-ad .lb-content{grid-template-columns:1fr!important;gap:16px!important}.leaderboard-ad .lb-cta{width:100%!important;justify-content:center!important}.leaderboard-ad .ad-tag{position:static!important;right:auto!important;top:auto!important;margin:0 20px 8px!important;text-align:right!important}}'
        . '</style>';
}, 99);
