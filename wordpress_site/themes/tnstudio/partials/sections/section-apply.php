<?php
defined('ABSPATH') || exit;

if (!isset($args['object_id']))
    return;

$data = get_field('section_apply', $args['object_id']);
if (empty($data))
    return;
?>

<section class="is-full contact-form" data-anchor="apply"
  <?php echo ! empty($args['form_id']) ? 'id="' . esc_attr($args['form_id']) . '"' : '' ?>>
  <div class="container">
    <div class="ct-decor"><img src="/template/assets/images/recruit/img-decor.jpg" alt="" title="" loading="lazy">
    </div>
    <div class="ct-form-inner">
      <div class="ct-form-box">
        <?php if (! empty($data['title'])) : ?>
        <h2 class="main-tt"><?php echo esc_html($data['title']); ?></h2>
        <?php endif; ?>
        <?php if (! empty($data['description'])) : ?>
        <div class="heading-main_desc mona-content">
          <p class="text-14"><?php echo wp_kses($data['description'], [
                                                'br' => [],
                                            ]); ?></p>
        </div>
        <?php endif; ?>

        <?php
                $web_builder_id = $data['contact_info'] ?? null;
                if ($web_builder_id) :
                    $contactInfo = get_field('contact_info', $web_builder_id);

                    if ($contactInfo) :
                        get_template_part('partials/components/contact-info', null, [
                            'contact-info' => $contactInfo
                        ]);
                    endif;
                endif;
                ?>

        <?php
                $socials_web_builder_id = $data['contact_socials'] ?? null;
                if ($socials_web_builder_id) :
                    $socialsInfo = get_field('socials', $socials_web_builder_id);
                    if ($socialsInfo) :
                        get_template_part('partials/components/contact-socials', null, [
                            'socials' => $socialsInfo
                        ]);
                    endif;
                endif;
                ?>
      </div>
      <div class="ct-form-box">
        <div class="ct-form-main">
          <div class="text-28"><?php echo esc_html(function_exists('pll__') ? pll__('Thông tin') : 'Thông tin'); ?> <span><?php echo esc_html(function_exists('pll__') ? pll__('ỨNG TUYỂN') : 'ỨNG TUYỂN'); ?></span></div>
          <?php if (! empty($data['form_shortcode'])) : ?>
          <div class="m-contact_form">
            <?php echo do_shortcode($data['form_shortcode']); ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>