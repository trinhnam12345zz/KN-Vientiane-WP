<?php

/**
 * Template Name: Liên hệ
 */
defined('ABSPATH') || exit;

$current_page_id = get_the_ID();

$acf_fields = get_fields($current_page_id);

$section_contact = $acf_fields['section_contact'] ?? null;
$section_apply = ! empty($acf_fields['section_apply']) ? mona_web_builder_id($acf_fields['section_apply']) : mona_web_builder_id(488);
$section_contact_info = $acf_fields['section_contact_info'] ?? null;

get_header();
the_title('<h1 class="hide-sitename" style="display:none">', '</h1>');
?>

<?php mona_output_breadcrumb(); ?>

<?php
if (!empty($section_contact)) {
  mona_render_section($section_contact);
}
?>

<?php if (!empty($section_contact_info["show"])) : ?>
<section class="contact-map">
  <div class="ct-map-box">
    <?php if (!empty($section_contact_info["link_embed"])) : ?>
    <?php echo $section_contact_info["link_embed"] ?>
    <?php endif; ?>
  </div>
  <div class="ct-map-box">
    <div class="map-box-inner">
      <?php if (!empty($section_contact_info["title"])) : ?>
      <h2 class="main-tt"><?php echo esc_html($section_contact_info["title"]) ?></h2>
      <?php endif; ?>

      <?php if (!empty($section_contact_info["description"])) : ?>
      <?php echo apply_filters('the_content', $section_contact_info["description"]); ?>
      <?php endif; ?>

      <!-- <?php
              $contactInfo = get_field('contact_info', 183);
              // var_dump($contactInfo);

              if ($contactInfo) :
                get_template_part('partials/components/contact-info', null, [
                  'contact-info' => $contactInfo
                ]);
              endif;
              ?>

      <?php
      $socialsInfo = get_field('socials', 496);
      if ($socialsInfo) :
        get_template_part('partials/components/contact-socials', null, [
          'socials' => $socialsInfo
        ]);
      endif;
      ?> -->

    </div>
  </div>
</section>
<?php endif; ?>

<?php
get_footer();