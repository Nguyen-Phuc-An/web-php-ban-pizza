<?php

require_once ROOT_PATH . 'config/Database.php';
require_once APP_PATH . 'Models/Model.php';

class Cart extends Model
{
    protected $table = 'shopping_cart';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getCartByUserId($userId)
    {
        $sql = "SELECT sc.*, p.ten_product, p.hinh_anh_product FROM {$this->table} sc 
                JOIN products p ON sc.product_id = p.product_id WHERE sc.user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function deleteCartByUserId($userId)
    {
        $sql = "DELETE FROM {$this->table} WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId]);
    }
    
    public function addCartItem($userId, $productId, $kichCo, $soLuong, $gia)
    {
        $sql = "INSERT INTO {$this->table} (user_id, product_id, kich_co, so_luong, gia) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId, $productId, $kichCo, $soLuong, $gia]);
    }
}
?>
