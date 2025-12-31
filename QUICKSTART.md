// Quick Start Guide - Pizza Online

## Bắt Đầu Nhanh Chóng

### 1. Thiết Lập Database

```sql
-- Mở phpMyAdmin hoặc MySQL CLI
-- Tạo database
CREATE DATABASE `web-ban-thucan`;

-- Chọn database
USE `web-ban-thucan`;

-- Import tệp web-ban-thucan.sql
-- Hoặc chạy lệnh SQL từ tệp
```

### 2. Truy Cập Ứng Dụng

**Khách hàng:**
- URL: `http://localhost/web-php-ban-pizza/public/index.php`
- Hoặc: `http://localhost/web-php-ban-pizza/public/` (nếu có .htaccess)

**Admin:**
- URL: `http://localhost/web-php-ban-pizza/public/index.php?action=admin&method=dashboard`

### 3. Tài Khoản Mặc Định

Sau khi import database, bạn có thể tạo tài khoản admin:

```sql
INSERT INTO users (ten_nguoi_dung, email_user, mat_khau, loai_user)
VALUES ('Admin', 'admin@example.com', '$2y$10$...', 'admin');
```

Hoặc dùng hàm PHP để hash password:
```php
$password = password_hash('password123', PASSWORD_BCRYPT);
echo $password; // Dùng giá trị này trong SQL
```

### 4. Các Tính Năng Chính

**Website Khách Hàng:**
1. Trang chủ - Xem danh sách sản phẩm
2. Lọc theo danh mục
3. Tìm kiếm sản phẩm
4. Xem chi tiết sản phẩm
5. Thêm vào giỏ hàng
6. Thanh toán
7. Xem lịch sử đơn hàng
8. Quản lý yêu thích
9. Hồ sơ cá nhân
10. Liên hệ cửa hàng

**Admin:**
1. Dashboard - Xem thống kê
2. Quản lý sản phẩm - Thêm/Sửa/Xóa
3. Quản lý danh mục - Thêm/Sửa/Xóa
4. Quản lý đơn hàng - Cập nhật trạng thái
5. Quản lý khách hàng - Xem chi tiết
6. Quản lý liên hệ - Xem tin nhắn

### 5. Thêm Sản Phẩm Mẫu (SQL)

```sql
-- Thêm danh mục
INSERT INTO categories (ten_categories, mo_ta_categories, ngay_tao_categories, ngay_cap_nhap_categories)
VALUES ('Pizza Classic', 'Các loại pizza cổ điển', NOW(), NOW());

-- Thêm sản phẩm (sau khi upload hình ảnh)
INSERT INTO products (ten_product, mo_ta_product, gia_product, danh_muc_product, hinh_anh_product, ngay_tao_product, ngay_cap_nhap_product)
VALUES ('Margherita', 'Pizza Margherita truyền thống', 150000, 1, 'margherita.jpg', NOW(), NOW());
```

### 6. Cấu Trúc URL

```
Trang chủ: index.php
Sản phẩm: index.php?action=product
Chi tiết: index.php?action=product&method=detail&id=1
Tìm kiếm: index.php?action=product&method=search?q=keyword

Giỏ hàng: index.php?action=cart&method=view
Thanh toán: index.php?action=order&method=checkout
Lịch sử: index.php?action=order&method=history

Đăng nhập: index.php?action=auth&method=login
Đăng ký: index.php?action=auth&method=register

Admin: index.php?action=admin&method=dashboard
```

### 7. Folder Quan Trọng

- `public/uploads/` - Upload hình ảnh sản phẩm (cấp quyền 777)
- `public/assets/css/` - File CSS chính
- `public/assets/js/` - File JavaScript chính
- `app/Views/` - Tất cả template HTML

### 8. Các File Cấu Hình

- `config/Database.php` - Kết nối database
- `config/constants.php` - Hằng số ứng dụng
- `public/index.php` - Router chính

### 9. Kiểm Tra Lỗi Phổ Biến

```
Error: SQLSTATE[HY000] [1045]
→ Kiểm tra user/password database

Error: Call to undefined function
→ Kiểm tra include/require path

Error: Cannot write to directory
→ Kiểm tra quyền folder (chmod 777)

Error: Session error
→ Kiểm tra cấu hình session.save_path trong php.ini
```

### 10. Tùy Chỉnh

**Thay đổi thông tin cửa hàng:**
- Mở `app/Views/components/footer.php`
- Chỉnh sửa thông tin liên hệ

**Thay đổi tên website:**
- Mở `config/constants.php`
- Chỉnh sửa SITE_URL

**Thay đổi style:**
- Mở `public/assets/css/style.css`
- Chỉnh sửa CSS variables `:root { ... }`

---

### Liên Hệ Hỗ Trợ

Nếu gặp vấn đề, vui lòng kiểm tra:
1. Đã import database chưa?
2. MySQL đang chạy?
3. Quyền folder uploads?
4. PHP version >= 7.0?
5. Cấu hình database.php đúng?
