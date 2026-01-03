<?php
$page_title = 'Thanh toán';
include APP_PATH . 'Views/layout/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Thanh toán đơn hàng</h1>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
        
        <!-- LEFT: Order Details & Delivery -->
        <div>
            <!-- Order Information -->
            <div class="checkout-section" style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e0e0e0;">
                <h2 style="margin-top: 0;">📦 Thông tin đơn hàng</h2>
                <div style="max-height: 300px; overflow-y: auto;">
                    <?php $subtotal = 0; ?>
                    <?php if (!empty($cartItems)): ?>
                        <?php foreach ($cartItems as $item): ?>
                            <div style="display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid #f0f0f0;">
                                <img src="<?php echo SITE_URL; ?>uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                <div style="flex: 1;">
                                    <p style="margin: 0 0 4px 0; font-weight: 600; font-size: 14px;">
                                        <?php echo htmlspecialchars($item['product_name']); ?>
                                    </p>
                                    <p style="margin: 0 0 4px 0; font-size: 12px; color: #666;">
                                        Size: <strong><?php echo htmlspecialchars($item['size']); ?></strong>
                                    </p>
                                    <p style="margin: 0; font-size: 12px; color: #666;">
                                        <?php echo $item['quantity']; ?> × <?php echo number_format($item['price'], 0, ',', '.'); ?> đ
                                    </p>
                                </div>
                                <div style="text-align: right;">
                                    <p style="margin: 0; font-weight: 600; color: var(--primary-color);">
                                        <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> đ
                                    </p>
                                </div>
                            </div>
                            <?php $subtotal += $item['price'] * $item['quantity']; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Payment & Delivery Form -->
            <form method="POST" class="checkout-section" style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e0e0e0;">
                <input type="hidden" id="transferContentInput" name="transfer_content" value="">
                
                <!-- Payment Method -->
                <h3 style="margin-top: 0; margin-bottom: 15px;">💳 Phương thức thanh toán</h3>
                <div style="margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; margin-bottom: 12px; padding: 12px; border: 2px solid #ddd; border-radius: 6px; cursor: pointer; transition: border-color 0.3s;">
                        <input type="radio" name="phuong_thuc_thanh_toan" value="Trực tiếp" required checked 
                               style="width: 18px; height: 18px; margin-right: 12px; cursor: pointer;">
                        <div>
                            <strong>Thanh toán khi nhận hàng (COD)</strong><br>
                            <small style="color: #666;">Thanh toán tiền mặt khi nhận đơn hàng</small>
                        </div>
                    </label>
                    
                    <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #ddd; border-radius: 6px; cursor: pointer; transition: border-color 0.3s;">
                        <input type="radio" name="phuong_thuc_thanh_toan" value="Chuyển khoản"
                               style="width: 18px; height: 18px; margin-right: 12px; cursor: pointer;">
                        <div>
                            <strong>Chuyển khoản ngân hàng</strong><br>
                            <small style="color: #666;">Chuyển tiền trước khi giao hàng</small>
                        </div>
                    </label>
                </div>

                <!-- Delivery Information -->
                <h3 style="margin-bottom: 15px;">📍 Thông tin giao hàng</h3>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 500;">Tên người nhận:</label>
                    <input type="text" name="ten_nguoi_dung" 
                           value="<?php echo isset($user) ? htmlspecialchars($user['ten_nguoi_dung'] ?? '') : ''; ?>" 
                           placeholder="Nhập tên người nhận"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;" required>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 500;">Số điện thoại:</label>
                    <input type="tel" name="so_dien_thoai_user" 
                           value="<?php echo isset($user) ? htmlspecialchars($user['so_dien_thoai_user'] ?? '') : ''; ?>"
                           placeholder="Nhập số điện thoại"
                           pattern="[0-9]{10,11}"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;" required>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 500;">Địa chỉ giao hàng:</label>
                    <textarea name="dia_chi" 
                              placeholder="Nhập địa chỉ giao hàng đầy đủ"
                              style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: vertical; min-height: 80px;" 
                              required><?php echo isset($user) ? htmlspecialchars($user['dia_chi'] ?? '') : ''; ?></textarea>
                </div>

                <div style="display: flex; gap: 12px;">
                    <a href="<?php echo SITE_URL; ?>index.php?action=cart&method=view" class="btn btn-secondary" style="flex: 1; text-align: center; text-decoration: none;">
                        ← Quay lại giỏ hàng
                    </a>
                    <button type="submit" class="btn btn-primary" style="flex: 1; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);">
                        💳 Đặt hàng ngay
                    </button>
                </div>
            </form>
        </div>

        <!-- RIGHT: Order Summary -->
        <div style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); color: white; padding: 20px; border-radius: 8px; height: fit-content; position: sticky; top: 100px;">
            <h3 style="margin-top: 0; margin-bottom: 20px;">📊 Tóm tắt đơn hàng</h3>
            
            <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.2);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                    <span>Tổng tiền hàng:</span>
                    <strong><?php echo number_format($subtotal, 0, ',', '.'); ?> đ</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                    <span>Phí vận chuyển:</span>
                    <strong>30,000 đ</strong>
                </div>
            </div>
            
            <div style="margin-bottom: 25px;">
                <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 700;">
                    <span>Tổng thanh toán:</span>
                    <strong><?php echo number_format($subtotal + 30000, 0, ',', '.'); ?> đ</strong>
                </div>
            </div>

            <div style="background: rgba(255,255,255,0.1); padding: 12px; border-radius: 6px; font-size: 12px; line-height: 1.5;">
                <p style="margin: 0 0 8px 0;"><strong>ℹ️ Lưu ý:</strong></p>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Kiểm tra thông tin giao hàng trước khi đặt hàng</li>
                    <li>Phí vận chuyển cố định 30,000 đ cho tất cả đơn hàng</li>
                    <li>Thời gian giao hàng: 2-3 ngày làm việc</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>

<script>
// Update transfer content on form submit
document.querySelector('form').addEventListener('submit', function(e) {
    const bankMethod = document.querySelector('input[value="Chuyển khoản"]').checked;
    if (bankMethod) {
        const transferContent = 'DONHANG_' + new Date().toLocaleDateString('vi-VN').split('/').reverse().join('') + '_0000';
        document.getElementById('transferContentInput').value = transferContent;
    }
});
</script>
