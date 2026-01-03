<?php

require_once APP_PATH . 'Models/Model.php';

class Product extends Model
{
    protected $table = 'products';
    
    protected function getPrimaryKey()
    {
        return 'product_id';
    }
    
    public function getAllByCategory($categoryId, $page = 1)
    {
        $offset = ($page - 1) * ITEMS_PER_PAGE;
        $sql = "SELECT * FROM {$this->table} WHERE danh_muc_product = ? 
                ORDER BY ngay_tao_product DESC 
                LIMIT ? OFFSET ?";
        $stmt = $this->query($sql, [$categoryId, ITEMS_PER_PAGE, $offset]);
        return $stmt->fetchAll();
    }
    
    public function countByCategory($categoryId): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE danh_muc_product = ?";
        $stmt = $this->query($sql, [$categoryId]);
        $result = $stmt->fetch();
        return (int)$result['count'];
    }
    
    public function getAll($page = 1)
    {
        $offset = ($page - 1) * ITEMS_PER_PAGE;
        $sql = "SELECT * FROM {$this->table} 
                ORDER BY ngay_tao_product DESC 
                LIMIT ? OFFSET ?";
        $stmt = $this->query($sql, [ITEMS_PER_PAGE, $offset]);
        return $stmt->fetchAll();
    }
    
    public function count(): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->query($sql);
        $result = $stmt->fetch();
        return (int)$result['count'];
    }
    
    public function search($keyword)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE ten_product LIKE ? OR mo_ta_product LIKE ?
                ORDER BY ngay_tao_product DESC";
        $search = '%' . $keyword . '%';
        $stmt = $this->query($sql, [$search, $search]);
        return $stmt->fetchAll();
    }
    
    public function getByCategory($categoryId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE danh_muc_product = ? OR sub_category_id = ?
                ORDER BY ngay_tao_product DESC";
        $stmt = $this->query($sql, [$categoryId, $categoryId]);
        return $stmt->fetchAll();
    }
}
?>
