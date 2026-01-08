<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div class="container">
    <div class="cart-section" style=" margin-bottom: 20px;">
        <h2>Giỏ Hàng</h2>
        
        <?php if (empty($cart)): ?>
            <p class="empty-cart">Giỏ hàng của bạn trống. <a href="<?php echo SITE_URL; ?>index.php?action=home&method=index">Tiếp tục mua sắm</a></p>
        <?php else: ?>
            <!-- Cart Layout: Left Grid + Right Summary -->
            <div style="display: grid; grid-template-columns: 1fr 350px; gap: 25px; align-items: start;">
                
                <!-- LEFT: Cart Grid với Checkboxes -->
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h3 style="margin: 0; font-size: 18px;">Sản phẩm của bạn</h3>
                        <button id="selectAllBtn" onclick="toggleSelectAll()" style="background: var(--primary-color); color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: 500; font-size: 14px; transition: background 0.3s ease;">
                            Chọn tất cả
                        </button>
                    </div>
                    <div class="products-grid">
                        <?php 
                        $total = 0;
                        foreach ($cart as $cartKey => $item): 
                            $itemTotal = $item['price'] * $item['quantity'];
                            $total += $itemTotal;
                        ?>
                            <div class="product-card" style="position: relative;">
                                <!-- Checkbox -->
                                <div style="position: absolute; top: 10px; left: 10px; z-index: 10;">
                                    <input type="checkbox" class="product-checkbox" data-cart-key="<?php echo htmlspecialchars($cartKey); ?>" 
                                           data-price="<?php echo $item['price']; ?>" data-quantity="<?php echo $item['quantity']; ?>"
                                           data-size="<?php echo htmlspecialchars($item['size'] ?? 'Vừa'); ?>"
                                           style="width: 20px; height: 20px; cursor: pointer;" onchange="updateSelectAllButton(); updateSummary()">
                                </div>
                                
                                <!-- Product Image -->
                                <div class="product-image">
                                    <img src="<?php echo SITE_URL; ?>uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($item['name']); ?>">
                                </div>
                                
                                <!-- Product Info -->
                                <div class="product-info">
                                    <h3 style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 3.2em; line-height: 1.6em; margin: 0 0 8px 0;">
                                        <?php echo htmlspecialchars($item['name']); ?>
                                    </h3>
                                    
                                    <div style="margin: 4px 0 8px 0; padding: 8px; background: #f5f5f5; border-radius: 4px; min-height: 40px;">
                                        <?php if (isset($item['size']) && !empty($item['size'])): ?>
                                        <label style="display: block; margin-bottom: 4px; font-size: 12px; font-weight: 600; color: #666;">Size:</label>
                                        <select class="size-select" data-cart-key="<?php echo htmlspecialchars($cartKey); ?>" 
                                                style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 3px; font-size: 12px; cursor: pointer;"
                                                onchange="changeSize(this, this.value)">
                                            <option value="Nhỏ" <?php echo $item['size'] === 'Nhỏ' ? 'selected' : ''; ?>>Nhỏ (- 30,000đ)</option>
                                            <option value="Vừa" <?php echo $item['size'] === 'Vừa' ? 'selected' : ''; ?>>Vừa (giá cơ bản)</option>
                                            <option value="Lớn" <?php echo $item['size'] === 'Lớn' ? 'selected' : ''; ?>>Lớn (+ 50,000đ)</option>
                                        </select>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Quantity Control -->
                                    <form class="quantity-form" data-cart-key="<?php echo htmlspecialchars($cartKey); ?>"
                                          style="display: flex; gap: 3px; margin-bottom: 10px;">
                                        <input type="hidden" name="cart_key" value="<?php echo htmlspecialchars($cartKey); ?>">
                                        <button type="button" class="qty-minus" style="width: 28px; height: 28px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 3px; font-size: 14px;">−</button>
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" 
                                               class="qty-input" style="flex: 1; padding: 4px; border: 1px solid #ddd; border-radius: 3px; text-align: center; font-size: 12px;">
                                        <button type="button" class="qty-plus" style="width: 28px; height: 28px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 3px; font-size: 14px;">+</button>
                                    </form>
                                    
                                    <p style="margin: 6px 0 10px 0; padding: 8px; background: #f0f0f0; border-radius: 4px; text-align: center; font-size: 20px; color: #666;">
                                        Tổng: <strong style="color: var(--primary-color);" data-item-total><?php echo number_format($itemTotal, 0, ',', '.'); ?> đ</strong>
                                    </p>
                                    
                                    <!-- Delete Button -->
                                    <button onclick="removeFromCart('<?php echo htmlspecialchars($cartKey); ?>')" 
                                            style="width: 100%; background: #f44336; color: white; border: none; padding: 8px; border-radius: 4px; cursor: pointer; font-weight: 500; font-size: 12px; transition: background 0.3s ease;">
                                        Xóa
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- RIGHT: Tóm tắt đơn hàng  Summary   -->
                <div style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);color: #000033;padding: 20px;border-radius: 8px;position: sticky;top: 100px;height: fit-content;">
                    <h3 style="margin: 0 0 20px 0; font-size: 18px; border-bottom: 2px solid rgba(255,255,255,0.2); padding-bottom: 15px;">Tóm tắt đơn hàng</h3>
                    
                    <div style="margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                            <span>Số sản phẩm chọn:</span>
                            <strong id="selectedCount">0</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                            <span>Tổng tiền hàng:</span>
                            <strong id="subtotal">0 đ</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; padding-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.2);">
                            <span>Phí vận chuyển:</span>
                            <strong>30,000 đ</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 16px; font-weight: 700;">
                            <span>Tổng thanh toán:</span>
                            <strong id="totalPayment">30,000 đ</strong>
                        </div>
                    </div>
                    
                    <!-- Nút hành động -->
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button onclick="proceedToCheckout()" 
                           class="btn btn-primary" style="width: 100%; text-align: center; text-decoration: none; background: white; color: var(--primary-color); font-weight: 600; border: none; cursor: pointer; padding: 12px; border-radius: 4px;">
                            Thanh toán ngay
                        </button>
                        <button onclick="deleteAllCart()" 
                                style="width: 100%;background: rgb(255 0 0 / 20%);color: red;border: 1px solid #ff0000;padding: 12px;border-radius: 4px;cursor: pointer;font-weight: 500;font-size: 14px;transition: background 0.3s ease;">
                            Xóa toàn bộ
                        </button>
                    </div>
                </div>
                
            </div>
            
        <?php endif; ?>
    </div>
</div>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>

<script>
const SHIPPING_FEE = 30000;

function updateSelectAllButton() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const checkedCount = document.querySelectorAll('.product-checkbox:checked').length;
    const btn = document.getElementById('selectAllBtn');
    
    if (checkedCount === checkboxes.length && checkboxes.length > 0) {
        btn.textContent = 'Bỏ chọn tất cả';
    } else {
        btn.textContent = 'Chọn tất cả';
    }
}
// Khởi tạo trạng thái nút khi tải trang
function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const btn = document.getElementById('selectAllBtn');
    const allChecked = document.querySelectorAll('.product-checkbox:checked').length === checkboxes.length;
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });
    
    btn.textContent = allChecked ? 'Chọn tất cả' : 'Bỏ chọn tất cả';
    updateSummary();
}
// Cập nhật tóm tắt đơn hàng
function updateSummary() {
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');
    let selectedCount = 0;
    let subtotal = 0;
    
    checkboxes.forEach(checkbox => {
        selectedCount += parseInt(checkbox.dataset.quantity);
        subtotal += parseInt(checkbox.dataset.price) * parseInt(checkbox.dataset.quantity);
    });
    
    const totalPayment = subtotal + SHIPPING_FEE;
    
    document.getElementById('selectedCount').textContent = selectedCount;
    document.getElementById('subtotal').textContent = subtotal.toLocaleString('vi-VN') + ' đ';
    document.getElementById('totalPayment').textContent = totalPayment.toLocaleString('vi-VN') + ' đ';
}

// Xử lý thay đổi số lượng
document.querySelectorAll('.quantity-form').forEach(form => {
    const cartKey = form.dataset.cartKey;
    const qtyInput = form.querySelector('.qty-input');
    const minusBtn = form.querySelector('.qty-minus');
    const plusBtn = form.querySelector('.qty-plus');
    
    minusBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const newQty = Math.max(1, parseInt(qtyInput.value) - 1);
        qtyInput.value = newQty;
        updateQuantity(cartKey, newQty);
    });
    
    plusBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const newQty = parseInt(qtyInput.value) + 1;
        qtyInput.value = newQty;
        updateQuantity(cartKey, newQty);
    });
    
    qtyInput.addEventListener('change', function() {
        const newQty = Math.max(1, parseInt(this.value));
        this.value = newQty;
        updateQuantity(cartKey, newQty);
    });
});
// Cập nhật số lượng trong giỏ hàng
function updateQuantity(cartKey, quantity) {
    const formData = new FormData();
    formData.append('cart_key', cartKey);
    formData.append('quantity', quantity);
    formData.append('action', 'update');
    
    fetch('<?php echo SITE_URL; ?>index.php?action=cart&method=update', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update checkbox data attribute for summary calculation
            const checkbox = document.querySelector(`.product-checkbox[data-cart-key="${cartKey}"]`);
            if (checkbox) {
                const price = parseInt(checkbox.dataset.price);
                checkbox.dataset.quantity = quantity;
                
                // Update the cart item total display - find the correct element
                const productCard = checkbox.closest('.product-card');
                if (productCard) {
                    const itemTotalEl = productCard.querySelector('[data-item-total]');
                    if (itemTotalEl) {
                        const newTotal = price * quantity;
                        itemTotalEl.textContent = newTotal.toLocaleString('vi-VN') + ' đ';
                    }
                }
            }
            updateSummary();
        }
    })
    .catch(error => console.error('Error:', error));
}
// Xóa
function removeFromCart(cartKey) {
    const formData = new FormData();
    formData.append('cart_key', cartKey);
    
    fetch('<?php echo SITE_URL; ?>index.php?action=cart&method=remove', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Đã xóa sản phẩm khỏi giỏ hàng', 'success');
            setTimeout(() => location.reload(), 500);
        } else {
            showToast('Lỗi: ' + (data.error || 'Không thể xóa sản phẩm'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Lỗi kết nối', 'error');
    });
}
// Xóa toàn bộ giỏ hàng
function deleteAllCart() {
    if (!confirm('Bạn chắc chắn muốn xóa toàn bộ sản phẩm trong giỏ hàng?')) {
        return;
    }
    
    const checkboxes = document.querySelectorAll('.product-checkbox');
    let deleteCount = 0;
    
    checkboxes.forEach(checkbox => {
        const cartKey = checkbox.dataset.cartKey;
        const formData = new FormData();
        formData.append('cart_key', cartKey);
        
        fetch('<?php echo SITE_URL; ?>index.php?action=cart&method=remove', {
            method: 'POST',
            body: formData
        }).then(response => response.json()).then(data => {
            if (data.success) {
                deleteCount++;
                if (deleteCount === checkboxes.length) {
                    showToast('Đã xóa toàn bộ sản phẩm', 'success');
                    setTimeout(() => location.reload(), 500);
                }
            }
        });
    });
}
// Tiến hành thanh toán
function proceedToCheckout() {
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');
    
    if (checkboxes.length === 0) {
        showToast('Vui lòng chọn ít nhất 1 sản phẩm để thanh toán', 'warning');
        return;
    }
    
    // Get selected cart keys
    const selectedKeys = [];
    checkboxes.forEach(checkbox => {
        selectedKeys.push(checkbox.dataset.cartKey);
    });
    
    // Store selected items in session via POST
    const formData = new FormData();
    formData.append('action', 'setSelectedItems');
    formData.append('selectedKeys', JSON.stringify(selectedKeys));
    
    fetch('<?php echo SITE_URL; ?>index.php?action=cart&method=setSelected', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect to checkout
            window.location.href = '<?php echo SITE_URL; ?>index.php?action=order&method=checkout';
        } else {
            showToast('Lỗi: ' + (data.error || 'Không thể tiếp tục'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Lỗi kết nối', 'error');
    });
}
// Thay đổi size sản phẩm
function changeSize(selectElement, newSize) {
    console.log('changeSize called with:', { selectElement, newSize });
    
    // Get the select element and product card
    const sizeSelect = selectElement;
    if (!sizeSelect) {
        console.error('Size select element is null');
        showToast('Lỗi: Không tìm thấy phần tử chọn size', 'error');
        return;
    }
    
    const cartKey = sizeSelect.dataset.cartKey;
    console.log('Cart key from element:', cartKey);
    
    const productCard = sizeSelect.closest('.product-card');
    if (!productCard) {
        console.error('Product card not found');
        showToast('Lỗi: Không tìm thấy thẻ sản phẩm', 'error');
        return;
    }
    
    const checkbox = productCard.querySelector('.product-checkbox');
    if (!checkbox) {
        console.error('Checkbox not found');
        showToast('Lỗi: Không tìm thấy checkbox', 'error');
        return;
    }
    
    // Get the current size from data-size attribute
    const currentSize = checkbox.dataset.size || 'Vừa';
    const productId = checkbox.dataset.cartKey.split('_')[0]; // Extract product_id from cartKey
    
    // Construct the ACTUAL current cartKey based on current size
    const actualCurrentKey = productId + '_' + currentSize;
    
    console.log('Actual current key:', actualCurrentKey, 'Current size:', currentSize);
    
    // Get the base price (current price stored in checkbox)
    let basePrice = parseInt(checkbox.dataset.price);
    let adjustedPrice = basePrice;
    
    console.log('Current price:', basePrice, 'Current size:', currentSize);
    
    // Reverse the current size adjustment to get the original Vừa price
    if (currentSize === 'Nhỏ') {
        basePrice = basePrice + 30000; // Was reduced by 30k, so add back
    } else if (currentSize === 'Lớn') {
        basePrice = basePrice - 50000; // Was increased by 50k, so subtract
    }
    // If currentSize === 'Vừa', basePrice is already correct
    
    // Now apply the new size adjustment
    if (newSize === 'Nhỏ') {
        adjustedPrice = basePrice - 30000;
    } else if (newSize === 'Vừa') {
        adjustedPrice = basePrice;
    } else if (newSize === 'Lớn') {
        adjustedPrice = basePrice + 50000;
    }
    
    // Ensure price is not negative
    if (adjustedPrice < 0) {
        adjustedPrice = 0;
    }
    
    console.log('Sending to server:', { cart_key: actualCurrentKey, newSize, new_price: adjustedPrice });
    
    const formData = new FormData();
    formData.append('cart_key', actualCurrentKey);  // Use the actual current key
    formData.append('new_size', newSize);
    formData.append('new_price', adjustedPrice);
    
    fetch('<?php echo SITE_URL; ?>index.php?action=cart&method=changeSize', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Server response:', data);
        if (data.success) {
            // Update checkbox data attributes
            checkbox.dataset.price = adjustedPrice;
            checkbox.dataset.size = newSize;
            checkbox.dataset.quantity = checkbox.dataset.quantity || 1;
            
            // Update the select element's data-cart-key for next change
            const newCartKey = productId + '_' + newSize;
            sizeSelect.dataset.cartKey = newCartKey;
            
            // Update the item total display
            const itemTotalEl = productCard.querySelector('[data-item-total]');
            if (itemTotalEl) {
                const quantity = parseInt(checkbox.dataset.quantity);
                const newTotal = adjustedPrice * quantity;
                itemTotalEl.textContent = newTotal.toLocaleString('vi-VN') + ' đ';
            }
            
            updateSummary();
            showToast('Đã cập nhật size', 'success');
        } else {
            showToast(data.error || 'Lỗi cập nhật size', 'error');
            // Revert select to original size if save fails
            sizeSelect.value = currentSize;
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showToast('Lỗi kết nối', 'error');
        // Revert select to original size if request fails
        sizeSelect.value = currentSize;
    });
}
</script>
