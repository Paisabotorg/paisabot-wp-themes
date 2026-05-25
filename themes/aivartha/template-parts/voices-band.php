<?php
$voices = aiv_voices_posts(4);
if (!$voices) return;
?>
<section class="voices-band">
  <div class="section-band-hd">
    <div class="section-band-hd-left">
      <h2>Voices</h2>
      <p class="tagline">Columnists with a perspective — and the data to back it.</p>
    </div>
    <a class="sec-more" href="<?php echo esc_url(get_category_link(get_cat_ID('Opinion'))); ?>">
      All Opinion <?php echo aiv_icon('i-arrow-r'); ?>
    </a>
  </div>
  <div class="voices-grid">
    <?php foreach ($voices as $v):
      $author    = get_the_author_meta('display_name', $v->post_author);
      $role      = get_the_author_meta('aiv_role', $v->post_author) ?: get_the_author_meta('description', $v->post_author);
      $role      = $role ? wp_trim_words($role, 3, '') : 'Columnist';
      $tag       = get_the_tags($v->ID);
      $first_tag = $tag ? $tag[0]->name : (get_the_category($v->ID)[0]->name ?? 'Opinion');
    ?>
    <a class="voice-card" href="<?php echo esc_url(get_permalink($v)); ?>">
      <div class="voice-meta">
        <div class="voice-avatar"><?php echo esc_html(aiv_initials($author)); ?></div>
        <div>
          <div class="voice-name"><?php echo esc_html($author); ?></div>
          <div class="voice-role"><?php echo esc_html($role); ?></div>
        </div>
      </div>
      <h4>&ldquo;<?php echo esc_html(get_the_title($v)); ?>&rdquo;</h4>
      <div class="voice-foot">
        <span class="voice-tag"><?php echo esc_html($first_tag); ?></span>
        <span><?php echo get_the_date('j M', $v); ?> · <?php echo aiv_read_time($v->ID); ?></span>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
</section>
