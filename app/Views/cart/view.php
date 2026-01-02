<?php include APP_PATH . 'Views/layout/header.php'; ?>

<div class="container">
    <div class="cart-section" style=" margin-bottom: 20px;">
        <h2>Giỏ Hàng</h2>
        
        <?php if (empty($cart)): ?>
            <p class="empty-cart">Giỏ hàng của bạn trống. <a href="<?php echo SITE_URL; ?>index.php?action=home&method=index">Tiếp tục mua sắm</a></p>
        <?php else: ?>
            <!-- Cart Layout: Left Grid + Right Summary -->
            <div style="display: grid; grid-template-columns: 1fr 350px; gap: 25px; align-items: start;">
                
                <!-- LEFT: Cart Grid with Checkboxes -->
                <div>
                    <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 18px;">Sản phẩm của bạn</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 15px;">
                        <?php 
                        $total = 0;
                        foreach ($cart as $cartKey => $item): 
                            $itemTotal = $item['price'] * $item['quantity'];
                            $total += $itemTotal;
                        ?>
                            <div class="cart-item" style="background: white; border: 2px solid #e0e0e0; border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; position: relative;">
                                <!-- Checkbox -->
                                <div style="position: absolute; top: 10px; left: 10px; z-index: 10;">
                                    <input type="checkbox" class="product-checkbox" data-cart-key="<?php echo htmlspecialchars($cartKey); ?>" 
                                           data-price="<?php echo $item['price']; ?>" data-quantity="<?php echo $item['quantity']; ?>"
                                           style="width: 20px; height: 20px; cursor: pointer;" onchange="updateSummary()">
                                </div>
                                
                                <!-- Product Image -->
                                <div style="height: 160px; overflow: hidden; background: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                                    <img src="<?php echo SITE_URL; ?>uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                
                                <!-- Product Info -->
                                <div style="padding: 12px;">
                                    <h4 style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #333; line-height: 1.3;">
                                        <?php echo htmlspecialchars($item['name']); ?>
                                    </h4>
                                    
                                    <p style="margin: 4px 0; font-size: 12px; color: #666;">
                                        <strong>Size:</strong> <?php echo htmlspecialchars($item['size']); ?>
                                    </p>
                                    
                                    <p style="margin: 4px 0 10px 0; font-size: 14px; color: var(--primary-color); font-weight: 600;">
                                        <?php echo number_format($item['price'], 0, ',', '.'); ?> đ
                                    </p>
                                    
                                    <!-- Quantity Control -->
                                    <form class="quantity-form" data-cart-key="<?php echo htmlspecialchars($cartKey); ?>"
                                          style="display: flex; gap: 3px; margin-bottom: 10px;">
                                        <input type="hidden" name="cart_key" value="<?php echo htmlspecialchars($cartKey); ?>">
                                        <button type="button" class="qty-minus" style="width: 28px; height: 28px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 3px; font-size: 14px;">−</button>
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" 
                                               class="qty-input" style="flex: 1; padding: 4px; border: 1px solid #ddd; border-radius: 3px; text-align: center; font-size: 12px;">
                                        <button type="button" class="qty-plus" style="width: 28px; height: 28px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 3px; font-size: 14px;">+</button>
                                    </form>
                                    
                                    <p style="margin: 6px 0 10px 0; padding: 8px; background: #f0f0f0; border-radius: 4px; text-align: center; font-size: 12px; color: #666;">
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
                
                <!-- RIGHT: Order Summary -->
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
                    
                    <!-- Action Buttons -->
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

// Setup quantity control buttons
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
                
                // Update the cart item total display
                const itemTotalEl = document.querySelector(`[data-cart-key="${cartKey}"]`).closest('.cart-item').querySelector('[data-item-total]');
                if (itemTotalEl) {
                    itemTotalEl.textContent = (price * quantity).toLocaleString('vi-VN') + ' đ';
                }
            }
            updateSummary();
        }
    })
    .catch(error => console.error('Error:', error));
}

function removeFromCart(cartKey) {
    if (!confirm('Bạn chắc chắn muốn xóa sản phẩm này?')) {
        return;
    }
    
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
</script>
