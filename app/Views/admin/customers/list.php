<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <h3>Menu Quản Trị</h3>
        <!-- Menu điều hướng admin -->
        <nav class="admin-menu">
            <ul>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=dashboard" class="menu-item"><i class="bi bi-graph-up"></i> Dashboard</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=products" class="menu-item"><i class="bi bi-circle"></i> Sản phẩm</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=categories" class="menu-item"><i class="bi bi-folder"></i> Danh mục</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=orders" class="menu-item"><i class="bi bi-box"></i> Đơn hàng</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=customers" class="menu-item active"><i class="bi bi-people"></i> Khách hàng</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=contacts" class="menu-item"><i class="bi bi-chat-dots"></i> Liên hệ</a></li>
            </ul>
        </nav>
    </aside>
    <!-- Nội dung chính của trang quản trị khách hàng -->
    <main class="admin-content">
        <div class="container">            
            <table class="admin-table" style="height: 80vh;">
                <thead>
                    <tr>
                        <th style="width: calc(100% / 7);">ID</th>
                        <th style="width: calc(100% / 7);">Tên</th>
                        <th style="width: calc(100% / 7);">Email</th>
                        <th style="width: calc(100% / 7);">Điện thoại</th>
                        <th style="width: calc(100% / 7);">Trạng thái</th>
                        <th style="width: calc(100% / 7);">Ngày tạo</th>
                        <th style="width: calc(100% / 7);">Hành động</th>
                    </tr>
                </thead>
                <tbody style="max-height: 90vh;">
                    <?php foreach ($customers as $customer): ?>
                        <tr onclick="openCustomerModal(<?php echo $customer['user_id']; ?>, '<?php echo htmlspecialchars(addslashes($customer['ten_nguoi_dung'])); ?>', '<?php echo htmlspecialchars($customer['email_user']); ?>', '<?php echo htmlspecialchars($customer['so_dien_thoai_user'] ?? '-'); ?>')" style="cursor: pointer;">
                            <td style="text-align: center; width: calc(100% / 7);"><?php echo $customer['user_id']; ?></td>
                            <td style="width: calc(100% / 7);"><?php echo htmlspecialchars($customer['ten_nguoi_dung']); ?></td>
                            <td style="width: calc(100% / 7);"><?php echo htmlspecialchars($customer['email_user']); ?></td>
                            <td style="text-align: center; width: calc(100% / 7);"><?php echo htmlspecialchars($customer['so_dien_thoai_user'] ?? '-'); ?></td>
                            <td style="text-align: center; width: calc(100% / 7);">
                                <?php if (isset($customer['trang_thai_tai_khoan']) && $customer['trang_thai_tai_khoan'] === 'Khóa'): ?>
                                    <span style="background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;"><i class="bi bi-lock"></i> Khóa</span>
                                <?php else: ?>
                                    <span style="background: #d1e7dd; color: #0f5132; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;"><i class="bi bi-check-circle"></i> Hoạt động</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center; width: calc(100% / 7);"><?php echo date('d/m/Y', strtotime($customer['ngay_tao_user'])); ?></td>
                            <td style="text-align: center; white-space: nowrap; width: calc(100% / 7);" onclick="event.stopPropagation();">
                                <?php if (!isset($customer['trang_thai_tai_khoan']) || $customer['trang_thai_tai_khoan'] === 'Hoạt động'): ?>
                                    <button onclick="toggleAccountStatus(<?php echo $customer['user_id']; ?>, 'Khóa')" class="btn-action" style="background: #dc3545; padding: 4px 8px; font-size: 12px;"><i class="bi bi-lock"></i> Khóa</button>
                                <?php else: ?>
                                    <button onclick="toggleAccountStatus(<?php echo $customer['user_id']; ?>, 'Hoạt động')" class="btn-action" style="background: #28a745; padding: 4px 8px; font-size: 12px;"><i class="bi bi-unlock"></i> Bỏ khóa</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($current_page > 1): ?>
                        <a href="<?php echo SITE_URL; ?>index.php?action=admin&method=customers&page=<?php echo $current_page - 1; ?>" class="page-link">← Trước</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="<?php echo SITE_URL; ?>index.php?action=admin&method=customers&page=<?php echo $i; ?>" class="page-link <?php echo $i == $current_page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                        <a href="<?php echo SITE_URL; ?>index.php?action=admin&method=customers&page=<?php echo $current_page + 1; ?>" class="page-link">Sau →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Modal Xác nhận Khóa/Bỏ khóa tài khoản -->
<div id="confirmModal" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <h3 style="margin-top: 0; text-align: center; color: var(--primary-color);">
            <i class="bi bi-exclamation-triangle" style="font-size: 32px; color: #ff9800;"></i>
        </h3>
        <h3 id="confirmTitle" style="text-align: center; margin: 10px 0;">Xác nhận hành động</h3>
        <p id="confirmMessage" style="text-align: center; color: var(--text-muted); margin-bottom: 20px;">Bạn chắc chắn muốn thực hiện hành động này?</p>
        
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeConfirmModal()">Hủy</button>
            <button type="button" id="confirmBtn" class="btn btn-danger" onclick="executeToggleStatus()">Xác nhận</button>
        </div>
    </div>
</div>

<!-- Modal Chi tiết khách hàng -->
<div id="customerModal" class="modal">
    <div class="modal-content" style="max-width: 700px; max-height: 90vh; overflow-y: auto;">
        <span class="close" onclick="closeCustomerModal()">&times;</span>
        <h2>👤 Thông tin khách hàng</h2>
        
        <div style="background: var(--light-bg); padding: var(--spacing-md); border-radius: 8px; margin-bottom: var(--spacing-md);">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-md);">
                <div>
                    <label style="font-weight: 600; color: var(--text-muted); font-size: 12px;">Tên khách hàng</label>
                    <p id="modalCustomerName" style="margin: 4px 0 0 0; font-size: 16px;"></p>
                </div>
                <div>
                    <label style="font-weight: 600; color: var(--text-muted); font-size: 12px;">Email</label>
                    <p id="modalCustomerEmail" style="margin: 4px 0 0 0; font-size: 16px;"></p>
                </div>
                <div>
                    <label style="font-weight: 600; color: var(--text-muted); font-size: 12px;">Điện thoại</label>
                    <p id="modalCustomerPhone" style="margin: 4px 0 0 0; font-size: 16px;"></p>
                </div>
                <div>
                    <label style="font-weight: 600; color: var(--text-muted); font-size: 12px;">Địa chỉ</label>
                    <p id="modalCustomerAddress" style="margin: 4px 0 0 0; font-size: 16px;"></p>
                </div>
            </div>
        </div>
        
        <h3><i class="bi bi-file-text"></i> Lịch sử đơn hàng</h3>
        <div id="modalOrderHistory" style="border: 1px solid var(--border-color); border-radius: 8px;">
            <p style="padding: var(--spacing-md); text-align: center; color: var(--text-muted);">Đang tải...</p>
        </div>
        
        <div class="modal-actions" style="margin-top: var(--spacing-lg);">
            <button type="button" class="btn btn-secondary" onclick="closeCustomerModal()">Đóng</button>
        </div>
    </div>
</div>

<script>
// Hàm mở modal chi tiết khách hàng
function openCustomerModal(customerId, name, email, phone) {
    document.getElementById('modalCustomerName').textContent = name;
    document.getElementById('modalCustomerEmail').textContent = email;
    document.getElementById('modalCustomerPhone').textContent = phone;
    document.getElementById('modalOrderHistory').innerHTML = '<p style="padding: var(--spacing-md); text-align: center; color: var(--text-muted);">Đang tải...</p>';
    
    document.getElementById('customerModal').style.display = 'block';
    
    // Thông tin khách hàng từ server
    fetch('<?php echo SITE_URL; ?>index.php?action=admin&method=getCustomerData&id=' + customerId)
        .then(response => response.json())
        .then(data => {
            // Cập nhật chi tiết khách hàng
            document.getElementById('modalCustomerAddress').textContent = data.customer.dia_chi || '-';
            
            // Định dạng và sắp xếp lịch sử đơn hàng
            let orders = data.orders || [];
            
            if (orders.length > 0) {
                // Định nghĩa độ ưu tiên trạng thái
                const statusPriority = {
                    'Chờ xác nhận': 0,
                    'Đã xác nhận': 1,
                    'Đang giao': 2,
                    'Đã giao': 3,
                    'Đã hủy': 4
                };
                
                // Sắp xếp đơn hàng
                orders.sort((a, b) => {
                    const priorityA = statusPriority[a.trang_thai] ?? 999;
                    const priorityB = statusPriority[b.trang_thai] ?? 999;
                    
                    // Sắp xếp theo độ ưu tiên (đơn hàng đang hoạt động trước)
                    if (priorityA !== priorityB) {
                        return priorityA - priorityB;
                    }
                    
                    // Trong cùng độ ưu tiên, sắp xếp theo ngày (mới nhất trước)
                    return new Date(b.ngay_tao_order) - new Date(a.ngay_tao_order);
                });
            }
            
            // Tạo HTML cho lịch sử đơn hàng
            let orderHTML = '';
            if (orders && orders.length > 0) {
                orderHTML = '<div style="padding: var(--spacing-md);">';
                orders.forEach(order => {
                    const orderDate = new Date(order.ngay_tao_order).toLocaleDateString('vi-VN');
                    const total = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(order.tong_tien);
                    
                    // Xác định màu trạng thái
                    let statusColor = '#666';
                    let statusBg = '#f0f0f0';
                    if (order.trang_thai === 'Chờ xác nhận') {
                        statusColor = '#856404';
                        statusBg = '#fff3cd';
                    } else if (order.trang_thai === 'Đã xác nhận') {
                        statusColor = '#0c5460';
                        statusBg = '#d1ecf1';
                    } else if (order.trang_thai === 'Đang giao') {
                        statusColor = '#084298';
                        statusBg = '#cfe2ff';
                    } else if (order.trang_thai === 'Đã giao') {
                        statusColor = '#0f5132';
                        statusBg = '#d1e7dd';
                    } else if (order.trang_thai === 'Đã hủy') {
                        statusColor = '#842029';
                        statusBg = '#f8d7da';
                    }
                    
                    orderHTML += `
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--spacing-sm) 0; border-bottom: 1px solid var(--border-color);">
                            <div>
                                <p style="margin: 0; font-weight: 600;">Đơn hàng #${order.order_id}</p>
                                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">${orderDate}</p>
                            </div>
                            <div style="text-align: right;">
                                <p style="margin: 0; font-weight: 600;">${total}</p>
                                <p style="margin: 4px 0 0 0; font-size: 12px; color: white; background: ${statusBg}; color: ${statusColor}; padding: 2px 8px; border-radius: 4px; display: inline-block;">${order.trang_thai}</p>
                            </div>
                        </div>
                    `;
                });
                orderHTML += '</div>';
            } else {
                orderHTML = '<p style="padding: var(--spacing-md); text-align: center; color: var(--text-muted);">Khách hàng chưa có đơn hàng</p>';
            }
            document.getElementById('modalOrderHistory').innerHTML = orderHTML;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modalOrderHistory').innerHTML = '<p style="padding: var(--spacing-md); text-align: center; color: red;">Lỗi tải dữ liệu</p>';
        });
}
// Hàm đóng modal chi tiết khách hàng
function closeCustomerModal() {
    document.getElementById('customerModal').style.display = 'none';
}
// Đóng modal khi click ngoài
window.onclick = function(event) {
    var customerModal = document.getElementById('customerModal');
    var confirmModal = document.getElementById('confirmModal');
    
    if (event.target == customerModal) {
        customerModal.style.display = 'none';
    }
    if (event.target == confirmModal) {
        closeConfirmModal();
    }
}
// Biến lưu trữ userId và status để toggle
let pendingToggleUserId = null;
let pendingToggleStatus = null;
// Khóa khóa tài khoản
function toggleAccountStatus(userId, status) {
    pendingToggleUserId = userId;
    pendingToggleStatus = status;
    
    // Cập nhật modal
    const confirmTitle = document.getElementById('confirmTitle');
    const confirmMessage = document.getElementById('confirmMessage');
    const confirmBtn = document.getElementById('confirmBtn');
    
    if (status === 'Khóa') {
        confirmTitle.innerHTML = '<i class="bi bi-lock" style="color: #dc3545;"></i> Khóa tài khoản';
        confirmMessage.textContent = 'Bạn chắc chắn muốn khóa tài khoản này? Tài khoản sẽ không thể đăng nhập được.';
        confirmBtn.style.background = '#dc3545';
    } else {
        confirmTitle.innerHTML = '<i class="bi bi-unlock" style="color: #28a745;"></i> Bỏ khóa tài khoản';
        confirmMessage.textContent = 'Bạn chắc chắn muốn bỏ khóa tài khoản này? Tài khoản sẽ có thể đăng nhập trở lại.';
        confirmBtn.style.background = '#28a745';
    }
    
    // Hiển thị modal
    document.getElementById('confirmModal').style.display = 'block';
}
// Đóng modal xác nhận
function closeConfirmModal() {
    document.getElementById('confirmModal').style.display = 'none';
    pendingToggleUserId = null;
    pendingToggleStatus = null;
}
// Thực hiện toggle trạng thái
function executeToggleStatus() {
    if (!pendingToggleUserId || !pendingToggleStatus) return;
    
    const userId = pendingToggleUserId;
    const status = pendingToggleStatus;
    
    // Đóng modal
    closeConfirmModal();
    
    fetch('<?php echo SITE_URL; ?>index.php?action=admin&method=toggleAccountStatus&id=' + userId + '&status=' + status, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Tạo toast element
            const toastContainer = document.querySelector('.toast-container') || createToastContainer();
            const toastEl = document.createElement('div');
            toastEl.className = 'toast success';
            toastEl.innerHTML = `
                <span class="toast-icon"><i class="bi bi-check-circle"></i></span>
                <span class="toast-message">${data.message || 'Cập nhật trạng thái thành công!'}</span>
                <button class="toast-close" onclick="this.parentElement.remove();">&times;</button>
            `;
            toastContainer.appendChild(toastEl);
            setTimeout(() => { if (toastEl.parentElement) toastEl.remove(); }, 1500);
            
            // Reload sau 1.5 giây
            setTimeout(() => location.reload(), 1500);
        } else {
            // Tạo toast error
            const toastContainer = document.querySelector('.toast-container') || createToastContainer();
            const toastEl = document.createElement('div');
            toastEl.className = 'toast error';
            toastEl.innerHTML = `
                <span class="toast-icon"><i class="bi bi-exclamation-circle"></i></span>
                <span class="toast-message">${data.message || 'Có lỗi xảy ra!'}</span>
                <button class="toast-close" onclick="this.parentElement.remove();">&times;</button>
            `;
            toastContainer.appendChild(toastEl);
            setTimeout(() => { if (toastEl.parentElement) toastEl.remove(); }, 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const toastContainer = document.querySelector('.toast-container') || createToastContainer();
        const toastEl = document.createElement('div');
        toastEl.className = 'toast error';
        toastEl.innerHTML = `
            <span class="toast-icon"><i class="bi bi-exclamation-circle"></i></span>
            <span class="toast-message">Có lỗi xảy ra!</span>
            <button class="toast-close" onclick="this.parentElement.remove();">&times;</button>
        `;
        toastContainer.appendChild(toastEl);
        setTimeout(() => { if (toastEl.parentElement) toastEl.remove(); }, 3000);
    });
}
// Tạo toast container nếu chưa tồn tại
function createToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
    return container;
}
// Đóng confirm modal khi click ngoài
window.onclick = function(event) {
    var confirmModal = document.getElementById('confirmModal');
    if (event.target == confirmModal) {
        closeConfirmModal();
    }
}
</script>
