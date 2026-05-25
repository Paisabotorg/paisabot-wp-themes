<?php
if (!is_active_sidebar('ad-in-article')) return;
?>
<div class="ad-slot ad-in-article" style="margin: 32px 0;">
  <?php dynamic_sidebar('ad-in-article'); ?>
</div>
