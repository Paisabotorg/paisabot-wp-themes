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

      <!-- Hamburger — leftmost on mobile, hidden on desktop -->
      <button class="icon-btn hamburger" id="hamburger" aria-label="Open menu" aria-expanded="false" aria-controls="primary-nav">
        <?php echo aiv_icon('menu'); ?>
      </button>

      <!-- Logo — desktop: natural flow; mobile: centered via flex -->
      <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="PaisaBot home">
        <span class="logo-text">
          <span class="logo-name"><span class="paisa-pill">Paisa</span><em>Bot</em></span>
          <span class="logo-sub"><?php echo esc_html(get_bloginfo('description') ?: 'Economic Intelligence'); ?></span>
        </span>
      </a>

      <!-- Primary nav — desktop only.
           Hardcoded editorial order, identical on every site (and matching
           markets/analyse). WP-assigned menus are deliberately ignored: the
           per-site menus had drifted to an alphabetical list without Stocks. -->
      <nav class="primary-nav" id="primary-nav" aria-label="Primary navigation">
        <?php
          $aiv_nav = [
            'home'           => ['label'=>'Home',           'url'=>home_url('/')],
            'indian-markets' => ['label'=>'Indian Markets', 'url'=>'https://markets.paisabot.com/?view=indian', 'external'=>true],
            'global-markets' => ['label'=>'Global Markets', 'url'=>'https://markets.paisabot.com/?view=global', 'external'=>true],
            'stocks'         => ['label'=>'Stocks',         'url'=>'https://analyse.paisabot.com', 'external'=>true],
            'news'           => ['label'=>'News',           'url'=>home_url('/?pb_view=news')],
            'technology'     => ['label'=>'Technology',     'url'=>aiv_cat_url('technology')],
            'opinion'        => ['label'=>'Opinion',        'url'=>aiv_cat_url('opinion')],
            'history'        => ['label'=>'History',        'url'=>aiv_cat_url('history')],
          ];
          echo '<ul class="nav-list">';
          foreach ($aiv_nav as $slug => $item) {
            $url      = $item['url'] ?: home_url('/category/' . $slug);
            $active   = (is_home() && $slug === 'home') || (is_category($slug));
            $ext_attr = !empty($item['external']) ? ' target="_blank" rel="noopener noreferrer"' : '';
            echo '<li><a href="' . esc_url($url) . '"' . ($active ? ' class="is-active"' : '') . $ext_attr . '>' . esc_html($item['label']) . '</a></li>';
          }
          echo '</ul>';
        ?>
      </nav>

      <!-- Right actions: Subscribe · Search · Profile -->
      <div class="header-actions">
        <a class="btn-subscribe" href="<?php echo esc_url(home_url('/subscribe')); ?>">
          <?php echo aiv_icon('i-star'); ?> <span class="btn-subscribe-label">Subscribe</span>
        </a>

        <button class="icon-btn" id="search-btn" aria-label="Search" aria-expanded="false">
          <?php echo aiv_icon('search'); ?>
        </button>

        <!-- Profile button + dropdown -->
        <div class="profile-wrap" id="profile-wrap">
          <button class="icon-btn profile-btn" id="profile-btn" aria-label="Account" aria-expanded="false" aria-haspopup="true">
            <span class="profile-avatar" id="profile-avatar" aria-hidden="true">
              <?php echo aiv_icon('user'); ?>
            </span>
          </button>

          <div class="profile-drop" id="profile-drop" role="dialog" aria-label="Account panel" hidden>
            <!-- Loading state -->
            <div class="pd-loading" id="pd-loading">
              <div class="pd-skel"></div>
              <div class="pd-skel pd-skel-sm"></div>
              <div class="pd-skel pd-skel-sm"></div>
            </div>
            <!-- Logged-in state (filled by JS) -->
            <div class="pd-user" id="pd-user" hidden>
              <div class="pd-head">
                <div class="pd-avatar-wrap">
                  <img class="pd-avatar-img" id="pd-avatar-img" src="" alt="" width="40" height="40" hidden>
                  <span class="pd-avatar-initials" id="pd-avatar-initials"></span>
                </div>
                <div class="pd-identity">
                  <span class="pd-name" id="pd-name"></span>
                  <span class="pd-email" id="pd-email"></span>
                  <span class="pd-phone" id="pd-phone" hidden></span>
                </div>
              </div>
              <div class="pd-status">
                <span class="pd-tier" id="pd-tier"></span>
                <span class="pd-expires" id="pd-expires"></span>
              </div>
              <nav class="pd-links">
                <a href="<?php echo esc_url(home_url('/account')); ?>" class="pd-link">
                  <?php echo aiv_icon('user'); ?> My Account
                </a>
                <a href="<?php echo esc_url(home_url('/subscribe')); ?>" class="pd-link pd-link-upgrade" id="pd-upgrade" hidden>
                  <?php echo aiv_icon('i-star'); ?> Upgrade to Pro
                </a>
                <a href="<?php echo esc_url(home_url('/account/settings')); ?>" class="pd-link">
                  <?php echo aiv_icon('i-arrow-r'); ?> Settings
                </a>
                <button class="pd-link pd-signout" id="pd-signout">
                  <?php echo aiv_icon('i-arrow-r'); ?> Sign out
                </button>
              </nav>
            </div>
            <!-- Logged-out state -->
            <div class="pd-guest" id="pd-guest" hidden>
              <p class="pd-guest-msg">Sign in to access your watchlist, newsletters, and premium articles.</p>
              <a href="https://auth.paisabot.com/login?return=<?php echo rawurlencode(home_url(add_query_arg([]))); ?>" class="pd-signin-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                Continue with Google
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</header>

<!-- ▸ MOBILE NAV DRAWER (slides in from left on hamburger click) -->
<div class="mobile-nav-overlay" id="mobile-nav-overlay" hidden aria-hidden="true"></div>
<div class="mobile-nav-drawer" id="mobile-nav-drawer" aria-label="Navigation menu" hidden>
  <div class="mnd-head">
    <a class="site-logo mnd-logo" href="<?php echo esc_url(home_url('/')); ?>">
      <span class="logo-name"><span class="paisa-pill">Paisa</span><em>Bot</em></span>
    </a>
    <button class="icon-btn mnd-close" id="mnd-close" aria-label="Close menu">
      <?php echo aiv_icon('i-close'); ?>
    </button>
  </div>
  <nav class="mnd-nav">
    <ul class="mnd-list">
      <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo aiv_icon('i-home'); ?> Home</a></li>
      <li><a href="https://markets.paisabot.com/?view=indian" target="_blank" rel="noopener noreferrer"><?php echo aiv_icon('i-trend-up'); ?> Indian Markets</a></li>
      <li><a href="https://markets.paisabot.com/?view=global" target="_blank" rel="noopener noreferrer"><?php echo aiv_icon('i-trend-up'); ?> Global Markets</a></li>
      <li><a href="https://analyse.paisabot.com" target="_blank" rel="noopener noreferrer"><?php echo aiv_icon('i-trend-up'); ?> Stocks</a></li>
      <li><a href="<?php echo esc_url(home_url('/?pb_view=news')); ?>"><?php echo aiv_icon('i-arrow-r'); ?> News</a></li>
      <li><a href="<?php echo esc_url(aiv_cat_url('technology')); ?>"><?php echo aiv_icon('i-arrow-r'); ?> Technology</a></li>
      <li><a href="<?php echo esc_url(aiv_cat_url('opinion')); ?>"><?php echo aiv_icon('i-arrow-r'); ?> Opinion</a></li>
      <li><a href="<?php echo esc_url(aiv_cat_url('history')); ?>"><?php echo aiv_icon('i-arrow-r'); ?> History</a></li>
    </ul>
  </nav>
  <div class="mnd-footer">
    <a class="btn-subscribe" href="<?php echo esc_url(home_url('/subscribe')); ?>">
      <?php echo aiv_icon('i-star'); ?> Subscribe
    </a>
  </div>
</div>

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

