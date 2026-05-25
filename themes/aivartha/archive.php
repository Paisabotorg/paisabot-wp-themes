<?php get_header(); ?>

<div class="home-grid">
  <div>

    <!-- Archive header -->
    <header class="archive-head">
      <?php if (is_category()): ?>
        <?php $cat = get_queried_object(); ?>
        <div class="arc-kicker">Section</div>
        <h1 class="arc-title"><?php single_cat_title(); ?></h1>
        <?php if ($cat->description): ?>
        <p class="arc-desc"><?php echo esc_html($cat->description); ?></p>
        <?php endif; ?>

      <?php elseif (is_tag()): ?>
        <div class="arc-kicker">Tagged</div>
        <h1 class="arc-title"><?php single_tag_title(); ?></h1>

      <?php elseif (is_author()): ?>
        <div class="arc-kicker">Author</div>
        <h1 class="arc-title"><?php the_author(); ?></h1>
        <?php $bio = get_the_author_meta('description'); if ($bio): ?>
        <p class="arc-desc"><?php echo esc_html($bio); ?></p>
        <?php endif; ?>

      <?php elseif (is_date()): ?>
        <div class="arc-kicker">Archive</div>
        <h1 class="arc-title"><?php the_archive_title(); ?></h1>

      <?php elseif (is_search()): ?>
        <div class="arc-kicker">Search Results</div>
        <h1 class="arc-title">&ldquo;<?php echo esc_html(get_search_query()); ?>&rdquo;</h1>
        <p class="arc-desc"><?php printf('%d articles found', $wp_query->found_posts); ?></p>

      <?php else: ?>
        <h1 class="arc-title"><?php the_archive_title(); ?></h1>
      <?php endif; ?>
    </header>

    <?php if (have_posts()): ?>

    <div class="news-rows">
      <?php while (have_posts()): the_post(); ?>
      <article <?php post_class('news-row'); ?>>
        <a href="<?php the_permalink(); ?>" class="news-row-img" tabindex="-1" aria-hidden="true">
          <?php if (has_post_thumbnail()):
            the_post_thumbnail('aiv-thumb', ['alt' => '', 'loading' => 'lazy']);
          else: ?>
            <div class="news-row-img-ph"><?php echo aiv_icon('newspaper'); ?></div>
          <?php endif; ?>
        </a>
        <div class="news-row-body">
          <?php aiv_cat(); ?>
          <h2 class="news-row-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <p class="news-row-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 18, '…'); ?></p>
          <div class="meta">
            <span class="meta-i"><?php echo aiv_icon('calendar'); ?> <?php echo get_the_date(); ?></span>
            <span class="meta-i"><?php echo aiv_icon('clock'); ?> <?php echo aiv_read_time(); ?></span>
            <span class="meta-i"><?php echo aiv_icon('user'); ?> <?php the_author(); ?></span>
          </div>
        </div>
      </article>
      <?php endwhile; ?>
    </div>

    <div class="archive-pagination">
      <?php
      echo paginate_links([
        'total'     => $wp_query->max_num_pages,
        'current'   => max(1, get_query_var('paged')),
        'mid_size'  => 2,
        'prev_text' => '&larr; Prev',
        'next_text' => 'Next &rarr;',
        'type'      => 'list',
      ]);
      ?>
    </div>

    <?php else: ?>

    <?php if (is_category()): ?>
    <?php
      $cat        = get_queried_object();
      $cat_slug   = $cat->slug;
      $cat_name   = $cat->name;
      // Per-section descriptions and icon hints
      $section_meta = [
        'markets'        => ['desc' => 'Live indices, sector heatmaps, and in-depth market analysis — covering NSE, BSE, and global exchanges.', 'icon' => 'chart-bar'],
        'policy'         => ['desc' => 'Government decisions, regulatory changes, and policy analysis that shape India\'s economic direction.', 'icon' => 'landmark'],
        'banking'        => ['desc' => 'RBI updates, credit trends, digital payments, and banking sector developments.', 'icon' => 'building-columns'],
        'economy'        => ['desc' => 'GDP, inflation, trade data, and macro indicators powering the Indian economy.', 'icon' => 'globe'],
        'global'         => ['desc' => 'International economic news and geopolitical developments affecting Indian markets.', 'icon' => 'globe'],
        'foreign-policy' => ['desc' => 'India\'s diplomatic ties, trade agreements, and cross-border economic relationships.', 'icon' => 'handshake'],
        'technology'     => ['desc' => 'Fintech, AI, startups, and digital infrastructure transforming India\'s economy.', 'icon' => 'cpu-chip'],
        'opinion'        => ['desc' => 'Expert analysis, commentary, and informed perspectives from economists and market veterans.', 'icon' => 'pencil'],
        'breaking'       => ['desc' => 'Urgent market-moving news and real-time economic developments.', 'icon' => 'bolt'],
      ];
      $meta = $section_meta[$cat_slug] ?? ['desc' => 'Stay tuned — coverage for this section is coming soon.', 'icon' => 'newspaper'];
    ?>
    <div class="cat-placeholder">
      <div class="cat-ph-icon"><?php echo aiv_icon($meta['icon']); ?></div>
      <div class="cat-ph-badge">Coming Soon</div>
      <h2 class="cat-ph-title"><?php echo esc_html($cat_name); ?> Coverage</h2>
      <p class="cat-ph-desc"><?php echo esc_html($meta['desc']); ?></p>
      <div class="cat-ph-actions">
        <a href="<?php echo esc_url(home_url('/subscribe')); ?>" class="btn-subscribe">
          <?php echo aiv_icon('envelope'); ?> Get notified when we publish
        </a>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-outline">
          <?php echo aiv_icon('arrow-left'); ?> Back to Home
        </a>
      </div>
      <div class="cat-ph-cards" aria-hidden="true">
        <?php for ($i = 0; $i < 3; $i++): ?>
        <div class="cat-ph-card">
          <div class="cat-ph-card-img skel"></div>
          <div class="cat-ph-card-body">
            <div class="skel skel-line skel-short"></div>
            <div class="skel skel-line"></div>
            <div class="skel skel-line skel-med"></div>
            <div class="skel skel-line skel-short"></div>
          </div>
        </div>
        <?php endfor; ?>
      </div>
    </div>

    <?php else: ?>

    <div class="empty-state">
      <?php echo aiv_icon('newspaper'); ?>
      <h2>Nothing found</h2>
      <p>Try a different search term or browse our sections.</p>
      <?php get_search_form(); ?>
    </div>

    <?php endif; ?>

    <?php endif; ?>

  </div>

  <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
