<?php
defined('ABSPATH') || exit;

// 1. Kiểm tra biến $args và $data an toàn
if (empty($args['data']) || !is_array($args['data'])) {
  return;
}

$data = $args['data'];
$benefit = $data['benefit'] ?? null;

if (empty($benefit)) {
  return;
}

$img_url = $benefit['image']['url'] ?? '';
$benefit_title = $benefit['title'] ?? '';
$benefit_list = $benefit['list'] ?? [];

?>

<div class="recruit-sub">
  <div class="sub-benefit">
    <?php 
    $lang = apply_filters('wpml_current_language', NULL);
    $translated_title = $benefit_title;
    if ($lang === 'zh-hant') $translated_title = '公司福利';
    if ($lang === 'en') $translated_title = 'Company Benefits';
    if ($lang === 'ko') $translated_title = '회사 복지';
    if ($lang === 'lo') $translated_title = 'ສະຫວັດດີການຂອງບໍລິສັດ';
    
    if (!empty($benefit_title)): ?>
    <div class="text-18 fw-b"><?php echo esc_html($translated_title); ?></div>
    <?php endif; ?>

    <div class="ben-img">
      <?php if (!empty($img_url)): ?>
      <img src="<?php echo esc_url($img_url); ?>" alt="" title="" loading="lazy">
      <?php endif; ?>
    </div>

    <ul class="ben-list">
      <?php if (!empty($benefit_list) && is_array($benefit_list)): ?>
      <?php 
      $trans_list = [
          'Môi trường làm việc chuyên nghiệp, ổn định' => ['zh-hant' => '专业稳定的工作环境', 'en' => 'Professional and stable working environment', 'ko' => '전문적이고 안정적인 근무 환경', 'lo' => 'ສະພາບແວດລ້ອມການເຮັດວຽກທີ່ເປັນມືອາຊີບ ແລະ ໝັ້ນຄົງ'],
          'Tham gia các dự án quy mô lớn' => ['zh-hant' => '参与大型项目', 'en' => 'Participate in large-scale projects', 'ko' => '대규모 프로젝트 참여', 'lo' => 'ເຂົ້າຮ່ວມໂຄງການຂະໜາດໃຫຍ່'],
          'Cơ hội phát triển và thăng tiến rõ ràng' => ['zh-hant' => '清晰的发展和晋升机会', 'en' => 'Clear opportunities for development and promotion', 'ko' => '명확한 발전 및 승진 기회', 'lo' => 'ໂອກາດການພັດທະນາ ແລະ ເລື່ອນຕຳແໜ່ງທີ່ຊັດເຈນ'],
          'Chính sách đãi ngộ cạnh tranh' => ['zh-hant' => '具有竞争力的薪酬政策', 'en' => 'Competitive compensation policy', 'ko' => '경쟁력 있는 보상 정책', 'lo' => 'ນະໂຍບາຍຄ່າຕອບແທນທີ່ແຂ່ງຂັນ'],
          'Đào tạo và nâng cao chuyên môn' => ['zh-hant' => '培训与专业提升', 'en' => 'Training and professional improvement', 'ko' => '교육 및 전문성 향상', 'lo' => 'ການຝຶກອົບຮົມ ແລະ ຍົກລະດັບວິຊາສະເພາະ']
      ];
      foreach ($benefit_list as $item): 
          $text = $item["text"];
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
      <?php endif; ?>
    </ul>
  </div>
</div>