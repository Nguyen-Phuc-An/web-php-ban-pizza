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
    // Trang hồ sơ người dùng
    public function view()
    {
        if (!$this->isAuthenticated()) {
            $this->redirectToLogin();
        }
        
        $user = $this->userModel->read($this->user['user_id']);
        $data = ['user' => $user];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'updateProfile';
            if ($action === 'deleteAccount') {
                $this->deleteAccount($user);
                return;
            } elseif ($action === 'changePassword') {
                $this->changePassword($user);
            } else {
                $this->updateProfile($user);
            }
        }
        
        $this->render('profile/view', $data);
    }
    // Lịch sử đơn hàng với bộ lọc trạng thái và phân trang
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
    // Hàm phụ trợ để lấy đơn hàng theo trạng thái và đếm số lượng
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
    // Hàm phụ trợ để đếm số lượng đơn hàng theo trạng thái
    private function countOrdersByUserAndStatus($userId, $status)
    {
        $sql = "SELECT COUNT(*) as count FROM orders 
                WHERE nguoi_mua_id = ? AND trang_thai = ?";
        $stmt = $this->orderModel->query($sql, [$userId, $status]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
    // Cập nhật thông tin hồ sơ
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
    // Đổi mật khẩu
    private function changePassword($currentUser)
    {
        $currentPassword = $_POST['currentPassword'] ?? '';
        $newPassword = $_POST['newPassword'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';
        
        // Validation
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $this->jsonResponse(['success' => false, 'error' => 'Vui lòng điền tất cả các trường'], 400);
            return;
        }
        
        if (strlen($newPassword) < 6) {
            $this->jsonResponse(['success' => false, 'error' => 'Mật khẩu mới phải có ít nhất 6 ký tự'], 400);
            return;
        }
        
        if ($newPassword !== $confirmPassword) {
            $this->jsonResponse(['success' => false, 'error' => 'Mật khẩu xác nhận không trùng khớp'], 400);
            return;
        }
        
        // Verify current password
        if (!password_verify($currentPassword, $currentUser['mat_khau'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Mật khẩu hiện tại không đúng'], 401);
            return;
        }
        
        // Hash new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        // Update password
        $data = [
            'mat_khau' => $hashedPassword,
            'ngay_cap_nhap_user' => date('Y-m-d H:i:s')
        ];
        
        if ($this->userModel->update($this->user['user_id'], $data)) {
            $this->jsonResponse(['success' => true, 'message' => 'Đã cập nhật mật khẩu thành công']);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Lỗi cập nhật mật khẩu'], 500);
        }
    }
    // Xóa tài khoản (khóa tài khoản)
    private function deleteAccount($currentUser)
    {
        $password = $_POST['password'] ?? '';
        
        // Validation
        if (empty($password)) {
            $this->jsonResponse(['success' => false, 'error' => 'Vui lòng nhập mật khẩu'], 400);
            return;
        }
        
        // Verify password
        if (!password_verify($password, $currentUser['mat_khau'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Mật khẩu không đúng'], 401);
            return;
        }
        
        // Lock account (set trang_thai_tai_khoan to 'Khóa')
        $data = [
            'trang_thai_tai_khoan' => 'Khóa',
            'ngay_cap_nhap_user' => date('Y-m-d H:i:s')
        ];
        
        if ($this->userModel->update($this->user['user_id'], $data)) {
            // Log out the user by clearing session
            session_destroy();
            $this->jsonResponse(['success' => true, 'message' => 'Tài khoản đã được khóa thành công']);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Lỗi khi khóa tài khoản'], 500);
        }
    }
}
