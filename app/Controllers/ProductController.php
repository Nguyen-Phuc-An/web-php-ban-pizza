<?php

require_once APP_PATH . 'Controllers/Controller.php';
require_once APP_PATH . 'Models/Product.php';
require_once APP_PATH . 'Models/Category.php';

class ProductController extends Controller
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
        $page = $_GET['page'] ?? 1;
        $categoryId = $_GET['category'] ?? null;
        
        if ($categoryId) {
            $products = $this->productModel->getAllByCategory($categoryId, $page);
            $total = $this->productModel->countByCategory($categoryId);
        } else {
            $products = $this->productModel->getAll($page);
            $total = $this->productModel->count();
        }
        
        $categories = $this->categoryModel->readAll();
        $totalPages = ceil($total / ITEMS_PER_PAGE);
        
        $data = [
            'products' => $products,
            'categories' => $categories,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'current_category' => $categoryId
        ];
        
        $this->render('home/index', $data);
    }
    
    public function detail()
    {
        $productId = $_GET['id'] ?? null;
        if (!$productId) {
            $this->redirect(SITE_URL . 'index.php?action=product');
        }
        
        $product = $this->productModel->read($productId);
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại';
            $this->redirect(SITE_URL . 'index.php?action=product');
        }
        
        $data = ['product' => $product];
        $this->render('product/detail', $data);
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
    
    public function search()
    {
        $keyword = $_GET['q'] ?? '';
        $products = [];
        
        if (!empty($keyword)) {
            $products = $this->productModel->search($keyword);
        }
        
        $data = [
            'products' => $products,
            'keyword' => $keyword
        ];
        
        $this->render('product/search', $data);
    }
}
?>
