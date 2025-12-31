# Pizza Online - Website Bán Pizza Trực Tuyến

## Mô Tả Dự Án

Pizza Online là một website bán pizza trực tuyến được xây dựng bằng PHP thuần với mô hình MVC đơn giản và MySQL. Hệ thống cho phép khách hàng xem sản phẩm, đặt hàng online và nhân viên quản lý toàn bộ hệ thống bán hàng.

## Công Nghệ Sử Dụng

- **Backend**: PHP 7.0+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Mô hình**: MVC (Model-View-Controller)
- **Server**: Apache/XAMPP

## Cấu Trúc Dự Án

```
web-php-ban-pizza/
├── app/
│   ├── Controllers/      # Controllers cho routing
│   ├── Models/          # Models cho database interaction
│   └── Views/           # Views template HTML
├── config/              # Cấu hình ứng dụng
├── public/              # Entry point và assets
│   ├── index.php        # Router chính
│   ├── assets/
│   │   ├── css/         # Stylesheet
│   │   ├── js/          # JavaScript
│   │   └── images/      # Hình ảnh tĩnh
│   └── uploads/         # Thư mục upload hình ảnh sản phẩm
└── web-ban-thucan.sql   # Database schema
```

## Cài Đặt

### 1. Yêu Cầu Hệ Thống
- PHP 7.0 hoặc cao hơn
- MySQL 5.7 hoặc MariaDB 10.2+
- Apache với mod_rewrite
- XAMPP hoặc tương đương

### 2. Bước Cài Đặt

1. **Clone hoặc tải dự án:**
   ```bash
   cd C:\xampp\htdocs\web-php-ban-pizza
   ```

2. **Tạo database:**
   - Mở phpMyAdmin (http://localhost/phpmyadmin)
   - Tạo database mới: `web-ban-thucan`
   - Import file `web-ban-thucan.sql`

3. **Cấu hình database:**
   - Mở file `config/Database.php`
   - Chỉnh sửa thông tin kết nối nếu cần
   - Mặc định: localhost:3307, user: root, password: (trống)

4. **Quyền thư mục:**
   - Đảm bảo `public/uploads/` có quyền ghi

5. **Truy cập ứng dụng:**
   - Website: `http://localhost/web-php-ban-pizza/public/index.php`
   - Admin: `http://localhost/web-php-ban-pizza/public/index.php?action=admin&method=dashboard`

## Các Tính Năng

### Khách Hàng
- ✅ Đăng ký/Đăng nhập
- ✅ Xem danh sách sản phẩm
- ✅ Lọc theo danh mục
- ✅ Tìm kiếm sản phẩm
- ✅ Chi tiết sản phẩm (overlay)
- ✅ Chọn size pizza
- ✅ Giỏ hàng
- ✅ Đặt hàng & thanh toán
- ✅ Xem lịch sử đơn hàng
- ✅ Hồ sơ cá nhân
- ✅ Danh sách yêu thích
- ✅ Liên hệ cửa hàng

### Admin/Nhân Viên
- ✅ Dashboard thống kê
- ✅ Quản lý sản phẩm (CRUD)
- ✅ Quản lý danh mục
- ✅ Quản lý đơn hàng
- ✅ Cập nhật trạng thái đơn hàng
- ✅ Quản lý khách hàng
- ✅ Xem lịch sử mua hàng khách
- ✅ Quản lý liên hệ

## Cấu Trúc Database

### Bảng chính:
- **users**: Người dùng (customer, admin, staff)
- **products**: Sản phẩm pizza
- **categories**: Danh mục
- **orders**: Đơn hàng
- **order_items**: Chi tiết đơn hàng
- **wishlists**: Danh sách yêu thích
- **contacts**: Liên hệ

## Routing

Ứng dụng sử dụng query string:

```
Home: ?action=home
Auth: ?action=auth&method=login|register|logout
Products: ?action=product[&method=detail&id=X]
Cart: ?action=cart&method=view|add|remove
Order: ?action=order&method=checkout|history|detail
Profile: ?action=profile&method=view
Wishlist: ?action=wishlist&method=view|add|remove
Contact: ?action=contact
Admin: ?action=admin&method=dashboard|products|orders|customers|contacts
```

## Các Lớp Chính

### Models
- `Model.php` - Base model với CRUD
- `User.php` - Quản lý người dùng
- `Product.php` - Quản lý sản phẩm
- `Category.php` - Quản lý danh mục
- `Order.php` - Quản lý đơn hàng
- `OrderItem.php` - Chi tiết đơn hàng
- `Wishlist.php` - Danh sách yêu thích
- `Contact.php` - Liên hệ

### Controllers
- `Controller.php` - Base controller
- `AuthController.php` - Xác thực
- `ProductController.php` - Sản phẩm
- `CartController.php` - Giỏ hàng
- `OrderController.php` - Đơn hàng
- `ProfileController.php` - Hồ sơ
- `WishlistController.php` - Yêu thích
- `ContactController.php` - Liên hệ
- `AdminController.php` - Quản trị

## Security

- Password hashing với BCRYPT
- SQL injection prevention (prepared statements)
- Session management
- Input validation

## Troubleshooting

**Lỗi kết nối DB:**
- Kiểm tra `config/Database.php`
- Đảm bảo MySQL đang chạy

**Lỗi upload hình:**
- Kiểm tra quyền `public/uploads/`
- Kiểm tra `upload_max_filesize` trong php.ini

**Lỗi session:**
- Kiểm tra cấu hình session trong php.ini

## Tác Giả

Dự án được phát triển để hỗ trợ học tập PHP MVC Pattern.

---

Để biết thêm chi tiết, vui lòng liên hệ qua form liên hệ trên website.
