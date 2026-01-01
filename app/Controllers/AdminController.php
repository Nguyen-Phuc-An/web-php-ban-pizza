<?php

require_once APP_PATH . 'Controllers/Controller.php';
require_once APP_PATH . 'Models/Order.php';
require_once APP_PATH . 'Models/User.php';
require_once APP_PATH . 'Models/Product.php';
require_once APP_PATH . 'Models/Category.php';
require_once APP_PATH . 'Models/Contact.php';

class AdminController extends Controller
{
    private $orderModel;
    private $userModel;
    private $productModel;
    private $categoryModel;
    private $contactModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new Order();
        $this->userModel = new User();
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->contactModel = new Contact();
    }
    
    public function dashboard()
    {
        if (!$this->isAdmin()) {
            $this->redirectToLogin();
        }
        
        $totalOrders = $this->orderModel->countAll();
        $totalCustomers = $this->userModel->countUsers();
        $totalRevenue = $this->orderModel->getTotalRevenue();
        
        // Get monthly revenue for chart
        $monthlyRevenue = [];
        for ($i = 0; $i < 12; $i++) {
            $month = date('m', strtotime("-$i month"));
            $year = date('Y', strtotime("-$i month"));
            $revenue = $this->orderModel->getRevenueByMonth($month, $year);
            $monthlyRevenue[date('M Y', strtotime("$year-$month-01"))] = $revenue;
        }
        
        $data = [
            'total_orders' => $totalOrders,
            'total_customers' => $totalCustomers,
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => array_reverse($monthlyRevenue)
        ];
        
        $this->render('admin/dashboard', $data);
    }
    
    public function products()
    {
        if (!$this->isAdmin()) {
            $this->redirectToLogin();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'add') {
                $this->addProductFromModal();
            } elseif ($action === 'edit') {
                $this->editProductFromModal();
            } elseif ($action === 'delete') {
                $this->deleteProductFromModal();
            }
        }
        
        $page = $_GET['page'] ?? 1;
        $products = $this->productModel->getAll($page);
        $total = $this->productModel->count();
        $totalPages = ceil($total / ADMIN_ITEMS_PER_PAGE);
        
        $data = [
            'products' => $products,
            'current_page' => $page,
            'total_pages' => $totalPages
        ];
        
        $this->render('admin/products/list', $data);
    }
    
    private function addProductFromModal()
    {
        $data = [
            'ten_product' => $_POST['ten_product'] ?? '',
            'mo_ta_product' => $_POST['mo_ta_product'] ?? '',
            'gia_product' => $_POST['gia_product'] ?? 0,
            'danh_muc_product' => $_POST['danh_muc_product'] ?? 0,
            'ngay_tao_product' => date('Y-m-d H:i:s'),
            'ngay_cap_nhap_product' => date('Y-m-d H:i:s')
        ];
        
        // Handle image upload
        if (isset($_FILES['hinh_anh_product']) && $_FILES['hinh_anh_product']['error'] === UPLOAD_ERR_OK) {
            $filename = time() . '_' . basename($_FILES['hinh_anh_product']['name']);
            $target = UPLOAD_PATH . $filename;
            
            if (move_uploaded_file($_FILES['hinh_anh_product']['tmp_name'], $target)) {
                $data['hinh_anh_product'] = $filename;
            } else {
                $_SESSION['error'] = 'Lỗi tải hình ảnh';
                return;
            }
        } else {
            $_SESSION['error'] = 'Vui lòng chọn hình ảnh sản phẩm';
            return;
        }
        
        if (empty($data['ten_product']) || empty($data['danh_muc_product']) || $data['gia_product'] <= 0) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc';
            return;
        }
        
        if ($this->productModel->create($data)) {
            $_SESSION['success'] = 'Thêm sản phẩm thành công';
        } else {
            $_SESSION['error'] = 'Lỗi thêm sản phẩm';
        }
    }
    
    private function editProductFromModal()
    {
        $productId = $_POST['product_id'] ?? null;
        if (!$productId) {
            $_SESSION['error'] = 'ID sản phẩm không hợp lệ';
            return;
        }
        
        $data = [
            'ten_product' => $_POST['ten_product'] ?? '',
            'mo_ta_product' => $_POST['mo_ta_product'] ?? '',
            'gia_product' => $_POST['gia_product'] ?? 0,
            'danh_muc_product' => $_POST['danh_muc_product'] ?? 0,
            'ngay_cap_nhap_product' => date('Y-m-d H:i:s')
        ];
        
        // Handle image upload (optional)
        if (isset($_FILES['hinh_anh_product']) && $_FILES['hinh_anh_product']['error'] === UPLOAD_ERR_OK) {
            $filename = time() . '_' . basename($_FILES['hinh_anh_product']['name']);
            $target = UPLOAD_PATH . $filename;
            
            if (move_uploaded_file($_FILES['hinh_anh_product']['tmp_name'], $target)) {
                $data['hinh_anh_product'] = $filename;
            } else {
                $_SESSION['error'] = 'Lỗi tải hình ảnh';
                return;
            }
        }
        
        if (empty($data['ten_product']) || empty($data['danh_muc_product']) || $data['gia_product'] <= 0) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc';
            return;
        }
        
        if ($this->productModel->update($productId, $data)) {
            $_SESSION['success'] = 'Cập nhật sản phẩm thành công';
        } else {
            $_SESSION['error'] = 'Lỗi cập nhật sản phẩm';
        }
    }
    
    private function deleteProductFromModal()
    {
        $productId = $_POST['product_id'] ?? null;
        if (!$productId) {
            $_SESSION['error'] = 'ID sản phẩm không hợp lệ';
            return;
        }
        
        if ($this->productModel->delete($productId)) {
            $_SESSION['success'] = 'Xóa sản phẩm thành công';
        } else {
            $_SESSION['error'] = 'Lỗi xóa sản phẩm';
        }
    }
    
    public function addProduct()
    {
        if (!$this->isAdmin()) {
            $this->redirectToLogin();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->saveProduct();
        }
        
        $categories = $this->categoryModel->readAll();
        $data = ['categories' => $categories];
        
        $this->render('admin/products/add', $data);
    }
    
    public function editProduct()
    {
        if (!$this->isAdmin()) {
            $this->redirectToLogin();
        }
        
        $productId = $_GET['id'] ?? null;
        if (!$productId) {
            $this->redirect(SITE_URL . 'index.php?action=admin&method=products');
        }
        
        $product = $this->productModel->read($productId);
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại';
            $this->redirect(SITE_URL . 'index.php?action=admin&method=products');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->saveProduct($productId);
        }
        
        $categories = $this->categoryModel->readAll();
        $data = [
            'product' => $product,
            'categories' => $categories
        ];
        
        $this->render('admin/products/edit', $data);
    }
    
    private function saveProduct($productId = null)
    {
        $data = [
            'ten_product' => $_POST['name'] ?? '',
            'mo_ta_product' => $_POST['description'] ?? '',
            'gia_product' => $_POST['price'] ?? 0,
            'danh_muc_product' => $_POST['category'] ?? 0,
            'ngay_cap_nhap_product' => date('Y-m-d H:i:s')
        ];
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $filename = time() . '_' . basename($_FILES['image']['name']);
            $target = UPLOAD_PATH . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $data['hinh_anh_product'] = $filename;
            }
        } elseif (!$productId) {
            $_SESSION['error'] = 'Vui lòng chọn hình ảnh sản phẩm';
            return;
        }
        
        if (empty($data['ten_product']) || empty($data['danh_muc_product']) || $data['gia_product'] <= 0) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc';
            return;
        }
        
        if ($productId) {
            if ($this->productModel->update($productId, $data)) {
                $_SESSION['success'] = 'Cập nhật sản phẩm thành công';
                $this->redirect(SITE_URL . 'index.php?action=admin&method=products');
            } else {
                $_SESSION['error'] = 'Lỗi cập nhật sản phẩm';
            }
        } else {
            $data['ngay_tao_product'] = date('Y-m-d H:i:s');
            if ($this->productModel->create($data)) {
                $_SESSION['success'] = 'Thêm sản phẩm thành công';
                $this->redirect(SITE_URL . 'index.php?action=admin&method=products');
            } else {
                $_SESSION['error'] = 'Lỗi thêm sản phẩm';
            }
        }
    }
    
    public function deleteProduct()
    {
        if (!$this->isAdmin()) {
            $this->redirectToLogin();
        }
        
        $productId = $_GET['id'] ?? null;
        if (!$productId) {
            $this->jsonResponse(['error' => 'Product ID is required'], 400);
        }
        
        if ($this->productModel->delete($productId)) {
            $_SESSION['success'] = 'Xóa sản phẩm thành công';
            $this->redirect(SITE_URL . 'index.php?action=admin&method=products');
        } else {
            $_SESSION['error'] = 'Lỗi xóa sản phẩm';
            $this->redirect(SITE_URL . 'index.php?action=admin&method=products');
        }
    }
    
    public function categories()
    {
        if (!$this->isAdminOnly()) {
            $this->redirectToLogin();
        }
        
        $categories = $this->categoryModel->readAll();
        $data = ['categories' => $categories];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'add') {
                $this->addCategory();
            } elseif ($action === 'edit') {
                $this->updateCategoryFromModal();
            } elseif ($action === 'delete') {
                $this->deleteCategoryFromModal();
            }
            
            // Reload categories after add/edit/delete
            $categories = $this->categoryModel->readAll();
            $data = ['categories' => $categories];
        }
        
        $this->render('admin/categories/list', $data);
    }
    
    private function addCategory()
    {
        $data = [
            'ten_categories' => $_POST['name'] ?? '',
            'mo_ta_categories' => $_POST['description'] ?? '',
            'ngay_tao_categories' => date('Y-m-d H:i:s'),
            'ngay_cap_nhap_categories' => date('Y-m-d H:i:s')
        ];
        
        if (empty($data['ten_categories'])) {
            $_SESSION['error'] = 'Vui lòng nhập tên danh mục';
            return;
        }
        
        if ($this->categoryModel->create($data)) {
            $_SESSION['success'] = 'Thêm danh mục thành công';
        } else {
            $_SESSION['error'] = 'Lỗi thêm danh mục';
        }
    }
    
    private function updateCategoryFromModal()
    {
        $categoryId = $_POST['category_id'] ?? null;
        $data = [
            'ten_categories' => $_POST['name'] ?? '',
            'mo_ta_categories' => $_POST['description'] ?? '',
            'ngay_cap_nhap_categories' => date('Y-m-d H:i:s')
        ];
        
        if (empty($data['ten_categories'])) {
            $_SESSION['error'] = 'Vui lòng nhập tên danh mục';
            return;
        }
        
        if ($categoryId && $this->categoryModel->update($categoryId, $data)) {
            $_SESSION['success'] = 'Cập nhật danh mục thành công';
        } else {
            $_SESSION['error'] = 'Lỗi cập nhật danh mục';
        }
    }
    
    private function deleteCategoryFromModal()
    {
        $categoryId = $_POST['category_id'] ?? null;
        if (!$categoryId) {
            $_SESSION['error'] = 'ID danh mục không hợp lệ';
            return;
        }
        
        if ($this->categoryModel->delete($categoryId)) {
            $_SESSION['success'] = 'Xóa danh mục thành công';
        } else {
            $_SESSION['error'] = 'Lỗi xóa danh mục';
        }
    }
    
    public function editCategory()
    {
        if (!$this->isAdminOnly()) {
            $this->redirectToLogin();
        }
        
        $categoryId = $_GET['id'] ?? null;
        if (!$categoryId) {
            $this->redirect(SITE_URL . 'index.php?action=admin&method=categories');
        }
        
        $category = $this->categoryModel->read($categoryId);
        if (!$category) {
            $_SESSION['error'] = 'Danh mục không tồn tại';
            $this->redirect(SITE_URL . 'index.php?action=admin&method=categories');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ten_categories' => $_POST['name'] ?? '',
                'mo_ta_categories' => $_POST['description'] ?? '',
                'ngay_cap_nhap_categories' => date('Y-m-d H:i:s')
            ];
            
            if (empty($data['ten_categories'])) {
                $_SESSION['error'] = 'Vui lòng nhập tên danh mục';
            } else {
                if ($this->categoryModel->update($categoryId, $data)) {
                    $_SESSION['success'] = 'Cập nhật danh mục thành công';
                    $this->redirect(SITE_URL . 'index.php?action=admin&method=categories');
                } else {
                    $_SESSION['error'] = 'Lỗi cập nhật danh mục';
                }
            }
        }
        
        $data = ['category' => $category];
        $this->render('admin/categories/edit', $data);
    }
    
    public function deleteCategory()
    {
        if (!$this->isAdminOnly()) {
            $this->redirectToLogin();
        }
        
        $categoryId = $_GET['id'] ?? null;
        if (!$categoryId) {
            $_SESSION['error'] = 'ID danh mục không hợp lệ';
            $this->redirect(SITE_URL . 'index.php?action=admin&method=categories');
        }
        
        if ($this->categoryModel->delete($categoryId)) {
            $_SESSION['success'] = 'Xóa danh mục thành công';
        } else {
            $_SESSION['error'] = 'Lỗi xóa danh mục';
        }
        
        $this->redirect(SITE_URL . 'index.php?action=admin&method=categories');
    }
    
    public function orders()
    {
        if (!$this->isAdmin()) {
            $this->redirectToLogin();
        }
        
        $page = $_GET['page'] ?? 1;
        $orders = $this->orderModel->getAllOrders($page);
        $total = $this->orderModel->countAll();
        $totalPages = ceil($total / ADMIN_ITEMS_PER_PAGE);
        
        $data = [
            'orders' => $orders,
            'current_page' => $page,
            'total_pages' => $totalPages
        ];
        
        $this->render('admin/orders/list', $data);
    }
    
    public function orderDetail()
    {
        if (!$this->isAdmin()) {
            $this->redirectToLogin();
        }
        
        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            $this->redirect(SITE_URL . 'index.php?action=admin&method=orders');
        }
        
        require_once APP_PATH . 'Models/OrderItem.php';
        $orderItemModel = new OrderItem();
        
        $order = $this->orderModel->read($orderId);
        if (!$order) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            $this->redirect(SITE_URL . 'index.php?action=admin&method=orders');
        }
        
        $items = $orderItemModel->getByOrderId($orderId);
        $customer = $this->userModel->read($order['nguoi_mua_id']);
        
        $data = [
            'order' => $order,
            'items' => $items,
            'customer' => $customer
        ];
        
        $this->render('admin/orders/detail', $data);
    }
    
    public function getOrderDetail()
    {
        if (!$this->isAdmin()) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
            return;
        }
        
        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            $this->jsonResponse(['error' => 'Order ID required'], 400);
            return;
        }
        
        require_once APP_PATH . 'Models/OrderItem.php';
        $orderItemModel = new OrderItem();
        
        $order = $this->orderModel->read($orderId);
        if (!$order) {
            $this->jsonResponse(['error' => 'Order not found'], 404);
            return;
        }
        
        $items = $orderItemModel->getByOrderId($orderId);
        $customer = $this->userModel->read($order['nguoi_mua_id']);
        
        // Get shipping info from order
        $shippingInfo = [
            'ten_nguoi_dung' => $order['nguoi_nhan'] ?? '-',
            'so_dien_thoai_user' => $order['sdt_nguoi_nhan'] ?? '-',
            'dia_chi' => $order['dia-chi_nhan'] ?? '-',
            'email_user' => $customer['email_user'] ?? '-'
        ];
        
        $this->jsonResponse([
            'order' => $order,
            'items' => $items,
            'customer' => $shippingInfo
        ]);
    }
    
    public function updateOrderStatus()
    {
        if (!$this->isAdmin()) {
            $this->redirectToLogin();
        }
        
        $orderId = $_GET['id'] ?? null;
        $status = $_POST['status'] ?? null;
        
        if (!$orderId || !$status) {
            $_SESSION['error'] = 'Dữ liệu không hợp lệ';
            return;
        }
        
        if ($this->orderModel->update($orderId, ['trang_thai' => $status])) {
            $_SESSION['success'] = 'Cập nhật trạng thái đơn hàng thành công';
        } else {
            $_SESSION['error'] = 'Lỗi cập nhật trạng thái';
        }
        
        $this->redirect(SITE_URL . 'index.php?action=admin&method=orders');
    }
    
    public function customers()
    {
        if (!$this->isAdmin()) {
            $this->redirectToLogin();
        }
        
        $page = $_GET['page'] ?? 1;
        $customers = $this->userModel->getAllUsers($page);
        $total = $this->userModel->countUsers();
        $totalPages = ceil($total / ADMIN_ITEMS_PER_PAGE);
        
        $data = [
            'customers' => $customers,
            'current_page' => $page,
            'total_pages' => $totalPages
        ];
        
        $this->render('admin/customers/list', $data);
    }
    
    public function getCustomerData()
    {
        if (!$this->isAdmin()) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
        }
        
        $customerId = $_GET['id'] ?? null;
        if (!$customerId) {
            $this->jsonResponse(['error' => 'Customer ID required'], 400);
        }
        
        $customer = $this->userModel->read($customerId);
        if (!$customer) {
            $this->jsonResponse(['error' => 'Customer not found'], 404);
        }
        
        // Get orders for this customer
        $orders = $this->orderModel->getOrdersByCustomer($customerId);
        
        $this->jsonResponse([
            'customer' => $customer,
            'orders' => $orders ?? []
        ]);
    }
    
    public function customerDetail()
    {
        if (!$this->isAdmin()) {
            $this->redirectToLogin();
        }
        
        $customerId = $_GET['id'] ?? null;
        if (!$customerId) {
            $this->redirect(SITE_URL . 'index.php?action=admin&method=customers');
        }
        
        $customer = $this->userModel->read($customerId);
        if (!$customer) {
            $_SESSION['error'] = 'Khách hàng không tồn tại';
            $this->redirect(SITE_URL . 'index.php?action=admin&method=customers');
        }
        
        $page = $_GET['page'] ?? 1;
        $orders = $this->orderModel->getByUserId($customerId, $page);
        $total = $this->orderModel->countByUserId($customerId);
        $totalPages = ceil($total / ADMIN_ITEMS_PER_PAGE);
        
        $data = [
            'customer' => $customer,
            'orders' => $orders,
            'current_page' => $page,
            'total_pages' => $totalPages
        ];
        
        $this->render('admin/customers/detail', $data);
    }
    
    public function contacts()
    {
        if (!$this->isAdmin()) {
            $this->redirectToLogin();
        }
        
        $page = $_GET['page'] ?? 1;
        $contacts = $this->contactModel->getAllContacts($page);
        $total = $this->contactModel->count();
        $totalPages = ceil($total / ADMIN_ITEMS_PER_PAGE);
        
        $data = [
            'contacts' => $contacts,
            'current_page' => $page,
            'total_pages' => $totalPages
        ];
        
        $this->render('admin/contacts/list', $data);
    }
    
    public function getContactData()
    {
        if (!$this->isAdmin()) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
        }
        
        $contactId = $_GET['id'] ?? null;
        if (!$contactId) {
            $this->jsonResponse(['error' => 'Contact ID required'], 400);
        }
        
        $contact = $this->contactModel->read($contactId);
        if (!$contact) {
            $this->jsonResponse(['error' => 'Contact not found'], 404);
        }
        
        $this->jsonResponse(['contact' => $contact]);
    }
    
    public function deleteContact()
    {
        if (!$this->isAdmin()) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
        }
        
        $contactId = $_POST['contact_id'] ?? null;
        if (!$contactId) {
            $this->jsonResponse(['error' => 'Contact ID required'], 400);
        }
        
        if ($this->contactModel->delete($contactId)) {
            $this->jsonResponse(['success' => true]);
        } else {
            $this->jsonResponse(['error' => 'Failed to delete contact'], 500);
        }
    }
    
    public function contactDetail()
    {
        if (!$this->isAdmin()) {
            $this->redirectToLogin();
        }
        
        $contactId = $_GET['id'] ?? null;
        if (!$contactId) {
            $this->redirect(SITE_URL . 'index.php?action=admin&method=contacts');
        }
        
        $contact = $this->contactModel->read($contactId);
        if (!$contact) {
            $_SESSION['error'] = 'Liên hệ không tồn tại';
            $this->redirect(SITE_URL . 'index.php?action=admin&method=contacts');
        }
        
        $data = ['contact' => $contact];
        $this->render('admin/contacts/detail', $data);
    }
}
?>
