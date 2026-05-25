<?php get_header(); ?>
<div class="container">
  <div class="single-layout">
    <article <?php post_class(); ?>>
      <?php while (have_posts()): the_post(); ?>
      <header class="post-header">
        <h1 class="post-title"><?php the_title(); ?></h1>
      </header>
      <?php if (has_post_thumbnail()): ?>
      <div class="post-featured-image"><?php the_post_thumbnail('arthanama-hero'); ?></div>
      <?php endif; ?>
      <div class="post-content"><?php the_content(); ?></div>
      <?php endwhile; ?>
    </article>
    <?php get_sidebar(); ?>
  </div>
</div>
<?php get_footer(); ?>
