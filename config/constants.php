<?php

define('SITE_URL', 'http://localhost:81/web-php-ban-pizza/public/');
define('ADMIN_URL', 'http://localhost:81/web-php-ban-pizza/public/index.php?action=admin');
define('ROOT_PATH', dirname(dirname(__FILE__)) . '/');
define('APP_PATH', ROOT_PATH . 'app/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');
define('UPLOAD_PATH', PUBLIC_PATH . 'uploads/');
define('ASSETS_PATH', PUBLIC_PATH . 'assets/');

// Status constants
define('ORDER_STATUS_PENDING', 'Chờ xác nhận');
define('ORDER_STATUS_CONFIRMED', 'Đã xác nhận');
define('ORDER_STATUS_SHIPPING', 'Đang giao');
define('ORDER_STATUS_DELIVERED', 'Đã giao');
define('ORDER_STATUS_CANCELLED', 'Đã hủy');

// User role constants
define('ROLE_CUSTOMER', 'customer');
define('ROLE_ADMIN', 'admin');
define('ROLE_STAFF', 'staff');

// Items per page
define('ITEMS_PER_PAGE', 12);
define('ADMIN_ITEMS_PER_PAGE', 10);
?>
