<?php

/**
 * Template Name: Lĩnh vực hoạt động
 *
 * @author MONA.Media
 */

defined('ABSPATH') || exit;

get_header();

the_title('<h1 class="hide-sitename">', '</h1>');

/**
 * Page Data
 */
$page_id = get_the_ID();
$fields  = get_fields($page_id);

/**
 * Sections
 */
$sections = [
  'field_activity' => $fields['section_field_activity'] ?? [],
  'area_activity' => $fields['section_area_activity'] ?? [],
  'creative_value' => $fields['section_creative_value'] ?? [],
  'featured_project' => $fields['section_featured_project'] ?? [],
  'contact' => $fields['section_contact'] ?? [],
];

?>

<?php mona_output_breadcrumb(); ?>

<?php
/**
 * section_field_activity
 */
$field_activity = $sections['field_activity'];

if (!empty($field_activity)) {
  mona_render_section($field_activity);
}
?>

<?php
/**
 * section_area_activity
 */
$area_activity = $sections['area_activity'];

if (!empty($area_activity)) {
  mona_render_section($area_activity);
}
?>

<?php
/**
 * section_creative_value
 */
$creative_value = $sections['creative_value'];

mona_render_creative_value_section($creative_value, (int) $page_id);

?>

<?php
/**
 * section_featured_project
 */
$featured_project = $sections['featured_project'];
if (!empty($featured_project['show'])) :
  $taxonomy = 'danh-muc-du-an';

  $project_query = new WP_Query([
    'post_type'      => 'du-an',
    'posts_per_page' => 10,
    'tax_query'      => [[
      'taxonomy' => $taxonomy,
      'field'    => 'slug',
      'terms'    => 'du-an-tieu-bieu',
    ]],
  ]);
?>

  <section class="news-req field-req">
    <div class="container">
      <div class="news-req-tt">
        <?php if (!empty($featured_project['title'])): ?>
          <h2 class="main-tt"><?php echo esc_html($featured_project['title']); ?></h2>
        <?php endif; ?>
        <?php if ($project_query->found_posts > 3) : ?>
          <div class="swiper-nav yel swiper-navigation">
            <div class="prev">
              <img src="<?php echo MONA_SITE_TEMPLATE_URL; ?>/assets/images/icons/arrow-left-yel.svg" alt="" loading="lazy">
            </div>

            <div class="next">
              <img src="<?php echo MONA_SITE_TEMPLATE_URL; ?>/assets/images/icons/arrow-left-yel.svg" alt="" loading="lazy">
            </div>
          </div>
        <?php endif; ?>
      </div>
      <div class="news-req-slide js-news-req">
        <div class="swiper">
          <div class="swiper-wrapper">
            <?php
            if ($project_query->have_posts()) :
            ?>

              <?php while ($project_query->have_posts()) : $project_query->the_post(); ?>
                <div class="swiper-slide">
                  <?php get_template_part('partials/components/loops/item', 'project'); ?>
                </div>
              <?php endwhile; ?>

            <?php
              wp_reset_postdata();
            else :
              echo '<div class="swiper-slide"><p>' . (function_exists('pll__') ? pll__('Chưa có dự án tiêu biểu nào.') : 'Chưa có dự án tiêu biểu nào.') . '</p></div>';
            endif;
            ?>
          </div>
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </div>
  </section>

<?php endif; ?>

<?php
/**
 * section_contact
 */
$contact = $sections['contact'];

if (!empty($contact)) {
  mona_render_section($contact);
}
?>

<?php get_footer(); ?>