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

    public function getOrders()
    {
        if (!$this->isAuthenticated()) {
            http_response_code(401);
            return;
        }

        $status = $_GET['status'] ?? 'all';
        $page = $_GET['page'] ?? 1;
        
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
        
        // Generate HTML
        ob_start();
        
        if (empty($orders)): ?>
            <div style="background: white; padding: 40px; border-radius: 12px; text-align: center; border: 1px solid #e0e0e0;">
                <div style="font-size: 48px; margin-bottom: 15px;"><i class="bi bi-cart-dash" style="color: #666; font-size: 48px;"></i></div>
                <h3 style="margin: 0 0 10px 0; color: var(--text-dark);">Chưa có đơn hàng nào</h3>
                <p style="margin: 0 0 20px 0; color: #666;">Bạn chưa đặt hàng. Hãy khám phá bộ sưu tập pizza của chúng tôi!</p>
                <a href="<?php echo SITE_URL; ?>index.php?action=home" class="btn btn-primary" style="display: inline-block; text-decoration: none; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%)); color: white; padding: 12px 30px; border-radius: 6px; font-weight: 600;">
                    <i class="bi bi-circle"></i> Mua sắm ngay
                </a>
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <?php foreach ($orders as $order): ?>
                    <div style="background: white; border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,51,0.05); transition: box-shadow 0.3s;" onMouseOver="this.style.boxShadow='0 4px 16px rgba(0,0,51,0.1)'" onMouseOut="this.style.boxShadow='0 2px 8px rgba(0,0,51,0.05)'">
                        <div style="display: grid; grid-template-columns: 1fr 2fr 1.5fr auto; gap: 20px; align-items: center;">
                            <!-- Order ID -->
                            <div>
                                <p style="margin: 0 0 4px 0; font-size: 12px; color: #999; text-transform: uppercase;">Mã đơn</p>
                                <p style="margin: 0; font-size: 18px; font-weight: 700; color: var(--primary-color);">#<?php echo $order['order_id']; ?></p>
                            </div>
                            
                            <!-- Order Info -->
                            <div>
                                <p style="margin: 0 0 4px 0; font-size: 12px; color: #999; text-transform: uppercase;">Ngày đặt</p>
                                <p style="margin: 0; font-size: 14px; color: var(--text-dark); font-weight: 600;"><?php echo date('d/m/Y H:i', strtotime($order['ngay_tao_order'])); ?></p>
                                <p style="margin: 4px 0 0 0; font-size: 12px; color: #666;">Người nhận: <?php echo htmlspecialchars($order['nguoi_nhan'] ?? '-'); ?></p>
                            </div>
                            
                            <!-- Amount & Status -->
                            <div>
                                <p style="margin: 0 0 4px 0; font-size: 12px; color: #999; text-transform: uppercase;">Tổng tiền</p>
                                <p style="margin: 0 0 6px 0; font-size: 18px; font-weight: 700; color: var(--primary-color);"><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?> đ</p>
                                <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; 
                                    <?php 
                                    $orderStatus = $order['trang_thai'];
                                    if ($orderStatus === 'Chờ xác nhận') echo 'background: #fff3cd; color: #856404;';
                                    elseif ($orderStatus === 'Đã xác nhận') echo 'background: #d1ecf1; color: #0c5460;';
                                    elseif ($orderStatus === 'Đang giao') echo 'background: #cfe2ff; color: #084298;';
                                    elseif ($orderStatus === 'Đã giao') echo 'background: #d1e7dd; color: #0f5132;';
                                    elseif ($orderStatus === 'Đã hủy') echo 'background: #f8d7da; color: #842029;';
                                    ?>">
                                    <?php echo htmlspecialchars($orderStatus); ?>
                                </span>
                            </div>
                            
                            <!-- Action Button -->
                            <div>
                                <a href="<?php echo SITE_URL; ?>index.php?action=order&method=detail&id=<?php echo $order['order_id']; ?>" 
                                   style="display: inline-block; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%)); color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; transition: transform 0.2s;" 
                                   onMouseOver="this.style.transform='translateY(-2px)'" onMouseOut="this.style.transform='translateY(0)'">
                                    Xem chi tiết →
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div style="display: flex; justify-content: center; gap: 8px; margin-top: 30px;">
                    <?php if ($page > 1): ?>
                        <a href="#" onclick="filterOrders('<?php echo $status; ?>', <?php echo $page - 1; ?>); return false;" 
                           style="padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; text-decoration: none; color: var(--text-dark); font-weight: 600; transition: all 0.2s;" 
                           onMouseOver="this.style.background='#f5f5f5'" onMouseOut="this.style.background='white'">← Trước</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="#" onclick="filterOrders('<?php echo $status; ?>', <?php echo $i; ?>); return false;" 
                           style="padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.2s; <?php echo $i == $page ? 'background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%)); color: white; border-color: var(--primary-color);' : 'color: var(--text-dark);'; ?>" 
                           onMouseOver="<?php if ($i != $page): ?>this.style.background='#f5f5f5'<?php endif; ?>" onMouseOut="<?php if ($i != $page): ?>this.style.background='white'<?php endif; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="#" onclick="filterOrders('<?php echo $status; ?>', <?php echo $page + 1; ?>); return false;" 
                           style="padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; text-decoration: none; color: var(--text-dark); font-weight: 600; transition: all 0.2s;" 
                           onMouseOver="this.style.background='#f5f5f5'" onMouseOut="this.style.background='white'">Sau →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif;
        
        $html = ob_get_clean();
        echo $html;
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
