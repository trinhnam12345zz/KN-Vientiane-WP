<?php
defined('ABSPATH') || exit;

if (!isset($args['object_id']))
  return;

$data = get_field('section_area_activity', $args['object_id']);
$allowed_tags = [
  'br' => [],
  'br/' => [],
];

if (empty($data))
  return;
?>

<section class="is-full activity" data-anchor="operating-areas">
  <div class="container">
    <div class="acti-inner">
      <div class="acti-box">
        <?php if (!empty($data['title'])): ?>
          <h2 class="main-tt" data-aos="fade-up" data-aos-delay="200">
            <?php echo wp_kses(nl2br($data['title']), $allowed_tags); ?></h2>
        <?php endif; ?>

        <?php if (!empty($data['description'])): ?>
          <div class="text-14" data-aos="fade-up" data-aos-delay="300">
            <?php echo wp_kses(nl2br($data['description']), $allowed_tags); ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($data['link'])): ?>
          <a class="btn" href="<?php echo esc_url(mona_localize_url($data['link'])); ?>" data-aos="fade-up" data-aos-delay="400">
            <span><?php echo esc_html(function_exists('pll__') ? pll__('Hợp tác cùng chúng tôi') : 'Hợp tác cùng chúng tôi'); ?></span>
          </a>
        <?php endif; ?>
      </div>
      <div class="acti-box">
        <?php if (!empty($data['image'])): ?>
          <div class="map-img" data-aos="zoom-in-up" data-aos-delay="300">
            <?php echo wp_get_attachment_image($data['image'], 'full', false, ['loading' => 'lazy']); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>