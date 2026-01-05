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
                <h2 style="margin: 0;">Đăng Ký</h2>
                
                <form method="POST" class="auth-form" style="gap: 0;" id="registerForm">
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
                    
                    <div class="form-group">
                        <label for="confirm_password">Xác nhận mật khẩu:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <span id="passwordError" style="color: #dc3545; font-size: 12px; display: none;">Mật khẩu không trùng khớp</span>
                        <span id="passwordSuccess" style="color: #28a745; font-size: 12px; display: none;">Mật khẩu trùng khớp</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Số điện thoại:</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Địa chỉ:</label>
                        <input type="text" id="address" name="address" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" id="submitBtn">Đăng Ký</button>
                </form>
                
                <p class="auth-link">Đã có tài khoản? <a href="<?php echo SITE_URL; ?>index.php?action=auth&method=login">Đăng nhập</a></p>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const passwordError = document.getElementById('passwordError');
    const passwordSuccess = document.getElementById('passwordSuccess');
    const submitBtn = document.getElementById('submitBtn');
    const registerForm = document.getElementById('registerForm');
    
    // Kiểm tra mật khẩu mỗi khi nhập
    confirmPasswordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (confirmPassword === '') {
            passwordError.style.display = 'none';
            passwordSuccess.style.display = 'none';
            submitBtn.disabled = false;
        } else if (password === confirmPassword) {
            passwordError.style.display = 'none';
            passwordSuccess.style.display = 'inline';
            submitBtn.disabled = false;
        } else {
            passwordError.style.display = 'inline';
            passwordSuccess.style.display = 'none';
            submitBtn.disabled = true;
        }
    });
    
    // Xác thực khi submit form
    registerForm.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            passwordError.style.display = 'inline';
            passwordSuccess.style.display = 'none';
        }
    });
});
</script>
