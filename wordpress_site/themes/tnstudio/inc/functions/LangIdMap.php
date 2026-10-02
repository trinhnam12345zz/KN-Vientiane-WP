<?php

/**
 * Bảng khai báo thủ công: nội dung nào là bản của ngôn ngữ nào.
 *
 * Hiện giữ hai bảng: bài web-builder và form Contact Form 7.
 *
 * TẠI SAO PHẢI CÓ FILE NÀY
 *
 * Các field trỏ tới web-builder (section_activity, section_contact, achievement,
 * contact_info...) lưu ID của bài GỐC tiếng Việt. Bình thường WPML lo phần đổi
 * sang bản dịch, và mona_web_builder_id() vẫn ưu tiên hỏi WPML trước.
 *
 * Nhưng dữ liệu WPML của site này không tin được ở đúng chỗ đó:
 *  - Các bài web-builder tiếng Anh / Lào / Hàn KHÔNG phải bản dịch WPML, mà là
 *    bài rời tạo riêng cho từng ngôn ngữ. Chúng chạy được vì mỗi trang chủ của
 *    từng ngôn ngữ tự trỏ sang bài của mình.
 *  - Hộp "Kết nối với bản dịch" của WPML đưa ra HAI mục trùng tên y hệt nhau
 *    cho cùng một bài (4429 và 4435) trong khi tiếng Việt chỉ có đúng một bài.
 *    Chọn bừa là nối nhầm nhóm dịch, gỡ ra rất phiền.
 *
 * Nên phần ánh xạ được khai báo thẳng ở đây: nhìn là biết, nằm trong git, review
 * và rollback được, không phụ thuộc trạng thái nội bộ của WPML.
 *
 * CÁCH THÊM
 *
 * Tạo bài web-builder cho ngôn ngữ cần, rồi thêm một dòng:
 *     <ID bài gốc tiếng Việt> => ['<mã ngôn ngữ>' => <ID bài mới>],
 *
 * Mã ngôn ngữ lấy đúng như WPML đăng ký (`zh-hant`, không phải `zh`).
 * Bài nào chưa khai báo thì giữ nguyên hành vi cũ — dùng bài gốc.
 *
 * @author MONA.Media / Website
 */

defined('ABSPATH') || exit;

if (! function_exists('mona_web_builder_lang_map')) {
    /**
     * @return array<int, array<string, int>>
     */
    function mona_web_builder_lang_map(): array
    {
        $map = [
            // Thành tựu / Số liệu thống kê (10+ năm kinh nghiệm, 86+ đối tác...)
            180 => ['zh-hant' => 1520],

            // Section khu vực hoạt động (khối "KHU VỰC HOẠT ĐỘNG" ở trang chủ)
            304 => ['zh-hant' => 1521],

            // Section liên hệ (khối "Kết nối cùng chúng tôi" + form)
            177 => ['zh-hant' => 1522],

            // Thông tin liên hệ (điện thoại / email / địa chỉ)
            183 => ['zh-hant' => 1523],
        ];

        /**
         * Cho phép plugin hoặc child theme bổ sung mà không phải sửa file này.
         */
        return apply_filters('mona_web_builder_lang_map', $map);
    }
}

if (! function_exists('mona_cf7_lang_map')) {
    /**
     * Bảng khai báo form Contact Form 7 theo ngôn ngữ.
     *
     * Khoá là MÃ BĂM trong shortcode (`[contact-form-7 id="20c8e18"]`), không
     * phải ID bài — vì lúc cần tra thì chỉ có mã băm trong tay.
     *
     * Vì sao không dùng liên kết dịch của WPML: bản dịch form phải tạo qua Bảng
     * điều khiển dịch thuật, mà bảng đó đang thiếu bước gửi đi dịch (site key
     * chưa đăng ký). Form tiếng Trung vì vậy được tạo tay, đứng rời, không nằm
     * trong nhóm dịch nào — nên phải chỉ đường thủ công.
     *
     * @return array<string, array<string, int>>
     */
    function mona_cf7_lang_map(): array
    {
        $map = [
            // Form liên hệ (124) — dùng ở section liên hệ trang chủ và trang Liên hệ
            '20c8e18' => ['zh-hant' => 1549],

            // Form đăng ký - footer (608)
            'db86e06' => ['zh-hant' => 1550],
        ];

        return apply_filters('mona_cf7_lang_map', $map);
    }
}
