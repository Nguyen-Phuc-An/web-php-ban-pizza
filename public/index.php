<?php

require_once dirname(dirname(__FILE__)) . '/config/constants.php';
require_once dirname(dirname(__FILE__)) . '/config/Database.php';

// Initialize database connection
Database::connect();

// Route handling
$action = $_GET['action'] ?? 'home';
$method = $_GET['method'] ?? 'index';

try {
    switch ($action) {
        case 'auth':
            require_once APP_PATH . 'Controllers/AuthController.php';
            $controller = new AuthController();
            if (method_exists($controller, $method)) {
                $controller->$method();
            }
            break;
            
        case 'product':
            require_once APP_PATH . 'Controllers/ProductController.php';
            $controller = new ProductController();
            if (method_exists($controller, $method)) {
                $controller->$method();
            }
            break;
            
        case 'cart':
            require_once APP_PATH . 'Controllers/CartController.php';
            $controller = new CartController();
            if (method_exists($controller, $method)) {
                $controller->$method();
            }
            break;
            
        case 'order':
            require_once APP_PATH . 'Controllers/OrderController.php';
            $controller = new OrderController();
            if (method_exists($controller, $method)) {
                $controller->$method();
            }
            break;
            
        case 'profile':
            require_once APP_PATH . 'Controllers/ProfileController.php';
            $controller = new ProfileController();
            if (method_exists($controller, $method)) {
                $controller->$method();
            }
            break;
            
        case 'wishlist':
            require_once APP_PATH . 'Controllers/WishlistController.php';
            $controller = new WishlistController();
            if (method_exists($controller, $method)) {
                $controller->$method();
            }
            break;
            
        case 'contact':
            require_once APP_PATH . 'Controllers/ContactController.php';
            $controller = new ContactController();
            if (method_exists($controller, $method)) {
                $controller->$method();
            }
            break;
            
        case 'admin':
            require_once APP_PATH . 'Controllers/AdminController.php';
            $controller = new AdminController();
            if (method_exists($controller, $method)) {
                $controller->$method();
            }
            break;
            
        case 'home':
            require_once APP_PATH . 'Controllers/HomeController.php';
            $controller = new HomeController();
            if (method_exists($controller, $method)) {
                $controller->$method();
            } else {
                $controller->index();
            }
            break;
            
        default:
            require_once APP_PATH . 'Controllers/HomeController.php';
            $controller = new HomeController();
            $controller->index();
            break;
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
