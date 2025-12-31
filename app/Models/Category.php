<?php

require_once APP_PATH . 'Models/Model.php';

class Category extends Model
{
    protected $table = 'categories';
    
    protected function getPrimaryKey()
    {
        return 'categories_id';
    }
}
?>
