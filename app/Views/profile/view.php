<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div class="container">
    <div class="profile-section">
        <h2>Hồ Sơ Cá Nhân</h2>
        
        <form method="POST" class="profile-form">
            <div class="form-group">
                <label for="name">Tên:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['ten_nguoi_dung']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" value="<?php echo htmlspecialchars($user['email_user']); ?>" disabled>
            </div>
            
            <div class="form-group">
                <label for="phone">Điện thoại:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['so_dien_thoai_user'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <textarea id="address" name="address"><?php echo htmlspecialchars($user['dia_chi'] ?? ''); ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
        </form>
    </div>
</div>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
