<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="theme-color" content="#0F1E33">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php include get_template_directory() . '/assets/svg/sprite.php'; ?>

<?php if (is_single()): ?>
<div class="reading-progress" id="rp" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
<?php endif; ?>

<!-- ▸ TOP BAR -->
<div class="topbar">
  <div class="wrap">
    <div class="topbar-left">
      <span class="topbar-date">
        <?php echo aiv_icon('calendar'); ?>
        <?php echo wp_date('l, d M Y'); ?>
      </span>
      <span class="topbar-edition">
        <?php echo esc_html(ucfirst(get_option('aivartha_script','Economic'))); ?> Edition
      </span>
    </div>
    <div class="topbar-right">
      <div class="topbar-langs">
        <?php
        $current = get_option('aivartha_script', 'latin');
        $langs   = ['latin'=>'EN','malayalam'=>'ML','hindi'=>'HI','telugu'=>'TE'];
        foreach ($langs as $k => $label):
        ?>
          <a class="lang-pill <?php echo $k === $current ? 'active' : ''; ?>"
             href="<?php echo esc_url(add_query_arg('aiv_lang', $k)); ?>"><?php echo $label; ?></a>
        <?php endforeach; ?>
      </div>
      <div class="topbar-social">
        <a href="#" aria-label="Facebook"><?php echo aiv_icon('i-fb'); ?></a>
        <a href="#" aria-label="Twitter"><?php echo aiv_icon('i-tw'); ?></a>
        <a href="#" aria-label="WhatsApp"><?php echo aiv_icon('i-wa'); ?></a>
        <a href="<?php bloginfo('rss2_url'); ?>" aria-label="RSS"><?php echo aiv_icon('i-rss'); ?></a>
      </div>
    </div>
  </div>
</div>

<!-- ▸ MARKET TICKER -->
<div class="market-strip" aria-label="Live market data" role="region">
  <div class="market-label">
    <?php echo aiv_icon('i-trend-up'); ?> Markets
  </div>
  <div class="ticker-track">
    <div class="ticker-inner">
      <?php foreach (array_merge(aiv_market_items(), aiv_market_items()) as $m): ?>
      <div class="ticker-item">
        <span class="t-name"><?php echo esc_html($m['n']); ?></span>
        <span class="t-price"><?php echo esc_html($m['p']); ?></span>
        <span class="<?php echo $m['up'] ? 't-up' : 't-dn'; ?>">
          <?php echo aiv_icon($m['up'] ? 'i-trend-up' : 'i-trend-dn'); ?>
          <?php echo esc_html($m['c']); ?>
        </span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- ▸ SITE HEADER -->
<header class="site-header" id="site-header">
  <div class="wrap">
    <div class="header-inner">

      <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="PaisaBot home">
        <span class="logo-text">
          <span class="logo-name"><span class="paisa-pill">Paisa</span><em>Bot</em></span>
          <span class="logo-sub"><?php echo esc_html(get_bloginfo('description') ?: 'Economic Intelligence'); ?></span>
        </span>
      </a>

      <nav class="primary-nav" id="primary-nav" aria-label="Primary navigation">
        <?php wp_nav_menu([
          'theme_location' => 'primary',
          'menu_class'     => 'nav-list',
          'container'      => false,
          'fallback_cb'    => function() {
            // Default sections — slug-based lookup works on all language sites
            $defaults = [
                'home'           => ['label'=>'Home',           'url'=>home_url('/')],
                'markets'        => ['label'=>'Markets',        'url'=>'https://markets.paisabot.com', 'external'=>true],
                'stocks'         => ['label'=>'Stocks',         'url'=>'https://analyse.paisabot.com', 'external'=>true],
                'policy'         => ['label'=>'Policy',         'url'=>aiv_cat_url('policy')],
                'banking'        => ['label'=>'Banking',        'url'=>aiv_cat_url('banking')],
                'economy'        => ['label'=>'Economy',        'url'=>aiv_cat_url('economy')],
                'global'         => ['label'=>'Global',         'url'=>aiv_cat_url('global')],
                'foreign-policy' => ['label'=>'Foreign Policy', 'url'=>aiv_cat_url('foreign-policy')],
                'technology'     => ['label'=>'Technology',     'url'=>aiv_cat_url('technology')],
                'opinion'        => ['label'=>'Opinion',        'url'=>aiv_cat_url('opinion')],
            ];
            echo '<ul class="nav-list">';
            foreach ($defaults as $slug => $item) {
                $url      = $item['url'] ?: home_url('/category/' . $slug);
                $active   = (is_home() && $slug === 'home') || (is_category($slug));
                $ext_attr = !empty($item['external']) ? ' target="_blank" rel="noopener noreferrer"' : '';
                echo '<li><a href="' . esc_url($url) . '"' . ($active ? ' class="is-active"' : '') . $ext_attr . '>' . esc_html($item['label']) . '</a></li>';
            }
            echo '</ul>';
          },
        ]); ?>
      </nav>

      <div class="header-actions">
        <button class="icon-btn" id="search-btn" aria-label="Search" aria-expanded="false">
          <?php echo aiv_icon('search'); ?>
        </button>
        <a class="btn-subscribe" href="<?php echo esc_url(home_url('/subscribe')); ?>">
          <?php echo aiv_icon('i-star'); ?> Subscribe
        </a>
        <button class="icon-btn hamburger" id="hamburger" aria-label="Menu" aria-expanded="false" aria-controls="primary-nav">
          <?php echo aiv_icon('menu'); ?>
        </button>
      </div>

    </div>
  </div>
</header>

<!-- ▸ BREAKING TICKER -->
<?php $bp = aiv_breaking(); if ($bp): ?>
<div class="breaking-bar" aria-label="Breaking news" role="region">
  <div class="breaking-pill"><?php echo aiv_icon('bolt'); ?> Breaking</div>
  <div class="breaking-scroll">
    <div class="breaking-inner">
      <?php foreach (array_merge($bp, $bp) as $b): ?>
      <div class="b-item">
        <a href="<?php echo esc_url(get_permalink($b->ID)); ?>">
          <?php echo esc_html(get_the_title($b->ID)); ?>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- ▸ DATELINE STRIP (broadsheet vol. + edition + indices) -->
<?php if (is_home() || is_front_page()): get_template_part('template-parts/dateline'); endif; ?>

<!-- ▸ SEARCH OVERLAY -->
<div class="search-overlay" id="search-overlay" role="dialog" aria-modal="true" aria-label="Search">
  <div class="search-box">
    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
      <input type="search" name="s" placeholder="<?php esc_attr_e('Search articles…','aivartha'); ?>" value="<?php echo get_search_query(); ?>" autocomplete="off">
      <button type="submit"><?php echo aiv_icon('search'); ?> Search</button>
    </form>
  </div>
</div>

<main class="site-body">
  <div class="wrap">
