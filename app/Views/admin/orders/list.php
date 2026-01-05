<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <h3>Menu Quản Trị</h3>
        <nav class="admin-menu">
            <ul>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=dashboard" class="menu-item"><i class="bi bi-graph-up"></i> Dashboard</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=products" class="menu-item"><i class="bi bi-circle"></i> Sản phẩm</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=categories" class="menu-item"><i class="bi bi-folder"></i> Danh mục</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=orders" class="menu-item active"><i class="bi bi-box"></i> Đơn hàng</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=customers" class="menu-item"><i class="bi bi-people"></i> Khách hàng</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=contacts" class="menu-item"><i class="bi bi-chat-dots"></i> Liên hệ</a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="admin-content">
        <div class="container">            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr style="cursor: pointer; transition: background-color 0.2s;" onMouseOver="this.style.backgroundColor='#f5f5f5'" onMouseOut="this.style.backgroundColor=''" onclick="viewOrderDetail(<?php echo $order['order_id']; ?>)">
                            <td style="text-align: center;">#<?php echo $order['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($order['ten_nguoi_dung']); ?></td>
                            <td style="text-align: center;"><?php echo date('d/m/Y H:i', strtotime($order['ngay_tao_order'])); ?></td>
                            <td style="text-align: center;"><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?>đ</td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <?php 
                                    $currentStatus = $order['trang_thai'];
                                    $nextStatus = '';
                                    
                                    if ($currentStatus === 'Chờ xác nhận') {
                                        $nextStatus = 'Đã xác nhận';
                                    } elseif ($currentStatus === 'Đã xác nhận') {
                                        $nextStatus = 'Đang giao';
                                    } elseif ($currentStatus === 'Đang giao') {
                                        $nextStatus = 'Đã giao';
                                    }
                                    ?>
                                    
                                    <!-- Next Step Button - Click to change status -->
                                    <?php if (!empty($nextStatus)): ?>
                                        <form method="POST" action="<?php echo SITE_URL; ?>index.php?action=admin&method=updateOrderStatus&id=<?php echo $order['order_id']; ?>" style="display: inline;" onclick="event.stopPropagation();">
                                            <input type="hidden" name="status" value="<?php echo $nextStatus; ?>">
                                            <button type="submit" class="btn-action" style="background: #198754; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 600; transition: background 0.3s;">
                                                <?php echo $currentStatus; ?>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button disabled style="background: #d1e7dd; color: #0f5132; border: none; padding: 8px 16px; border-radius: 4px; font-size: 12px; font-weight: 600; cursor: not-allowed;">
                                            <?php echo $currentStatus; ?>
                                        </button>
                                    <?php endif; ?>
                                    
                                    <!-- Cancel Button -->
                                    <?php if ($currentStatus !== 'Đã hủy' && $currentStatus !== 'Đã giao'): ?>
                                        <form method="POST" action="<?php echo SITE_URL; ?>index.php?action=admin&method=updateOrderStatus&id=<?php echo $order['order_id']; ?>" style="display: inline;" onclick="event.stopPropagation();">
                                            <input type="hidden" name="status" value="Đã hủy">
                                            <button type="submit" class="btn-action" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 600; transition: background 0.3s;" onclick="return confirm('Bạn chắc chắn muốn hủy đơn này?')">
                                                <i class="bi bi-trash"></i> Hủy
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
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
<div id="orderModal" class="admin-modal">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h2>Chi tiết đơn hàng #<span id="modalOrderId"></span></h2>
            <button onclick="closeOrderModal()" class="admin-modal-close">×</button>
        </div>

        <!-- Modal Body -->
        <div class="admin-modal-body">
            <div class="order-detail-grid">
                
                <!-- Left: Order Items -->
                <div class="order-items-section">
                    <h3>Danh sách sản phẩm</h3>
                    <div id="modalOrderItems" class="order-items-container"></div>
                    
                    <!-- Subtotal -->
                    <div class="order-summary-row">
                        <span>Tạm tính:</span>
                        <span id="modalSubtotal" style="font-weight: 600;">0 đ</span>
                    </div>
                    <div class="order-summary-row">
                        <span>Phí giao hàng:</span>
                        <span style="font-weight: 600;">30,000 đ</span>
                    </div>
                </div>

                <!-- Right: Summary -->
                <div class="order-info-section">
                    <!-- Shipping Info -->
                    <div class="info-box">
                        <h4><i class="bi bi-truck"></i> Thông tin giao hàng</h4>
                        <div class="info-item">
                            <label>Người nhận:</label>
                            <span id="modalCustomerName">-</span>
                        </div>
                        <div class="info-item">
                            <label>Điện thoại:</label>
                            <span id="modalCustomerPhone">-</span>
                        </div>
                        <div class="info-item">
                            <label>Địa chỉ giao:</label>
                            <span id="modalCustomerAddress">-</span>
                        </div>
                        <div class="info-item">
                            <label>Email:</label>
                            <span id="modalCustomerEmail">-</span>
                        </div>
                    </div>

                    <!-- Order Info -->
                    <div class="info-box">
                        <h4><i class="bi bi-file-text"></i> Thông tin đơn hàng</h4>
                        <div class="info-item">
                            <label>Ngày đặt:</label>
                            <span id="modalOrderDate">-</span>
                        </div>
                        <div class="info-item">
                            <label>Phương thức TT:</label>
                            <span id="modalPaymentMethod">-</span>
                        </div>
                        <div class="info-item">
                            <label>Trạng thái:</label>
                            <span id="modalOrderStatus" class="status-badge">-</span>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="total-box">
                        <p>Tổng thanh toán</p>
                        <p id="modalTotalAmount" class="total-amount">0 đ</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="admin-modal-footer">
            <button id="exportInvoiceBtn" onclick="exportInvoice()" class="btn btn-primary" style="display: none;">📄 Xuất hóa đơn</button>
            <button onclick="closeOrderModal()" class="btn btn-secondary">Đóng</button>
        </div>
    </div>
</div>

<!-- Invoice Modal -->
<div id="invoiceModal" class="admin-modal" style="z-index: 2000;">
    <div class="invoice-modal-content">
        <div class="invoice-header">
            <button onclick="closeInvoiceModal()" class="invoice-close">×</button>
            <button onclick="printInvoice()" class="btn btn-primary"><i class="bi bi-printer"></i> In hóa đơn</button>
        </div>
        <div id="invoiceContent" class="invoice-content"></div>
    </div>
</div>

<style id="printStyle" type="text/css" media="print">
    @page {
        margin: 0.3cm;
        size: A4;
    }
    
    body * {
        visibility: hidden;
    }
    
    .invoice-print-area,
    .invoice-print-area * {
        visibility: visible;
    }
    
    .invoice-print-area {
        position: static;
        left: auto;
        top: auto;
        width: auto;
        page-break-inside: avoid;
    }
    
    .invoice-header,
    .invoice-close,
    .btn {
        display: none !important;
    }
</style>

<style>
/* Order Modal Styles */
.admin-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    z-index: 1000;
    overflow-y: auto;
    padding: 20px 0;
}

.status-select {
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s;
    font-weight: 600;
    min-width: 140px;
    appearance: none;
    background-position: right 8px center;
    background-repeat: no-repeat;
    background-size: 18px;
    padding-right: 28px;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
}

.status-select[data-status="Chờ xác nhận"] {
    background-color: #fff3cd;
    color: #856404;
    border-color: #ffc107;
}

.status-select[data-status="Đã xác nhận"] {
    background-color: #d1ecf1;
    color: #0c5460;
    border-color: #17a2b8;
}

.status-select[data-status="Đang giao"] {
    background-color: #cfe2ff;
    color: #084298;
    border-color: #0d6efd;
}

.status-select[data-status="Đã giao"] {
    background-color: #d1e7dd;
    color: #0f5132;
    border-color: #198754;
}

.status-select[data-status="Đã hủy"] {
    background-color: #f8d7da;
    color: #842029;
    border-color: #dc3545;
}


.admin-modal-content {
    background: white;
    margin: 20px auto;
    border-radius: 12px;
    width: 90%;
    max-width: 1000px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    max-height: calc(100vh - 40px);
}

.admin-modal-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    color: white;
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.admin-modal-header h2 {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
}

.admin-modal-close {
    background: none;
    border: none;
    color: white;
    font-size: 32px;
    cursor: pointer;
    padding: 0;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: background 0.2s;
}

.admin-modal-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

.admin-modal-body {
    padding: 25px;
    overflow-y: auto;
    flex: 1;
}

.order-detail-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 30px;
}

/* Order Items Section */
.order-items-section h3 {
    margin: 0 0 15px 0;
    font-size: 18px;
    color: var(--text-dark);
    font-weight: 700;
}

.order-items-container {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 20px;
}

.order-item {
    padding: 15px;
    border-bottom: 1px solid #e0e0e0;
    display: grid;
    grid-template-columns: 80px 1fr auto;
    gap: 15px;
    align-items: start;
}

.order-item:last-child {
    border-bottom: none;
}

.order-item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 6px;
    background: #f5f5f5;
}

.order-item-details {
    flex: 1;
}

.order-item-name {
    margin: 0 0 8px 0;
    font-size: 15px;
    font-weight: 600;
    color: var(--text-dark);
}

.order-item-info {
    margin: 0 0 6px 0;
    font-size: 13px;
    color: #666;
}

.order-item-price {
    margin: 0;
    font-size: 12px;
    color: #999;
}

.order-item-total {
    text-align: right;
    min-width: 100px;
}

.order-item-total-value {
    font-size: 16px;
    font-weight: 700;
    color: var(--primary-color);
}

.order-summary-row {
    padding: 12px 0;
    border-top: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    color: var(--text-dark);
}

.order-summary-row:last-child {
    border-bottom: 2px solid #e0e0e0;
    padding-bottom: 15px;
}

/* Order Info Section */
.order-info-section {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.info-box {
    background: #f9f9f9;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.info-box h4 {
    margin: 0 0 12px 0;
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    text-transform: uppercase;
}

.info-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 13px;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-item label {
    font-weight: 600;
    color: #666;
    min-width: 100px;
}

.info-item span {
    color: var(--text-dark);
    text-align: right;
    flex: 1;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    background: #e3f2fd;
    color: var(--primary-color);
}

.total-box {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    color: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    margin-top: auto;
}

.total-box p:first-child {
    margin: 0 0 8px 0;
    font-size: 13px;
    opacity: 0.9;
}

.total-amount {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.admin-modal-footer {
    padding: 15px 25px;
    background: #f9f9f9;
    border-top: 1px solid #e0e0e0;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

/* Responsive */
@media (max-width: 768px) {
    .order-detail-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .admin-modal-content {
        width: 95%;
        margin: 10px auto;
    }
}

/* Invoice Modal Styles */
.invoice-modal-content {
    background: white;
    margin: 20px auto;
    border-radius: 12px;
    width: 90%;
    max-width: 900px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    max-height: calc(100vh - 40px);
}

.invoice-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
    border-radius: 12px 12px 0 0;
}

.invoice-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 28px;
    cursor: pointer;
    padding: 0;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: background 0.2s;
}

.invoice-close:hover {
    background: rgba(255, 255, 255, 0.3);
}

.invoice-content {
    padding: 30px;
    overflow-y: auto;
    flex: 1;
}

/* Invoice Print Area */
.invoice-print-area {
    background: white;
    padding: 3px;
    font-family: 'Arial', sans-serif;
    max-width: 100%;
    margin: 0;
}

.invoice-company {
    text-align: center;
    margin-bottom: 4px;
    border-bottom: 2px solid #333;
    padding-bottom: 3px;
}

.invoice-company h1 {
    margin: 0;
    font-size: 18px;
    color: var(--primary-color);
}

.invoice-company p {
    margin: 2px 0;
    color: #666;
    font-size: 10px;
}

.invoice-title {
    text-align: center;
    font-size: 16px;
    font-weight: bold;
    margin: 4px 0;
    color: #333;
}

.invoice-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4px;
    margin-bottom: 6px;
    font-size: 9px;
}

.invoice-info-box {
    padding: 6px;
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 3px;
}

.invoice-info-box h4 {
    margin: 0 0 4px 0;
    font-size: 9px;
    font-weight: bold;
    text-transform: uppercase;
    color: var(--primary-color);
}

.invoice-info-item {
    margin: 2px 0;
    line-height: 1.2;
}

.invoice-info-item strong {
    display: inline-block;
    width: 60px;
}

.invoice-items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 4px;
    font-size: 9px;
}

.invoice-items-table thead {
    background: var(--primary-color);
    color: white;
}

.invoice-items-table th {
    padding: 2px;
    text-align: left;
    font-weight: bold;
    font-size: 8px;
}

.invoice-items-table td {
    padding: 2px;
    border-bottom: 1px solid #e0e0e0;
}

.invoice-items-table tbody tr:last-child td {
    border-bottom: 2px solid #333;
}

.invoice-items-table .text-right {
    text-align: right;
}

.invoice-summary {
    margin-left: auto;
    width: 100%;
    margin-bottom: 3px;
    font-size: 9px;
}

.invoice-summary-row {
    display: flex;
    justify-content: space-between;
    padding: 1px 0;
    border-bottom: 1px solid #e0e0e0;
}

.invoice-summary-row.total {
    border-bottom: 2px solid #333;
    font-weight: bold;
    font-size: 10px;
    color: var(--primary-color);
    padding: 2px 0;
}

.invoice-footer {
    text-align: center;
    margin-top: 3px;
    padding-top: 3px;
    border-top: 1px solid #ddd;
    font-size: 8px;
    color: #666;
}

.invoice-footer p {
    margin: 2px 0;
}

@media print {
    .invoice-print-area {
        page-break-after: avoid;
    }
}
</style>

<script>
function viewOrderDetail(orderId) {
    const modal = document.getElementById('orderModal');
    
    fetch('<?php echo SITE_URL; ?>index.php?action=admin&method=getOrderDetail&id=' + orderId)
        .then(response => response.json())
        .then(data => {
            console.log('Order Detail Data:', data); // Debug log
            if (data.order) {
                populateOrderModal(data);
                modal.style.display = 'block';
            } else {
                alert('Lỗi: Không thể tải thông tin đơn hàng');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Lỗi khi tải thông tin đơn hàng: ' + error.message);
        });
}

function populateOrderModal(data) {
    const order = data.order;
    const items = data.items || [];
    const customer = data.customer;
    
    // Store order data for invoice export
    window.currentOrderData = {
        order: order,
        items: items,
        customer: customer
    };
    
    // Header
    document.getElementById('modalOrderId').textContent = order.order_id;
    
    // Order Items
    let itemsHTML = '';
    let subtotal = 0;
    
    if (items && items.length > 0) {
        items.forEach(item => {
            const itemTotal = parseInt(item.gia_order_items) * parseInt(item.so_luong_mua);
            subtotal += itemTotal;
            
            const productName = item.ten_product || 'Sản phẩm không xác định';
            const productImage = item.hinh_anh_product || 'placeholder.jpg';
            const size = item.size || 'N/A';
            const quantity = item.so_luong_mua || 0;
            const price = parseInt(item.gia_order_items) || 0;
            
            itemsHTML += `<div class="order-item">
                <img src="<?php echo SITE_URL; ?>uploads/${productImage}" class="order-item-image" alt="${productName}">
                <div class="order-item-details">
                    <p class="order-item-name">${productName}</p>
                    <p class="order-item-info"><i class="bi bi-arrow-right"></i> <strong>Size:</strong> ${size}</p>
                    <p class="order-item-info"><i class="bi bi-box"></i> <strong>Số lượng:</strong> ${quantity}</p>
                    <p class="order-item-price">${quantity} × ${price.toLocaleString('vi-VN')} đ</p>
                </div>
                <div class="order-item-total">
                    <div class="order-item-total-value">${itemTotal.toLocaleString('vi-VN')} đ</div>
                </div>
            </div>`;
        });
    } else {
        itemsHTML = '<p style="text-align: center; color: #999; padding: 20px;">Không có sản phẩm</p>';
    }
    
    document.getElementById('modalOrderItems').innerHTML = itemsHTML;
    document.getElementById('modalSubtotal').textContent = subtotal.toLocaleString('vi-VN') + ' đ';
    
    // Customer Info
    document.getElementById('modalCustomerName').textContent = customer.ten_nguoi_dung || '-';
    document.getElementById('modalCustomerEmail').textContent = customer.email_user || '-';
    document.getElementById('modalCustomerPhone').textContent = customer.so_dien_thoai_user || '-';
    document.getElementById('modalCustomerAddress').textContent = customer.dia_chi || '-';
    
    // Order Info
    const orderDate = new Date(order.ngay_tao_order);
    document.getElementById('modalOrderDate').textContent = orderDate.toLocaleString('vi-VN');
    document.getElementById('modalPaymentMethod').textContent = order.phuong_thuc_thanh_toan || '-';
    
    // Status Badge with color
    const statusElement = document.getElementById('modalOrderStatus');
    const status = order.trang_thai || '-';
    statusElement.textContent = status;
    
    // Apply color based on status
    let statusBg = '#e3f2fd';
    let statusColor = '#0056b3';
    
    if (status === 'Chờ xác nhận') {
        statusBg = '#fff3cd';
        statusColor = '#856404';
    } else if (status === 'Đã xác nhận') {
        statusBg = '#d1ecf1';
        statusColor = '#0c5460';
    } else if (status === 'Đang giao') {
        statusBg = '#cfe2ff';
        statusColor = '#084298';
    } else if (status === 'Đã giao') {
        statusBg = '#d1e7dd';
        statusColor = '#0f5132';
    } else if (status === 'Đã hủy') {
        statusBg = '#f8d7da';
        statusColor = '#842029';
    }
    
    statusElement.style.backgroundColor = statusBg;
    statusElement.style.color = statusColor;
    
    // Show/hide export invoice button
    const exportBtn = document.getElementById('exportInvoiceBtn');
    if (status === 'Đã xác nhận' || status === 'Đang giao' || status === 'Đã giao') {
        exportBtn.style.display = 'inline-block';
    } else {
        exportBtn.style.display = 'none';
    }
    
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

// Update status select color based on selected value
function updateStatusColor(selectElement) {
    const selectedValue = selectElement.value;
    selectElement.setAttribute('data-status', selectedValue);
}

// Initialize status colors on page load
document.addEventListener('DOMContentLoaded', function() {
    const statusSelects = document.querySelectorAll('.status-select');
    statusSelects.forEach(select => {
        updateStatusColor(select);
    });
});

// Export Invoice
function exportInvoice() {
    if (!window.currentOrderData) {
        alert('Không có dữ liệu đơn hàng');
        return;
    }
    
    const data = window.currentOrderData;
    const order = data.order;
    const items = data.items || [];
    const customer = data.customer;
    
    let invoiceHTML = `<div class="invoice-print-area">
        <!-- 1. THÔNG TIN CỬA HÀNG -->
        <div class="invoice-company">
            <h1>🍕 PIZZA A.D.A</h1>
            <div style="border-bottom: 2px solid #333; padding-bottom: 8px; margin-bottom: 8px;">
                <p><strong>Địa chỉ:</strong> Số 123 Đường Pizza, Quận 1, TP.HCM</p>
                <p><strong>Điện thoại:</strong> 0123 456 789 | <strong>Email:</strong> info@pizzaada.vn</p>
                <p><strong>Website:</strong> www.pizzaada.vn</p>
            </div>
        </div>
        
        <div style="text-align: center; font-weight: bold; font-size: 16px; margin: 10px 0; border-bottom: 2px solid #333; padding-bottom: 8px;">
            HÓA ĐƠN GIAO HÀNG
        </div>
        
        <!-- 2. THÔNG TIN HÓA ĐƠN -->
        <div style="background: #f9f9f9; padding: 10px; border: 1px solid #ddd; margin-bottom: 12px; border-radius: 3px; font-size: 12px;">
            <strong style="color: var(--primary-color); display: block; margin-bottom: 6px;">THÔNG TIN HÓA ĐƠN</strong>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                <p style="margin: 2px 0;"><strong>Mã hóa đơn:</strong> #${order.order_id}</p>
                <p style="margin: 2px 0;"><strong>Ngày lập:</strong> ${new Date(order.ngay_tao_order).toLocaleDateString('vi-VN')} ${new Date(order.ngay_tao_order).toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'})}</p>
            </div>
        </div>
        
        <!-- 3. THÔNG TIN KHÁCH HÀNG -->
        <div style="background: #f9f9f9; padding: 10px; border: 1px solid #ddd; margin-bottom: 12px; border-radius: 3px; font-size: 12px;">
            <strong style="color: var(--primary-color); display: block; margin-bottom: 6px;">THÔNG TIN KHÁCH HÀNG</strong>
            <p style="margin: 3px 0;"><strong>Tên người nhận:</strong> ${customer.ten_nguoi_dung}</p>
            <p style="margin: 3px 0;"><strong>Số điện thoại:</strong> ${customer.so_dien_thoai_user}</p>
            <p style="margin: 3px 0;"><strong>Địa chỉ giao:</strong></p>
            <p style="margin: 3px 0 6px 0; color: #666; padding-left: 10px;">${customer.dia_chi}</p>
        </div>
        
        <!-- 4. DANH SÁCH SẢN PHẨM -->
        <table class="invoice-items-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Tên Sản Phẩm</th>
                    <th style="width: 12%;">Size</th>
                    <th style="width: 15%; text-align: right;">Đơn Giá</th>
                    <th style="width: 10%; text-align: center;">SL</th>
                    <th style="width: 23%; text-align: right;">Thành Tiền</th>
                </tr>
            </thead>
            <tbody>`;
    
    let subtotal = 0;
    items.forEach(item => {
        const itemTotal = parseInt(item.gia_order_items) * parseInt(item.so_luong_mua);
        subtotal += itemTotal;
        const price = parseInt(item.gia_order_items);
        const quantity = parseInt(item.so_luong_mua);
        
        invoiceHTML += `<tr>
            <td>${item.ten_product}</td>
            <td>${item.size}</td>
            <td style="text-align: right;">${price.toLocaleString('vi-VN')} đ</td>
            <td style="text-align: center;">${quantity}</td>
            <td style="text-align: right;">${itemTotal.toLocaleString('vi-VN')} đ</td>
        </tr>`;
    });
    
    invoiceHTML += `</tbody>
        </table>
        
        <!-- 5. TỔNG TIỀN & THANH TOÁN -->
        <div style="background: #f9f9f9; padding: 10px; border: 1px solid #ddd; margin-bottom: 12px; border-radius: 3px; font-size: 12px;">
            <strong style="color: var(--primary-color); display: block; margin-bottom: 6px;">THÔNG TIN THANH TOÁN</strong>
            <p style="margin: 3px 0;"><strong>Phương thức:</strong> ${order.phuong_thuc_thanh_toan}</p>
            <p style="margin: 3px 0;"><strong>Trạng thái:</strong> <span style="background: #fff3cd; color: #856404; padding: 2px 6px; border-radius: 3px; font-weight: bold;">Chưa thanh toán</span></p>
        </div>
        
        <!-- Tổng tiền -->
        <div style="margin-bottom: 12px;">
            <div style="padding: 8px; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; font-size: 12px;">
                <span><strong>Tạm tính:</strong></span>
                <span><strong>${subtotal.toLocaleString('vi-VN')} đ</strong></span>
            </div>
            <div style="padding: 8px; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; font-size: 12px;">
                <span><strong>Phí giao hàng:</strong></span>
                <span><strong>30,000 đ</strong></span>
            </div>
            <div style="padding: 10px; background: var(--primary-color); color: white; display: flex; justify-content: space-between; font-size: 14px; font-weight: bold; border-radius: 3px;">
                <span>TỔNG THANH TOÁN:</span>
                <span>${parseInt(order.tong_tien).toLocaleString('vi-VN')} đ</span>
            </div>
        </div>
        
        <!-- 7. FOOTER: LỜI CẢM ƠN & CHÍNH SÁCH -->
        <div class="invoice-footer">
            <p style="font-size: 13px; font-weight: bold;">Cảm ơn quý khách đã tin tưởng Pizza A.D.A! 🙏</p>
            <p style="margin-top: 6px; font-size: 11px;">Hotline hỗ trợ: <strong>0123 456 789</strong> (8:00 - 22:00 hằng ngày)</p>
            <p style="margin-top: 6px; font-size: 11px; color: #666;">Chính sách đổi/trả: Hàng bị hỏng/thiếu có thể đổi trong vòng 24h. Vui lòng liên hệ hotline.</p>
            <p style="margin-top: 8px; font-size: 12px; font-style: italic; color: var(--primary-color);">🍕 "Pizza tươi, ngon, đậm đà" 🍕</p>
            <p style="margin-top: 8px; font-size: 10px; color: #999;">In lúc: ${new Date().toLocaleString('vi-VN')}</p>
        </div>
    </div>`;
    
    document.getElementById('invoiceContent').innerHTML = invoiceHTML;
    document.getElementById('invoiceModal').style.display = 'block';
}

function closeInvoiceModal() {
    document.getElementById('invoiceModal').style.display = 'none';
}

function printInvoice() {
    window.print();
}
</script>
