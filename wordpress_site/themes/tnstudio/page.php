<?php

/**
 * The template for single post type is page
 *
 * @author MONA.Media / Website
 */

if (! defined('ABSPATH')) {
  die();
}

get_header();

while (have_posts()) {
  the_post();

  $post_id = get_the_ID();
  // Ẩn sidebar Categories/Recommended trên các trang tĩnh dạng chính sách, điều khoản...
  $mona_page_slug = get_post_field('post_name', $post_id);
  $mona_no_sidebar_slugs = ['chinh-sach', 'policy', 'privacy-policy'];
  $mona_hide_sidebar = in_array($mona_page_slug, $mona_no_sidebar_slugs, true);
  $mona_has_sidebar  = ! $mona_hide_sidebar && is_active_sidebar('sidebar-detail-blog');
?>
<?php
/**
 * Banner đầu trang.
 *
 * Dùng lại đúng markup .banner-main.type-2 của trang chi tiết Lĩnh vực hoạt
 * động để hai bên trông giống nhau; class này nằm trong common.css nên trang
 * mặc định vẫn nhận được style.
 */
?>
<section class="banner-main type-2">
  <?php mona_output_breadcrumb(); ?>
  <div class="container">
    <div class="banner-info">
      <div class="logo-mark">
        <img src="<?php echo MONA_SITE_TEMPLATE_URL; ?>/assets/images/news/logo-banner.png" alt="" loading="lazy">
      </div>
      <div class="banner-i-top">
        <h1 class="main-tt"><?php the_title(); ?></h1>
        <?php // Chỉ hiện khi biên tập viên tự nhập tóm tắt, tránh WordPress tự cắt từ nội dung ?>
        <?php if (has_excerpt($post_id)) : ?>
          <?php the_excerpt(); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php
/**
 * Phần nội dung.
 *
 * Trước đây khối này dùng bộ class .postdt / .l-sidebar / .l-sidebar_main /
 * .l-sidebar_aside — nhưng KHÔNG có file CSS nào của dự án định nghĩa chúng
 * (đã grep toàn bộ template/assets/css: không một dòng). Hệ quả là .l-sidebar
 * giữ nguyên display:block nên cột nội dung và sidebar xếp chồng dọc, mỗi cột
 * rộng hết khung — đúng cảnh khách chụp lại: chữ dồn một cột hẹp bên trái,
 * hộp DANH MỤC trôi xuống dưới.
 *
 * Chuyển sang bộ class của trang chi tiết Tin tức (.news-detail /
 * .news-d-desc / .news-d-txt / .news-cate-group). Bộ này có sẵn CSS thật trong
 * news.css — chia 9/12 và 3/12 ở desktop, sidebar biến thành ngăn kéo trượt ra
 * ở mobile (ToggleFilter.js) — nên trang mặc định dùng lại được nguyên vẹn mà
 * không phải viết thêm CSS mới.
 */
?>
<section class="news-detail">
  <div class="container" data-aos="fade-up">
    <div class="news-d-desc<?php echo $mona_has_sidebar ? '' : ' is-no-aside'; ?>">
      <div class="news-d-txt">
        <div class="mona-content">
          <?php
          ob_start();
          the_content();
          $content = ob_get_clean();

          if ($content) {
            echo $content;
          } else {
            echo '<p class="mona-empty">' . (function_exists('pll__') ? pll__('Nội dung sẽ sớm được cập nhật') : 'Nội dung sẽ sớm được cập nhật') . '</p>';
          }
          ?>
        </div>
      </div>

      <?php if ($mona_has_sidebar) : ?>
        <div class="btn-open-mb">
          <img src="<?php echo MONA_SITE_TEMPLATE_URL; ?>/assets/images/icons/icon-cate.svg" alt="" title=""
            loading="lazy">
        </div>
        <div class="news-cate-group">
          <div class="btn-clost-mb">
            <img src="<?php echo MONA_SITE_TEMPLATE_URL; ?>/assets/images/icons/close-btn.svg" alt="" title=""
              loading="lazy">
          </div>
          <?php dynamic_sidebar('sidebar-detail-blog'); ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<div class="mountain-decor js-add-active"></div>
<?php
}

get_footer();
