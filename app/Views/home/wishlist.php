<?php
$page_title = 'Danh sách yêu thích';
include APP_PATH . 'Views/layout/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><i class="bi bi-heart-fill"></i> Danh sách yêu thích</h1>
    </div>

    <div id="wishlistContainer">
        <?php if (empty($wishlists)): ?>
            <div style="text-align: center; padding: var(--spacing-lg);">
                <p style="color: var(--text-muted); margin-bottom: var(--spacing-md);">Chưa có sản phẩm yêu thích</p>
                <a href="<?php echo SITE_URL; ?>index.php?action=home&method=index" class="btn btn-primary">Quay lại mua sắm</a>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($wishlists as $product): ?>
                    <div class="product-card" onclick="viewProductDetail(<?php echo $product['product_id']; ?>)" style="cursor: pointer;">
                        <div class="product-image">
                            <img src="<?php echo SITE_URL; ?>uploads/<?php echo htmlspecialchars($product['hinh_anh_product']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['ten_product']); ?>">
                        </div>
                        <div class="product-info">
                            <h3 style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 3.2em; line-height: 1.6em; margin: 0 0 8px 0;">
                                <?php echo htmlspecialchars(strpos($product['ten_product'], '-') !== false ? substr($product['ten_product'], 0, strpos($product['ten_product'], '-')) : $product['ten_product']); ?>
                            </h3>
                            <p class="product-price"><?php echo number_format($product['gia_product'], 0, ',', '.'); ?> đ</p>
                            <p class="product-description" style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; min-height: 1.6em; line-height: 1.6em; margin: 8px 0;">
                                <?php echo htmlspecialchars(substr($product['mo_ta_product'], 0, 50)); ?>...
                            </p>
                            <div class="product-actions">
                                <button class="btn btn-favorite wishlist-btn" 
                                        onclick="event.stopPropagation(); removeFromWishlist(<?php echo $product['product_id']; ?>)" 
                                        title="Bỏ yêu thích"
                                        style="background: red;border: none;font-size: 16px;cursor: pointer;padding: 0;min-width: auto;color: white;padding: 6px 10px;width: 100%;">
                                    Bỏ yêu thích
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Product detail modal -->
<div id="productModal" class="modal">
    <div class="modal-content" style=" height: 600px;">
        <span class="close" onclick="closeProductModal()">&times;</span>
        <div id="productDetail" style=" height: 100%;"></div>
    </div>
</div>

<script>
// User login status (global variable)
window.isLoggedIn = true;

function viewProductDetail(productId) {
    fetch('<?php echo SITE_URL; ?>index.php?action=home&method=getDetail&id=' + productId)
        .then(response => response.json())
        .then(data => {
            if (data.product) {
                const product = data.product;
                const categoryId = Number(product.danh_muc_product);
                const isPizza = categoryId === 1;
                window.currentProductCategory = categoryId;
                
                const sizeOptions = isPizza ? `
                    <div style="margin-bottom: 15px;">
                        <label for="sizeSelect" style="display: block; margin-bottom: 8px; font-weight: 600;">Chọn kích cỡ:</label>
                        <select id="sizeSelect" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; cursor: pointer;">
                            <option value="">-- Chọn size --</option>
                            <option value="Nhỏ">Nhỏ (Giá gốc - 30,000đ)</option>
                            <option value="Vừa">Vừa (Giá gốc)</option>
                            <option value="Lớn">Lớn (Giá gốc + 50,000đ)</option>
                        </select>
                    </div>
                ` : '';
                
                const html = `
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; height: 100%; padding: 20px; box-sizing: border-box; overflow-y: auto;">
                        <div>
                            <img src="<?php echo SITE_URL; ?>uploads/${product.hinh_anh_product}" 
                                 alt="${product.ten_product}" 
                                 style="width: 100%; height: 350px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">
                        </div>
                        <div style="overflow-y: auto;">
                            <h2 style="margin: 0 0 10px 0; font-size: 20px;">${product.ten_product}</h2>
                            <p class="product-price" id="productPrice" style="font-size: 24px; margin: 10px 0;">${Number(product.gia_product).toLocaleString('vi-VN')} đ</p>
                            <p style="color: #666; margin: 10px 0; line-height: 1.6;">${product.mo_ta_product}</p>
                            
                            ${sizeOptions}
                            
                            <div style="margin-bottom: 15px;">
                                <label for="quantityInput" style="display: block; margin-bottom: 8px; font-weight: 600;">Số lượng:</label>
                                <input type="number" id="quantityInput" value="1" min="1" 
                                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                            </div>
                            
                            <button onclick="addToCart(${product.product_id})" 
                                    style="width: 100%; padding: 12px; background: var(--primary-color); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 600; margin-bottom: 10px;">
                                <i class="bi bi-cart-plus"></i> Thêm vào giỏ hàng
                            </button>
                            <button onclick="closeProductModal()" 
                                    style="width: 100%; padding: 12px; background: #f5f5f5; color: #333; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 600;">
                                Đóng
                            </button>
                        </div>
                    </div>
                `;
                document.getElementById('productDetail').innerHTML = html;
                document.getElementById('productModal').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Lỗi tải chi tiết sản phẩm', 'error');
        });
}

function closeProductModal() {
    document.getElementById('productModal').style.display = 'none';
}

function addToCart(productId) {
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
    
    const priceText = document.getElementById('productPrice').textContent;
    const price = parseInt(priceText.replace(/[^\d]/g, ''));
    
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

function removeFromWishlist(productId) {
    const formData = new FormData();
    formData.append('product_id', productId);
    
    fetch('<?php echo SITE_URL; ?>index.php?action=wishlist&method=remove', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Đã xóa khỏi danh sách yêu thích', 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showToast(data.error || 'Lỗi xóa khỏi danh sách yêu thích', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Lỗi kết nối', 'error');
    });
}

window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}
</script>

<!-- Product detail modal -->
<div id="productModal" class="modal">
    <div class="modal-content" style=" height: 600px;">
        <span class="close" onclick="closeProductModal()">&times;</span>
        <div id="productDetail" style=" height: 100%;"></div>
    </div>
</div>

<script>
// User login status (global variable)
window.isLoggedIn = true;

function viewProductDetail(productId) {
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

function addToCart(productId) {
    // Check if user is logged in
    if (!window.isLoggedIn) {
        showToast('Vui lòng đăng nhập để thêm vào giỏ hàng', 'warning');
        // Redirect to login after a short delay
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

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}</script>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
