<?php

/**
 * Template Name: Dự án
 * 
 * @author MONA.Media / Website
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
  'banner' => $fields['section_banner'] ?? [],
  'contact' => $fields['section_contact'] ?? [],
];

?>

<?php

/**
 * Banner Section
 */
$banner = $sections['banner'];

?>

<?php if (!empty($banner['show'])) : ?>

  <?php
  $featured_project = $sections['featured_project'] ?? null;
  $taxonomy = 'danh-muc-du-an';

  $featured_term_id = function_exists('pll_get_term') ? (pll_get_term(8) ?: 8) : apply_filters('wpml_object_id', 8, 'danh-muc-du-an', true);

  $project_query = new WP_Query([
    'post_type'        => 'du-an',
    'posts_per_page'   => 10,
    'suppress_filters' => false,
    'tax_query'        => [[
      'taxonomy' => $taxonomy,
      'field'    => 'term_id',
      'terms'    => $featured_term_id ?: 8,
    ]],
  ]);
  ?>

  <section class="pj-banner">
    <?php mona_output_breadcrumb(); ?>

    <div class="pj-bg">
      <div class="pj-bg-inner js-pj-background">
        <div class="swiper">
          <div class="swiper-wrapper">
            <?php while ($project_query->have_posts()) : $project_query->the_post(); ?>
              <div class="swiper-slide">
                <div class="pj-bg-img">
                  <?php echo get_the_post_thumbnail(get_the_ID(), 'full', [
                    'loading' => 'lazy',
                  ]); ?>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
      </div>
    </div>


    <div class="pj-ban-inner">
      <div class="pj-ban-info js-ban-info">
        <div class="swiper">
          <div class="swiper-wrapper">
            <?php $index = 1;
            while ($project_query->have_posts()) : $project_query->the_post(); ?>
              <div class="swiper-slide">
                <div class="pj-ban-item">
                  <p class="text-18 fs-i"><?php echo esc_html(function_exists('pll__') ? pll__('Dự án') : 'Dự án'); ?> <?php echo $index; ?></p>
                  <?php
                  $title_short = get_field('project_title_short');
                  ?>

                  <p class="main-tt t-up">
                    <?php echo esc_html($title_short ?: get_the_title()); ?>
                  </p>
                  <p class="text-14"><?php echo wp_trim_words(get_the_excerpt(), 30, '...'); ?></p>
                  <a class="n-view-more" href="<?php the_permalink(); ?>">
                    <span><?php echo esc_html(function_exists('pll__') ? pll__('Dự án chi tiết') : 'Dự án chi tiết'); ?></span>
                    <img src="<?php echo MONA_SITE_TEMPLATE_URL; ?>/assets/images/icons/arrow-right.svg" alt="" title="" loading="lazy">
                  </a>
                </div>
              </div>
            <?php $index++;
            endwhile; ?>
          </div>
        </div>
      </div>
      <div class="pj-ban-img js-ban-img">
        <div class="swiper">
          <div class="swiper-wrapper">
            <?php while ($project_query->have_posts()) : $project_query->the_post(); ?>
              <div class="swiper-slide">
                <div class="pj-img">
                  <?php echo get_the_post_thumbnail(get_the_ID(), 'full', [
                    'loading' => 'lazy',
                  ]); ?>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php
/**
 * List project
 */
$taxonomy = 'danh-muc-du-an';

$terms = get_terms([
  'taxonomy'   => $taxonomy,
  'hide_empty' => true,
  'meta_key'   => 'term_order_index',
  'orderby'    => 'meta_value_num',
  'order'      => 'ASC',
]);

?>
<section id="sec-project" class="news-group sec-project-js">
  <div class="container">
    <h2 class="main-tt"><?php echo esc_html(function_exists('pll__') ? pll__('DANH SÁCH DỰ ÁN') : 'DANH SÁCH DỰ ÁN'); ?></h2>
    <div class="news-tab">
      <div class="news-tab-inner js-tab-slide">
        <div class="swiper">
          <div id="product-categories" class="swiper-wrapper">
            <!-- <?php foreach ($terms as $key => $term) : ?>
              <div class="swiper-slide">
                <a class="tab-item <?php echo $key === 0 ? 'is-active' : ''; ?>"
                  href="#"><?php echo esc_html($term->name); ?></a>
              </div>
            <?php endforeach; ?> -->
          </div>
        </div>
      </div>
    </div>
    <div id="project-list-js" class="news-list">
      <!-- <div class="news-box">
        <div class="project-item">
          <div class="img-pj"><img src="/template/assets/images/field/pj2.jpg" alt="" title="" loading="lazy">
            <div class="pj-hub">
              <div class="pj-hub-ic"> <img src="/template/assets/images/home/icon-bot.png" alt="" title=""
                  loading="lazy">
              </div>
              <p class="text-16">Lorem ipsum dolor sit amet, consectetur minim veniamadipiscing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                ullamco laboris nisi ut aliquip ex ea commodo consequat. </p><a class="btn" href="#"><span>Dự án chi
                  tiết</span><img src="/template/assets/images/icons/arrow-right.svg" alt="" title=""
                  loading="lazy"></a>
            </div>
          </div><a class="pj-name" href="#">KN Prime Urban</a>
        </div>
      </div>
      <div class="news-box">
        <div class="project-item">
          <div class="img-pj"><img src="/template/assets/images/field/pj2.jpg" alt="" title="" loading="lazy">
            <div class="pj-hub">
              <div class="pj-hub-ic"> <img src="/template/assets/images/home/icon-bot.png" alt="" title=""
                  loading="lazy">
              </div>
              <p class="text-16">Lorem ipsum dolor sit amet, consectetur minim veniamadipiscing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                ullamco laboris nisi ut aliquip ex ea commodo consequat. </p><a class="btn" href="#"><span>Dự án chi
                  tiết</span><img src="/template/assets/images/icons/arrow-right.svg" alt="" title=""
                  loading="lazy"></a>
            </div>
          </div><a class="pj-name" href="#">KN Prime Urban</a>
        </div>
      </div>
      <div class="news-box">
        <div class="project-item">
          <div class="img-pj"><img src="/template/assets/images/field/pj2.jpg" alt="" title="" loading="lazy">
            <div class="pj-hub">
              <div class="pj-hub-ic"> <img src="/template/assets/images/home/icon-bot.png" alt="" title=""
                  loading="lazy">
              </div>
              <p class="text-16">Lorem ipsum dolor sit amet, consectetur minim veniamadipiscing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                ullamco laboris nisi ut aliquip ex ea commodo consequat. </p><a class="btn" href="#"><span>Dự án chi
                  tiết</span><img src="/template/assets/images/icons/arrow-right.svg" alt="" title=""
                  loading="lazy"></a>
            </div>
          </div><a class="pj-name" href="#">KN Prime Urban</a>
        </div>
      </div>
      <div class="news-box">
        <div class="project-item">
          <div class="img-pj"><img src="/template/assets/images/field/pj2.jpg" alt="" title="" loading="lazy">
            <div class="pj-hub">
              <div class="pj-hub-ic"> <img src="/template/assets/images/home/icon-bot.png" alt="" title=""
                  loading="lazy">
              </div>
              <p class="text-16">Lorem ipsum dolor sit amet, consectetur minim veniamadipiscing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                ullamco laboris nisi ut aliquip ex ea commodo consequat. </p><a class="btn" href="#"><span>Dự án chi
                  tiết</span><img src="/template/assets/images/icons/arrow-right.svg" alt="" title=""
                  loading="lazy"></a>
            </div>
          </div><a class="pj-name" href="#">KN Prime Urban</a>
        </div>
      </div>
      <div class="news-box">
        <div class="project-item">
          <div class="img-pj"><img src="/template/assets/images/field/pj2.jpg" alt="" title="" loading="lazy">
            <div class="pj-hub">
              <div class="pj-hub-ic"> <img src="/template/assets/images/home/icon-bot.png" alt="" title=""
                  loading="lazy">
              </div>
              <p class="text-16">Lorem ipsum dolor sit amet, consectetur minim veniamadipiscing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                ullamco laboris nisi ut aliquip ex ea commodo consequat. </p><a class="btn" href="#"><span>Dự án chi
                  tiết</span><img src="/template/assets/images/icons/arrow-right.svg" alt="" title=""
                  loading="lazy"></a>
            </div>
          </div><a class="pj-name" href="#">KN Prime Urban</a>
        </div>
      </div>
      <div class="news-box">
        <div class="project-item">
          <div class="img-pj"><img src="/template/assets/images/field/pj2.jpg" alt="" title="" loading="lazy">
            <div class="pj-hub">
              <div class="pj-hub-ic"> <img src="/template/assets/images/home/icon-bot.png" alt="" title=""
                  loading="lazy">
              </div>
              <p class="text-16">Lorem ipsum dolor sit amet, consectetur minim veniamadipiscing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                ullamco laboris nisi ut aliquip ex ea commodo consequat. </p><a class="btn" href="#"><span>Dự án chi
                  tiết</span><img src="/template/assets/images/icons/arrow-right.svg" alt="" title=""
                  loading="lazy"></a>
            </div>
          </div><a class="pj-name" href="#">KN Prime Urban</a>
        </div>
      </div>
      <div class="news-box">
        <div class="project-item">
          <div class="img-pj"><img src="/template/assets/images/field/pj2.jpg" alt="" title="" loading="lazy">
            <div class="pj-hub">
              <div class="pj-hub-ic"> <img src="/template/assets/images/home/icon-bot.png" alt="" title=""
                  loading="lazy">
              </div>
              <p class="text-16">Lorem ipsum dolor sit amet, consectetur minim veniamadipiscing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                ullamco laboris nisi ut aliquip ex ea commodo consequat. </p><a class="btn" href="#"><span>Dự án chi
                  tiết</span><img src="/template/assets/images/icons/arrow-right.svg" alt="" title=""
                  loading="lazy"></a>
            </div>
          </div><a class="pj-name" href="#">KN Prime Urban</a>
        </div>
      </div>
      <div class="news-box">
        <div class="project-item">
          <div class="img-pj"><img src="/template/assets/images/field/pj2.jpg" alt="" title="" loading="lazy">
            <div class="pj-hub">
              <div class="pj-hub-ic"> <img src="/template/assets/images/home/icon-bot.png" alt="" title=""
                  loading="lazy">
              </div>
              <p class="text-16">Lorem ipsum dolor sit amet, consectetur minim veniamadipiscing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                ullamco laboris nisi ut aliquip ex ea commodo consequat. </p><a class="btn" href="#"><span>Dự án chi
                  tiết</span><img src="/template/assets/images/icons/arrow-right.svg" alt="" title=""
                  loading="lazy"></a>
            </div>
          </div><a class="pj-name" href="#">KN Prime Urban</a>
        </div>
      </div>
      <div class="news-box">
        <div class="project-item">
          <div class="img-pj"><img src="/template/assets/images/field/pj2.jpg" alt="" title="" loading="lazy">
            <div class="pj-hub">
              <div class="pj-hub-ic"> <img src="/template/assets/images/home/icon-bot.png" alt="" title=""
                  loading="lazy">
              </div>
              <p class="text-16">Lorem ipsum dolor sit amet, consectetur minim veniamadipiscing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                ullamco laboris nisi ut aliquip ex ea commodo consequat. </p><a class="btn" href="#"><span>Dự án chi
                  tiết</span><img src="/template/assets/images/icons/arrow-right.svg" alt="" title=""
                  loading="lazy"></a>
            </div>
          </div><a class="pj-name" href="#">KN Prime Urban</a>
        </div>
      </div> -->
    </div>
    <div class="pagination">
      <!-- <ul class="page-numbers">
        <li><a class="prev page-numbers" href="#!">
            <div class="page-number"><img src="/template/assets/images/icons/icon-arrow-prv.svg" alt="" title=""
                loading="lazy">
            </div>
          </a></li>
        <li><span class="page-numbers current" aria-current="page">1</span></li>
        <li><a class="page-numbers" href="#!">2</a></li>
        <li><a class="page-numbers" href="#!">3</a></li>
        <li><a class="page-numbers disable" href="#!">...</a></li>
        <li><a class="page-numbers" href="#!">9</a></li>
        <li><a class="page-numbers" href="#!">10</a></li>
        <li><a class="next page-numbers" href="#!">
            <div class="page-number"><img src="/template/assets/images/icons/icon-arrow.svg" alt="" title=""
                loading="lazy">
            </div>
          </a></li> -->
      </ul>
    </div>
  </div>
</section>

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