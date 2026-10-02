<?php

/**
 * Template Name: Phát triển bền vững
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
  'overview' => $fields['section_overview'] ?? [],
  'certifi' => $fields['section_certifi'] ?? [],
  'commit' => $fields['section_commit'] ?? [],
  'contact' => $fields['section_contact'] ?? [],
];

?>

<?php
/**
 * section_banner
 */
$banner = $sections['banner'];

if (!empty($banner['show'])) : ?>

<section class="banner-main">
    <?php mona_output_breadcrumb(); ?>

    <div class="container">
        <div class="banner-info">
            <div class="logo-mark">
                <img src="/template/assets/images/news/logo-banner.png" alt="" title="" loading="lazy">
            </div>
            <?php if (!empty($banner['title'])): ?>
            <h2 class="main-tt"><?php echo esc_html($banner['title']); ?></h2>
            <?php endif; ?>

            <?php if (!empty($banner['description'])): ?>
            <p class="tt-desc"><?php echo esc_html($banner['description']); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($banner['stats_block']) && is_array($banner['stats_block'])): ?>
            <div class="exp-list">
                <?php foreach ($banner['stats_block'] as $index => $item) : ?>
                <div class="exp-item">
                    <p class="exp-num js-count"><?php echo esc_html($item['number']); ?></p>
                    <p><?php echo nl2br(esc_html($item['text'])); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php endif; ?>

<?php
/**
 * section_overview
 */
$overview = $sections['overview'];

if (!empty($overview['show'])) : ?>

<section class="overview py-8 pt-16">
    <div class="container">
        <?php if (!empty($overview['title'])): ?>
        <h2 class="main-tt"><?php echo esc_html($overview['title']); ?></h2>
        <?php endif; ?>
        <div class="swiper js-coverflow-slider">
            <div class="swiper-wrapper">
                <?php if (!empty($overview['list']) && is_array($overview['list'])): ?>
                    <?php foreach ($overview['list'] as $index => $item) : ?>
                        <div class="swiper-slide">
                            <div class="overview-box">
                                <div class="overview-img">
                                    <?php echo wp_get_attachment_image($item['image'], 'full', false, ['loading' => 'lazy']); ?>
                                </div>
                                <div class="overview-text">
                                    <?php if (!empty($item['title'])): ?>
                                    <h3 class="overview-tt"><?php echo esc_html($item['title']); ?></h3>
                                    <?php endif; ?>
                                </div>
                                <div class="overview-content">
                                    <?php if (!empty($item['title'])): ?>
                                    <h3 class="overview-tt"><?php echo esc_html($item['title']); ?></h3>
                                    <?php endif; ?>

                                    <?php if (!empty($item['description'])): ?>
                                    <p class="tt-desc"><?php echo esc_html($item['description']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php // Bố cục 1 thẻ/màn hình nên phải có nút điều hướng, trước đây
                  // slider chuyển bằng cách bấm vào thẻ bên cạnh nên không cần. ?>
            <div class="overview-nav swiper-navigation">
                <div class="prev" role="button" tabindex="0" aria-label="<?php esc_attr_e('Xem mục trước', 'monamedia'); ?>">
                    <img src="<?php echo MONA_SITE_TEMPLATE_URL ?>/assets/images/icons/arr-slide.svg" alt="" loading="lazy">
                </div>
                <div class="next" role="button" tabindex="0" aria-label="<?php esc_attr_e('Xem mục kế tiếp', 'monamedia'); ?>">
                    <img src="<?php echo MONA_SITE_TEMPLATE_URL ?>/assets/images/icons/arr-slide.svg" alt="" loading="lazy">
                </div>
            </div>
            <div class="overview-pagi swiper-pagination"></div>
        </div>
    </div>
</section>

<?php endif; ?>

<?php
/**
 * section_certifi
 */
$certifi = $sections['certifi'];
if (!empty($certifi['show'])) : ?>

<section class="certifi py-8">
    <div class="container">
        <div class="certifi-wrap">
            <div class="certifi-content certifi-item">
                <?php if (!empty($certifi['title'])): ?>
                <h2 class="main-tt"><?php echo nl2br(esc_html($certifi['title'])); ?></h2>
                <?php endif; ?>

                <?php if (!empty($certifi['description'])): ?>
                <p class="tt-desc"><?php echo nl2br(esc_html($certifi['description'])); ?></p>
                <?php endif; ?>

                <?php if (!empty($certifi['features'])): ?>
                <ul>
                    <?php foreach ($certifi['features'] as $feature): ?>
                        <li><?php echo esc_html($feature["text"]); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
            <div class="certifi-slider certifi-item">
                <div class="certifi-thumb">
                    <div class="slider-prev">
                        <img src="/template/assets/images/stable/prev.svg" alt="" title="" loading="lazy">
                    </div>
                    <div class="swiper js-slider-thumbs">
                        <div class="swiper-wrapper">
                            <?php if (!empty($certifi['list']) && is_array($certifi['list'])): ?>
                                <?php foreach ($certifi['list'] as $index => $item) : ?>
                                     <div class="swiper-slide">
                                        <div class="slider-image">
                                            <?php echo wp_get_attachment_image($item['image'], 'full', false, [
                                                        'loading' => 'lazy',
                                                    ]); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="slider-next">
                        <img src="/template/assets/images/stable/next.svg" alt="" title="" loading="lazy">
                    </div>
                </div>

                <div class="swiper js-slider-images js-gallery">
                    <div class="swiper-wrapper">
                            <?php if (!empty($certifi['list']) && is_array($certifi['list'])): ?>
                                    <?php foreach ($certifi['list'] as $index => $item) : ?>
                                        <div class="swiper-slide">
                                            <a class="slider-image gItem" href="<?php echo esc_url($item['image']); ?>">
                                                <?php echo wp_get_attachment_image($item['image'], 'full', false, [
                                                    'loading' => 'lazy',
                                                ]); ?>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php endif; ?>

<?php
/**
 * section_commit
 */ 
$commit = $sections['commit'];
if (!empty($commit['show'])) : ?>
<section class="commit py-8 pb-16">
        <div class="container">
          <div class="content-wrapper">
            <div class="left-image">
                <?php echo wp_get_attachment_image($commit['left_image'], 'full', false, [
                                                        'loading' => 'lazy',
                                                    ]); ?>
            </div>
            <div class="right-content">
<?php if (!empty($commit['title'])): ?>
              <h2 class="main-tt"><?php echo nl2br(esc_html($commit['title'])); ?></h2>
<?php endif; ?>
<?php if (!empty($commit['description'])): ?>
    <?php
              echo wp_kses_post(
                wrap_lines_from_second_with_p(
                  $commit['description']
                )
              );
              ?>
<?php endif; ?>

              <?php if (!empty($commit['right_image'])) : ?>
          <div class="bottom-images">
            <?php foreach ($commit['right_image'] as $image_id) : ?>
              <div class="small-image">
                <?php echo wp_get_attachment_image($image_id, 'medium', false, [
                    'loading' => 'lazy',
                ]); ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
            </div>
          </div>
        </div>
      </section>
<?php endif; ?>

<?php
/**
 * section_contact
 */
$contact = $sections['contact'];

if (!empty($contact)) {
  mona_render_section($contact);
}
?>

<?php get_footer(); ?>