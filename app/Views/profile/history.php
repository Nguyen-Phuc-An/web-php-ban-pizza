<?php include APP_PATH . 'Views/layout/header.php'; ?>

<style>
    html, body {
        height: 100%;
    }
    
    body {
        display: flex;
        flex-direction: column;
    }
    
    .main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
</style>

<div class="container">
    <div class="page-header">
        <h1>📦 Lịch Sử Đơn Hàng</h1>
        <p>Quản lý và theo dõi các đơn hàng của bạn</p>
    </div>

    <!-- Filter Menu -->
    <div style="margin-top: 30px; margin-bottom: 25px;">
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button onclick="filterOrders('all')" 
               style="padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.3s; border: 2px solid #ddd; background: white; color: var(--text-dark); cursor: pointer; <?php echo (!isset($_GET['status']) || $_GET['status'] === 'all') ? 'background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%)); color: white; border-color: var(--primary-color);' : ''; ?>" 
               onMouseOver="if (this.dataset.active !== 'true') this.style.borderColor='#999'" 
               onMouseOut="if (this.dataset.active !== 'true') this.style.borderColor='#ddd'"
               data-active="<?php echo (!isset($_GET['status']) || $_GET['status'] === 'all') ? 'true' : 'false'; ?>">
                Tất cả
            </button>
            <button onclick="filterOrders('pending')" 
               style="padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.3s; border: 2px solid #ddd; background: white; color: var(--text-dark); cursor: pointer; <?php echo (isset($_GET['status']) && $_GET['status'] === 'pending') ? 'background: #fff3cd; color: #856404; border-color: #ffc107;' : ''; ?>"
               onMouseOver="if (this.dataset.active !== 'true') this.style.borderColor='#ffc107'"
               onMouseOut="if (this.dataset.active !== 'true') this.style.borderColor='#ddd'"
               data-active="<?php echo (isset($_GET['status']) && $_GET['status'] === 'pending') ? 'true' : 'false'; ?>">
                Chờ xác nhận
            </button>
            <button onclick="filterOrders('confirmed')" 
               style="padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.3s; border: 2px solid #ddd; background: white; color: var(--text-dark); cursor: pointer; <?php echo (isset($_GET['status']) && $_GET['status'] === 'confirmed') ? 'background: #d1ecf1; color: #0c5460; border-color: #17a2b8;' : ''; ?>"
               onMouseOver="if (this.dataset.active !== 'true') this.style.borderColor='#17a2b8'"
               onMouseOut="if (this.dataset.active !== 'true') this.style.borderColor='#ddd'"
               data-active="<?php echo (isset($_GET['status']) && $_GET['status'] === 'confirmed') ? 'true' : 'false'; ?>">
                Đã xác nhận
            </button>
            <button onclick="filterOrders('shipping')" 
               style="padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.3s; border: 2px solid #ddd; background: white; color: var(--text-dark); cursor: pointer; <?php echo (isset($_GET['status']) && $_GET['status'] === 'shipping') ? 'background: #cfe2ff; color: #084298; border-color: #0d6efd;' : ''; ?>"
               onMouseOver="if (this.dataset.active !== 'true') this.style.borderColor='#0d6efd'"
               onMouseOut="if (this.dataset.active !== 'true') this.style.borderColor='#ddd'"
               data-active="<?php echo (isset($_GET['status']) && $_GET['status'] === 'shipping') ? 'true' : 'false'; ?>">
                Đang giao
            </button>
            <button onclick="filterOrders('delivered')" 
               style="padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.3s; border: 2px solid #ddd; background: white; color: var(--text-dark); cursor: pointer; <?php echo (isset($_GET['status']) && $_GET['status'] === 'delivered') ? 'background: #d1e7dd; color: #0f5132; border-color: #198754;' : ''; ?>"
               onMouseOver="if (this.dataset.active !== 'true') this.style.borderColor='#198754'"
               onMouseOut="if (this.dataset.active !== 'true') this.style.borderColor='#ddd'"
               data-active="<?php echo (isset($_GET['status']) && $_GET['status'] === 'delivered') ? 'true' : 'false'; ?>">
                Đã giao
            </button>
            <button onclick="filterOrders('cancelled')" 
               style="padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.3s; border: 2px solid #ddd; background: white; color: var(--text-dark); cursor: pointer; <?php echo (isset($_GET['status']) && $_GET['status'] === 'cancelled') ? 'background: #f8d7da; color: #842029; border-color: #dc3545;' : ''; ?>"
               onMouseOver="if (this.dataset.active !== 'true') this.style.borderColor='#dc3545'"
               onMouseOut="if (this.dataset.active !== 'true') this.style.borderColor='#ddd'"
               data-active="<?php echo (isset($_GET['status']) && $_GET['status'] === 'cancelled') ? 'true' : 'false'; ?>">
                Đã hủy
            </button>
        </div>
    </div>

    <div id="ordersContainer" style="margin-bottom: 20px;margin-top: 30px;">
        <?php if (empty($orders)): ?>
            <div style="background: white; padding: 40px; border-radius: 12px; text-align: center; border: 1px solid #e0e0e0;">
                <div style="font-size: 48px; margin-bottom: 15px;">🛒</div>
                <h3 style="margin: 0 0 10px 0; color: var(--text-dark);">Chưa có đơn hàng nào</h3>
                <p style="margin: 0 0 20px 0; color: #666;">Bạn chưa đặt hàng. Hãy khám phá bộ sưu tập pizza của chúng tôi!</p>
                <a href="<?php echo SITE_URL; ?>index.php?action=home" class="btn btn-primary" style="display: inline-block; text-decoration: none; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); color: white; padding: 12px 30px; border-radius: 6px; font-weight: 600;">
                    🍕 Mua sắm ngay
                </a>
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <?php foreach ($orders as $order): ?>
                    <div style="background: white; border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,51,0.05); transition: box-shadow 0.3s;" onMouseOver="this.style.boxShadow='0 4px 16px rgba(0,0,51,0.1)'" onMouseOut="this.style.boxShadow='0 2px 8px rgba(0,0,51,0.05)'">
                        <div style="display: grid; grid-template-columns: 1fr 2fr 1.5fr auto; gap: 20px; align-items: center;">
                            <!-- Order ID -->
                            <div>
                                <p style="margin: 0 0 4px 0; font-size: 12px; color: #999; text-transform: uppercase;">Mã đơn</p>
                                <p style="margin: 0; font-size: 18px; font-weight: 700; color: var(--primary-color);">#<?php echo $order['order_id']; ?></p>
                            </div>
                            
                            <!-- Order Info -->
                            <div>
                                <p style="margin: 0 0 4px 0; font-size: 12px; color: #999; text-transform: uppercase;">Ngày đặt</p>
                                <p style="margin: 0; font-size: 14px; color: var(--text-dark); font-weight: 600;"><?php echo date('d/m/Y H:i', strtotime($order['ngay_tao_order'])); ?></p>
                                <p style="margin: 4px 0 0 0; font-size: 12px; color: #666;">Người nhận: <?php echo htmlspecialchars($order['nguoi_nhan'] ?? '-'); ?></p>
                            </div>
                            
                            <!-- Amount & Status -->
                            <div>
                                <p style="margin: 0 0 4px 0; font-size: 12px; color: #999; text-transform: uppercase;">Tổng tiền</p>
                                <p style="margin: 0 0 6px 0; font-size: 18px; font-weight: 700; color: var(--primary-color);"><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?> đ</p>
                                <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; 
                                    <?php 
                                    $status = $order['trang_thai'];
                                    if ($status === 'Chờ xác nhận') echo 'background: #fff3cd; color: #856404;';
                                    elseif ($status === 'Đã xác nhận') echo 'background: #d1ecf1; color: #0c5460;';
                                    elseif ($status === 'Đang giao') echo 'background: #cfe2ff; color: #084298;';
                                    elseif ($status === 'Đã giao') echo 'background: #d1e7dd; color: #0f5132;';
                                    elseif ($status === 'Đã hủy') echo 'background: #f8d7da; color: #842029;';
                                    ?>">
                                    <?php echo htmlspecialchars($status); ?>
                                </span>
                            </div>
                            
                            <!-- Action Button -->
                            <div>
                                <a href="<?php echo SITE_URL; ?>index.php?action=order&method=detail&id=<?php echo $order['order_id']; ?>" 
                                   style="display: inline-block; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; transition: transform 0.2s;" 
                                   onMouseOver="this.style.transform='translateY(-2px)'" onMouseOut="this.style.transform='translateY(0)'">
                                    Xem chi tiết →
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div style="display: flex; justify-content: center; gap: 8px; margin-top: 30px;">
                    <?php if ($current_page > 1): ?>
                        <a href="<?php echo SITE_URL; ?>index.php?action=profile&method=history&page=<?php echo $current_page - 1; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>" 
                           style="padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; text-decoration: none; color: var(--text-dark); font-weight: 600; transition: all 0.2s;" 
                           onMouseOver="this.style.background='#f5f5f5'" onMouseOut="this.style.background='white'">← Trước</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="<?php echo SITE_URL; ?>index.php?action=profile&method=history&page=<?php echo $i; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>" 
                           style="padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.2s; <?php echo $i == $current_page ? 'background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%)); color: white; border-color: var(--primary-color);' : 'color: var(--text-dark);'; ?>" 
                           onMouseOver="<?php if ($i != $current_page): ?>this.style.background='#f5f5f5'<?php endif; ?>" onMouseOut="<?php if ($i != $current_page): ?>this.style.background='white'<?php endif; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                        <a href="<?php echo SITE_URL; ?>index.php?action=profile&method=history&page=<?php echo $current_page + 1; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>" 
                           style="padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; text-decoration: none; color: var(--text-dark); font-weight: 600; transition: all 0.2s;" 
                           onMouseOver="this.style.background='#f5f5f5'" onMouseOut="this.style.background='white'">Sau →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function filterOrders(status, page = 1) {
    // Update button states
    document.querySelectorAll('[onclick^="filterOrders"]').forEach(btn => {
        btn.dataset.active = 'false';
        btn.style.background = '';
        btn.style.color = '';
        btn.style.borderColor = '#ddd';
    });
    
    event.target.dataset.active = 'true';
    updateButtonStyle(event.target);

    // Load orders via AJAX
    fetch(`<?php echo SITE_URL; ?>index.php?action=profile&method=getOrders&status=${status}&page=${page}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('ordersContainer').innerHTML = html;
        })
        .catch(error => console.error('Error:', error));
}

function updateButtonStyle(btn) {
    const status = btn.textContent.trim();
    
    if (status.includes('Tất cả')) {
        btn.style.background = 'linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%))';
        btn.style.color = 'white';
        btn.style.borderColor = 'var(--primary-color)';
    } else if (status.includes('Chờ xác nhận')) {
        btn.style.background = '#fff3cd';
        btn.style.color = '#856404';
        btn.style.borderColor = '#ffc107';
    } else if (status.includes('Đã xác nhận')) {
        btn.style.background = '#d1ecf1';
        btn.style.color = '#0c5460';
        btn.style.borderColor = '#17a2b8';
    } else if (status.includes('Đang giao')) {
        btn.style.background = '#cfe2ff';
        btn.style.color = '#084298';
        btn.style.borderColor = '#0d6efd';
    } else if (status.includes('Đã giao')) {
        btn.style.background = '#d1e7dd';
        btn.style.color = '#0f5132';
        btn.style.borderColor = '#198754';
    } else if (status.includes('Đã hủy')) {
        btn.style.background = '#f8d7da';
        btn.style.color = '#842029';
        btn.style.borderColor = '#dc3545';
    }
}
</script>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
