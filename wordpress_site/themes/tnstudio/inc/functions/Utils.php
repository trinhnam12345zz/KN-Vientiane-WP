<?php

/**
 * Tự động bọc thẻ span cho các dòng văn bản từ dòng thứ 2 trở đi.
 *
 * @param string $text Chuỗi văn bản đầu vào (thường từ textarea).
 * @return string Chuỗi đã được xử lý với thẻ span.
 */
function wrap_lines_from_second_with_span($text)
{
  if (empty($text)) {
    return '';
  }

  // Tách chuỗi thành mảng, xử lý cả trường hợp xuống dòng khác nhau (\r\n hoặc \n)
  $lines = explode("\n", str_replace("\r", "", trim($text)));
  $output = '';

  foreach ($lines as $index => $line) {
    $line = trim($line); // Xóa khoảng trắng thừa từng dòng
    if (empty($line)) continue;

    if ($index === 0) {
      // Dòng đầu tiên giữ nguyên text thuần
      $output .= esc_html($line) . ' ';
    } else {
      // Các dòng tiếp theo bọc thẻ span
      $output .= '<span>' . esc_html($line) . '</span>';
    }
  }

  return $output;
}

/**
 * Tự động bọc thẻ p cho các dòng văn bản kèm theo class tùy chọn.
 *
 * @param string $text        Chuỗi văn bản đầu vào (thường từ textarea).
 * @param string $class_name  Tên class muốn thêm vào thẻ p (mặc định là rỗng).
 * @return string Chuỗi đã được xử lý với thẻ p.
 */
function wrap_lines_from_second_with_p($text, $class_name = '')
{
  if (empty($text)) {
    return '';
  }

  // Tách chuỗi thành mảng, xử lý cả trường hợp xuống dòng khác nhau (\r\n hoặc \n)
  $lines = explode("\n", str_replace("\r", "", trim($text)));
  $output = '';

  // Chuẩn bị chuỗi class nếu có truyền vào (ví dụ: ' class="my-class"')
  $class_attr = '';
  if (!empty($class_name)) {
    // esc_attr giúp làm sạch tên class để tránh lỗi bảo mật hoặc lỗi HTML
    $class_attr = ' class="' . esc_attr(trim($class_name)) . '"';
  }

  foreach ($lines as $index => $line) {
    $line = trim($line); // Xóa khoảng trắng thừa từng dòng
    if (empty($line)) continue;
    $output .= '<p' . $class_attr . '>' . esc_html($line) . '</p>';
  }

  return $output;
}
