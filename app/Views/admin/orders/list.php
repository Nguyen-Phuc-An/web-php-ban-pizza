<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <h3>Menu Quản Trị</h3>
        <nav class="admin-menu">
            <ul>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=dashboard" class="menu-item">📊 Dashboard</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=products" class="menu-item">🍕 Sản phẩm</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=categories" class="menu-item">📁 Danh mục</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=orders" class="menu-item active">📦 Đơn hàng</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=customers" class="menu-item">👥 Khách hàng</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=contacts" class="menu-item">💬 Liên hệ</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=auth&method=logout" class="menu-item">🚪 Đăng xuất</a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="admin-content">
        <div class="container">
            <h2>Quản Lý Đơn Hàng</h2>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($order['ten_nguoi_dung']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($order['ngay_tao_order'])); ?></td>
                            <td><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?>đ</td>
                            <td>
                                <form method="POST" action="<?php echo SITE_URL; ?>index.php?action=admin&method=updateOrderStatus&id=<?php echo $order['order_id']; ?>" class="inline-form">
                                    <select name="status" class="status-select" onchange="this.form.submit()">
                                        <option value="Chờ xác nhận" <?php echo $order['trang_thai'] === 'Chờ xác nhận' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                        <option value="Đã xác nhận" <?php echo $order['trang_thai'] === 'Đã xác nhận' ? 'selected' : ''; ?>>Đã xác nhận</option>
                                        <option value="Đang giao" <?php echo $order['trang_thai'] === 'Đang giao' ? 'selected' : ''; ?>>Đang giao</option>
                                        <option value="Đã giao" <?php echo $order['trang_thai'] === 'Đã giao' ? 'selected' : ''; ?>>Đã giao</option>
                                        <option value="Đã hủy" <?php echo $order['trang_thai'] === 'Đã hủy' ? 'selected' : ''; ?>>Đã hủy</option>
                                    </select>
                                </form>
                            </td>
                            <td><button class="btn btn-small" onclick="viewOrderDetail(<?php echo $order['order_id']; ?>)">Chi tiết</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($current_page > 1): ?>
                        <a href="<?php echo SITE_URL; ?>index.php?action=admin&method=orders&page=<?php echo $current_page - 1; ?>" class="page-link">← Trước</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="<?php echo SITE_URL; ?>index.php?action=admin&method=orders&page=<?php echo $i; ?>" class="page-link <?php echo $i == $current_page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                        <a href="<?php echo SITE_URL; ?>index.php?action=admin&method=orders&page=<?php echo $current_page + 1; ?>" class="page-link">Sau →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Order Detail Modal -->
<div id="orderModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; overflow-y: auto;">
    <div style="background: white; margin: 20px auto; border-radius: 8px; max-width: 800px; padding: 0; box-shadow: 0 5px 20px rgba(0,0,0,0.2);">
        <!-- Modal Header -->
        <div style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin: 0; font-size: 20px;">Chi tiết đơn hàng #<span id="modalOrderId"></span></h2>
            <button onclick="closeOrderModal()" style="background: none; border: none; color: white; font-size: 28px; cursor: pointer;">×</button>
        </div>

        <!-- Modal Body -->
        <div style="padding: 25px;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                
                <!-- Left: Order Items -->
                <div>
                    <h3>Sản phẩm</h3>
                    <div id="modalOrderItems" style="margin-bottom: 20px;"></div>
                </div>

                <!-- Right: Summary -->
                <div>
                    <!-- Customer Info -->
                    <div style="background: #f5f5f5; padding: 15px; border-radius: 6px; margin-bottom: 15px;">
                        <h4 style="margin-top: 0; margin-bottom: 12px;">Khách hàng</h4>
                        <p style="margin: 5px 0; font-size: 13px;"><strong>Tên:</strong> <span id="modalCustomerName"></span></p>
                        <p style="margin: 5px 0; font-size: 13px;"><strong>Email:</strong> <span id="modalCustomerEmail"></span></p>
                        <p style="margin: 5px 0; font-size: 13px;"><strong>Phone:</strong> <span id="modalCustomerPhone"></span></p>
                        <p style="margin: 5px 0; font-size: 13px;"><strong>Địa chỉ:</strong> <span id="modalCustomerAddress"></span></p>
                    </div>

                    <!-- Order Info -->
                    <div style="background: #f5f5f5; padding: 15px; border-radius: 6px; margin-bottom: 15px;">
                        <h4 style="margin-top: 0; margin-bottom: 12px;">Thông tin</h4>
                        <p style="margin: 5px 0; font-size: 13px;"><strong>Ngày đặt:</strong> <span id="modalOrderDate"></span></p>
                        <p style="margin: 5px 0; font-size: 13px;"><strong>Phương thức:</strong> <span id="modalPaymentMethod"></span></p>
                        <p style="margin: 5px 0; font-size: 13px;"><strong>Trạng thái:</strong> <span id="modalOrderStatus"></span></p>
                    </div>

                    <!-- Total -->
                    <div style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); color: white; padding: 15px; border-radius: 6px;">
                        <p style="margin: 0 0 10px 0; font-size: 13px;">Tổng thanh toán:</p>
                        <p style="margin: 0; font-size: 24px; font-weight: 700;" id="modalTotalAmount"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div style="padding: 15px 25px; background: #f9f9f9; border-top: 1px solid #e0e0e0; border-radius: 0 0 8px 8px; display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="closeOrderModal()" class="btn btn-secondary">Đóng</button>
        </div>
    </div>
</div>

<script>
function viewOrderDetail(orderId) {
    const modal = document.getElementById('orderModal');
    
    fetch('<?php echo SITE_URL; ?>index.php?action=admin&method=getOrderDetail&id=' + orderId)
        .then(response => response.json())
        .then(data => {
            if (data.order) {
                populateOrderModal(data);
                modal.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Lỗi khi tải thông tin đơn hàng');
        });
}

function populateOrderModal(data) {
    const order = data.order;
    const items = data.items;
    const customer = data.customer;
    
    // Header
    document.getElementById('modalOrderId').textContent = order.order_id;
    
    // Order Items
    let itemsHTML = '';
    let subtotal = 0;
    if (items && items.length > 0) {
        itemsHTML = '<div style="border: 1px solid #e0e0e0; border-radius: 6px; overflow: hidden;">';
        items.forEach(item => {
            const itemTotal = item.gia_order_items * item.so_luong_mua;
            subtotal += itemTotal;
            itemsHTML += `
                <div style="padding: 12px; border-bottom: 1px solid #e0e0e0; display: grid; grid-template-columns: auto 1fr auto; gap: 12px; align-items: center;">
                    <div style="font-weight: 600; font-size: 12px; color: #666;">SP #${item.fk_product_id}</div>
                    <div>
                        <p style="margin: 0 0 4px 0; font-size: 13px;"><strong>Size:</strong> ${item.size} | <strong>Số lượng:</strong> ${item.so_luong_mua}</p>
                        <p style="margin: 0; font-size: 13px; color: #666;">${item.so_luong_mua} × ${parseInt(item.gia_order_items).toLocaleString('vi-VN')} đ</p>
                    </div>
                    <div style="text-align: right; font-weight: 600; color: var(--primary-color);">${parseInt(itemTotal).toLocaleString('vi-VN')} đ</div>
                </div>
            `;
        });
        itemsHTML += '</div>';
    }
    document.getElementById('modalOrderItems').innerHTML = itemsHTML;
    
    // Customer Info
    document.getElementById('modalCustomerName').textContent = customer.ten_nguoi_dung || 'N/A';
    document.getElementById('modalCustomerEmail').textContent = customer.email_user || 'N/A';
    document.getElementById('modalCustomerPhone').textContent = customer.so_dien_thoai_user || 'N/A';
    document.getElementById('modalCustomerAddress').textContent = customer.dia_chi || 'N/A';
    
    // Order Info
    document.getElementById('modalOrderDate').textContent = new Date(order.ngay_tao_order).toLocaleString('vi-VN');
    document.getElementById('modalPaymentMethod').textContent = order.phuong_thuc_thanh_toan;
    document.getElementById('modalOrderStatus').textContent = order.trang_thai;
    
    // Total
    document.getElementById('modalTotalAmount').textContent = parseInt(order.tong_tien).toLocaleString('vi-VN') + ' đ';
}

function closeOrderModal() {
    document.getElementById('orderModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('orderModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}
</script>
