<?php

/**
 * Bộ chuyển ngôn ngữ ở thanh copyright.
 *
 * Dùng chung cho hai chỗ vì thanh copyright bị viết lặp: footer.php lo cho mọi
 * trang, còn front-page.php dựng lại một bản y hệt bên trong section cuối của
 * fullPage (trang chủ không kéo tới thẻ <footer> thật). Đợt trước chỉ thêm bộ
 * chuyển vào footer.php nên trang chủ không có — đúng lỗi khách báo lại.
 *
 * Chỉ hiện từ 769px trở lên. Ở mobile khách chốt là ẩn hẳn vì header đã có sẵn
 * nút chọn ngôn ngữ (quy tắc ẩn nằm ở mona-custom.css).
 *
 * @author MONA.Media / Website
 */

defined('ABSPATH') || exit;

$mona_ls_languages = mona_get_languages();

if (empty($mona_ls_languages)) {
    return;
}
?>
<div class="fc-lang">
  <?php foreach ($mona_ls_languages as $mona_ls_item) :
    $mona_ls_label = $mona_ls_item['native_name'] ?: strtoupper($mona_ls_item['language_code']);
  ?>
    <a class="fc-lang-item<?php echo ! empty($mona_ls_item['active']) ? ' is-active' : ''; ?>"
      href="<?php echo esc_url($mona_ls_item['url']); ?>"
      lang="<?php echo esc_attr($mona_ls_item['language_code']); ?>"
      title="<?php echo esc_attr($mona_ls_label); ?>">
      <img class="lang-flag" src="<?php echo esc_url(mona_lang_flag_url($mona_ls_item['language_code'])); ?>"
        alt="<?php echo esc_attr($mona_ls_label); ?>" loading="lazy" width="20" height="14">
      <span class="fc-lang-name"><?php echo esc_html($mona_ls_label); ?></span>
    </a>
  <?php endforeach; ?>
</div>
