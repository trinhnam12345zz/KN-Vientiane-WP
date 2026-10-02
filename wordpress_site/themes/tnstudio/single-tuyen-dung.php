<?php
defined('ABSPATH') || exit;

$current_page_id = get_the_ID();

$acf_fields = get_fields($current_page_id);
$expire = $acf_fields['expire'] ?? null;
$is_expire = ! empty($expire) && strtotime($expire) < time() ? true : false;
$salary = $acf_fields['salary'] ?? null;
$amount = $acf_fields['amount'] ?? null;
$level = $acf_fields['level'] ?? null;
$experience = $acf_fields['experience'] ?? null;
$location = $acf_fields['location'] ?? null;
$method = $acf_fields['method'] ?? null;
$gender = $acf_fields['gender'] ?? null;
$gallery = $acf_fields['gallery'] ?? null;
$section_apply = ! empty($acf_fields['section_apply']) ? mona_web_builder_id($acf_fields['section_apply']) : mona_web_builder_id(488);

$infos = [
  [
    'label' => (function_exists('pll__') ? pll__('Số lượng') : 'Số lượng'),
    'value' => $amount,
    'icon' => '/template/assets/images/recruit/ic1.svg',
  ],
  [
    'label' => (function_exists('pll__') ? pll__('Cấp bậc') : 'Cấp bậc'),
    'value' => $level,
    'icon' => '/template/assets/images/recruit/ic2.svg',
  ],
  [
    'label' => (function_exists('pll__') ? pll__('Kinh nghiệm') : 'Kinh nghiệm'),
    'value' => $experience,
    'icon' => '/template/assets/images/recruit/ic3.svg',
  ],
  [
    'label' => (function_exists('pll__') ? pll__('Địa điểm làm việc') : 'Địa điểm làm việc'),
    'value' => $location,
    'icon' => '/template/assets/images/recruit/ic4.svg',
  ],
  [
    'label' => (function_exists('pll__') ? pll__('Hình thức làm việc') : 'Hình thức làm việc'),
    'value' => $method,
    'icon' => '/template/assets/images/recruit/ic5.svg',
  ],
  [
    'label' => (function_exists('pll__') ? pll__('Giới tính') : 'Giới tính'),
    'value' => $gender,
    'icon' => '/template/assets/images/recruit/ic6.svg',
  ],
];

get_header();


?>
<section class="banner-main">
  <?php mona_output_breadcrumb(); ?>
  <div class="container">
    <div class="banner-info">
      <div class="logo-mark">
        <img src="/template/assets/images/news/logo-banner.png" alt="" title="" loading="lazy">
      </div>
      <?php the_title('<h1 class="main-tt">', '</h1>'); ?>
    </div>
  </div>
</section>

<section class="recruit-main">
  <div class="container">
    <div class="recruit-inner">
      <div class="recruit-box">
        <h2 class="title-36"><?php echo esc_html(function_exists('pll__') ? pll__('CHI TIẾT TIN TUYỂN DỤNG') : 'CHI TIẾT TIN TUYỂN DỤNG'); ?></h2>
        <div class="mona-content">
          <?php
          ob_start();
          the_content();
          $content = ob_get_clean();

          if ($content) {
            echo $content;
          } else {
            echo '<p class="hide-sitename">' . (function_exists('pll__') ? pll__('Nội dung sẽ sớm được cập nhật') : 'Nội dung sẽ sớm được cập nhật') . '</p>';
          }
          ?>
        </div>
        <?php if (! empty($gallery)): ?>
          <div class="team-img">
            <div class="team-top">
              <h2 class="text-20"><?php echo esc_html(function_exists('pll__') ? pll__('Hình ảnh đội ngũ') : 'Hình ảnh đội ngũ'); ?></h2>
              <?php if (count($gallery) > 3) : ?>
                <div class="swiper-nav yel swiper-navigation">
                  <div class="prev"> <img src="/template/assets/images/icons/arrow-left-yel.svg" alt="" title=""
                      loading="lazy">
                  </div>
                  <div class="next"> <img src="/template/assets/images/icons/arrow-left-yel.svg" alt="" title=""
                      loading="lazy">
                  </div>
                </div>
              <?php endif; ?>
            </div>
            <div class="team-img-slide js-gallery js-team-slide">
              <div class="swiper">
                <div class="swiper-wrapper">
                  <?php foreach ($gallery as $image) : ?>
                    <div class="swiper-slide">
                      <a class="recruitdt-content-img gItem"
                        href="<?php echo wp_get_attachment_image_url($image, 'full'); ?>">
                        <div class="img-box">
                          <?php echo wp_get_attachment_image($image, 'full', false, [
                            'loading' => 'lazy',
                          ]); ?>
                        </div>
                      </a>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="swiper-pagination"></div>
            </div>
          </div>
        <?php endif; ?>
      </div>
      <div class="recruit-sub">
        <div class="sub-benefit pd-24">
          <div class="text-18 fw-b"><?php echo esc_html(function_exists('pll__') ? pll__('Thông tin chung') : 'Thông tin chung'); ?></div>
          <?php if (! empty($salary)) : ?>
            <div class="text-16"><?php echo esc_html($salary); ?></div>
          <?php endif; ?>

          <?php if (! empty($infos)): ?>
            <ul class="rec-short-list">
              <?php foreach ($infos as $index => $item) : ?>
                <?php if (! empty($item['value'])): ?>
                  <li class="rec-short-item">
                    <?php if (! empty($item['icon'])): ?>
                      <img src="<?php echo $item['icon']; ?>" alt="Image" loading="lazy">

                    <?php endif; ?>

                    <div class="r-txt">
                      <?php if (! empty($item['label'])): ?>
                        <p class="text-14 fw-t"><?php echo esc_html($item['label']); ?></p>
                      <?php endif; ?>

                      <p class="rtext-14"><?php echo esc_html($item['value']); ?></p>
                    </div>
                  </li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <?php if (! $is_expire) : ?>
            <a class="btn" href="#formRecruit" rel="nofollow">
              <span><?php echo esc_html(function_exists('pll__') ? pll__('Ứng tuyển ngay') : 'Ứng tuyển ngay') ?></span>
              <img src="/template/assets/images/icons/arrow-down.svg" alt="Image" title="Image" loading="lazy">
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
/**
 * section_other_recruit
 */

$other_recruit_query = new WP_Query([
  'post_type' => 'tuyen-dung',
  'posts_per_page' => 12,
  'post__not_in' => [$current_page_id],
]);
?>

<section class="recruit-req">
  <div class="container">
    <div class="recruit-req-inner">
      <div class="rr-top">
        <div class="main-tt"><?php echo esc_html(function_exists('pll__') ? pll__('CÁC VỊ TRÍ KHÁC') : 'CÁC VỊ TRÍ KHÁC'); ?></div>

        <?php if ($other_recruit_query->found_posts > 1) : ?>
          <div class="swiper-nav yel swiper-navigation">
            <div class="prev"> <img src="/template/assets/images/icons/arrow-left-yel.svg" alt="" title="" loading="lazy">
            </div>
            <div class="next"> <img src="/template/assets/images/icons/arrow-left-yel.svg" alt="" title="" loading="lazy">
            </div>
          </div>
        <?php endif; ?>
      </div>
      <div class="rr-slide js-recruit-slide">
        <div class="swiper">
          <div class="swiper-wrapper">
            <?php if ($other_recruit_query->have_posts()) : ?>
              <?php while ($other_recruit_query->have_posts()) : $other_recruit_query->the_post(); ?>
                <div class="swiper-slide">
                  <?php get_template_part('partials/components/loops/item', 'recruit'); ?>
                </div>
              <?php endwhile; ?>
              <?php wp_reset_postdata(); ?>
            <?php else: ?>
              <p class="hide-sitename"><?php echo esc_html(function_exists('pll__') ? pll__('Không có vị trí tuyển dụng nào khác') : 'Không có vị trí tuyển dụng nào khác'); ?></p>
            <?php endif; ?>
          </div>
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </div>
  </div>
</section>

<?php if ($section_apply) mona_render_section($section_apply, ['form_id' => 'formRecruit']); ?>
<script>
  (function() {
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('formRecruit');
      if (!form) {
        document.querySelector('.btn-apply')?.remove();
      }
    });
  })();
</script>

<div class="mountain-decor js-add-active"></div>
<?php
get_footer();
