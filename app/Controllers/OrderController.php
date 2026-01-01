<?php

require_once APP_PATH . 'Controllers/Controller.php';
require_once APP_PATH . 'Models/Order.php';
require_once APP_PATH . 'Models/OrderItem.php';
require_once APP_PATH . 'Models/Product.php';

class OrderController extends Controller
{
    private $orderModel;
    private $orderItemModel;
    private $productModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new Order();
        $this->orderItemModel = new OrderItem();
        $this->productModel = new Product();
    }
    
    public function checkout()
    {
        if (!$this->isAuthenticated()) {
            $this->redirectToLogin();
        }
        
        if (empty($_SESSION['cart'])) {
            $_SESSION['error'] = 'Giỏ hàng trống';
            $this->redirect(SITE_URL . 'index.php?action=cart&method=view');
        }
        
        // Calculate total
        $total = 0;
        $cartItems = [];
        
        foreach ($_SESSION['cart'] as $cartKey => $item) {
            $product = $this->productModel->read($item['product_id']);
            if ($product) {
                $cartItem = [
                    'product_id' => $item['product_id'],
                    'product_name' => $product['ten_product'],
                    'price' => $product['gia_product'],
                    'image' => $product['hinh_anh_product'],
                    'quantity' => $item['quantity'],
                    'size' => $item['size']
                ];
                $cartItem['subtotal'] = $product['gia_product'] * $item['quantity'];
                $total += $cartItem['subtotal'];
                $cartItems[] = $cartItem;
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
        foreach ($cartItems as $cartKey => $item) {
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
        
        // Clear cart
        unset($_SESSION['cart']);
        
        $_SESSION['success'] = 'Đơn hàng đã được tạo thành công';
        $this->redirect(SITE_URL . 'index.php?action=order&method=history');
    }
    
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
}
?>
