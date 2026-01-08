<?php

class Controller
{
    protected $page_title = '';
    protected $user = null;
    
    public function __construct()
    {
        $this->checkSession();
    }
    //Render view với dữ liệu
    protected function render($view, $data = [])
    {
        extract($data);
        $viewPath = APP_PATH . 'Views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            die("View not found: " . $view);
        }
        
        include $viewPath;
    }
    //Render một phần view không đầy đủ
    protected function renderPartial($view, $data = [])
    {
        extract($data);
        $viewPath = APP_PATH . 'Views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            die("View not found: " . $view);
        }
        
        ob_start();
        include $viewPath;
        return ob_get_clean();
    }
    //Kiểm tra phiên đăng nhập
    protected function checkSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['user_id'])) {
            // Load customer data from session
            $this->user = [
                'user_id' => $_SESSION['user_id'],
                'ten_nguoi_dung' => $_SESSION['ten_nguoi_dung'],
                'email_user' => $_SESSION['email_user'] ?? null,
                'is_customer' => true
            ];
        }
    }
    //Kiểm tra user đã đăng nhập
    protected function isAuthenticated()
    {
        return isset($_SESSION['user_id']) || isset($_SESSION['admin_id']);
    }
    //Kiểm tra quyền admin
    protected function isAdmin()
    {
        return isset($_SESSION['admin_id']) && $_SESSION['is_admin'] === true;
    }
    //Kiểm tra quyền admin mới được truy cập
    protected function isAdminOnly()
    {
        return isset($_SESSION['admin_id']) && $_SESSION['is_admin'] === true;
    }
    //Chuyển hướng đến trang đăng nhập
    protected function redirectToLogin()
    {
        header('Location: ' . SITE_URL . 'index.php?action=auth&method=login');
        exit;
    }
    //Chuyển hướng đến trang quản trị
    protected function redirectToAdmin()
    {
        header('Location: ' . SITE_URL . 'index.php?action=admin&method=dashboard');
        exit;
    }
    //Chuyển hướng đến URL cụ thể
    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }
    //Trả về dữ liệu JSON
    protected function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    //Lấy giá trị từ session
    protected function getSession($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }
    
    //Đặt giá trị cho session
    protected function setSession($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    //Xóa giá trị khỏi session
    protected function unsetSession($key)
    {
        unset($_SESSION[$key]);
    }
}
?>
