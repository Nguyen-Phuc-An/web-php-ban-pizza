<?php
header('Content-Type: application/javascript; charset=UTF-8');
// Define constants for JavaScript
$SITE_URL = SITE_URL;
$isLoggedIn = isset($_SESSION['user_id']) ? 'true' : 'false';
?>
// Cart Management
let cart = {};

// Load cart from localStorage
function loadCart() {
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
    }
}

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Add to cart
function addToCart(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    fetch('<?php echo $SITE_URL; ?>index.php?action=cart&method=add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            form.reset();
        } else {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

// View product detail modal
function viewProductDetail(productId) {
    const modal = document.getElementById('productModal');
    const detailDiv = document.getElementById('productDetail');
    
    fetch(`<?php echo $SITE_URL; ?>index.php?action=product&method=detail&id=${productId}`)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const productContent = doc.querySelector('.product-detail');
            
            if (productContent) {
                detailDiv.innerHTML = productContent.innerHTML;
                modal.style.display = 'block';
            }
        })
        .catch(error => console.error('Error:', error));
}

function closeProductModal() {
    const modal = document.getElementById('productModal');
    modal.style.display = 'none';
}

// Wishlist
function toggleWishlist(productId) {
    const isLoggedIn = <?php echo $isLoggedIn; ?>;
    
    if (!isLoggedIn) {
        if (confirm('Vui lòng đăng nhập để thêm vào danh sách yêu thích')) {
            window.location.href = '<?php echo $SITE_URL; ?>index.php?action=auth&method=login';
        }
        return;
    }
    
    const formData = new FormData();
    formData.append('product_id', productId);
    
    fetch('<?php echo $SITE_URL; ?>index.php?action=wishlist&method=add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message || data.error);
    })
    .catch(error => console.error('Error:', error));
}

function removeFromWishlist(productId) {
    const formData = new FormData();
    formData.append('product_id', productId);
    
    fetch('<?php echo $SITE_URL; ?>index.php?action=wishlist&method=remove', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Form validation
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    
    for (let input of inputs) {
        if (!input.value.trim()) {
            alert(`Vui lòng điền ${input.previousElementSibling.textContent}`);
            input.focus();
            return false;
        }
    }
    
    return true;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadCart();
    
    // Form submission validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (form.classList.contains('auth-form') || form.classList.contains('contact-form')) {
                if (!validateForm(form)) {
                    e.preventDefault();
                }
            }
        });
    });
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('productModal');
        if (modal && event.target == modal) {
            modal.style.display = 'none';
        }
    }
});
