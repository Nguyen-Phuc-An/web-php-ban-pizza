<?php

require_once APP_PATH . 'Controllers/Controller.php';
require_once APP_PATH . 'Models/User.php';

class ProfileController extends Controller
{
    private $userModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }
    
    public function view()
    {
        if (!$this->isAuthenticated()) {
            $this->redirectToLogin();
        }
        
        $user = $this->userModel->read($this->user['user_id']);
        $data = ['user' => $user];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateProfile($user);
        }
        
        $this->render('profile/view', $data);
    }
    
    private function updateProfile($currentUser)
    {
        $data = [
            'ten_nguoi_dung' => $_POST['name'] ?? $currentUser['ten_nguoi_dung'],
            'so_dien_thoai_user' => $_POST['phone'] ?? $currentUser['so_dien_thoai_user'],
            'dia_chi' => $_POST['address'] ?? $currentUser['dia_chi'],
            'ngay_cap_nhap_user' => date('Y-m-d H:i:s')
        ];
        
        if ($this->userModel->update($this->user['user_id'], $data)) {
            $_SESSION['ten_nguoi_dung'] = $data['ten_nguoi_dung'];
            $_SESSION['success'] = 'Cập nhật thông tin thành công';
        } else {
            $_SESSION['error'] = 'Lỗi cập nhật thông tin';
        }
    }
}
?>
