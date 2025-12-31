<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Pizza Online</title>
    <!-- Base styles -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/base.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/layout.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/components.css">
    
    <!-- Page-specific styles -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/pages-home-product.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/pages-about.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/pages-cart-checkout.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/pages-contact.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/pages-admin.css">
    
    <!-- Responsive styles -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/responsive.css">
</head>
<body>
    <!-- Toast Notifications Container -->
    <div id="toastContainer" class="toast-container"></div>
    
    <?php include APP_PATH . 'Views/components/header.php'; ?>
    
    <main class="main-content">
        <?php if (isset($_SESSION['success'])): ?>
            <script>
                showToast('<?php echo addslashes($_SESSION['success']); ?>', 'success');
            </script>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <script>
                showToast('<?php echo addslashes($_SESSION['error']); ?>', 'error');
            </script>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

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
    </script>
