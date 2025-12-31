<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <h3>Menu Quản Trị</h3>
        <nav class="admin-menu">
            <ul>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=dashboard" class="menu-item active">📊 Dashboard</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=products" class="menu-item">🍕 Sản phẩm</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=categories" class="menu-item">📁 Danh mục</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=orders" class="menu-item">📦 Đơn hàng</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=customers" class="menu-item">👥 Khách hàng</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=contacts" class="menu-item">💬 Liên hệ</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=auth&method=logout" class="menu-item">🚪 Đăng xuất</a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="admin-content">
        <div class="container">
            <h2>Dashboard - Thống Kê</h2>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Tổng đơn hàng</h3>
                    <p class="stat-number"><?php echo isset($total_orders) ? $total_orders : 0; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Tổng khách hàng</h3>
                    <p class="stat-number"><?php echo isset($total_customers) ? $total_customers : 0; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Tổng doanh thu</h3>
                    <p class="stat-number"><?php echo isset($total_revenue) ? number_format($total_revenue, 0, ',', '.') : 0; ?> đ</p>
                </div>
                <div class="stat-card">
                    <h3>Sản phẩm trong kho</h3>
                    <p class="stat-number"><?php echo isset($total_products) ? $total_products : 0; ?></p>
                </div>
            </div>
            
            <div class="chart-section">
                <h3>Doanh thu theo tháng</h3>
                <?php if (isset($monthly_revenue) && !empty($monthly_revenue)): ?>
                    <table class="revenue-table">
                        <thead>
                            <tr>
                                <th>Tháng</th>
                                <th>Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($monthly_revenue as $month => $revenue): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($month); ?></td>
                                    <td><?php echo number_format($revenue, 0, ',', '.'); ?> đ</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Chưa có dữ liệu doanh thu</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
