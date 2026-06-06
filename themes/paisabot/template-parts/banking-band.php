<?php
$bank = aiv_banking_posts(4);
if (!$bank) return;
$feature = array_shift($bank);
$fc = get_the_category($feature->ID)[0] ?? null;
?>
<section class="section-band dark">
  <div class="wrap">
    <div class="section-band-hd">
      <div class="section-band-hd-left">
        <h2>Banking</h2>
        <p class="tagline">Capital raises, NIM trends, and the cycle the market doesn't yet trust.</p>
      </div>
      <a class="sec-more" href="<?php echo esc_url(get_category_link(get_cat_ID('Banking'))); ?>">
        All Banking <?php echo aiv_icon('i-arrow-r'); ?>
      </a>
    </div>

    <div class="banking-grid">
      <a class="banking-feature" href="<?php echo esc_url(get_permalink($feature)); ?>">
        <div class="bf-image">
          <?php if (has_post_thumbnail($feature)):
            echo get_the_post_thumbnail($feature, 'aiv-banking');
          endif; ?>
        </div>
        <span class="fp-cat"><?php echo $fc ? esc_html($fc->name) : 'Banking'; ?> · Feature</span>
        <h3><?php echo esc_html(get_the_title($feature)); ?></h3>
        <p><?php echo esc_html(wp_trim_words(get_the_excerpt($feature), 36, '…')); ?></p>
      </a>

      <div class="banking-list">
        <?php foreach ($bank as $b):
          $bc = get_the_category($b->ID)[0] ?? null;
        ?>
        <a class="banking-row" href="<?php echo esc_url(get_permalink($b)); ?>">
          <div class="br-text">
            <?php if ($bc): ?><span class="briefing-cat"><?php echo esc_html($bc->name); ?></span><?php endif; ?>
            <h4><?php echo esc_html(get_the_title($b)); ?></h4>
            <div class="meta" style="font-size:11px;color:var(--fg-muted)">
              <?php echo get_the_date('j M', $b); ?> · <?php echo aiv_read_time($b->ID); ?> read
            </div>
          </div>
          <div class="br-image">
            <?php if (has_post_thumbnail($b)):
              echo get_the_post_thumbnail($b, 'aiv-thumb', ['alt'=>'']);
            endif; ?>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
