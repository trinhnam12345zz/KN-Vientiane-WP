<?php

/**
 * The template for front page
 * 
 * @author MONA.Media / Website
 */

if (!defined('ABSPATH')) {
  die();
}

$cur_lang = function_exists('pll_current_language') ? pll_current_language('slug') : apply_filters('wpml_current_language', null);
$current_page_id = get_the_ID();
if (! $current_page_id || ($current_page_id == MONA_PAGE_HOME && $cur_lang && $cur_lang !== 'vi')) {
  $current_page_id = function_exists('pll_get_post') ? (pll_get_post(MONA_PAGE_HOME, $cur_lang) ?: MONA_PAGE_HOME) : apply_filters('wpml_object_id', MONA_PAGE_HOME, 'page', true, $cur_lang);
}
$template_url = MONA_SITE_TEMPLATE_URL;
$allowed_tags = [
  'br' => [],
  'br/' => [],
];

$acf_fields = get_fields($current_page_id);
$section_hero = $acf_fields['section_hero'] ?? null;
$section_about = $acf_fields['section_about'] ?? null;
$section_business_sectors = $acf_fields['section_business_sectors'] ?? null;
$section_project = $acf_fields['section_project'] ?? null;
$section_activity = $acf_fields['section_activity'] ?? null;
$section_contact = $acf_fields['section_contact'] ?? null;
$section_cooperation = $acf_fields['section_cooperation'] ?? null;
$footer_bottom = get_field('footer_bottom', 'option');

get_header();

the_title('<h1 class="hide-sitename">', '</h1>');

?>
<div id="onepage">
  <?php if (! empty($section_hero['show'])) : ?>
  <section class="is-full slogan-main" data-anchor="KN Vientiane Group">
    <div class="slogan">
      <div class="slogan-bg">
        <?php mona_render_hero_media($section_hero); ?>
      </div>
      <?php if (! empty($section_hero['sub_title'])) : ?>

      <div class="slogan-note">
        <p class="text-16" data-aos="fade-up">
          <?php echo wp_kses($section_hero['sub_title'], [
                'br' => [],
              ]); ?></p>
      </div>
      <?php endif; ?>

      <?php if (! empty($section_hero['main_title'])) : ?>
      <div class="slogan-tt" data-aos="zoom-in-up">
        <div class="container">
          <h2 class="title-64"><?php echo wp_kses($section_hero['main_title'], [
                                      'br' => [],
                                    ]); ?></h2>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </section>
  <?php endif; ?>

  <?php if (! empty($section_about['show'])) : ?>
  <section class="is-full home-intro" data-anchor="about-us" data-anchor-label="<?php echo esc_attr__('Giới thiệu', 'monamedia'); ?>">
    <div class="container">
      <div class="home-intro-inner">
        <div class="intro-info">
          <div class="intro-top">
            <?php if (! empty($section_about['title'])) : ?>
            <h2 class="intro-tt t-center" data-aos="fade-up" data-aos-delay="200">
              <?php echo wrap_lines_from_second_with_span($section_about['title']); ?>
            </h2>
            <?php endif; ?>

            <?php
              // Khối "Năm kinh nghiệm / Đối tác / Dự án" nằm ở một bài web-builder
              // riêng, phải đổi sang bản dịch thì mới ra đúng ngôn ngữ đang xem.
              $web_builder_id = mona_web_builder_id($section_about['achievement'] ?? null);

              if ($web_builder_id) :
                $statistics = get_field('achievement_statistics', $web_builder_id);

                if ($statistics) :
                  get_template_part('partials/components/achievement', null, [
                    'statistics' => $statistics
                  ]);
                endif;
              endif;
              ?>
          </div>

          <?php if (! empty($section_about['short_description'])) : ?>
          <div class="intro-note" data-aos="fade-right" data-aos-delay="200">
            <?php echo $section_about['short_description'] ?></div>
          <?php endif; ?>
        </div>

        <?php
        // Có video thì hình bên trái biến thành nút mở popup, không có thì giữ ảnh tĩnh.
        $intro_video_url = mona_get_acf_file_url($section_about['video_file'] ?? '');
        $intro_modal_id  = 'home-intro-video-' . $current_page_id;
        ?>
        <div class="intro-img" data-aos="zoom-in" data-aos-delay="400">
          <?php if ($intro_video_url) : ?>
          <a class="img-box has-video" href="#<?php echo esc_attr($intro_modal_id); ?>" rel="modal:open">
            <?php if (! empty($section_about['left_image'])) : ?>
            <?php echo wp_get_attachment_image($section_about['left_image'], 'full', false, [
                  'loading' => 'lazy',
                ]); ?>
            <?php endif; ?>
            <span class="video-btn">
              <img src="<?php echo esc_url(MONA_SITE_TEMPLATE_URL . '/assets/images/about/btn-video.svg'); ?>"
                alt="<?php esc_attr_e('Phát video', 'monamedia'); ?>" loading="lazy">
            </span>
          </a>
          <?php else : ?>
          <div class="img-box">
            <?php if (! empty($section_about['left_image'])) : ?>
            <?php echo wp_get_attachment_image($section_about['left_image'], 'full', false, [
                  'loading' => 'lazy',
                ]); ?>
            <?php endif; ?>
          </div>
          <?php endif; ?>
        </div>

        <?php if ($intro_video_url) : ?>
        <div class="modal video-box" id="<?php echo esc_attr($intro_modal_id); ?>">
          <div class="video-pop">
            <video controls preload="metadata">
              <source src="<?php echo esc_url($intro_video_url); ?>" type="video/mp4">
            </video>
          </div>
        </div>
        <?php endif; ?>

        <div class="intro-end">
          <div class="intro-end-l">
            <div class="left_image ie-img" data-aos="fade-down" data-aos-delay="800">
              <?php if (! empty($section_about['left_image'])) : ?>
              <?php echo wp_get_attachment_image($section_about['left_image'], 'full', false, [
                    'loading' => 'lazy',
                  ]); ?>
              <?php endif; ?>
            </div>

            <div class="ie-img" data-aos="fade-down" data-aos-delay="800">
              <?php if (! empty($section_about['right_image'])) : ?>
              <?php echo wp_get_attachment_image($section_about['right_image'], 'full', false, [
                    'loading' => 'lazy',
                  ]); ?>
              <?php endif; ?>
            </div>
          </div>

          <div class="ie-content" data-aos="fade-up" data-aos-delay="1000">
            <?php if (! empty($section_about['long_description'])) : ?>
            <p class="text-14"><?php echo $section_about['long_description'] ?></p>
            <?php endif; ?>

            <?php if (! empty($section_about['link'])) : ?>
            <a class="n-view-more" href="<?php echo esc_url(mona_localize_url($section_about['link'])); ?>">
              <span><?php echo esc_html(function_exists('pll__') ? pll__('Tìm hiểu về chúng tôi') : 'Tìm hiểu về chúng tôi'); ?></span>
              <img src="<?php echo $template_url; ?>/assets/images/news/arr-view.svg" alt="" title="" loading="lazy">
            </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php
  if (!empty($section_business_sectors['show'])) :
    $sectors_data = $section_business_sectors;
  ?>
  <section class="is-full home-field" data-anchor="business-areas" data-anchor-label="<?php echo esc_attr__('Lĩnh vực', 'monamedia'); ?>">
    <div class="container">
      <div class="hf-inner">
        <div class="hf-top">
          <div class="hf-box" data-aos="fade-right" data-aos-delay="200">
            <?php if (!empty($sectors_data['title'])): ?>
            <h3 class="main-tt">
              <?php echo wp_kses(nl2br($sectors_data['title']), $allowed_tags); ?>
            </h3>
            <?php endif; ?>
          </div>

          <div class="hf-box" data-aos="fade-left" data-aos-delay="200">
            <?php if (!empty($sectors_data['description'])): ?>
            <p class="text-14"><?php echo esc_html($sectors_data['description']); ?></p>
            <?php endif; ?>

            <?php if (!empty($sectors_data['link'])): ?>
            <a class="n-view-more" href="<?php echo esc_url(mona_localize_url($sectors_data['link'])); ?>">
              <span><?php echo esc_html(function_exists('pll__') ? pll__('Tìm hiểu thêm') : 'Tìm hiểu thêm'); ?></span>
              <img src="<?php echo $template_url; ?>/assets/images/news/arr-view.svg" alt="icon" loading="lazy">
            </a>
            <?php endif; ?>
          </div>
        </div>

        <div class="field-ban-slide js-field-slide" data-aos="zoom-in" data-aos-delay="500">
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
                $mona_seen_sectors = [];
                if ($the_query->have_posts()) :
                  while ($the_query->have_posts()) : $the_query->the_post();

                    // WPML does not reliably filter this secondary query — guard by language + dedupe.
                    $mona_sid = get_the_ID();
                    if (! mona_post_in_lang($mona_sid) || isset($mona_seen_sectors[$mona_sid])) continue;
                    $mona_seen_sectors[$mona_sid] = true;

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
                      <!-- <p class="text-14">Phát triển và vận hành sân golf tiêu chuẩn quốc tế cùng các dịch vụ nghỉ dưỡng cao cấp, mang đến trải nghiệm thể thao và giải trí đẳng cấp tại Viêng Chăn. </p> -->

                      <a class="n-view-more" href="<?php the_permalink(); ?>">
                        <span><?php echo esc_html(function_exists('pll__') ? pll__('Xem chi tiết') : 'Xem chi tiết'); ?></span>
                        <img src="/template/assets/images/icons/arrow-right.svg" alt="" title="" loading="lazy">
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
        </div>

      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php
  $terms = [];

  if (! empty($section_project['show'])) {
    $template_url = MONA_SITE_TEMPLATE_URL;
    $taxonomy = 'danh-muc-du-an';

    $cur_lang = function_exists('pll_current_language') ? pll_current_language('slug') : apply_filters('wpml_current_language', null);

    $terms = get_terms([
      'taxonomy'   => $taxonomy,
      'hide_empty' => true,
      'lang'       => $cur_lang ?: '',
    ]);

    if (empty($terms) || is_wp_error($terms)) {
      // Fallback: translate canonical terms 8, 10
      $term_ids = [8, 10];
      $terms = [];
      foreach ($term_ids as $tid) {
        $tr_tid = function_exists('pll_get_term') ? pll_get_term($tid, $cur_lang) : apply_filters('wpml_object_id', $tid, $taxonomy, true, $cur_lang);
        if ($tr_tid) {
          $tobj = get_term($tr_tid, $taxonomy);
          if ($tobj && ! is_wp_error($tobj)) {
            $terms[] = $tobj;
          }
        }
      }
    }
  }

  /**
   * Không còn danh mục nào thì bỏ hẳn section, đừng render tiêu đề trơ trọi.
   *
   * Bản cũ chỉ xét cờ `show`, nên ngôn ngữ nào chưa có danh mục dự án sẽ ra một
   * màn hình chỉ có mỗi chữ "Dự án" rồi trắng trơn tới cuối — khách chụp lại
   * đúng cảnh này ở bản tiếng Trung. Ẩn section gọn hơn nhiều, và khi nào có dữ
   * liệu thì nó tự hiện lại, không phải sửa gì thêm.
   */
  if (! empty($section_project['show']) && ! empty($terms)) :
  ?>
  <section class="is-full news-req field-req" data-anchor="projects" data-anchor-label="<?php echo esc_attr__('Dự án', 'monamedia'); ?>">
    <div class="container">
      <div class="news-req-tt">
        <?php if (!empty($section_project['title'])) : ?>

        <h2 class="main-tt" data-aos="fade-right" data-aos-delay="200">
          <?php echo esc_html($section_project['title']); ?>
        </h2>
        <?php endif; ?>

        <div class="field-filter" data-aos="fade-left" data-aos-delay="200">
          <?php foreach ($terms as $key => $term) : ?>
          <div class="btn ff-link <?php echo $key === 0 ? 'is-current' : ''; ?>"
            data-tab="tab-project-<?php echo esc_attr($term->term_id); ?>">
            <?php echo esc_html($term->name); ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <?php if (!empty($terms) && !is_wp_error($terms)) :
        ?>
      <div class="nr-type-list">
        <?php foreach ($terms as $key => $term) :
              $project_query = new WP_Query([
                'post_type'      => 'du-an',
                'posts_per_page' => 10,
                'tax_query'      => [[
                  'taxonomy' => $taxonomy,
                  'field'    => 'slug',
                  'terms'    => $term->slug,
                ]],
              ]);

              if ($project_query->have_posts()) :
            ?>
        <div class="news-req-slide js-news-req <?php echo $key === 0 ? 'is-active' : ''; ?>"
          id="tab-project-<?php echo esc_attr($term->term_id); ?>">

          <div class="swiper">
            <div class="swiper-wrapper">
              <?php
              $mona_seen_proj = [];
              while ($project_query->have_posts()) : $project_query->the_post();
                $mona_pid = get_the_ID();
                if (! mona_post_in_lang($mona_pid) || isset($mona_seen_proj[$mona_pid])) continue;
                $mona_seen_proj[$mona_pid] = true;
              ?>
              <div class="swiper-slide">
                <?php get_template_part('partials/components/loops/item', 'project'); ?>
              </div>
              <?php endwhile; ?>
            </div>
          </div>

          <div class="field-action">
            <div class="field-action-pagin">
              <?php if ($project_query->found_posts > 3) : ?>
              <div class="swiper-pagination"></div>
              <?php endif; ?>
            </div>

            <?php if (!empty($section_project['link']['url'])) : ?>
            <a class="n-view-more" href="<?php echo esc_url($section_project['link']['url']); ?>"
              target="<?php echo esc_attr($section_project['link']['target'] ? $section_project['link']['target'] : '_self'); ?>">

              <span><?php echo esc_html(function_exists('pll__') ? pll__('Tất cả dự án') : 'Tất cả dự án'); ?></span>

              <img src="<?php echo esc_url($template_url . '/assets/images/news/arr-view.svg'); ?>" alt="icon"
                loading="lazy">
            </a>
            <?php endif; ?>
          </div>
        </div>
        <?php
              endif;
              wp_reset_postdata();
            endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </section>
  <?php endif; ?>

  <?php
  if (!empty($section_activity)) {
    mona_render_section($section_activity);
  }
  ?>

  <?php
  if (!empty($section_contact)) {
    mona_render_section($section_contact);
  }
  ?>

  <?php if (! empty($section_cooperation['show'])) : ?>
  <section class="is-full home-bottom" data-anchor="cooperation" data-anchor-label="<?php echo esc_attr__('Hợp tác', 'monamedia'); ?>">
    <div class="home-bot-inner" data-aos="zoom-in" data-aos-delay="200">
      <div class="home-bot-bg">
        <img src="/template/assets/images/home/bg-bot.jpg" alt="" title="" loading="lazy">
      </div>
      <div class="home-bot-content">
        <div class="hb-txt">
          <div class="hb-ic" data-aos="zoom-in" data-aos-delay="600">
            <?php if (! empty($section_cooperation['logo'])) : ?>
            <?php echo wp_get_attachment_image($section_cooperation['logo'], 'full', false, ['loading' => 'lazy']); ?>
            <?php endif; ?>
          </div>

          <?php if (! empty($section_cooperation['title'])) : ?>
          <h2 class="main-tt" data-aos="fade-up" data-aos-delay="800">
            <?php echo wrap_lines_from_second_with_span($section_cooperation['title']); ?>
          </h2>
          <?php endif; ?>

          <?php if (! empty($section_cooperation['link'])) : ?>
          <a class="btn" href="<?php echo esc_url(mona_localize_url($section_cooperation['link'])); ?>" data-aos="fade-up"
            data-aos-delay="1000"><span><?php echo esc_html(function_exists('pll__') ? pll__('Tìm hiểu về chúng tôi') : 'Tìm hiểu về chúng tôi'); ?> </span></a>
          <?php endif; ?>
        </div>

        <?php if (! empty($section_cooperation['contact_info'])) :
            get_template_part('partials/components/contact-info', null);
          ?>
        <?php endif; ?>

        <?php
          // Cùng lý do như khối thành tựu: đổi sang bản dịch của bài web-builder
          $web_builder_id = mona_web_builder_id($section_cooperation['contact_info'] ?? null);

          if ($web_builder_id) :
            $contactInfo = get_field('contact_info', $web_builder_id);

            if ($contactInfo) :
              get_template_part('partials/components/contact-info', null, [
                'contact-info' => $contactInfo
              ]);
            endif;
          endif;
          ?>

      </div>
    </div>
    <?php if (!empty($footer_bottom['show'])): ?>
    <div class="footer-copyright">
      <div class="container">
        <div class="fc-block">
          <?php if (!empty($footer_bottom['copyright_text'])): ?>
          <p class="text-14"><?php echo esc_html($footer_bottom['copyright_text']); ?></p>
          <?php endif; ?>
          <div class="fc-end">
            <?php
                $socials_web_builder_id = $footer_bottom['contact_socials'] ?? null;
                if ($socials_web_builder_id) :
                  $socialsInfo = get_field('socials', $socials_web_builder_id);
                  if ($socialsInfo) :
                    get_template_part('partials/components/contact-socials', null, [
                      'socials' => $socialsInfo
                    ]);
                  endif;
                endif;
                ?>

            <?php
            $footer_links = $footer_bottom['links'] ?? [];
            $has_valid_links = false;
            if (!empty($footer_links) && is_array($footer_links)) {
              foreach ($footer_links as $fl) {
                if (!empty($fl['link']['title']) || !empty($fl['link']['url'])) {
                  $has_valid_links = true;
                  break;
                }
              }
            }
            if (!$has_valid_links) {
              $opt_count = (int) get_option('options_footer_bottom_links', 0);
              if ($opt_count > 0) {
                $footer_links = [];
                for ($i = 0; $i < $opt_count; $i++) {
                  $raw_link = get_option("options_footer_bottom_links_{$i}_link");
                  if (!empty($raw_link)) {
                    $footer_links[] = ['link' => maybe_unserialize($raw_link)];
                  }
                }
              }
            }
            ?>

            <?php if (!empty($footer_links) && is_array($footer_links)) : ?>
            <?php
            $mona_fp_lang = mona_ui_lang();
            ?>
            <?php foreach ($footer_links as $item) : ?>
            <?php if (!empty($item['link']) && is_array($item['link'])) :
              $mona_fp_title = $item['link']['title'] ?? '';
              $mona_fp_url   = $item['link']['url'] ?? '';
              if (function_exists('pll__') && $mona_fp_title !== '') {
                $mona_fp_title = pll__($mona_fp_title);
              }
              if (!empty($mona_fp_url)) {
                $mona_fp_url = mona_localize_url($mona_fp_url);
              }
              ?>
              <?php if ($mona_fp_title && $mona_fp_url): ?>
            <a class="fc-link" href="<?php echo esc_url($mona_fp_url); ?>"
              target="<?php echo !empty($item['link']['target']) ? esc_attr($item['link']['target']) : '_self'; ?>">
              <?php echo esc_html($mona_fp_title); ?>
            </a>
              <?php endif; ?>
            <?php endif; ?>
            <?php endforeach; ?>
            <?php endif; ?>

            <?php
            // Thanh copyright của trang chủ là bản dựng riêng, không phải thẻ
            // <footer> ở footer.php, nên phải gọi lại bộ chuyển ngôn ngữ ở đây.
            get_template_part('partials/components/lang-switcher');
            ?>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </section>
  <?php endif; ?>
</div>
<ul id="menu-fullpage">
  <li data-menuanchor="page-1"><a href="#1">
      <div class="txt-animate">KN Vientiane Group</div>
    </a></li>
  <li data-menuanchor="page-2"><a href="#2">
      <div class="txt-animate">KN Vientiane Group 2</div>
    </a></li>
  <li data-menuanchor="page-3"><a href="#3">
      <div class="txt-animate">KN Vientiane Group 3</div>
    </a></li>
  <li data-menuanchor="page-4"><a href="#4">
      <div class="txt-animate">KN Vientiane Group 4</div>
    </a></li>
  <li data-menuanchor="page-5"><a href="#5">
      <div class="txt-animate">KN Vientiane Group 5</div>
    </a></li>
  <li data-menuanchor="page-6"><a href="#6">
      <div class="txt-animate">KN Vientiane Group 6</div>
    </a></li>
  <li data-menuanchor="page-7"><a href="#7">
      <div class="txt-animate">KN Vientiane Group 7</div>
    </a></li>
</ul>
<?php
get_footer();