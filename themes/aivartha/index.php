<?php
/**
 * Homepage composer (and fallback for blog/search/archive).
 * Calls each template-part in order. Reorder to taste, or hide via filter.
 */
get_header();

if (is_home() && !is_paged()):
    // ── EDITORIAL HOMEPAGE ──────────────────────────────────────────────
    get_template_part('template-parts/front-page');
    get_template_part('template-parts/ads/leaderboard');
    get_template_part('template-parts/markets-pulse');
    get_template_part('template-parts/briefing');
    get_template_part('template-parts/banking-band');
    get_template_part('template-parts/ads/native');
    get_template_part('template-parts/index-stories');
    get_template_part('template-parts/voices-band');
    get_template_part('template-parts/global-band');

else:
    // ── ARCHIVE / SEARCH / FALLBACK ─────────────────────────────────────
    ?>
    <div class="home-grid">
      <div>

        <?php if (is_category()): ?>
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
          <?php echo paginate_links(['total'=>$GLOBALS['wp_query']->max_num_pages,'mid_size'=>2,'prev_text'=>'&larr;','next_text'=>'&rarr;','type'=>'list']); ?>
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
