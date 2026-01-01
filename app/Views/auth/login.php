<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div class="container">
    <div class="auth-container">
        <!-- Left Side: Empty/Information -->
        <div class="auth-left">
            <div class="auth-left-content">
                
            </div>
        </div>
        
        <!-- Right Side: Login Form -->
        <div class="auth-right">
            <div class="auth-form-wrapper">
                <h2>Đăng Nhập</h2>
                
                <form method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mật khẩu:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Đăng Nhập</button>
                </form>
                
                <p class="auth-link">Chưa có tài khoản? <a href="<?php echo SITE_URL; ?>index.php?action=auth&method=register">Đăng ký ngay</a></p>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
