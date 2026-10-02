<?php
defined('ABSPATH') || exit;

$template_url = MONA_SITE_TEMPLATE_URL;

// Ưu tiên ID truyền từ args, nếu không có thì lấy ID hiện tại trong loop
$post_id = $args['post_id'] ?? get_the_ID();
$cur_lang = function_exists('mona_ui_lang') ? mona_ui_lang() : 'vi';

if ($cur_lang !== 'vi' && function_exists('mona_get_translated_post_id')) {
    $tr_id = mona_get_translated_post_id((int)$post_id, $cur_lang);
    if ($tr_id) {
        $post_id = $tr_id;
    }
}

$link  = get_permalink($post_id);
if ($cur_lang !== 'vi' && function_exists('mona_localize_url')) {
    $link = mona_localize_url($link, $cur_lang);
}
$title = get_field('project_title_short', $post_id) ?: get_the_title($post_id);

$btn_text = function_exists('pll__') ? pll__('Dự án chi tiết') : 'Dự án chi tiết';
?>

<div class="project-item" data-aos="fade-up" data-aos-delay="200">
  <div class="img-pj">
    <?php if (has_post_thumbnail($post_id)) : ?>
    <?php echo get_the_post_thumbnail($post_id, 'full', [
        'loading' => 'lazy',
        'class'   => 'img-main',
      ]); ?>
    <?php else : ?>
    <img src="<?php echo $template_url; ?>/assets/images/default.jpg" alt="No image">
    <?php endif; ?>

    <div class="pj-hub">
      <div class="pj-hub-ic">
        <img src="<?php echo $template_url; ?>/assets/images/home/icon-bot.png" alt="icon" loading="lazy">
      </div>

      <p class="text-16">
        <?php echo wp_trim_words(get_the_excerpt($post_id), 30, '...'); ?>
      </p>

      <?php if ($link) : ?>
      <a class="btn" href="<?php echo esc_url($link); ?>">
        <span><?php echo esc_html($btn_text); ?></span>
        <img src="<?php echo $template_url; ?>/assets/images/icons/arrow-right.svg" alt="arrow" loading="lazy">
      </a>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($link) : ?>
  <a class="pj-name" href="<?php echo esc_url($link); ?>">
    <?php echo esc_html($title); ?>
  </a>
  <?php else : ?>
  <p class="pj-name"><?php echo esc_html($title); ?></p>
  <?php endif; ?>
</div>