<?php
/**
 * Front-page asymmetric editorial hero.
 * 3 columns: big lead | mid (image + chart) | 4 stacked text-only stories
 */
$top = aiv_top_story();
$mid = $top ? aiv_mid_story($top->ID) : null;
$ex  = array_filter([$top?->ID, $mid?->ID]);
$right = aiv_right_stories($ex, 4);
if (!$top) return;
$top_cat = (get_the_category($top->ID)[0] ?? null);
$mid_cat = $mid ? (get_the_category($mid->ID)[0] ?? null) : null;
?>
<section class="front-page">
  <div class="front-page-eyebrow">
    <span class="left">
      <span class="pip">●</span>
      The Front Page
      <em>— <?php echo wp_date('l, d F Y'); ?></em>
    </span>
    <span class="right">UPDATED <?php echo wp_date('H:i'); ?> IST · LIVE</span>
  </div>

  <div class="front-page-grid">

    <!-- LEAD -->
    <a class="fp-lead" href="<?php echo esc_url(get_permalink($top)); ?>">
      <div class="fp-image">
        <?php if (has_post_thumbnail($top)):
          echo get_the_post_thumbnail($top, 'aiv-hero', ['alt' => get_the_title($top), 'loading' => 'eager']);
        else: ?>
          <div class="card-img-placeholder">
            <svg viewBox="0 0 40 40" width="60" height="60" style="opacity:.2">
              <rect x="7"  y="22" width="5"  height="12" rx="2" fill="#fff"/>
              <rect x="14" y="15" width="5"  height="19" rx="2" fill="#fff" opacity=".75"/>
              <rect x="21" y="9"  width="5"  height="25" rx="2" fill="#fff" opacity=".5"/>
              <rect x="28" y="5"  width="5"  height="29" rx="2" fill="#fff" opacity=".3"/>
            </svg>
          </div>
        <?php endif; ?>
      </div>
      <?php if ($top_cat): ?>
        <span class="fp-cat" style="color: var(--cat-<?php echo esc_attr($top_cat->slug); ?>, var(--amber-3))"><?php echo esc_html($top_cat->name); ?> · Lead Story</span>
      <?php endif; ?>
      <h1><?php echo esc_html(get_the_title($top)); ?></h1>
      <p class="fp-deck"><?php echo esc_html(get_the_excerpt($top)); ?></p>
      <div class="fp-byline">
        <strong>By <?php echo esc_html(get_the_author_meta('display_name', $top->post_author)); ?></strong>
        <span class="fp-byline-dot"></span>
        <span><?php echo esc_html(get_the_author_meta('description', $top->post_author) ? wp_trim_words(get_the_author_meta('description', $top->post_author), 4, '') : 'Senior Correspondent'); ?></span>
        <span class="fp-byline-dot"></span>
        <span><?php echo get_the_date('j M Y', $top); ?> · <?php echo get_the_date('H:i', $top); ?> IST</span>
        <span class="fp-byline-dot"></span>
        <span><?php echo aiv_read_time($top->ID); ?> read</span>
      </div>
    </a>

    <!-- MID -->
    <?php if ($mid): ?>
    <a class="fp-mid" href="<?php echo esc_url(get_permalink($mid)); ?>">
      <div class="fp-image-sm">
        <?php if (has_post_thumbnail($mid)):
          echo get_the_post_thumbnail($mid, 'aiv-mid', ['alt' => get_the_title($mid)]);
        endif; ?>
      </div>
      <?php if ($mid_cat): ?>
        <span class="fp-cat" style="color: var(--cat-<?php echo esc_attr($mid_cat->slug); ?>, var(--cat-markets))"><?php echo esc_html($mid_cat->name); ?></span>
      <?php endif; ?>
      <h2><?php echo esc_html(get_the_title($mid)); ?></h2>
      <p class="fp-deck"><?php echo esc_html(wp_trim_words(get_the_excerpt($mid), 22, '…')); ?></p>
      <div class="meta" style="font-size:11px;color:var(--fg-muted)">
        By <?php echo esc_html(get_the_author_meta('display_name', $mid->post_author)); ?> · <?php echo get_the_date('j M', $mid); ?> · <?php echo aiv_read_time($mid->ID); ?>
      </div>
    </a>
    <?php endif; ?>

    <!-- RIGHT — 4 stories -->
    <div class="fp-right">
      <?php foreach ($right as $r):
        $rc = get_the_category($r->ID)[0] ?? null;
      ?>
      <a class="fp-right-item" href="<?php echo esc_url(get_permalink($r)); ?>">
        <?php if ($rc): ?>
        <span class="fp-cat" style="color: var(--cat-<?php echo esc_attr($rc->slug); ?>, var(--amber-3))"><?php echo esc_html($rc->name); ?></span>
        <?php endif; ?>
        <h3><?php echo esc_html(get_the_title($r)); ?></h3>
        <div class="meta" style="font-size:11px;color:var(--fg-muted)">
          <?php echo get_the_date('j M', $r); ?> · <?php echo aiv_read_time($r->ID); ?>
        </div>
      </a>
      <?php endforeach; ?>
    </div>

  </div>
</section>
