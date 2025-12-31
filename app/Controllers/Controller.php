<?php

class Controller
{
    protected $page_title = '';
    protected $user = null;
    
    public function __construct()
    {
        $this->checkSession();
    }
    
    protected function render($view, $data = [])
    {
        extract($data);
        $viewPath = APP_PATH . 'Views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            die("View not found: " . $view);
        }
        
        include $viewPath;
    }
    
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
    
    protected function isAuthenticated()
    {
        return isset($_SESSION['user_id']) || isset($_SESSION['admin_id']);
    }
    
    protected function isAdmin()
    {
        return isset($_SESSION['admin_id']) && $_SESSION['is_admin'] === true;
    }
    
    protected function isAdminOnly()
    {
        return isset($_SESSION['admin_id']) && $_SESSION['is_admin'] === true;
    }
    
    protected function redirectToLogin()
    {
        header('Location: ' . SITE_URL . 'index.php?action=auth&method=login');
        exit;
    }
    
    protected function redirectToAdmin()
    {
        header('Location: ' . SITE_URL . 'index.php?action=admin&method=dashboard');
        exit;
    }
    
    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }
    
    protected function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function getSession($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }
    
    protected function setSession($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    protected function unsetSession($key)
    {
        unset($_SESSION[$key]);
    }
}
?>
