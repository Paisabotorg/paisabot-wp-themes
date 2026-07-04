<?php
if (!is_active_sidebar('ad-native')) return;
// Native ad is rendered with a wrapper but content is widget-supplied
?>
<aside class="native-ad-card" aria-label="Sponsored content">
  <span class="native-label"><?php esc_html_e('Partner Content', 'aivartha'); ?></span>
  <?php dynamic_sidebar('ad-native'); ?>
</aside>
