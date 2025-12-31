<?php
$page_title = 'Liên hệ';
include APP_PATH . 'Views/layout/header.php';
?>

<div class="container">
    <div class="contact-container">
        <!-- Contact Information -->
        <div class="contact-info-section">
            <h2>Thông tin liên hệ</h2>
            <div class="contact-info">
                <div class="info-item">
                    <h3>Địa chỉ</h3>
                    <p>123 Đường Pizza, Quận 1, Thành phố Hồ Chí Minh</p>
                </div>
                <div class="info-item">
                    <h3>Số điện thoại</h3>
                    <p>0123 456 789</p>
                </div>
                <div class="info-item">
                    <h3>Email</h3>
                    <p>contact@pizzaonline.com</p>
                </div>
                <div class="info-item">
                    <h3>Giờ hoạt động</h3>
                    <p>Thứ Hai - Chủ Nhật: 10:00 - 22:00</p>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form-section">
            <h2>Gửi liên hệ cho chúng tôi</h2>
            <form method="POST" action="<?php echo SITE_URL; ?>index.php?action=contact" class="contact-form">
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
                    <textarea id="noi_dung_contact" name="noi_dung_contact" rows="6" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-large">
                    Gửi liên hệ
                </button>
            </form>
        </div>
    </div>
</div>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
