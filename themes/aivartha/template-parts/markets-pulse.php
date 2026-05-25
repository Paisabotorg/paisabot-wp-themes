<?php
$hm      = aiv_heatmap_data();
$idx     = aiv_pulse_indices();
$stocks  = aiv_stock_list();
$sa_page  = 'https://analyse.paisabot.com';
$from_url = home_url();

// Color mapping for cells
$cell_color = function ($pct) {
    $v = floatval(str_replace(['%', '+'], '', $pct));
    if ($v >=  2)   return '#0E5C36';
    if ($v >=  1)   return '#1A7A4A';
    if ($v >=  0.3) return '#3FA268';
    if ($v >=  0)   return '#7BC09A';
    if ($v >= -0.5) return '#E68A82';
    if ($v >= -1)   return '#D45A4A';
    return '#A82A20';
};
?>
<section class="pulse-band">
  <div class="pulse-hd">
    <div class="pulse-hd-left">
      <span class="live-tag"><span class="dot"></span>LIVE</span>
      <h2>Markets Pulse · Sector Heatmap</h2>
    </div>
    <div class="pulse-tabs">
      <?php foreach (['1d','5d','1m','6m','1y'] as $i => $t): ?>
        <button class="<?php echo $i === 0 ? 'active' : ''; ?>"><?php echo strtoupper($t); ?></button>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="pulse-body">
    <div>
      <div class="heatmap">
        <?php foreach ($hm as $c): ?>
        <div class="heatmap-cell<?php echo !empty($c['large']) ? ' lg' : ''; ?>"
             style="background: <?php echo esc_attr($cell_color($c['pct'])); ?>">
          <span class="hm-name"><?php echo esc_html($c['name']); ?></span>
          <div>
            <div class="hm-pct"><?php echo esc_html($c['pct']); ?></div>
            <?php if (!empty($c['large']) && !empty($c['sub'])): ?>
            <div class="hm-sub"><?php echo esc_html($c['sub']); ?></div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="pulse-side">
      <h3>Key Indices</h3>
      <div class="pulse-indices">
        <?php foreach ($idx as $m): ?>
        <div class="pulse-index">
          <div class="pulse-index-left">
            <span class="pulse-index-name"><?php echo esc_html($m['name']); ?></span>
            <span class="pulse-index-value"><?php echo esc_html($m['value']); ?></span>
          </div>
          <span class="pulse-index-chg <?php echo $m['up'] ? 'up' : 'down'; ?>">
            <?php echo $m['up'] ? '▲' : '▼'; ?> <?php echo esc_html($m['chg']); ?>
          </span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- ▸ STOCK PANELS WITH ANALYSE BUTTONS -->
  <div class="pulse-stocks">
    <div class="pulse-stocks-hd">
      <h3>Top Stocks</h3>
      <a href="<?php echo esc_url(add_query_arg('from', $from_url, $sa_page)); ?>" class="pulse-see-all">View All Analysis →</a>
    </div>
    <div class="pulse-stock-grid">
      <?php foreach ($stocks as $s): ?>
      <div class="pulse-stock-card">
        <div class="pulse-stock-top">
          <div>
            <span class="pulse-stock-ticker"><?php echo esc_html($s['ticker']); ?></span>
            <span class="pulse-stock-name"><?php echo esc_html($s['name']); ?></span>
          </div>
          <span class="pulse-stock-sector"><?php echo esc_html($s['sector']); ?></span>
        </div>
        <div class="pulse-stock-bottom">
          <div class="pulse-stock-price">
            <span class="pulse-stock-val"><?php echo esc_html($s['price']); ?></span>
            <span class="pulse-stock-chg <?php echo $s['up'] ? 'up' : 'down'; ?>">
              <?php echo $s['up'] ? '▲' : '▼'; ?> <?php echo esc_html($s['chg']); ?>
            </span>
          </div>
          <a href="<?php echo esc_url(add_query_arg(['ticker' => $s['ticker'], 'from' => $from_url], $sa_page)); ?>"
             class="btn-analyse">Analyse</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
