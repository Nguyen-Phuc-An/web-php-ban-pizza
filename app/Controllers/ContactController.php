<?php

require_once APP_PATH . 'Controllers/Controller.php';
require_once APP_PATH . 'Models/Contact.php';

class ContactController extends Controller
{
    private $contactModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->contactModel = new Contact();
    }
    // Trang liên hệ
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ten_contact' => $_POST['ten_contact'] ?? '',
                'email_contact' => $_POST['email_contact'] ?? '',
                'so_dien_thoai_contact' => $_POST['so_dien_thoai_contact'] ?? '',
                'noi_dung_contact' => $_POST['noi_dung_contact'] ?? ''
            ];
            
            if (empty($data['ten_contact']) || empty($data['email_contact']) || empty($data['noi_dung_contact'])) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
            } else {
                if ($this->contactModel->create($data)) {
                    $_SESSION['success'] = 'Tin nhắn của bạn đã được gửi. Chúng tôi sẽ liên hệ với bạn sớm nhất.';
                    $this->redirect(SITE_URL . 'index.php?action=contact');
                } else {
                    $_SESSION['error'] = 'Lỗi gửi tin nhắn. Vui lòng thử lại.';
                }
            }
        }
        
        $this->render('contact/index');
    }
}
?>
