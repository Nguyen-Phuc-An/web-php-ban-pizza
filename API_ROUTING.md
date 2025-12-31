// API & Routing Documentation

## Router System

Pizza Online sử dụng query string based router với cấu trúc:
```
index.php?action=[controller]&method=[method]&[params]
```

---

## Routing Map

### HOME & PUBLIC PAGES

#### Trang Chủ
```
GET /index.php?action=home
```
Hiển thị danh sách sản phẩm (mặc định 12 sản phẩm)

#### Sản Phẩm
```
GET /index.php?action=product
GET /index.php?action=product&page=2
GET /index.php?action=product&category=1
GET /index.php?action=product&category=1&page=2
GET /index.php?action=product&method=detail&id=5
GET /index.php?action=product&method=search?q=margherita
```

#### Liên Hệ
```
GET /index.php?action=contact
POST /index.php?action=contact
```

---

## AUTHENTICATION

#### Đăng Nhập
```
GET /index.php?action=auth&method=login
POST /index.php?action=auth&method=login

POST data:
  - email: string
  - password: string
```

#### Đăng Ký
```
GET /index.php?action=auth&method=register
POST /index.php?action=auth&method=register

POST data:
  - name: string
  - email: string
  - password: string
```

#### Đăng Xuất
```
GET /index.php?action=auth&method=logout
```

---

## SHOPPING

#### Giỏ Hàng - View
```
GET /index.php?action=cart&method=view
```
Hiển thị giỏ hàng từ session

#### Giỏ Hàng - Thêm
```
POST /index.php?action=cart&method=add

POST data:
  - product_id: int (required)
  - quantity: int (default: 1)
  - size: string (Nhỏ|Vừa|Lớn) (default: Vừa)

Response: JSON
  {
    "success": true/false,
    "message": string,
    "error": string
  }
```

#### Giỏ Hàng - Xóa
```
POST /index.php?action=cart&method=remove

POST data:
  - cart_key: string (required)

Response: JSON
```

#### Giỏ Hàng - Update
```
POST /index.php?action=cart&method=update

POST data:
  - cart_key: string (required)
  - quantity: int (required)

Response: JSON
```

#### Thanh Toán
```
GET /index.php?action=order&method=checkout
POST /index.php?action=order&method=checkout

POST data:
  - payment_method: string (Trực tiếp|Chuyển khoản)
```

#### Lịch Sử Đơn Hàng
```
GET /index.php?action=order&method=history
GET /index.php?action=order&method=history?page=2
```

#### Chi Tiết Đơn Hàng
```
GET /index.php?action=order&method=detail&id=5
```

---

## USER ACCOUNT

#### Hồ Sơ Cá Nhân
```
GET /index.php?action=profile&method=view
POST /index.php?action=profile&method=view

POST data:
  - name: string
  - phone: string
  - address: string
```

#### Danh Sách Yêu Thích
```
GET /index.php?action=wishlist&method=view
```

#### Thêm Vào Yêu Thích
```
POST /index.php?action=wishlist&method=add

POST data:
  - product_id: int (required)

Response: JSON
```

#### Xóa Khỏi Yêu Thích
```
POST /index.php?action=wishlist&method=remove

POST data:
  - product_id: int (required)

Response: JSON
```

---

## ADMIN PANEL

### Yêu Cầu
- User phải đăng nhập
- User phải có role: 'admin' hoặc 'staff'

#### Dashboard
```
GET /index.php?action=admin&method=dashboard
```
Thống kê: tổng đơn hàng, khách hàng, doanh thu, doanh thu tháng

#### Quản Lý Sản Phẩm

##### Danh Sách
```
GET /index.php?action=admin&method=products
GET /index.php?action=admin&method=products?page=2
```

##### Thêm Mới
```
GET /index.php?action=admin&method=addProduct
POST /index.php?action=admin&method=addProduct

POST data (multipart/form-data):
  - name: string (required)
  - description: string
  - price: float (required)
  - category: int (required)
  - image: file (required)
```

##### Sửa
```
GET /index.php?action=admin&method=editProduct&id=5
POST /index.php?action=admin&method=editProduct&id=5

POST data (multipart/form-data):
  - name: string (required)
  - description: string
  - price: float (required)
  - category: int (required)
  - image: file (optional)
```

##### Xóa
```
GET /index.php?action=admin&method=deleteProduct&id=5
```

#### Quản Lý Danh Mục
```
GET /index.php?action=admin&method=categories
POST /index.php?action=admin&method=categories

POST data:
  - action: "add"
  - name: string (required)
  - description: string
```

#### Quản Lý Đơn Hàng

##### Danh Sách
```
GET /index.php?action=admin&method=orders
GET /index.php?action=admin&method=orders?page=2
```

##### Cập Nhật Trạng Thái
```
POST /index.php?action=admin&method=updateOrderStatus&id=5

POST data:
  - status: string
    (Chờ xác nhận|Đã xác nhận|Đang giao|Đã giao|Đã hủy)
```

#### Quản Lý Khách Hàng

##### Danh Sách
```
GET /index.php?action=admin&method=customers
GET /index.php?action=admin&method=customers?page=2
```

##### Chi Tiết Khách Hàng
```
GET /index.php?action=admin&method=customerDetail&id=5
GET /index.php?action=admin&method=customerDetail&id=5&page=2
```

#### Quản Lý Liên Hệ

##### Danh Sách
```
GET /index.php?action=admin&method=contacts
GET /index.php?action=admin&method=contacts?page=2
```

##### Chi Tiết
```
GET /index.php?action=admin&method=contactDetail&id=5
```

---

## Session Variables

Sau khi đăng nhập, các biến session được set:
```php
$_SESSION['user_id']        // int
$_SESSION['ten_nguoi_dung'] // string
$_SESSION['email_user']     // string
$_SESSION['loai_user']      // 'customer'|'admin'|'staff'
```

---

## Error Responses

### JSON Responses (API)
```json
{
  "success": false,
  "error": "Error message"
}

{
  "success": true,
  "message": "Success message"
}
```

### HTTP Status Codes
- 200: OK
- 400: Bad Request
- 401: Unauthorized
- 500: Server Error

---

## Data Validation

### Product Prices
- Kiểu: decimal(10,2)
- Min: 0
- Là bắt buộc

### Order Status
- 'Chờ xác nhận'
- 'Đã xác nhận'
- 'Đang giao'
- 'Đã giao'
- 'Đã hủy'

### User Roles
- 'customer'
- 'admin'
- 'staff'

### Pizza Sizes
- 'Nhỏ'
- 'Vừa'
- 'Lớn'

---

## Rate Limiting

Không có rate limiting hiện tại. Nên thêm nếu cần.

---

## CORS

Không áp dụng (chỉ là form POST truyền thống)

---

## Pagination

- Trang mặc định: 1
- Items per page (khách): 12
- Items per page (admin): 10

### Example
```
GET /index.php?action=product&page=1
→ Sản phẩm 1-12

GET /index.php?action=product&page=2
→ Sản phẩm 13-24
```

---

## Sorting

Sắp xếp mặc định:
- Sản phẩm: Ngày tạo DESC
- Đơn hàng: Ngày tạo DESC
- Khách hàng: Ngày tạo DESC
