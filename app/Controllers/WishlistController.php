<?php

require_once APP_PATH . 'Controllers/Controller.php';
require_once APP_PATH . 'Models/Wishlist.php';
require_once APP_PATH . 'Models/Product.php';

class WishlistController extends Controller
{
    private $wishlistModel;
    private $productModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->wishlistModel = new Wishlist();
        $this->productModel = new Product();
    }
    
    public function view()
    {
        if (!$this->isAuthenticated()) {
            $this->redirectToLogin();
        }
        
        $wishlists = $this->wishlistModel->getByUserId($this->user['user_id']);
        $data = ['wishlists' => $wishlists];
        
        $this->render('wishlist/view', $data);
    }
    
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid method'], 400);
        }
        
        if (!$this->isAuthenticated()) {
            $this->jsonResponse(['error' => 'Please login first'], 401);
        }
        
        $productId = $_POST['product_id'] ?? null;
        
        if (!$productId) {
            $this->jsonResponse(['error' => 'Product ID is required'], 400);
        }
        
        $exists = $this->wishlistModel->checkExists($this->user['user_id'], $productId);
        
        if ($exists) {
            $this->jsonResponse(['success' => true, 'message' => 'Sản phẩm đã có trong danh sách yêu thích']);
            return;
        }
        
        $data = [
            'nguoi_dung_id' => $this->user['user_id'],
            'product_id' => $productId,
            'tao_luc' => date('Y-m-d H:i:s')
        ];
        
        error_log('Adding to wishlist: ' . json_encode($data));
        
        if ($this->wishlistModel->create($data)) {
            $this->jsonResponse(['success' => true, 'message' => 'Đã thêm vào danh sách yêu thích']);
        } else {
            $this->jsonResponse(['error' => 'Lỗi thêm vào danh sách yêu thích'], 500);
        }
    }
    
    public function remove()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid method'], 400);
        }
        
        if (!$this->isAuthenticated()) {
            $this->jsonResponse(['error' => 'Please login first'], 401);
        }
        
        $productId = $_POST['product_id'] ?? null;
        
        if (!$productId) {
            $this->jsonResponse(['error' => 'Product ID is required'], 400);
        }
        
        if ($this->wishlistModel->removeByProduct($this->user['user_id'], $productId)) {
            $this->jsonResponse(['success' => true, 'message' => 'Đã xóa khỏi danh sách yêu thích']);
        } else {
            $this->jsonResponse(['error' => 'Lỗi xóa khỏi danh sách yêu thích'], 500);
        }
    }
}
?>
