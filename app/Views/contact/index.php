<?php
$page_title = 'Liên hệ';
include APP_PATH . 'Views/layout/header.php';
?>

<div class="container">
    <div class="contact-container">
        <!-- Left Side: Contact Information -->
        <div class="contact-left">
            <div class="contact-left-content">
                <h2>Liên hệ với chúng tôi</h2>
                <div class="contact-info" style="margin-top: var(--spacing-lg);">
                    <div class="info-item" style="margin-bottom: var(--spacing-md);">
                        <h3 style="color: white; margin: 0 0 8px 0; font-size: 16px;">📍 Địa chỉ</h3>
                        <p style="margin: 0; color: rgba(255, 255, 255, 0.9); font-size: 14px;">123 Đường Pizza, xã Mỳ Ý, tỉnh Tráng Miệng</p>
                    </div>
                    <div class="info-item" style="margin-bottom: var(--spacing-md);">
                        <h3 style="color: white; margin: 0 0 8px 0; font-size: 16px;">☎️ Số điện thoại</h3>
                        <p style="margin: 0; color: rgba(255, 255, 255, 0.9); font-size: 14px;">0363 547 545</p>
                    </div>
                    <div class="info-item" style="margin-bottom: var(--spacing-md);">
                        <h3 style="color: white; margin: 0 0 8px 0; font-size: 16px;">✉️ Email</h3>
                        <p style="margin: 0; color: rgba(255, 255, 255, 0.9); font-size: 14px;">anphuc1203@gmail.com</p>
                    </div>
                    <div class="info-item">
                        <h3 style="color: white; margin: 0 0 8px 0; font-size: 16px;">⏰ Giờ hoạt động</h3>
                        <p style="margin: 0; color: rgba(255, 255, 255, 0.9); font-size: 14px;">Thứ Hai - Chủ Nhật: 10:00 - 22:00</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Contact Form -->
        <div class="contact-right">
            <div class="contact-form-wrapper">
                <h2>Gửi liên hệ cho chúng tôi</h2>
                
                <form method="POST" action="<?php echo SITE_URL; ?>index.php?action=contact" class="auth-form">
                    <div class="form-group">
                        <label for="ten_contact">Tên của bạn:</label>
                        <input type="text" id="ten_contact" name="ten_contact" required>
                    </div>

                    <div class="form-group">
                        <label for="email_contact">Email:</label>
                        <input type="email" id="email_contact" name="email_contact" required>
                    </div>

                    <div class="form-group">
                        <label for="so_dien_thoai_contact">Số điện thoại:</label>
                        <input type="tel" id="so_dien_thoai_contact" name="so_dien_thoai_contact" 
                               pattern="[0-9]{10}" placeholder="0123456789" required>
                    </div>

                    <div class="form-group">
                        <label for="noi_dung_contact">Nội dung:</label>
                        <textarea id="noi_dung_contact" name="noi_dung_contact" rows="4" required 
                                  style="padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; font-family: inherit;"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Gửi liên hệ</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
