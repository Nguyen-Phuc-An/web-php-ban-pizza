<?php

require_once APP_PATH . 'Models/Model.php';

class User extends Model
{
    protected $table = 'users';
    // Lấy tên khóa chính của bảng
    protected function getPrimaryKey()
    {
        return 'user_id';
    }
    // Tìm người dùng theo email
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email_user = ?";
        $stmt = $this->query($sql, [$email]);
        return $stmt->fetch();
    }
    // Lấy tất cả người dùng với phân trang
    public function getAllUsers($page = 1)
    {
        $offset = ($page - 1) * ADMIN_ITEMS_PER_PAGE;
        $sql = "SELECT * FROM {$this->table} 
                ORDER BY ngay_tao_user DESC 
                LIMIT ? OFFSET ?";
        $stmt = $this->query($sql, [ADMIN_ITEMS_PER_PAGE, $offset]);
        return $stmt->fetchAll();
    }
    // Đếm tổng số người dùng
    public function countUsers(): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->query($sql);
        $result = $stmt->fetch();
        return (int) $result['count'];
    }
    // Xác thực mật khẩu người dùng
    public function validatePassword($plain, $hashed)
    {
        // Sử dụng password_verify để so sánh BCRYPT
        return password_verify($plain, $hashed);
    }
    // Băm mật khẩu người dùng
    public function hashPassword($password)
    {
        // Hash bằng BCRYPT
        return password_hash($password, PASSWORD_BCRYPT);
    }
    // Cập nhật trạng thái tài khoản người dùng
    public function updateAccountStatus($userId, $status)
    {
        $sql = "UPDATE {$this->table} SET trang_thai_tai_khoan = ? WHERE user_id = ?";
        $stmt = $this->query($sql, [$status, $userId]);
        return $stmt !== false;
    }
}
?>
