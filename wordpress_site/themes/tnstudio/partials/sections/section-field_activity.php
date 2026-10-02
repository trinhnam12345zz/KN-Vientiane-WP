<?php
defined('ABSPATH') || exit;

if (!isset($args['object_id']))
  return;

$data = get_field('section_field_activity', $args['object_id']);
$template_url = MONA_SITE_TEMPLATE_URL;
$allowed_tags = [
  'br' => [],
  'br/' => [],
];

if (empty($data))
  return;
?>

<section class="field-ban">
  <div class="container">
    <div class="field-ban-tt">
      <?php if (!empty($data['title'])): ?>
        <h2 class="main-tt"><?php echo wp_kses(nl2br($data['title']), $allowed_tags); ?></h2>
      <?php endif; ?>

      <?php if (!empty($data['description'])): ?>
        <p class="text-14"><?php echo wp_kses(nl2br($data['description']), $allowed_tags); ?></p>
      <?php endif; ?>
    </div>
    <div class="field-ban-slide js-field-slide">
      <div class="swiper">
        <div class="swiper-wrapper">
          <?php
          $args = array(
            'post_type'      => 'linh-vuc-hoat-dong',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'ASC'
          );

          $the_query = new WP_Query($args);

          // 2. Vòng lặp hiển thị dữ liệu
          if ($the_query->have_posts()) :
            while ($the_query->have_posts()) : $the_query->the_post();

              $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
              if (!$thumbnail_url) {
                $thumbnail_url = get_template_directory_uri() . '/assets/images/field/field1.jpg'; // Đường dẫn ảnh backup
              }

              $title = get_the_title();
              // $words = explode(' ', $title);
              // if (count($words) >= 3) {
              //   $middle = ceil(count($words) / 2);
              //   array_splice($words, $middle, 0, '<br>');
              //   $title = implode(' ', $words);
              // }
          ?>

              <div class="swiper-slide">
                <div class="field-item">
                  <div class="img-box">
                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"
                      title="<?php echo esc_attr(get_the_title()); ?>" loading="lazy">
                  </div>
                  <div class="field-txt">
                    <a href="<?php the_permalink(); ?>"><?php echo wp_kses_post($title); ?></a>
                    <div class="field-item-desc">
                      <?php
                      $excerpt = get_the_excerpt();
                      if ($excerpt) {
                        echo '<p class="text-14">' . esc_html($excerpt) . '</p>';
                      } else {
                        echo '<p class="mona-empty">' . (function_exists('pll__') ? pll__('Nội dung sẽ sớm được cập nhật') : 'Nội dung sẽ sớm được cập nhật') . '</p>';
                      }
                      ?>
                      <a class="n-view-more" href="<?php the_permalink(); ?>">
                        <span><?php echo esc_html(function_exists('pll__') ? pll__('Xem chi tiết') : 'Xem chi tiết'); ?></span>
                        <img src="<?php echo MONA_SITE_TEMPLATE_URL; ?>/assets/images/icons/arrow-right.svg" alt="" title="" loading="lazy">
                      </a>
                    </div>
                  </div>
                </div>
              </div>

          <?php
            endwhile;
            wp_reset_postdata();
          else :
            echo '<p>' . (function_exists('pll__') ? pll__('Chưa có bài viết nào trong lĩnh vực này.') : 'Chưa có bài viết nào trong lĩnh vực này.') . '</p>';
          endif;
          ?>
        </div>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>
</section>