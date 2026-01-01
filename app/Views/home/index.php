<?php
$page_title = 'Trang chủ';
include APP_PATH . 'Views/layout/header.php';
?>

<div class="banner-section">
    <img src="<?php echo SITE_URL; ?>uploads/banner.jpg" alt="Banner" class="banner-image">
</div>

<div class="container">
    <!-- Search Result Header (hidden by default) -->
    <div id="searchHeader" style="display: none; margin: var(--spacing-xl) 0; padding: var(--spacing-lg); background: var(--bg-light); border-radius: 8px; text-align: center;">
        <p style="font-size: 16px; color: var(--text-dark); margin: 0;">
            Tìm kiếm: <strong id="searchKeyword"></strong>
            <span style="color: var(--text-light); margin-left: var(--spacing-md);">
                (<span id="searchCount">0</span> kết quả)
            </span>
            <a href="#" onclick="clearSearch(); return false;" style="margin-left: var(--spacing-md); color: var(--primary-color); text-decoration: none;">✕ Xóa tìm kiếm</a>
        </p>
    </div>

    <!-- Filter by categories -->
    <div class="filter-section">
        <div class="filter-group">
            <div style="display: flex; gap: var(--spacing-md); flex-wrap: wrap;">
                <button class="btn <?php echo empty($selected_category) ? 'btn-primary' : 'btn-secondary'; ?>" 
                        onclick="loadProducts(null)">
                    Tất cả
                </button>
                <?php foreach ($categories as $category): ?>
                    <button class="btn <?php echo $selected_category == $category['categories_id'] ? 'btn-primary' : 'btn-secondary'; ?>" 
                            onclick="loadProducts(<?php echo $category['categories_id']; ?>)">
                        <?php echo htmlspecialchars($category['ten_categories']); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Products list -->
    <div id="productsContainer" class="products-grid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo SITE_URL; ?>uploads/<?php echo htmlspecialchars($product['hinh_anh_product']); ?>" 
                             alt="<?php echo htmlspecialchars($product['ten_product']); ?>">
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['ten_product']); ?></h3>
                        <p class="product-price"><?php echo number_format($product['gia_product'], 0, ',', '.'); ?> đ</p>
                        <p class="product-description"><?php echo htmlspecialchars(substr($product['mo_ta_product'], 0, 50)); ?>...</p>
                        <div class="product-actions">
                            <button class="btn btn-primary" onclick="viewProductDetail(<?php echo $product['product_id']; ?>)">
                                Chi tiết
                            </button>
                            <button class="btn btn-favorite wishlist-btn" 
                                    onclick="toggleWishlist(<?php echo $product['product_id']; ?>, this)" 
                                    title="Thêm vào yêu thích"
                                    data-product-id="<?php echo $product['product_id']; ?>"
                                    style="background: none; border: 1px solid; font-size: 24px; cursor: pointer; padding: 0; min-width: auto;">
                                <span class="wishlist-icon">♡</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-products">Không có sản phẩm nào</p>
        <?php endif; ?>
    </div>
</div>

<!-- Product detail modal -->
<div id="productModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeProductModal()">&times;</span>
        <div id="productDetail"></div>
    </div>
</div>

</div>

<script>
// User login status (global variable)
window.isLoggedIn = <?php echo isset($is_logged_in) && $is_logged_in ? 'true' : 'false'; ?>;
console.log('User logged in:', window.isLoggedIn);

// Load wishlist from localStorage on page load
document.addEventListener('DOMContentLoaded', function() {
    const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    wishlist.forEach(productId => {
        const btn = document.querySelector(`[data-product-id="${productId}"] .wishlist-icon`);
        if (btn) {
            btn.textContent = '♥';
            btn.parentElement.style.color = 'red';
        }
    });
});

function toggleWishlist(productId, button) {
    // Check if user is logged in
    if (!window.isLoggedIn) {
        showToast('Vui lòng đăng nhập để thêm vào yêu thích', 'warning');
        window.location.href = '<?php echo SITE_URL; ?>index.php?action=auth&method=login';
        return;
    }
    
    const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    const index = wishlist.indexOf(productId);
    const icon = button.querySelector('.wishlist-icon');
    
    if (index > -1) {
        // Remove from wishlist
        wishlist.splice(index, 1);
        icon.textContent = '♡';
        button.style.color = 'inherit';
    } else {
        // Add to wishlist
        wishlist.push(productId);
        icon.textContent = '♥';
        button.style.color = 'red';
    }
    
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
}

function viewProductDetail(productId) {
    // Load product detail
    fetch('<?php echo SITE_URL; ?>index.php?action=product&method=getDetail&id=' + productId)
        .then(response => response.json())
        .then(data => {
            if (data.product) {
                const product = data.product;
                const detailHTML = `
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: start;">
                        <!-- Left: Product Image -->
                        <div>
                            <img src="<?php echo SITE_URL; ?>uploads/${product.hinh_anh_product}" 
                                 style="width: 100%; height: auto; border-radius: 8px; display: block;">
                        </div>
                        
                        <!-- Right: Product Info -->
                        <div>
                            <h2 style="margin: 0 0 var(--spacing-md) 0; font-size: 28px;">
                                ${product.ten_product}
                            </h2>
                            
                            <p style="font-size: 28px; font-weight: 600; color: var(--primary-color); margin: 0 0 var(--spacing-md) 0;">
                                ${Number(product.gia_product).toLocaleString('vi-VN')} đ
                            </p>
                            
                            <div style="background: #f5f5f5; padding: var(--spacing-md); border-radius: 6px; margin-bottom: var(--spacing-md);">
                                <p style="margin: 0; line-height: 1.6; color: #666;">
                                    ${product.mo_ta_product}
                                </p>
                            </div>
                            
                            <!-- Size Selection -->
                            <div style="margin-bottom: var(--spacing-lg);">
                                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Chọn kích cỡ:</label>
                                <select id="sizeSelect" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                                    <option value="">-- Chọn kích cỡ --</option>
                                    <option value="Nhỏ">Nhỏ</option>
                                    <option value="Vừa">Vừa</option>
                                    <option value="Lớn">Lớn</option>
                                </select>
                            </div>
                            
                            <!-- Quantity Input -->
                            <div style="margin-bottom: var(--spacing-lg);">
                                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Số lượng:</label>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <button onclick="changeQuantity(-1)" style="width: 40px; height: 40px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 4px; font-size: 18px;">−</button>
                                    <input type="number" id="quantityInput" value="1" min="1" style="width: 60px; padding: 8px; border: 1px solid #ddd; border-radius: 4px; text-align: center;">
                                    <button onclick="changeQuantity(1)" style="width: 40px; height: 40px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 4px; font-size: 18px;">+</button>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div style="display: flex; gap: var(--spacing-md);">
                                <button class="btn btn-primary" onclick="addToCart(${product.product_id})" style="flex: 1;">
                                    🛒 Thêm vào giỏ hàng
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

function closeProductModal() {
    document.getElementById('productModal').style.display = 'none';
}

function changeQuantity(change) {
    const input = document.getElementById('quantityInput');
    const newValue = parseInt(input.value) + change;
    if (newValue >= 1) {
        input.value = newValue;
    }
}

function addToCart(productId) {
    // Check if user is logged in
    console.log('Adding to cart. User logged in:', window.isLoggedIn);
    
    if (!window.isLoggedIn) {
        showToast('Vui lòng đăng nhập để thêm vào giỏ hàng', 'warning');
        // Redirect to login after a short delay
        setTimeout(() => {
            window.location.href = '<?php echo SITE_URL; ?>index.php?action=auth&method=login';
        }, 1500);
        return;
    }
    
    const size = document.getElementById('sizeSelect').value;
    const quantity = document.getElementById('quantityInput').value;
    
    if (!size) {
        showToast('Vui lòng chọn kích cỡ', 'warning');
        return;
    }
    
    // Send to server
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('size', size);
    formData.append('quantity', quantity);
    
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

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Load products by category via AJAX
function loadProducts(categoryId) {
    // Hide search header when loading category
    document.getElementById('searchHeader').style.display = 'none';
    
    const url = categoryId 
        ? '<?php echo SITE_URL; ?>index.php?action=home&method=getProducts&category=' + categoryId
        : '<?php echo SITE_URL; ?>index.php?action=home&method=getProducts';
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                document.getElementById('productsContainer').innerHTML = data.html;
                // Update login status from server
                if (data.isLoggedIn !== undefined) {
                    window.isLoggedIn = data.isLoggedIn;
                }
                // Update button styles
                updateCategoryButtons(categoryId);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Lỗi tải sản phẩm', 'error');
        });
}

// Handle search form submission
function handleSearch(event) {
    event.preventDefault();
    const keyword = document.getElementById('searchInput').value.trim();
    
    if (!keyword) {
        showToast('Vui lòng nhập từ khóa tìm kiếm', 'warning');
        return;
    }
    
    // Hide category buttons and show search header
    document.querySelector('.filter-section').style.display = 'none';
    const searchHeader = document.getElementById('searchHeader');
    searchHeader.style.display = 'block';
    document.getElementById('searchKeyword').textContent = keyword;
    
    // Fetch search results
    const url = '<?php echo SITE_URL; ?>index.php?action=home&method=searchProducts&q=' + encodeURIComponent(keyword);
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                document.getElementById('productsContainer').innerHTML = data.html;
                document.getElementById('searchCount').textContent = data.count || 0;
                // Update login status from server
                if (data.isLoggedIn !== undefined) {
                    window.isLoggedIn = data.isLoggedIn;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Lỗi tìm kiếm', 'error');
        });
}

// Clear search and go back to showing all products
function clearSearch() {
    document.getElementById('searchInput').value = '';
    document.querySelector('.filter-section').style.display = 'block';
    document.getElementById('searchHeader').style.display = 'none';
    loadProducts(null);
}

function updateCategoryButtons(categoryId) {
    // Remove active state from all buttons
    document.querySelectorAll('.filter-group button').forEach(btn => {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-secondary');
    });
    
    // Add active state to selected button
    if (categoryId === null) {
        document.querySelectorAll('.filter-group button')[0].classList.remove('btn-secondary');
        document.querySelectorAll('.filter-group button')[0].classList.add('btn-primary');
    } else {
        document.querySelectorAll('.filter-group button').forEach((btn, idx) => {
            if (btn.onclick && btn.onclick.toString().includes(categoryId)) {
                btn.classList.remove('btn-secondary');
                btn.classList.add('btn-primary');
            }
        });
    }
}
</script>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
