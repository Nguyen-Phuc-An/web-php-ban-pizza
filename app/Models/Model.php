<?php

require_once ROOT_PATH . 'config/Database.php';

abstract class Model
{
    protected $db;
    protected $table;
    // Khởi tạo kết nối cơ sở dữ liệu
    public function __construct()
    {
        $this->db = Database::connect();
    }
    // Tạo bản ghi mới
    public function create($data)
    {
        $columns = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($columns), '?');
        
        // Wrap column names in backticks to handle special characters
        $quotedColumns = array_map(function($col) {
            return "`{$col}`";
        }, $columns);
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $quotedColumns) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }
    // Đọc bản ghi theo ID
    public function read($id)
    {
        $pk = $this->getPrimaryKey();
        $sql = "SELECT * FROM {$this->table} WHERE {$pk} = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    // Đọc tất cả bản ghi
    public function readAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    // Cập nhật bản ghi theo ID
    public function update($id, $data)
    {
        $columns = array_keys($data);
        $values = array_values($data);
        $pk = $this->getPrimaryKey();
        
        $setClause = implode(' = ?, ', $columns) . ' = ?';
        $values[] = $id;
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$pk} = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }
    // Xóa bản ghi theo ID
    public function delete($id)
    {
        $pk = $this->getPrimaryKey();
        $sql = "DELETE FROM {$this->table} WHERE {$pk} = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    // Thực thi truy vấn tùy chỉnh
    public function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    // Lấy ID của bản ghi mới chèn
    public function getLastInsertId()
    {
        return $this->db->lastInsertId();
    }
    // Lấy tên khóa chính của bảng
    protected function getPrimaryKey()
    {
        // Override this method in child classes if needed
        return 'id';
    }
}
?>
