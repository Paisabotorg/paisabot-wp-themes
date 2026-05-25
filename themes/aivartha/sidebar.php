<aside class="site-sidebar" role="complementary" aria-label="Sidebar">

  <?php if (is_active_sidebar('sidebar-1')): ?>
    <?php dynamic_sidebar('sidebar-1'); ?>
  <?php else: ?>

    <!-- Default sidebar widgets when none are configured -->

    <!-- About -->
    <section class="widget widget-about">
      <div class="widget-hd">About AI Vartha</div>
      <div class="widget-body">
        <p><?php echo esc_html(get_bloginfo('description') ?: 'Trusted economic and financial news powered by AI — delivered in your language.'); ?></p>
        <div class="sidebar-socials">
          <a href="#" aria-label="Facebook"><?php echo aiv_icon('i-fb'); ?></a>
          <a href="#" aria-label="Twitter"><?php echo aiv_icon('i-tw'); ?></a>
          <a href="#" aria-label="WhatsApp"><?php echo aiv_icon('i-wa'); ?></a>
          <a href="<?php bloginfo('rss2_url'); ?>" aria-label="RSS"><?php echo aiv_icon('i-rss'); ?></a>
        </div>
      </div>
    </section>

    <!-- Trending / Most Recent -->
    <section class="widget widget-trending">
      <div class="widget-hd"><?php echo aiv_icon('i-trend-up'); ?> Trending Now</div>
      <div class="widget-body">
        <?php
        $trending = get_posts(['numberposts' => 6, 'post_status' => 'publish']);
        foreach ($trending as $i => $p):
        ?>
        <div class="sidebar-post">
          <span class="sp-num"><?php echo $i + 1; ?></span>
          <div class="sp-body">
            <?php
            $thumb = get_the_post_thumbnail_url($p->ID, 'aiv-mini');
            if ($thumb): ?>
            <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="sp-img">
              <img src="<?php echo esc_url($thumb); ?>" alt="" loading="lazy" width="70" height="53">
            </a>
            <?php endif; ?>
            <div class="sp-text">
              <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="sp-title">
                <?php echo esc_html(wp_trim_words(get_the_title($p->ID), 9, '…')); ?>
              </a>
              <div class="meta">
                <span class="meta-i"><?php echo aiv_icon('calendar'); ?> <?php echo get_the_date('d M Y', $p->ID); ?></span>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; wp_reset_postdata(); ?>
      </div>
    </section>

    <!-- Categories -->
    <section class="widget widget-cats">
      <div class="widget-hd">Sections</div>
      <div class="widget-body">
        <ul class="cat-list">
          <?php
          $cats = get_categories(['hide_empty' => true]);
          foreach ($cats as $cat):
          ?>
          <li>
            <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>">
              <?php echo aiv_icon('i-arrow-r'); ?>
              <span><?php echo esc_html($cat->name); ?></span>
              <span class="cat-count"><?php echo intval($cat->count); ?></span>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>

    <!-- Market Snapshot -->
    <section class="widget widget-market">
      <div class="widget-hd"><?php echo aiv_icon('i-trend-up'); ?> Market Snapshot</div>
      <div class="widget-body">
        <table class="mkt-table">
          <?php foreach (aiv_market_items() as $m): ?>
          <tr>
            <td class="mkt-name"><?php echo esc_html($m['n']); ?></td>
            <td class="mkt-price"><?php echo esc_html($m['p']); ?></td>
            <td class="<?php echo $m['up'] ? 't-up' : 't-dn'; ?>">
              <?php echo aiv_icon($m['up'] ? 'i-trend-up' : 'i-trend-dn'); ?>
              <?php echo esc_html($m['c']); ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </table>
      </div>
    </section>

    <!-- Newsletter -->
    <section class="widget widget-newsletter">
      <div class="widget-hd">Stay Informed</div>
      <div class="widget-body">
        <p>Get the latest economic insights delivered to your inbox.</p>
        <form class="nl-form" onsubmit="return false;">
          <input type="email" class="nl-input" placeholder="Your email address" autocomplete="email">
          <button type="submit" class="nl-btn">
            Subscribe <?php echo aiv_icon('i-arrow-r'); ?>
          </button>
        </form>
      </div>
    </section>

    <!-- Skyscraper ad (only renders if widget area populated) -->
    <?php get_template_part('template-parts/ads/skyscraper'); ?>

  <?php endif; ?>

</aside>
