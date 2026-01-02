<?php

require_once APP_PATH . 'Controllers/Controller.php';
require_once APP_PATH . 'Models/Order.php';
require_once APP_PATH . 'Models/OrderItem.php';
require_once APP_PATH . 'Models/Product.php';

class CartController extends Controller
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
    
    public function view()
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        $data = ['cart' => $_SESSION['cart']];
        $this->render('cart/view', $data);
    }
    
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid method'], 400);
        }
        
        $productId = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;
        $size = $_POST['size'] ?? 'Vừa';
        $price = $_POST['price'] ?? null;
        
        if (!$productId) {
            $this->jsonResponse(['error' => 'Product ID is required'], 400);
        }
        
        // Get product details
        $product = $this->productModel->read($productId);
        if (!$product) {
            $this->jsonResponse(['error' => 'Product not found'], 404);
        }
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        $cartKey = $productId . '_' . $size;
        
        // Use adjusted price from frontend if provided, otherwise use base price
        $finalPrice = $price !== null ? intval($price) : $product['gia_product'];
        
        if (isset($_SESSION['cart'][$cartKey])) {
            $_SESSION['cart'][$cartKey]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$cartKey] = [
                'product_id' => $productId,
                'name' => $product['ten_product'],
                'price' => $finalPrice,
                'image' => $product['hinh_anh_product'],
                'quantity' => $quantity,
                'size' => $size
            ];
        }
        
        $this->jsonResponse(['success' => true, 'message' => 'Đã thêm vào giỏ hàng']);
    }
    
    public function remove()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid method'], 400);
        }
        
        $cartKey = $_POST['cart_key'] ?? null;
        
        if ($cartKey && isset($_SESSION['cart'][$cartKey])) {
            unset($_SESSION['cart'][$cartKey]);
        }
        
        $this->jsonResponse(['success' => true]);
    }
    
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid method'], 400);
        }
        
        $cartKey = $_POST['cart_key'] ?? null;
        $quantity = $_POST['quantity'] ?? 0;
        
        if ($cartKey && isset($_SESSION['cart'][$cartKey])) {
            if ($quantity > 0) {
                $_SESSION['cart'][$cartKey]['quantity'] = $quantity;
            } else {
                unset($_SESSION['cart'][$cartKey]);
            }
        }
        
        $this->jsonResponse(['success' => true]);
    }
    
    public function getTotal()
    {
        require_once APP_PATH . 'Models/Product.php';
        $productModel = new Product();
        
        $total = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $product = $productModel->read($item['product_id']);
                if ($product) {
                    $total += $product['gia_product'] * $item['quantity'];
                }
            }
        }
        
        return $total;
    }
    
    public function setSelected()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid method'], 400);
        }
        
        $selectedKeys = $_POST['selectedKeys'] ?? '[]';
        $selectedKeys = json_decode($selectedKeys, true);
        
        if (!is_array($selectedKeys)) {
            $this->jsonResponse(['error' => 'Invalid selected keys'], 400);
        }
        
        // Store selected items in session
        $_SESSION['selectedCartItems'] = $selectedKeys;
        
        $this->jsonResponse(['success' => true]);
    }
}
?>
