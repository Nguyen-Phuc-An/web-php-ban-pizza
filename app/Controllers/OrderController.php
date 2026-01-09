<?php

require_once APP_PATH . 'Controllers/Controller.php';
require_once APP_PATH . 'Models/Order.php';
require_once APP_PATH . 'Models/OrderItem.php';
require_once APP_PATH . 'Models/Product.php';
require_once APP_PATH . 'Models/Cart.php';

class OrderController extends Controller
{
    private $orderModel;
    private $orderItemModel;
    private $productModel;
    private $cartModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new Order();
        $this->orderItemModel = new OrderItem();
        $this->productModel = new Product();
        $this->cartModel = new Cart();
    }
    // Trang thanh toán
    public function checkout()
    {
        if (!$this->isAuthenticated()) {
            $this->redirectToLogin();
        }
        
        if (empty($_SESSION['cart'])) {
            $_SESSION['error'] = 'Giỏ hàng trống';
            $this->redirect(SITE_URL . 'index.php?action=cart&method=view');
        }
        
        // Get selected items - must have at least one selected
        $selectedKeys = $_SESSION['selectedCartItems'] ?? [];
        
        if (empty($selectedKeys)) {
            $_SESSION['error'] = 'Vui lòng chọn ít nhất một sản phẩm để thanh toán';
            $this->redirect(SITE_URL . 'index.php?action=cart&method=view');
        }
        
        // Calculate total for selected items only
        $total = 0;
        $cartItems = [];
        
        foreach ($selectedKeys as $cartKey) {
            if (isset($_SESSION['cart'][$cartKey])) {
                $item = $_SESSION['cart'][$cartKey];
                $product = $this->productModel->read($item['product_id']);
                if ($product) {
                    // Use price from cart (adjusted by size) instead of base product price
                    $itemPrice = $item['price'] ?? $product['gia_product'];
                    $cartItem = [
                        'product_id' => $item['product_id'],
                        'product_name' => $product['ten_product'],
                        'price' => $itemPrice,
                        'image' => $product['hinh_anh_product'],
                        'quantity' => $item['quantity'],
                        'size' => $item['size']
                    ];
                    $cartItem['subtotal'] = $itemPrice * $item['quantity'];
                    $total += $cartItem['subtotal'];
                    $cartItems[] = $cartItem;
                }
            }
        }
        
        $data = [
            'cartItems' => $cartItems,
            'total' => $total,
            'shipping_fee' => 30000,
            'user' => $this->user ?? null
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCheckout($cartItems, $total);
        }
        
        $this->render('order/checkout', $data);
    }
    // Xử lý thanh toán + Tạo đơn hàng
    private function processCheckout($cartItems, $total)
    {
        $paymentMethod = $_POST['phuong_thuc_thanh_toan'] ?? 'Trực tiếp';
        $ten_nguoi_dung = $_POST['ten_nguoi_dung'] ?? '';
        $so_dien_thoai_user = $_POST['so_dien_thoai_user'] ?? '';
        $dia_chi = $_POST['dia_chi'] ?? '';
        
        // Create order with shipping information
        $orderData = [
            'nguoi_mua_id' => $this->user['user_id'],
            'tong_tien' => $total + 30000, // Include shipping fee
            'phuong_thuc_thanh_toan' => $paymentMethod,
            'trang_thai' => 'Chờ xác nhận',
            'ngay_tao_order' => date('Y-m-d H:i:s'),
            'nguoi_nhan' => $ten_nguoi_dung,
            'sdt_nguoi_nhan' => $so_dien_thoai_user,
            'dia-chi_nhan' => $dia_chi
        ];
        
        if (!$this->orderModel->create($orderData)) {
            $_SESSION['error'] = 'Lỗi tạo đơn hàng';
            return;
        }
        
        $orderId = $this->orderModel->getLastInsertId();
        
        // Create order items
        foreach ($cartItems as $item) {
            $itemData = [
                'fk_order_id' => $orderId,
                'fk_product_id' => $item['product_id'],
                'so_luong_mua' => $item['quantity'],
                'size' => $item['size'],
                'gia_order_items' => $item['price'],
                'ngay_dat' => date('Y-m-d H:i:s')
            ];
            
            $this->orderItemModel->create($itemData);
        }
        
        // Remove purchased items from session cart
        if (isset($_SESSION['selectedCartItems'])) {
            foreach ($_SESSION['selectedCartItems'] as $cartKey) {
                if (isset($_SESSION['cart'][$cartKey])) {
                    unset($_SESSION['cart'][$cartKey]);
                }
            }
            unset($_SESSION['selectedCartItems']);
        }
        
        // If cart is empty, clear it completely
        if (empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
        
        // Clear cart from database
        $this->cartModel->deleteCartByUserId($this->user['user_id']);
        
        $_SESSION['success'] = 'Đơn hàng đã được tạo thành công';
        
        // Redirect based on payment method
        if ($paymentMethod === 'Chuyển khoản') {
            $this->redirect(SITE_URL . 'index.php?action=order&method=success&id=' . $orderId);
        } else {
            $this->redirect(SITE_URL . 'index.php?action=order&method=history');
        }
    }
    // Lịch sử đơn hàng
    public function history()
    {
        if (!$this->isAuthenticated()) {
            $this->redirectToLogin();
        }
        
        $page = $_GET['page'] ?? 1;
        $orders = $this->orderModel->getByUserId($this->user['user_id'], $page);
        $total = $this->orderModel->countByUserId($this->user['user_id']);
        $totalPages = ceil($total / ADMIN_ITEMS_PER_PAGE);
        
        $data = [
            'orders' => $orders,
            'current_page' => $page,
            'total_pages' => $totalPages
        ];
        
        $this->render('order/history', $data);
    }
    // Chi tiết đơn hàng
    public function detail()
    {
        if (!$this->isAuthenticated()) {
            $this->redirectToLogin();
        }
        
        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            $this->redirect(SITE_URL . 'index.php?action=order&method=history');
        }
        
        $order = $this->orderModel->read($orderId);
        
        if (!$order || $order['nguoi_mua_id'] != $this->user['user_id']) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            $this->redirect(SITE_URL . 'index.php?action=order&method=history');
        }
        
        $items = $this->orderItemModel->getByOrderId($orderId);
        
        $data = [
            'order' => $order,
            'items' => $items
        ];
        
        $this->render('order/detail', $data);
    }
    // Chi tiết đơn hàng thành công
    public function success()
    {
        if (!$this->isAuthenticated()) {
            $this->redirectToLogin();
        }
        
        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            $this->redirect(SITE_URL . 'index.php?action=order&method=history');
            return;
        }
        
        $order = $this->orderModel->read($orderId);
        if (!$order || $order['nguoi_mua_id'] != $this->user['user_id']) {
            $this->redirect(SITE_URL . 'index.php?action=order&method=history');
            return;
        }
        
        $items = $this->orderItemModel->getByOrderId($orderId);
        
        $data = [
            'order' => $order,
            'items' => $items
        ];
        
        $this->render('order/success', $data);
    }
    // Hủy đơn hàng
    public function cancel()
    {
        if (!$this->isAuthenticated()) {
            $this->jsonResponse(['success' => false, 'error' => 'Vui lòng đăng nhập'], 401);
            return;
        }
        
        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            $this->jsonResponse(['success' => false, 'error' => 'ID đơn hàng không hợp lệ'], 400);
            return;
        }
        
        $order = $this->orderModel->read($orderId);
        
        // Check if order exists and belongs to user
        if (!$order || $order['nguoi_mua_id'] != $this->user['user_id']) {
            $this->jsonResponse(['success' => false, 'error' => 'Đơn hàng không tồn tại'], 404);
            return;
        }
        
        // Check if order is still pending
        if ($order['trang_thai'] !== 'Chờ xác nhận') {
            $this->jsonResponse(['success' => false, 'error' => 'Chỉ có thể hủy đơn hàng ở trạng thái "Chờ xác nhận"'], 400);
            return;
        }
        
        // Update order status to cancelled
        $updateData = ['trang_thai' => 'Đã hủy'];
        if ($this->orderModel->update($orderId, $updateData)) {
            $this->jsonResponse(['success' => true, 'message' => 'Đơn hàng đã được hủy thành công']);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Lỗi khi hủy đơn hàng'], 500);
        }
    }
}
?>
