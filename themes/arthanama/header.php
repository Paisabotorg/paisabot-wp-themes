<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#0C1E35">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php include get_template_directory() . '/assets/svg/icons.php'; ?>

<!-- Reading Progress (single posts only) -->
<?php if (is_single()): ?>
<div class="reading-progress" id="reading-progress" role="progressbar" aria-label="<?php esc_attr_e('Reading progress','arthanama'); ?>"></div>
<?php endif; ?>

<!-- ── TOP BAR ──────────────────────────────────────────────────────────── -->
<div class="topbar">
  <div class="container">
    <span class="topbar-date">
      <?php echo arthanama_icon('calendar'); ?>
      <?php echo wp_date(get_option('date_format')); ?>
    </span>
    <div class="topbar-social">
      <a href="#" aria-label="Facebook"><?php echo arthanama_icon('facebook'); ?></a>
      <a href="#" aria-label="Twitter"><?php echo arthanama_icon('twitter'); ?></a>
      <a href="#" aria-label="WhatsApp"><?php echo arthanama_icon('whatsapp'); ?></a>
      <?php if (get_option('arthanama_rss', true)): ?>
      <a href="<?php bloginfo('rss2_url'); ?>" aria-label="RSS"><?php echo arthanama_icon('rss'); ?></a>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- ── SITE HEADER ──────────────────────────────────────────────────────── -->
<header class="site-header" id="site-header">
  <div class="container">
    <div class="header-inner">

      <!-- Logo -->
      <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="<?php bloginfo('name'); ?>">
        <!-- Logo mark: bar chart SVG -->
        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" aria-hidden="true">
          <rect width="36" height="36" rx="8" fill="#0C1E35"/>
          <rect x="7"  y="20" width="5" height="10" rx="1.5" fill="#C4982A"/>
          <rect x="15" y="13" width="5" height="17" rx="1.5" fill="#C4982A" opacity=".8"/>
          <rect x="23" y="7"  width="5" height="23" rx="1.5" fill="#C4982A" opacity=".6"/>
          <path d="M8 16 L18 10 L28 6" stroke="white" stroke-width="1.5" stroke-linecap="round" opacity=".4"/>
        </svg>
        <span class="logo-text">
          <span class="logo-name">Artha<span>Nama</span></span>
          <span class="logo-tagline"><?php bloginfo('description') ?: _e('Economic News','arthanama'); ?></span>
        </span>
      </a>

      <!-- Primary Nav -->
      <nav class="primary-nav" id="primary-nav" aria-label="<?php esc_attr_e('Primary','arthanama'); ?>">
        <?php wp_nav_menu([
          'theme_location' => 'primary',
          'menu_class'     => 'nav-list',
          'container'      => false,
          'fallback_cb'    => function() {
            echo '<ul class="nav-list">';
            $cats = get_categories(['number' => 8, 'hide_empty' => true]);
            echo '<li><a href="' . esc_url(home_url('/')) . '">' . arthanama_icon('home') . __('Home','arthanama') . '</a></li>';
            echo '<li><a href="https://markets.paisabot.com" target="_blank" rel="noopener noreferrer">' . __('Markets','arthanama') . '</a></li>';
            echo '<li><a href="https://analyse.paisabot.com" target="_blank" rel="noopener noreferrer">' . __('Stocks','arthanama') . '</a></li>';
            foreach ($cats as $cat) {
              if ($cat->slug === 'markets') continue; // avoid duplicate
              echo '<li><a href="' . esc_url(get_category_link($cat->term_id)) . '">' . esc_html($cat->name) . '</a></li>';
            }
            echo '</ul>';
          },
        ]); ?>
      </nav>

      <!-- Actions -->
      <div class="header-actions">
        <button class="btn-icon" id="search-toggle" aria-label="<?php esc_attr_e('Search','arthanama'); ?>" aria-expanded="false">
          <?php echo arthanama_icon('search'); ?>
        </button>
        <button class="btn-icon menu-toggle" id="menu-toggle" aria-label="<?php esc_attr_e('Menu','arthanama'); ?>" aria-expanded="false" aria-controls="primary-nav">
          <?php echo arthanama_icon('menu'); ?>
        </button>
      </div>

    </div><!-- .header-inner -->
  </div>
</header>

<!-- ── MARKET STRIP ─────────────────────────────────────────────────────── -->
<div class="market-strip" role="region" aria-label="<?php esc_attr_e('Market Data','arthanama'); ?>">
  <div class="market-strip-label">
    <?php echo arthanama_icon('trending-up'); ?>
    <?php esc_html_e('Markets','arthanama'); ?>
  </div>
  <div class="market-ticker-wrap">
    <div class="market-ticker" aria-hidden="true">
      <?php foreach (array_merge(arthanama_market_items(), arthanama_market_items()) as $item): ?>
      <div class="market-item">
        <span class="label"><?php echo esc_html($item['label']); ?></span>
        <span class="price"><?php echo esc_html($item['price']); ?></span>
        <span class="<?php echo $item['up'] ? 'market-up' : 'market-down'; ?>">
          <?php echo arthanama_icon($item['up'] ? 'trending-up' : 'trending-down'); ?>
          <?php echo esc_html($item['change']); ?>
        </span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- ── BREAKING NEWS TICKER ──────────────────────────────────────────────── -->
<?php $breaking = arthanama_breaking_posts(); if ($breaking): ?>
<div class="breaking-bar" role="marquee" aria-label="<?php esc_attr_e('Breaking News','arthanama'); ?>">
  <div class="breaking-label">
    <?php echo arthanama_icon('breaking'); ?>
    <?php esc_html_e('Breaking','arthanama'); ?>
  </div>
  <div class="breaking-ticker-wrap">
    <div class="breaking-ticker">
      <?php foreach (array_merge($breaking, $breaking) as $bp): ?>
      <div class="breaking-item">
        <a href="<?php echo esc_url(get_permalink($bp->ID)); ?>">
          <?php echo esc_html(get_the_title($bp->ID)); ?>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- ── SEARCH OVERLAY ────────────────────────────────────────────────────── -->
<div class="search-overlay" id="search-overlay" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Search','arthanama'); ?>">
  <?php get_search_form(); ?>
</div>

<!-- ── MAIN CONTENT ──────────────────────────────────────────────────────── -->
<main id="main" class="site-main">
