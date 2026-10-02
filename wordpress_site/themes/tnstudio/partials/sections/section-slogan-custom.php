<?php

/**
 * Section "Kiến tạo giá trị" — bản cấu hình trực tiếp trên trang.
 *
 * Chỉ được nạp khi field section_slogan.show bật. Dùng lại đúng class của bản
 * cũ (.slogan / .slogan-bg / .slogan-tt) nên không cần thêm CSS.
 *
 * @author MONA.Media / Website
 */

defined('ABSPATH') || exit;

$mona_slogan = $args['data'] ?? [];

if (empty($mona_slogan)) {
    return;
}
?>

<section class="slogan">
  <div class="slogan-bg">
    <?php mona_render_hero_media($mona_slogan); ?>
  </div>

  <?php if (! empty($mona_slogan['title'])) : ?>
    <div class="slogan-tt">
      <div class="container">
        <h2 class="title-64"><?php echo nl2br(esc_html($mona_slogan['title'])); ?></h2>
      </div>
    </div>
  <?php endif; ?>
</section>
