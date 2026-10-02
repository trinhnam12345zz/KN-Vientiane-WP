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
