## 🧪 Testing với Postman

### Chuẩn Bị
1. **Cài đặt Postman** (nếu chưa có)
2. **Tạo Collection mới:** Pizza Online API
3. **Đặt Base URL:** `http://localhost/web-php-ban-pizza/public/`

### Lưu Ý Quan Trọng

#### 1. ⚠️ Session Cookies
- Postman **TỰ ĐỘNG** lưu cookies từ response
- Đảm bảo **"Automatically follow redirects"** được bật
- Cần login trước khi test API có yêu cầu authentication

#### 2. ⚠️ Content-Type
- **Hầu hết API** dùng `form-data` (KHÔNG phải JSON)
- Chọn **Body → form-data** khi test POST
- **KHÔNG dùng** "raw" JSON trừ khi được nói

#### 3. ⚠️ CSRF Token
- API này **KHÔNG có CSRF protection**
- Có thể test trực tiếp mà không cần token

---

## 📋 Ví Dụ Test Từng API

### 1. Đăng Nhập

**Request:**
```
POST /index.php?action=auth&method=login

Body (form-data):
  - email: admin@example.com
  - password: password123
```

**Postman Setup:**
1. Method: POST
2. URL: `{{baseUrl}}index.php?action=auth&method=login`
3. Tab Body → form-data
4. Key: `email`, Value: `admin@example.com`
5. Key: `password`, Value: `password123`
6. Gửi (Send)
7. ✅ Response sẽ có redirect hoặc success message

---

### 2. Thêm Vào Giỏ Hàng

**Request:**
```
POST /index.php?action=cart&method=add

Body (form-data):
  - product_id: 1
  - quantity: 2
  - size: Vừa
  - price: 120000
```

**Postman Setup:**
1. Method: POST
2. URL: `{{baseUrl}}index.php?action=cart&method=add`
3. Tab Body → form-data
4. Điền dữ liệu:
   - product_id: 1
   - quantity: 2
   - size: Vừa
   - price: 120000
5. Gửi
6. ✅ Response: `{"success": true, "message": "..."}`

---

### 3. Xem Giỏ Hàng

**Request:**
```
GET /index.php?action=cart&method=view
```

**Postman Setup:**
1. Method: GET
2. URL: `{{baseUrl}}index.php?action=cart&method=view`
3. Gửi
4. ✅ Response: HTML page (hoặc redirect nếu chưa login)

---

### 4. Thay Đổi Size

**Request:**
```
POST /index.php?action=cart&method=changeSize

Body (form-data):
  - cart_key: 1_Vừa
  - new_size: Lớn
  - new_price: 170000
```

**Postman Setup:**
1. Method: POST
2. URL: `{{baseUrl}}index.php?action=cart&method=changeSize`
3. Tab Body → form-data
4. Điền:
   - cart_key: 1_Vừa
   - new_size: Lớn
   - new_price: 170000
5. Gửi
6. ✅ Response: `{"success": true, "message": "Đã cập nhật size"}`

---

### 5. Thanh Toán

**Request:**
```
POST /index.php?action=order&method=checkout

Body (form-data):
  - phuong_thuc_thanh_toan: Trực tiếp
  - ten_nguoi_dung: John Doe
  - so_dien_thoai_user: 0123456789
  - dia_chi: 123 Đường ABC, TP HCM
```

**Postman Setup:**
1. Method: POST
2. URL: `{{baseUrl}}index.php?action=order&method=checkout`
3. Tab Body → form-data
4. Điền dữ liệu
5. **Lưu ý:** Phải login trước + phải chọn sản phẩm trong giỏ
6. Gửi
7. ✅ Response: Redirect đến success page

---

### 6. Thêm Vào Yêu Thích

**Request:**
```
POST /index.php?action=wishlist&method=add

Body (form-data):
  - product_id: 1
```

**Postman Setup:**
1. Method: POST
2. URL: `{{baseUrl}}index.php?action=wishlist&method=add`
3. Tab Body → form-data
4. Key: `product_id`, Value: `1`
5. Gửi
6. ✅ Response: `{"success": true, ...}`

---

## 🎯 Quy Trình Test Hoàn Chỉnh

### Workflow 1: Khách Hàng Mua Hàng
```
1. Đăng nhập
   POST /auth&method=login
   
2. Thêm sản phẩm vào giỏ
   POST /cart&method=add (product_id=1, qty=1, price=100000)
   POST /cart&method=add (product_id=2, qty=2, price=120000)
   
3. Xem giỏ hàng
   GET /cart&method=view
   
4. Thay đổi size
   POST /cart&method=changeSize (cart_key=1_Vừa, new_size=Lớn, new_price=150000)
   
5. Cập nhật số lượng
   POST /cart&method=update (cart_key=1_Lớn, quantity=3)
   
6. Thanh toán
   POST /order&method=checkout (payment method + address)
   
7. Xem lịch sử
   GET /order&method=history
```

### Workflow 2: Admin Quản Lý
```
1. Đăng nhập admin
   POST /auth&method=login (admin email)
   
2. Xem dashboard
   GET /admin&method=dashboard
   
3. Xem danh sách sản phẩm
   GET /admin&method=products
   
4. Thêm sản phẩm
   POST /admin&method=addProduct
   (multipart/form-data với file upload)
   
5. Cập nhật trạng thái đơn
   POST /admin&method=updateOrderStatus&id=1 (status=Đã xác nhận)
```

---

## ✅ Checklist Test

- [ ] **Đăng nhập/Đăng ký** - Test auth flow
- [ ] **Thêm/Xóa/Update giỏ** - Test cart operations
- [ ] **Thay size + giá** - Test changeSize
- [ ] **Thanh toán** - Test checkout flow
- [ ] **Yêu thích** - Test wishlist add/remove
- [ ] **Admin** - Test admin operations (nếu có quyền)
- [ ] **Cookies** - Kiểm tra session persist
- [ ] **Error cases** - Test validation errors

---

## 🐛 Troubleshooting

| Vấn đề | Nguyên Nhân | Giải Pháp |
|--------|-----------|----------|
| **405 Method Not Allowed** | Sai method (GET vs POST) | Kiểm tra method đúng |
| **Session lost** | Cookies không được gửi | Bật "Automatically follow redirects" |
| **401 Unauthorized** | Chưa login hoặc session hết | Login lại |
| **400 Bad Request** | Thiếu parameter hoặc sai format | Kiểm tra form-data |
| **Redirect loop** | Middleware blocking | Kiểm tra .htaccess |

---

## 🔧 Postman Environment Variables

Tạo file `.postman_environment.json`:
```json
{
  "name": "Pizza Online Dev",
  "values": [
    {
      "key": "baseUrl",
      "value": "http://localhost/web-php-ban-pizza/public/"
    },
    {
      "key": "product_id",
      "value": "1"
    },
    {
      "key": "admin_email",
      "value": "admin@example.com"
    },
    {
      "key": "admin_password",
      "value": "password123"
    }
  ]
}
```

Sử dụng trong URL: `{{baseUrl}}` hoặc `{{admin_email}}`

---

## 📌 Ghi Chú Quan Trọng

1. ✅ **API có thể test bằng Postman được**
2. ⚠️ **Phải dùng form-data, không phải JSON**
3. ⚠️ **Phải enable cookies/session**
4. ⚠️ **Một số API yêu cầu login trước**
5. ✅ **Không cần CSRF token**
6. ✅ **Response là JSON hoặc HTML**
