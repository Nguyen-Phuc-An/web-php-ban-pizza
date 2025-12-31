<?php
$page_title = 'Danh sách yêu thích';
include APP_PATH . 'Views/layout/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>❤️ Danh sách yêu thích</h1>
    </div>

    <div id="wishlistContainer">
        <p style="text-align: center; color: var(--text-muted);">Đang tải...</p>
    </div>
</div>

<!-- Product detail modal -->
<div id="productModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeProductModal()">&times;</span>
        <div id="productDetail"></div>
    </div>
</div>

<script>
// Load wishlist on page load
document.addEventListener('DOMContentLoaded', function() {
    loadWishlist();
});

function loadWishlist() {
    const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    
    if (wishlist.length === 0) {
        document.getElementById('wishlistContainer').innerHTML = `
            <div style="text-align: center; padding: var(--spacing-lg);">
                <p style="color: var(--text-muted); margin-bottom: var(--spacing-md);">Chưa có sản phẩm yêu thích</p>
                <a href="<?php echo SITE_URL; ?>index.php?action=home&method=index" class="btn btn-primary">Quay lại mua sắm</a>
            </div>
        `;
        return;
    }
    
    // Fetch all wishlist products
    fetch('<?php echo SITE_URL; ?>index.php?action=home&method=getWishlistItems', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_ids: wishlist
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.products && data.products.length > 0) {
            let html = '<div class="products-grid">';
            
            data.products.forEach(product => {
                html += `
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo SITE_URL; ?>uploads/${product.hinh_anh_product}" 
                                 alt="${product.ten_product}">
                        </div>
                        <div class="product-info">
                            <h3>${product.ten_product}</h3>
                            <p class="product-price">${Number(product.gia_product).toLocaleString('vi-VN')} đ</p>
                            <p class="product-description">${product.mo_ta_product.substring(0, 50)}...</p>
                            <div class="product-actions">
                                <button class="btn btn-primary" onclick="viewProductDetail(${product.product_id})">
                                    Chi tiết
                                </button>
                                <button class="btn btn-favorite wishlist-btn" 
                                        onclick="removeFromWishlist(${product.product_id})" 
                                        title="Bỏ yêu thích"
                                        style="background: none; border: none; font-size: 24px; cursor: pointer; padding: 0; min-width: auto; color: red;">
                                    ♥
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            document.getElementById('wishlistContainer').innerHTML = html;
        } else {
            document.getElementById('wishlistContainer').innerHTML = `
                <div style="text-align: center; padding: var(--spacing-lg);">
                    <p style="color: var(--text-muted); margin-bottom: var(--spacing-md);">Không tìm thấy sản phẩm yêu thích</p>
                    <a href="<?php echo SITE_URL; ?>index.php?action=home&method=index" class="btn btn-primary">Quay lại mua sắm</a>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('wishlistContainer').innerHTML = '<p style="color: red; text-align: center;">Lỗi tải danh sách yêu thích</p>';
    });
}

function removeFromWishlist(productId) {
    const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    const index = wishlist.indexOf(productId);
    
    if (index > -1) {
        wishlist.splice(index, 1);
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        loadWishlist();
    }
}

function viewProductDetail(productId) {
    fetch('<?php echo SITE_URL; ?>index.php?action=product&method=getDetail&id=' + productId)
        .then(response => response.json())
        .then(data => {
            if (data.product) {
                const product = data.product;
                const detailHTML = `
                    <h2>${product.ten_product}</h2>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-lg);">
                        <div>
                            <img src="<?php echo SITE_URL; ?>uploads/${product.hinh_anh_product}" style="width: 100%; height: auto; border-radius: 8px;">
                        </div>
                        <div>
                            <p style="font-size: 24px; font-weight: 600; color: var(--primary-color); margin: 0 0 var(--spacing-md) 0;">
                                ${Number(product.gia_product).toLocaleString('vi-VN')} đ
                            </p>
                            <p style="margin: 0 0 var(--spacing-md) 0; line-height: 1.6;">
                                ${product.mo_ta_product}
                            </p>
                            <div style="display: flex; gap: var(--spacing-md);">
                                <button class="btn btn-primary" onclick="addToCart(${product.product_id})">
                                    🛒 Thêm vào giỏ hàng
                                </button>
                                <button class="btn btn-secondary" onclick="closeProductModal()">
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

function addToCart(productId) {
    alert('Thêm sản phẩm ' + productId + ' vào giỏ hàng');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

<?php include APP_PATH . 'Views/layout/footer.php'; ?>
