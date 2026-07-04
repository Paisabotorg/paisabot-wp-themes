<?php $d = aiv_dateline_data(); ?>
<div class="dateline-strip">
  <div class="wrap">
    <div class="dateline-left">
      <span class="vol"><?php echo esc_html($d['vol']); ?></span>
      <span class="sep">·</span>
      <span><?php echo wp_date('l, d F Y'); ?></span>
      <span class="sep">·</span>
      <span><?php echo esc_html($d['edition']); ?></span>
    </div>
    <div class="dateline-right">
      <span><?php echo esc_html($d['weather']); ?></span>
      <span class="sep">·</span>
      <span class="pulse"><span class="pulse-dot"></span>SENSEX <?php echo esc_html($d['sensex']); ?></span>
      <span>NIFTY <?php echo esc_html($d['nifty']); ?></span>
      <span>USD/INR <?php echo esc_html($d['rupee']); ?></span>
    </div>
  </div>
</div>
