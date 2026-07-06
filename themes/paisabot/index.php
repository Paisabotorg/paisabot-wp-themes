<?php
/**
 * Homepage composer (and fallback for blog/search/archive).
 * Calls each template-part in order. Reorder to taste, or hide via filter.
 */
get_header();

// News menu → all posts across every category (skip the editorial front).
$pb_news = isset($_GET['pb_view']) && $_GET['pb_view'] === 'news';

if (is_home() && !is_paged() && !$pb_news):
    // ── EDITORIAL HOMEPAGE ──────────────────────────────────────────────
    get_template_part('template-parts/front-page');
    get_template_part('template-parts/ads/leaderboard');
    get_template_part('template-parts/markets-pulse');

    // Wide sponsor ad — between Markets Pulse and The Briefing
    get_template_part('template-parts/ads/sponsor-leaderboard', null, [
      'slot'     => 'ad-leaderboard-pulse',
      'sponsor'  => 'HDFC Securities',
      'headline' => 'Trade Equity, F&O & Commodity — all on one screen.',
      'body'     => '₹0 brokerage on intraday equity. Free research reports. SEBI-registered since 2000.',
      'cta'      => 'Open account',
      'cta_url'  => '#',
      'kind'     => 'sponsor',
    ]);

    get_template_part('template-parts/briefing');
    get_template_part('template-parts/banking-band');
    get_template_part('template-parts/ads/native');
    get_template_part('template-parts/index-stories');

    // Wide house ad — above Voices
    get_template_part('template-parts/ads/sponsor-leaderboard', null, [
      'slot'     => 'ad-leaderboard-voices-top',
      'sponsor'  => 'PaisaBot Daily Brief',
      'headline' => "The day's markets — read in three minutes, every morning.",
      'body'     => 'Sent at 7am IST. Written by the same desk that publishes paisabot.com. 128,000 readers.',
      'cta'      => 'Subscribe free',
      'cta_url'  => home_url('/subscribe'),
      'kind'     => 'house',
    ]);

    get_template_part('template-parts/voices-band');

    // Wide sponsor ad — below Voices
    get_template_part('template-parts/ads/sponsor-leaderboard', null, [
      'slot'     => 'ad-leaderboard-voices',
      'sponsor'  => 'Bajaj Allianz Life Insurance',
      'headline' => 'Build a ₹1 Crore corpus — start with ₹2,500 a month.',
      'body'     => "Tax-free returns under Sec 10(10D). Long-term wealth creation backed by India's largest private insurer. Trusted by 8 crore Indians.",
      'cta'      => 'Check your plan',
      'cta_url'  => '#',
      'kind'     => 'sponsor',
    ]);

    get_template_part('template-parts/global-band');

else:
    // ── ARCHIVE / SEARCH / FALLBACK ─────────────────────────────────────
    ?>
    <div class="home-grid">
      <div>

        <?php if ($pb_news): ?>
        <div class="sec-hd">
          <span class="sec-title sec-title-large">Latest News</span>
        </div>
        <?php elseif (is_category()): ?>
        <div class="sec-hd">
          <span class="sec-title sec-title-large"><?php single_cat_title(); ?></span>
        </div>
        <?php elseif (is_search()): ?>
        <div class="sec-hd">
          <span class="sec-title sec-title-large">Results for &ldquo;<?php echo esc_html(get_search_query()); ?>&rdquo;</span>
        </div>
        <?php else: ?>
        <div class="sec-hd">
          <span class="sec-title sec-title-large"><?php the_archive_title(); ?></span>
        </div>
        <?php endif; ?>

        <?php if (have_posts()): ?>
        <div class="news-rows">
          <?php while (have_posts()): the_post(); ?>
          <article <?php post_class('news-row'); ?>>
            <a href="<?php the_permalink(); ?>" class="news-row-img" tabindex="-1" aria-hidden="true">
              <?php if (has_post_thumbnail()):
                the_post_thumbnail('aiv-thumb', ['alt'=>'','loading'=>'lazy']);
              endif; ?>
            </a>
            <div class="news-row-body">
              <?php aiv_cat(); ?>
              <h3 class="news-row-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <p class="news-row-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 22, '…'); ?></p>
              <div class="meta">
                <span class="meta-i"><?php echo aiv_icon('calendar'); ?> <?php echo get_the_date(); ?></span>
                <span class="meta-i"><?php echo aiv_icon('clock'); ?> <?php echo aiv_read_time(); ?></span>
                <span class="meta-i"><?php echo aiv_icon('user'); ?> <?php the_author(); ?></span>
              </div>
            </div>
          </article>
          <?php endwhile; ?>
        </div>

        <div class="archive-pagination" style="margin-top:24px">
          <?php echo paginate_links([
            'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
            'total'     => $GLOBALS['wp_query']->max_num_pages,
            'current'   => max(1, get_query_var('paged')),
            'mid_size'  => 2,
            'prev_text' => '&larr;',
            'next_text' => '&rarr;',
            'type'      => 'list',
          ]); ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
          <?php echo aiv_icon('newspaper'); ?>
          <p>No articles found. Check back soon.</p>
        </div>
        <?php endif; ?>

      </div>
      <?php get_sidebar(); ?>
    </div>
    <?php
endif;

get_footer();
