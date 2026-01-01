<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div class="container">
    <div class="auth-container">
        <!-- Left Side: Empty/Information -->
        <div class="auth-left">
            <div class="auth-left-content">
                
            </div>
        </div>
        
        <!-- Right Side: Register Form -->
        <div class="auth-right">
            <div class="auth-form-wrapper">
                <h2>Đăng Ký</h2>
                
                <form method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="name">Tên:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mật khẩu:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Đăng Ký</button>
                </form>
                
                <p class="auth-link">Đã có tài khoản? <a href="<?php echo SITE_URL; ?>index.php?action=auth&method=login">Đăng nhập</a></p>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
