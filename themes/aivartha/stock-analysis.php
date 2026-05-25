<?php
/**
 * Template Name: Stock Analysis
 *
 * Individual stock analysis page.
 * URL: /stock-analysis/?ticker=RELIANCE
 */
defined('ABSPATH') || exit;

$ticker = strtoupper(sanitize_text_field($_GET['ticker'] ?? 'RELIANCE'));
$stock  = aiv_get_stock_data($ticker);

get_header();
?>
<div class="sa-page">
  <div class="wrap">

    <!-- ▸ HERO BAR -->
    <div class="sa-hero">
      <div class="sa-hero-left">
        <div class="sa-ticker-badge"><?php echo esc_html($ticker); ?> <span class="sa-exchange">NSE</span></div>
        <h1 class="sa-company"><?php echo esc_html($stock['name']); ?></h1>
        <div class="sa-sector-tag"><?php echo esc_html($stock['sector']); ?></div>
      </div>
      <div class="sa-hero-right">
        <div class="sa-price-block">
          <span class="sa-price"><?php echo esc_html($stock['price']); ?></span>
          <span class="sa-change <?php echo $stock['up'] ? 'sa-up' : 'sa-dn'; ?>">
            <?php echo $stock['up'] ? '▲' : '▼'; ?> <?php echo esc_html($stock['change']); ?> (<?php echo esc_html($stock['change_pct']); ?>)
          </span>
        </div>
        <div class="sa-hero-meta">
          <span>Vol: <strong><?php echo esc_html($stock['volume']); ?></strong></span>
          <span>Mkt Cap: <strong><?php echo esc_html($stock['mktcap']); ?></strong></span>
          <span>52W: <strong class="sa-up"><?php echo esc_html($stock['w52_high']); ?></strong> / <strong class="sa-dn"><?php echo esc_html($stock['w52_low']); ?></strong></span>
        </div>
      </div>
    </div>

    <!-- ▸ TAB BAR -->
    <div class="sa-tabs">
      <?php foreach (['1D','1W','21D','1M','3M','1Y','5Y'] as $t): ?>
      <button class="sa-tab<?php echo $t === '1D' ? ' active' : ''; ?>" data-tf="<?php echo esc_attr($t); ?>"><?php echo esc_html($t); ?></button>
      <?php endforeach; ?>
    </div>

    <!-- ▸ SECTION 2: THREE CHARTS -->
    <div class="sa-charts-row">
      <div class="sa-chart-panel">
        <div class="sa-chart-hd">
          <span class="sa-chart-title">Today (1D)</span>
          <span class="sa-return <?php echo $stock['up'] ? 'sa-up' : 'sa-dn'; ?>"><?php echo esc_html($stock['change_pct']); ?></span>
        </div>
        <canvas id="chart-1d" class="sa-canvas" data-type="intraday" data-ticker="<?php echo esc_attr($ticker); ?>"></canvas>
        <div class="sa-chart-legend">
          <span class="sa-leg-dot" style="background:#C4820A"></span> Price
          <span class="sa-leg-dot" style="background:#334155;margin-left:12px"></span> Volume
          <span style="margin-left:auto;font-size:11px;color:var(--muted)">Open: <strong><?php echo esc_html($stock['open']); ?></strong></span>
        </div>
      </div>

      <div class="sa-chart-panel">
        <div class="sa-chart-hd">
          <span class="sa-chart-title">This Week (5D)</span>
          <span class="sa-return <?php echo floatval($stock['chg_5d']) >= 0 ? 'sa-up' : 'sa-dn'; ?>"><?php echo esc_html($stock['chg_5d']); ?></span>
        </div>
        <canvas id="chart-5d" class="sa-canvas" data-type="weekly" data-ticker="<?php echo esc_attr($ticker); ?>"></canvas>
        <div class="sa-chart-legend">
          <span class="sa-leg-dot" style="background:#C4820A"></span> OHLCV candles
          <span style="margin-left:auto;font-size:11px;color:var(--muted)">High: <strong class="sa-up"><?php echo esc_html($stock['high']); ?></strong> Low: <strong class="sa-dn"><?php echo esc_html($stock['low']); ?></strong></span>
        </div>
      </div>

      <div class="sa-chart-panel">
        <div class="sa-chart-hd">
          <span class="sa-chart-title">21-Day Trend</span>
          <span class="sa-return <?php echo floatval($stock['chg_21d']) >= 0 ? 'sa-up' : 'sa-dn'; ?>"><?php echo esc_html($stock['chg_21d']); ?></span>
        </div>
        <canvas id="chart-21d" class="sa-canvas" data-type="trend21" data-ticker="<?php echo esc_attr($ticker); ?>"></canvas>
        <div class="sa-chart-legend">
          <span class="sa-leg-dot" style="background:#C4820A"></span> Price
          <span class="sa-leg-dot" style="background:#6366F1;margin-left:12px"></span> 9-EMA
          <span class="sa-leg-dot" style="background:#F59E0B;margin-left:12px"></span> 21-EMA
        </div>
      </div>
    </div>

    <!-- ▸ SECTION 3: OHLCV + KEY METRICS -->
    <div class="sa-data-row">
      <div class="sa-ohlcv-card">
        <div class="sa-card-hd">OHLCV &mdash; <?php echo esc_html(wp_date('d M Y')); ?></div>
        <div class="sa-ohlcv-grid">
          <div class="sa-ohlcv-item"><span>Open</span><strong><?php echo esc_html($stock['open']); ?></strong></div>
          <div class="sa-ohlcv-item"><span>High</span><strong class="sa-up"><?php echo esc_html($stock['high']); ?></strong></div>
          <div class="sa-ohlcv-item"><span>Low</span><strong class="sa-dn"><?php echo esc_html($stock['low']); ?></strong></div>
          <div class="sa-ohlcv-item"><span>Close</span><strong><?php echo esc_html($stock['price']); ?></strong></div>
          <div class="sa-ohlcv-item"><span>Volume</span><strong><?php echo esc_html($stock['volume']); ?></strong></div>
          <div class="sa-ohlcv-item"><span>Value</span><strong><?php echo esc_html($stock['turnover']); ?></strong></div>
          <div class="sa-ohlcv-item"><span>VWAP</span><strong><?php echo esc_html($stock['vwap']); ?></strong></div>
          <div class="sa-ohlcv-item"><span>Prev Close</span><strong><?php echo esc_html($stock['prev_close']); ?></strong></div>
        </div>
      </div>

      <div class="sa-metrics-card">
        <div class="sa-card-hd">Key Metrics</div>
        <div class="sa-metrics-grid">
          <div class="sa-metric"><span>52W High</span><strong class="sa-up"><?php echo esc_html($stock['w52_high']); ?></strong></div>
          <div class="sa-metric"><span>52W Low</span><strong class="sa-dn"><?php echo esc_html($stock['w52_low']); ?></strong></div>
          <div class="sa-metric"><span>P/E Ratio</span><strong><?php echo esc_html($stock['pe']); ?></strong></div>
          <div class="sa-metric"><span>EPS (TTM)</span><strong><?php echo esc_html($stock['eps']); ?></strong></div>
          <div class="sa-metric"><span>Market Cap</span><strong><?php echo esc_html($stock['mktcap']); ?></strong></div>
          <div class="sa-metric"><span>Beta</span><strong><?php echo esc_html($stock['beta']); ?></strong></div>
          <div class="sa-metric"><span>Div Yield</span><strong><?php echo esc_html($stock['div_yield']); ?></strong></div>
          <div class="sa-metric"><span>Face Value</span><strong><?php echo esc_html($stock['face_value']); ?></strong></div>
        </div>
      </div>
    </div>

    <!-- ▸ SECTION 4: TECHNICAL ANALYSIS -->
    <div class="sa-technical-row">

      <!-- Support & Resistance -->
      <div class="sa-tech-card">
        <div class="sa-card-hd">
          Support &amp; Resistance
          <div class="sa-pivot-tabs">
            <button class="active">Classic</button>
            <button>Fibonacci</button>
            <button>Camarilla</button>
          </div>
        </div>
        <div class="sa-sr-ladder">
          <?php foreach ($stock['resistance'] as $r): ?>
          <div class="sa-sr-row sa-sr-res">
            <span class="sa-sr-label"><?php echo esc_html($r['label']); ?></span>
            <span class="sa-sr-bar"><span style="width:<?php echo esc_attr($r['pct']); ?>%"></span></span>
            <span class="sa-sr-val"><?php echo esc_html($r['val']); ?></span>
            <span class="sa-sr-strength"><?php echo esc_html($r['strength'] ?? ''); ?></span>
          </div>
          <?php endforeach; ?>
          <div class="sa-sr-row sa-sr-cmp">
            <span class="sa-sr-label">CMP</span>
            <span class="sa-sr-bar sa-sr-cmp-bar"><span style="width:50%"></span></span>
            <span class="sa-sr-val"><?php echo esc_html($stock['price']); ?></span>
          </div>
          <?php foreach ($stock['support'] as $s): ?>
          <div class="sa-sr-row sa-sr-sup">
            <span class="sa-sr-label"><?php echo esc_html($s['label']); ?></span>
            <span class="sa-sr-bar"><span style="width:<?php echo esc_attr($s['pct']); ?>%"></span></span>
            <span class="sa-sr-val"><?php echo esc_html($s['val']); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Moving Averages -->
      <div class="sa-tech-card">
        <div class="sa-card-hd">Moving Averages</div>
        <div class="sa-ma-list">
          <?php foreach ($stock['moving_averages'] as $ma): ?>
          <div class="sa-ma-row">
            <span class="sa-ma-name"><?php echo esc_html($ma['name']); ?></span>
            <span class="sa-ma-val"><?php echo esc_html($ma['val']); ?></span>
            <span class="sa-ma-pos <?php echo $ma['above'] ? 'sa-up' : 'sa-dn'; ?>"><?php echo $ma['above'] ? 'ABOVE' : 'BELOW'; ?></span>
            <span class="sa-signal-badge <?php echo $ma['above'] ? 'bullish' : 'bearish'; ?>"><?php echo $ma['above'] ? 'BULLISH' : 'BEARISH'; ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <?php
        $bull = count(array_filter($stock['moving_averages'], fn($m) => $m['above']));
        $total_ma = count($stock['moving_averages']);
        $bull_pct = $total_ma ? round($bull / $total_ma * 100) : 0;
        ?>
        <div class="sa-ma-summary">
          <span>Overall: <strong><?php echo $bull >= $total_ma * .75 ? 'BULLISH' : ($bull <= $total_ma * .25 ? 'BEARISH' : 'MIXED'); ?></strong> (<?php echo $bull; ?>/<?php echo $total_ma; ?>)</span>
          <div class="sa-bull-bar"><div style="width:<?php echo $bull_pct; ?>%"></div></div>
        </div>
      </div>

      <!-- Momentum Indicators -->
      <div class="sa-tech-card">
        <div class="sa-card-hd">Momentum Indicators</div>
        <div class="sa-momentum-list">
          <?php foreach ($stock['indicators'] as $ind): ?>
          <div class="sa-ind-row">
            <span class="sa-ind-name"><?php echo esc_html($ind['name']); ?></span>
            <span class="sa-ind-val"><?php echo esc_html($ind['val']); ?></span>
            <span class="sa-signal-badge <?php echo esc_attr(strtolower(str_replace(' ', '-', $ind['signal']))); ?>"><?php echo esc_html($ind['signal']); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="sa-momentum-verdict">
          <span class="sa-verdict-label">Overall Signal</span>
          <span class="sa-signal-badge bullish"><?php echo esc_html($stock['momentum_verdict']); ?></span>
        </div>
      </div>
    </div>

    <!-- ▸ SECTION 5: BREAKOUT ALERTS -->
    <?php if (!empty($stock['alerts'])): ?>
    <div class="sa-alerts-row">
      <?php foreach ($stock['alerts'] as $alert): ?>
      <div class="sa-alert-card sa-alert-<?php echo esc_attr($alert['type']); ?>">
        <div class="sa-alert-icon"><?php echo esc_html($alert['icon']); ?></div>
        <div class="sa-alert-body">
          <div class="sa-alert-title"><?php echo esc_html($alert['title']); ?></div>
          <div class="sa-alert-desc"><?php echo esc_html($alert['desc']); ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- ▸ SECTION 6: ADDITIONAL CHARTS -->
    <div class="sa-charts-row">
      <div class="sa-chart-panel">
        <div class="sa-chart-hd"><span class="sa-chart-title">Volume Profile</span></div>
        <canvas id="chart-vol-profile" class="sa-canvas" data-type="volume-profile" data-ticker="<?php echo esc_attr($ticker); ?>"></canvas>
        <div class="sa-chart-legend"><span class="sa-leg-dot" style="background:#C4820A"></span> POC <span class="sa-leg-dot" style="background:#1E3050;margin-left:12px"></span> Value Area</div>
      </div>
      <div class="sa-chart-panel">
        <div class="sa-chart-hd"><span class="sa-chart-title">Relative Strength vs NIFTY50</span></div>
        <canvas id="chart-rs" class="sa-canvas" data-type="relative-strength" data-ticker="<?php echo esc_attr($ticker); ?>"></canvas>
        <div class="sa-chart-legend"><span class="sa-leg-dot" style="background:#6366F1"></span> <?php echo esc_html($ticker); ?> <span class="sa-leg-dot" style="background:#334155;margin-left:12px"></span> NIFTY50</div>
      </div>
      <div class="sa-chart-panel">
        <div class="sa-chart-hd"><span class="sa-chart-title">Delivery % (30 sessions)</span></div>
        <canvas id="chart-delivery" class="sa-canvas" data-type="delivery" data-ticker="<?php echo esc_attr($ticker); ?>"></canvas>
        <div class="sa-chart-legend"><span class="sa-leg-dot" style="background:#C4820A"></span> Delivery % <span class="sa-leg-dot" style="background:#334155;margin-left:12px"></span> Total Volume</div>
      </div>
    </div>

    <!-- ▸ SECTION 7: ANALYST VERDICT -->
    <div class="sa-verdict-box">
      <div class="sa-verdict-header">
        <span class="sa-verdict-brand">annaPaisa Analyst View</span>
        <span class="sa-signal-badge <?php echo esc_attr(strtolower(str_replace(' ', '-', $stock['verdict']['rating']))); ?> large"><?php echo esc_html($stock['verdict']['rating']); ?></span>
      </div>
      <div class="sa-verdict-body">
        <div class="sa-verdict-metrics">
          <div class="sa-verdict-metric">
            <span>Target (3M)</span>
            <strong class="sa-up"><?php echo esc_html($stock['verdict']['target']); ?></strong>
            <em>Upside: <?php echo esc_html($stock['verdict']['upside']); ?></em>
          </div>
          <div class="sa-verdict-metric">
            <span>Stop Loss</span>
            <strong class="sa-dn"><?php echo esc_html($stock['verdict']['stop_loss']); ?></strong>
            <em>Risk: <?php echo esc_html($stock['verdict']['risk']); ?></em>
          </div>
          <div class="sa-verdict-metric">
            <span>Risk / Reward</span>
            <strong><?php echo esc_html($stock['verdict']['rr_ratio']); ?></strong>
          </div>
        </div>
        <blockquote class="sa-verdict-quote"><?php echo esc_html($stock['verdict']['note']); ?></blockquote>
        <div class="sa-verdict-actions">
          <a href="#" class="sa-btn-primary">Read Full Analysis</a>
          <a href="#" class="sa-btn-outline">Set Price Alert</a>
        </div>
      </div>
    </div>

    <!-- ▸ SECTION 8: RELATED NEWS -->
    <?php
    $related = get_posts([
        'numberposts' => 6,
        'post_status' => 'publish',
        's'           => $stock['name'],
    ]);
    if (empty($related)) {
        $related = get_posts(['numberposts' => 6, 'post_status' => 'publish']);
    }
    ?>
    <?php if ($related): ?>
    <div class="sa-news-section">
      <h2 class="sa-news-hd">Latest News &mdash; <?php echo esc_html($stock['name']); ?></h2>
      <div class="sa-news-scroll">
        <?php foreach ($related as $p): ?>
        <div class="sa-news-card">
          <?php if (has_post_thumbnail($p->ID)): ?>
          <div class="sa-news-img"><?php echo get_the_post_thumbnail($p->ID, 'aiv-card'); ?></div>
          <?php endif; ?>
          <div class="sa-news-body">
            <span class="sa-news-cat"><?php
              $cats = get_the_category($p->ID);
              echo esc_html($cats ? $cats[0]->name : 'Markets');
            ?></span>
            <h3><a href="<?php echo esc_url(get_permalink($p->ID)); ?>"><?php echo esc_html(get_the_title($p->ID)); ?></a></h3>
            <div class="sa-news-meta">
              <span><?php echo human_time_diff(get_the_time('U', $p->ID), current_time('timestamp')); ?> ago</span>
              <span class="sa-sentiment-badge neutral">NEUTRAL</span>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

  </div><!-- .wrap -->
</div><!-- .sa-page -->

<?php get_footer(); ?>
