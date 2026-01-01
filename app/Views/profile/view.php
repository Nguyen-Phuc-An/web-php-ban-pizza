<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>👤 Hồ Sơ Cá Nhân</h1>
        <p>Quản lý thông tin cá nhân của bạn</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 25px; margin-top: 30px;">
        <!-- LEFT: Profile Info Card -->
        <div style="background: white; padding: 25px; border-radius: 12px; border: 1px solid #e0e0e0; height: fit-content; box-shadow: 0 2px 8px rgba(0,0,51,0.05);">
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="width: 80px; height: 80px; margin: 0 auto 15px; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 32px;">
                    👤
                </div>
                <h3 style="margin: 0 0 8px 0; color: var(--text-dark);"><?php echo htmlspecialchars($user['ten_nguoi_dung']); ?></h3>
                <p style="margin: 0; font-size: 13px; color: #666;"><?php echo htmlspecialchars($user['email_user']); ?></p>
            </div>
            <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 20px 0;">
            <div style="font-size: 13px; color: #666; line-height: 1.8;">
                <div style="margin-bottom: 12px;">
                    <strong style="color: var(--text-dark);">📱 Điện thoại:</strong><br>
                    <?php echo htmlspecialchars($user['so_dien_thoai_user'] ?? 'Chưa cập nhật'); ?>
                </div>
                <div>
                    <strong style="color: var(--text-dark);">📍 Địa chỉ:</strong><br>
                    <?php echo htmlspecialchars($user['dia_chi'] ?? 'Chưa cập nhật'); ?>
                </div>
            </div>
            <div style="margin-top: 20px;">
                <a href="<?php echo SITE_URL; ?>index.php?action=order&method=history" class="btn btn-primary" style="display: block; text-align: center; text-decoration: none; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); color: white; padding: 12px; border-radius: 6px; font-weight: 600;">
                    📦 Xem lịch sử đơn hàng
                </a>
            </div>
        </div>

        <!-- RIGHT: Edit Form -->
        <div style="background: white; padding: 25px; border-radius: 12px; border: 1px solid #e0e0e0; box-shadow: 0 2px 8px rgba(0,0,51,0.05);">
            <h3 style="margin-top: 0; margin-bottom: 20px; color: var(--text-dark); font-size: 18px;">✏️ Chỉnh sửa thông tin</h3>
            
            <form method="POST">
                <div style="margin-bottom: 18px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: var(--text-dark);">Tên người dùng:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['ten_nguoi_dung']); ?>" required 
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; transition: border-color 0.3s;" 
                           onFocus="this.style.borderColor='var(--primary-color)'" onBlur="this.style.borderColor='#ddd'">
                </div>
                
                <div style="margin-bottom: 18px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: var(--text-dark);">Email:</label>
                    <input type="email" id="email" value="<?php echo htmlspecialchars($user['email_user']); ?>" disabled 
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: #f5f5f5; color: #999;">
                    <small style="color: #999; display: block; margin-top: 4px;">Không thể thay đổi email</small>
                </div>
                
                <div style="margin-bottom: 18px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: var(--text-dark);">Số điện thoại:</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['so_dien_thoai_user'] ?? ''); ?>" placeholder="Nhập số điện thoại" 
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; transition: border-color 0.3s;" 
                           onFocus="this.style.borderColor='var(--primary-color)'" onBlur="this.style.borderColor='#ddd'">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: var(--text-dark);">Địa chỉ giao hàng:</label>
                    <textarea id="address" name="address" placeholder="Nhập địa chỉ đầy đủ" 
                              style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: vertical; min-height: 80px; transition: border-color 0.3s;" 
                              onFocus="this.style.borderColor='var(--primary-color)'" onBlur="this.style.borderColor='#ddd'"><?php echo htmlspecialchars($user['dia_chi'] ?? ''); ?></textarea>
                </div>
                
                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); border: none; color: white; padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: transform 0.2s;" onMouseOver="this.style.transform='translateY(-2px)'" onMouseOut="this.style.transform='translateY(0)'">💾 Lưu thay đổi</button>
                    <a href="<?php echo SITE_URL; ?>index.php?action=home" class="btn btn-secondary" style="flex: 1; background: #f0f0f0; border: none; color: var(--text-dark); padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; text-align: center; transition: transform 0.2s;" onMouseOver="this.style.transform='translateY(-2px)'" onMouseOut="this.style.transform='translateY(0)'">✕ Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
