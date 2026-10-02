<?php
defined('ABSPATH') || exit;

// 1. Kiểm tra biến $args và $data an toàn
if (empty($args['data']) || !is_array($args['data'])) {
    return;
}

$data = $args['data'];
$promotion = $data['promotion'] ?? null;

if (empty($promotion)) {
    return;
}

$img_url = $promotion['image']['url'] ?? '';
$promo_title = $promotion['title'] ?? '';

// Translate promotion title & CTA based on WPML current language.
$mona_wp_lang = mona_ui_lang();
if (function_exists('pll__') && $promo_title !== '') {
  $promo_title = pll__($promo_title);
}
$mona_wp_cta = function_exists('pll__') ? pll__('Xem ngay') : 'Xem ngay';

$link_url = '#';
$link_target = '_self';

if (!empty($promotion['url'])) {
    if (is_array($promotion['url'])) {
        $link_url = $promotion['url']['url'] ?? '#';
        $link_target = $promotion['url']['target'] ?? '_self';
    } else {
        $link_url = $promotion['url'];
    }
}
?>

<div class="preview-box">
    <?php if (!empty($img_url)): ?>
        <img src="<?php echo esc_url($img_url); ?>" alt="" title="" loading="lazy">
    <?php endif; ?>

    <div class="preview-content">
        <?php if (!empty($promo_title)): ?>
            <p class="title-24"><?php echo esc_html($promo_title); ?></p>
        <?php endif; ?>

        <?php if (!empty($promotion['url'])): ?>
            <a class="n-view-more" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                <span><?php echo esc_html($mona_wp_cta); ?></span>
                <img src="/template/assets/images/icons/arrow-right.svg" alt="" title="" loading="lazy">
            </a>
        <?php endif; ?>
    </div>
</div>