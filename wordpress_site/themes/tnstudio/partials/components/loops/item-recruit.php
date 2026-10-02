<?php
defined('ABSPATH') || exit;

$post_id = get_the_ID();
$post_permalink = get_the_permalink();

$expire = get_field('expire', $post_id);
$is_expire = ! empty($expire) && strtotime($expire) < time() ? true : false;
$method = get_field('method', $post_id);
$experience = get_field('experience', $post_id);
$location = get_field('location', $post_id);

$vitri_terms = get_the_terms($post_id, 'vi-tri-tuyen-dung');
$vitri_name = '';
if (! empty($vitri_terms) && ! is_wp_error($vitri_terms)) {
    $vitri_name = $vitri_terms[0]->name;
}
?>
<article class="recruit-item">
  <div class="recruit-item-i">
    <p class="text-20 fw-sb"><?php the_title(); ?></p>
    <div class="text-14"><?php the_excerpt(); ?></div>
  </div>

  <div class="recruit-action">
    <ul class="rec-info">
      <?php if (! empty($vitri_name)) : ?>
        <li class="rec-i-item"><span><?php echo esc_html($vitri_name); ?></span></li>
      <?php endif; ?>

      <?php if (! empty($method)) : ?>
        <li class="rec-i-item"><span><?php echo esc_html($method); ?></span></li>
      <?php endif; ?>

      <?php if (! empty($experience)) : ?>
        <li class="rec-i-item">
          <span><?php echo (function_exists('pll__') ? pll__('Kinh nghiệm: ') : 'Kinh nghiệm: ') . esc_html($experience); ?></span>
        </li>
      <?php endif; ?>

      <?php if (! empty($location)) : ?>
        <li class="rec-i-item"><span><?php echo esc_html($location); ?></span></li>
      <?php endif; ?>

      <?php if (! empty($is_expire)) : ?>
        <li class="rec-i-item t-red"><span class="rc-status"
            style="color:#C33025;"><?php echo esc_html(function_exists('pll__') ? pll__('Hết hạn') : 'Hết hạn'); ?></span></li>
      <?php else : ?>
        <li class="rec-i-item t-green"><span class="rc-status"><?php echo esc_html(function_exists('pll__') ? pll__('Đang tuyển') : 'Đang tuyển'); ?></span></li>
      <?php endif; ?>
    </ul>
    <div class="rec-btn">
      <a class="btn" href="<?php echo $post_permalink; ?>" <?php disabled($is_expire) ?>>
        <span><?php echo esc_html(function_exists('pll__') ? pll__('Ứng tuyển') : 'Ứng tuyển'); ?></span>
      </a>
    </div>
  </div>
</article>