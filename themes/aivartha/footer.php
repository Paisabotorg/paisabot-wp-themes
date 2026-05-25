  </div><!-- /.wrap -->
</main><!-- /.site-body -->

<!-- ▸ NEWSLETTER BAND (full-bleed, home only) -->
<?php if (is_home() || is_front_page()): get_template_part('template-parts/newsletter-band'); endif; ?>

<!-- ▸ FOOTER -->
<footer class="site-footer">
  <div class="footer-top">
    <div class="wrap">
      <div class="footer-cols">

        <div class="footer-logo-block">
          <span class="footer-logo-name">AI&nbsp;<em>Vartha</em></span>
          <p class="footer-desc"><?php echo esc_html(get_bloginfo('description') ?: 'Trusted economic and financial news in your language, powered by AI translation and an editorial team of veteran journalists.'); ?></p>
          <div class="footer-socials">
            <a href="#" aria-label="Facebook"><?php echo aiv_icon('i-fb'); ?></a>
            <a href="#" aria-label="Twitter"><?php echo aiv_icon('i-tw'); ?></a>
            <a href="#" aria-label="WhatsApp"><?php echo aiv_icon('i-wa'); ?></a>
            <a href="<?php bloginfo('rss2_url'); ?>" aria-label="RSS Feed"><?php echo aiv_icon('i-rss'); ?></a>
          </div>
        </div>

        <div>
          <p class="footer-col-title">Sections</p>
          <nav class="footer-links">
            <?php
            foreach (['Markets','Policy','Banking','Economy','Global','Foreign Policy','Technology','Opinion'] as $name):
              $cat = get_category_by_slug(sanitize_title($name));
              $url = $cat ? get_category_link($cat->term_id) : home_url('/category/' . sanitize_title($name));
            ?>
            <a href="<?php echo esc_url($url); ?>"><?php echo aiv_icon('i-arrow-r'); ?> <?php echo esc_html($name); ?></a>
            <?php endforeach; ?>
          </nav>
        </div>

        <div>
          <p class="footer-col-title">Products</p>
          <nav class="footer-links">
            <a href="<?php echo esc_url(home_url('/subscribe')); ?>"><?php echo aiv_icon('i-arrow-r'); ?> Newsletter</a>
            <a href="<?php echo esc_url(home_url('/markets-pro')); ?>"><?php echo aiv_icon('i-arrow-r'); ?> Markets Pro</a>
            <a href="<?php echo esc_url(home_url('/stock-analysis')); ?>"><?php echo aiv_icon('i-arrow-r'); ?> Stock Analysis</a>
          </nav>
        </div>

        <div>
          <p class="footer-col-title">About</p>
          <nav class="footer-links">
            <?php if (is_active_sidebar('footer-4')): dynamic_sidebar('footer-4');
            else: ?>
            <a href="<?php echo esc_url(home_url('/about')); ?>"><?php echo aiv_icon('i-arrow-r'); ?> About AI Vartha</a>
            <a href="<?php echo esc_url(home_url('/editorial-policy')); ?>"><?php echo aiv_icon('i-arrow-r'); ?> Editorial Policy</a>
            <a href="<?php echo esc_url(home_url('/contact')); ?>"><?php echo aiv_icon('i-arrow-r'); ?> Contact</a>
            <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>"><?php echo aiv_icon('i-arrow-r'); ?> Privacy Policy</a>
            <a href="<?php echo esc_url(home_url('/disclaimer')); ?>"><?php echo aiv_icon('i-arrow-r'); ?> Disclaimer</a>
            <?php endif; ?>
          </nav>
        </div>

      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="wrap">
      <span>&copy; <?php echo date('Y'); ?> <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>. All rights reserved.</span>
      <span>Available in
        <span style="color:var(--amber-2);font-weight:700">EN · ML · HI · TE</span>
        · Powered by IndicTrans2
      </span>
    </div>
  </div>
</footer>

<!-- ▸ STICKY BOTTOM AD (loaded from widget area) -->
<?php if (is_active_sidebar('ad-sticky')): ?>
<div class="sticky-ad is-visible" id="sticky-ad">
  <div class="wrap">
    <div class="sticky-ad-content"><?php dynamic_sidebar('ad-sticky'); ?></div>
    <button class="sticky-ad-close" id="sticky-ad-close" aria-label="Close ad"><?php echo aiv_icon('i-close'); ?></button>
  </div>
</div>
<?php endif; ?>

<button class="back-top" id="back-top" aria-label="Back to top"><?php echo aiv_icon('i-arrow-up'); ?></button>

<?php wp_footer(); ?>
</body>
</html>
