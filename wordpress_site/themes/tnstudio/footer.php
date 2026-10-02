<?php

/**
 * The template for displaying footer.
 *
 * @package MONA.Media / Website
 */

if (!defined('ABSPATH')) {
  die();
}

$footer_top = get_field('footer_top', 'option');
$footer_mid = get_field('footer_mid', 'option');
$footer_bottom = get_field('footer_bottom', 'option');
$footer_button_stickies = get_field('footer_button_stickies', 'option');

?>
</main>

<footer class="footer js-footer" data-color="13, 20, 50">
  <div class="container">
    <div class="footer-wrap flex">
      <?php if (!empty($footer_top['show'])): ?>
        <div class="footer-logo">
          <?php if (! empty($footer_top['logo'])): ?>
            <a class="custom-logo-link" href="<?php echo esc_url($footer_top['logo_url'] ?? home_url('/')); ?>">
              <?php echo wp_get_attachment_image($footer_top['logo'], 'full', false, [
                'loading' => 'lazy',
              ]); ?>
            </a>
          <?php endif; ?>
          <?php if (! empty($footer_top['description'])): ?>
            <div class="footer-short">
              <p><?php echo esc_html($footer_top['description']); ?></p>
            </div>
          <?php endif; ?>
        </div>

        <?php if (! empty($footer_top['form_shortcode'])): ?>
          <div class="footer-regist">
            <?php echo do_shortcode($footer_top['form_shortcode']); ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>

      <?php if (!empty($footer_mid['show'])): ?>
        <?php
        $web_builder_id = $footer_mid['contact_info'] ?? null;
        if ($web_builder_id) :
          $web_builder_id = mona_web_builder_id($web_builder_id);
          $contactInfo = get_field('contact_info', $web_builder_id);

          if ($contactInfo) :
            get_template_part('partials/components/contact-info', null, [
              'contact-info' => $contactInfo
            ]);
          endif;
        endif;
        ?>
      <?php endif; ?>
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
              $mona_footer_lang = mona_ui_lang();
              ?>
              <?php foreach ($footer_links as $item) : ?>
                <?php if (!empty($item['link']) && is_array($item['link'])) :
                  $mona_link_title = $item['link']['title'] ?? '';
                  $mona_link_url   = $item['link']['url'] ?? '';
                  if (function_exists('pll__') && $mona_link_title !== '') {
                    $mona_link_title = pll__($mona_link_title);
                  }
                  if (!empty($mona_link_url)) {
                    $mona_link_url = mona_localize_url($mona_link_url);
                  }
                  ?>
                  <?php if ($mona_link_title && $mona_link_url): ?>
                  <a class="fc-link" href="<?php echo esc_url($mona_link_url); ?>"
                    target="<?php echo !empty($item['link']['target']) ? esc_attr($item['link']['target']) : '_self'; ?>">
                    <?php echo esc_html($mona_link_title); ?>
                  </a>
                  <?php endif; ?>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endif; ?>

            <?php
            /**
             * Bộ chuyển ngôn ngữ ở footer.
             *
             * Markup nằm ở partials/components/lang-switcher.php vì trang chủ
             * dựng lại thanh copyright riêng trong front-page.php — để hai nơi
             * cùng gọi một file thì sửa một chỗ là ăn cả hai.
             *
             * Nhớ TẮT tùy chọn WPML -> Ngôn ngữ -> "Bộ chuyển đổi ngôn ngữ chân
             * trang" (wpml-ls-show-in-footer), nếu không sẽ hiện hai bộ cùng lúc.
             */
            get_template_part('partials/components/lang-switcher');
            ?>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</footer>

<div class="move-top backToTop">
  <!-- <div class="move-top-social"><a rel="noopener noreferrer" target="_blank" href="#"><img
        src="<?php echo MONA_SITE_TEMPLATE_URL ?>/assets/images/stable/zalo.svg" alt="" title="" loading="lazy"></a>
  </div>
  <div class="move-top-social"><a rel="noopener noreferrer" target="_blank" href="#"><img
        src="<?php echo MONA_SITE_TEMPLATE_URL ?>/assets/images/stable/messager.svg" alt="" title="" loading="lazy"></a>
  </div>
  <div class="move-top-social"><a rel="noopener noreferrer" target="_blank" href="#"><img
        src="<?php echo MONA_SITE_TEMPLATE_URL ?>/assets/images/stable/fdf.svg" alt="" title="" loading="lazy"></a>
  </div> -->
  <?php if (!empty($footer_button_stickies)): ?>
    <?php
    $footer_button_stickies_desc = array_reverse($footer_button_stickies);
    ?>

    <?php foreach ($footer_button_stickies_desc as $index => $button): ?>
      <?php if (! empty($button['image'])): ?>
        <div class="move-top-social">
          <?php if (! empty($button['url'])): ?>
            <a href="<?php echo esc_url($button['url']); ?>" target="_blank" rel="noopener nofollow">
              <?php echo wp_get_attachment_image($button['image'], 'full', false, [
                'loading' => 'lazy',
              ]); ?>
            </a>
          <?php else: ?>
            <?php echo wp_get_attachment_image($button['image'], 'full', false, [
              'loading' => 'lazy',
            ]); ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>
  <div class="move-top-back">
    <div class="progress-wrap">
      <svg class="progress-circle" fill="none" viewBox="0 0 32 32" height="100%" width="100%"
        xmlns="http://www.w3.org/2000/svg">
        <circle class="progress-path" style="stroke-dasharray: 100px; stroke-dashoffset: 100px;" fill="#B38C00" r="16"
          cy="16" cx="16"></circle>
      </svg>
      <div class="inner"><img src="<?php echo MONA_SITE_TEMPLATE_URL ?>/assets/images/stable/arrow-up.svg" alt=""
          title="" loading="lazy">
      </div>
    </div>
  </div>
</div>
<?php wp_footer(); ?>
</body>

</html>