<?php get_header(); ?>

<div class="home-grid">
  <div>

    <div class="error-404">
      <div class="e404-code">404</div>
      <div class="e404-icon"><?php echo aiv_icon('newspaper'); ?></div>
      <h1 class="e404-title">Page Not Found</h1>
      <p class="e404-desc">The article or page you're looking for may have been moved, deleted, or never existed. Try searching or explore our latest coverage.</p>

      <?php get_search_form(); ?>

      <div class="e404-links">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="hero-cta">
          <?php echo aiv_icon('i-home'); ?> Back to Home
        </a>
      </div>

      <!-- Recent articles -->
      <div class="e404-recent">
        <div class="sec-hd">
          <span class="sec-title">Recent Articles</span>
        </div>
        <div class="news-rows">
          <?php
          $recent = get_posts(['numberposts' => 5, 'post_status' => 'publish']);
          foreach ($recent as $p):
          ?>
          <article class="news-row">
            <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="news-row-img" tabindex="-1" aria-hidden="true">
              <?php $thumb = get_the_post_thumbnail_url($p->ID, 'aiv-thumb');
              if ($thumb): ?>
              <img src="<?php echo esc_url($thumb); ?>" alt="" loading="lazy">
              <?php else: ?>
              <div class="news-row-img-ph"><?php echo aiv_icon('newspaper'); ?></div>
              <?php endif; ?>
            </a>
            <div class="news-row-body">
              <h3 class="news-row-title">
                <a href="<?php echo esc_url(get_permalink($p->ID)); ?>">
                  <?php echo esc_html(get_the_title($p->ID)); ?>
                </a>
              </h3>
              <div class="meta">
                <span class="meta-i"><?php echo aiv_icon('calendar'); ?> <?php echo get_the_date('d M Y', $p->ID); ?></span>
              </div>
            </div>
          </article>
          <?php endforeach; wp_reset_postdata(); ?>
        </div>
      </div>

    </div>

  </div>

  <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
