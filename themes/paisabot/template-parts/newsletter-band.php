<section class="newsletter-band">
  <div class="wrap">
    <div>
      <span class="nb-eyebrow">The Brief · Free Newsletter</span>
      <h2>The five things that <em>matter</em> in Indian economy — every morning at 7am.</h2>
      <p>Curated by our newsroom. No paid placements, no clickbait. Markets, policy, banking, and a single contrarian opinion — read in under five minutes with your first coffee.</p>
      <div class="nb-stats">
        <div class="nb-stat">
          <div class="num">2.4M</div>
          <div class="lbl"><?php esc_html_e('Active Subscribers', 'aivartha'); ?></div>
        </div>
        <div class="nb-stat">
          <div class="num">48%</div>
          <div class="lbl"><?php esc_html_e('Open Rate', 'aivartha'); ?></div>
        </div>
        <div class="nb-stat">
          <div class="num">4</div>
          <div class="lbl"><?php esc_html_e('Languages', 'aivartha'); ?></div>
        </div>
      </div>
    </div>
    <div class="nb-form">
      <h3><?php esc_html_e('Subscribe Free', 'aivartha'); ?></h3>
      <form action="<?php echo esc_url(home_url('/?aiv_subscribe=1')); ?>" method="post">
        <?php wp_nonce_field('aiv_subscribe'); ?>
        <input class="nb-input" type="email" name="email" required placeholder="<?php esc_attr_e('Your email address', 'aivartha'); ?>">
        <div class="nb-select">
          <label class="nb-option active"><input type="radio" name="freq" value="daily" checked hidden> Daily</label>
          <label class="nb-option"><input type="radio" name="freq" value="markets" hidden> Markets</label>
          <label class="nb-option"><input type="radio" name="freq" value="weekend" hidden> Weekend</label>
        </div>
        <button type="submit" class="nb-cta"><?php esc_html_e('Send me The Brief', 'aivartha'); ?> →</button>
        <p class="nb-fineprint"><?php esc_html_e('We never spam · Unsubscribe in one click', 'aivartha'); ?></p>
      </form>
    </div>
  </div>
</section>
