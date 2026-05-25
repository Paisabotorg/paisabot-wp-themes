<?php get_header(); ?>

<div class="home-grid">
  <div>

    <?php while (have_posts()): the_post(); ?>

    <article <?php post_class('page-article'); ?>>

      <header class="page-art-head">
        <h1 class="page-art-title"><?php the_title(); ?></h1>
        <?php if (has_post_thumbnail()): ?>
        <div class="page-art-hero">
          <?php the_post_thumbnail('aiv-hero', ['alt' => get_the_title()]); ?>
        </div>
        <?php endif; ?>
      </header>

      <div class="page-art-body">
        <?php the_content(); ?>
        <?php wp_link_pages(['before' => '<div class="page-links">', 'after' => '</div>']); ?>
      </div>

    </article>

    <?php if (comments_open() || get_comments_number()): ?>
    <div class="comments-wrapper">
      <?php comments_template(); ?>
    </div>
    <?php endif; ?>

    <?php endwhile; ?>

  </div>

  <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
