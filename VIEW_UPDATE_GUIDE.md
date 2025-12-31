# Hướng dẫn sửa chữa giao diện (Views)

## Các thay đổi đã thực hiện:

### 1. ✅ Header & Footer
- Cập nhật footer.php để include `main.php` thay vì `main.js`

### 2. ✅ Trang chủ (Home)
- Thêm nút lọc theo danh mục (dropdown select)
- Hiển thị danh sách sản phẩm dạng grid
- Mỗi sản phẩm: hình ảnh, tên, giá, nút "Chi tiết", nút yêu thích (♡)

### 3. ✅ Trang giới thiệu
- Tạo mới file `about.php`
- Giới thiệu về trang web
- Tại sao chọn chúng tôi (5 điểm chính)
- Các loại pizza
- Cam kết của chúng tôi

### 4. ✅ Trang Liên hệ
- Cập nhật để hiển thị thông tin liên hệ (địa chỉ, SĐT, email, giờ hoạt động)
- Form gửi liên hệ có các trường:
  - Tên khách hàng
  - Email
  - Số điện thoại
  - Nội dung

### 5. ✅ Chi tiết sản phẩm (Modal/Overlay)
- Hiển thị thông tin sản phẩm
- Chọn size (Nhỏ, Vừa, Lớn)
- Nhập số lượng
- Nút "Thêm vào giỏ hàng"
- Nút "Thêm vào yêu thích"

### 6. ✅ Trang giỏ hàng
- Bảng danh sách sản phẩm (tên, giá, size, số lượng, thành tiền)
- Có thể sửa số lượng
- Nút xóa sản phẩm
- Hiển thị tổng tiền
- Nút "Tiếp tục mua sắm"
- Nút "Thanh toán"

### 7. ✅ Trang thanh toán (Checkout)
- Hiển thị thông tin đơn hàng (bảng)
- Phương thức thanh toán:
  - Radio button: "Thanh toán trực tiếp (COD)"
  - Radio button: "Thanh toán chuyển khoản"
- Thông tin giao hàng:
  - Tên người nhận
  - Số điện thoại
  - Địa chỉ giao hàng
- Nút "Quay lại"
- Nút "Đặt hàng"

### 8. ✅ Trang danh sách yêu thích
- Hiển thị danh sách sản phẩm yêu thích dạng grid
- Mỗi sản phẩm: hình ảnh, tên, giá, nút "Chi tiết", nút "Xóa"

### 9. ✅ Trang Admin - Dashboard
- Sidebar menu với các mục:
  - 📊 Dashboard (thống kê)
  - 🍕 Sản phẩm
  - 📁 Danh mục
  - 📦 Đơn hàng
  - 👥 Khách hàng
  - 💬 Liên hệ
  - 🚪 Đăng xuất
- Hiển thị thống kê:
  - Tổng đơn hàng
  - Tổng khách hàng
  - Tổng doanh thu
  - Số sản phẩm trong kho
- Bảng doanh thu theo tháng

## Các view files cần cập nhật thêm:

### Cần kiểm tra và sửa:
1. `app/Views/order/history.php` - Danh sách lịch sử đặt hàng
2. `app/Views/order/detail.php` - Chi tiết đơn hàng
3. `app/Views/profile/` - Trang hồ sơ cá nhân
4. `app/Views/admin/products/` - Danh sách, thêm, sửa sản phẩm
5. `app/Views/admin/categories/` - Danh sách, thêm, sửa danh mục
6. `app/Views/admin/orders/` - Danh sách, chi tiết, cập nhật trạng thái đơn hàng
7. `app/Views/admin/customers/` - Danh sách, chi tiết khách hàng
8. `app/Views/admin/contacts/` - Danh sách, chi tiết liên hệ
9. `app/Views/auth/` - Trang đăng nhập, đăng ký
10. `app/Views/components/header.php` - Header trang

## Cập nhật Header navigation:
Header cần có:
- Logo/Tên trang
- Navigation menu:
  - Trang chủ
  - Giới thiệu
  - Liên hệ
  - Giỏ hàng (với biểu tượng giỏ)
  - (Nếu đã đăng nhập) Tên người dùng + Đăng xuất
  - (Nếu chưa đăng nhập) Đăng nhập, Đăng ký

## Cập nhật Footer:
Footer cần có:
- Thông tin liên hệ
- Đường dẫn nhanh
- Bản quyền

## Cần hoàn thành tiếp:
- [ ] Cập nhật components/header.php
- [ ] Cập nhật order/history.php
- [ ] Cập nhật order/detail.php
- [ ] Cập nhật profile/ files
- [ ] Cập nhật auth/ files
- [ ] Cập nhật admin products files
- [ ] Cập nhật admin categories files
- [ ] Cập nhật admin orders files
- [ ] Cập nhật admin customers files
- [ ] Cập nhật admin contacts files

Bạn muốn tôi tiếp tục cập nhật những files còn lại không?
