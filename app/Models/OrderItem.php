<?php

require_once APP_PATH . 'Models/Model.php';

class OrderItem extends Model
{
    protected $table = 'order_items';
    // Lấy tên khóa chính của bảng
    protected function getPrimaryKey()
    {
        return 'order_item_id';
    }
    // Lấy các mục đơn hàng theo order_id
    public function getByOrderId($orderId)
    {
        $sql = "SELECT oi.*, p.ten_product, p.hinh_anh_product 
                FROM {$this->table} oi
                JOIN products p ON oi.fk_product_id = p.product_id
                WHERE oi.fk_order_id = ?";
        $stmt = $this->query($sql, [$orderId]);
        return $stmt->fetchAll();
    }
}
?>
