<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div class="container">
    <div class="order-detail-section">
        <h2>Chi Tiết Đơn Hàng #<?php echo $order['order_id']; ?></h2>
        
        <div class="order-info">
            <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['ngay_tao_order'])); ?></p>
            <p><strong>Trạng thái:</strong> <span class="status"><?php echo htmlspecialchars($order['trang_thai']); ?></span></p>
            <p><strong>Phương thức thanh toán:</strong> <?php echo htmlspecialchars($order['phuong_thuc_thanh_toan']); ?></p>
        </div>
        
        <h3>Danh sách sản phẩm</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Size</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['ten_product']); ?></td>
                        <td><?php echo htmlspecialchars($item['size_pizza']); ?></td>
                        <td><?php echo number_format($item['gia_order_items'], 0, ',', '.'); ?>đ</td>
                        <td><?php echo $item['so_luong_mua']; ?></td>
                        <td><?php echo number_format($item['gia_order_items'] * $item['so_luong_mua'], 0, ',', '.'); ?>đ</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="order-total">
            <h3>Tổng tiền: <?php echo number_format($order['tong_tien'], 0, ',', '.'); ?>đ</h3>
        </div>
        
        <a href="<?php echo SITE_URL; ?>index.php?action=order&method=history" class="btn btn-secondary">Quay lại</a>
    </div>
</div>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
