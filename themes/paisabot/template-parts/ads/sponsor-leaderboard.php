<?php
/**
 * Full-width sponsor leaderboard ad (between sections).
 * Pulls from widget area if active; otherwise renders a default house ad.
 *
 * Usage:
 *   get_template_part('template-parts/ads/sponsor-leaderboard', null, [
 *     'slot'     => 'ad-leaderboard-pulse',
 *     'sponsor'  => 'HDFC Securities',
 *     'headline' => 'Trade Equity, F&O & Commodity — all on one screen.',
 *     'body'     => '₹0 brokerage on intraday equity. Free research reports.',
 *     'cta'      => 'Open account',
 *     'cta_url'  => 'https://...',
 *     'kind'     => 'sponsor',   // 'sponsor' | 'house'
 *   ]);
 */
$args = wp_parse_args($args ?? [], [
    'slot'     => 'ad-leaderboard',
    'sponsor'  => '',
    'headline' => '',
    'body'     => '',
    'cta'      => 'Learn more',
    'cta_url'  => '#',
    'kind'     => 'sponsor',
]);

// If the named widget area is active, render that instead (advertiser GAM/AdSense)
if (!empty($args['slot']) && is_active_sidebar($args['slot'])): ?>
<aside class="leaderboard-ad lb-<?php echo esc_attr($args['kind']); ?>" aria-label="Advertisement">
  <div class="lb-rule"></div>
  <div class="lb-inner">
    <span class="ad-tag">Advertisement</span>
    <div class="lb-content lb-content-widget">
      <?php dynamic_sidebar($args['slot']); ?>
    </div>
  </div>
  <div class="lb-rule"></div>
</aside>
<?php elseif (!empty($args['headline'])): ?>
<aside class="leaderboard-ad lb-<?php echo esc_attr($args['kind']); ?>" aria-label="Advertisement">
  <div class="lb-rule"></div>
  <div class="lb-inner">
    <span class="ad-tag">Advertisement · 970×120</span>
    <div class="lb-content">
      <div class="lb-text">
        <?php if (!empty($args['sponsor'])): ?>
          <span class="lb-sponsor"><?php echo esc_html($args['sponsor']); ?></span>
        <?php endif; ?>
        <h3 class="lb-headline"><?php echo esc_html($args['headline']); ?></h3>
        <?php if (!empty($args['body'])): ?>
          <p class="lb-body"><?php echo esc_html($args['body']); ?></p>
        <?php endif; ?>
      </div>
      <a class="lb-cta" href="<?php echo esc_url($args['cta_url']); ?>"<?php echo $args['kind'] === 'sponsor' ? ' rel="sponsored noopener" target="_blank"' : ''; ?>>
        <?php echo esc_html($args['cta']); ?>
        <?php echo aiv_icon('i-arrow-r'); ?>
      </a>
    </div>
  </div>
  <div class="lb-rule"></div>
</aside>
<?php endif; ?>
