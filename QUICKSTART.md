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

### 5. Cấu Trúc URL

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