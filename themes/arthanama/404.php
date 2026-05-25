<?php get_header(); ?>
<div class="container">
  <div class="error-page">
    <div class="error-page-inner">
      <div class="error-code">404</div>
      <h1 class="error-title"><?php esc_html_e('Page Not Found','arthanama'); ?></h1>
      <p class="error-text"><?php esc_html_e('The article you are looking for may have been moved or no longer exists.','arthanama'); ?></p>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-primary">
        <?php echo arthanama_icon('home'); ?>
        <?php esc_html_e('Back to Homepage','arthanama'); ?>
      </a>
    </div>
  </div>
</div>
<?php get_footer(); ?>
