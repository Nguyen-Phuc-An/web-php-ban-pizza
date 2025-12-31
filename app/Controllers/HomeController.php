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
            'selected_category' => $categoryId
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
}
?>
