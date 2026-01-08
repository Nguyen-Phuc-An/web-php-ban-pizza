<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <h3>Menu Quản Trị</h3>
        <!-- Menu điều hướng admin -->
        <nav class="admin-menu">
            <ul>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=dashboard" class="menu-item active"><i class="bi bi-graph-up"></i> Dashboard</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=products" class="menu-item"><i class="bi bi-circle"></i> Sản phẩm</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=categories" class="menu-item"><i class="bi bi-folder"></i> Danh mục</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=orders" class="menu-item"><i class="bi bi-box"></i> Đơn hàng</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=customers" class="menu-item"><i class="bi bi-people"></i> Khách hàng</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=contacts" class="menu-item"><i class="bi bi-chat-dots"></i> Liên hệ</a></li>
            </ul>
        </nav>
    </aside>
    <!-- Nội dung chính của trang dashboard -->
    <main class="admin-content">
        <div class="container">            
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
                    <h3>Sản phẩm đang bán</h3>
                    <p class="stat-number"><?php echo isset($total_products) ? $total_products : 0; ?></p>
                </div>
            </div>
            
            <div class="chart-section">
                <h3>Doanh thu theo tháng</h3>
                <?php if (isset($monthly_revenue) && !empty($monthly_revenue)): ?>
                    <div style="position: relative; height: 400px; margin-top: 20px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                <?php else: ?>
                    <p>Chưa có dữ liệu doanh thu</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<!-- Chart.js Thư viện -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Biểu đồ doanh thu
    <?php if (isset($monthly_revenue) && !empty($monthly_revenue)): ?>
        const revenueLabels = <?php echo json_encode(array_keys($monthly_revenue)); ?>;
        const revenueData = <?php echo json_encode(array_values($monthly_revenue)); ?>;
        
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Doanh thu (đ)',
                    data: revenueData,
                    backgroundColor: 'var(--primary-color)',
                    borderColor: 'var(--primary-dark)',
                    borderWidth: 1,
                    borderRadius: 4,
                    hoverBackgroundColor: 'var(--primary-dark)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + ' đ';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toLocaleString('vi-VN') + ' đ';
                            }
                        }
                    }
                }
            }
        });
    <?php endif; ?>
</script>
