<?php

class Database
{
    private static $connection = null;
    
    public static function connect()
    {
        if (self::$connection === null) {
            try {
                $config = [
                    'host' => 'localhost:3307',
                    'dbname' => 'web_ban_pizza',
                    'user' => 'root',
                    'password' => ''
                ];
                
                $dsn = "mysql:host=" . $config['host'] . ";dbname=" . $config['dbname'] . ";charset=utf8mb4";
                
                self::$connection = new PDO(
                    $dsn,
                    $config['user'],
                    $config['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                die("Connection Error: " . $e->getMessage());
            }
        }
        
        return self::$connection;
    }
}
?>
