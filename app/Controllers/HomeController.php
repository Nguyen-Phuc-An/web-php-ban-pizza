<?php

require_once APP_PATH . 'Controllers/Controller.php';
require_once APP_PATH . 'Models/Product.php';
require_once APP_PATH . 'Models/Category.php';

class HomeController extends Controller
{
    private $productModel;
    private $categoryModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }
    
    public function index()
    {
        $categoryId = $_GET['category'] ?? null;
        
        if ($categoryId) {
            $products = $this->productModel->getByCategory($categoryId);
        } else {
            $products = $this->productModel->readAll();
        }
        
        $categories = $this->categoryModel->readAll();
        
        $data = [
            'products' => $products,
            'categories' => $categories,
            'selected_category' => $categoryId,
            'is_logged_in' => isset($_SESSION['user_id'])
        ];
        
        $this->render('home/index', $data);
    }
    
    public function about()
    {
        $page_title = 'Giới thiệu';
        $this->render('home/about');
    }
    
    public function wishlist()
    {
        $this->render('home/wishlist');
    }
    
    public function getWishlistItems()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $product_ids = $data['product_ids'] ?? [];
        
        if (empty($product_ids)) {
            $this->jsonResponse(['products' => []]);
            return;
        }
        
        $products = [];
        foreach ($product_ids as $id) {
            $product = $this->productModel->read($id);
            if ($product) {
                $products[] = $product;
            }
        }
        
        $this->jsonResponse(['products' => $products]);
    }
    
    public function getDetail()
    {
        $productId = $_GET['id'] ?? null;
        if (!$productId) {
            $this->jsonResponse(['error' => 'Product ID required'], 400);
            return;
        }
        
        $product = $this->productModel->read($productId);
        if (!$product) {
            $this->jsonResponse(['error' => 'Product not found'], 404);
            return;
        }
        
        $this->jsonResponse(['product' => $product]);
    }
    
    public function getProducts()
    {
        $categoryId = $_GET['category'] ?? null;
        
        if ($categoryId) {
            $products = $this->productModel->getByCategory($categoryId);
        } else {
            $products = $this->productModel->readAll();
        }
        
        $isLoggedIn = isset($_SESSION['user_id']);
        
        // Generate HTML for products
        $html = '';
        
        if (!empty($products)) {
            foreach ($products as $product) {
                $html .= '<div class="product-card">';
                $html .= '    <div class="product-image">';
                $html .= '        <img src="' . SITE_URL . 'uploads/' . htmlspecialchars($product['hinh_anh_product']) . '" alt="' . htmlspecialchars($product['ten_product']) . '">';
                $html .= '    </div>';
                $html .= '    <div class="product-info">';
                $html .= '        <h3>' . htmlspecialchars($product['ten_product']) . '</h3>';
                $html .= '        <p class="product-price">' . number_format($product['gia_product'], 0, ',', '.') . ' đ</p>';
                $html .= '        <p class="product-description">' . htmlspecialchars(substr($product['mo_ta_product'], 0, 50)) . '...</p>';
                $html .= '        <div class="product-actions">';
                $html .= '            <button class="btn btn-primary" onclick="viewProductDetail(' . $product['product_id'] . ')">Chi tiết</button>';
                $html .= '            <button class="btn btn-favorite wishlist-btn" onclick="toggleWishlist(' . $product['product_id'] . ', this)" title="Thêm vào yêu thích" data-product-id="' . $product['product_id'] . '" style="background: none; border: 1px solid; font-size: 24px; cursor: pointer; padding: 0; min-width: auto;"><span class="wishlist-icon">♡</span></button>';
                $html .= '        </div>';
                $html .= '    </div>';
                $html .= '</div>';
            }
        } else {
            $html = '<p class="no-products">Không có sản phẩm nào</p>';
        }
        
        $this->jsonResponse(['html' => $html, 'isLoggedIn' => $isLoggedIn]);
    }
    
    public function searchProducts()
    {
        $keyword = $_GET['q'] ?? '';
        $products = [];
        
        if (!empty($keyword)) {
            $products = $this->productModel->search($keyword);
        }
        
        $isLoggedIn = isset($_SESSION['user_id']);
        
        // Generate HTML for products
        $html = '';
        
        if (!empty($products)) {
            foreach ($products as $product) {
                $html .= '<div class="product-card">';
                $html .= '    <div class="product-image">';
                $html .= '        <img src="' . SITE_URL . 'uploads/' . htmlspecialchars($product['hinh_anh_product']) . '" alt="' . htmlspecialchars($product['ten_product']) . '">';
                $html .= '    </div>';
                $html .= '    <div class="product-info">';
                $html .= '        <h3>' . htmlspecialchars($product['ten_product']) . '</h3>';
                $html .= '        <p class="product-price">' . number_format($product['gia_product'], 0, ',', '.') . ' đ</p>';
                $html .= '        <p class="product-description">' . htmlspecialchars(substr($product['mo_ta_product'], 0, 50)) . '...</p>';
                $html .= '        <div class="product-actions">';
                $html .= '            <button class="btn btn-primary" onclick="viewProductDetail(' . $product['product_id'] . ')">Chi tiết</button>';
                $html .= '            <button class="btn btn-favorite wishlist-btn" onclick="toggleWishlist(' . $product['product_id'] . ', this)" title="Thêm vào yêu thích" data-product-id="' . $product['product_id'] . '" style="background: none; border: 1px solid; font-size: 24px; cursor: pointer; padding: 0; min-width: auto;"><span class="wishlist-icon">♡</span></button>';
                $html .= '        </div>';
                $html .= '    </div>';
                $html .= '</div>';
            }
        } else {
            $html = '<p class="no-products" style="grid-column: 1 / -1; text-align: center;">Không tìm thấy sản phẩm nào phù hợp</p>';
        }
        
        $this->jsonResponse([
            'html' => $html, 
            'count' => count($products),
            'isLoggedIn' => $isLoggedIn
        ]);
    }
}
?>
