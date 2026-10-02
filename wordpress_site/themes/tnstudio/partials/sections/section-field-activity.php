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

<section class="is-full home-field" data-anchor="business-areas">
  <div class="container">
    <div class="hf-inner">

      <div class="hf-top">
        <div class="hf-box" data-aos="fade-right" data-aos-delay="200">
          <?php if (!empty($data['title'])): ?>
            <h3 class="main-tt">
              <?php echo wp_kses(nl2br($data['title']), $allowed_tags); ?>
            </h3>
          <?php endif; ?>
        </div>

        <div class="hf-box" data-aos="fade-left" data-aos-delay="200">
          <?php if (!empty($data['description'])): ?>
            <p class="text-14"><?php echo esc_html($data['description']); ?></p>
          <?php endif; ?>

          <?php if (!empty($data['link'])): ?>
            <a class="n-view-more" href="<?php echo esc_url(mona_localize_url($data['link'])); ?>">
              <span>Tìm hiểu về chúng tôi</span>
              <img src="<?php echo $template_url; ?>/assets/images/news/arr-view.svg" alt="icon" loading="lazy">
            </a>
          <?php endif; ?>
        </div>
      </div>



    </div>
  </div>
</section>