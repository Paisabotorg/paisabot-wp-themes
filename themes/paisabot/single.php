<?php get_header(); ?>

<?php while (have_posts()): the_post(); ?>

<article <?php post_class('single-article'); ?> itemscope itemtype="https://schema.org/NewsArticle">

  <!-- ── ARTICLE HEADER ──────────────────────────────────────────────────── -->
  <header class="art-head">
    <div class="art-head-inner">

      <?php aiv_cat(); ?>

      <h1 class="art-title" itemprop="headline"><?php the_title(); ?></h1>

      <div class="art-meta-row">
        <div class="art-meta">
          <span class="meta-i" itemprop="author" itemscope itemtype="https://schema.org/Person">
            <?php echo aiv_icon('user'); ?>
            <span itemprop="name"><?php the_author(); ?></span>
          </span>
          <span class="meta-i">
            <?php echo aiv_icon('calendar'); ?>
            <time datetime="<?php echo get_the_date('c'); ?>" itemprop="datePublished">
              <?php echo get_the_date(); ?>
            </time>
          </span>
          <span class="meta-i">
            <?php echo aiv_icon('clock'); ?>
            <?php echo aiv_read_time(); ?> read
          </span>
          <?php $cats = get_the_category(); if ($cats): ?>
          <span class="meta-i">
            <?php echo aiv_icon('tag'); ?>
            <a href="<?php echo esc_url(get_category_link($cats[0]->term_id)); ?>"><?php echo esc_html($cats[0]->name); ?></a>
          </span>
          <?php endif; ?>
        </div>

        <div class="art-share">
          <span class="share-label"><?php echo aiv_icon('i-share'); ?> Share</span>
          <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="share-btn share-fb" aria-label="Share on Facebook">
            <?php echo aiv_icon('i-fb'); ?>
          </a>
          <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener" class="share-btn share-tw" aria-label="Share on Twitter">
            <?php echo aiv_icon('i-tw'); ?>
          </a>
          <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>" target="_blank" rel="noopener" class="share-btn share-wa" aria-label="Share on WhatsApp">
            <?php echo aiv_icon('i-wa'); ?>
          </a>
        </div>
      </div>

    </div>
  </header>

  <!-- ── FEATURED IMAGE ──────────────────────────────────────────────────── -->
  <?php if (has_post_thumbnail()): ?>
  <div class="art-hero-img" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
    <?php the_post_thumbnail('aiv-hero', ['alt' => get_the_title(), 'itemprop' => 'url']); ?>
    <?php $caption = get_the_post_thumbnail_caption(); if ($caption): ?>
    <figcaption class="art-img-caption"><?php echo esc_html($caption); ?></figcaption>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <!-- ── ARTICLE BODY + SIDEBAR ──────────────────────────────────────────── -->
  <div class="art-layout">

    <div class="art-body" itemprop="articleBody">

      <!-- Excerpt lead -->
      <?php if (has_excerpt()): ?>
      <p class="art-lead"><?php the_excerpt(); ?></p>
      <?php endif; ?>

      <?php the_content(); ?>

      <?php
      wp_link_pages([
        'before'      => '<div class="page-links"><span class="page-links-title">Pages:</span>',
        'after'       => '</div>',
        'link_before' => '<span>',
        'link_after'  => '</span>',
      ]);
      ?>

      <!-- Tags -->
      <?php $tags = get_the_tags(); if ($tags): ?>
      <div class="art-tags">
        <?php echo aiv_icon('tag'); ?>
        <?php foreach ($tags as $tag): ?>
        <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="art-tag-pill"><?php echo esc_html($tag->name); ?></a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- Bottom share -->
      <div class="art-share art-share-bottom">
        <span class="share-label"><?php echo aiv_icon('i-share'); ?> Share this article</span>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="share-btn share-fb" aria-label="Share on Facebook">
          <?php echo aiv_icon('i-fb'); ?>
        </a>
        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener" class="share-btn share-tw" aria-label="Share on Twitter">
          <?php echo aiv_icon('i-tw'); ?>
        </a>
        <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>" target="_blank" rel="noopener" class="share-btn share-wa" aria-label="Share on WhatsApp">
          <?php echo aiv_icon('i-wa'); ?>
        </a>
      </div>

    </div>

    <?php get_sidebar(); ?>
  </div>

  <!-- ── AUTHOR BOX ───────────────────────────────────────────────────────── -->
  <div class="author-box">
    <?php echo get_avatar(get_the_author_meta('email'), 72, '', get_the_author(), ['class' => 'author-avatar']); ?>
    <div class="author-info">
      <div class="author-name"><?php the_author(); ?></div>
      <?php $bio = get_the_author_meta('description'); if ($bio): ?>
      <p class="author-bio"><?php echo esc_html($bio); ?></p>
      <?php endif; ?>
    </div>
  </div>

</article>

<!-- ── POST NAVIGATION ──────────────────────────────────────────────────── -->
<nav class="post-nav" aria-label="Article navigation">
  <div class="post-nav-prev">
    <?php $prev = get_previous_post(); if ($prev): ?>
    <a href="<?php echo esc_url(get_permalink($prev->ID)); ?>">
      <span class="post-nav-label"><?php echo aiv_icon('i-arrow-r', 'flip'); ?> Previous</span>
      <span class="post-nav-title"><?php echo esc_html(get_the_title($prev->ID)); ?></span>
    </a>
    <?php endif; ?>
  </div>
  <div class="post-nav-next">
    <?php $next = get_next_post(); if ($next): ?>
    <a href="<?php echo esc_url(get_permalink($next->ID)); ?>">
      <span class="post-nav-label">Next <?php echo aiv_icon('i-arrow-r'); ?></span>
      <span class="post-nav-title"><?php echo esc_html(get_the_title($next->ID)); ?></span>
    </a>
    <?php endif; ?>
  </div>
</nav>

<!-- ── RELATED ARTICLES ──────────────────────────────────────────────────── -->
<?php
$cats   = get_the_category();
$cat_id = $cats ? $cats[0]->term_id : 0;
$related = new WP_Query([
  'posts_per_page'      => 4,
  'category__in'        => [$cat_id],
  'post__not_in'        => [get_the_ID()],
  'post_status'         => 'publish',
  'ignore_sticky_posts' => 1,
]);
if ($related->have_posts()):
?>
<section class="related-section" aria-label="Related articles">
  <div class="sec-hd">
    <span class="sec-title">Related Articles</span>
  </div>
  <div class="card-strip">
    <?php while ($related->have_posts()): $related->the_post(); ?>
    <article <?php post_class('post-card'); ?>>
      <a href="<?php the_permalink(); ?>" class="card-img" tabindex="-1" aria-hidden="true">
        <?php if (has_post_thumbnail()):
          the_post_thumbnail('aiv-card', ['alt' => get_the_title(), 'loading' => 'lazy']);
        else: echo aiv_placeholder(); endif; ?>
      </a>
      <div class="card-body">
        <?php aiv_cat(); ?>
        <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <div class="card-foot">
          <div class="meta">
            <span class="meta-i"><?php echo aiv_icon('calendar'); ?> <?php echo get_the_date(); ?></span>
            <span class="meta-i"><?php echo aiv_icon('clock'); ?> <?php echo aiv_read_time(); ?></span>
          </div>
        </div>
      </div>
    </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
