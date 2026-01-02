-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jan 02, 2026 at 05:50 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web-ban-thucan`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `email_admin` varchar(255) NOT NULL,
  `mat_khau_admin` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `email_admin`, `mat_khau_admin`) VALUES
(1, 'admin@gmail.com', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categories_id` int(11) NOT NULL,
  `ten_categories` varchar(255) NOT NULL,
  `mo_ta_categories` text NOT NULL,
  `ngay_tao_categories` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ngay_cap_nhap_categories` timestamp NULL DEFAULT NULL,
  `parent_category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categories_id`, `ten_categories`, `mo_ta_categories`, `ngay_tao_categories`, `ngay_cap_nhap_categories`, `parent_category_id`) VALUES
(1, 'Pizza', '', '2025-12-31 07:37:30', '2025-12-31 07:37:30', NULL),
(3, 'Pizza Muffin', '', '2025-12-31 07:45:00', '2025-12-31 07:45:00', NULL),
(4, 'Gà', '', '2025-12-31 07:45:20', '2025-12-31 07:45:20', NULL),
(5, 'Mỳ Ý', '', '2025-12-31 07:45:32', '2025-12-31 07:45:32', NULL),
(6, 'Khai Vị', '', '2025-12-31 07:45:40', '2025-12-31 07:45:40', NULL),
(7, 'Tráng Miệng', '', '2025-12-31 07:45:48', '2025-12-31 07:45:48', NULL),
(8, 'Thức Uống', '', '2025-12-31 07:45:54', '2025-12-31 07:45:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `contact_id` int(11) NOT NULL,
  `ten_contact` varchar(255) NOT NULL,
  `email_contact` varchar(255) NOT NULL,
  `so_dien_thoai_contact` varchar(10) NOT NULL,
  `noi_dung_contact` text NOT NULL,
  `ngay_tao_contact` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`contact_id`, `ten_contact`, `email_contact`, `so_dien_thoai_contact`, `noi_dung_contact`, `ngay_tao_contact`) VALUES
(1, 'Test', 'test@gmail.com', '0363547540', 'Test nè', '2025-12-31 13:29:19');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `tong_tien` decimal(10,2) NOT NULL,
  `phuong_thuc_thanh_toan` varchar(255) NOT NULL,
  `trang_thai` varchar(50) NOT NULL DEFAULT 'Chờ xác nhận',
  `ngay_tao_order` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nguoi_mua_id` int(11) NOT NULL,
  `nguoi_nhan` varchar(255) NOT NULL,
  `sdt_nguoi_nhan` varchar(10) NOT NULL,
  `dia-chi_nhan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `tong_tien`, `phuong_thuc_thanh_toan`, `trang_thai`, `ngay_tao_order`, `nguoi_mua_id`, `nguoi_nhan`, `sdt_nguoi_nhan`, `dia-chi_nhan`) VALUES
(1, 598000.00, 'Trực tiếp', 'Đã hủy', '2025-12-31 15:57:53', 1, '', '', ''),
(2, 628000.00, 'Trực tiếp', 'Đã hủy', '2026-01-01 07:20:34', 1, '', '', ''),
(3, 628000.00, 'Trực tiếp', 'Đã hủy', '2025-12-31 15:57:55', 1, '', '', ''),
(4, 329000.00, 'Trực tiếp', 'Đã hủy', '2026-01-01 07:39:51', 1, '', '', ''),
(5, 329000.00, 'Trực tiếp', 'Đã giao', '2026-01-01 09:12:14', 1, '', '', ''),
(6, 329000.00, 'Trực tiếp', 'Đã giao', '2026-01-01 09:12:16', 1, 'an phúc', '0363547545', 'gaii hàng ở tvu\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `so_luong_mua` int(11) NOT NULL,
  `gia_order_items` decimal(10,2) NOT NULL,
  `size` enum('Nhỏ','Vừa','Lớn') NOT NULL,
  `ngay_dat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fk_order_id` int(11) NOT NULL,
  `fk_product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `so_luong_mua`, `gia_order_items`, `size`, `ngay_dat`, `fk_order_id`, `fk_product_id`) VALUES
(1, 1, 299000.00, 'Lớn', '2025-12-31 09:57:12', 3, 1),
(2, 1, 299000.00, 'Vừa', '2025-12-31 09:57:12', 3, 1),
(3, 1, 299000.00, 'Nhỏ', '2026-01-01 01:30:10', 4, 1),
(4, 1, 299000.00, 'Vừa', '2026-01-01 01:41:59', 5, 1),
(5, 1, 299000.00, 'Nhỏ', '2026-01-01 01:56:24', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `ten_product` varchar(255) NOT NULL,
  `mo_ta_product` text NOT NULL,
  `gia_product` decimal(10,2) NOT NULL,
  `ngay_tao_product` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ngay_cap_nhap_product` timestamp NULL DEFAULT NULL,
  `danh_muc_product` int(11) NOT NULL,
  `hinh_anh_product` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `ten_product`, `mo_ta_product`, `gia_product`, `ngay_tao_product`, `ngay_cap_nhap_product`, `danh_muc_product`, `hinh_anh_product`) VALUES
(1, 'Pizza Tôm & Bò Xốt Parmesan', 'Bánh ngoan nhắm', 299000.00, '2026-01-01 05:52:32', '2025-12-31 23:52:32', 1, '1767190336_Pizza Tôm & Bò Xốt Parmesan.jpg'),
(3, 'Pizza Siêu Topping Hải Sản Xốt Mayonnaise', 'Tăng 50% lượng topping protein: Tôm, Mực, Thanh Cua; Thêm Phô Mai Mozzarella, Xốt Mayonnaise, Húng Tây, Hành', 235000.00, '2026-01-01 22:20:45', '2026-01-01 22:20:45', 1, '1767327645_Pizza Siêu Topping Hải Sản Xốt Mayonnaise - Super Topping Ocean Mania.jpg'),
(4, 'Pizza Siêu Topping Bơ Gơ Bò Mỹ Xốt Phô Mai - Super Topping Bacon Cheeseburger', 'Tăng 50% lượng topping protein: Thịt Bò Bơ Gơ Nhập Khẩu, Thịt Heo Xông Khói; Thêm Xốt Phô Mai, Xốt Mayonnaise, Phô Mai Mozzarella, Phô Mai Cheddar, Cà Chua, Hành Tây, Nấm', 235000.00, '2026-01-01 22:21:53', '2026-01-01 22:21:53', 1, '1767327713_Pizza Siêu Topping Bơ Gơ Bò Mỹ Xốt Phô Mai - Super Topping Bacon Cheeseburger.jpg'),
(5, 'Pizza Siêu Topping Hải Sản Nhiệt Đới Xốt Tiêu - Super Topping Pizzamin Sea', 'Tăng 50% lượng topping protein: Tôm, Mực; Thêm Phô Mai Mozzarella, Phô Mai Cheddar, Thơm, Hành Tây, Xốt Mayonnaise, Xốt Tiêu Đen\r\n\r\n', 235000.00, '2026-01-01 22:22:41', '2026-01-01 22:22:41', 1, '1767327761_Pizza Siêu Topping Hải Sản Nhiệt Đới Xốt Tiêu - Super Topping Pizzamin Sea.jpg'),
(6, 'Pizza Siêu Topping Bò Và Tôm Nướng Kiểu Mỹ - Super Topping Surf And Turf', 'Tăng 50% lượng topping protein: Tôm, Thịt Bò Mexico; Thêm Phô Mai Mozzarella, Cà Chua, Hành, Xốt Cà Chua, Xốt Mayonnaise Xốt Phô Mai', 235000.00, '2026-01-01 22:23:58', '2026-01-01 22:23:58', 1, '1767327838_Pizza Siêu Topping Bò Và Tôm Nướng Kiểu Mỹ - Super Topping Surf And Turf.jpg'),
(7, 'Pizza Siêu Topping Dăm Bông Dứa Kiểu Hawaiian - Super Topping Hawaiian', 'Tăng 50% lượng topping protein: Thịt Dăm Bông; Thêm Phô Mai Mozzarella, Dứa, Xốt Mayonnaise, Xốt Cà Chua', 205000.00, '2026-01-01 22:25:10', '2026-01-01 22:25:10', 1, '1767327910_Pizza Siêu Topping Dăm Bông Dứa Kiểu Hawaiian - Super Topping Hawaiian.jpg'),
(8, 'Pizza Siêu Topping Xúc Xích Ý Truyền Thống', 'Tăng 50% lượng topping protein: Xúc Xích Pepperoni; Thêm Phô Mai Mozzarella, Xốt Cà Chua', 235000.00, '2026-01-01 22:25:51', '2026-01-01 22:25:51', 1, '1767327951_Pizza Siêu Topping Xúc Xích Ý Truyền Thống - Super Topping Pepperoni.jpg'),
(9, 'Pizza Hải Sản Nhiệt Đới Xốt Tiêu - Pizzamin Sea', 'Xốt tiêu đen, Phô Mai Mozzarella, Phô Mai Cheddar, Thơm, Hành Tây, Tôm, Mực', 115000.00, '2026-01-01 22:27:10', '2026-01-01 22:27:10', 1, '1767328030_Pizza Hải Sản Nhiệt Đới Xốt Tiêu - Pizzamin Sea.jpg'),
(10, 'Pizza Hải Sản Xốt Cà Chua - Seafood Delight', 'Xốt Cà Chua, Phô Mai Mozzarella, Tôm, Mực, Thanh Cua, Hành Tây', 205000.00, '2026-01-01 22:28:02', '2026-01-01 22:28:02', 1, '1767328082_Pizza Hải Sản Xốt Cà Chua - Seafood Delight.jpg'),
(11, 'Pizza Hải Sản Xốt Mayonnaise - Ocean Mania', 'Xốt Mayonnaise , Phô Mai Mozzarella, Tôm, Mực, Thanh Cua, Hành Tây', 115000.00, '2026-01-01 22:29:13', '2026-01-01 22:29:13', 1, '1767328153_Pizza Hải Sản Xốt Mayonnaise - Ocean Mania.jpg'),
(12, 'Pizza Thanh Cua Dứa Xốt Phô Mai - Cheesy Crab Stick & Pineapple', 'Xốt Mayonnasie, Phô Mai Mozzarella, Thanh Cua, Dứa', 95000.00, '2026-01-01 22:30:18', '2026-01-01 22:30:18', 1, '1767328218_Pizza Thanh Cua Dứa Xốt Phô Mai - Cheesy Crab Stick & Pineapple.jpg'),
(13, 'Pizza Dăm Bông Bắp Xốt Phô Mai - Cheesy Ham & Corn', 'Xốt Phô Mai, Phô Mai Mozzarella, Thịt Dăm Bông, Thịt Xông Khói, Bắp', 95000.00, '2026-01-01 22:31:14', '2026-01-01 22:31:14', 1, '1767328274_Pizza Dăm Bông Bắp Xốt Phô Mai - Cheesy Ham & Corn.jpg'),
(14, 'Pizza Xúc Xích Xốt Phô Mai - Sausage Kid Mania', 'Xốt phô mai, Phô mai Mozzarella, Xúc Xích, Thịt Heo Xông Khói, Bắp (Ngô), Thơm (Dứa)', 95000.00, '2026-01-01 22:32:07', '2026-01-01 22:32:07', 1, '1767328327_Pizza Xúc Xích Xốt Phô Mai - Sausage Kid Mania.jpg'),
(15, 'Pizza Gà Phô Mai Thịt Heo Xông Khói - Cheesy Chicken Bac', 'Xốt Phô Mai, Gà Viên, Thịt Heo Xông Khói, Phô Mai Mozzarella, Cà Chua', 95000.00, '2026-01-01 22:33:03', '2026-01-01 22:33:03', 1, '1767328383_Pizza Gà Phô Mai Thịt Heo Xông Khói - Cheesy Chicken Bacon.jpg'),
(16, 'Pizza Phô Mai Thịt Heo Xông Khói - Cheesy Bacon', 'Phô mai Mozzarella , Phô Mai Cheddar, Xốt 7 Loại Phô Mai Đặc Biệt, Thịt Heo Xông Khói, Thịt Heo Xông Khói Miếng', 205000.00, '2026-01-01 22:34:00', '2026-01-01 22:34:00', 1, '1767328440_Pizza Phô Mai Thịt Heo Xông Khói - Cheesy Bacon.jpg'),
(17, 'Pizza Thập Cẩm Thượng Hạng - Extravaganza', 'Xốt Cà Chua, Phô Mai Mozzarella, Xúc Xích Pepperoni, Thịt Dăm Bông, Xúc Xich Ý, Thịt Bò Nướng, Nấm Mỡ, Hành Tây, Ô-liu', 115000.00, '2026-01-01 22:34:46', '2026-01-01 22:34:46', 1, '1767328486_Pizza Thập Cẩm Thượng Hạng - Extravaganza.jpg'),
(18, 'Pizza Ngập Vị Phô Mai Hảo Hạng - Cheesy Madness', 'Phô Mai Cheddar, Phô Mai Mozzarella, Phô Mai Xanh Viên, Viền Phô Mai, Xốt Phô Mai Và Phục Vụ Cùng Mật Ong.', 205000.00, '2026-01-01 22:35:31', '2026-01-01 22:35:31', 1, '1767328531_Pizza Ngập Vị Phô Mai Hảo Hạng - Cheesy Madness.jpg'),
(19, 'Pizza Bơ Gơ Bò Mỹ Xốt Phô Mai - Bacon Cheeseburger', 'Thịt Bò Bơ Gơ Nhập Khẩu, Thịt Heo Xông Khói, Xốt Phô Mai, Xốt Mayonnaise, Phô Mai Mozzarella, Phô Mai Cheddar, Cà Chua, Hành Tây, Nấm', 205000.00, '2026-01-01 22:36:23', '2026-01-01 22:36:23', 1, '1767328583_Pizza Bơ Gơ Bò Mỹ Xốt Phô Mai - Bacon Cheeseburger.jpg'),
(20, 'Pizza Rau Củ Thập Cẩm - Veggie Mania', 'Xốt Cà Chua, Phô Mai Mozzarella, Hành Tây, Ớt Chuông Xanh, Ô-liu, Nấm Mỡ, Cà Chua, Thơm (dứa)', 85000.00, '2026-01-01 22:37:12', '2026-01-01 22:37:12', 1, '1767328632_Pizza Rau Củ Thập Cẩm - Veggie Mania.jpg'),
(21, 'Pizza 5 Loại Thịt Thượng Hạng - Meat Lovers', 'Xốt Cà Chua, Phô Mai Mozzarella, Xúc Xích Pepperoni, Thịt Dăm Bông, Xúc Xich Ý, Thịt Heo Xông Khói', 115000.00, '2026-01-01 22:37:51', '2026-01-01 22:37:51', 1, '1767328671_Pizza 5 Loại Thịt Thượng Hạng - Meat Lovers.jpg'),
(22, 'Pizza Xúc Xích Ý Truyền Thống - Pepperoni Feast', 'Xốt Cà Chua, Phô Mai Mozzarella, Xúc Xích Pepperoni', 115000.00, '2026-01-01 22:38:37', '2026-01-01 22:38:37', 1, '1767328717_Pizza Xúc Xích Ý Truyền Thống - Pepperoni Feast.jpg'),
(23, 'Pizza Dăm Bông Dứa Kiểu Hawaii - Hawaiian', 'Xốt Cà Chua, Phô Mai Mozzarella, Thịt Dăm Bông, Thơm', 95000.00, '2026-01-01 22:39:27', '2026-01-01 22:39:27', 1, '1767328767_Pizza Dăm Bông Dứa Kiểu Hawaii - Hawaiian.jpg'),
(24, 'Pizza Phô Mai Truyền Thống - Cheese Mania', 'Xốt Cà Chua, phô Mai Mozzarella', 155000.00, '2026-01-01 22:40:08', '2026-01-01 22:40:08', 1, '1767328808_Pizza Phô Mai Truyền Thống - Cheese Mania.jpg'),
(25, 'Set Pizza Muffin 6 Vị (6pcs) - 6 Flavors Pizza Muffin Set (6pcs)', 'Đế Bánh Bột Tươi, Phô Mai Mozzarella, Phô Mai Cheddar, Xúc xích Pepperoni, Thịt Bò Nướng, Cà Chua, Thanh Cua, Dứa (Thơm), Thịt Dăm Bông, Gà Viên Nấm Mỡ, Xúc Xích Parsley, Bắp (Ngô), Xốt BBQ, Xốt Kem Chanh, Xốt Tiêu Đen Xốt Mayonnaise, Xốt Phô Mai', 138000.00, '2026-01-01 22:41:10', '2026-01-01 22:41:10', 3, '1767328870_Set Pizza Muffin 6 Vị (6pcs) - 6 Flavors Pizza Muffin Set (6pcs).jpg'),
(26, 'Set Pizza Muffin Mê Thịt Đậm Đà (3pcs) - Sausage & Ham & Pepperoni Pizza Muffin Set', 'Đế Bánh Bột Tươi, Phô Mai Mozzarella, Phô Mai Cheddar, Thịt Dăm Bông, Dứa (Thơm), Xúc Xích Parsley, Bắp (Ngô), Xúc xích Pepperoni, Xốt Phô Mai, Xốt BBQ, Xốt Tiêu Đen', 69000.00, '2026-01-01 22:41:50', '2026-01-01 22:41:50', 3, '1767328910_Set Pizza Muffin Mê Thịt Đậm Đà (3pcs) - Sausage & Ham & Pepperoni Pizza Muffin Set.jpg'),
(27, 'Set Pizza Muffin Khoái Tươi Nhiều Vị (3pcs) - Sausage & Ham & Chicken Pizza Muffin Set', 'Đế Bánh Bột Tươi, Phô Mai Mozzarella, Phô Mai Cheddar, Thịt Dăm Bông, Dứa (Thơm), Gà Viên, Nấm Mỡ, Xúc Xích Parsley, Bắp (Ngô), Xốt BBQ, Xốt Kem Chanh, Xốt Tiêu Đen', 96000.00, '2026-01-01 22:42:31', '2026-01-01 22:42:31', 3, '1767328951_Set Pizza Muffin Khoái Tươi Nhiều Vị (3pcs) - Sausage & Ham & Chicken Pizza Muffin Set.jpg'),
(28, 'Set Pizza Muffin Thích Béo Mê Ly (3pcs) - Pepperoni & Crab Stick & Grilled Beef Pizza Muffin Set', 'Đế Bánh Bột Tươi, Phô Mai Mozzarella, Phô Mai Cheddar, Xúc xích Pepperoni, Thịt Bò Nướng, Cà Chua, Thanh Cua, Dứa (Thơm), Xốt Mayonnaise, Xốt Phô Mai', 69000.00, '2026-01-01 22:43:10', '2026-01-01 22:43:10', 3, '1767328990_Set Pizza Muffin Thích Béo Mê Ly (3pcs) - Pepperoni & Crab Stick & Grilled Beef Pizza Muffin Set.jpg'),
(29, 'Set Pizza Muffin Rau Củ (3pcs) - Veggie Pizza Muffin Set', 'Đế Bánh Bột Tươi, Phô Mai Mozzarella, Phô Mai Cheddar, Xốt 7 Loại Phô Mai Đặc Biệt, Xốt Cà Chua, Xốt Phô Mai, Xốt Mayonnaise, Xốt Tiêu Đen, Nấm Mỡ, Dứa (Thơm), Ớt Chuông Xanh, Oliu Đen, Bắp (Ngô)', 69000.00, '2026-01-01 22:43:47', '2026-01-01 22:43:47', 3, '1767329027_Set Pizza Muffin Rau Củ (3pcs) - Veggie Pizza Muffin Set.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `ten_nguoi_dung` varchar(255) NOT NULL,
  `email_user` varchar(255) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `so_dien_thoai_user` varchar(10) NOT NULL,
  `dia_chi` text NOT NULL,
  `ngay_tao_user` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ngay_cap_nhap_user` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `ten_nguoi_dung`, `email_user`, `mat_khau`, `so_dien_thoai_user`, `dia_chi`, `ngay_tao_user`, `ngay_cap_nhap_user`) VALUES
(1, 'an', 'an@gmail.com', '$2y$10$id6NHB9fjcnTqWvhhXNo3Omy8.p.7gd4842neezgpJYxwJUltJgLS', '0363547545', 'ándaksdn·', '2025-12-31 15:51:13', '2025-12-31 09:51:13');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `wishlist_id` int(11) NOT NULL,
  `tao_luc` date NOT NULL,
  `nguoi_dung_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categories_id`),
  ADD KEY `parent_category_id` (`parent_category_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `FK_order_user` (`nguoi_mua_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `FK_oritem_pro` (`fk_product_id`),
  ADD KEY `FK_oritem_order` (`fk_order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `FK_pro_cate` (`danh_muc_product`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD KEY `FK_wish_pro` (`product_id`),
  ADD KEY `FK_wish_user` (`nguoi_dung_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categories_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_category_id`) REFERENCES `categories` (`categories_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_order_user` FOREIGN KEY (`nguoi_mua_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `FK_oritem_order` FOREIGN KEY (`fk_order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `FK_oritem_pro` FOREIGN KEY (`fk_product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_pro_cate` FOREIGN KEY (`danh_muc_product`) REFERENCES `categories` (`categories_id`);

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `FK_wish_pro` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `FK_wish_user` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
