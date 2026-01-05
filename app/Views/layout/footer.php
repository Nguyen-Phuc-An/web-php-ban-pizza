    </main>
    
    <?php include APP_PATH . 'Views/components/footer.php'; ?>
    
    <script src="<?php echo SITE_URL; ?>assets/js/main.php"></script>
    
    <script>
        function showToast(message, type = 'info', duration = 3000) {
            const container = document.getElementById('toastContainer');
            
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            const icons = {
                success: '✓',
                error: '✕',
                info: 'ℹ',
                warning: '⚠'
            };
            
            toast.innerHTML = `
                <span class="toast-icon">${icons[type] || icons.info}</span>
                <span class="toast-message">${message}</span>
                <button class="toast-close" onclick="this.parentElement.remove()">×</button>
            `;
            
            container.appendChild(toast);
            
            if (duration > 0) {
                setTimeout(() => {
                    toast.style.animation = 'slideOutRight 0.3s ease-out forwards';
                    setTimeout(() => toast.remove(), 300);
                }, duration);
            }
        }
        
        // Hiển thị toast nếu có message từ session
        document.addEventListener('DOMContentLoaded', function() {
            if (window.toastMessage) {
                showToast(window.toastMessage, window.toastType || 'info');
            }
        });
    </script>
</body>
</html>
