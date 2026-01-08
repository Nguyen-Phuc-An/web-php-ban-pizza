<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;"></div>

    <div style="background: white; padding: 50px; border-radius: 16px; width: 90%; max-width: 600px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); position: relative; z-index: 1000; animation: slideUp 0.5s ease;">
        <!-- Thành công -->
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #4CAF50, #45a049); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 40px;">
                ✓
            </div>
        </div>        
        <!-- Thông điệp thành công -->
        <h1 style="margin: 0 0 15px 0; text-align: center; color: #333; font-size: 28px;">
            Đặt hàng thành công!
        </h1>        
        <p style="margin: 0 0 30px 0; text-align: center; color: #666; font-size: 16px; line-height: 1.6;">
            Đơn hàng của bạn đã được tạo. Hãy kiểm tra email hoặc liên hệ với chúng tôi để xác nhận.
        </p>        
        <!-- Thông tin đơn hàng -->
        <div style="background: #f9f9f9; padding: 25px; border-radius: 10px; margin-bottom: 25px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div style="padding-bottom: 20px; border-bottom: 1px solid #e0e0e0;">
                    <p style="margin: 0 0 5px 0; color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Mã đơn hàng</p>
                    <p style="margin: 0; font-weight: 700; font-size: 20px; color: var(--primary-color);">#<?php echo $order['order_id']; ?></p>
                </div>
                <div style="padding-bottom: 20px; border-bottom: 1px solid #e0e0e0;">
                    <p style="margin: 0 0 5px 0; color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Ngày đặt</p>
                    <p style="margin: 0; font-weight: 600; font-size: 16px; color: #333;"><?php echo date('d/m/Y H:i', strtotime($order['ngay_tao_order'])); ?></p>
                </div>
            </div>            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <p style="margin: 0 0 5px 0; color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Phương thức thanh toán</p>
                    <p style="margin: 0; font-weight: 600; font-size: 14px; color: #333;"><?php echo htmlspecialchars($order['phuong_thuc_thanh_toan']); ?></p>
                </div>
                <div>
                    <p style="margin: 0 0 5px 0; color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Tổng tiền</p>
                    <p style="margin: 0; font-weight: 700; font-size: 18px; color: var(--primary-color);"><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?> đ</p>
                </div>
            </div>
        </div>        
        <!-- Hướng dẫn thanh toán -->
        <?php if ($order['phuong_thuc_thanh_toan'] === 'Chuyển khoản'): ?>
            <div style="background: #fff3cd; border: 2px solid #ffc107; padding: 20px; border-radius: 10px; margin-bottom: 25px;">
                <h3 style="margin: 0 0 15px 0; color: #856404; font-size: 16px; font-weight: 700;">
                    Thông tin chuyển khoản
                </h3>
                
                <div style="background: white; padding: 15px; border-radius: 6px; margin-bottom: 15px;">
                    <div style="margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e0e0e0;">
                        <p style="margin: 0 0 5px 0; color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Số tài khoản</p>
                        <p style="margin: 0; font-weight: 600; font-size: 14px; color: #333; font-family: monospace;">070119938250</p>
                    </div>
                    <div style="margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e0e0e0;">
                        <p style="margin: 0 0 5px 0; color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Chủ tài khoản</p>
                        <p style="margin: 0; font-weight: 600; font-size: 14px; color: #333;">NGUYEN PHUC AN</p>
                    </div>
                    <div>
                        <p style="margin: 0 0 5px 0; color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Nội dung chuyển khoản</p>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <p id="transferText" style="margin: 0; font-weight: 600; font-size: 14px; color: var(--primary-color); font-family: monospace;">
                                DONHANG_<?php echo date('dmY'); ?>_0000<?php echo sprintf('%04d', $order['order_id']); ?>
                            </p>
                            <button type="button" onclick="copyText()" style="background: var(--primary-color); color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 600;">
                                Copy
                            </button>
                        </div>
                    </div>
                </div>
                
                <p style="margin: 0; color: #856404; font-size: 13px; line-height: 1.5;">
                    <strong>⚠️ Lưu ý:</strong> Sau khi chuyển khoản, vui lòng chụp ảnh hóa đơn và liên hệ với chúng tôi để xác nhận thanh toán. Đơn hàng của bạn sẽ được giao trong vòng khoản từ 15 - 30 phút sau khi xác nhận.
                </p>
            </div>
        <?php endif; ?>
        
        <!-- Action Buttons -->
        <div style="display: flex; gap: 15px;">
            <a href="<?php echo SITE_URL; ?>index.php?action=order&method=detail&id=<?php echo $order['order_id']; ?>" class="btn btn-primary" style="flex: 1; padding: 14px; text-decoration: none; text-align: center; border-radius: 6px; font-weight: 600; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); color: white;">
                Xem chi tiết đơn hàng
            </a>
            <a href="<?php echo SITE_URL; ?>index.php?action=home&method=index" class="btn btn-secondary" style="flex: 1; padding: 14px; text-decoration: none; text-align: center; border-radius: 6px; font-weight: 600; background: #f5f5f5; color: #333; border: 1px solid #ddd;">
                Về trang chủ
            </a>
        </div>
    </div>
</div>
<style>
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
// Sao chép nội dung chuyển khoản vào clipboard
function copyText() {
    const text = document.getElementById('transferText').textContent;
    navigator.clipboard.writeText(text).then(() => {
        showToast('Đã sao chép vào clipboard', 'success');
    }).catch(() => {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showToast('Đã sao chép vào clipboard', 'success');
    });
}
</script>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
