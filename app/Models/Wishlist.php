<?php

require_once APP_PATH . 'Models/Model.php';

class Wishlist extends Model
{
    protected $table = 'wishlists';
    
    protected function getPrimaryKey()
    {
        return 'wishlist_id';
    }
    
    public function getByUserId($userId)
    {
        $sql = "SELECT w.*, p.* FROM {$this->table} w
                JOIN products p ON w.product_id = p.product_id
                WHERE w.nguoi_dung_id = ?
                ORDER BY w.tao_luc DESC";
        $stmt = $this->query($sql, [$userId]);
        return $stmt->fetchAll();
    }
    
    public function checkExists($userId, $productId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE nguoi_dung_id = ? AND product_id = ?";
        $stmt = $this->query($sql, [$userId, $productId]);
        return $stmt->fetch();
    }
    
    public function removeByProduct($userId, $productId)
    {
        $sql = "DELETE FROM {$this->table} WHERE nguoi_dung_id = ? AND product_id = ?";
        return $this->query($sql, [$userId, $productId])->execute();
    }
}
?>
