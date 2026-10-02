# Tổng hợp thông tin và các thay đổi giao diện/logic (Dự án KN Vientiane)

File này tổng hợp toàn bộ các vấn đề đã xử lý, vị trí file đã sửa và logic cốt lõi trong phiên làm việc, nhằm giúp bạn dễ dàng cung cấp bối cảnh (context) cho các phiên làm việc (chat) tiếp theo.

## 1. Sửa lỗi Polylang không hiển thị tuỳ chọn "Show all languages"
*   **Vấn đề:** Trong trang quản trị wp-admin, phần String Translation của Polylang bị kẹt ở ngôn ngữ tiếng Việt (vi) và không thể lọc "Show all languages". Bảng dịch hiện ra các ô trống trơn.
*   **Nguyên nhân:** File `CommonHook.php` có một đoạn mã can thiệp vào hook `admin_init` ép buộc biến `$_GET['lang'] = 'vi'`, khiến Polylang luôn tưởng người dùng đang lọc tiếng Việt.
*   **Cách xử lý:** Đã vô hiệu hóa (comment lại) đoạn code gây lỗi từ dòng 1132 đến 1144 trong file `CommonHook.php`.
*   **File đã sửa:** `d:\WP KnVientiane\wordpress_site\themes\tnstudio\inc\hooks\CommonHook.php`

## 2. Đăng ký chuỗi dịch thuật (String Translation) — ĐÃ HOÀN TẤT, SỬA LỖI FONT VÀ NHẬP CHUẨN 5 NGÔN NGỮ
*   **Vấn đề ban đầu:** Từ khóa "Tuyển dụng" bị hardcode trong giao diện. Toàn bộ chuỗi UI trên trang Tuyển dụng (placeholder, label dropdown, nút reset/clear...) bị hardcode trong mảng `$i18n_filter`.
*   **Sự cố phát sinh:** 
    *   Bản dịch tiếng Lào ban đầu bị sai ngữ nghĩa (`ການສະໝັກງານ` = nộp đơn xin việc thay vì tuyển dụng) và lỗi ký tự (`ຆ` thay vì `ຊ`).
    *   Lỗi font / ô vuông (tofu `[?]`): Khi lưu chuỗi qua một số lệnh Windows shell, ký tự tiếng Hàn, tiếng Lào bị lỗi mã hóa UTF-8 dẫn đến ký tự rác hoặc ô vuông khi hiển thị.
*   **Cách xử lý:** 
    *   Đã chuyển đổi toàn bộ sang hệ thống `pll_register_string` / `pll__()` của Polylang trong nhóm **"KN Vientiane"**.
    *   Đã viết script chuẩn UTF-8 nạp trực tiếp vào catalog `PLL_MO` của Polylang cho 5 ngôn ngữ.
    *   Đã chuẩn hóa bản dịch tiếng Lào: `ຮັບສະໝັກພະນັກງານ` (Tuyển dụng), `ຊອກຫາຕຳແໜ່ງງານ...` (Tìm việc), `ຕັ້ງຄ່າໃໝ່` (Đặt lại).
    *   Đã kiểm tra font hiển thị trên thực tế trình duyệt: Tiếng Lào (`/lo/`), Tiếng Hàn (`/ko/`), Tiếng Trung (`/zh/`) đều hiển thị sắc nét, chuẩn font chữ, hoàn toàn không còn ô vuông hay vỡ chữ.
*   **Danh sách 7 chuỗi đã hoàn thiện:**
    1. `tuyen_dung_title` → "Tuyển dụng"
    2. `tuyen_dung_search` → "Tìm vị trí ứng tuyển..."
    3. `tuyen_dung_all_positions` → "Tất cả vị trí"
    4. `tuyen_dung_all_locations` → "Tất cả địa điểm"
    5. `tuyen_dung_reset` → "Đặt lại"
    6. `tuyen_dung_clear` → "Xóa"
    7. `tuyen_dung_no_data` → "Không tìm thấy dữ liệu nào"
*   **Files đã sửa:**
    *   `d:\WP KnVientiane\wordpress_site\themes\tnstudio\inc\hooks\CommonHook.php`
    *   `d:\WP KnVientiane\wordpress_site\themes\tnstudio\archive-tuyen-dung.php`
    *   `d:\WP KnVientiane\wordpress_site\themes\tnstudio\archive-tuyen-dung-luxury.php`
    *   Đã đồng bộ toàn bộ sang thư mục LocalWP (`C:\Users\TRINH NAM\Local Sites\kn-vientiane\app\public\wp-content\themes\tnstudio\`).

## 3. Đồng bộ hiển thị Thanh điều hướng (Breadcrumb)
*   **Vấn đề:** Thanh điều hướng "TRANG CHỦ / ..." ở các trang Tuyển dụng và Tin tức bị lệch lề ngang (bị đẩy thụt vào) và lệch lề dọc (khoảng cách từ thanh MENU xuống không đều nhau).
*   **Lịch sử xử lý:**
    *   **Trang Tin Tức (`index.php`):** Bị bọc thừa thẻ `<div class="container">` làm cho chữ bị đẩy lệch sang trái. Đã xoá bỏ thẻ container thừa này.
    *   **Trang Tuyển dụng (`archive-tuyen-dung.php`):** Khoảng cách dọc (top padding) được quản lý bởi class `.banner-main`.
    *   **Thống nhất khoảng cách dọc:** Đã trả về nguyên trạng padding mặc định (9.5rem khoảng trống để không bị đè vào header) cho class `.banner-main` của trang Tuyển dụng, đồng thời gỡ bỏ các class padding/margin thừa ở trang Tin tức, giúp cả 2 trang tự động đẩy xuống một khoảng cách hoàn toàn bằng nhau so với thanh MENU mà không dùng hiệu ứng đè chèn ảnh (position: absolute).
*   **Các file đã sửa:** 
    *   `d:\WP KnVientiane\wordpress_site\themes\tnstudio\index.php` (Xoá div wrapper thừa).
    *   `d:\WP KnVientiane\wordpress_site\themes\tnstudio\archive-tuyen-dung.php` (Trả lại class css `.banner-main` gốc để duy trì padding-top 9.5rem).

## Ghi chú cho phiên tiếp theo
*   **QUAN TRỌNG — Thư mục làm việc:** Workspace `d:\WP KnVientiane\wordpress_site\` là bản sao/backup, KHÔNG phải thư mục LocalWP đang chạy. LocalWP đọc code từ `C:\Users\TRINH NAM\Local Sites\kn-vientiane\app\public\wp-content\themes\tnstudio\`. Mỗi lần sửa file trong workspace, **phải copy sang thư mục LocalWP** để thay đổi có hiệu lực.
*   Layout của breadcrumb đã được chốt là dùng luồng hiển thị chuẩn (document flow), có không gian padding để đẩy lùi xuống dưới Header, **không dùng position: absolute** (để tránh lỗi đè lên chữ MENU).
*   Cơ chế dịch ngôn ngữ tĩnh đã chuyển sang **Polylang `pll_register_string`** (group "KN Vientiane"). Nếu cần thêm từ khóa mới, đăng ký trong `CommonHook.php` và dùng `pll__()` trong template.
*   File `archive-tuyen-dung-luxury.php` dùng encoding **UTF-16 LE (Unicode)** — tool `view_file` không đọc được, phải dùng lệnh PowerShell với `-Encoding Unicode`.
