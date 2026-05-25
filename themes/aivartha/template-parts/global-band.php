<?php
$cells = aiv_global_cells();
$posts = aiv_global_posts(4);

// Merge: use post permalink/title if available; else fall back to default cell data
$merged = [];
for ($i = 0; $i < 4; $i++) {
    $c = $cells[$i] ?? null;
    $p = $posts[$i] ?? null;
    if ($p) {
        $merged[] = [
            'region' => $c['region'] ?? 'World',
            'flag'   => $c['flag']   ?? '#777',
            'title'  => get_the_title($p),
            'url'    => get_permalink($p),
            'stat'   => $c['stat']   ?? '',
            'up'     => $c['up']     ?? true,
            'pct'    => $c['pct']    ?? '',
        ];
    } elseif ($c) {
        $merged[] = $c + ['url' => '#'];
    }
}
if (!$merged) return;
?>
<section class="section-band">
  <div class="section-band-hd">
    <div class="section-band-hd-left">
      <h2>Global</h2>
      <p class="tagline">What moved overnight in the four economies most likely to move ours.</p>
    </div>
    <a class="sec-more" href="<?php echo esc_url(get_category_link(get_cat_ID('Global'))); ?>">
      All World news <?php echo aiv_icon('i-arrow-r'); ?>
    </a>
  </div>
  <div class="global-grid">
    <?php foreach ($merged as $m): ?>
    <a class="global-cell" href="<?php echo esc_url($m['url']); ?>">
      <span class="gc-region">
        <span class="gc-flag" style="background:<?php echo esc_attr($m['flag']); ?>"></span>
        <?php echo esc_html($m['region']); ?>
      </span>
      <h4><?php echo esc_html($m['title']); ?></h4>
      <?php if (!empty($m['stat'])): ?>
      <div class="gc-stat">
        <span><?php echo esc_html($m['stat']); ?></span>
        <span class="<?php echo $m['up'] ? 'up' : 'down'; ?>"><?php echo $m['up'] ? '▲' : '▼'; ?> <?php echo esc_html($m['pct']); ?></span>
      </div>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  </div>
</section>
