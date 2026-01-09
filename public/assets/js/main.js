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

// Show Toast Notification
function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = message;
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Wishlist - Remove
function removeFromWishlist(productId) {
    const formData = new FormData();
    formData.append('product_id', productId);
    
    fetch(window.SITE_URL + 'index.php?action=wishlist&method=remove', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Đã xóa khỏi yêu thích', 'success');
            // Remove card from DOM
            const productCard = document.querySelector(`[data-product-id="${productId}"]`)?.closest('.product-card');
            if (productCard) {
                productCard.style.transition = 'opacity 0.3s ease';
                productCard.style.opacity = '0';
                setTimeout(() => {
                    productCard.remove();
                    // Check if wishlist is empty
                    const container = document.getElementById('wishlistContainer');
                    const cards = container.querySelectorAll('.product-card');
                    if (cards.length === 0) {
                        location.reload();
                    }
                }, 300);
            }
        } else {
            showToast(data.error || 'Lỗi xóa yêu thích', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Lỗi kết nối', 'error');
    });
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
