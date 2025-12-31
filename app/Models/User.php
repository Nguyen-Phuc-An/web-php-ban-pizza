<?php

require_once APP_PATH . 'Models/Model.php';

class User extends Model
{
    protected $table = 'users';
    
    protected function getPrimaryKey()
    {
        return 'user_id';
    }
    
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email_user = ?";
        $stmt = $this->query($sql, [$email]);
        return $stmt->fetch();
    }
    
    public function getAllUsers($page = 1)
    {
        $offset = ($page - 1) * ADMIN_ITEMS_PER_PAGE;
        $sql = "SELECT * FROM {$this->table} 
                ORDER BY ngay_tao_user DESC 
                LIMIT ? OFFSET ?";
        $stmt = $this->query($sql, [ADMIN_ITEMS_PER_PAGE, $offset]);
        return $stmt->fetchAll();
    }
    
    public function countUsers(): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->query($sql);
        $result = $stmt->fetch();
        return (int) $result['count'];
    }
    
    public function validatePassword($plain, $hashed)
    {
        // Sử dụng password_verify để so sánh BCRYPT
        return password_verify($plain, $hashed);
    }
    
    public function hashPassword($password)
    {
        // Hash bằng BCRYPT
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
?>
