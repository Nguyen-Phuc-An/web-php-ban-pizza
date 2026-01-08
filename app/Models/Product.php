<?php

require_once APP_PATH . 'Models/Model.php';

class Product extends Model
{
    protected $table = 'products';
    // Lấy tên khóa chính của bảng
    protected function getPrimaryKey()
    {
        return 'product_id';
    }
    // Lấy tất cả sản phẩm theo danh mục với phân trang
    public function getAllByCategory($categoryId, $page = 1)
    {
        $offset = ($page - 1) * ITEMS_PER_PAGE;
        $sql = "SELECT * FROM {$this->table} WHERE danh_muc_product = ? 
                ORDER BY ngay_tao_product DESC 
                LIMIT ? OFFSET ?";
        $stmt = $this->query($sql, [$categoryId, ITEMS_PER_PAGE, $offset]);
        return $stmt->fetchAll();
    }
    // Đếm tổng số sản phẩm theo danh mục
    public function countByCategory($categoryId): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE danh_muc_product = ?";
        $stmt = $this->query($sql, [$categoryId]);
        $result = $stmt->fetch();
        return (int)$result['count'];
    }
    // Lấy tất cả sản phẩm với phân trang   
    public function getAll($page = 1)
    {
        $offset = ($page - 1) * ITEMS_PER_PAGE;
        $sql = "SELECT * FROM {$this->table} 
                ORDER BY ngay_tao_product DESC 
                LIMIT ? OFFSET ?";
        $stmt = $this->query($sql, [ITEMS_PER_PAGE, $offset]);
        return $stmt->fetchAll();
    }
    // Đếm tổng số sản phẩm
    public function count(): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->query($sql);
        $result = $stmt->fetch();
        return (int)$result['count'];
    }
    // Tìm kiếm sản phẩm theo từ khóa
    public function search($keyword)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE ten_product LIKE ? OR mo_ta_product LIKE ?
                ORDER BY ngay_tao_product DESC";
        $search = '%' . $keyword . '%';
        $stmt = $this->query($sql, [$search, $search]);
        return $stmt->fetchAll();
    }
    // Lấy sản phẩm theo danh mục hoặc sub-category
    public function getByCategory($categoryId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE danh_muc_product = ? OR sub_category_id = ?
                ORDER BY ngay_tao_product DESC";
        $stmt = $this->query($sql, [$categoryId, $categoryId]);
        return $stmt->fetchAll();
    }
}
?>
