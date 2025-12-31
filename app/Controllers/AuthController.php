<?php

require_once APP_PATH . 'Controllers/Controller.php';
require_once APP_PATH . 'Models/User.php';
require_once APP_PATH . 'Models/Admin.php';

class AuthController extends Controller
{
    private $userModel;
    private $adminModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->adminModel = new Admin();
    }
    
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Kiểm tra Admin trước
            $admin = $this->adminModel->findByEmail($email);
            if ($admin && $this->adminModel->validatePassword($password, $admin['mat_khau_admin'])) {
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['email_admin'] = $admin['email_admin'];
                $_SESSION['is_admin'] = true;
                $this->redirect(SITE_URL . 'index.php?action=admin&method=dashboard');
                return;
            }
            
            // Kiểm tra Customer
            $user = $this->userModel->findByEmail($email);
            if ($user && $this->userModel->validatePassword($password, $user['mat_khau'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['ten_nguoi_dung'] = $user['ten_nguoi_dung'];
                $_SESSION['email_user'] = $user['email_user'];
                $_SESSION['is_customer'] = true;
                $this->redirect(SITE_URL . 'index.php?action=home');
                return;
            }
            
            $_SESSION['error'] = 'Email hoặc mật khẩu không đúng';
        }
        
        $this->render('auth/login');
    }
    
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ten_nguoi_dung' => $_POST['name'] ?? '',
                'email_user' => $_POST['email'] ?? '',
                'mat_khau' => $this->userModel->hashPassword($_POST['password'] ?? ''),
                'so_dien_thoai_user' => $_POST['phone'] ?? '',
                'dia_chi' => $_POST['address'] ?? ''
            ];
            
            // Validate
            if (empty($data['ten_nguoi_dung']) || empty($data['email_user']) || empty($_POST['password'])) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
                $this->render('auth/register');
                return;
            }
            
            // Check if email exists
            if ($this->userModel->findByEmail($data['email_user'])) {
                $_SESSION['error'] = 'Email đã được đăng ký';
                $this->render('auth/register');
                return;
            }
            
            if ($this->userModel->create($data)) {
                $_SESSION['success'] = 'Đăng ký thành công. Vui lòng đăng nhập.';
                $this->redirect(SITE_URL . 'index.php?action=auth&method=login');
            } else {
                $_SESSION['error'] = 'Lỗi đăng ký. Vui lòng thử lại.';
            }
        }
        
        $this->render('auth/register');
    }
    
    public function logout()
    {
        session_destroy();
        $this->redirect(SITE_URL . 'index.php?action=home');
    }
}
?>
