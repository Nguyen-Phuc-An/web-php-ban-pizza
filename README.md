# 🍕 Pizza Online - Hệ Thống Bán Pizza Trực Tuyến

Một ứng dụng web bán pizza trực tuyến được xây dựng bằng **PHP thuần** với kiến trúc **MVC** và cơ sở dữ liệu **MySQL**.

---

## 📋 Mục Lục

- [Giới Thiệu](#giới-thiệu)
- [Yêu Cầu Hệ Thống](#yêu-cầu-hệ-thống)
- [Hướng Dẫn Cài Đặt](#hướng-dẫn-cài-đặt)
- [Cấu Trúc Thư Mục](#cấu-trúc-thư-mục)
- [Tính Năng Chính](#tính-năng-chính)
- [Hướng Dẫn Sử Dụng](#hướng-dẫn-sử-dụng)
- [Tài Khoản Mặc Định](#tài-khoản-mặc-định)
- [API Routing](#api-routing)
- [Troubleshooting](#troubleshooting)

---

## 🎯 Giới Thiệu

**Pizza Online** là một nền tảng e-commerce chuyên biệt cho việc bán pizza trực tuyến. Hệ thống cung cấp:

- ✅ Giao diện khách hàng thân thiện
- ✅ Hệ thống quản lý admin toàn diện
- ✅ Giỏ hàng với lưu trữ database
- ✅ Thanh toán và quản lý đơn hàng
- ✅ Danh sách yêu thích
- ✅ Hệ thống liên hệ
- ✅ Bảo mật với mã hóa mật khẩu BCRYPT
- ✅ Hỗ trợ chọn size pizza với cập nhật giá tự động

---

## 💻 Yêu Cầu Hệ Thống

- **PHP**: 7.0 hoặc cao hơn
- **MySQL**: 5.7 hoặc MariaDB 10.3+
- **Web Server**: Apache (với mod_rewrite)
- **Browser**: Chrome, Firefox, Safari, Edge (phiên bản mới nhất)

---

## 📦 Hướng Dẫn Cài Đặt

### 1. Chuẩn Bị Dữ Liệu

```sql
-- Nhập file SQL để tạo cơ sở dữ liệu
-- Mở phpMyAdmin và import file: web-ban-thucan.sql
```

### 2. Cấu Hình Cơ Sở Dữ Liệu

Chỉnh sửa file `config/Database.php`:

```php
<?php
// Thay đổi các thông tin sau:
define('DB_HOST', 'localhost:3307');  // Host MySQL
define('DB_NAME', 'web-ban-thucan');  // Tên CSDL
define('DB_USER', 'root');             // Tên user MySQL
define('DB_PASS', '');                 // Mật khẩu MySQL
```

### 3. Cấu Hình URL

Chỉnh sửa file `config/constants.php`:

```php
<?php
// Thay đổi URL theo cổng của bạn
define('SITE_URL', 'http://localhost:81/web-php-ban-pizza/public/');
```

### 4. Tạo Thư Mục Upload

```bash
mkdir public/uploads
chmod 755 public/uploads
```

### 5. Truy Cập Ứng Dụng

- **Trang chủ khách hàng**: `http://localhost:81/web-php-ban-pizza/public/`
- **Trang admin**: `http://localhost:81/web-php-ban-pizza/public/index.php?action=admin`

---

## 📂 Cấu Trúc Thư Mục

```
web-php-ban-pizza/
├── app/
│   ├── Controllers/        # Các controller
│   │   ├── AuthController.php
│   │   ├── ProductController.php
│   │   ├── CartController.php
│   │   ├── OrderController.php
│   │   ├── WishlistController.php
│   │   ├── ContactController.php
│   │   ├── ProfileController.php
│   │   └── AdminController.php
│   │
│   ├── Models/            # Các model
│   │   ├── Model.php
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Wishlist.php
│   │   └── Contact.php
│   │
│   └── Views/             # Các view (giao diện)
│       ├── layout/        # Layout chung (header, footer)
│       ├── components/    # Component tái sử dụng
│       ├── home/          # Trang chủ
│       ├── auth/          # Đăng nhập, đăng ký
│       ├── product/       # Chi tiết sản phẩm
│       ├── cart/          # Giỏ hàng
│       ├── order/         # Đơn hàng
│       ├── wishlist/      # Danh sách yêu thích
│       ├── contact/       # Liên hệ
│       ├── profile/       # Hồ sơ cá nhân
│       └── admin/         # Quản lý admin
│
├── config/
│   ├── Database.php       # Cấu hình kết nối CSDL
│   └── constants.php      # Các hằng số
│
├── public/
│   ├── index.php          # File router chính
│   ├── uploads/           # Thư mục lưu ảnh sản phẩm
│   └── assets/
│       ├── css/
│       │   └── style.css  # CSS chính
│       └── js/
│           └── main.php   # JavaScript chính
│
└── web-ban-thucan.sql    # File cơ sở dữ liệu
```

---

## 🚀 Tính Năng Chính

### 👥 Phía Khách Hàng

| Tính Năng | Mô Tả |
|-----------|-------|
| **Xem Sản Phẩm** | Duyệt danh sách pizza với hình ảnh, giá cả |
| **Lọc Danh Mục** | Lọc sản phẩm theo danh mục |
| **Chi Tiết Sản Phẩm** | Xem chi tiết, chọn size, thêm vào giỏ |
| **Giỏ Hàng** | Quản lý sản phẩm, cập nhật số lượng |
| **Thanh Toán** | Chọn phương thức (COD hoặc chuyển khoản) |
| **Lịch Sử Đơn Hàng** | Xem danh sách và chi tiết đơn hàng |
| **Danh Sách Yêu Thích** | Lưu sản phẩm yêu thích |
| **Hồ Sơ Cá Nhân** | Chỉnh sửa thông tin tài khoản |
| **Liên Hệ** | Gửi tin nhắn đến quản trị viên |

### 🔧 Phía Quản Trị Viên

| Tính Năng | Mô Tả |
|-----------|-------|
| **Dashboard** | Xem thống kê, biểu đồ doanh thu |
| **Quản Lý Sản Phẩm** | Thêm, sửa, xóa sản phẩm |
| **Quản Lý Danh Mục** | Thêm, sửa, xóa danh mục |
| **Quản Lý Đơn Hàng** | Xem, cập nhật trạng thái đơn hàng |
| **Quản Lý Khách Hàng** | Xem danh sách khách, lịch sử mua |
| **Quản Lý Liên Hệ** | Xem tin nhắn từ khách hàng |
| **Đăng Xuất** | Thoát khỏi tài khoản admin |

---

## 💡 Hướng Dẫn Sử Dụng

### Đăng Ký Tài Khoản Khách Hàng

1. Truy cập: `http://localhost:81/web-php-ban-pizza/public/`
2. Nhấp **Đăng Ký** ở góc trên phải
3. Điền thông tin:
   - Tên người dùng
   - Email
   - Mật khẩu
   - Số điện thoại
   - Địa chỉ
4. Nhấp **Đăng Ký**

### Mua Pizza

1. Truy cập trang chủ
2. Chọn pizza hoặc lọc theo danh mục
3. Nhấp **Chi Tiết** hoặc ảnh sản phẩm
4. Chọn size (Nhỏ, Vừa, Lớn)
5. Nhập số lượng
6. Nhấp **Thêm vào giỏ hàng**
7. Truy cập **Giỏ hàng** > **Thanh toán**
8. Chọn phương thức thanh toán
9. Nhấp **Đặt hàng**

### Truy Cập Admin

1. Truy cập: `http://localhost:81/web-php-ban-pizza/public/index.php?action=admin`
2. Đăng nhập với tài khoản admin
3. Sử dụng menu bên trái để quản lý

---

## 🔑 Tài Khoản Mặc Định

Sau khi import SQL, tạo tài khoản admin bằng cách chạy:

```sql
-- Chạy trong phpMyAdmin
INSERT INTO users (ten_nguoi_dung, email_user, mat_khau, so_dien_thoai_user, dia_chi, loai_user)
VALUES (
    'Admin',
    'admin@pizza.com',
    '$2y$10$YmFzZTY0X2VuY29kZWRfcGFzc3dvcmQhIQ==',  -- password: admin123
    '0123456789',
    '123 Đường Pizza',
    'Admin'
);
```

**Tài khoản admin mặc định:**
- Email: `admin@pizza.com`
- Mật khẩu: `admin123`

---

## 🌐 API Routing

Hệ thống sử dụng query string routing:

```
/?action=<controller>&method=<method>&<param>=<value>
```

### Các Route Chính

**Khách Hàng:**
- `/?action=home&method=index` - Trang chủ
- `/?action=home&method=about` - Giới thiệu
- `/?action=product&method=index` - Danh sách sản phẩm
- `/?action=product&method=detail&id=1` - Chi tiết sản phẩm
- `/?action=cart&method=index` - Giỏ hàng
- `/?action=order&method=checkout` - Thanh toán
- `/?action=wishlist&method=index` - Danh sách yêu thích
- `/?action=contact&method=index` - Liên hệ
- `/?action=auth&method=login` - Đăng nhập
- `/?action=auth&method=register` - Đăng ký
- `/?action=auth&method=logout` - Đăng xuất

**Admin:**
- `/?action=admin&method=dashboard` - Dashboard
- `/?action=admin&method=products` - Quản lý sản phẩm
- `/?action=admin&method=categories` - Quản lý danh mục
- `/?action=admin&method=orders` - Quản lý đơn hàng
- `/?action=admin&method=customers` - Quản lý khách hàng
- `/?action=admin&method=contacts` - Quản lý liên hệ

---

## 🔒 Bảo Mật

### Mã Hóa Mật Khẩu
```php
// Mã hóa mật khẩu
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Xác minh mật khẩu
password_verify($inputPassword, $hashedPassword);
```

### SQL Injection Prevention
Tất cả các truy vấn sử dụng **Prepared Statements**:
```php
$stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
```

### Session Management
- Phiên làm việc được lưu trữ an toàn
- Kiểm tra xác thực khi truy cập admin
- Tự động xóa session khi đăng xuất

---

## 🐛 Troubleshooting

### Lỗi: "CSDL không kết nối"

**Giải pháp:**
1. Kiểm tra XAMPP đã khởi động MySQL
2. Kiểm tra thông tin trong `config/Database.php`
3. Kiểm tra cổng MySQL (mặc định 3306 hoặc 3307)

### Lỗi: "Trang không tìm thấy"

**Giải pháp:**
1. Kiểm tra URL: `http://localhost:81/web-php-ban-pizza/public/`
2. Kiểm tra cấu hình `constants.php` với cổng của bạn
3. Kiểm tra module mod_rewrite đã bật

### Lỗi: "Không thể upload ảnh"

**Giải pháp:**
1. Kiểm tra thư mục `public/uploads` tồn tại
2. Cấp quyền: `chmod 755 public/uploads`
3. Kiểm tra dung lượng file không vượt quá giới hạn

### Lỗi: "CSS/JS không tải"

**Giải pháp:**
1. Xóa cache browser: **Ctrl + Shift + Delete**
2. Kiểm tra đường dẫn assets trong `config/constants.php`
3. Kiểm tra file CSS/JS tồn tại

---

## 📞 Liên Hệ & Hỗ Trợ

Nếu bạn gặp vấn đề:

1. Kiểm tra phần **Troubleshooting**
2. Đọc file documentation khác
3. Kiểm tra error logs trong XAMPP

---

## 📄 License

Dự án này được tạo cho mục đích học tập và sử dụng cá nhân.

---

## ✨ Tính Năng Bổ Sung (Trong Tương Lai)

- [ ] Thanh toán online (VNPay, PayPal)
- [ ] Email thông báo tự động
- [ ] Đánh giá sản phẩm
- [ ] Coupon/Discount code
- [ ] Notification system
- [ ] Chat support

---

**Cảm ơn bạn đã sử dụng Pizza Online!** 🍕