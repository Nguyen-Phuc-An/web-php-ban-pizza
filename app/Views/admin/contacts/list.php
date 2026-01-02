<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <h3>Menu Quản Trị</h3>
        <nav class="admin-menu">
            <ul>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=dashboard" class="menu-item">📊 Dashboard</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=products" class="menu-item">🍕 Sản phẩm</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=categories" class="menu-item">📁 Danh mục</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=orders" class="menu-item">📦 Đơn hàng</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=customers" class="menu-item">👥 Khách hàng</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=contacts" class="menu-item active">💬 Liên hệ</a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="admin-content">
        <div class="container" style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-lg); height: calc(100vh - 200px);">
            
            <!-- Left Column: Contacts List -->
            <div style="border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column;">
                <div style="padding: var(--spacing-md); border-bottom: 1px solid var(--border-color); background: var(--light-bg);">
                    <h3>Danh sách liên hệ</h3>
                </div>
                
                <div style="overflow-y: auto; flex: 1;">
                    <?php if ($contacts && count($contacts) > 0): ?>
                        <?php foreach ($contacts as $contact): ?>
                            <div class="contact-item" onclick="loadContactDetail(<?php echo $contact['contact_id']; ?>)" 
                                 style="padding: var(--spacing-md); border-bottom: 1px solid var(--border-color); cursor: pointer; transition: background 0.3s; background: var(--light-bg);"
                                 onmouseover="this.style.background = '#f5f5ff'" 
                                 onmouseout="this.style.background = 'var(--light-bg)'">
                                <p style="margin: 0 0 4px 0; font-weight: 600;"><?php echo htmlspecialchars($contact['ten_contact']); ?></p>
                                <p style="margin: 0 0 4px 0; font-size: 12px; color: var(--text-muted);"><?php echo htmlspecialchars($contact['email_contact']); ?></p>
                                <p style="margin: 0; font-size: 11px; color: var(--text-muted);"><?php echo date('d/m/Y H:i', strtotime($contact['ngay_tao_contact'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="padding: var(--spacing-md); text-align: center; color: var(--text-muted);">Không có liên hệ nào</p>
                    <?php endif; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div style="padding: var(--spacing-md); border-top: 1px solid var(--border-color); background: var(--light-bg); display: flex; gap: 8px; flex-wrap: wrap; justify-content: center;">
                        <?php if ($current_page > 1): ?>
                            <a href="<?php echo SITE_URL; ?>index.php?action=admin&method=contacts&page=<?php echo $current_page - 1; ?>" class="page-link">← Trước</a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="<?php echo SITE_URL; ?>index.php?action=admin&method=contacts&page=<?php echo $i; ?>" class="page-link <?php echo $i == $current_page ? 'active' : ''; ?>" style="padding: 4px 8px; font-size: 12px;">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($current_page < $total_pages): ?>
                            <a href="<?php echo SITE_URL; ?>index.php?action=admin&method=contacts&page=<?php echo $current_page + 1; ?>" class="page-link">Sau →</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Right Column: Contact Details -->
            <div id="contactDetail" style="border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; background: var(--light-bg);">
                <div style="padding: var(--spacing-lg); text-align: center; color: var(--text-muted);">
                    <p>Chọn một liên hệ từ danh sách bên trái để xem chi tiết</p>
                </div>
            </div>
            
        </div>
    </main>
</div>

<script>
function loadContactDetail(contactId) {
    const detailDiv = document.getElementById('contactDetail');
    detailDiv.innerHTML = '<p style="padding: var(--spacing-md); text-align: center; color: var(--text-muted);">Đang tải...</p>';
    
    fetch('<?php echo SITE_URL; ?>index.php?action=admin&method=getContactData&id=' + contactId)
        .then(response => response.json())
        .then(data => {
            if (data.contact) {
                const contact = data.contact;
                const contactDate = new Date(contact.ngay_tao_contact).toLocaleDateString('vi-VN', { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                detailDiv.innerHTML = `
                    <div style="padding: var(--spacing-lg); overflow-y: auto; flex: 1;">
                        <h3 style="margin-top: 0;">💬 Chi tiết liên hệ</h3>
                        
                        <div style="margin-bottom: var(--spacing-lg);">
                            <label style="font-weight: 600; color: var(--text-muted); font-size: 12px;">Tên liên hệ</label>
                            <p style="margin: 4px 0 0 0; font-size: 16px;">${htmlEscape(contact.ten_contact)}</p>
                        </div>
                        
                        <div style="margin-bottom: var(--spacing-lg);">
                            <label style="font-weight: 600; color: var(--text-muted); font-size: 12px;">Email</label>
                            <p style="margin: 4px 0 0 0; font-size: 16px;">${htmlEscape(contact.email_contact)}</p>
                        </div>
                        
                        <div style="margin-bottom: var(--spacing-lg);">
                            <label style="font-weight: 600; color: var(--text-muted); font-size: 12px;">Điện thoại</label>
                            <p style="margin: 4px 0 0 0; font-size: 16px;">${htmlEscape(contact.so_dien_thoai_contact || '-')}</p>
                        </div>
                        
                        <div style="margin-bottom: var(--spacing-lg);">
                            <label style="font-weight: 600; color: var(--text-muted); font-size: 12px;">Nội dung</label>
                            <p style="margin: 4px 0 0 0; line-height: 1.6; white-space: pre-wrap;">${htmlEscape(contact.noi_dung_contact)}</p>
                        </div>
                        
                        <div style="margin-bottom: var(--spacing-lg);">
                            <label style="font-weight: 600; color: var(--text-muted); font-size: 12px;">Ngày gửi</label>
                            <p style="margin: 4px 0 0 0; font-size: 14px;">${contactDate}</p>
                        </div>
                        
                        <div style="display: flex; gap: var(--spacing-md); margin-top: var(--spacing-lg);">
                            <button type="button" class="btn btn-danger" onclick="deleteContact(${contact.contact_id})">Xóa</button>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            detailDiv.innerHTML = '<p style="padding: var(--spacing-md); text-align: center; color: red;">Lỗi tải dữ liệu</p>';
        });
}

function deleteContact(contactId) {
    if (confirm('Bạn chắc chắn muốn xóa liên hệ này?')) {
        fetch('<?php echo SITE_URL; ?>index.php?action=admin&method=deleteContact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'contact_id=' + contactId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Xóa liên hệ thành công');
                location.reload();
            } else {
                alert('Lỗi: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Lỗi xóa liên hệ');
        });
    }
}

function htmlEscape(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Load first contact on page load
window.addEventListener('load', function() {
    const firstContact = document.querySelector('.contact-item');
    if (firstContact) {
        firstContact.click();
    }
});
</script>
