<?php

/**
 * Template Name: Tuyển dụng
 *
 * @author MONA.Media
 */

defined('ABSPATH') || exit;

get_header();

// the_title('<h1 class="hide-sitename">', '</h1>');

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
  'list_recruit' => $fields['section_list_recruit'] ?? [],
  'benefit' => $fields['benefit'] ?? [],
];

?>

<?php 
/**
 * section_banner
 */
$banner = $sections['banner'];
if (!empty($banner['show'])) : ?>

<section class="banner-main"> 
    <?php mona_output_breadcrumb(); ?>
    <div class="container"> 
        <div class="banner-info">
        <div class="logo-mark"> <img src="/template/assets/images/news/logo-banner.png" alt="" title="" loading="lazy">
        </div>
        <?php if (!empty($banner['title'])) : ?>
        <h2 class="main-tt"><?php echo esc_html($banner['title']); ?></h2>
        <?php endif; ?>
        </div>
        <div class="banner-filter">
        <form action="">
            <div class="form-item">
            <input type="text" placeholder="<?php esc_attr_e('Tìm vị trí ứng tuyển', 'monamedia'); ?>">
            <div class="ic-search"> <img src="/template/assets/images/icons/search-ic.svg" alt="" title="" loading="lazy">
            </div>
            </div>
            <div class="form-item">
            <label><?php echo esc_html(function_exists('pll__') ? pll__('Vị trí:') : 'Vị trí:'); ?></label>
            <select id="cars" name="vitri">
                <option value="1">Vị trí 1</option>
                <option value="2">Vị trí 2</option>
                <option value="3">Vị trí 3</option>
                <option value="4">Vị trí 4</option>
            </select>
            </div>
            <div class="form-item">
            <label><?php echo esc_html(function_exists('pll__') ? pll__('Địa điểm:') : 'Địa điểm:'); ?></label>
            <select id="cars" name="diadiem">
                <option value="1">Địa điểm 1</option>
                <option value="2">Địa điểm 2</option>
                <option value="3">Địa điểm 3</option>
                <option value="4">Địa điểm 4</option>
            </select>
            </div>
        </form>
        </div>
    </div>
</section>

<?php endif; ?>


<?php
/**
 * section_list_recruit
 */
$list_recruit = $sections['list_recruit'];
?>
  <section class="recruit-main"> 
    <div class="container">
        <div class="recruit-inner">
        <div class="recruit-box">
            <?php if (!empty($list_recruit['title'])) : ?>
            <h2 class="title-36"><?php echo esc_html($list_recruit['title']); ?></h2>
            <?php endif; ?>

            <?php if (!empty($list_recruit['list'])) : ?>
                <div class="recruit-list">
                    <?php foreach ($list_recruit['list'] as $item) : ?>
                        <div class="recruit-item">
                            <?php if (!empty($item['title'])) : ?>
                            <a href="#" class="text-20 fw-sb">
                                <?php echo esc_html($item['title']); ?>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($item['description'])) : ?>
                                <p class="text-14"><?php echo esc_html($item['description']); ?></p>
                            <?php endif; ?>

                            <div class="recruit-action">
                                <ul class="rec-info">
                                    <?php if (!empty($item['job_type'])) : ?>
                                        <li class="rec-i-item"><?php echo esc_html($item['job_type']); ?></li>
                                    <?php endif; ?>
                                    <?php if (!empty($item['experience'])) : ?>
                                        <li class="rec-i-item"><?php echo esc_html(function_exists('pll__') ? pll__('Kinh nghiệm:') : 'Kinh nghiệm:'); ?> <?php echo esc_html($item['experience']); ?></li>
                                    <?php endif; ?>
                                    <?php if (!empty($item['work_location'])) : ?>
                                        <li class="rec-i-item"><?php echo esc_html($item['work_location']); ?></li>
                                    <?php endif; ?>
                                    <?php if (!empty($item['job_status'])) : 
    // Tự động dịch giá trị dựa theo key hệ thống
    if ($item['job_status'] == 'active') {
        $status_label = __('Đang tuyển', 'monamedia');
        $status_class = 't-green'; // Màu xanh
    } elseif ($item['job_status'] == 'expired') {
        $status_label = __('Hết hạn', 'monamedia');
        $status_class = 't-red'; // Bạn có thể đổi thành class màu đỏ của bạn
    } else {
        $status_label = $item['job_status'];
        $status_class = 't-green';
    }
?>
    <li class="rec-i-item <?php echo esc_attr($status_class); ?>"><?php echo esc_html($status_label); ?></li>
<?php endif; ?>

                                    <!-- <?php if (!empty($item['job_status'])) : ?>
                                        <li class="rec-i-item t-green"><?php echo esc_html($item['job_status']); ?></li>
                                    <?php endif; ?> -->
                                </ul>
                                <div class="rec-btn"> 
                                    <a class="btn" href="#"><span><?php echo esc_html(function_exists('pll__') ? pll__('Ứng tuyển ngay') : 'Ứng tuyển ngay'); ?> </span></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <!-- <div class="recruit-item">
                        <p class="text-20 fw-sb">Chuyên viên phát triển dự án</p>
                        <p class="text-14">Tham gia nghiên cứu, phân tích và triển khai các dự án bất động sản của tập đoàn. Phối hợp với các phòng ban để đảm bảo tiến độ và chất lượng phát triển dự án theo định hướng chiến lược.</p>
                        <div class="recruit-action"> 
                        <ul class="rec-info">
                            <li class="rec-i-item">Full-time </li>
                            <li class="rec-i-item">Kinh nghiệm: 2 - 4 năm </li>
                            <li class="rec-i-item">Viêng Chăn</li>
                            <li class="rec-i-item t-green">Đang tuyển</li>
                        </ul>
                        <div class="rec-btn"> <a class="btn" href="#"><span>Ứng tuyển ngay </span></a></div>
                        </div>
                    </div>
                    <div class="recruit-item">
                        <p class="text-20 fw-sb">Chuyên viên phát triển dự án</p>
                        <p class="text-14">Tham gia nghiên cứu, phân tích và triển khai các dự án bất động sản của tập đoàn. Phối hợp với các phòng ban để đảm bảo tiến độ và chất lượng phát triển dự án theo định hướng chiến lược.</p>
                        <div class="recruit-action"> 
                        <ul class="rec-info">
                            <li class="rec-i-item">Full-time </li>
                            <li class="rec-i-item">Kinh nghiệm: 2 - 4 năm </li>
                            <li class="rec-i-item">Viêng Chăn</li>
                            <li class="rec-i-item t-green">Đang tuyển</li>
                        </ul>
                        <div class="rec-btn"> <a class="btn" href="#"><span>Ứng tuyển ngay </span></a></div>
                        </div>
                    </div>
                    <div class="recruit-item">
                        <p class="text-20 fw-sb">Chuyên viên phát triển dự án</p>
                        <p class="text-14">Tham gia nghiên cứu, phân tích và triển khai các dự án bất động sản của tập đoàn. Phối hợp với các phòng ban để đảm bảo tiến độ và chất lượng phát triển dự án theo định hướng chiến lược.</p>
                        <div class="recruit-action"> 
                        <ul class="rec-info">
                            <li class="rec-i-item">Full-time </li>
                            <li class="rec-i-item">Kinh nghiệm: 2 - 4 năm </li>
                            <li class="rec-i-item">Viêng Chăn</li>
                            <li class="rec-i-item t-green">Đang tuyển</li>
                        </ul>
                        <div class="rec-btn"> <a class="btn" href="#"><span>Ứng tuyển ngay </span></a></div>
                        </div>
                    </div>
                    <div class="recruit-item">
                        <p class="text-20 fw-sb">Chuyên viên phát triển dự án</p>
                        <p class="text-14">Tham gia nghiên cứu, phân tích và triển khai các dự án bất động sản của tập đoàn. Phối hợp với các phòng ban để đảm bảo tiến độ và chất lượng phát triển dự án theo định hướng chiến lược.</p>
                        <div class="recruit-action"> 
                        <ul class="rec-info">
                            <li class="rec-i-item">Full-time </li>
                            <li class="rec-i-item">Kinh nghiệm: 2 - 4 năm </li>
                            <li class="rec-i-item">Viêng Chăn</li>
                            <li class="rec-i-item t-green">Đang tuyển</li>
                        </ul>
                        <div class="rec-btn"> <a class="btn" href="#"><span>Ứng tuyển ngay </span></a></div>
                        </div>
                    </div> -->
                </div>
            <?php endif; ?>

            <div class="pagination">
            <ul class="page-numbers">
                <li><a class="prev page-numbers" href="#!">
                    <div class="page-number"><img src="./assets/images/icons/icon-arrow-prv.svg" alt="" title="" loading="lazy">
                    </div></a></li>
                <li><span class="page-numbers current" aria-current="page">1</span></li>
                <li><a class="page-numbers" href="#!">2</a></li>
                <li><a class="page-numbers" href="#!">3</a></li>
                <li><a class="page-numbers disable" href="#!">...</a></li>
                <li><a class="page-numbers" href="#!">9</a></li>
                <li><a class="page-numbers" href="#!">10</a></li>
                <li><a class="next page-numbers" href="#!">
                    <div class="page-number"><img src="./assets/images/icons/icon-arrow.svg" alt="" title="" loading="lazy">
                    </div></a></li>
            </ul>
            </div>
        </div>

        <?php
        $benefit = $sections['benefit'];

            if (!empty($benefit['show'])) : ?>
            <div class="recruit-sub">
                <div class="sub-benefit"> 
                <div class="text-18 fw-b"><?php echo esc_html(function_exists('pll__') ? pll__('Phúc lợi công ty') : 'Phúc lợi công ty'); ?></div>
                <?php if (!empty($benefit['image'])) : ?>
                    <div class="ben-img"> 
                    <img src="<?php echo esc_url($benefit['image']); ?>" alt="" title="" loading="lazy">
                    </div>
                <?php endif; ?>
                <?php if (!empty($benefit['list'])) : ?>
                <ul class="ben-list"> 
                    <?php 
                    $lang = apply_filters('wpml_current_language', NULL);
                    $trans_list = [
                        'Môi trường làm việc chuyên nghiệp, ổn định' => ['zh-hant' => '专业稳定的工作环境', 'en' => 'Professional and stable working environment', 'ko' => '전문적이고 안정적인 근무 환경', 'lo' => 'ສະພາບແວດລ້ອມການເຮັດວຽກທີ່ເປັນມືອາຊີບ ແລະ ໝັ້ນຄົງ'],
                        'Tham gia các dự án quy mô lớn' => ['zh-hant' => '参与大型项目', 'en' => 'Participate in large-scale projects', 'ko' => '대규모 프로젝트 참여', 'lo' => 'ເຂົ້າຮ່ວມໂຄງການຂະໜາດໃຫຍ່'],
                        'Cơ hội phát triển và thăng tiến rõ ràng' => ['zh-hant' => '清晰的发展和晋升机会', 'en' => 'Clear opportunities for development and promotion', 'ko' => '명확한 발전 및 승진 기회', 'lo' => 'ໂອກາດການພັດທະນາ ແລະ ເລື່ອນຕຳແໜ່ງທີ່ຊັດເຈນ'],
                        'Chính sách đãi ngộ cạnh tranh' => ['zh-hant' => '具有竞争力的薪酬政策', 'en' => 'Competitive compensation policy', 'ko' => '경쟁력 있는 보상 정책', 'lo' => 'ນະໂຍບາຍຄ່າຕອບແທນທີ່ແຂ່ງຂັນ'],
                        'Đào tạo và nâng cao chuyên môn' => ['zh-hant' => '培训与专业提升', 'en' => 'Training and professional improvement', 'ko' => '교육 및 전문성 향상', 'lo' => 'ການຝຶກອົບຮົມ ແລະ ຍົກລະດັບວິຊາສະເພາະ']
                    ];
                    foreach ($benefit['list'] as $item) : 
                        $text = $item['title'];
                        foreach ($trans_list as $vi_text => $langs) {
                            if (strpos($text, 'Môi trường') !== false && strpos($vi_text, 'Môi trường') !== false) $text = $langs[$lang] ?? $text;
                            elseif (strpos($text, 'quy mô lớn') !== false && strpos($vi_text, 'quy mô lớn') !== false) $text = $langs[$lang] ?? $text;
                            elseif (strpos($text, 'thăng tiến') !== false && strpos($vi_text, 'thăng tiến') !== false) $text = $langs[$lang] ?? $text;
                            elseif (strpos($text, 'đãi ngộ') !== false && strpos($vi_text, 'đãi ngộ') !== false) $text = $langs[$lang] ?? $text;
                            elseif (strpos($text, 'chuyên môn') !== false && strpos($vi_text, 'chuyên môn') !== false) $text = $langs[$lang] ?? $text;
                        }
                    ?>
                    <li class="ben-item"><?php echo esc_html($text); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            </div>
            </div>
    </div>
  </section>

<?php get_footer(); ?>