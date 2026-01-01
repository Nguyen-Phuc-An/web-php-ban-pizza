<?php

require_once ROOT_PATH . 'config/Database.php';

abstract class Model
{
    protected $db;
    protected $table;
    
    public function __construct()
    {
        $this->db = Database::connect();
    }
    
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
    
    public function read($id)
    {
        $pk = $this->getPrimaryKey();
        $sql = "SELECT * FROM {$this->table} WHERE {$pk} = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function readAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
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
    
    public function delete($id)
    {
        $pk = $this->getPrimaryKey();
        $sql = "DELETE FROM {$this->table} WHERE {$pk} = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    public function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function getLastInsertId()
    {
        return $this->db->lastInsertId();
    }
    
    protected function getPrimaryKey()
    {
        // Override this method in child classes if needed
        return 'id';
    }
}
?>
