<?php

require_once APP_PATH . 'Models/Model.php';

class Order extends Model
{
    protected $table = 'orders';
    // Lấy tên khóa chính của bảng
    protected function getPrimaryKey()
    {
        return 'order_id';
    }
    // Lấy đơn hàng theo user_id với phân trang
    public function getByUserId($userId, $page = 1)
    {
        $offset = ($page - 1) * ADMIN_ITEMS_PER_PAGE;
        $sql = "SELECT * FROM {$this->table} WHERE nguoi_mua_id = ? 
                ORDER BY ngay_tao_order DESC 
                LIMIT ? OFFSET ?";
        $stmt = $this->query($sql, [$userId, ADMIN_ITEMS_PER_PAGE, $offset]);
        return $stmt->fetchAll();
    }
    // Đếm tổng số đơn hàng theo user_id
    public function countByUserId($userId)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE nguoi_mua_id = ?";
        $stmt = $this->query($sql, [$userId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
    // Lấy tất cả đơn hàng với phân trang
    public function getAllOrders($page = 1)
    {
        $offset = ($page - 1) * ADMIN_ITEMS_PER_PAGE;
        $sql = "SELECT o.*, u.ten_nguoi_dung, u.so_dien_thoai_user, u.dia_chi 
                FROM {$this->table} o
                JOIN users u ON o.nguoi_mua_id = u.user_id
                ORDER BY 
                  CASE o.trang_thai 
                    WHEN 'Chờ xác nhận' THEN 0
                    WHEN 'Đã xác nhận' THEN 1
                    WHEN 'Đang giao' THEN 2
                    WHEN 'Đã giao' THEN 3
                    WHEN 'Đã hủy' THEN 4
                    ELSE 5
                  END,
                  o.ngay_tao_order DESC 
                LIMIT ? OFFSET ?";
        $stmt = $this->query($sql, [ADMIN_ITEMS_PER_PAGE, $offset]);
        return $stmt->fetchAll();
    }
    // Đếm tổng số đơn hàng
    public function countAll()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->query($sql);
        $result = $stmt->fetch();
        return $result['count'];
    }
    // Thống kê doanh thu
    public function getTotalRevenue()
    {
        $sql = "SELECT SUM(tong_tien) as total FROM {$this->table} 
                WHERE trang_thai NOT IN ('Đã hủy')";
        $stmt = $this->query($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    // Thống kê doanh thu theo tháng
    public function getRevenueByMonth($month, $year)
    {
        $sql = "SELECT SUM(tong_tien) as total FROM {$this->table} 
                WHERE MONTH(ngay_tao_order) = ? AND YEAR(ngay_tao_order) = ?
                AND trang_thai NOT IN ('Đã hủy')";
        $stmt = $this->query($sql, [$month, $year]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    // Lấy đơn hàng theo khách hàng
    public function getOrdersByCustomer($customerId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE nguoi_mua_id = ? 
                ORDER BY ngay_tao_order DESC";
        $stmt = $this->query($sql, [$customerId]);
        return $stmt->fetchAll();
    }
}
?>
