<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <h3>Menu Quản Trị</h3>
        <nav class="admin-menu">
            <ul>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=dashboard" class="menu-item">📊 Dashboard</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=products" class="menu-item active">🍕 Sản phẩm</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=categories" class="menu-item">📁 Danh mục</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=orders" class="menu-item">📦 Đơn hàng</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=customers" class="menu-item">👥 Khách hàng</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=contacts" class="menu-item">💬 Liên hệ</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=auth&method=logout" class="menu-item">🚪 Đăng xuất</a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="admin-content">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-lg);">
                <h2>Quản Lý Sản Phẩm</h2>
                <button type="button" class="btn btn-primary" onclick="openAddProductModal()">➕ Thêm sản phẩm</button>
            </div>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Giá</th>
                        <th>Hình ảnh</th>
                        <th>Danh mục</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['product_id']; ?></td>
                            <td><?php echo htmlspecialchars($product['ten_product']); ?></td>
                            <td><?php echo number_format($product['gia_product'], 0, ',', '.'); ?>đ</td>
                            <td><img src="<?php echo SITE_URL; ?>uploads/<?php echo htmlspecialchars($product['hinh_anh_product']); ?>" class="admin-thumb" alt="" style="width: 80px; height: 80px; object-fit: cover;"></td>
                            <td><?php echo $product['danh_muc_product']; ?></td>
                            <td>
                                <button type="button" class="btn btn-small btn-secondary" onclick="openEditProductModal(<?php echo $product['product_id']; ?>, '<?php echo htmlspecialchars(addslashes($product['ten_product'])); ?>', '<?php echo htmlspecialchars($product['gia_product']); ?>', '<?php echo htmlspecialchars($product['danh_muc_product']); ?>', '<?php echo htmlspecialchars(addslashes($product['mo_ta_product'])); ?>')">✏️ Sửa</button>
                                <button type="button" class="btn btn-small btn-danger" onclick="openDeleteProductModal(<?php echo $product['product_id']; ?>, '<?php echo htmlspecialchars(addslashes($product['ten_product'])); ?>')">🗑️ Xóa</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($current_page > 1): ?>
                        <a href="<?php echo SITE_URL; ?>index.php?action=admin&method=products&page=<?php echo $current_page - 1; ?>" class="page-link">← Trước</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="<?php echo SITE_URL; ?>index.php?action=admin&method=products&page=<?php echo $i; ?>" class="page-link <?php echo $i == $current_page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                        <a href="<?php echo SITE_URL; ?>index.php?action=admin&method=products&page=<?php echo $current_page + 1; ?>" class="page-link">Sau →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Modal Thêm sản phẩm -->
<div id="addProductModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddProductModal()">&times;</span>
        <h2>Thêm sản phẩm</h2>
        
        <form id="addProductForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="addProductName">Tên sản phẩm:</label>
                <input type="text" id="addProductName" name="ten_product" required>
            </div>
            
            <div class="form-group">
                <label for="addProductPrice">Giá:</label>
                <input type="number" id="addProductPrice" name="gia_product" required step="0.01">
            </div>
            
            <div class="form-group">
                <label for="addProductCategory">Danh mục:</label>
                <select id="addProductCategory" name="danh_muc_product" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php 
                    $categoryModel = new Category();
                    $categories = $categoryModel->readAll();
                    foreach ($categories as $cat):
                    ?>
                        <option value="<?php echo htmlspecialchars($cat['categories_id']); ?>"><?php echo htmlspecialchars($cat['ten_categories']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="addProductDesc">Mô tả:</label>
                <textarea id="addProductDesc" name="mo_ta_product" rows="4" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="addProductImage">Hình ảnh:</label>
                <input type="file" id="addProductImage" name="hinh_anh_product" accept="image/*" required>
            </div>
            
            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">✅ Thêm</button>
                <button type="button" class="btn btn-secondary" onclick="closeAddProductModal()">Hủy</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Sửa sản phẩm -->
<div id="editProductModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditProductModal()">&times;</span>
        <h2>✏️ Sửa sản phẩm</h2>
        
        <form id="editProductForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" id="editProductId" name="product_id">
            
            <div class="form-group">
                <label for="editProductName">Tên sản phẩm:</label>
                <input type="text" id="editProductName" name="ten_product" required>
            </div>
            
            <div class="form-group">
                <label for="editProductPrice">Giá:</label>
                <input type="number" id="editProductPrice" name="gia_product" required step="0.01">
            </div>
            
            <div class="form-group">
                <label for="editProductCategory">Danh mục:</label>
                <select id="editProductCategory" name="danh_muc_product" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php 
                    $categoryModel = new Category();
                    $categories = $categoryModel->readAll();
                    foreach ($categories as $cat):
                    ?>
                        <option value="<?php echo htmlspecialchars($cat['categories_id']); ?>"><?php echo htmlspecialchars($cat['ten_categories']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="editProductDesc">Mô tả:</label>
                <textarea id="editProductDesc" name="mo_ta_product" rows="4" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="editProductImage">Hình ảnh (để trống nếu không thay đổi):</label>
                <input type="file" id="editProductImage" name="hinh_anh_product" accept="image/*">
            </div>
            
            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">💾 Lưu</button>
                <button type="button" class="btn btn-secondary" onclick="closeEditProductModal()">Hủy</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Xác nhận xóa sản phẩm -->
<div id="deleteProductModal" class="modal">
    <div class="modal-content modal-confirm">
        <h2>⚠️ Xác nhận xóa</h2>
        <p>Bạn chắc chắn muốn xóa sản phẩm <strong id="deleteProductName"></strong>?</p>
        <p style="color: var(--error-color); font-size: 12px;">Hành động này không thể hoàn tác.</p>
        
        <div class="modal-actions">
            <form id="deleteProductForm" method="POST" style="display: inline;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" id="deleteProductId" name="product_id">
                <button type="submit" class="btn btn-danger">🗑️ Xóa</button>
            </form>
            <button type="button" class="btn btn-secondary" onclick="closeDeleteProductModal()">Hủy</button>
        </div>
    </div>
</div>

<script>
function openAddProductModal() {
    document.getElementById('addProductModal').style.display = 'block';
}

function closeAddProductModal() {
    document.getElementById('addProductModal').style.display = 'none';
    document.getElementById('addProductForm').reset();
}

function openEditProductModal(id, name, price, categoryId, description) {
    document.getElementById('editProductId').value = id;
    document.getElementById('editProductName').value = name;
    document.getElementById('editProductPrice').value = price;
    document.getElementById('editProductCategory').value = categoryId;
    document.getElementById('editProductDesc').value = description;
    document.getElementById('editProductModal').style.display = 'block';
}

function closeEditProductModal() {
    document.getElementById('editProductModal').style.display = 'none';
    document.getElementById('editProductForm').reset();
}

function openDeleteProductModal(id, name) {
    document.getElementById('deleteProductId').value = id;
    document.getElementById('deleteProductName').textContent = name;
    document.getElementById('deleteProductModal').style.display = 'block';
}

function closeDeleteProductModal() {
    document.getElementById('deleteProductModal').style.display = 'none';
}

// Đóng modal khi click ngoài
window.onclick = function(event) {
    var addModal = document.getElementById('addProductModal');
    var editModal = document.getElementById('editProductModal');
    var deleteModal = document.getElementById('deleteProductModal');
    
    if (event.target == addModal) {
        addModal.style.display = 'none';
    }
    if (event.target == editModal) {
        editModal.style.display = 'none';
    }
    if (event.target == deleteModal) {
        deleteModal.style.display = 'none';
    }
}

// Xử lý form submit add product
document.getElementById('addProductForm').addEventListener('submit', function(e) {
    // Cho phép form submit bình thường vì đã có enctype="multipart/form-data"
});

// Xử lý form submit edit product
document.getElementById('editProductForm').addEventListener('submit', function(e) {
    // Cho phép form submit bình thường vì đã có enctype="multipart/form-data"
});
</script>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
