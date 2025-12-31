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
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=customers" class="menu-item active">👥 Khách hàng</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=contacts" class="menu-item">💬 Liên hệ</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=auth&method=logout" class="menu-item">🚪 Đăng xuất</a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="admin-content">
        <div class="container">
            <h2>Quản Lý Khách Hàng</h2>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Điện thoại</th>
                        <th>Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr style="cursor: pointer;" onclick="openCustomerModal(<?php echo $customer['user_id']; ?>, '<?php echo htmlspecialchars(addslashes($customer['ten_nguoi_dung'])); ?>', '<?php echo htmlspecialchars($customer['email_user']); ?>', '<?php echo htmlspecialchars($customer['so_dien_thoai_user'] ?? '-'); ?>')">
                            <td><?php echo $customer['user_id']; ?></td>
                            <td><?php echo htmlspecialchars($customer['ten_nguoi_dung']); ?></td>
                            <td><?php echo htmlspecialchars($customer['email_user']); ?></td>
                            <td><?php echo htmlspecialchars($customer['so_dien_thoai_user'] ?? '-'); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($customer['ngay_tao_user'])); ?></td>
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
        
        <h3>📋 Lịch sử đơn hàng</h3>
        <div id="modalOrderHistory" style="border: 1px solid var(--border-color); border-radius: 8px;">
            <p style="padding: var(--spacing-md); text-align: center; color: var(--text-muted);">Đang tải...</p>
        </div>
        
        <div class="modal-actions" style="margin-top: var(--spacing-lg);">
            <button type="button" class="btn btn-secondary" onclick="closeCustomerModal()">Đóng</button>
        </div>
    </div>
</div>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>

<script>
function openCustomerModal(customerId, name, email, phone) {
    document.getElementById('modalCustomerName').textContent = name;
    document.getElementById('modalCustomerEmail').textContent = email;
    document.getElementById('modalCustomerPhone').textContent = phone;
    document.getElementById('modalOrderHistory').innerHTML = '<p style="padding: var(--spacing-md); text-align: center; color: var(--text-muted);">Đang tải...</p>';
    
    document.getElementById('customerModal').style.display = 'block';
    
    // Fetch customer details and order history
    fetch('<?php echo SITE_URL; ?>index.php?action=admin&method=getCustomerData&id=' + customerId)
        .then(response => response.json())
        .then(data => {
            // Update customer details
            document.getElementById('modalCustomerAddress').textContent = data.customer.dia_chi || '-';
            
            // Build order history HTML
            let orderHTML = '';
            if (data.orders && data.orders.length > 0) {
                orderHTML = '<div style="padding: var(--spacing-md);">';
                data.orders.forEach(order => {
                    const orderDate = new Date(order.ngay_tao_order).toLocaleDateString('vi-VN');
                    const total = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(order.tong_tien);
                    orderHTML += `
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--spacing-sm) 0; border-bottom: 1px solid var(--border-color);">
                            <div>
                                <p style="margin: 0; font-weight: 600;">Đơn hàng #${order.order_id}</p>
                                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">${orderDate}</p>
                            </div>
                            <div style="text-align: right;">
                                <p style="margin: 0; font-weight: 600;">${total}</p>
                                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">${order.trang_thai}</p>
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

function closeCustomerModal() {
    document.getElementById('customerModal').style.display = 'none';
}

// Đóng modal khi click ngoài
window.onclick = function(event) {
    var modal = document.getElementById('customerModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>
