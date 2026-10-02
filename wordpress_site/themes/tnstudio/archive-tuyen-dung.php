<?php
defined('ABSPATH') || exit;

// Dịch chuỗi qua Polylang String Translations (admin → Languages → Translations, group "KN Vientiane")
$lbl_search = function_exists('pll__') ? pll__('Tìm vị trí ứng tuyển...') : 'Tìm vị trí ứng tuyển...';
$lbl_pos    = function_exists('pll__') ? pll__('Tất cả vị trí')           : 'Tất cả vị trí';
$lbl_loc    = function_exists('pll__') ? pll__('Tất cả địa điểm')         : 'Tất cả địa điểm';
$lbl_reset  = function_exists('pll__') ? pll__('Đặt lại')                 : 'Đặt lại';
$lbl_clear  = function_exists('pll__') ? pll__('Xóa')                     : 'Xóa';

get_header();
?>

<section class="banner-main">
  <?php mona_output_breadcrumb(); ?>
  <div class="container">
    <div class="banner-info">
      <div class="logo-mark">
        <img src="/template/assets/images/news/logo-banner.png" alt="" title="" loading="lazy">
      </div>
      <h2 class="main-tt"><?php echo esc_html(function_exists('pll__') ? pll__('Tuyển dụng') : 'Tuyển dụng'); ?></h2>
    </div>

    <div class="banner-filter banner-filter-luxury">
      <form action="<?php echo esc_url(get_post_type_archive_link('tuyen-dung')); ?>" method="get" class="filter-luxury-form">
        <input type="hidden" name="post_type" value="tuyen-dung" />

        <!-- 1. Search Box -->
        <div class="filter-luxury-item filter-luxury-search">
          <div class="filter-icon-left">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#b38c00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </div>
          <input type="text" name="s_job" class="filter-luxury-input js-search-input" 
                 placeholder="<?php echo esc_attr($lbl_search); ?>"
                 value="<?php echo isset($_GET['s_job']) ? esc_attr($_GET['s_job']) : ''; ?>"
                 autocomplete="off">
          <button type="button" class="btn-clear-search js-clear-search" style="<?php echo empty($_GET['s_job']) ? 'display: none;' : ''; ?>" title="<?php echo esc_attr($lbl_clear); ?>">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#b38c00" stroke-width="2.5" stroke-linecap="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>

        <!-- 2. Position Filter -->
        <div class="filter-luxury-item filter-luxury-select">
          <div class="filter-icon-left">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#b38c00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
              <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
            </svg>
          </div>
          <select id="vitri" name="vitri" class="re-select-main filter-luxury-select-control">
            <option value=""><?php echo esc_html($lbl_pos); ?></option>
            <?php
            $job_categories = get_terms(array(
              'taxonomy'   => 'vi-tri-tuyen-dung',
              'hide_empty' => false,
            ));
            $current_vitri = isset($_GET['vitri']) ? $_GET['vitri'] : '';

            if (! empty($job_categories) && ! is_wp_error($job_categories)) {
              foreach ($job_categories as $cat) {
                $selected = selected($current_vitri, $cat->slug, false);
                echo '<option value="' . esc_attr($cat->slug) . '" ' . $selected . '>' . esc_html($cat->name) . '</option>';
              }
            }
            ?>
          </select>
        </div>

        <!-- 3. Location Filter -->
        <div class="filter-luxury-item filter-luxury-select">
          <div class="filter-icon-left">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#b38c00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
              <circle cx="12" cy="10" r="3"></circle>
            </svg>
          </div>
          <select id="diadiem" name="diadiem" class="re-select-main filter-luxury-select-control">
            <option value=""><?php echo esc_html($lbl_loc); ?></option>
            <?php
            $job_locations = get_terms(array(
              'taxonomy'   => 'dia-diem-tuyen-dung',
              'hide_empty' => false,
            ));
            $current_diadiem = isset($_GET['diadiem']) ? $_GET['diadiem'] : '';

            if (! empty($job_locations) && ! is_wp_error($job_locations)) {
              foreach ($job_locations as $loc) {
                $selected = selected($current_diadiem, $loc->slug, false);
                echo '<option value="' . esc_attr($loc->slug) . '" ' . $selected . '>' . esc_html($loc->name) . '</option>';
              }
            }
            ?>
          </select>
        </div>

        <!-- 4. Reset Action Button -->
        <?php 
        $has_active_filter = !empty($_GET['s_job']) || !empty($_GET['vitri']) || !empty($_GET['diadiem']);
        ?>
        <div class="filter-luxury-action">
          <button type="button" class="btn-filter-reset js-clear-filter <?php echo !$has_active_filter ? 'is-disabled' : ''; ?>" title="<?php echo esc_attr($lbl_reset); ?>">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="1 4 1 10 7 10"></polyline>
              <polyline points="23 20 23 14 17 14"></polyline>
              <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
            </svg>
            <span><?php echo esc_html($lbl_reset); ?></span>
          </button>
        </div>
      </form>
    </div>
  </div>
</section>

<style>
/* ==========================================================================
   Banner & Recruitment Title Centering
   ========================================================================== */
.banner-main {
  min-height: 48rem !important;
  display: flex !important;
  flex-direction: column !important;
  justify-content: space-between !important;
  padding: 9.5rem 0 3.5rem 0 !important;
}

.banner-main > .container {
  display: flex !important;
  flex-direction: column !important;
  justify-content: flex-end !important;
  align-items: center !important;
  flex: 1 !important;
  height: auto !important;
  position: relative !important;
}

.banner-main .banner-info {
  display: flex !important;
  flex-direction: column !important;
  align-items: center !important;
  justify-content: center !important;
  text-align: center !important;
  position: absolute !important;
  top: 50% !important;
  left: 50% !important;
  transform: translate(-50%, -50%) !important;
  width: 100% !important;
}

.banner-main .banner-info .main-tt {
  text-align: center !important;
  margin: 0 auto !important;
  color: #b38c00 !important;
  display: block !important;
}

.banner-main .banner-info .logo-mark {
  position: absolute !important;
  top: 50% !important;
  left: 50% !important;
  transform: translate(-50%, -50%) !important;
  max-width: 24rem !important;
  opacity: 0.08 !important;
  pointer-events: none !important;
  z-index: 0 !important;
}

/* ==========================================================================
   Luxury Recruitment Filter Bar Redesign
   ========================================================================== */
.banner-filter.banner-filter-luxury {
  max-width: 110rem !important;
  margin: 0 auto !important;
  padding: 0 1.5rem !important;
  width: 100% !important;
}

.filter-luxury-form {
  display: grid !important;
  grid-template-columns: 1.4fr 1.15fr 1.15fr auto !important;
  gap: 1.6rem !important;
  align-items: center !important;
  width: 100% !important;
}

.filter-luxury-item {
  position: relative !important;
  display: flex !important;
  align-items: center !important;
  height: 4.8rem !important;
  background: rgba(255, 255, 255, 0.75) !important;
  backdrop-filter: blur(10px) !important;
  -webkit-backdrop-filter: blur(10px) !important;
  border: 0.15rem solid #b38c00 !important;
  border-radius: 4rem !important;
  padding: 0 !important;
  transition: all 0.3s ease !important;
  box-shadow: 0 0.4rem 1.6rem rgba(179, 140, 0, 0.06) !important;
  box-sizing: border-box !important;
  width: 100% !important;
}

.filter-luxury-item:hover,
.filter-luxury-item:focus-within {
  background: #ffffff !important;
  border-color: #8c6d00 !important;
  box-shadow: 0 0.6rem 2rem rgba(179, 140, 0, 0.16) !important;
}

.filter-icon-left {
  position: absolute !important;
  left: 1.8rem !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  flex-shrink: 0 !important;
  color: #b38c00 !important;
  z-index: 2 !important;
  pointer-events: none !important;
}

.filter-icon-left svg {
  display: block !important;
}

.filter-luxury-input {
  width: 100% !important;
  height: 100% !important;
  border: none !important;
  background: transparent !important;
  font-size: 1.45rem !important;
  color: #241c00 !important;
  padding: 0 1.6rem 0 4.2rem !important;
  border-radius: 4rem !important;
  outline: none !important;
  font-family: inherit !important;
  line-height: normal !important;
}

.filter-luxury-input::placeholder {
  color: #a48206 !important;
  font-style: italic !important;
  opacity: 0.85 !important;
}

.btn-clear-search {
  background: none !important;
  border: none !important;
  cursor: pointer !important;
  padding: 0.4rem !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  color: #b38c00 !important;
  opacity: 0.6 !important;
  transition: opacity 0.2s, transform 0.2s !important;
  flex-shrink: 0 !important;
  margin-left: 0.6rem !important;
}

.btn-clear-search:hover {
  opacity: 1 !important;
  transform: scale(1.15) !important;
}

/* Select2 overrides inside luxury filter */
.filter-luxury-item .select2-container {
  width: 100% !important;
  flex: 1 !important;
}

.filter-luxury-item .select2-container--default .select2-selection--single {
  border: none !important;
  background: transparent !important;
  height: 4.5rem !important;
  display: flex !important;
  align-items: center !important;
  padding: 0 !important;
  margin: 0 !important;
  border-radius: 4rem !important;
}

.filter-luxury-item .select2-container--default .select2-selection--single .select2-selection__rendered {
  padding: 0 3.2rem 0 4.2rem !important;
  color: #241c00 !important;
  font-size: 1.45rem !important;
  font-style: normal !important;
  border: none !important;
  background: transparent !important;
  line-height: normal !important;
  white-space: nowrap !important;
  overflow: hidden !important;
  text-overflow: ellipsis !important;
  width: 100% !important;
}

.filter-luxury-item .select2-container--default .select2-selection--single .select2-selection__placeholder {
  color: #a48206 !important;
  font-style: italic !important;
  opacity: 0.85 !important;
}

.filter-luxury-item .select2-container--default .select2-selection--single .select2-selection__arrow {
  height: 100% !important;
  right: 1.6rem !important;
  top: 0 !important;
  transform: none !important;
  width: 1.8rem !important;
  display: flex !important;
  align-items: center !important;
  pointer-events: none !important;
}

.filter-luxury-item .select2-container--default .select2-selection--single .select2-selection__arrow::before {
  width: 1.4rem !important;
  height: 1.4rem !important;
}

/* Reset / Action button */
.filter-luxury-action {
  display: flex !important;
  align-items: center !important;
  flex-shrink: 0 !important;
}

.btn-filter-reset {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  gap: 0.8rem !important;
  height: 4.8rem !important;
  padding: 0 2.4rem !important;
  border-radius: 4rem !important;
  font-size: 1.45rem !important;
  font-weight: 700 !important;
  cursor: pointer !important;
  transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1) !important;
  white-space: nowrap !important;
  text-decoration: none !important;
  outline: none !important;
}

/* Active Highlighted State (When any filter is active) - Radiant Luxury Gold */
.btn-filter-reset:not(.is-disabled) {
  background: linear-gradient(135deg, #c59b00 0%, #9e7a00 100%) !important;
  border: 0.15rem solid #c59b00 !important;
  color: #ffffff !important;
  opacity: 1 !important;
  cursor: pointer !important;
  pointer-events: all !important;
  box-shadow: 0 0.4rem 1.6rem rgba(179, 140, 0, 0.4) !important;
  transform: translateY(0) !important;
}

.btn-filter-reset:not(.is-disabled) svg {
  stroke: #ffffff !important;
  transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.btn-filter-reset:not(.is-disabled):hover {
  background: linear-gradient(135deg, #d8ac0a 0%, #b38c00 100%) !important;
  border-color: #d8ac0a !important;
  color: #ffffff !important;
  box-shadow: 0 0.8rem 2.4rem rgba(179, 140, 0, 0.55) !important;
  transform: translateY(-0.2rem) !important;
}

.btn-filter-reset:not(.is-disabled):hover svg {
  transform: rotate(-180deg) scale(1.1) !important;
}

/* Dimmed / Inactive State (Default when no filter is active) */
.btn-filter-reset.is-disabled {
  background: rgba(179, 140, 0, 0.04) !important;
  border: 0.15rem solid rgba(179, 140, 0, 0.25) !important;
  color: rgba(164, 130, 6, 0.6) !important;
  opacity: 0.45 !important;
  cursor: default !important;
  pointer-events: none !important;
  box-shadow: none !important;
  transform: none !important;
}

.btn-filter-reset.is-disabled svg {
  stroke: rgba(164, 130, 6, 0.6) !important;
}

/* Luxury Dropdown Menu Popup - Seamless Single Border */
.select2-dropdown.custom-select2 {
    background: #ffffff !important;
    border: 0.15rem solid #b38c00 !important;
    border-radius: 1.6rem !important;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1) !important;
    overflow: hidden !important;
    padding: 0 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    animation: none !important;
    transform: none !important;
    opacity: 1 !important;
  }

.select2-dropdown.custom-select2 .select2-results {
  background: transparent !important;
  border: none !important;
  box-shadow: none !important;
  border-radius: 0 !important;
  padding: 0 !important;
  margin: 0 !important;
}

.select2-dropdown.custom-select2 .select2-results__options {
  padding: 0 !important;
  margin: 0 !important;
  list-style: none !important;
}

.select2-dropdown.custom-select2 .select2-results__option {
  padding: 1.2rem 2rem !important;
  font-size: 1.45rem !important;
  color: #333333 !important;
  transition: all 0.2s ease !important;
  border-bottom: 0.1rem solid rgba(179, 140, 0, 0.08) !important;
  margin: 0 !important;
}

.select2-dropdown.custom-select2 .select2-results__option:last-child {
  border-bottom: none !important;
}

.select2-dropdown.custom-select2 .select2-results__option--highlighted[aria-selected] {
  background: rgba(179, 140, 0, 0.1) !important;
  color: #b38c00 !important;
  font-weight: 600 !important;
}

.select2-dropdown.custom-select2 .select2-results__option[aria-selected="true"] {
  background: #b38c00 !important;
  color: #ffffff !important;
  font-weight: 600 !important;
}

/* Responsive Breakpoints */
@media screen and (max-width: 1024px) {
  .filter-luxury-form {
    grid-template-columns: 1fr 1fr !important;
    gap: 1.2rem !important;
  }
  .filter-luxury-search {
    grid-column: span 2 !important;
  }
  .filter-luxury-action {
    grid-column: span 2 !important;
    justify-content: center !important;
  }
  .btn-filter-reset {
    width: 100% !important;
    justify-content: center !important;
  }
}

@media screen and (max-width: 600px) {
  .filter-luxury-form {
    grid-template-columns: 1fr !important;
    gap: 1rem !important;
  }
  .filter-luxury-search,
  .filter-luxury-action {
    grid-column: span 1 !important;
  }
}
</style>

<script>
  jQuery(document).ready(function($) {
    var form = $('.banner-filter form');
    var listContainer = $('.recruit-box');
    var searchInput = form.find('.js-search-input');
    var clearSearchBtn = form.find('.js-clear-search');
    var resetBtn = form.find('.js-clear-filter');
    var isFetching = false;
    var searchTimer = null;

    function updateResetBtnState() {
      var hasKeyword = searchInput.val().trim().length > 0;
      var vitriVal = form.find('select[name="vitri"]').val();
      var diadiemVal = form.find('select[name="diadiem"]').val();
      
      var hasVitri = vitriVal && vitriVal !== '';
      var hasDiadiem = diadiemVal && diadiemVal !== '';

      if (hasKeyword || hasVitri || hasDiadiem) {
        resetBtn.removeClass('is-disabled');
      } else {
        resetBtn.addClass('is-disabled');
      }
    }

    // Toggle search clear button on input
    searchInput.on('input', function() {
      if ($(this).val().trim().length > 0) {
        clearSearchBtn.show();
      } else {
        clearSearchBtn.hide();
      }
      
      updateResetBtnState();

      // Debounce auto-search on typing
      clearTimeout(searchTimer);
      searchTimer = setTimeout(function() {
        fetchRecruitments();
      }, 400);
    });

    // Clear search text specifically
    clearSearchBtn.on('click', function() {
      searchInput.val('');
      clearSearchBtn.hide();
      updateResetBtnState();
      fetchRecruitments();
    });

    function fetchRecruitments(url) {
      if (isFetching) return;
      isFetching = true;
      
      var fetchUrl = url || (window.location.href.split('?')[0] + '?' + form.serialize());
      
      listContainer.css({ 'opacity': '0.45', 'pointer-events': 'none', 'transition': 'opacity 0.25s ease' });
      
      $.ajax({
        url: fetchUrl,
        type: 'GET',
        success: function(res) {
          var newContent = $(res).find('.recruit-box').html();
          if (newContent) {
            listContainer.html(newContent);
          }
          listContainer.css({ 'opacity': '1', 'pointer-events': 'all' });
          window.history.pushState({}, '', fetchUrl);
          updateResetBtnState();
          isFetching = false;
        },
        error: function() {
          listContainer.css({ 'opacity': '1', 'pointer-events': 'all' });
          isFetching = false;
        }
      });
    }

    form.on('submit', function(e) {
      e.preventDefault();
      fetchRecruitments();
    });

    $('.banner-filter select').on('change select2:select', function(e) {
      updateResetBtnState();
      fetchRecruitments();
    });

    // Reset all filters button
    resetBtn.on('click', function() {
      if ($(this).hasClass('is-disabled')) return;
      
      searchInput.val('');
      clearSearchBtn.hide();
      
      var selects = form.find('select');
      selects.val('');
      if ($.fn.select2) {
        selects.trigger('change');
      }
      
      updateResetBtnState();
      fetchRecruitments(window.location.href.split('?')[0]);
    });

    // Initial state check
    updateResetBtnState();

    // Handle pagination clicks dynamically
    $(document).on('click', '.recruit-box .pagination a', function(e) {
      e.preventDefault();
      var pageUrl = $(this).attr('href');
      if (pageUrl) {
        fetchRecruitments(pageUrl);
        $('html, body').animate({
          scrollTop: $('.recruit-main').offset().top - 100
        }, 300);
      }
    });
  });
</script>

<section class="recruit-main">
  <div class="container" data-aos="fade-up">
    <div class="recruit-inner">
      <div class="recruit-box">
        <h2 class="title-36"><?php echo esc_html(function_exists('pll__') ? pll__('Tuyển dụng') : 'Tuyển dụng'); ?></h2>
        <?php
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;

        // 1. Khởi tạo mảng Args 
        $args = [
          'post_type'      => 'tuyen-dung',
          'post_status'    => 'publish',
          'posts_per_page' => get_option('posts_per_page'),
          'paged'          => $paged,
          'orderby'        => 'date',
          'order'          => 'DESC',
        ];

        // 2. Tiếp nhận tham số từ Ô tìm kiếm chữ
        if (! empty($_GET['s_job'])) {
          $args['s'] = sanitize_text_field($_GET['s_job']);
        }

        // 3. Tiếp nhận tham số Tax Query từ Form Filter
        $tax_query = array('relation' => 'AND');

        if (! empty($_GET['vitri'])) {
          $tax_query[] = array(
            'taxonomy' => 'vi-tri-tuyen-dung',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_GET['vitri']),
          );
        }

        if (! empty($_GET['diadiem'])) {
          $tax_query[] = array(
            'taxonomy' => 'dia-diem-tuyen-dung',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_GET['diadiem']),
          );
        }

        if (count($tax_query) > 1) {
          $args['tax_query'] = $tax_query;
        }

        // Thực thi Query sau khi đã được map tham số filter
        $recruit_query = new WP_Query($args);
        $posts_list = $recruit_query->posts;

        // Giữ nguyên logic tách nhóm tin Còn hạn / Hết hạn 
        $active_posts = [];
        $expired_posts = [];

        foreach ($posts_list as $post) {
          $expire = get_field('expire', $post->ID);
          $is_expire = ! empty($expire) && strtotime($expire) < time() ? true : false;

          if ($is_expire) {
            $expired_posts[] = $post;
          } else {
            $active_posts[] = $post;
          }
        }

        $sorted_posts = array_merge($active_posts, $expired_posts);
        ?>

        <?php if (!empty($sorted_posts)) : ?>
          <ul class="recruit-list">
            <?php foreach ($sorted_posts as $post) : ?>
              <?php setup_postdata($post); ?>
              <?php get_template_part('partials/components/loops/item', 'recruit'); ?>
            <?php endforeach;
            wp_reset_postdata(); ?>
          </ul>

          <div class="pagination" data-aos="fade-up">
            <?php
            echo paginate_links([
              'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
              'current'   => max(1, $paged),
              'total'     => $recruit_query->max_num_pages,
              'prev_text' => (function_exists('pll__') ? pll__('« Trước') : '« Trước'),
              'next_text' => (function_exists('pll__') ? pll__('Sau »') : 'Sau »'),
              'add_args'  => array_filter([
                's_job'   => $_GET['s_job'] ?? '',
                'vitri'   => $_GET['vitri'] ?? '',
                'diadiem' => $_GET['diadiem'] ?? '',
              ]),
            ]);
            ?>
          </div>
        <?php else : ?>
          <p class="mona-empty"><?php echo esc_html(function_exists('pll__') ? pll__('Không tìm thấy dữ liệu nào') : 'Không tìm thấy dữ liệu nào'); ?></p>
        <?php endif; ?>
      </div>

      <?php if (is_active_sidebar('sidebar-benefit-job')) : ?>
        <?php dynamic_sidebar('sidebar-benefit-job'); ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php
get_footer();
