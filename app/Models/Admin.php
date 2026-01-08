<?php

require_once APP_PATH . 'Models/Model.php';

class Admin extends Model
{
    protected $table = 'admin';
    // Lấy tên khóa chính của bảng
    protected function getPrimaryKey()
    {
        return 'admin_id';
    }
    // Tìm admin theo email
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email_admin = ?";
        $stmt = $this->query($sql, [$email]);
        return $stmt->fetch();
    }
    // Xác thực mật khẩu admin
    public function validatePassword($plain, $hashed)
    {
        // Sử dụng MD5 như trong database
        return md5($plain) === $hashed;
    }
    // Băm mật khẩu admin
    public function hashPassword($password)
    {
        // Hash bằng MD5 để match với database
        return md5($password);
    }
}
?>
