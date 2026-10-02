<?php
defined('ABSPATH') || exit;

if (!isset($args['object_id']))
    return;

$data = get_field('contact', $args['object_id']);
if (empty($data))
    return;
?>

<section class="is-full contact-form" data-anchor="contact"
    <?php echo ! empty($args['form_id']) ? 'id="' . esc_attr($args['form_id']) . '"' : '' ?>>
    <div class="container">
        <div class="ct-decor" data-aos="fade-left" data-aos-delay="100"><img
                src="/template/assets/images/recruit/img-decor.jpg" alt="" title="" loading="lazy">
        </div>
        <div class="ct-form-inner">
            <div class="ct-form-box">
                <div class="cfb-top">
                    <?php if (! empty($data['title'])) : ?>
                        <h2 class="main-tt" data-aos="fade-up" data-aos-delay="200"><?php echo esc_html($data['title']); ?></h2>
                    <?php endif; ?>
                    <?php if (! empty($data['description'])) : ?>
                        <div class="heading-main_desc mona-content">
                            <p class="text-14" data-aos="fade-up" data-aos-delay="400"><?php echo wp_kses($data['description'], [
                                                                                            'br' => [],
                                                                                        ]); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (! empty($data['accordion'])): ?>
                    <ul class="faq-drop">
                        <?php foreach ($data['accordion'] as $index => $item): ?>
                            <li class="faq-item" data-aos="fade-left" data-aos-delay="<?php echo 200 + ($index * 200); ?>">
                                <div class="fi-inner">
                                    <div class="faq-ic">
                                        <?php if (! empty($item['icon'])): ?>
                                            <?php echo wp_get_attachment_image($item['icon'], 'full', false, ['loading' => 'lazy']); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="faq-ct">
                                        <?php if (! empty($item['title'])): ?>
                                            <div class="text-18"><?php echo esc_html($item['title']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="faq-btn"> <img src="/template/assets/images/home/arr-faq.svg" alt="" title="" loading="lazy">
                                    </div>
                                </div>
                                <?php if (! empty($item['description'])): ?>
                                    <div class="fi-content">
                                        <p class="text-14"><?php echo wp_kses($item['description'], [
                                                                'br' => [],
                                                            ]); ?></p>
                                    </div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <!-- <ul class="faq-drop">
                    <li class="faq-item" data-aos="fade-left" data-aos-delay="200">
                        <div class="fi-inner">
                            <div class="faq-ic"> <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/home/ic-1.svg" alt=""
                                    title="" loading="lazy">
                            </div>
                            <div class="faq-ct">
                                <div class="text-18">Giải pháp theo nhu cầu doanh nghiệp</div>
                            </div>
                            <div class="faq-btn"> <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/home/arr-faq.svg" alt=""
                                    title="" loading="lazy">
                            </div>
                        </div>
                        <div class="fi-content">
                            <p class="text-14">KN Vientiane Group đồng hành cùng doanh nghiệp trong việc phân tích nhu cầu, định hình
                                mục tiêu và đề xuất giải pháp phù hợp — từ trải nghiệm dịch vụ đến các định hướng hợp tác dài hạn.</p>
                        </div>
                    </li>
                    <li class="faq-item" data-aos="fade-left" data-aos-delay="400">
                        <div class="fi-inner">
                            <div class="faq-ic"> <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/home/ic-2.svg" alt=""
                                    title="" loading="lazy">
                            </div>
                            <div class="faq-ct">
                                <div class="text-18">Hệ thống dịch vụ đa lĩnh vực</div>
                            </div>
                            <div class="faq-btn"> <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/home/arr-faq.svg" alt=""
                                    title="" loading="lazy">
                            </div>
                        </div>
                        <div class="fi-content">
                            <p class="text-14">KN Vientiane Group đồng hành cùng doanh nghiệp trong việc phân tích nhu cầu, định hình
                                mục tiêu và đề xuất giải pháp phù hợp — từ trải nghiệm dịch vụ đến các định hướng hợp tác dài hạn.</p>
                        </div>
                    </li>
                    <li class="faq-item" data-aos="fade-left" data-aos-delay="600">
                        <div class="fi-inner">
                            <div class="faq-ic"> <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/home/ic-3.svg" alt=""
                                    title="" loading="lazy">
                            </div>
                            <div class="faq-ct">
                                <div class="text-18">Đội ngũ chuyên gia đồng hành</div>
                            </div>
                            <div class="faq-btn"> <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/home/arr-faq.svg" alt=""
                                    title="" loading="lazy">
                            </div>
                        </div>
                        <div class="fi-content">
                            <p class="text-14">KN Vientiane Group đồng hành cùng doanh nghiệp trong việc phân tích nhu cầu, định hình
                                mục tiêu và đề xuất giải pháp phù hợp — từ trải nghiệm dịch vụ đến các định hướng hợp tác dài hạn.</p>
                        </div>
                    </li>
                </ul> -->
            </div>
            <div class="ct-form-box">
                <div class="ct-form-main" data-aos="fade-left" data-aos-delay="300">
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