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
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/pages-auth.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/pages-cart-checkout.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/pages-contact.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/pages-admin.css">
    
    <!-- Responsive styles -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/responsive.css">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
    <!-- Toast Notifications Container -->
    <div id="toastContainer" class="toast-container"></div>
    
    <?php include APP_PATH . 'Views/components/header.php'; ?>
    
    <main class="main-content">
        <?php if (isset($_SESSION['success'])): ?>
            <script>
                window.toastMessage = '<?php echo addslashes($_SESSION['success']); ?>';
                window.toastType = 'success';
            </script>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <script>
                window.toastMessage = '<?php echo addslashes($_SESSION['error']); ?>';
                window.toastType = 'error';
            </script>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
