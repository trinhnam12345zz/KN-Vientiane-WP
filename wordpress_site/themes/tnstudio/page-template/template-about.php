<?php

/**
 * Template Name: Giới thiệu
 *
 * @author MONA.Media
 */

defined('ABSPATH') || exit;

get_header();

the_title('<h1 class="hide-sitename">', '</h1>');

/**
 * Page Data
 */
$page_id = get_the_ID();
$fields  = get_fields($page_id);

/**
 * Sections
 */
$sections = [
  'banner' => $fields['section_banner'] ?? [],
  'about_us' => $fields['section_about_us'] ?? [],
  'creative_value' => $fields['section_creative_value'] ?? [],
  'logo' => $fields['section_logo'] ?? [],
  'eco_sys' => $fields['section_eco_sys'] ?? [],
  'history' => $fields['section_history'] ?? [],
  'about_bod' => $fields['section_about_bod'] ?? [],
];

/**
 * Banner Section
 */
$banner = $sections['banner'];

?>

<?php if (!empty($banner['show'])) : ?>

<section class="banner-ab">
  <?php mona_output_breadcrumb(); ?>
  <div class="banner-ab-dt">
    <div class="container">
      <div class="ban-ab-inner">
        <!-- LEFT -->
        <div class="ban-ab-left">

          <div class="ab-left-title">

            <?php if (!empty($banner['title'])) : ?>
            <h2 class="ab-title">
              <?php
                  echo wp_kses_post(
                    wrap_lines_from_second_with_span(
                      $banner['title']
                    )
                  );
                  ?>
            </h2>
            <?php endif; ?>

            <?php if (!empty($banner['left_images'])) : ?>
            <div class="ab-left-img">
              <div class="img-box">

                <?php
                    echo wp_get_attachment_image(
                      $banner['left_images'],
                      'full',
                      false,
                      [
                        'loading' => 'lazy',
                        'alt' => esc_attr(
                          wp_strip_all_tags(
                            $banner['title'] ?? ''
                          )
                        ),
                      ]
                    );
                    ?>

              </div>
            </div>
            <?php endif; ?>

          </div>

          <?php if (!empty($banner['description'])) : ?>
          <div class="ab-left-ct text-14">

            <?php
                echo wp_kses_post(
                  wrap_lines_from_second_with_p(
                    $banner['description']
                  )
                );
                ?>

          </div>
          <?php endif; ?>

          <?php
            /**
             * Achievement
             */
              $achieve_id = !empty($banner['achievement']) ? $banner['achievement'] : 180;
              if (is_object($achieve_id) && isset($achieve_id->ID)) $achieve_id = $achieve_id->ID;
              if (is_array($achieve_id) && isset($achieve_id[0])) $achieve_id = $achieve_id[0];
              
              $statistics = get_field('achievement_statistics', 180); // hardcode to 180 for testing

              if (!empty($statistics)) :
            ?>

          <div class="ab-num-count">

            <?php
                  get_template_part(
                    'partials/components/achievement',
                    null,
                    [
                      'statistics' => $statistics,
                    ]
                  );
                  ?>

          </div>

          <?php
              endif;
            ?>

        </div>

        <!-- RIGHT -->
        <div class="ban-ab-right">

          <?php
            $video = $banner['right_video'] ?? [];

            $video_image_id = $video['images_video'] ?? '';
            $video_file_id  = $video['file_video'] ?? '';

            $video_file_url = $video_file_id
              ? wp_get_attachment_url($video_file_id)
              : '';

            if ($video_file_url) :

              $modal_id = 'video-box-' . $page_id;
            ?>

          <a class="ban-ab-video" href="#<?php echo esc_attr($modal_id); ?>" rel="modal:open">

            <?php if ($video_image_id) : ?>

            <div class="img-box">

              <?php
                    echo wp_get_attachment_image(
                      $video_image_id,
                      'full',
                      false,
                      [
                        'loading' => 'lazy',
                        'alt' => esc_attr(
                          wp_strip_all_tags(
                            $banner['title'] ?? ''
                          )
                        ),
                      ]
                    );
                    ?>

            </div>

            <?php endif; ?>

            <div class="video-btn">
              <img src="/template/assets/images/about/btn-video.svg" alt="Play video" loading="lazy">
            </div>

          </a>

          <div class="modal video-box" id="<?php echo esc_attr($modal_id); ?>">

            <div class="video-pop">

              <video controls preload="metadata">

                <source src="<?php echo esc_url($video_file_url); ?>" type="video/mp4">

              </video>

            </div>

          </div>

          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</section>

<?php endif; ?>

<?php
/**
 * section_about_us
 */
$about_us = $sections['about_us'];

if (!empty($about_us['show'])) :
?>

<section class="short-ab">
  <div class="container">
    <div class="short-ab-inner">
      <div class="sa-title">
        <?php if (!empty($about_us['title'])) : ?>
        <h2 class="main-tt"><?php echo esc_html($about_us['title']); ?></h2>
        <?php endif; ?>

        <?php if (!empty($about_us['upload_file'])) : ?>
        <a class="btn" href="<?php echo esc_url(wp_get_attachment_url($about_us['upload_file'])); ?>" target="_blank">
          <img src="/template/assets/images/about/book.svg" alt="" title="" loading="lazy">
          <span><?php echo esc_html(function_exists('pll__') ? pll__('Tải hồ sơ năng lực') : 'Tải hồ sơ năng lực'); ?> </span>
        </a>
        <?php endif; ?>

      </div>
      <div class="sa-info">
        <?php if (!empty($about_us['accordion'])) : ?>
        <div class="sa-info-inner">
          <ul class="sa-i-list js-toggle-item">
            <?php foreach ($about_us['accordion'] as $index => $item) : ?>
            <li class="sa-i-item js-item-<?php echo esc_attr($index); ?>">
              <div class="i-item-txt">
                <div class="i-title"> <img src="/template/assets/images/about/ic-1.svg" alt="" title="" loading="lazy">
                  <p class="title-24"><?php echo esc_html($item['title']); ?></p>
                </div>
                <p class="i-desc"><?php echo esc_html($item['description']); ?></p>
              </div>
              <div class="i-item-img">
                <div class="img-box">
                  <?php echo wp_get_attachment_image($item['images'], 'full', false, [
                          'loading' => 'lazy',
                        ]); ?>
                </div>
              </div>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php endif; ?>

<?php
/**
 * section_creative_value
 */
$creative_value = $sections['creative_value'];

mona_render_creative_value_section($creative_value, (int) $page_id);
?>

<?php
/**
 * section_logo
 */
$logo = $sections['logo'];

/**
 * Ưu tiên repeater "Danh sách logo (có link)" — mỗi mục có ảnh + link riêng.
 * Chưa nhập mục nào thì rơi về Gallery cũ (chỉ có ảnh, không bấm được), nên
 * không có thời điểm nào phần logo bị trống trong lúc chuyển đổi dữ liệu.
 */
$logo_rows = ! empty($logo['logos']) ? $logo['logos'] : ($logo['list_logo'] ?? []);

if (!empty($logo['show'])) :
?>
<section class="short-ic">
  <div class="container">
    <?php if (!empty($logo_rows)) : ?>
    <div class="logo-list">
      <?php foreach ($logo_rows as $logo_row):
        // Repeater trả về mảng ['image' => ID, 'link' => ...]; Gallery trả về ID ảnh
        $logo_image = is_array($logo_row) ? ($logo_row['image'] ?? 0) : $logo_row;
        $logo_link  = is_array($logo_row) ? mona_link_attrs($logo_row['link'] ?? '') : '';

        if (empty($logo_image)) {
          continue;
        }
      ?>
      <?php if ($logo_link) : ?>
      <a class="logo-item is-linked" <?php echo $logo_link; ?>>
        <?php echo wp_get_attachment_image($logo_image, 'full', false, [
                'loading' => 'lazy',
              ]); ?>
      </a>
      <?php else : ?>
      <div class="logo-item">
        <?php echo wp_get_attachment_image($logo_image, 'full', false, [
                'loading' => 'lazy',
              ]); ?>
      </div>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php
/**
 * section_eco_sys
 */
$eco_sys = $sections['eco_sys'];
if (!empty($eco_sys['show'])) :
?>
<section class="our-system">
  <div class="container">
    <?php if (!empty($eco_sys['title'])) : ?>
    <h2 class="main-tt"><?php echo esc_html($eco_sys['title']); ?></h2>
    <?php endif; ?>
    <div class="system-list-slide js-system-slide">
      <div class="swiper">
        <?php if (!empty($eco_sys['list_eco_sys'])) : ?>
        <div class="swiper-wrapper">
          <?php foreach ($eco_sys['list_eco_sys'] as $system) :
            // Chỉ render <a> khi thật sự có link, tránh tạo thẻ link rỗng
            $sys_link = mona_link_attrs($system['link'] ?? '');
          ?>
          <div class="swiper-slide">
            <?php if ($sys_link) : ?>
            <a class="sys-item is-linked" <?php echo $sys_link; ?>>
            <?php else : ?>
            <div class="sys-item">
            <?php endif; ?>
              <?php if (!empty($system['logo'])) : ?>
              <div class="sys-tag">
                <?php echo wp_get_attachment_image($system['logo'], 'full', false, [
                          'loading' => 'lazy',
                        ]); ?>
              </div>
              <?php endif; ?>
              <div class="img-box">
                <?php echo wp_get_attachment_image($system['image'], 'full', false, [
                        'loading' => 'lazy',
                      ]); ?>
              </div>
              <div class="sys-content">
                <?php if (!empty($system['title'])) : ?>
                <div class="title-24"><?php echo esc_html($system['title']); ?></div>
                <?php endif; ?>
                <?php if (!empty($system['description'])) : ?>
                <p class="text-14"><?php echo esc_html($system['description']); ?></p>
                <?php endif; ?>
              </div>
            <?php echo $sys_link ? '</a>' : '</div>'; ?>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <?php
        // Kiểm tra nếu danh sách tồn tại và có nhiều hơn 3 phần tử thì mới hiện nút điều hướng
        if (!empty($eco_sys['list_eco_sys']) && count($eco_sys['list_eco_sys']) > 3):
        ?>
      <div class="swiper-nav yel swiper-navigation">
        <div class="prev"> <img src="/template/assets/images/icons/arrow-left-yel.svg" alt="" title="" loading="lazy">
        </div>
        <div class="next"> <img src="/template/assets/images/icons/arrow-left-yel.svg" alt="" title="" loading="lazy">
        </div>
      </div>
      <div class="swiper-pagination"></div>
      <?php endif; ?>
    </div>
  </div>
  </div>
</section>
<?php endif; ?>

<?php
/**
 * section_history
 */
$history = $sections['history'];
if (!empty($history['show'])) :
?>
<section class="ab-history">
  <div class="container">
    <?php if (!empty($history['title'])) : ?>
    <h2 class="main-tt"><?php echo esc_html($history['title']); ?></h2>
    <?php endif; ?>

    <?php if (!empty($history['list_history'])) : ?>
    <ul class="year-list">
      <?php foreach ($history['list_history'] as $item) : ?>
      <?php if (!empty($item['show'])) : ?>
      <li class="year-point">
        <div class="year-txt">
          <p class="desc"><?php echo esc_html($item['year']); ?></p>
        </div>
        <div class="year-exp-list">
          <div class="year-ext-inner">
            <?php foreach ($item['milestones_of_year'] as $experience) : ?>
            <div class="yel-item">
              <div class="yel-content">
                <p class="title-28 fw-b"><?php echo esc_html($experience['title']); ?></p>
                <p><?php echo esc_html($experience['description']); ?></p>
                <?php if (!empty($experience['image'])) : ?>
                <div class="img-box"><img src="<?php echo esc_url(wp_get_attachment_url($experience['image'])); ?>"
                    alt="" title="" loading="lazy">
                </div>
                <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </li>
      <?php endif; ?>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php
/**
 * section_about_bod
 */
$about_bod = $sections['about_bod'];
if (!empty($about_bod['show'])) :
?>
<section class="ab-manager">
  <div class="container">
    <div class="ab-manager-group">
      <?php if (!empty($about_bod['title'])) : ?>
      <h2 class="main-tt"><?php echo esc_html($about_bod['title']); ?></h2>
      <?php endif; ?>
      <div class="ab-manager-inner js-manager-slide">
        <div class="swiper">
          <?php if (!empty($about_bod['list_bod'])) : ?>
          <div class="swiper-wrapper">
            <?php foreach ($about_bod['list_bod'] as $index => $item) : ?>
            <div class="swiper-slide">
              <div class="manager-item">
                <div class="m-box">
                  <a class="manager-avt ma-link" href="#ma-item<?php echo esc_attr($index); ?>" rel="modal:open">
                    <?php echo wp_get_attachment_image($item['image'], 'full', false, [
                            'loading' => 'lazy',
                            'alt' => $item['name'] ?? '',
                          ]); ?>
                  </a>
                </div>
                <div class="m-box">
                  <div class="ma-info">
                    <?php if (!empty($item['medal'])) : ?>
                    <div class="tag-role">
                      <img src="/template/assets/images/about/ic-role.svg" alt="" title="" loading="lazy">
                      <p class="text-14"><?php echo esc_html($item['medal']); ?></p>
                    </div>
                    <?php endif; ?>
                    <a class="ma-link" href="#ma-item<?php echo esc_attr($index); ?>" rel="modal:open">
                      <span><?php echo esc_html($item['name']); ?></span>
                      <img src="/template/assets/images/about/arr-desc.svg" alt="" title="" loading="lazy">
                    </a>
                    <p class="role-name"><?php echo esc_html($item['position']); ?></p>
                  </div>
                  <div class="modal pop-manager" id="ma-item<?php echo esc_attr($index); ?>"><a class="close-pop"
                      href="#" rel="modal:close"> <img src="/template/assets/images/about/close.svg" alt="" title=""
                        loading="lazy"></a>
                    <div class="pop-man-inner">
                      <div class="pmi-box">
                        <div class="big-avt">
                          <?php echo wp_get_attachment_image($item['image'], 'full', false, [
                                  'loading' => 'lazy',
                                ]); ?>
                        </div>
                      </div>
                      <div class="pmi-box">
                        <div class="ma-info">
                          <?php if (!empty($item['medal'])) : ?>
                          <div class="tag-role">
                            <img src="/template/assets/images/about/ic-role.svg" alt="" title="" loading="lazy">
                            <p class="text-14"><?php echo esc_html($item['medal']); ?></p>
                          </div>
                          <?php endif; ?>
                          <div class="ma-link">
                            <span><?php echo esc_html($item['name']); ?></span>
                          </div>
                          <p class="role-name"><?php echo esc_html($item['position']); ?></p>
                          <div class="mona-content">
                            <?php echo apply_filters('the_content', $item['description']); ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <div class="ab-manager-list js-manager-list">
        <div class="swiper">
          <?php if (!empty($about_bod['list_bod'])) : ?>
          <div class="swiper-wrapper">
            <?php foreach ($about_bod['list_bod'] as $item) : ?>
            <div class="swiper-slide">
              <div class="m-avt-item">
                <?php echo wp_get_attachment_image($item['image'], 'full', false, [
                        'loading' => 'lazy',
                      ]); ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>