<?php

require_once APP_PATH . 'Controllers/Controller.php';
require_once APP_PATH . 'Models/User.php';
require_once APP_PATH . 'Models/Order.php';

class ProfileController extends Controller
{
    private $userModel;
    private $orderModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->orderModel = new Order();
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

    public function history()
    {
        if (!$this->isAuthenticated()) {
            $this->redirectToLogin();
        }

        $page = $_GET['page'] ?? 1;
        $status = $_GET['status'] ?? 'all';
        
        // Map status filter to database values
        $statusMap = [
            'all' => null,
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao',
            'delivered' => 'Đã giao',
            'cancelled' => 'Đã hủy'
        ];
        
        $dbStatus = $statusMap[$status] ?? null;
        
        // Get filtered orders
        if ($dbStatus) {
            $orders = $this->getOrdersByUserAndStatus($this->user['user_id'], $dbStatus, $page);
            $total = $this->countOrdersByUserAndStatus($this->user['user_id'], $dbStatus);
        } else {
            $orders = $this->orderModel->getByUserId($this->user['user_id'], $page);
            $total = $this->orderModel->countByUserId($this->user['user_id']);
        }
        
        $totalPages = ceil($total / ADMIN_ITEMS_PER_PAGE);
        
        $data = [
            'orders' => $orders,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'current_status' => $status
        ];
        
        $this->render('profile/history', $data);
    }

    private function getOrdersByUserAndStatus($userId, $status, $page = 1)
    {
        $offset = ($page - 1) * ADMIN_ITEMS_PER_PAGE;
        $sql = "SELECT * FROM orders 
                WHERE nguoi_mua_id = ? AND trang_thai = ? 
                ORDER BY ngay_tao_order DESC 
                LIMIT ? OFFSET ?";
        $stmt = $this->orderModel->query($sql, [$userId, $status, ADMIN_ITEMS_PER_PAGE, $offset]);
        return $stmt->fetchAll();
    }

    private function countOrdersByUserAndStatus($userId, $status)
    {
        $sql = "SELECT COUNT(*) as count FROM orders 
                WHERE nguoi_mua_id = ? AND trang_thai = ?";
        $stmt = $this->orderModel->query($sql, [$userId, $status]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
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
