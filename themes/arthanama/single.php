<?php get_header(); ?>

<div class="container">
  <div class="single-layout">

    <!-- ── ARTICLE ──────────────────────────────────────────────────────── -->
    <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
      <?php while (have_posts()): the_post(); ?>

      <header class="post-header">
        <?php arthanama_cat_badge(); ?>
        <h1 class="post-title"><?php the_title(); ?></h1>
        <div class="post-meta">
          <span class="post-meta-item">
            <?php echo arthanama_icon('user'); ?>
            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
              <?php the_author(); ?>
            </a>
          </span>
          <span class="post-meta-item">
            <?php echo arthanama_icon('calendar'); ?>
            <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
          </span>
          <span class="post-meta-item">
            <?php echo arthanama_icon('clock'); ?>
            <?php echo arthanama_reading_time(); ?>
          </span>
        </div>
      </header>

      <?php if (has_post_thumbnail()): ?>
      <figure class="post-featured-image">
        <?php the_post_thumbnail('arthanama-hero', ['alt' => get_the_title(), 'loading' => 'eager']); ?>
        <?php if (get_the_post_thumbnail_caption()): ?>
          <figcaption><?php echo esc_html(get_the_post_thumbnail_caption()); ?></figcaption>
        <?php endif; ?>
      </figure>
      <?php endif; ?>

      <div class="post-content">
        <?php the_content(); ?>
        <?php wp_link_pages(['before' => '<div class="page-links">', 'after' => '</div>']); ?>
      </div>

      <!-- Tags -->
      <?php
      $tags = get_the_tags();
      if ($tags):
      ?>
      <div class="post-tags">
        <span class="post-tags-label"><?php echo arthanama_icon('tag'); ?> <?php esc_html_e('Tags','arthanama'); ?>:</span>
        <?php foreach ($tags as $tag): ?>
        <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="tag-chip">
          <?php echo esc_html($tag->name); ?>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- Share Bar -->
      <div class="share-bar">
        <span class="share-label"><?php echo arthanama_icon('share'); ?> <?php esc_html_e('Share','arthanama'); ?></span>
        <?php
        $post_url   = urlencode(get_permalink());
        $post_title = urlencode(get_the_title());
        ?>
        <a class="share-btn facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Share on Facebook','arthanama'); ?>">
          <?php echo arthanama_icon('facebook'); ?>
        </a>
        <a class="share-btn twitter" href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Share on Twitter','arthanama'); ?>">
          <?php echo arthanama_icon('twitter'); ?>
        </a>
        <a class="share-btn whatsapp" href="https://wa.me/?text=<?php echo $post_title; ?>%20<?php echo $post_url; ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Share on WhatsApp','arthanama'); ?>">
          <?php echo arthanama_icon('whatsapp'); ?>
        </a>
      </div>

      <?php endwhile; ?>
    </article>

    <!-- ── SIDEBAR ───────────────────────────────────────────────────────── -->
    <?php get_sidebar(); ?>

  </div><!-- .single-layout -->

  <!-- ── RELATED POSTS ─────────────────────────────────────────────────── -->
  <?php
  $cats = get_the_category();
  if ($cats):
    $related = new WP_Query([
      'category__in'   => [$cats[0]->term_id],
      'post__not_in'   => [get_the_ID()],
      'posts_per_page' => 3,
      'orderby'        => 'rand',
      'post_status'    => 'publish',
    ]);
    if ($related->have_posts()):
  ?>
  <section class="related-posts">
    <div class="section-header">
      <h2 class="section-title"><?php esc_html_e('Related Articles','arthanama'); ?></h2>
    </div>
    <div class="related-grid">
      <?php while ($related->have_posts()): $related->the_post(); ?>
      <article <?php post_class('post-card'); ?>>
        <a href="<?php the_permalink(); ?>" class="post-card-image" tabindex="-1">
          <?php if (has_post_thumbnail()):
            the_post_thumbnail('arthanama-card', ['alt' => get_the_title(), 'loading' => 'lazy']);
          else: echo arthanama_placeholder(); endif; ?>
        </a>
        <div class="post-card-body">
          <?php arthanama_cat_badge(); ?>
          <h3 class="post-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <div class="post-card-footer">
            <div class="post-meta">
              <span class="post-meta-item"><?php echo arthanama_icon('calendar'); ?> <?php echo get_the_date(); ?></span>
              <span class="post-meta-item"><?php echo arthanama_icon('clock'); ?> <?php echo arthanama_reading_time(); ?></span>
            </div>
          </div>
        </div>
      </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </section>
  <?php endif; endif; ?>

</div><!-- .container -->

<?php get_footer(); ?>
