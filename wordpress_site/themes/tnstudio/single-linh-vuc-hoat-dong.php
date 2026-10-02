<?php
defined('ABSPATH') || exit;

get_header();

while (have_posts()) {
  the_post();

  $current_post_id = get_the_ID();

  $fields = get_fields($current_post_id);

  /**
   * Sections
   */
  $sections = [
    'achievement' => $fields['achievement'] ?? [],
    'creative_value' => $fields['section_creative_value'] ?? [],
    'about' => $fields['section_about'] ?? [],
    'services_utils' => $fields['section_services_utils'] ?? [],
    'contact' => $fields['section_contact'] ?? [],
  ];
?>

  <section class="banner-main type-2">
    <?php mona_output_breadcrumb(); ?>
    <div class="container">
      <div class="banner-info">
        <div class="logo-mark"> <img src="<?php echo MONA_SITE_TEMPLATE_URL; ?>/assets/images/news/logo-banner.png" alt="" title="" loading="lazy">
        </div>
        <div class="banner-i-top">
          <h1 class="main-tt"><?php the_title(); ?></h1>
          <?php the_excerpt(); ?>
        </div>
      </div>
      <?php
      $achievement = $sections['achievement'];
      if (!empty($achievement['show'])) :
        $list = $achievement['list'];
      ?>

        <div class="count-list" data-aos="fade-up" data-aos-delay="400">
          <?php foreach ($list as $item) : ?>
            <div class="count-item">
              <?php if (!empty($item['number'])) : ?>
                <div class="statis-count" data-module="countup">
                  <p class="number" data-countup-number="<?php echo esc_attr($item['number']); ?>">
                    <?php echo esc_html($item['number']); ?>
                  </p>
                  <p class="mark">+</p>
                </div>
              <?php endif; ?>

              <?php if (!empty($item['title'])) : ?>
                <p class="desc"><?php echo esc_html($item['title']); ?></p>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>

      <?php endif; ?>
    </div>
  </section>

  <?php
  /**
   * section_creative_value
   */
  $creative_value = $sections['creative_value'];

  mona_render_creative_value_section($creative_value, (int) $current_post_id);
  ?>

  <?php
  /**
   * section_about
   */
  $about = $sections['about'];

  if (!empty($about['show'])) : ?>
    <section class="activity pd-160">
      <div class="container">
        <div class="acti-inner">
          <div class="acti-box">
            <?php if (!empty($about['title'])): ?>
              <h2 class="main-tt"><?php echo esc_html($about['title']); ?></h2>
            <?php endif; ?>
            <?php if (!empty($about['description'])): ?>

              <?php
              echo wp_kses_post(
                wrap_lines_from_second_with_p(
                  $about['description'],
                  'text-14'
                )
              );
              ?>

            <?php endif; ?>

            <?php if (!empty($about['features_section'])) : ?>
              <div class="acti-note">
                <?php foreach ($about['features_section'] as $item) : ?>
                  <div class="acti-n-item">

                    <?php echo wp_get_attachment_image($item['icon'], 'full', false, [
                      'loading' => 'lazy',
                    ]); ?>
                    <p class="text-14"><?php echo esc_html($item['description']); ?></p>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>


          </div>
          <div class="acti-box">
            <div class="map-img">
              <?php if (!empty($about['image'])): ?>
                <?php echo wp_get_attachment_image($about['image'], 'full', false, [
                  'loading' => 'lazy',
                ]); ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      </div>
    </section>
  <?php
  endif;
  ?>

  <?php
  /**
   * section_detail_activity
   */
  ?>
  <section class="news-detail">
    <div class="container">
      <div class="news-d-desc">
        <div class="news-d-txt">
          <div class="mona-content">
            <?php
            ob_start();
            the_content();
            $content = ob_get_clean();

            if ($content) {
              echo $content;
            } else {
              echo '<p class="mona=empty">' . (function_exists('pll__') ? pll__('Nội dung sẽ sớm được cập nhật') : 'Nội dung sẽ sớm được cập nhật') . '</p>';
            }
            ?>
          </div>
        </div>
        <div class="btn-open-mb"> <img src="<?php echo MONA_SITE_TEMPLATE_URL; ?>/assets/images/icons/icon-cate.svg" alt="" title="" loading="lazy">
        </div>
        <div class="news-cate-group">
          <div class="btn-clost-mb">
            <img src="<?php echo MONA_SITE_TEMPLATE_URL; ?>/assets/images/icons/close-btn.svg" alt="" title="" loading="lazy">
          </div>
          <?php
          $old = $fields['oil'] ?? [];
          if (!empty($old['show'])) : ?>
            <div class="cate-box">
              <div class="btn-clost-mb"> <img src="<?php echo MONA_SITE_TEMPLATE_URL; ?>/assets/images/icons/close-btn.svg" alt="" title=""
                  loading="lazy">
              </div>
              <div class="text-20"><?php echo esc_html(function_exists('pll__') ? pll__('Thông tin nổi bật') : 'Thông tin nổi bật'); ?></div>
              <ul class="ben-list">
                <?php foreach ($old['list'] as $item) : ?>
                  <li class="ben-item"><?php echo esc_html($item['title']); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
          <?php if (is_active_sidebar('sidebar-detail-blog')) : ?>
            <?php dynamic_sidebar('sidebar-detail-blog'); ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <?php
  /**
   * section_services_utils
   */
  $services_utils = $sections['services_utils'];

  if (!empty($services_utils['show'])) : ?>
    <section class="news-req field-req">
      <div class="container">
        <div class="news-req-tt">
          <?php if (!empty($services_utils['title'])): ?>
            <h2 class="main-tt"><?php echo nl2br(esc_html($services_utils['title'])); ?></h2>
          <?php endif; ?>
          <?php
          // Kiểm tra nếu danh sách tồn tại và có nhiều hơn 3 phần tử thì mới hiện nút điều hướng
          if (!empty($services_utils['list_services_utils']) && count($services_utils['list_services_utils']) > 3):
          ?>
            <div class="swiper-nav yel swiper-navigation">
              <div class="prev"> <img src="<?php echo MONA_SITE_TEMPLATE_URL; ?>/assets/images/icons/arrow-left-yel.svg" alt="" title="" loading="lazy">
              </div>
              <div class="next"> <img src="<?php echo MONA_SITE_TEMPLATE_URL; ?>/assets/images/icons/arrow-left-yel.svg" alt="" title="" loading="lazy">
              </div>
            </div>
          <?php endif; ?>
        </div>
        <div class="news-req-slide js-news-req">
          <div class="swiper">
            <?php if (!empty($services_utils['list_services_utils'])) : ?>
              <div class="swiper-wrapper">
                <?php foreach ($services_utils['list_services_utils'] as $service) :
                  // Chỉ render <a> khi thật sự có link, tránh tạo thẻ link rỗng
                  $service_link = mona_link_attrs($service['link'] ?? '');
                ?>
                  <div class="swiper-slide">
                    <?php if ($service_link) : ?>
                    <a class="project-item is-linked" <?php echo $service_link; ?>>
                    <?php else : ?>
                    <div class="project-item">
                    <?php endif; ?>
                      <div class="img-pj">
                        <?php if (!empty($service['image'])) : ?>
                          <?php echo wp_get_attachment_image($service['image'], 'full', false, [
                            'loading' => 'lazy',
                          ]); ?>
                        <?php endif; ?>
                      </div>
                      <?php
                      $service_title = $service['title'] ?? '';
                      ?>
                      <div class="pj-name"><?php echo wp_kses_post($service_title); ?></div>
                    <?php echo $service_link ? '</a>' : '</div>'; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else : ?>
              <p><?php echo esc_html(function_exists('pll__') ? pll__('Chưa có dịch vụ tiện ích nào.') : 'Chưa có dịch vụ tiện ích nào.'); ?></p>
            <?php endif; ?>
          </div>
        </div>
        <div class="swiper-pagination"></div>
      </div>
      </div>
    </section>
  <?php
  endif;
  ?>

  <?php
  /**
   * section_contact
   */
  $contact = $sections['contact'];

  if (!empty($contact)) {
    mona_render_section($contact);
  }
  ?>
<?php
}

get_footer();
