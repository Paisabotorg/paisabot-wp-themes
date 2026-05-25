<?php
$briefs = aiv_briefing_posts(5);
$nums   = aiv_numbers_today();
if (!$briefs) return;
?>
<section class="briefing-band">
  <div>
    <div class="briefing-hd">
      <h2>The <em>Briefing</em></h2>
      <p>Five stories shaping today's market — chosen by our newsroom, in the order you should read them.</p>
    </div>
    <div class="briefing-list">
      <?php foreach ($briefs as $i => $p):
        $c = get_the_category($p->ID)[0] ?? null;
      ?>
      <a class="briefing-item" href="<?php echo esc_url(get_permalink($p)); ?>">
        <span class="briefing-num"><?php echo sprintf('%02d', $i + 1); ?></span>
        <div class="briefing-body">
          <?php if ($c): ?><span class="briefing-cat"><?php echo esc_html($c->name); ?></span><?php endif; ?>
          <h3><?php echo esc_html(get_the_title($p)); ?></h3>
          <p><?php echo esc_html(wp_trim_words(get_the_excerpt($p), 30, '…')); ?></p>
          <div class="meta">
            <span class="meta-i"><?php echo aiv_icon('user'); ?> <?php echo esc_html(get_the_author_meta('display_name', $p->post_author)); ?></span>
            <span class="meta-i"><?php echo aiv_icon('calendar'); ?> <?php echo get_the_date('j M', $p); ?></span>
            <span class="meta-i"><?php echo aiv_icon('clock'); ?> <?php echo aiv_read_time($p->ID); ?></span>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="briefing-side">
    <div class="numbers-card">
      <div class="nc-hd">By the Numbers · <?php echo wp_date('j M'); ?></div>
      <div class="numbers-stack">
        <?php foreach ($nums as $n): ?>
        <div class="numbers-item">
          <div class="numbers-figure <?php echo esc_attr($n['accent']); ?>">
            <?php echo esc_html($n['figure']); ?><?php if (!empty($n['unit'])): ?><em style="font-size:.6em;margin-left:4px;font-style:italic;color:var(--amber-2)"><?php echo esc_html($n['unit']); ?></em><?php endif; ?>
          </div>
          <div class="numbers-label"><?php echo esc_html($n['label']); ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <?php get_template_part('template-parts/ads/mpu'); ?>
  </div>
</section>
