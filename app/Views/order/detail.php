<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div class="container" style="max-width: 900px; margin: 40px auto;">
    <div class="order-detail-section" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h2 style="margin: 0 0 30px 0; font-size: 28px; color: #333; border-bottom: 3px solid var(--primary-color); padding-bottom: 15px;">
            Chi Tiết Đơn Hàng #<?php echo $order['order_id']; ?>
        </h2>
        
        <div class="order-info" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; padding: 20px; background: #f9f9f9; border-radius: 8px;">
            <div style="padding: 15px; background: white; border-radius: 6px; border-left: 4px solid var(--primary-color);">
                <p style="margin: 0; font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Ngày đặt</p>
                <p style="margin: 0; font-size: 16px; font-weight: 600; color: #333;"><?php echo date('d/m/Y H:i', strtotime($order['ngay_tao_order'])); ?></p>
            </div>
            <div style="padding: 15px; background: white; border-radius: 6px; border-left: 4px solid #4CAF50;">
                <p style="margin: 0; font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Trạng thái</p>
                <span class="status" style="display: inline-block; padding: 8px 12px; background: #4CAF50; color: white; border-radius: 4px; font-weight: 600; font-size: 14px;">
                    <?php echo htmlspecialchars($order['trang_thai']); ?>
                </span>
            </div>
            <div style="padding: 15px; background: white; border-radius: 6px; border-left: 4px solid #2196F3;">
                <p style="margin: 0; font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Thanh toán</p>
                <p style="margin: 0; font-size: 16px; font-weight: 600; color: #333;"><?php echo htmlspecialchars($order['phuong_thuc_thanh_toan']); ?></p>
            </div>
        </div>
        
        <h3 style="margin: 30px 0 20px 0; font-size: 20px; color: #333; border-bottom: 2px solid #e0e0e0; padding-bottom: 10px;">
            Danh sách sản phẩm
        </h3>
        <div style="overflow-x: auto; border-radius: 8px; border: 1px solid #e0e0e0;">
            <table class="items-table" style="width: 100%; border-collapse: collapse; background: white;">
                <thead style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); color: white;">
                    <tr>
                        <th style="padding: 15px; text-align: left; font-weight: 600;">Sản phẩm</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600;">Size</th>
                        <th style="padding: 15px; text-align: right; font-weight: 600;">Giá/Cái</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600;">Số lượng</th>
                        <th style="padding: 15px; text-align: right; font-weight: 600;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $subtotalAmount = 0;
                    foreach ($items as $index => $item): 
                        $itemTotal = $item['gia_order_items'] * $item['so_luong_mua'];
                        $subtotalAmount += $itemTotal;
                    ?>
                        <tr style="border-bottom: 1px solid #e0e0e0; background: <?php echo $index % 2 === 0 ? 'white' : '#f9f9f9'; ?>; transition: background 0.3s ease;">
                            <td style="padding: 15px; font-weight: 500; color: #333;"><?php echo htmlspecialchars($item['ten_product']); ?></td>
                            <td style="padding: 15px; text-align: center; color: #666;">
                                <span style="display: inline-block; padding: 5px 10px; background: #f0f0f0; border-radius: 4px; font-size: 12px;">
                                    <?php echo htmlspecialchars($item['size'] ?? 'Không'); ?>
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: right; color: #333; font-weight: 500;"><?php echo number_format($item['gia_order_items'], 0, ',', '.'); ?> đ</td>
                            <td style="padding: 15px; text-align: center; color: #333; font-weight: 500;"><?php echo $item['so_luong_mua']; ?></td>
                            <td style="padding: 15px; text-align: right; color: var(--primary-color); font-weight: 600;">
                                <?php echo number_format($itemTotal, 0, ',', '.'); ?> đ
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="order-summary" style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin: 30px 0; border: 1px solid #e0e0e0;">
            <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
                <div style="width: 100%;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #ddd;">
                        <span style="color: #666; font-size: 14px;">Tổng tiền hàng:</span>
                        <span style="font-weight: 600; color: #333;"><?php echo number_format($subtotalAmount, 0, ',', '.'); ?> đ</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 2px solid #e0e0e0;">
                        <span style="color: #666; font-size: 14px;">Phí vận chuyển:</span>
                        <span style="font-weight: 600; color: #e74c3c;">+ 30,000 đ</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 16px; font-weight: 700; color: #333;">Tổng thanh toán:</span>
                        <span style="font-size: 24px; font-weight: 700; color: var(--primary-color);"><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?> đ</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <?php if ($order['trang_thai'] === 'Chờ xác nhận'): ?>
                <button onclick="cancelOrder(<?php echo $order['order_id']; ?>)" class="btn" style="padding: 12px 25px; background: #f44336; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px;">
                    Hủy đơn hàng
                </button>
            <?php endif; ?>
            <a href="<?php echo SITE_URL; ?>index.php?action=order&method=history" class="btn btn-secondary" style="padding: 12px 25px; background: #f5f5f5; color: #333; text-decoration: none; border-radius: 6px; font-weight: 600; transition: all 0.3s ease; border: 1px solid #ddd; display: inline-flex; align-items: center; gap: 8px;">
                ← Quay lại lịch sử
            </a>
        </div>
    </div>
</div>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>

<script>
function cancelOrder(orderId) {
    if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        return;
    }
    
    fetch('<?php echo SITE_URL; ?>index.php?action=order&method=cancel&id=' + orderId, {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Đơn hàng đã được hủy thành công', 'success');
            setTimeout(() => {
                window.location.href = '<?php echo SITE_URL; ?>index.php?action=order&method=history';
            }, 1500);
        } else {
            showToast(data.error || 'Lỗi: Không thể hủy đơn hàng', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Lỗi kết nối', 'error');
    });
}
</script>
