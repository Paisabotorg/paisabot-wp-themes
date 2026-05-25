<?php get_header(); ?>

<div class="archive-header">
  <div class="container">
    <span class="archive-subtitle">
      <?php if (is_category()): esc_html_e('Category','arthanama');
      elseif (is_tag()): esc_html_e('Tag','arthanama');
      elseif (is_date()): esc_html_e('Archive','arthanama');
      else: esc_html_e('Articles','arthanama'); endif; ?>
    </span>
    <h1 class="archive-title">
      <?php if (is_category()) single_cat_title();
      elseif (is_tag())      single_tag_title();
      elseif (is_date())     echo get_the_date('F Y');
      else                   the_archive_title(); ?>
    </h1>
    <?php if (category_description()): ?>
    <p style="color:rgba(255,255,255,.55);font-size:.9rem;max-width:560px"><?php echo category_description(); ?></p>
    <?php endif; ?>
    <span class="archive-count">
      <?php printf(_n('%d article','%d articles', $wp_query->found_posts, 'arthanama'), $wp_query->found_posts); ?>
    </span>
  </div>
</div>

<div class="container">
  <div class="archive-layout">
    <div>
      <?php if (have_posts()): ?>
        <div class="archive-grid">
          <?php while (have_posts()): the_post(); ?>
          <article <?php post_class('post-card'); ?>>
            <a href="<?php the_permalink(); ?>" class="post-card-image" tabindex="-1">
              <?php if (has_post_thumbnail()):
                the_post_thumbnail('arthanama-card', ['alt' => get_the_title(), 'loading' => 'lazy']);
              else: echo arthanama_placeholder(); endif; ?>
            </a>
            <div class="post-card-body">
              <?php arthanama_cat_badge(); ?>
              <h2 class="post-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
              <p class="post-card-excerpt"><?php echo get_the_excerpt(); ?></p>
              <div class="post-card-footer">
                <div class="post-meta">
                  <span class="post-meta-item"><?php echo arthanama_icon('calendar'); ?> <?php echo get_the_date(); ?></span>
                  <span class="post-meta-item"><?php echo arthanama_icon('clock'); ?> <?php echo arthanama_reading_time(); ?></span>
                </div>
              </div>
            </div>
          </article>
          <?php endwhile; ?>
        </div>
        <div class="archive-pagination">
          <?php the_posts_pagination(['mid_size' => 2, 'prev_text' => '&larr;', 'next_text' => '&rarr;']); ?>
        </div>
      <?php else: ?>
        <div class="no-posts">
          <?php echo arthanama_icon('newspaper'); ?>
          <p><?php esc_html_e('No articles found in this section yet.','arthanama'); ?></p>
        </div>
      <?php endif; ?>
    </div>
    <?php get_sidebar(); ?>
  </div>
</div>

<?php get_footer(); ?>
