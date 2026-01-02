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

        <!-- RIGHT: Action Buttons -->
        <div style="background: white; padding: 25px; border-radius: 12px; border: 1px solid #e0e0e0; height: fit-content; box-shadow: 0 2px 8px rgba(0,0,51,0.05);">
            <h3 style="margin-top: 0; margin-bottom: 20px; color: var(--text-dark); font-size: 18px;">⚙️ Quản lý tài khoản</h3>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <button onclick="openEditModal()" class="btn btn-primary" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); border: none; color: white; padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: transform 0.2s; text-align: left;" onMouseOver="this.style.transform='translateY(-2px)'" onMouseOut="this.style.transform='translateY(0)'">
                    ✏️ Chỉnh sửa thông tin
                </button>
                <button onclick="openPasswordModal()" class="btn btn-primary" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); border: none; color: white; padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: transform 0.2s; text-align: left;" onMouseOver="this.style.transform='translateY(-2px)'" onMouseOut="this.style.transform='translateY(0)'">
                    🔐 Đổi mật khẩu
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Edit Profile -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.2); max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: var(--text-dark); font-size: 18px;">✏️ Chỉnh sửa thông tin</h3>
            <button onclick="closeEditModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">✕</button>
        </div>
        
        <form id="profileForm" method="POST">
            <div style="margin-bottom: 18px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: var(--text-dark);">Tên người dùng:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['ten_nguoi_dung']); ?>" required 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; transition: border-color 0.3s; box-sizing: border-box;" 
                       onFocus="this.style.borderColor='var(--primary-color)'" onBlur="this.style.borderColor='#ddd'">
            </div>
            
            <div style="margin-bottom: 18px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: var(--text-dark);">Email:</label>
                <input type="email" id="email" value="<?php echo htmlspecialchars($user['email_user']); ?>" disabled 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: #f5f5f5; color: #999; box-sizing: border-box;">
                <small style="color: #999; display: block; margin-top: 4px;">Không thể thay đổi email</small>
            </div>
            
            <div style="margin-bottom: 18px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: var(--text-dark);">Số điện thoại:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['so_dien_thoai_user'] ?? ''); ?>" placeholder="Nhập số điện thoại" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; transition: border-color 0.3s; box-sizing: border-box;" 
                       onFocus="this.style.borderColor='var(--primary-color)'" onBlur="this.style.borderColor='#ddd'">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: var(--text-dark);">Địa chỉ giao hàng:</label>
                <textarea id="address" name="address" placeholder="Nhập địa chỉ đầy đủ" 
                          style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: vertical; min-height: 80px; transition: border-color 0.3s; box-sizing: border-box;" 
                          onFocus="this.style.borderColor='var(--primary-color)'" onBlur="this.style.borderColor='#ddd'"><?php echo htmlspecialchars($user['dia_chi'] ?? ''); ?></textarea>
            </div>
            
            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="flex: 1; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); border: none; color: white; padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: transform 0.2s;" onMouseOver="this.style.transform='translateY(-2px)'" onMouseOut="this.style.transform='translateY(0)'">💾 Lưu thay đổi</button>
                <button type="button" onclick="closeEditModal()" class="btn btn-secondary" style="flex: 1; background: #f0f0f0; border: none; color: var(--text-dark); padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; text-align: center; transition: transform 0.2s;" onMouseOver="this.style.transform='translateY(-2px)'" onMouseOut="this.style.transform='translateY(0)'">✕ Hủy</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Change Password -->
<div id="passwordModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.2); max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: var(--text-dark); font-size: 18px;">🔐 Đổi mật khẩu</h3>
            <button onclick="closePasswordModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">✕</button>
        </div>
        
        <form id="passwordForm" method="POST">
            <div style="margin-bottom: 18px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: var(--text-dark);">Mật khẩu hiện tại:</label>
                <input type="password" id="currentPassword" name="currentPassword" placeholder="Nhập mật khẩu hiện tại" required
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; transition: border-color 0.3s; box-sizing: border-box;" 
                       onFocus="this.style.borderColor='var(--primary-color)'" onBlur="this.style.borderColor='#ddd'">
            </div>
            
            <div style="margin-bottom: 18px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: var(--text-dark);">Mật khẩu mới:</label>
                <input type="password" id="newPassword" name="newPassword" placeholder="Nhập mật khẩu mới" required
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; transition: border-color 0.3s; box-sizing: border-box;" 
                       onFocus="this.style.borderColor='var(--primary-color)'" onBlur="this.style.borderColor='#ddd'">
                <small style="color: #999; display: block; margin-top: 4px;">Mật khẩu phải có ít nhất 6 ký tự</small>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: var(--text-dark);">Xác nhận mật khẩu mới:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Xác nhận mật khẩu mới" required
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; transition: border-color 0.3s; box-sizing: border-box;" 
                       onFocus="this.style.borderColor='var(--primary-color)'" onBlur="this.style.borderColor='#ddd'">
            </div>
            
            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="flex: 1; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); border: none; color: white; padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: transform 0.2s;" onMouseOver="this.style.transform='translateY(-2px)'" onMouseOut="this.style.transform='translateY(0)'">🔒 Cập nhật mật khẩu</button>
                <button type="button" onclick="closePasswordModal()" class="btn btn-secondary" style="flex: 1; background: #f0f0f0; border: none; color: var(--text-dark); padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; text-align: center; transition: transform 0.2s;" onMouseOver="this.style.transform='translateY(-2px)'" onMouseOut="this.style.transform='translateY(0)'">✕ Hủy</button>
            </div>
        </form>
    </div>
</div>

<script>
// Fallback showToast function if not defined in header
if (typeof showToast === 'undefined') {
    window.showToast = function(message, type = 'info') {
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 6px;
            color: white;
            font-weight: 500;
            z-index: 9999;
            animation: slideIn 0.3s ease;
            background: ${type === 'success' ? '#27ae60' : type === 'error' ? '#e74c3c' : type === 'warning' ? '#f39c12' : '#3498db'};
        `;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    };
}

// Modal functions
function openEditModal() {
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function openPasswordModal() {
    document.getElementById('passwordModal').style.display = 'flex';
}

function closePasswordModal() {
    document.getElementById('passwordModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const editModal = document.getElementById('editModal');
    const passwordModal = document.getElementById('passwordModal');
    
    if (event.target === editModal) {
        editModal.style.display = 'none';
    }
    if (event.target === passwordModal) {
        passwordModal.style.display = 'none';
    }
};

// Handle form submission
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?php echo SITE_URL; ?>index.php?action=profile&method=view', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Show success message
        showToast('Đã lưu thông tin', 'success');
        closeEditModal();
        // Reload page to show updated data
        setTimeout(() => {
            window.location.reload();
        }, 1500);
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Lỗi cập nhật thông tin', 'error');
    });
});

// Handle password change form submission
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    // Validation
    if (newPassword.length < 6) {
        showToast('Mật khẩu mới phải có ít nhất 6 ký tự', 'warning');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        showToast('Mật khẩu xác nhận không trùng khớp', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'changePassword');
    formData.append('currentPassword', currentPassword);
    formData.append('newPassword', newPassword);
    formData.append('confirmPassword', confirmPassword);
    
    fetch('<?php echo SITE_URL; ?>index.php?action=profile&method=view', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Đã cập nhật mật khẩu thành công', 'success');
            document.getElementById('passwordForm').reset();
            closePasswordModal();
        } else {
            showToast(data.error || 'Lỗi cập nhật mật khẩu', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Lỗi kết nối', 'error');
    });
});
</script>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
