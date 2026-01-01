<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <h3>Menu Quản Trị</h3>
        <nav class="admin-menu">
            <ul>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=dashboard" class="menu-item">📊 Dashboard</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=products" class="menu-item">🍕 Sản phẩm</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=categories" class="menu-item active">📁 Danh mục</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=orders" class="menu-item">📦 Đơn hàng</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=customers" class="menu-item">👥 Khách hàng</a></li>
                <li><a href="<?php echo SITE_URL; ?>index.php?action=admin&method=contacts" class="menu-item">💬 Liên hệ</a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="admin-content">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2>Quản Lý Danh Mục</h2>
                <button type="button" class="btn btn-primary" onclick="openAddCategoryModal()">➕ Thêm danh mục</button>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Mô tả</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?php echo $cat['categories_id']; ?></td>
                            <td><?php echo htmlspecialchars($cat['ten_categories']); ?></td>
                            <td><?php echo htmlspecialchars($cat['mo_ta_categories'] ?? ''); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($cat['ngay_tao_categories'])); ?></td>
                            <td>
                                <button type="button" class="btn btn-small btn-secondary" onclick="openEditCategoryModal(<?php echo $cat['categories_id']; ?>, '<?php echo htmlspecialchars(addslashes($cat['ten_categories'])); ?>', '<?php echo htmlspecialchars(addslashes($cat['mo_ta_categories'] ?? '')); ?>')">✏️ Sửa</button>
                                <button type="button" class="btn btn-small btn-danger" onclick="openDeleteCategoryModal(<?php echo $cat['categories_id']; ?>, '<?php echo htmlspecialchars(addslashes($cat['ten_categories'])); ?>')">🗑️ Xóa</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Modal Thêm/Sửa Danh Mục -->
<div id="categoryModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeCategoryModal()">&times;</span>
        <h2 id="modalTitle">Thêm Danh Mục</h2>
        
        <form id="categoryForm" method="POST" action="<?php echo SITE_URL; ?>index.php?action=admin&method=categories">
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="category_id" id="categoryId" value="">
            
            <div class="form-group">
                <label for="categoryName">Tên danh mục:</label>
                <input type="text" id="categoryName" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="categoryDescription">Mô tả:</label>
                <textarea id="categoryDescription" name="description" rows="3"></textarea>
            </div>
            
            <div class="modal-actions">
                <button type="submit" class="btn btn-primary" id="submitBtn">Lưu</button>
                <button type="button" class="btn btn-secondary" onclick="closeCategoryModal()">Hủy</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Xác nhận xóa -->
<div id="deleteConfirmModal" class="modal">
    <div class="modal-content modal-confirm">
        <h2>⚠️ Xác nhận xóa</h2>
        <p>Bạn chắc chắn muốn xóa danh mục <strong id="deleteCategoryName"></strong>?</p>
        <p style="color: var(--error-color); font-size: 12px;">Hành động này không thể hoàn tác.</p>
        
        <div class="modal-actions">
            <form id="deleteForm" method="POST" action="<?php echo SITE_URL; ?>index.php?action=admin&method=categories" style="display: inline;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="category_id" id="deleteCategoryId">
                <button type="submit" class="btn btn-danger">🗑️ Xóa</button>
            </form>
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Hủy</button>
        </div>
    </div>
</div>

<script>
function openAddCategoryModal() {
    document.getElementById('modalTitle').textContent = 'Thêm Danh Mục';
    document.getElementById('formAction').value = 'add';
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryName').value = '';
    document.getElementById('categoryDescription').value = '';
    document.getElementById('submitBtn').textContent = 'Lưu';
    document.getElementById('categoryModal').style.display = 'block';
}

function openEditCategoryModal(id, name, description) {
    document.getElementById('modalTitle').textContent = 'Sửa Danh Mục';
    document.getElementById('formAction').value = 'edit';
    document.getElementById('categoryId').value = id;
    document.getElementById('categoryName').value = decodeURIComponent(name);
    document.getElementById('categoryDescription').value = decodeURIComponent(description);
    document.getElementById('submitBtn').textContent = 'Cập nhật';
    document.getElementById('categoryModal').style.display = 'block';
}

function closeCategoryModal() {
    document.getElementById('categoryModal').style.display = 'none';
}

function openDeleteCategoryModal(id, name) {
    document.getElementById('deleteCategoryName').textContent = name;
    document.getElementById('deleteCategoryId').value = id;
    document.getElementById('deleteConfirmModal').style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('deleteConfirmModal').style.display = 'none';
}

// Đóng modal khi click ngoài
window.onclick = function(event) {
    var modal = document.getElementById('categoryModal');
    var deleteModal = document.getElementById('deleteConfirmModal');
    
    if (event.target == modal) {
        modal.style.display = 'none';
    }
    if (event.target == deleteModal) {
        deleteModal.style.display = 'none';
    }
}

// Xử lý form submit
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    var action = document.getElementById('formAction').value;
    var categoryId = document.getElementById('categoryId').value;
    var name = document.getElementById('categoryName').value;
    var description = document.getElementById('categoryDescription').value;
    
    if (!name.trim()) {
        alert('Vui lòng nhập tên danh mục');
        return;
    }
    
    // Tạo form ẩn để submit
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo SITE_URL; ?>index.php?action=admin&method=categories';
    
    var actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = action;
    
    var nameInput = document.createElement('input');
    nameInput.type = 'hidden';
    nameInput.name = 'name';
    nameInput.value = name;
    
    var descInput = document.createElement('input');
    descInput.type = 'hidden';
    descInput.name = 'description';
    descInput.value = description;
    
    form.appendChild(actionInput);
    form.appendChild(nameInput);
    form.appendChild(descInput);
    
    if (action === 'edit' && categoryId) {
        var idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'category_id';
        idInput.value = categoryId;
        form.appendChild(idInput);
    }
    
    document.body.appendChild(form);
    form.submit();
});
</script>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
