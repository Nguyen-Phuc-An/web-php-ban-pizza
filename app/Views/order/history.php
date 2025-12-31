<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div class="container">
    <div class="order-history-section">
        <h2>Lịch Sử Đơn Hàng</h2>
        
        <?php if (empty($orders)): ?>
            <p class="empty-orders">Bạn chưa có đơn hàng nào. <a href="<?php echo SITE_URL; ?>index.php?action=product">Mua sắm ngay</a></p>
        <?php else: ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
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
                            <td><?php echo date('d/m/Y H:i', strtotime($order['ngay_tao_order'])); ?></td>
                            <td><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?>đ</td>
                            <td><span class="status"><?php echo htmlspecialchars($order['trang_thai']); ?></span></td>
                            <td><a href="<?php echo SITE_URL; ?>index.php?action=order&method=detail&id=<?php echo $order['order_id']; ?>" class="btn btn-small">Xem chi tiết</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($current_page > 1): ?>
                        <a href="<?php echo SITE_URL; ?>index.php?action=order&method=history&page=<?php echo $current_page - 1; ?>" class="page-link">← Trước</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="<?php echo SITE_URL; ?>index.php?action=order&method=history&page=<?php echo $i; ?>" class="page-link <?php echo $i == $current_page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                        <a href="<?php echo SITE_URL; ?>index.php?action=order&method=history&page=<?php echo $current_page + 1; ?>" class="page-link">Sau →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
