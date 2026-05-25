<aside class="sidebar" role="complementary" aria-label="<?php esc_attr_e('Sidebar','arthanama'); ?>">

  <?php if (is_active_sidebar('sidebar-main')): ?>
    <?php dynamic_sidebar('sidebar-main'); ?>
  <?php else: ?>

    <!-- Popular Posts Widget -->
    <?php
    $popular = new WP_Query(['posts_per_page' => 5, 'post_status' => 'publish', 'orderby' => 'comment_count', 'order' => 'DESC']);
    if ($popular->have_posts()):
    ?>
    <section class="widget">
      <h3 class="widget-title"><?php echo arthanama_icon('star'); ?> <?php esc_html_e('Top Stories','arthanama'); ?></h3>
      <div class="widget-body">
        <ol class="popular-list">
          <?php $i = 1; while ($popular->have_posts()): $popular->the_post(); ?>
          <li class="popular-item">
            <span class="popular-num"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></span>
            <div>
              <div class="popular-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </div>
              <div class="post-meta" style="margin-top:4px">
                <span class="post-meta-item"><?php echo arthanama_icon('clock'); ?> <?php echo arthanama_reading_time(); ?></span>
              </div>
            </div>
          </li>
          <?php $i++; endwhile; wp_reset_postdata(); ?>
        </ol>
      </div>
    </section>
    <?php endif; ?>

    <!-- Categories Widget -->
    <?php
    $cats = get_categories(['hide_empty' => true, 'number' => 10, 'orderby' => 'count', 'order' => 'DESC']);
    if ($cats):
    ?>
    <section class="widget">
      <h3 class="widget-title"><?php echo arthanama_icon('tag'); ?> <?php esc_html_e('Categories','arthanama'); ?></h3>
      <div class="widget-body">
        <ul class="cat-list">
          <?php foreach ($cats as $cat): ?>
          <li class="cat-list-item">
            <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>">
              <?php echo esc_html($cat->name); ?>
              <span class="cat-count"><?php echo $cat->count; ?></span>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>
    <?php endif; ?>

    <!-- Tags Widget -->
    <?php
    $tags = get_tags(['number' => 20, 'orderby' => 'count', 'order' => 'DESC']);
    if ($tags):
    ?>
    <section class="widget">
      <h3 class="widget-title"><?php echo arthanama_icon('tag'); ?> <?php esc_html_e('Tags','arthanama'); ?></h3>
      <div class="widget-body">
        <div class="tags-cloud">
          <?php foreach ($tags as $tag): ?>
          <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="tag-chip">
            <?php echo esc_html($tag->name); ?>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- Recent Posts Widget -->
    <?php
    $recent = new WP_Query(['posts_per_page' => 4, 'post_status' => 'publish', 'orderby' => 'date']);
    if ($recent->have_posts()):
    ?>
    <section class="widget">
      <h3 class="widget-title"><?php echo arthanama_icon('clock'); ?> <?php esc_html_e('Recent','arthanama'); ?></h3>
      <div class="widget-body">
        <div class="news-list">
          <?php while ($recent->have_posts()): $recent->the_post(); ?>
          <div class="news-item" style="grid-template-columns:70px 1fr;gap:10px">
            <a href="<?php the_permalink(); ?>" class="news-item-image" style="aspect-ratio:4/3;border-radius:var(--radius-sm);overflow:hidden;background:var(--navy-light)" tabindex="-1">
              <?php if (has_post_thumbnail()):
                the_post_thumbnail('thumbnail', ['alt' => '', 'loading' => 'lazy', 'style' => 'width:100%;height:100%;object-fit:cover']);
              endif; ?>
            </a>
            <div class="news-item-body">
              <div class="news-item-title" style="font-size:.84rem">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </div>
              <div class="post-meta" style="margin-top:4px">
                <span class="post-meta-item"><?php echo arthanama_icon('calendar'); ?> <?php echo get_the_date(); ?></span>
              </div>
            </div>
          </div>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

  <?php endif; ?>

</aside>
