<?php $cells = aiv_index_stories(); ?>
<section class="idx-stories">
  <?php foreach ($cells as $c): ?>
  <a class="idx-cell" href="<?php echo esc_url(home_url('/markets')); ?>">
    <span class="idx-symbol"><?php echo esc_html($c['symbol']); ?></span>
    <span class="idx-value"><?php echo esc_html($c['value']); ?></span>
    <span class="idx-change <?php echo $c['up'] ? 'up' : 'down'; ?>">
      <?php echo $c['up'] ? '▲' : '▼'; ?> <?php echo esc_html($c['chg']); ?>
    </span>
    <span class="idx-story-cat"><?php echo esc_html($c['cat']); ?></span>
    <span class="idx-story-title"><?php echo esc_html($c['title']); ?></span>
  </a>
  <?php endforeach; ?>
</section>
