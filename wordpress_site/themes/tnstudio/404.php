<?php
/**
 * The template for displaying 404 (Page Not Found).
 * Minimalist & Luxury Design for KN Vientiane Group.
 *
 * @package MONA.Media / TN Studio
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$cur_lang = function_exists('mona_ui_lang') ? mona_ui_lang() : 'vi';

$t = [
    'title'    => function_exists('pll__') ? pll__('Không tìm thấy trang') : 'Không tìm thấy trang',
    'desc'     => function_exists('pll__') ? pll__('Trang bạn đang tìm kiếm không tồn tại hoặc đã được chuyển sang địa chỉ khác.') : 'Trang bạn đang tìm kiếm không tồn tại hoặc đã được chuyển sang địa chỉ khác.',
    'btn_home' => function_exists('pll__') ? pll__('Trở về trang chủ') : 'Trở về trang chủ',
];

$home_url = function_exists('pll_home_url') ? pll_home_url($cur_lang) : home_url('/');
if (empty($home_url)) {
    $home_url = home_url('/');
}
?>

<section class="minimal-404">
  <div class="container">
    <div class="minimal-404-inner" data-aos="fade-up">
      <div class="minimal-404-code">404</div>
      <h1 class="minimal-404-title"><?php echo esc_html($t['title']); ?></h1>
      <p class="minimal-404-desc"><?php echo esc_html($t['desc']); ?></p>
      <div class="minimal-404-action">
        <a href="<?php echo esc_url($home_url); ?>" class="btn-minimal-home">
          <span><?php echo esc_html($t['btn_home']); ?></span>
          <span class="btn-arrow" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
          </span>
        </a>
      </div>
    </div>
  </div>
</section>

<style>
.minimal-404 {
  min-height: 65vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 10rem 0 12rem;
  background: #fdfbf7;
  text-align: center;
  position: relative;
}
.minimal-404-inner {
  max-width: 620px;
  margin: 0 auto;
}
.minimal-404-code {
  font-family: var(--font-pri, 'Playfair Display', serif);
  font-size: 13rem;
  font-weight: 700;
  line-height: 1;
  letter-spacing: 0.04em;
  background: linear-gradient(135deg, #dfb76c 0%, #a67c38 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 2rem;
  user-select: none;
}
.minimal-404-title {
  font-family: var(--font-pri, 'Playfair Display', serif);
  font-size: 3.2rem;
  font-weight: 700;
  color: #0d1432;
  margin-bottom: 1.6rem;
  line-height: 1.3;
}
.minimal-404-desc {
  font-size: 1.6rem;
  line-height: 1.7;
  color: #6a7082;
  margin: 0 auto 3.5rem;
  max-width: 480px;
}
.minimal-404-action {
  display: flex;
  justify-content: center;
}
.btn-minimal-home {
  display: inline-flex;
  align-items: center;
  gap: 1.2rem;
  background: #0d1432;
  color: #ffffff !important;
  font-size: 1.5rem;
  font-weight: 500;
  padding: 1.4rem 3.6rem;
  border-radius: 50px;
  text-decoration: none;
  transition: all 0.35s cubic-bezier(0.25, 1, 0.5, 1);
  box-shadow: 0 8px 24px rgba(13, 20, 50, 0.15);
}
.btn-minimal-home .btn-arrow {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.3s ease;
}
.btn-minimal-home:hover {
  background: linear-gradient(135deg, #dfb76c 0%, #a67c38 100%);
  transform: translateY(-3px);
  box-shadow: 0 12px 28px rgba(166, 124, 56, 0.35);
}
.btn-minimal-home:hover .btn-arrow {
  transform: translateX(4px);
}
@media screen and (max-width: 768px) {
  .minimal-404 {
    min-height: 55vh;
    padding: 6rem 0 8rem;
  }
  .minimal-404-code {
    font-size: 9rem;
    margin-bottom: 1.5rem;
  }
  .minimal-404-title {
    font-size: 2.4rem;
  }
  .minimal-404-desc {
    font-size: 1.45rem;
    margin-bottom: 3rem;
  }
  .btn-minimal-home {
    padding: 1.2rem 2.8rem;
    font-size: 1.4rem;
  }
}
</style>

<?php
get_footer();
