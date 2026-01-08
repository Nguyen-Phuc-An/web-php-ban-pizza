<?php

require_once APP_PATH . 'Models/Model.php';

class Contact extends Model
{
    protected $table = 'contacts';
    // Lấy tên khóa chính của bảng
    protected function getPrimaryKey()
    {
        return 'contact_id';
    }
    // Lấy tất cả liên hệ với phân trang
    public function getAllContacts($page = 1)
    {
        $offset = ($page - 1) * ADMIN_ITEMS_PER_PAGE;
        $sql = "SELECT * FROM {$this->table} 
                ORDER BY ngay_tao_contact DESC 
                LIMIT ? OFFSET ?";
        $stmt = $this->query($sql, [ADMIN_ITEMS_PER_PAGE, $offset]);
        return $stmt->fetchAll();
    }
    // Đếm tổng số liên hệ
    public function count()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->query($sql);
        $result = $stmt->fetch();
        return $result['count'];
    }
}
?>
