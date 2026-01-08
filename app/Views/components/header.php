<header class="header">
    <div class="container">
        <div class="header-content">
            <!-- Logo + Tên website (bên trái) -->
            <div class="logo">
                <h1><a href="<?php echo SITE_URL; ?>index.php?action=home">🍕 Pizza A.D.A</a></h1>
            </div>            
            <!-- Menu chính (Trang chủ, Giới thiệu, Liên hệ) -->
            <nav class="navbar">
                <ul class="nav-menu">
                    <li><a href="<?php echo SITE_URL; ?>index.php?action=home">Trang chủ</a></li>
                    <li <?php if (isset($_SESSION['admin_id'])) echo 'style="display: none;"'; ?>><a href="<?php echo SITE_URL; ?>index.php?action=home&method=about">Giới thiệu</a></li>
                    <li <?php if (isset($_SESSION['admin_id'])) echo 'style="display: none;"'; ?>><a href="<?php echo SITE_URL; ?>index.php?action=contact">Liên hệ</a></li>
                </ul>
            </nav>            
            <!-- Ô tìm kiếm -->
            <form class="search-form" onsubmit="handleSearch(event)" <?php if (isset($_SESSION['admin_id'])) echo 'style="display: none;"'; ?>>
                <input type="text" id="searchInput" name="q" class="search-input" placeholder="Tìm kiếm pizza...">
                <button type="submit" class="search-btn"></button>
            </form>            
            <!-- Menu phụ (Yêu thích, Giỏ hàng) - chỉ hiện khi đã login user (không admin) -->
            <div class="nav-menu" <?php if (isset($_SESSION['admin_id'])) echo 'style="display: none;"'; ?>>
                <a href="<?php echo SITE_URL; ?>index.php?action=home&method=wishlist" class="menu-item" title="Danh sách yêu thích">Yêu thích</a>
                <?php if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])): ?>
                    <a href="<?php echo SITE_URL; ?>index.php?action=cart&method=view" class="menu-item" title="Giỏ hàng">Giỏ hàng</a>
                <?php endif; ?>
            </div>            
            <!-- Menu tài khoản (bên phải) -->
            <div class="user-menu">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <button class="dropdown-btn"><?php echo $_SESSION['ten_nguoi_dung']; ?></button>
                        <div class="dropdown-content">
                            <a href="<?php echo SITE_URL; ?>index.php?action=profile&method=view">Hồ sơ</a>
                            <a href="<?php echo SITE_URL; ?>index.php?action=order&method=history">Đơn hàng</a>
                            <a href="<?php echo SITE_URL; ?>index.php?action=auth&method=logout">Đăng xuất</a>
                        </div>
                    </div>
                <?php elseif (isset($_SESSION['admin_id'])): ?>
                    <div class="dropdown">
                        <button class="dropdown-btn">Admin</button>
                        <div class="dropdown-content">
                            <a href="<?php echo SITE_URL; ?>index.php?action=admin&method=dashboard">Dashboard</a>
                            <a href="<?php echo SITE_URL; ?>index.php?action=auth&method=logout">Đăng xuất</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>index.php?action=auth&method=login" class="btn btn-primary">Đăng nhập</a>
                    <a href="<?php echo SITE_URL; ?>index.php?action=auth&method=register" class="btn btn-secondary">Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
