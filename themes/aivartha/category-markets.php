<?php
/**
 * Template: Category — Markets Dashboard
 * Used automatically by WordPress for /category/markets/
 */
defined('ABSPATH') || exit;

/* ── Redirect to standalone markets site ────────────────────────── */
$_aiv_site = get_bloginfo('url');
$_aiv_lang = 'en';
if (strpos($_aiv_site, 'hi.paisabot.com')  !== false) $_aiv_lang = 'hi';
elseif (strpos($_aiv_site, 'ml.paisabot.com')  !== false) $_aiv_lang = 'ml';
elseif (strpos($_aiv_site, 'tel.paisabot.com') !== false) $_aiv_lang = 'tel';

wp_redirect('https://markets.paisabot.com' . ($_aiv_lang !== 'en' ? '?from=' . $_aiv_lang : ''), 302);
exit;

/* ── Index cards ────────────────────────────────────────────────── */
$indices = apply_filters('aiv_mktd_indices', [
    ['name' => 'NIFTY 50',   'value' => '22,450.20', 'abs' => '+265.40', 'pct' => '+1.20%', 'up' => true],
    ['name' => 'SENSEX',     'value' => '73,888.45', 'abs' => '+695.20', 'pct' => '+0.95%', 'up' => true],
    ['name' => 'BANK NIFTY', 'value' => '48,120.10', 'abs' => '−148.90', 'pct' => '−0.31%', 'up' => false],
    ['name' => 'USD/INR',    'value' => '83.45',     'abs' => '−0.10',   'pct' => '−0.12%', 'up' => false],
]);

/* ── Constituents ───────────────────────────────────────────────── */
$mkt_rows = apply_filters('aiv_mktd_rows', [
    ['name' => 'Reliance Industries', 'sym' => 'RELIANCE',   'price' => '2,940.55', 'chg' => '+1.42%', 'abs' => '+41.20', 'vol' => '8.4M', 'up' => true],
    ['name' => 'TCS',                 'sym' => 'TCS',        'price' => '4,210.80', 'chg' => '+2.18%', 'abs' => '+89.85', 'vol' => '3.2M', 'up' => true],
    ['name' => 'HDFC Bank',           'sym' => 'HDFCBANK',   'price' => '1,712.45', 'chg' => '+1.31%', 'abs' => '+22.10', 'vol' => '6.1M', 'up' => true],
    ['name' => 'Infosys',             'sym' => 'INFY',       'price' => '1,548.20', 'chg' => '+2.05%', 'abs' => '+29.80', 'vol' => '2.8M', 'up' => true],
    ['name' => 'ICICI Bank',          'sym' => 'ICICIBANK',  'price' => '1,128.60', 'chg' => '+0.88%', 'abs' => '+9.85',  'vol' => '5.4M', 'up' => true],
    ['name' => 'L&T',                 'sym' => 'LT',         'price' => '3,724.40', 'chg' => '+2.65%', 'abs' => '+96.20', 'vol' => '1.4M', 'up' => true],
    ['name' => 'HCL Technologies',    'sym' => 'HCLTECH',    'price' => '1,624.80', 'chg' => '+2.05%', 'abs' => '+32.65', 'vol' => '1.9M', 'up' => true],
    ['name' => 'Bharti Airtel',       'sym' => 'BHARTIARTL', 'price' => '1,688.20', 'chg' => '+2.10%', 'abs' => '+34.70', 'vol' => '2.2M', 'up' => true],
    ['name' => 'Wipro',               'sym' => 'WIPRO',      'price' => '512.40',   'chg' => '+0.65%', 'abs' => '+3.30',  'vol' => '4.1M', 'up' => true],
    ['name' => 'ITC',                 'sym' => 'ITC',        'price' => '456.30',   'chg' => '+0.42%', 'abs' => '+1.90',  'vol' => '7.2M', 'up' => true],
    ['name' => 'Axis Bank',           'sym' => 'AXISBANK',   'price' => '1,204.30', 'chg' => '−0.21%', 'abs' => '−2.55',  'vol' => '3.8M', 'up' => false],
    ['name' => 'Bajaj Finance',       'sym' => 'BAJFINANCE',  'price' => '7,320.00', 'chg' => '−0.45%', 'abs' => '−33.10', 'vol' => '1.1M', 'up' => false],
    ['name' => 'Sun Pharma',          'sym' => 'SUNPHARMA',  'price' => '1,582.10', 'chg' => '−0.52%', 'abs' => '−8.30',  'vol' => '2.0M', 'up' => false],
    ['name' => 'Nestle India',        'sym' => 'NESTLEIND',  'price' => '2,340.50', 'chg' => '−0.61%', 'abs' => '−14.40', 'vol' => '0.4M', 'up' => false],
    ['name' => 'HDFC Life',           'sym' => 'HDFCLIFE',   'price' => '628.40',   'chg' => '−0.38%', 'abs' => '−2.40',  'vol' => '1.6M', 'up' => false],
]);

/* ── Gainers / Losers ───────────────────────────────────────────── */
$gainers = apply_filters('aiv_mktd_gainers', [
    ['name' => 'L&T',           'sym' => 'LT',         'price' => '3,724.40', 'chg' => '+2.65%'],
    ['name' => 'TCS',           'sym' => 'TCS',        'price' => '4,210.80', 'chg' => '+2.18%'],
    ['name' => 'Tech Mahindra', 'sym' => 'TECHM',      'price' => '1,548.20', 'chg' => '+2.05%'],
    ['name' => 'Bharti Airtel', 'sym' => 'BHARTIARTL', 'price' => '1,688.20', 'chg' => '+2.10%'],
    ['name' => 'Infosys',       'sym' => 'INFY',       'price' => '1,542.30', 'chg' => '+1.23%'],
]);
$losers = apply_filters('aiv_mktd_losers', [
    ['name' => 'Nestle India',  'sym' => 'NESTLEIND',  'price' => '2,340.50', 'chg' => '−0.61%'],
    ['name' => 'Sun Pharma',    'sym' => 'SUNPHARMA',  'price' => '1,582.10', 'chg' => '−0.52%'],
    ['name' => 'Bajaj Finance', 'sym' => 'BAJFINANCE',  'price' => '7,320.00', 'chg' => '−0.45%'],
    ['name' => 'HDFC Life',     'sym' => 'HDFCLIFE',   'price' => '628.40',   'chg' => '−0.38%'],
    ['name' => 'Axis Bank',     'sym' => 'AXISBANK',   'price' => '1,204.30', 'chg' => '−0.21%'],
]);

/* ── Sparkline paths ────────────────────────────────────────────── */
function aiv_spark_stroke(bool $up): string {
    return $up
        ? 'M0,24 C8,20 16,18 24,14 C32,10 38,13 46,8 C54,4 60,3 70,2'
        : 'M0,4  C8,8  16,10 24,14 C32,18 38,15 46,20 C54,24 60,25 70,26';
}
function aiv_spark_area(bool $up): string {
    return aiv_spark_stroke($up) . ($up ? ' L70,28 L0,28 Z' : ' L70,28 L0,28 Z');
}

get_header();
?>

<div class="mktd-page">

  <!-- ══ DARK HERO: kicker + title + desc + index cards ════════════ -->
  <div class="mktd-dark">
    <div class="mktd-wrap">

      <!-- Hero text row -->
      <div class="mktd-hero-row">
        <div class="mktd-hero-text">
          <p class="mktd-kicker">Live &middot; Equity</p>
          <h1 class="mktd-hero-h1">Markets Dashboard</h1>
          <p class="mktd-hero-desc">Real-time Indian equity, commodities, and currency markets — with key indices and movers updated every 15 seconds during trading hours.</p>
        </div>
        <div class="mktd-open-status">
          <span class="mktd-open-dot"></span>
          <span>Markets open &middot; <span class="mktd-clock" id="mktd-clock"><?php echo esc_html(wp_date('H:i:s')); ?> IST</span> &middot; Closes 15:30</span>
        </div>
      </div>

      <!-- Index cards -->
      <div class="mktd-cards-grid">
        <?php foreach ($indices as $ci => $idx):
          $color = $idx['up'] ? '#1A7A4A' : '#C0392B';
          $gid   = 'mktd-g' . $ci;
        ?>
        <div class="mktd-idx-card">
          <div class="mktd-idx-top">
            <span class="mktd-idx-name"><?php echo esc_html($idx['name']); ?></span>
            <span class="mktd-idx-value"><?php echo esc_html($idx['value']); ?></span>
            <span class="mktd-idx-chg <?php echo $idx['up'] ? 'is-up' : 'is-dn'; ?>">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <?php if ($idx['up']): ?>
                  <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>
                <?php else: ?>
                  <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/>
                <?php endif; ?>
              </svg>
              <?php echo esc_html($idx['abs']); ?>
              <span class="mktd-idx-pct">(<?php echo esc_html($idx['pct']); ?>)</span>
            </span>
          </div>
          <div class="mktd-idx-spark-wrap">
            <svg class="mktd-idx-spark" viewBox="0 0 70 28" preserveAspectRatio="none" aria-hidden="true">
              <defs>
                <linearGradient id="<?php echo esc_attr($gid); ?>" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stop-color="<?php echo esc_attr($color); ?>" stop-opacity=".28"/>
                  <stop offset="100%" stop-color="<?php echo esc_attr($color); ?>" stop-opacity="0"/>
                </linearGradient>
              </defs>
              <path d="<?php echo esc_attr(aiv_spark_area($idx['up'])); ?>" fill="url(#<?php echo esc_attr($gid); ?>)"/>
              <path d="<?php echo esc_attr(aiv_spark_stroke($idx['up'])); ?>" fill="none" stroke="<?php echo esc_attr($color); ?>" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

    </div><!-- /.mktd-wrap -->
  </div><!-- /.mktd-dark -->

  <!-- ══ LIGHT BODY: table + sidebar ══════════════════════════════ -->
  <div class="mktd-light">
    <div class="mktd-wrap">
      <div class="mktd-layout">

        <!-- ▸ Constituents table -->
        <div class="mktd-panel">
          <div class="mktd-panel-hd">
            <div class="mktd-panel-title">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:var(--amber)">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>
              </svg>
              <span>
                <strong>Nifty 50</strong>
                <small>Constituents</small>
              </span>
            </div>
            <div class="mktd-tabs" role="tablist">
              <?php foreach (['all' => 'All', 'gainers' => 'Gainers', 'losers' => 'Losers', 'volume' => 'Volume'] as $fk => $fl): ?>
              <button class="mktd-tab<?php echo $fk === 'all' ? ' is-active' : ''; ?>"
                      data-f="<?php echo esc_attr($fk); ?>"
                      role="tab"
                      aria-selected="<?php echo $fk === 'all' ? 'true' : 'false'; ?>">
                <?php echo esc_html($fl); ?>
              </button>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="mktd-tbl-scroll">
            <table class="mktd-tbl">
              <thead>
                <tr>
                  <th class="mktd-th-l">Stock</th>
                  <th class="mktd-th-r">LTP (₹)</th>
                  <th class="mktd-th-r">CHG %</th>
                  <th class="mktd-th-r">CHG (₹)</th>
                  <th class="mktd-th-r">Vol</th>
                  <th class="mktd-th-r">Trend</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($mkt_rows as $r): ?>
                <tr class="mktd-tr" data-up="<?php echo $r['up'] ? '1' : '0'; ?>">
                  <td>
                    <div class="mktd-stock">
                      <span class="mktd-stock-n"><?php echo esc_html($r['name']); ?></span>
                      <span class="mktd-stock-s"><?php echo esc_html($r['sym']); ?></span>
                    </div>
                  </td>
                  <td class="mktd-td-r mktd-mono"><?php echo esc_html($r['price']); ?></td>
                  <td class="mktd-td-r mktd-mono <?php echo $r['up'] ? 'mktd-up' : 'mktd-dn'; ?>"><?php echo esc_html($r['chg']); ?></td>
                  <td class="mktd-td-r mktd-mono <?php echo $r['up'] ? 'mktd-up' : 'mktd-dn'; ?>"><?php echo esc_html($r['abs']); ?></td>
                  <td class="mktd-td-r mktd-mono"><?php echo esc_html($r['vol']); ?></td>
                  <td class="mktd-td-r">
                    <svg class="mktd-spark-sm" viewBox="0 0 70 28" preserveAspectRatio="none" aria-hidden="true">
                      <path d="<?php echo esc_attr(aiv_spark_stroke($r['up'])); ?>"
                            fill="none"
                            stroke="<?php echo $r['up'] ? '#1A7A4A' : '#C0392B'; ?>"
                            stroke-width="2" stroke-linecap="round"/>
                    </svg>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div><!-- /.mktd-panel -->

        <!-- ▸ Sidebar: gainers + losers -->
        <aside class="mktd-sidebar">

          <div class="mktd-movers">
            <div class="mktd-movers-hd mktd-movers-up">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>
              </svg>
              Top Gainers
            </div>
            <ul class="mktd-movers-list" role="list">
              <?php foreach ($gainers as $g): ?>
              <li class="mktd-mover">
                <div class="mktd-mover-l">
                  <span class="mktd-mover-name"><?php echo esc_html($g['name']); ?></span>
                  <span class="mktd-mover-sym"><?php echo esc_html($g['sym']); ?></span>
                </div>
                <div class="mktd-mover-r">
                  <span class="mktd-mover-price"><?php echo esc_html($g['price']); ?></span>
                  <span class="mktd-mover-chg mktd-up"><?php echo esc_html($g['chg']); ?></span>
                </div>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>

          <div class="mktd-movers">
            <div class="mktd-movers-hd mktd-movers-dn">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/>
              </svg>
              Top Losers
            </div>
            <ul class="mktd-movers-list" role="list">
              <?php foreach ($losers as $l): ?>
              <li class="mktd-mover">
                <div class="mktd-mover-l">
                  <span class="mktd-mover-name"><?php echo esc_html($l['name']); ?></span>
                  <span class="mktd-mover-sym"><?php echo esc_html($l['sym']); ?></span>
                </div>
                <div class="mktd-mover-r">
                  <span class="mktd-mover-price"><?php echo esc_html($l['price']); ?></span>
                  <span class="mktd-mover-chg mktd-dn"><?php echo esc_html($l['chg']); ?></span>
                </div>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>

        </aside>
      </div><!-- /.mktd-layout -->

      <?php
      $q = new WP_Query(['category_name'=>'markets','posts_per_page'=>6,'post_status'=>'publish']);
      if ($q->have_posts()): ?>
      <section class="mktd-news">
        <div class="sec-hd">
          <span class="sec-title">Latest from Markets</span>
        </div>
        <div class="news-rows">
          <?php while ($q->have_posts()): $q->the_post(); ?>
          <article <?php post_class('news-row'); ?>>
            <a href="<?php the_permalink(); ?>" class="news-row-img" tabindex="-1" aria-hidden="true">
              <?php if (has_post_thumbnail()): the_post_thumbnail('aiv-thumb', ['alt'=>'','loading'=>'lazy']); endif; ?>
            </a>
            <div class="news-row-body">
              <?php aiv_cat(); ?>
              <h3 class="news-row-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <p class="news-row-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 22, '…'); ?></p>
              <div class="meta">
                <span class="meta-i"><?php echo aiv_icon('calendar'); ?> <?php echo get_the_date(); ?></span>
                <span class="meta-i"><?php echo aiv_icon('clock'); ?> <?php echo aiv_read_time(); ?></span>
              </div>
            </div>
          </article>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
      </section>
      <?php endif; ?>

    </div><!-- /.mktd-wrap -->
  </div><!-- /.mktd-light -->

</div><!-- /.mktd-page -->

<script>
(function(){
  /* Tab filter */
  var tabs = document.querySelectorAll('.mktd-tab');
  var rows = document.querySelectorAll('.mktd-tr');
  tabs.forEach(function(tab){
    tab.addEventListener('click', function(){
      tabs.forEach(function(t){ t.classList.remove('is-active'); t.setAttribute('aria-selected','false'); });
      tab.classList.add('is-active'); tab.setAttribute('aria-selected','true');
      var f = tab.dataset.f;
      rows.forEach(function(row){
        var up = row.dataset.up === '1';
        if (f === 'all' || f === 'volume') row.style.display = '';
        else if (f === 'gainers') row.style.display = up  ? '' : 'none';
        else if (f === 'losers')  row.style.display = !up ? '' : 'none';
      });
    });
  });
  /* Live clock */
  var clock = document.getElementById('mktd-clock');
  if (clock) {
    setInterval(function(){
      var now = new Date();
      var h = String(now.getHours()).padStart(2,'0');
      var m = String(now.getMinutes()).padStart(2,'0');
      var s = String(now.getSeconds()).padStart(2,'0');
      clock.textContent = h + ':' + m + ':' + s + ' IST';
    }, 1000);
  }
}());
</script>

<?php get_footer(); ?>
