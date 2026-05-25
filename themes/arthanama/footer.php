</main><!-- #main -->

<!-- ── SITE FOOTER ──────────────────────────────────────────────────────── -->
<footer class="site-footer" role="contentinfo">
  <div class="footer-main">
    <div class="container">
      <div class="footer-grid">

        <!-- Brand column -->
        <div class="footer-brand">
          <span class="logo-name">Artha<span>Nama</span></span>
          <p class="footer-desc">
            <?php echo esc_html(get_bloginfo('description') ?: __('Your trusted source for economic and financial news in your language.','arthanama')); ?>
          </p>
          <div class="footer-social">
            <a href="#" aria-label="Facebook"><?php echo arthanama_icon('facebook'); ?></a>
            <a href="#" aria-label="Twitter / X"><?php echo arthanama_icon('twitter'); ?></a>
            <a href="#" aria-label="WhatsApp"><?php echo arthanama_icon('whatsapp'); ?></a>
            <a href="<?php bloginfo('rss2_url'); ?>" aria-label="RSS"><?php echo arthanama_icon('rss'); ?></a>
          </div>
        </div>

        <!-- Categories column -->
        <div>
          <h3 class="footer-col-title"><?php esc_html_e('Categories','arthanama'); ?></h3>
          <nav class="footer-links" aria-label="<?php esc_attr_e('Footer categories','arthanama'); ?>">
            <?php
            $cats = get_categories(['number' => 8, 'hide_empty' => true]);
            foreach ($cats as $cat):
            ?>
            <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>">
              <?php echo arthanama_icon('arrow-right'); ?>
              <?php echo esc_html($cat->name); ?>
            </a>
            <?php endforeach; ?>
          </nav>
        </div>

        <!-- Recent posts column -->
        <div>
          <h3 class="footer-col-title"><?php esc_html_e('Recent Articles','arthanama'); ?></h3>
          <nav class="footer-links" aria-label="<?php esc_attr_e('Recent posts','arthanama'); ?>">
            <?php
            $recent = get_posts(['numberposts' => 5, 'post_status' => 'publish']);
            foreach ($recent as $rp):
            ?>
            <a href="<?php echo esc_url(get_permalink($rp->ID)); ?>">
              <?php echo arthanama_icon('arrow-right'); ?>
              <?php echo esc_html(get_the_title($rp->ID)); ?>
            </a>
            <?php endforeach; ?>
          </nav>
        </div>

        <!-- About / widget column -->
        <div>
          <h3 class="footer-col-title"><?php esc_html_e('About Us','arthanama'); ?></h3>
          <div class="footer-links">
            <?php if (is_active_sidebar('footer-col3')): ?>
              <?php dynamic_sidebar('footer-col3'); ?>
            <?php else: ?>
              <a href="<?php echo esc_url(home_url('/about')); ?>">
                <?php echo arthanama_icon('arrow-right'); ?>
                <?php esc_html_e('About','arthanama'); ?>
              </a>
              <a href="<?php echo esc_url(home_url('/contact')); ?>">
                <?php echo arthanama_icon('arrow-right'); ?>
                <?php esc_html_e('Contact','arthanama'); ?>
              </a>
              <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">
                <?php echo arthanama_icon('arrow-right'); ?>
                <?php esc_html_e('Privacy Policy','arthanama'); ?>
              </a>
              <a href="<?php echo esc_url(home_url('/disclaimer')); ?>">
                <?php echo arthanama_icon('arrow-right'); ?>
                <?php esc_html_e('Disclaimer','arthanama'); ?>
              </a>
            <?php endif; ?>
          </div>
        </div>

      </div><!-- .footer-grid -->
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container">
      <span>
        &copy; <?php echo date('Y'); ?>
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>.
        <?php esc_html_e('All rights reserved.','arthanama'); ?>
      </span>
      <span>
        <?php wp_nav_menu([
          'theme_location' => 'footer',
          'menu_class'     => 'footer-nav-list',
          'container'      => false,
          'depth'          => 1,
          'fallback_cb'    => false,
        ]); ?>
      </span>
    </div>
  </div>
</footer>

<!-- Back to top -->
<button class="back-to-top" id="back-to-top" aria-label="<?php esc_attr_e('Back to top','arthanama'); ?>">
  <?php echo arthanama_icon('arrow-up'); ?>
</button>

<?php wp_footer(); ?>
</body>
</html>
