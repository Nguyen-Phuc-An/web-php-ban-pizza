<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <!-- Thông tin công ty -->
            <div class="footer-section">
                <h3>🍕 Pizza Online</h3>
                <p>Hệ thống bán pizza trực tuyến hàng đầu với các loại pizza chất lượng cao, nguyên liệu tươi ngon, giao hàng nhanh chóng.</p>
                <p><strong>Giờ hoạt động:</strong><br>Thứ 2 - Chủ Nhật: 10:00 - 22:00</p>
            </div>
            
            <!-- Thông tin liên hệ -->
            <div class="footer-section">
                <h3>📞 Liên Hệ</h3>
                <ul style="list-style: none;">
                    <li>
                        <strong>Điện thoại:</strong><br>
                        <a href="tel:0123456789">0123 456 789</a>
                    </li>
                    <li style="margin-top: 10px;">
                        <strong>Email:</strong><br>
                        <a href="mailto:contact@pizzaonline.com">contact@pizzaonline.com</a>
                    </li>
                    <li style="margin-top: 10px;">
                        <strong>Địa chỉ:</strong><br>
                        123 Đường Pizza, Quận 1, TP. Hồ Chí Minh
                    </li>
                </ul>
            </div>
            
            <!-- Liên kết nhanh -->
            <div class="footer-section">
                <h3>🔗 Liên Kết Nhanh</h3>
                <ul style="list-style: none;">
                    <li><a href="<?php echo SITE_URL; ?>index.php?action=home&method=index">🏠 Trang chủ</a></li>
                    <li><a href="<?php echo SITE_URL; ?>index.php?action=product&method=index">🍕 Sản phẩm</a></li>
                    <li><a href="<?php echo SITE_URL; ?>index.php?action=home&method=about">ℹ️ Giới thiệu</a></li>
                    <li><a href="<?php echo SITE_URL; ?>index.php?action=contact&method=index">📧 Liên hệ</a></li>
                    <?php if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])): ?>
                        <li><a href="<?php echo SITE_URL; ?>index.php?action=auth&method=login">🔐 Đăng nhập</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <!-- Hỗ trợ khách hàng -->
            <div class="footer-section">
                <h3>❓ Hỗ Trợ</h3>
                <ul style="list-style: none;">
                    <li><a href="<?php echo SITE_URL; ?>index.php?action=contact&method=index">📞 Gọi chúng tôi</a></li>
                    <li><a href="<?php echo SITE_URL; ?>index.php?action=contact&method=index">💬 Gửi tin nhắn</a></li>
                    <li><a href="<?php echo SITE_URL; ?>index.php?action=home&method=about">📖 Về chúng tôi</a></li>
                    <li><a href="<?php echo SITE_URL; ?>index.php?action=cart&method=index">🛒 Giỏ hàng</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Đường phân cách -->
        <div style="border-top: 1px solid rgba(255,255,255,0.2); margin: 30px 0;"></div>
        
        <!-- Footer bottom -->
        <div class="footer-bottom">
            <div style="text-align: center;">
                <p>&copy; 2025 <strong>Pizza Online</strong>. Tất cả quyền được bảo lưu.</p>
                <p style="font-size: 12px; opacity: 0.8; margin-top: 10px;">
                    Được xây dựng bằng ❤️ với PHP, MySQL và MVC
                </p>
            </div>
        </div>
    </div>
</footer>
