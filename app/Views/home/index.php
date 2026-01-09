<?php
$page_title = 'Trang chủ';
include APP_PATH . 'Views/layout/header.php';
?>

<div class="banner-section">
    <img src="<?php echo SITE_URL; ?>uploads/banner.jpg" alt="Banner" class="banner-image">
</div>

<div class="container">
    <!-- Tìm kiếm -->
    <div id="searchHeader" style="display: none; margin: var(--spacing-xl) 0; padding: var(--spacing-lg); background: var(--bg-light); border-radius: 8px; text-align: center;">
        <p style="font-size: 16px; color: var(--text-dark); margin: 0;">
            Tìm kiếm: <strong id="searchKeyword"></strong>
            <span style="color: var(--text-light); margin-left: var(--spacing-md);">
                (<span id="searchCount">0</span> kết quả)
            </span>
            <a href="#" onclick="clearSearch(); return false;" style="margin-left: var(--spacing-md); color: var(--primary-color); text-decoration: none;">✕ Xóa tìm kiếm</a>
        </p>
    </div>

    <!-- Lọc theo danh mục -->
    <div class="filter-section">
        <div class="filter-group">
            <div style="display: flex; gap: var(--spacing-md); flex-wrap: wrap;">
                <button class="btn <?php echo empty($selected_category) ? 'btn-primary' : 'btn-secondary'; ?>" 
                        onclick="loadProducts(null); showSubcategories(null)">
                    Tất cả
                </button>
                <?php foreach ($categories as $category): ?>
                    <?php if (is_null($category['parent_category_id']) || $category['parent_category_id'] == ''): ?>
                        <button class="btn category-btn <?php echo $selected_category == $category['categories_id'] ? 'btn-primary' : 'btn-secondary'; ?>" 
                                data-category-id="<?php echo $category['categories_id']; ?>"
                                onclick="loadProducts(<?php echo $category['categories_id']; ?>, 1); showSubcategories(<?php echo $category['categories_id']; ?>)">
                            <?php echo htmlspecialchars($category['ten_categories']); ?>
                        </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Danh mục con (hàng riêng biệt) -->
    <div id="subcategoriesContainer" style="display: none; padding: var(--spacing-md); background: #f9f9f9; border-radius: 8px;">
        <div style="display: flex; gap: var(--spacing-md); flex-wrap: wrap; align-items: center;">
            <div id="subcategoriesList" style="display: flex; gap: var(--spacing-md); flex-wrap: wrap;"></div>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <div id="productsContainer" class="products-grid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card" onclick="viewProductDetail(<?php echo $product['product_id']; ?>)" style="cursor: pointer;">
                    <div class="product-image">
                        <img src="<?php echo SITE_URL; ?>uploads/<?php echo htmlspecialchars($product['hinh_anh_product']); ?>" 
                             alt="<?php echo htmlspecialchars($product['ten_product']); ?>">
                    </div>
                    <div class="product-info">
                        <h3 style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 3.2em; line-height: 1.6em; margin: 0 0 8px 0;"><?php echo htmlspecialchars(strpos($product['ten_product'], '-') !== false ? substr($product['ten_product'], 0, strpos($product['ten_product'], '-')) : $product['ten_product']); ?></h3>
                        <p class="product-price"><?php echo number_format($product['gia_product'], 0, ',', '.'); ?> đ</p>
                        <p class="product-description" style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; min-height: 1.6em; line-height: 1.6em; margin: 8px 0;"><?php echo htmlspecialchars(substr($product['mo_ta_product'], 0, 50)); ?>...</p>
                        <div class="product-actions">
                            <button class="btn btn-favorite wishlist-btn" 
                                    onclick="event.stopPropagation(); toggleWishlist(<?php echo $product['product_id']; ?>, this)" 
                                    title="Thêm vào yêu thích"
                                    data-product-id="<?php echo $product['product_id']; ?>"
                                    style="background: none;border: 1px solid var(--primary-color);font-size: 20px;/* cursor: pointer; */padding: 0;min-width: auto;padding-top: 5px;width: 100%;">
                                <i class="bi bi-heart wishlist-icon" style="font-size: 20px;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-products">Không có sản phẩm nào</p>
        <?php endif; ?>
    </div>
    
    <!-- Phân trang -->
    <div id="paginationContainer" style=" margin-bottom: 20px;">
        <?php echo isset($pagination) ? $pagination : ''; ?>
    </div>
</div>

<!-- Chi tiết sản phẩm -->
<div id="productModal" class="modal">
    <div class="modal-content" style=" height: 600px;">
        <span class="close" onclick="closeProductModal()" style="top: '0px'">&times;</span>
        <div id="productDetail" style=" height: 100%;"></div>
    </div>
</div>

</div>

<script>
// Dữ liệu danh mục (toàn bộ) từ PHP sang JS
const categoriesData = <?php 
    echo json_encode(array_map(function($cat) {
        return [
            'id' => $cat['categories_id'],
            'name' => $cat['ten_categories'],
            'parent' => $cat['parent_category_id']
        ];
    }, $categories));
?>;

// Người dùng đã đăng nhập hay chưa
window.isLoggedIn = <?php echo isset($is_logged_in) && $is_logged_in ? 'true' : 'false'; ?>;
console.log('User logged in:', window.isLoggedIn);

// Khôi phục trạng thái yêu thích từ database (nếu đã đăng nhập) hoặc localStorage
document.addEventListener('DOMContentLoaded', function() {
    if (window.isLoggedIn) {
        // Load từ database
        fetch('<?php echo SITE_URL; ?>index.php?action=wishlist&method=getList')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.wishlist) {
                    data.wishlist.forEach(productId => {
                        const btn = document.querySelector(`[data-product-id="${productId}"] .wishlist-icon`);
                        if (btn) {
                            btn.classList.remove('bi-heart');
                            btn.classList.add('bi-heart-fill');
                            btn.parentElement.style.color = 'red';
                        }
                    });
                }
            })
            .catch(error => console.error('Error loading wishlist:', error));
    } else {
        // Load từ localStorage cho user chưa đăng nhập
        const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        wishlist.forEach(productId => {
            const btn = document.querySelector(`[data-product-id="${productId}"] .wishlist-icon`);
            if (btn) {
                btn.classList.remove('bi-heart');
                btn.classList.add('bi-heart-fill');
                btn.parentElement.style.color = 'red';
            }
        });
    }
});
// Hiển thị danh mục con
function showSubcategories(parentId) {
    const subcategoriesList = document.getElementById('subcategoriesList');
    const container = document.getElementById('subcategoriesContainer');
    
    if (!parentId) {
        container.style.display = 'none';
        return;
    }
    
    // Tìm danh mục con
    const subcategories = categoriesData.filter(cat => cat.parent == parentId);
    
    if (subcategories.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    // Hiển thị danh mục con
    subcategoriesList.innerHTML = '';
    subcategories.forEach(subcat => {
        const btn = document.createElement('button');
        btn.className = 'btn btn-secondary subcategory-btn';
        btn.textContent = subcat.name;
        btn.dataset.categoryId = subcat.id;
        btn.onclick = function(e) {
            e.preventDefault();
            loadProducts(subcat.id);
            // Cập nhật trạng thái nút danh mục con (KHÔNG bỏ active danh mục cha)
            document.querySelectorAll('.subcategory-btn').forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-secondary');
            });
            this.classList.remove('btn-secondary');
            this.classList.add('btn-primary');
        };
        subcategoriesList.appendChild(btn);
    });
    
    container.style.display = 'block';
}
// Đóng danh mục con
function closeSubcategories() {
    document.getElementById('subcategoriesContainer').style.display = 'none';
}
// Thêm / Xóa yêu thích
function toggleWishlist(productId, button) {
    // Check if user is logged in
    if (!window.isLoggedIn) {
        showToast('Vui lòng đăng nhập để thêm vào yêu thích', 'warning');
        window.location.href = '<?php echo SITE_URL; ?>index.php?action=auth&method=login';
        return;
    }
    
    const icon = button.querySelector('.wishlist-icon');
    const isCurrentlyFavorited = icon.classList.contains('bi-heart-fill');
    
    // Determine action
    const method = isCurrentlyFavorited ? 'remove' : 'add';
    
    const formData = new FormData();
    formData.append('product_id', productId);
    
    // Send request to server
    fetch('<?php echo SITE_URL; ?>index.php?action=wishlist&method=' + method, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (isCurrentlyFavorited) {
                // Remove
                icon.classList.remove('bi-heart-fill');
                icon.classList.add('bi-heart');
                button.style.color = '#FFD700';
                showToast('Đã xóa khỏi yêu thích', 'info');
            } else {
                // Add
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill');
                button.style.color = 'red';
                showToast('Đã thêm vào yêu thích', 'success');
            }
        } else {
            showToast(data.message || data.error || 'Lỗi cập nhật yêu thích', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Lỗi kết nối', 'error');
    });
}
// Xem chi tiết sản phẩm
function viewProductDetail(productId) {
    // Load product detail
    fetch('<?php echo SITE_URL; ?>index.php?action=product&method=getDetail&id=' + productId)
        .then(response => response.json())
        .then(data => {
            if (data.product) {
                const product = data.product;
                const basePrice = Number(product.gia_product);
                const categoryId = Number(product.danh_muc_product);
                const isPizza = categoryId === 1; // Pizza category ID = 1
                
                // Store category for addToCart function
                window.currentProductCategory = categoryId;
                
                const detailHTML = `
                    <div style="height: 100%;display: grid;grid-template-columns: 1fr 1fr;gap: 30px;align-items: start;">
                        <!-- Left: Product Image -->
                        <div style=" display: flex; height: 100%; overflow: hidden; align-items: center; justify-content: center;">
                            <img src="<?php echo SITE_URL; ?>uploads/${product.hinh_anh_product}" 
                                 style="width: auto;height: 100%;border-radius: 8px;display: block;">
                        </div>
                        
                        <!-- Right: Product Info -->
                        <div>
                            <h2 style="margin: 0 0 10px 0; font-size: 20px;">
                                ${product.ten_product.includes('-') ? product.ten_product.split('-')[0].trim() : product.ten_product}
                            </h2>
                            
                            <div style=" margin-bottom: 10px;">
                                <p style="display: -webkit-box; -webkit-line-clamp: 6; -webkit-box-orient: vertical; overflow: hidden; min-height: 9.6em; line-height: 1.6em; margin: 0; color: #666;">
                                    ${product.mo_ta_product}
                                </p>
                            </div>
                            
                            <!-- Size Selection -->
                            ${isPizza ? `
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Chọn kích cỡ:</label>
                                <select id="sizeSelect" onchange="updatePriceBySize(${basePrice})" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                                    <option value="">-- Chọn kích cỡ --</option>
                                    <option value="Nhỏ">Nhỏ (- 30,000đ)</option>
                                    <option value="Vừa" selected>Vừa (giá cơ bản)</option>
                                    <option value="Lớn">Lớn (+ 50,000đ)</option>
                                </select>
                            </div>
                            ` : ''}
                            
                            <!-- Quantity Input -->
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Số lượng:</label>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <button onclick="changeQuantity(-1)" style="width: 40px; height: 40px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 4px; font-size: 18px;">−</button>
                                    <input type="number" id="quantityInput" value="1" min="1" style="width: 60px; padding: 8px; border: 1px solid #ddd; border-radius: 4px; text-align: center;">
                                    <button onclick="changeQuantity(1)" style="width: 40px; height: 40px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 4px; font-size: 18px;">+</button>
                                </div>
                            </div>

                            <p id="productPrice" style="font-size: 28px; font-weight: bold; color: var(--primary-color); margin: 0 0 10px 0;">
                                ${basePrice.toLocaleString('vi-VN')} đ
                            </p>
                            
                            <!-- Action Buttons -->
                            <div style="display: flex; gap: var(--spacing-md);">
                                <button class="btn btn-primary" onclick="addToCart(${product.product_id})" style="flex: 1;">
                                    Thêm vào giỏ hàng
                                </button>
                                <button class="btn btn-secondary" onclick="closeProductModal()" style="flex: 1;">
                                    Đóng
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                document.getElementById('productDetail').innerHTML = detailHTML;
                document.getElementById('productModal').style.display = 'block';
            }
        });
}
// Đóng modal chi tiết sản phẩm
function closeProductModal() {
    document.getElementById('productModal').style.display = 'none';
}
// Thay đổi số lượng
function changeQuantity(change) {
    const input = document.getElementById('quantityInput');
    const newValue = parseInt(input.value) + change;
    if (newValue >= 1) {
        input.value = newValue;
    }
}
// Cập nhật giá theo kích cỡ
function updatePriceBySize(basePrice) {
    const sizeSelect = document.getElementById('sizeSelect');
    const size = sizeSelect.value;
    let adjustedPrice = basePrice;
    
    if (size === 'Nhỏ') {
        adjustedPrice = basePrice - 30000;
    } else if (size === 'Vừa') {
        adjustedPrice = basePrice;
    } else if (size === 'Lớn') {
        adjustedPrice = basePrice + 50000;
    }
    
    // Ensure price is not negative
    if (adjustedPrice < 0) {
        adjustedPrice = 0;
    }
    
    document.getElementById('productPrice').textContent = adjustedPrice.toLocaleString('vi-VN') + ' đ';
}
// Thêm vào giỏ hàng
function addToCart(productId) {
    console.log('Adding to cart. User logged in:', window.isLoggedIn);
    
    if (!window.isLoggedIn) {
        showToast('Vui lòng đăng nhập để thêm vào giỏ hàng', 'warning');
        setTimeout(() => {
            window.location.href = '<?php echo SITE_URL; ?>index.php?action=auth&method=login';
        }, 1500);
        return;
    }
    
    const sizeSelect = document.getElementById('sizeSelect');
    const categoryId = window.currentProductCategory || 1;
    const isPizza = categoryId === 1;
    
    let size = '';
    if (isPizza) {
        size = sizeSelect.value;
        if (!size) {
            showToast('Vui lòng chọn kích cỡ', 'warning');
            return;
        }
    }
    
    const quantity = document.getElementById('quantityInput').value;
    
    // Get the adjusted price from the displayed price
    const priceText = document.getElementById('productPrice').textContent;
    const price = parseInt(priceText.replace(/[^\d]/g, ''));
    
    // Send to server
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('size', size);
    formData.append('quantity', quantity);
    formData.append('price', price);
    
    fetch('<?php echo SITE_URL; ?>index.php?action=cart&method=add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(`Đã thêm ${quantity} ${size} vào giỏ hàng`, 'success');
            closeProductModal();
        } else {
            showToast('Lỗi: ' + (data.error || 'Không thể thêm vào giỏ hàng'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Lỗi kết nối', 'error');
    });
}
// Đóng modal khi click bên ngoài
window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
// Tải sản phẩm theo danh mục
function loadProducts(categoryId, page = 1) {
    console.log('loadProducts called with categoryId:', categoryId, 'page:', page);    
    // Ẩn tiêu đề tìm kiếm khi tải danh mục
    document.getElementById('searchHeader').style.display = 'none';    
    // Nếu categoryId là null, ẩn danh mục con
    if (categoryId === null) {
        closeSubcategories();
    }    
    const url = categoryId 
        ? '<?php echo SITE_URL; ?>index.php?action=home&method=getProducts&category=' + categoryId + '&page=' + page
        : '<?php echo SITE_URL; ?>index.php?action=home&method=getProducts&page=' + page;
    
    console.log('Fetching URL:', url);    
    fetch(url)
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.html) {
                document.getElementById('productsContainer').innerHTML = data.html;
                document.getElementById('paginationContainer').innerHTML = data.pagination || '';
                // Update login status from server
                if (data.isLoggedIn !== undefined) {
                    window.isLoggedIn = data.isLoggedIn;
                }
                
                // Restore wishlist icons from localStorage
                const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
                document.querySelectorAll('.wishlist-btn').forEach(btn => {
                    const productId = parseInt(btn.getAttribute('data-product-id'));
                    const icon = btn.querySelector('.wishlist-icon');
                    if (wishlist.includes(productId)) {
                        icon.classList.remove('bi-heart');
                        icon.classList.add('bi-heart-fill');
                        btn.style.color = 'red';
                    } else {
                        icon.classList.remove('bi-heart-fill');
                        icon.classList.add('bi-heart');
                        btn.style.color = '';
                    }
                });
                
                // Update button styles
                updateCategoryButtons(categoryId);
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                console.error('No html in response');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Lỗi tải sản phẩm', 'error');
        });
}
// Xử lý gửi form tìm kiếm
function handleSearch(event) {
    event.preventDefault();
    const keyword = document.getElementById('searchInput').value.trim();
    
    if (!keyword) {
        showToast('Vui lòng nhập từ khóa tìm kiếm', 'warning');
        return;
    }
    
    // Ẩn nút danh mục và hiển thị tiêu đề tìm kiếm
    document.querySelector('.filter-section').style.display = 'none';
    const searchHeader = document.getElementById('searchHeader');
    searchHeader.style.display = 'block';
    document.getElementById('searchKeyword').textContent = keyword;
    
    // Tải sản phẩm theo từ khóa
    const url = '<?php echo SITE_URL; ?>index.php?action=home&method=searchProducts&q=' + encodeURIComponent(keyword);
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                document.getElementById('productsContainer').innerHTML = data.html;
                document.getElementById('searchCount').textContent = data.count || 0;
                // Cập nhật trạng thái đăng nhập từ server
                if (data.isLoggedIn !== undefined) {
                    window.isLoggedIn = data.isLoggedIn;
                }
                
                // Khôi phục biểu tượng yêu thích từ localStorage
                const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
                document.querySelectorAll('.wishlist-btn').forEach(btn => {
                    const productId = parseInt(btn.getAttribute('data-product-id'));
                    const icon = btn.querySelector('.wishlist-icon');
                    if (wishlist.includes(productId)) {
                        icon.classList.remove('bi-heart');
                        icon.classList.add('bi-heart-fill');
                        btn.style.color = 'red';
                    } else {
                        icon.classList.remove('bi-heart-fill');
                        icon.classList.add('bi-heart');
                        btn.style.color = '';
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Lỗi tìm kiếm', 'error');
        });
}
// Xóa tìm kiếm và trở về hiển thị tất cả sản phẩm
function clearSearch() {
    document.getElementById('searchInput').value = '';
    document.querySelector('.filter-section').style.display = 'block';
    document.getElementById('searchHeader').style.display = 'none';
    loadProducts(null);
}
// Cập nhật trạng thái nút danh mục
function updateCategoryButtons(categoryId) {
    // Trả về trạng thái mặc định cho tất cả nút
    document.querySelectorAll('.filter-group button').forEach(btn => {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-secondary');
    });
    
    // Thêm trạng thái active cho nút tương ứng
    if (categoryId === null) {
        document.querySelectorAll('.filter-group button')[0].classList.remove('btn-secondary');
        document.querySelectorAll('.filter-group button')[0].classList.add('btn-primary');
    } else {
        // Tìm nút có data-category-id tương ứng
        const targetBtn = document.querySelector(`[data-category-id="${categoryId}"]`);
        if (targetBtn) {
            targetBtn.classList.remove('btn-secondary');
            targetBtn.classList.add('btn-primary');
        }
    }
}
</script>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
