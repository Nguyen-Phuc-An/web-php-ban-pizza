<?php

require_once APP_PATH . 'Controllers/Controller.php';
require_once APP_PATH . 'Models/Product.php';
require_once APP_PATH . 'Models/Category.php';
require_once APP_PATH . 'Models/Wishlist.php';

class HomeController extends Controller
{
    private $productModel;
    private $categoryModel;
    private $wishlistModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->wishlistModel = new Wishlist();
    }
    
    public function index()
    {
        $categoryId = $_GET['category'] ?? null;
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 12;
        
        if ($categoryId) {
            $allProducts = $this->productModel->getByCategory($categoryId);
        } else {
            $allProducts = $this->productModel->readAll();
        }
        
        $totalProducts = count($allProducts);
        $totalPages = ceil($totalProducts / $perPage);
        
        // Ensure page is valid
        if ($page < 1) $page = 1;
        if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
        
        $offset = ($page - 1) * $perPage;
        $products = array_slice($allProducts, $offset, $perPage);
        
        $categories = $this->categoryModel->readAll();
        
        // Generate pagination HTML
        $paginationHtml = '';
        if ($totalPages > 1) {
            $paginationHtml = '<div class="pagination" style="margin-top: 30px; text-align: center; display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">';
            
            // Previous button
            if ($page > 1) {
                $prevPage = $page - 1;
                $paginationHtml .= '<button class="btn btn-secondary" onclick="loadProducts(' . ($categoryId ? $categoryId : 'null') . ', ' . $prevPage . ')">← Trước</button>';
            }
            
            // Page numbers
            for ($i = 1; $i <= $totalPages; $i++) {
                if ($i == $page) {
                    $paginationHtml .= '<button class="btn btn-primary" style="min-width: 40px;">' . $i . '</button>';
                } else {
                    $paginationHtml .= '<button class="btn btn-secondary" style="min-width: 40px;" onclick="loadProducts(' . ($categoryId ? $categoryId : 'null') . ', ' . $i . ')">' . $i . '</button>';
                }
            }
            
            // Next button
            if ($page < $totalPages) {
                $nextPage = $page + 1;
                $paginationHtml .= '<button class="btn btn-secondary" onclick="loadProducts(' . ($categoryId ? $categoryId : 'null') . ', ' . $nextPage . ')">Tiếp →</button>';
            }
            
            $paginationHtml .= '</div>';
        }
        
        $data = [
            'products' => $products,
            'categories' => $categories,
            'selected_category' => $categoryId,
            'is_logged_in' => isset($_SESSION['user_id']),
            'pagination' => $paginationHtml,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts
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
        if (!$this->isAuthenticated()) {
            $this->redirectToLogin();
        }
        
        $wishlists = $this->wishlistModel->getByUserId($this->user['user_id']);
        
        // Debug log
        error_log('User ID: ' . $this->user['user_id']);
        error_log('Wishlists: ' . json_encode($wishlists));
        
        $data = [
            'is_logged_in' => true,
            'wishlists' => $wishlists
        ];
        $this->render('home/wishlist', $data);
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
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 12;
        
        if ($categoryId) {
            $allProducts = $this->productModel->getByCategory($categoryId);
        } else {
            $allProducts = $this->productModel->readAll();
        }
        
        $totalProducts = count($allProducts);
        $totalPages = ceil($totalProducts / $perPage);
        
        // Ensure page is valid
        if ($page < 1) $page = 1;
        if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
        
        $offset = ($page - 1) * $perPage;
        $products = array_slice($allProducts, $offset, $perPage);
        
        $isLoggedIn = isset($_SESSION['user_id']);
        
        // Generate HTML for products
        $html = '';
        
        if (!empty($products)) {
            foreach ($products as $product) {
                // Extract product name before "-"
                $productName = strpos($product['ten_product'], '-') !== false 
                    ? substr($product['ten_product'], 0, strpos($product['ten_product'], '-'))
                    : $product['ten_product'];
                
                $html .= '<div class="product-card" onclick="viewProductDetail(' . $product['product_id'] . ')" style="cursor: pointer;">';
                $html .= '    <div class="product-image">';
                $html .= '        <img src="' . SITE_URL . 'uploads/' . htmlspecialchars($product['hinh_anh_product']) . '" alt="' . htmlspecialchars($product['ten_product']) . '">';
                $html .= '    </div>';
                $html .= '    <div class="product-info">';
                $html .= '        <h3 style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 3.2em; line-height: 1.6em; margin: 0 0 8px 0;">' . htmlspecialchars($productName) . '</h3>';
                $html .= '        <p class="product-price">' . number_format($product['gia_product'], 0, ',', '.') . ' đ</p>';
                $html .= '        <p class="product-description" style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; min-height: 1.6em; line-height: 1.6em; margin: 8px 0;">' . htmlspecialchars(substr($product['mo_ta_product'], 0, 50)) . '...</p>';
                $html .= '        <div class="product-actions">';
                $html .= '            <button class="btn btn-favorite wishlist-btn" onclick="event.stopPropagation(); toggleWishlist(' . $product['product_id'] . ', this)" title="Thêm vào yêu thích" data-product-id="' . $product['product_id'] . '" style="background: none; border: 1px solid; font-size: 20px; cursor: pointer; padding: 0; min-width: auto; width: 100%;"><i class="bi bi-heart wishlist-icon" style="font-size: 20px;"></i></button>';
                $html .= '        </div>';
                $html .= '    </div>';
                $html .= '</div>';
            }
        } else {
            $html = '<p class="no-products" style="grid-column: 1 / -1; text-align: center;">Không có sản phẩm nào</p>';
        }
        
        // Generate pagination HTML
        $paginationHtml = '';
        if ($totalPages > 1) {
            $paginationHtml = '<div class="pagination" style="margin-top: 30px; text-align: center; display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">';
            
            // Previous button
            if ($page > 1) {
                $prevPage = $page - 1;
                $paginationHtml .= '<button class="btn btn-secondary" onclick="loadProducts(' . ($categoryId ? $categoryId : 'null') . ', ' . $prevPage . ')">← Trước</button>';
            }
            
            // Page numbers
            for ($i = 1; $i <= $totalPages; $i++) {
                if ($i == $page) {
                    $paginationHtml .= '<button class="btn btn-primary" style="min-width: 40px;">' . $i . '</button>';
                } else {
                    $paginationHtml .= '<button class="btn btn-secondary" style="min-width: 40px;" onclick="loadProducts(' . ($categoryId ? $categoryId : 'null') . ', ' . $i . ')">' . $i . '</button>';
                }
            }
            
            // Next button
            if ($page < $totalPages) {
                $nextPage = $page + 1;
                $paginationHtml .= '<button class="btn btn-secondary" onclick="loadProducts(' . ($categoryId ? $categoryId : 'null') . ', ' . $nextPage . ')">Tiếp →</button>';
            }
            
            $paginationHtml .= '</div>';
        }
        
        $this->jsonResponse([
            'html' => $html,
            'pagination' => $paginationHtml,
            'isLoggedIn' => $isLoggedIn,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'totalProducts' => $totalProducts
        ]);
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
                // Extract product name before "-"
                $productName = strpos($product['ten_product'], '-') !== false 
                    ? substr($product['ten_product'], 0, strpos($product['ten_product'], '-'))
                    : $product['ten_product'];
                
                $html .= '<div class="product-card" onclick="viewProductDetail(' . $product['product_id'] . ')" style="cursor: pointer;">';
                $html .= '    <div class="product-image">';
                $html .= '        <img src="' . SITE_URL . 'uploads/' . htmlspecialchars($product['hinh_anh_product']) . '" alt="' . htmlspecialchars($product['ten_product']) . '">';
                $html .= '    </div>';
                $html .= '    <div class="product-info">';
                $html .= '        <h3 style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 3.2em; line-height: 1.6em; margin: 0 0 8px 0;">' . htmlspecialchars($productName) . '</h3>';
                $html .= '        <p class="product-price">' . number_format($product['gia_product'], 0, ',', '.') . ' đ</p>';
                $html .= '        <p class="product-description" style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; min-height: 1.6em; line-height: 1.6em; margin: 8px 0;">' . htmlspecialchars(substr($product['mo_ta_product'], 0, 50)) . '...</p>';
                $html .= '        <div class="product-actions">';
                $html .= '            <button class="btn btn-favorite wishlist-btn" onclick="event.stopPropagation(); toggleWishlist(' . $product['product_id'] . ', this)" title="Thêm vào yêu thích" data-product-id="' . $product['product_id'] . '" style="background: none; border: 1px solid; font-size: 20px; cursor: pointer; padding: 0; min-width: auto; width: 100%;"><i class="bi bi-heart wishlist-icon" style="font-size: 20px;"></i></button>';
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
