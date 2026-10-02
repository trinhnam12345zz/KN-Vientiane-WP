# 👑 KN VIENTIANE GROUP - TÀI LIỆU DỰ ÁN & LỊCH SỬ PHÁT TRIỂN

## 🚨 1. TOÀN BỘ LỖI & BẤT CẬP TRƯỚC KHI NHẬN DỰ ÁN

Khi bắt đầu tiếp nhận dự án từ bản backup ban đầu, hệ thống tồn tại rất nhiều lỗi nghiêm trọng về logic, dữ liệu và giao diện:

### 1.1. Lỗi hệ thống Đa ngôn ngữ (WPML cũ)
* ❌ **Ảnh hưởng nghiêm trọng đến hiệu suất (Performance)**: Plugin WPML cũ tạo ra quá nhiều bảng phụ và truy vấn thừa trong cơ sở dữ liệu (Database Bloat), làm cho tốc độ tải trang (đặc biệt là trang chủ và trang quản trị) bị chậm chạp và ì ạch.

* ❌ **Xung đột cấu trúc dữ liệu (Data Corruption)**: Cơ chế dịch chéo bài viết và chuyên mục của WPML hoạt động thiếu ổn định với Custom Post Type và ACF, gây ra hiện tượng sinh ra rác dữ liệu, nhân bản chuyên mục (duplicate categories) không kiểm soát ở các ngôn ngữ Tiếng Hàn và Tiếng Trung.
* ❌ **Khó khăn trong việc tích hợp Tiếng Lào**: WPML gặp nhiều bất cập trong việc cấu hình Locale, font chữ và hiển thị cờ tùy chỉnh cho thị trường Lào, dẫn đến hiện tượng lỗi font (hiển thị ô vuông) và đứt gãy link.

-> **Giải pháp đã thực hiện**: Gỡ bỏ hoàn toàn WPML, dọn dẹp sạch sẽ dữ liệu rác do WPML để lại, và chuyển đổi toàn bộ cấu trúc đa ngôn ngữ sang **Polylang**. Polylang hoạt động nhẹ nhàng, miễn phí, truy vấn cực kỳ tối ưu và dễ dàng custom các ngôn ngữ đặc thù như Tiếng Lào.

### 1.2. Lỗi Xung đột Đường dẫn & Trùng Slug (CPT Hijacking)
* ❌ **Trùng slug giữa Dự án và Lĩnh vực hoạt động**: Bài Dự án *"Thăm dò đồng tại Lào"* (`post_type: du-an`) và bài Lĩnh vực *"Khai thác khoáng sản"* (`post_type: linh-vuc-hoat-dong`) có cùng slug URL `khai-thac-khoang-san`. 
* ❌ **Lỗi đè bài viết**: Hàm can thiệp truy vấn `pre_get_posts` trong theme cũ thực hiện câu truy vấn SQL tìm bài theo slug mà không lọc theo `post_type`, dẫn đến việc người dùng bấm xem Dự án nhưng website lại hiển thị nội dung của Lĩnh vực hoạt động.

### 1.3. Lỗi Chân trang (Footer) ở các ngôn ngữ ngoài Tiếng Việt
* ❌ **Mất link Chính sách và Tuyển dụng**: Ở 4 ngôn ngữ (Lào, Anh, Trung, Hàn), phần dưới cùng Footer bị ẩn trắng liên kết "Chính sách" và "Tuyển dụng" do ACF Options Page của các ngôn ngữ này chưa có dữ liệu và code cũ không có cơ chế fallback.

### 1.4. Lỗi Giao diện & Tiện ích Sidebar (Widgets)
* ❌ **Khối Tin tức Nổi bật (News Hero Slider)**: Không đọc đúng danh sách bài viết ghim từ trường `featured_posts` của ACF.
* ❌ **Khối Đề xuất bạn đọc (Recent Posts Widget)**: Code widget đọc sai tên trường `data['posts']` thay vì `data['list']`, không hiển thị đúng các bài viết được chọn và không tự chuyển đổi sang bài dịch khi xem ở các ngôn ngữ khác.
* ❌ **Khối Danh mục (Category Widget)**: Người dùng không thấy chỗ add link trong admin nhưng ngoài giao diện vẫn click được (gây khó hiểu trong quản trị).
* ❌ **Hardcode cứng tên Danh mục (Categories)**: Tên các chuyên mục quan trọng như "Tin tập đoàn", "Sự kiện", "Báo chí" bị code cứng trong giao diện (`widget-links.php`, `index.php`), vô hiệu hóa hệ thống quản trị taxonomy của WordPress, khiến người dùng không thể đổi tên hay tự động dịch thuật từ WP Admin.
* ❌ **Khối Phúc lợi công ty (Recruitment Benefit Widget)**: Nội dung danh sách quyền lợi bị cố định tiếng Việt, không tự động dịch sang tiếng Lào, Anh, Trung, Hàn.
* ❌ **Thanh điều hướng Breadcrumbs**: Hiển thị thô sơ, font chữ chưa tối ưu và không khớp nhãn tiêu đề theo từng ngôn ngữ.

### 1.5. Trang Báo lỗi 404
* ❌ **Trang 404 cũ sơ sài, lạc hậu**: Là một file HTML đơn giản nền trắng trơn, không có Header/Footer, không có logo nhận diện thương hiệu tập đoàn và hoàn toàn không hỗ trợ đa ngữ.

### 1.6. Bất cập trong Trang Quản trị (WP Admin)
* ❌ **Yoast SEO che khuất màn hình nhập liệu**: Khung Yoast SEO mặc định đặt độ ưu tiên cao (`priority: high`) nằm chình ình ở đầu trang chỉnh sửa, buộc người quản trị phải cuộn chuột rất xa mới thấy các ô nhập liệu ACF và nội dung bài viết.
* ❌ **Rác mã nguồn ở thư mục gốc**: Nhiều file script cào dữ liệu, file html, file dump tạm thời nằm rải rác ngoài thư mục dự án.

## 🚀 2. HƯỚNG DẪN CÀI ĐẶT & CHẠY DỰ ÁN (DÀNH CHO ĐỐI TÁC/DEV)

Do dự án được lưu trữ trên Git theo chuẩn tách rời mã nguồn (Decoupled), bạn sẽ nhận được thư mục `wordpress_site` chứa mã nguồn giao diện (wp-content) và cơ sở dữ liệu (`database.sql`). Để chạy dự án trên máy tính của bạn, vui lòng thực hiện các bước sau:

**Bước 1: Cài đặt WordPress Core**
- Thiết lập một môi trường Localhost mới (dùng XAMPP, LocalWP, Laragon, v.v.).
- Cài đặt WordPress nguyên bản trên môi trường này.

**Bước 2: Ghi đè mã nguồn (wp-content)**
- Mở thư mục `wp-content` của trang WordPress bạn vừa cài đặt.
- Copy đè các thư mục `themes`, `plugins`, `uploads`, `languages` (từ thư mục `wordpress_site` trên GitHub) vào thư mục `wp-content` của bạn.

**Bước 3: Nhập Cơ sở dữ liệu (Import Database)**
- Truy cập vào trình quản lý CSDL của bạn (ví dụ: phpMyAdmin, TablePlus).
- Chọn database của trang WordPress bạn vừa tạo.
- Xóa toàn bộ các bảng hiện có (Drop all tables).
- Import file `database.sql` (nằm trong thư mục `wordpress_site`) vào database này. File này chứa toàn bộ cấu trúc, dữ liệu và các cấu hình đã được sửa lỗi hoàn chỉnh.

**Bước 4: Đổi Tên miền (Search & Replace)**
- Vì file `database.sql` đang lưu trữ tên miền nội bộ là `http://kn-vientiane.local`, bạn cần cập nhật lại tên miền này.
- Sử dụng các công cụ Search & Replace của WordPress (ví dụ: lệnh WP-CLI `wp search-replace`, plugin Better Search Replace, hoặc script interconnect/it) để đổi toàn bộ chuỗi `http://kn-vientiane.local` thành tên miền localhost của bạn (ví dụ `http://localhost/knvientiane`).
- *Lưu ý: Tuyệt đối không dùng câu lệnh SQL `UPDATE` thông thường để đổi tên miền vì dữ liệu cấu hình của Elementor/ACF là dạng chuỗi tuần tự hóa (Serialized Data), việc sửa chay sẽ làm hỏng toàn bộ giao diện.*

**Bước 5: Hoàn tất**
- Đăng nhập vào trang quản trị (WP Admin).
- Vào mục **Settings (Cài đặt) -> Permalinks (Đường dẫn tĩnh)** và bấm **Save Changes (Lưu lại)** để làm mới lại cấu trúc URL (tránh lỗi 404).
- Ra ngoài trang chủ (Frontend) và kiểm tra lại website!
