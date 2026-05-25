<?php get_header(); ?>

<div class="container">

<?php if (is_home() && !is_paged()):
  // ── HERO: Featured (sticky or latest) post ──────────────────────────────
  $hero_args = [
    'posts_per_page' => 1,
    'post_status'    => 'publish',
    'meta_key'       => '_featured',
    'meta_value'     => '1',
  ];
  $hero_query = new WP_Query($hero_args);
  if (!$hero_query->have_posts()) {
    $hero_query = new WP_Query(['posts_per_page' => 1, 'post_status' => 'publish']);
  }

  if ($hero_query->have_posts()):
    $hero_query->the_post();
    $hero_img = get_the_post_thumbnail_url(null, 'arthanama-hero');
?>
  <article class="hero" style="<?php echo $hero_img ? 'min-height:480px' : ''; ?>">
    <?php if ($hero_img): ?>
      <div class="hero-image" style="background-image:url('<?php echo esc_url($hero_img); ?>')" role="img" aria-label="<?php the_title_attribute(); ?>"></div>
      <div class="hero-overlay"></div>
    <?php else: ?>
      <div style="position:absolute;inset:0;background:linear-gradient(135deg,#0C1E35 0%,#1A3254 100%)"></div>
    <?php endif; ?>

    <div class="hero-content">
      <?php arthanama_cat_badge(); ?>
      <h2 class="hero-title">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </h2>
      <div class="post-meta">
        <span class="post-meta-item"><?php echo arthanama_icon('calendar'); ?> <?php echo get_the_date(); ?></span>
        <span class="post-meta-item"><?php echo arthanama_icon('clock'); ?> <?php echo arthanama_reading_time(); ?></span>
        <span class="post-meta-item"><?php echo arthanama_icon('user'); ?> <?php the_author(); ?></span>
      </div>
      <p class="hero-excerpt"><?php echo get_the_excerpt(); ?></p>
      <a href="<?php the_permalink(); ?>" class="hero-read-more">
        <?php esc_html_e('Read More','arthanama'); ?>
        <?php echo arthanama_icon('arrow-right'); ?>
      </a>
    </div>
  </article>
<?php
  wp_reset_postdata();
  endif;

  // ── TOP 3 CARDS ─────────────────────────────────────────────────────────
  $top3 = new WP_Query(['posts_per_page' => 3, 'post_status' => 'publish', 'offset' => 1]);
  if ($top3->have_posts()):
?>
  <div class="section-header">
    <h2 class="section-title"><?php esc_html_e('Latest News','arthanama'); ?></h2>
    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="section-view-all">
      <?php esc_html_e('View All','arthanama'); ?> <?php echo arthanama_icon('arrow-right'); ?>
    </a>
  </div>
  <div class="card-grid">
    <?php while ($top3->have_posts()): $top3->the_post(); ?>
    <article <?php post_class('post-card'); ?>>
      <a href="<?php the_permalink(); ?>" class="post-card-image" aria-hidden="true" tabindex="-1">
        <?php if (has_post_thumbnail()):
          the_post_thumbnail('arthanama-card', ['alt' => get_the_title(), 'loading' => 'lazy']);
        else: echo arthanama_placeholder(); endif; ?>
      </a>
      <div class="post-card-body">
        <?php arthanama_cat_badge(); ?>
        <h3 class="post-card-title">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        <p class="post-card-excerpt"><?php echo get_the_excerpt(); ?></p>
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
<?php endif; endif; ?>

<!-- ── TWO-COL: More News + Sidebar ──────────────────────────────────────── -->
<div class="home-layout">
  <div>
    <div class="section-header">
      <h2 class="section-title">
        <?php if (is_category()): single_cat_title();
        elseif (is_search()): printf(esc_html__('Search: %s','arthanama'), '<span>'.get_search_query().'</span>');
        elseif (is_archive()): the_archive_title();
        else: esc_html_e('More Stories','arthanama');
        endif; ?>
      </h2>
    </div>

    <?php
    $offset = (is_home() && !is_paged()) ? 4 : 0;
    $paged  = max(1, get_query_var('paged'));
    $args   = array_merge(
      is_home() ? ['posts_per_page' => 8, 'offset' => $offset, 'paged' => $paged] : [],
      is_category() ? ['cat' => get_queried_object_id()] : [],
      is_search()   ? ['s' => get_search_query()] : [],
      ['post_status' => 'publish']
    );
    $query = is_home() ? new WP_Query($args) : $GLOBALS['wp_query'];
    ?>

    <?php if ($query->have_posts()): ?>
    <div class="news-list">
      <?php while ($query->have_posts()): $query->the_post(); ?>
      <article <?php post_class('news-item'); ?>>
        <a href="<?php the_permalink(); ?>" class="news-item-image" aria-hidden="true" tabindex="-1">
          <?php if (has_post_thumbnail()):
            the_post_thumbnail('arthanama-thumb', ['alt' => '', 'loading' => 'lazy']);
          else: ?>
            <div style="width:100%;height:100%;background:var(--navy-light);display:flex;align-items:center;justify-content:center">
              <?php echo arthanama_icon('newspaper'); ?>
            </div>
          <?php endif; ?>
        </a>
        <div class="news-item-body">
          <?php arthanama_cat_badge(); ?>
          <h3 class="news-item-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h3>
          <div class="post-meta">
            <span class="post-meta-item"><?php echo arthanama_icon('calendar'); ?> <?php echo get_the_date(); ?></span>
            <span class="post-meta-item"><?php echo arthanama_icon('clock'); ?> <?php echo arthanama_reading_time(); ?></span>
          </div>
        </div>
      </article>
      <?php endwhile; if (is_home()) wp_reset_postdata(); ?>
    </div>

    <!-- Pagination -->
    <div class="archive-pagination">
      <?php
      $total = is_home() ? $query->max_num_pages : $GLOBALS['wp_query']->max_num_pages;
      echo paginate_links([
        'total'     => $total,
        'current'   => $paged,
        'prev_text' => '&larr;',
        'next_text' => '&rarr;',
        'type'      => 'list',
        'mid_size'  => 2,
      ]);
      ?>
    </div>

    <?php else: ?>
    <div class="no-posts">
      <?php echo arthanama_icon('newspaper'); ?>
      <p><?php esc_html_e('No articles found. Check back soon.','arthanama'); ?></p>
    </div>
    <?php endif; ?>
  </div>

  <!-- Sidebar -->
  <?php get_sidebar(); ?>
</div>

</div><!-- .container -->

<?php get_footer(); ?>
