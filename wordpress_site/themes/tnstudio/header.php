<?php

/**
 * The template for displaying header.
 *
 * @package MONA.Media / Website
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

// Body class
if (wp_is_mobile()) {
  $body = 'mobile-detect';
} else {
  $body = 'desktop-detect';
}

$menu_html = '';

// Contact button URL — translated to the current language when WPML is active.
$mona_contact_page = get_page_by_path('lien-he');
$mona_contact_id   = $mona_contact_page ? $mona_contact_page->ID : 0;
$mona_contact_id   = function_exists('pll_get_post') ? (pll_get_post($mona_contact_id) ?: $mona_contact_id) : apply_filters('wpml_object_id', $mona_contact_id, 'page', true);
$mona_contact_url  = $mona_contact_id ? get_permalink($mona_contact_id) : (MONA_SITE_URL . '/lien-he');

// Active languages (WPML), sorted VI - LA - EN - ZH - KO. Empty when WPML is off.
$mona_languages = mona_get_languages();

$cur_lang = function_exists('pll_current_language') ? pll_current_language('slug') : apply_filters('wpml_current_language', null);
if (empty($cur_lang)) {
  $cur_lang = 'vi';
}

$menu_html = wp_nav_menu(array(
  'container'       => false,
  'container_class' => '',
  'menu_class'      => 'menu',
  'theme_location'  => 'primary-menu',
  'before'          => '',
  'after'           => '',
  'link_before'     => '',
  'link_after'      => '',
  'fallback_cb'     => false,
  'echo'            => false,
  'walker'          => new Mona_Walker_Nav_Menu,
));

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <!-- Meta ================================================== -->
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport"
    content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
  <?php wp_site_icon(); ?>
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
  <?php wp_head(); ?>
</head>

<body <?php body_class($body); ?>>
  <?php
  // Menu
  // $menu_location = '';

  // if (has_nav_menu('primary-menu')) {
  //   $menu_location = 'primary-menu';
  // } elseif (has_nav_menu('header-menu')) {
  //   $menu_location = 'header-menu';
  // }

  // if ($menu_location) {
  //   mona_cached_nav_menu(array(
  //     'container' => false,
  //     'container_class' => '',
  //     'menu_class' => 'menu-list',
  //     'theme_location' => $menu_location,
  //     'before' => '',
  //     'after' => '',
  //     'link_before' => '',
  //     'link_after' => '',
  //     'fallback_cb' => false,
  //     'walker' => new Mona_Walker_Nav_Menu,
  //   ));
  // }
  // 
  ?>

  <header class="header js-header">
    <div class="container">
      <div class="header-wrap">
        <div class="header-group-left">
          <div class="menu-btn js-toggle-menu js-bar"><img
              src="<?php echo MONA_SITE_TEMPLATE_URL ?>/assets/images/header/menu-btn.svg" alt="" title=""
              loading="lazy"><span>Menu</span>
          </div>
          <?php
          $mona_current_lang = 'VI';
          $mona_current_code = 'vi';
          foreach ($mona_languages as $mona_lang) {
            if (! empty($mona_lang['active'])) {
              $mona_current_lang = mona_lang_label($mona_lang['language_code']);
              $mona_current_code = $mona_lang['language_code'];
            }
          }
          ?>
          <div class="lang-btn js-lang<?php echo ! empty($mona_languages) ? ' has-dropdown' : ''; ?>">
            <div class="lang-select js-lang-toggle"><img class="lang-flag"
                src="<?php echo esc_url(mona_lang_flag_url($mona_current_code)); ?>" alt="<?php echo esc_attr($mona_current_lang); ?>"
                title="" loading="lazy">
              <p class="l-selected"><?php echo esc_html($mona_current_lang); ?> </p>
              <div class="arr-select"> <img src="<?php echo MONA_SITE_TEMPLATE_URL ?>/assets/images/header/ic-drop.svg"
                  alt="" title="" loading="lazy">
              </div>
            </div>
            <?php if (! empty($mona_languages)): ?>
            <ul class="lang-dropdown js-lang-menu">
              <?php foreach ($mona_languages as $mona_lang): ?>
              <li<?php echo ! empty($mona_lang['active']) ? ' class="is-active"' : ''; ?>>
                <a href="<?php echo esc_url($mona_lang['url']); ?>">
                  <img class="lang-flag" src="<?php echo esc_url(mona_lang_flag_url($mona_lang['language_code'])); ?>"
                    alt="<?php echo esc_attr(mona_lang_label($mona_lang['language_code'])); ?>" title="" loading="lazy">
                  <span><?php echo esc_html(mona_lang_label($mona_lang['language_code'])); ?></span>
                </a>
              </li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
          </div>
          <?php if (! empty($menu_html)): ?>
          <nav class="header-nav js-menu">
            <div class="js-btn-close"> <img src="<?php echo MONA_SITE_TEMPLATE_URL ?>/assets/images/icons/close-btn.svg"
                alt="" title="" loading="lazy">
            </div>
            <div class="container">
              <?php echo $menu_html; ?>
            </div>
          </nav>
          <?php endif; ?>
        </div>
        <a class="custom-logo-link" href="#!">
          <!-- <img src="<?php echo MONA_SITE_TEMPLATE_URL ?>/assets/images/header/header-logo-white.png" alt="" title=""
            loading="lazy"> -->
          <?php
          /**
           * Logo header, có dự phòng theo ngôn ngữ.
           *
           * the_custom_logo() đọc theme mod `custom_logo`, mà WPML lưu theme mod
           * riêng cho từng ngôn ngữ. Ngôn ngữ nào chưa vào Tùy biến đặt logo thì
           * hàm trả về RỖNG — header mất logo, chỉ còn nút menu với nút liên hệ.
           * Đúng cảnh khách chụp lại ở bản tiếng Trung.
           *
           * Thiếu thì mượn logo của ngôn ngữ mặc định. Ngôn ngữ nào tự đặt logo
           * riêng vẫn dùng logo của mình, không bị đè.
           */
          $mona_logo = get_custom_logo();

          if (empty($mona_logo)) {
            $mona_cur_lang_code = apply_filters('wpml_current_language', null);
            $mona_def_lang_code = apply_filters('wpml_default_language', null);

            if ($mona_cur_lang_code && $mona_def_lang_code && $mona_cur_lang_code !== $mona_def_lang_code) {
              do_action('wpml_switch_language', $mona_def_lang_code);
              $mona_logo = get_custom_logo();
              do_action('wpml_switch_language', $mona_cur_lang_code);
            }
          }

          echo $mona_logo;
          ?>
          <h1 class="hide-sitename"></h1>
        </a>
        <div class="header-group-right"><a class="btn btn-sec" href="<?php echo esc_url($mona_contact_url); ?>">
            <span><?php echo (function_exists('pll__') ? pll__('Liên hệ ngay') : 'Liên hệ ngay'); ?></span></a></div>
      </div>
    </div>
    <div class="overlay"></div>
  </header>
  <main class="main">