# Hướng dẫn sửa chữa giao diện (Views)

## Các thay đổi đã thực hiện:

### 1. Header & Footer
- Cập nhật footer.php để include `main.php` thay vì `main.js`

### 2. Trang chủ (Home)
- Thêm nút lọc theo danh mục (dropdown select)
- Hiển thị danh sách sản phẩm dạng grid
- Mỗi sản phẩm: hình ảnh, tên, giá, nút "Chi tiết", nút yêu thích (♡)

### 3. Trang giới thiệu
- Tạo mới file `about.php`
- Giới thiệu về trang web
- Tại sao chọn chúng tôi (5 điểm chính)
- Các loại pizza
- Cam kết của chúng tôi

### 4. Trang Liên hệ
- Cập nhật để hiển thị thông tin liên hệ (địa chỉ, SĐT, email, giờ hoạt động)
- Form gửi liên hệ có các trường:
  - Tên khách hàng
  - Email
  - Số điện thoại
  - Nội dung

### 5. Chi tiết sản phẩm (Modal/Overlay)
- Hiển thị thông tin sản phẩm
- Chọn size (Nhỏ, Vừa, Lớn)
- Nhập số lượng
- Nút "Thêm vào giỏ hàng"
- Nút "Thêm vào yêu thích"

### 6. Trang giỏ hàng
- Bảng danh sách sản phẩm (tên, giá, size, số lượng, thành tiền)
- Có thể sửa số lượng
- Nút xóa sản phẩm
- Hiển thị tổng tiền
- Nút "Tiếp tục mua sắm"
- Nút "Thanh toán"

### 7. Trang thanh toán (Checkout)
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

### 8. Trang danh sách yêu thích
- Hiển thị danh sách sản phẩm yêu thích dạng grid
- Mỗi sản phẩm: hình ảnh, tên, giá, nút "Chi tiết", nút "Xóa"

### 9. Trang Admin - Dashboard
- Sidebar menu với các mục:
  - Dashboard (thống kê)
  - Sản phẩm
  - Danh mục
  - Đơn hàng
  - Khách hàng
  - Liên hệ
  - Đăng xuất
- Hiển thị thống kê:
  - Tổng đơn hàng
  - Tổng khách hàng
  - Tổng doanh thu
  - Số sản phẩm trong kho
- Bảng doanh thu theo tháng