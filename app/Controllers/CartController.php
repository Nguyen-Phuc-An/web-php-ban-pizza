<?php

require_once APP_PATH . 'Controllers/Controller.php';
require_once APP_PATH . 'Models/Order.php';
require_once APP_PATH . 'Models/OrderItem.php';
require_once APP_PATH . 'Models/Product.php';
require_once APP_PATH . 'Models/Cart.php';

class CartController extends Controller
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
    
    public function view()
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Load cart from database if user is logged in
        if (isset($_SESSION['user_id'])) {
            $this->loadCartFromDatabase($_SESSION['user_id']);
        }
        
        $data = ['cart' => $_SESSION['cart']];
        $this->render('cart/view', $data);
    }
    
    private function loadCartFromDatabase($userId)
    {
        $cartItems = $this->cartModel->getCartByUserId($userId);
        
        $_SESSION['cart'] = [];
        foreach ($cartItems as $item) {
            $cartKey = $item['product_id'] . '_' . ($item['kich_co'] ?? 'Vừa');
            $_SESSION['cart'][$cartKey] = [
                'product_id' => $item['product_id'],
                'name' => $item['ten_product'] ?? '',
                'price' => $item['gia'],
                'image' => $item['hinh_anh_product'] ?? '',
                'quantity' => $item['so_luong'],
                'size' => $item['kich_co'] ?? 'Vừa'
            ];
        }
    }
    
    private function saveCartToDatabase($userId)
    {
        // Clear existing cart for this user
        $this->cartModel->deleteCartByUserId($userId);
        
        // Insert current cart items
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $this->cartModel->addCartItem(
                    $userId,
                    $item['product_id'],
                    $item['size'] ?? 'Vừa',
                    $item['quantity'],
                    $item['price']
                );
            }
        }
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
        
        // Save cart to database if user is logged in
        if (isset($_SESSION['user_id'])) {
            $this->saveCartToDatabase($_SESSION['user_id']);
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
        
        // Save cart to database if user is logged in
        if (isset($_SESSION['user_id'])) {
            $this->saveCartToDatabase($_SESSION['user_id']);
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
        
        // Save cart to database if user is logged in
        if (isset($_SESSION['user_id'])) {
            $this->saveCartToDatabase($_SESSION['user_id']);
        }
        
        $this->jsonResponse(['success' => true]);
    }
    
    public function getTotal()
    {
        $total = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                // Use price from cart (adjusted by size) instead of base product price
                $total += $item['price'] * $item['quantity'];
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
    
    public function changeSize()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid method'], 400);
        }
        
        $oldCartKey = $_POST['cart_key'] ?? null;
        $newSize = $_POST['new_size'] ?? null;
        
        if (!$oldCartKey || !$newSize || !isset($_SESSION['cart'][$oldCartKey])) {
            $this->jsonResponse(['error' => 'Invalid request'], 400);
            return;
        }
        
        $item = $_SESSION['cart'][$oldCartKey];
        $productId = $item['product_id'];
        
        // Create new cart key with new size
        $newCartKey = $productId . '_' . $newSize;
        
        // If new size already exists in cart, merge quantities
        if (isset($_SESSION['cart'][$newCartKey])) {
            $_SESSION['cart'][$newCartKey]['quantity'] += $item['quantity'];
        } else {
            // Move item to new size
            $_SESSION['cart'][$newCartKey] = $item;
            $_SESSION['cart'][$newCartKey]['size'] = $newSize;
        }
        
        // Remove old cart key
        unset($_SESSION['cart'][$oldCartKey]);
        
        // Save cart to database if user is logged in
        if (isset($_SESSION['user_id'])) {
            $this->saveCartToDatabase($_SESSION['user_id']);
        }
        
        $this->jsonResponse(['success' => true, 'message' => 'Đã cập nhật size']);
    }
}
?>
