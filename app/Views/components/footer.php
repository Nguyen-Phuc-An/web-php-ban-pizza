<footer class="footer">
    <div class="container">
        <div class="footer-content">

            <!-- Thông tin website / đồ án -->
            <div class="footer-section">
                <h3>🍕 Pizza A.D.A</h3>
                <p>
                    Website bán pizza trực tuyến được xây dựng phục vụ mục đích học tập
                    trong khuôn khổ đồ án môn <strong>Phát triển ứng dụng web với mã nguồn mở</strong>.
                </p>
                <p>
                    Ứng dụng cho phép người dùng xem sản phẩm, đặt hàng trực tuyến
                    và quản lý đơn hàng một cách thuận tiện.
                </p>
            </div>

            <!-- Thông tin nhóm thực hiện -->
            <div class="footer-section">
                <h3>👨‍💻 Nhóm Thực Hiện</h3>
                <ul style="list-style: none; padding-left: 0; margin-left: 20px;">
                    <li>• Nguyễn Phúc An</li>
                    <li>• Nguyễn Thiên Ân</li>
                    <li>• Hứa Khánh Đăng</li>
                </ul>
                <p style="margin-top: 10px;">
                    <strong>Môn học:</strong><br>
                    Phát triển ứng dụng web với mã nguồn mở
                </p>
            </div>

            <!-- Liên kết nhanh -->
            <div class="footer-section">
                <h3>🔗 Liên Kết Nhanh</h3>
                <ul style="list-style: none; padding-left: 0; margin-left: 20px;">
                    <li><a href="<?php echo SITE_URL; ?>index.php?action=home&method=index">Trang chủ</a></li>
                    <li><a href="<?php echo SITE_URL; ?>index.php?action=product&method=index">Sản phẩm</a></li>
                    <li><a href="<?php echo SITE_URL; ?>index.php?action=home&method=about">Giới thiệu</a></li>
                    <li><a href="<?php echo SITE_URL; ?>index.php?action=contact&method=index">Liên hệ</a></li>
                    <li><a href="<?php echo SITE_URL; ?>index.php?action=cart&method=view">Giỏ hàng</a></li>
                    <?php if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])): ?>
                        <li><a href="<?php echo SITE_URL; ?>index.php?action=auth&method=login">Đăng nhập</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Công nghệ sử dụng -->
            <div class="footer-section">
                <h3>⚙️ Công Nghệ</h3>
                <ul style="list-style: none; padding-left: 0; margin-left: 20px;">
                    <li>• PHP (MVC)</li>
                    <li>• MySQL / MariaDB</li>
                    <li>• HTML, CSS, JavaScript</li>
                    <li>• XAMPP</li>
                </ul>
                <p style="margin-top: 10px;">
                    Mục tiêu: Rèn luyện kỹ năng phân tích, thiết kế và phát triển web.
                </p>
            </div>

        </div>

        <!-- Đường phân cách -->
        <div style="border-top: 1px solid rgba(255,255,255,0.2); margin: 10px 0;"></div>

        <!-- Footer bottom -->
        <div class="footer-bottom">
            <div style="text-align: center;">
                <p>
                    &copy; 2025 <strong>Pizza A.D.A</strong> – Đồ án học phần
                </p>
                <p style="font-size: 12px; opacity: 0.8; margin-top: 8px;">
                    Website phục vụ mục đích học tập, không dùng cho mục đích thương mại
                </p>
            </div>
        </div>
    </div>
</footer>
