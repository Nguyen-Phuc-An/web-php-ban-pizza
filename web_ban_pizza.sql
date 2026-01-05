-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jan 05, 2026 at 10:33 AM
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
-- Database: `web_ban_pizza`
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
(3, 'Pizza Muffin', '', '2026-01-02 09:18:25', '2026-01-02 03:18:25', NULL),
(4, 'Gà', '', '2025-12-31 07:45:20', '2025-12-31 07:45:20', NULL),
(5, 'Mỳ Ý', '', '2025-12-31 07:45:32', '2025-12-31 07:45:32', NULL),
(6, 'Khai Vị', '', '2025-12-31 07:45:40', '2025-12-31 07:45:40', NULL),
(7, 'Tráng Miệng', '', '2025-12-31 07:45:48', '2025-12-31 07:45:48', NULL),
(8, 'Thức Uống', '', '2025-12-31 07:45:54', '2025-12-31 07:45:54', NULL),
(10, 'Siêu Toppings', '', '2026-01-02 05:28:20', '2026-01-01 23:28:20', 1),
(11, 'Đại tiệc hải sản', '', '2026-01-02 05:28:31', '2026-01-01 23:28:31', 1),
(12, 'Lựa chọn cho bé', '', '2026-01-02 05:31:00', '2026-01-01 23:31:00', 1),
(13, 'Huyền thoại Pizza Mỹ', '', '2026-01-02 09:18:35', '2026-01-02 03:18:35', 1);

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
(1, 'Test', 'test@gmail.com', '0363547540', 'Test nè', '2025-12-31 13:29:19'),
(2, 'Đăng', 'dang@gmail.com', '0363547540', 'Sản phẩm ngon', '2026-01-03 04:26:13');

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
(2, 175000.00, 'Chuyển khoản', 'Đã giao', '2026-01-05 07:46:00', 1, 'Đăng', '0363547540', 'TVU-D3'),
(3, 55000.00, 'Trực tiếp', 'Đã hủy', '2026-01-05 07:45:43', 1, 'an', '0363547545', 'test view lịch sử'),
(4, 109000.00, 'Trực tiếp', 'Đã giao', '2026-01-05 07:45:45', 1, 'an', '0363547542', 'test chuyển khoản ngân hàng'),
(5, 208000.00, 'Chuyển khoản', 'Đã giao', '2026-01-05 08:37:34', 1, 'an', '0363547542', 'Test chuyển khoản'),
(6, 360000.00, 'Chuyển khoản', 'Đã hủy', '2026-01-05 08:29:55', 4, 'Thanh Đỉnh', '0123123132', 'Mũy Mo mó'),
(7, 1269000.00, 'Chuyển khoản', 'Đã giao', '2026-01-05 09:19:31', 1, 'an', '0363547545', 'TVU');

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
(9, 1, 145000.00, 'Lớn', '2026-01-02 22:23:09', 2, 15),
(10, 1, 25000.00, '', '2026-01-03 00:11:23', 3, 50),
(11, 1, 79000.00, '', '2026-01-03 00:29:43', 4, 46),
(12, 2, 89000.00, '', '2026-01-03 00:53:48', 5, 40),
(13, 2, 165000.00, 'Lớn', '2026-01-05 02:19:02', 6, 9),
(14, 1, 299000.00, 'Vừa', '2026-01-05 03:17:42', 7, 1),
(15, 1, 235000.00, 'Vừa', '2026-01-05 03:17:42', 7, 3),
(16, 2, 235000.00, 'Vừa', '2026-01-05 03:17:42', 7, 4),
(17, 1, 235000.00, 'Vừa', '2026-01-05 03:17:42', 7, 5);

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
  `sub_category_id` int(11) DEFAULT NULL,
  `hinh_anh_product` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `ten_product`, `mo_ta_product`, `gia_product`, `ngay_tao_product`, `ngay_cap_nhap_product`, `danh_muc_product`, `sub_category_id`, `hinh_anh_product`) VALUES
(1, 'Pizza Tôm & Bò Xốt Parmesan', 'Bánh ngoan nhắm', 299000.00, '2026-01-03 05:43:35', '2026-01-02 23:43:35', 1, 10, '1767190336_Pizza Tôm & Bò Xốt Parmesan.jpg'),
(3, 'Pizza Siêu Topping Hải Sản Xốt Mayonnaise', 'Tăng 50% lượng topping protein: Tôm, Mực, Thanh Cua; Thêm Phô Mai Mozzarella, Xốt Mayonnaise, Húng Tây, Hành', 235000.00, '2026-01-03 05:46:42', '2026-01-02 23:46:42', 1, 10, '1767327645_Pizza Siêu Topping Hải Sản Xốt Mayonnaise - Super Topping Ocean Mania.jpg'),
(4, 'Pizza Siêu Topping Bơ Gơ Bò Mỹ Xốt Phô Mai - Super Topping Bacon Cheeseburger', 'Tăng 50% lượng topping protein: Thịt Bò Bơ Gơ Nhập Khẩu, Thịt Heo Xông Khói; Thêm Xốt Phô Mai, Xốt Mayonnaise, Phô Mai Mozzarella, Phô Mai Cheddar, Cà Chua, Hành Tây, Nấm', 235000.00, '2026-01-03 05:46:49', '2026-01-02 23:46:49', 1, 10, '1767327713_Pizza Siêu Topping Bơ Gơ Bò Mỹ Xốt Phô Mai - Super Topping Bacon Cheeseburger.jpg'),
(5, 'Pizza Siêu Topping Hải Sản Nhiệt Đới Xốt Tiêu - Super Topping Pizzamin Sea', 'Tăng 50% lượng topping protein: Tôm, Mực; Thêm Phô Mai Mozzarella, Phô Mai Cheddar, Thơm, Hành Tây, Xốt Mayonnaise, Xốt Tiêu Đen\r\n\r\n', 235000.00, '2026-01-01 22:22:41', '2026-01-01 22:22:41', 1, 0, '1767327761_Pizza Siêu Topping Hải Sản Nhiệt Đới Xốt Tiêu - Super Topping Pizzamin Sea.jpg'),
(6, 'Pizza Siêu Topping Bò Và Tôm Nướng Kiểu Mỹ - Super Topping Surf And Turf', 'Tăng 50% lượng topping protein: Tôm, Thịt Bò Mexico; Thêm Phô Mai Mozzarella, Cà Chua, Hành, Xốt Cà Chua, Xốt Mayonnaise Xốt Phô Mai', 235000.00, '2026-01-03 05:47:04', '2026-01-02 23:47:04', 1, 10, '1767327838_Pizza Siêu Topping Bò Và Tôm Nướng Kiểu Mỹ - Super Topping Surf And Turf.jpg'),
(7, 'Pizza Siêu Topping Dăm Bông Dứa Kiểu Hawaiian - Super Topping Hawaiian', 'Tăng 50% lượng topping protein: Thịt Dăm Bông; Thêm Phô Mai Mozzarella, Dứa, Xốt Mayonnaise, Xốt Cà Chua', 205000.00, '2026-01-03 05:47:16', '2026-01-02 23:47:16', 1, 10, '1767327910_Pizza Siêu Topping Dăm Bông Dứa Kiểu Hawaiian - Super Topping Hawaiian.jpg'),
(8, 'Pizza Siêu Topping Xúc Xích Ý Truyền Thống', 'Tăng 50% lượng topping protein: Xúc Xích Pepperoni; Thêm Phô Mai Mozzarella, Xốt Cà Chua', 235000.00, '2026-01-03 05:47:26', '2026-01-02 23:47:26', 1, 10, '1767327951_Pizza Siêu Topping Xúc Xích Ý Truyền Thống - Super Topping Pepperoni.jpg'),
(9, 'Pizza Hải Sản Nhiệt Đới Xốt Tiêu - Pizzamin Sea', 'Xốt tiêu đen, Phô Mai Mozzarella, Phô Mai Cheddar, Thơm, Hành Tây, Tôm, Mực', 115000.00, '2026-01-03 05:47:36', '2026-01-02 23:47:36', 1, 11, '1767328030_Pizza Hải Sản Nhiệt Đới Xốt Tiêu - Pizzamin Sea.jpg'),
(10, 'Pizza Hải Sản Xốt Cà Chua - Seafood Delight', 'Xốt Cà Chua, Phô Mai Mozzarella, Tôm, Mực, Thanh Cua, Hành Tây', 205000.00, '2026-01-03 05:47:44', '2026-01-02 23:47:44', 1, 11, '1767328082_Pizza Hải Sản Xốt Cà Chua - Seafood Delight.jpg'),
(11, 'Pizza Hải Sản Xốt Mayonnaise - Ocean Mania', 'Xốt Mayonnaise , Phô Mai Mozzarella, Tôm, Mực, Thanh Cua, Hành Tây', 115000.00, '2026-01-03 05:47:52', '2026-01-02 23:47:52', 1, 11, '1767328153_Pizza Hải Sản Xốt Mayonnaise - Ocean Mania.jpg'),
(12, 'Pizza Thanh Cua Dứa Xốt Phô Mai - Cheesy Crab Stick & Pineapple', 'Xốt Mayonnasie, Phô Mai Mozzarella, Thanh Cua, Dứa', 95000.00, '2026-01-03 05:48:08', '2026-01-02 23:48:08', 1, 12, '1767328218_Pizza Thanh Cua Dứa Xốt Phô Mai - Cheesy Crab Stick & Pineapple.jpg'),
(13, 'Pizza Dăm Bông Bắp Xốt Phô Mai - Cheesy Ham & Corn', 'Xốt Phô Mai, Phô Mai Mozzarella, Thịt Dăm Bông, Thịt Xông Khói, Bắp', 95000.00, '2026-01-03 05:48:24', '2026-01-02 23:48:24', 1, 12, '1767328274_Pizza Dăm Bông Bắp Xốt Phô Mai - Cheesy Ham & Corn.jpg'),
(14, 'Pizza Xúc Xích Xốt Phô Mai - Sausage Kid Mania', 'Xốt phô mai, Phô mai Mozzarella, Xúc Xích, Thịt Heo Xông Khói, Bắp (Ngô), Thơm (Dứa)', 95000.00, '2026-01-03 05:48:31', '2026-01-02 23:48:31', 1, 12, '1767328327_Pizza Xúc Xích Xốt Phô Mai - Sausage Kid Mania.jpg'),
(15, 'Pizza Gà Phô Mai Thịt Heo Xông Khói - Cheesy Chicken Bac', 'Xốt Phô Mai, Gà Viên, Thịt Heo Xông Khói, Phô Mai Mozzarella, Cà Chua', 95000.00, '2026-01-03 05:48:36', '2026-01-02 23:48:36', 1, 12, '1767328383_Pizza Gà Phô Mai Thịt Heo Xông Khói - Cheesy Chicken Bacon.jpg'),
(16, 'Pizza Phô Mai Thịt Heo Xông Khói - Cheesy Bacon', 'Phô mai Mozzarella , Phô Mai Cheddar, Xốt 7 Loại Phô Mai Đặc Biệt, Thịt Heo Xông Khói, Thịt Heo Xông Khói Miếng', 205000.00, '2026-01-03 05:48:42', '2026-01-02 23:48:42', 1, 12, '1767328440_Pizza Phô Mai Thịt Heo Xông Khói - Cheesy Bacon.jpg'),
(17, 'Pizza Thập Cẩm Thượng Hạng - Extravaganza', 'Xốt Cà Chua, Phô Mai Mozzarella, Xúc Xích Pepperoni, Thịt Dăm Bông, Xúc Xich Ý, Thịt Bò Nướng, Nấm Mỡ, Hành Tây, Ô-liu', 115000.00, '2026-01-03 05:48:48', '2026-01-02 23:48:48', 1, 13, '1767328486_Pizza Thập Cẩm Thượng Hạng - Extravaganza.jpg'),
(18, 'Pizza Ngập Vị Phô Mai Hảo Hạng - Cheesy Madness', 'Phô Mai Cheddar, Phô Mai Mozzarella, Phô Mai Xanh Viên, Viền Phô Mai, Xốt Phô Mai Và Phục Vụ Cùng Mật Ong.', 205000.00, '2026-01-03 05:48:55', '2026-01-02 23:48:55', 1, 13, '1767328531_Pizza Ngập Vị Phô Mai Hảo Hạng - Cheesy Madness.jpg'),
(19, 'Pizza Bơ Gơ Bò Mỹ Xốt Phô Mai - Bacon Cheeseburger', 'Thịt Bò Bơ Gơ Nhập Khẩu, Thịt Heo Xông Khói, Xốt Phô Mai, Xốt Mayonnaise, Phô Mai Mozzarella, Phô Mai Cheddar, Cà Chua, Hành Tây, Nấm', 205000.00, '2026-01-03 05:49:02', '2026-01-02 23:49:02', 1, 13, '1767328583_Pizza Bơ Gơ Bò Mỹ Xốt Phô Mai - Bacon Cheeseburger.jpg'),
(20, 'Pizza Rau Củ Thập Cẩm - Veggie Mania', 'Xốt Cà Chua, Phô Mai Mozzarella, Hành Tây, Ớt Chuông Xanh, Ô-liu, Nấm Mỡ, Cà Chua, Thơm (dứa)', 85000.00, '2026-01-03 05:49:09', '2026-01-02 23:49:09', 1, 13, '1767328632_Pizza Rau Củ Thập Cẩm - Veggie Mania.jpg'),
(21, 'Pizza 5 Loại Thịt Thượng Hạng - Meat Lovers', 'Xốt Cà Chua, Phô Mai Mozzarella, Xúc Xích Pepperoni, Thịt Dăm Bông, Xúc Xich Ý, Thịt Heo Xông Khói', 115000.00, '2026-01-03 05:49:17', '2026-01-02 23:49:17', 1, 13, '1767328671_Pizza 5 Loại Thịt Thượng Hạng - Meat Lovers.jpg'),
(22, 'Pizza Xúc Xích Ý Truyền Thống - Pepperoni Feast', 'Xốt Cà Chua, Phô Mai Mozzarella, Xúc Xích Pepperoni', 115000.00, '2026-01-03 05:49:25', '2026-01-02 23:49:25', 1, 13, '1767328717_Pizza Xúc Xích Ý Truyền Thống - Pepperoni Feast.jpg'),
(23, 'Pizza Dăm Bông Dứa Kiểu Hawaii - Hawaiian', 'Xốt Cà Chua, Phô Mai Mozzarella, Thịt Dăm Bông, Thơm', 95000.00, '2026-01-03 05:49:32', '2026-01-02 23:49:32', 1, 13, '1767328767_Pizza Dăm Bông Dứa Kiểu Hawaii - Hawaiian.jpg'),
(24, 'Pizza Phô Mai Truyền Thống - Cheese Mania', 'Xốt Cà Chua, phô Mai Mozzarella', 155000.00, '2026-01-03 05:49:39', '2026-01-02 23:49:39', 1, 13, '1767328808_Pizza Phô Mai Truyền Thống - Cheese Mania.jpg'),
(25, 'Set Pizza Muffin 6 Vị (6pcs) - 6 Flavors Pizza Muffin Set (6pcs)', 'Đế Bánh Bột Tươi, Phô Mai Mozzarella, Phô Mai Cheddar, Xúc xích Pepperoni, Thịt Bò Nướng, Cà Chua, Thanh Cua, Dứa (Thơm), Thịt Dăm Bông, Gà Viên Nấm Mỡ, Xúc Xích Parsley, Bắp (Ngô), Xốt BBQ, Xốt Kem Chanh, Xốt Tiêu Đen Xốt Mayonnaise, Xốt Phô Mai', 138000.00, '2026-01-02 08:59:02', '2026-01-02 02:59:02', 3, 0, '1767328870_Set Pizza Muffin 6 Vị (6pcs) - 6 Flavors Pizza Muffin Set (6pcs).jpg'),
(26, 'Set Pizza Muffin Mê Thịt Đậm Đà (3pcs) - Sausage & Ham & Pepperoni Pizza Muffin Set', 'Đế Bánh Bột Tươi, Phô Mai Mozzarella, Phô Mai Cheddar, Thịt Dăm Bông, Dứa (Thơm), Xúc Xích Parsley, Bắp (Ngô), Xúc xích Pepperoni, Xốt Phô Mai, Xốt BBQ, Xốt Tiêu Đen', 69000.00, '2026-01-01 22:41:50', '2026-01-01 22:41:50', 3, 0, '1767328910_Set Pizza Muffin Mê Thịt Đậm Đà (3pcs) - Sausage & Ham & Pepperoni Pizza Muffin Set.jpg'),
(27, 'Set Pizza Muffin Khoái Tươi Nhiều Vị (3pcs) - Sausage & Ham & Chicken Pizza Muffin Set', 'Đế Bánh Bột Tươi, Phô Mai Mozzarella, Phô Mai Cheddar, Thịt Dăm Bông, Dứa (Thơm), Gà Viên, Nấm Mỡ, Xúc Xích Parsley, Bắp (Ngô), Xốt BBQ, Xốt Kem Chanh, Xốt Tiêu Đen', 96000.00, '2026-01-02 09:01:43', '2026-01-02 03:01:43', 3, 0, '1767328951_Set Pizza Muffin Khoái Tươi Nhiều Vị (3pcs) - Sausage & Ham & Chicken Pizza Muffin Set.jpg'),
(28, 'Set Pizza Muffin Thích Béo Mê Ly (3pcs) - Pepperoni & Crab Stick & Grilled Beef Pizza Muffin Set', 'Đế Bánh Bột Tươi, Phô Mai Mozzarella, Phô Mai Cheddar, Xúc xích Pepperoni, Thịt Bò Nướng, Cà Chua, Thanh Cua, Dứa (Thơm), Xốt Mayonnaise, Xốt Phô Mai', 69000.00, '2026-01-01 22:43:10', '2026-01-01 22:43:10', 3, 0, '1767328990_Set Pizza Muffin Thích Béo Mê Ly (3pcs) - Pepperoni & Crab Stick & Grilled Beef Pizza Muffin Set.jpg'),
(29, 'Set Pizza Muffin Rau Củ (3pcs) - Veggie Pizza Muffin Set', 'Đế Bánh Bột Tươi, Phô Mai Mozzarella, Phô Mai Cheddar, Xốt 7 Loại Phô Mai Đặc Biệt, Xốt Cà Chua, Xốt Phô Mai, Xốt Mayonnaise, Xốt Tiêu Đen, Nấm Mỡ, Dứa (Thơm), Ớt Chuông Xanh, Oliu Đen, Bắp (Ngô)', 69000.00, '2026-01-02 09:05:24', '2026-01-02 03:05:24', 3, 0, '1767329027_Set Pizza Muffin Rau Củ (3pcs) - Veggie Pizza Muffin Set.jpg'),
(30, 'Gà Viên Phô Mai Đút Lò - Cheesy Chicken Popcorn', 'Gà Viên Popcorn, Thịt Heo Xông Khói, Phô Mai Mozzarella, Xốt Pizza', 69000.00, '2026-01-02 03:25:16', '2026-01-02 03:25:16', 4, 0, '1767345916_Gà Viên Phô Mai Đút Lò - Cheesy Chicken Popcorn.jpg'),
(31, 'Gà Viên Xốt Hàn Quốc - Korean Chicken Popcorn', 'Gà Popcorn, Dứa, Cà Chua, Mè, Xốt Hàn Quốc\r\n\r\n', 69000.00, '2026-01-02 03:25:57', '2026-01-02 03:25:57', 4, 0, '1767345957_Gà Viên Xốt Hàn Quốc - Korean Chicken Popcorn.png'),
(32, 'Cánh Gà Phủ Xốt Hàn Quốc (4 Miếng) - Korean Tossed Chicken Wings (4pcs)', 'Cánh Gà, Xốt Hàn Quốc', 99000.00, '2026-01-02 03:26:34', '2026-01-02 03:26:34', 4, 0, '1767345994_Cánh Gà Phủ Xốt Hàn Quốc (4 Miếng) - Korean Tossed Chicken Wings (4pcs).png'),
(33, 'Cánh Gà Phủ Xốt Hàn Quốc (6 Miếng) - Korean Tossed Chicken Wings (6pcs)', 'Cánh Gà, Xốt Hàn Quốc', 129000.00, '2026-01-02 03:27:09', '2026-01-02 03:27:09', 4, 0, '1767346029_Cánh Gà Phủ Xốt Hàn Quốc (6 Miếng) - Korean Tossed Chicken Wings (6pcs).png'),
(34, 'Cánh Gà Phủ Xốt BBQ Kiểu Mỹ (4 miếng) - American BBQ Tossed Chicken Wings (4pcs)', 'Cánh Gà, Xốt BBQ', 99000.00, '2026-01-02 03:27:46', '2026-01-02 03:27:46', 4, 0, '1767346066_Cánh Gà Phủ Xốt BBQ Kiểu Mỹ (4 miếng) - American BBQ Tossed Chicken Wings (4pcs).png'),
(35, 'Cánh Gà Phủ Xốt BBQ Kiểu Mỹ (6 Miếng) - American BBQ Tossed Chicken Wings (6pcs)', 'Cánh Gà, Xốt BBQ', 129000.00, '2026-01-02 03:28:15', '2026-01-02 03:28:15', 4, 0, '1767346095_Cánh Gà Phủ Xốt BBQ Kiểu Mỹ (6 Miếng) - American BBQ Tossed Chicken Wings (6pcs).jpg'),
(36, 'Mỳ Ý Thịt Heo Xông Khói Xốt Kem - Bacon Carbonara Pasta', 'Mỳ Ý, Xốt Carbonara, Thịt Xông Khói, Bột Rong Biển, Bột Tỏi', 109000.00, '2026-01-02 03:29:16', '2026-01-02 03:29:16', 5, 0, '1767346156_Mỳ Ý Thịt Heo Xông Khói Xốt Kem - Bacon Carbonara Pasta.jpg'),
(37, 'Mỳ Ý Bò Bằm Xốt Marinara - Bolognese Pasta', 'Mỳ Ý, Xốt Bò Bằm, Bột Rong Biển, Bột Tỏi', 109000.00, '2026-01-02 03:29:48', '2026-01-02 03:29:48', 5, 0, '1767346188_Mỳ Ý Bò Bằm Xốt Marinara - Bolognese Pasta.jpg'),
(38, 'Mỳ Ý Xúc Xích Xốt Marinara - Sausage Pepperoni Pasta', 'Mỳ Ý, Xúc Xích Parsley, Thịt Xông Khói, Xúc Xích Pepperoni, Xốt Marinara, Bột Rong Biển, Bột Tỏi', 89000.00, '2026-01-02 03:30:58', '2026-01-02 03:30:58', 5, 0, '1767346258_Mỳ Ý Xúc Xích Xốt Marinara - Sausage Pepperoni Pasta.jpg'),
(39, 'Mỳ Ý, Xúc Xích Parsley, Thịt Xông Khói, Xúc Xích Pepperoni, Xốt Marinara, Bột Rong Biển, Bột Tỏi', 'Mỳ Ý, Hành Tây, Tôm, Xốt Marinara, Ớt Vẩy, Bột Rong Biển, Bột Tỏi', 109000.00, '2026-01-02 03:31:44', '2026-01-02 03:31:44', 5, 0, '1767346304_Mỳ Ý Tôm Xốt Marinara Cay - Spicy Shrimp Pasta.jpg'),
(40, 'Mỳ Ý Thịt Heo Xông Khói Xốt Marinara Cay - Spicy Bacon Bacon Pasta', 'Mỳ Ý, Thịt Xông Khói, Thịt Xông Khói Miếng, Xốt Marinara, Ớt Vẩy, Bột Rong Biển, Bột Tỏi\r\n\r\n', 89000.00, '2026-01-02 03:32:17', '2026-01-02 03:32:17', 5, 0, '1767346337_Mỳ Ý Thịt Heo Xông Khói Xốt Marinara Cay - Spicy Bacon Bacon Pasta.jpg'),
(41, 'Mỳ Ý Rau Củ Xốt Marinara - Veggie Pasta', 'Mỳ Ý, Ớt Chuông Xanh, Nấm, Cà Chua, Dứa, Ô-Liu Đen, Xốt Marinara, Bột Rong Biển, Bột Tỏi', 89000.00, '2026-01-02 03:32:50', '2026-01-02 03:32:50', 5, 0, '1767346370_Mỳ Ý Rau Củ Xốt Marinara - Veggie Pasta.jpg'),
(42, 'Xúc Xích Xông Khói Đút Lò (4 miếng) - Baked Pork Sausages (4pcs)', 'Xúc Xích Xông Khói, Xốt BBQ', 49000.00, '2026-01-02 03:33:24', '2026-01-02 03:33:24', 6, 0, '1767346404_Xúc Xích Xông Khói Đút Lò (4 miếng) - Baked Pork Sausages (4pcs).jpg'),
(43, 'Xúc Xích Xông Khói Đút Lò (8 miếng) - Baked Pork Sausages (8pcs)', 'Xúc Xích Xông Khói, Xốt BBQ', 89000.00, '2026-01-02 03:33:49', '2026-01-02 03:33:49', 6, 0, '1767346429_Xúc Xích Xông Khói Đút Lò (8 miếng) - Baked Pork Sausages (8pcs).jpg'),
(44, 'Set Bánh Tart Phô Mai Nhiệt Đới Dứa & Mật Ong (2pcs) - Tropical Pineapple & Honey Cheese Tart Set (2pcs)', 'Đế Mỏng Giòn, Xốt Kem Chua, Mật Ong, Dứa (Thơm), Phô Mai Mozzarella', 59000.00, '2026-01-02 03:34:35', '2026-01-02 03:34:35', 7, 0, '1767346475_Set Bánh Tart Phô Mai Nhiệt Đới Dứa & Mật Ong (2pcs) - Tropical Pineapple & Honey Cheese Tart Set (2pcs).jpg'),
(45, 'Set Bánh Tart Phô Mai Socola Quả Mọng (2pcs) - Choco & Berry Cheese Tart Set (2pcs)', 'Đế Mỏng Giòn, Xốt Kem Chua, Xốt Socola Đen, Socola Chip, Xốt Quả Mọng, Phô Mai Mozzarella', 59000.00, '2026-01-02 03:35:25', '2026-01-02 03:35:25', 7, 0, '1767346525_Set Bánh Tart Phô Mai Socola Quả Mọng (2pcs) - Choco & Berry Cheese Tart Set (2pcs).jpg'),
(46, 'Set Bánh Tart Phô Mai Trái Cây 2 Vị (3pcs) - Fruity Favorites Cheese Tart Set (3pcs)', 'Đế Mỏng Giòn, Xốt Kem Chua, Xốt Quả Mọng, Mật Ong, Dứa (Thơm), Phô Mai Mozzarella', 79000.00, '2026-01-02 03:35:57', '2026-01-02 03:35:57', 7, 0, '1767346557_Set Bánh Tart Phô Mai Trái Cây 2 Vị (3pcs) - Fruity Favorites Cheese Tart Set (3pcs).jpg'),
(47, 'Set Bánh Tart Phô Mai Socola Đậm Đà (3pcs) - Choco Lovers Cheese Tart Set (3pcs)', 'Đế Mỏng Giòn, Xốt Kem Chua, Xốt Socola Đen, Socola Chip, Phô Mai Mozzarella\r\n\r\n', 79000.00, '2026-01-02 03:36:26', '2026-01-02 03:36:26', 7, 0, '1767346586_Set Bánh Tart Phô Mai Socola Đậm Đà (3pcs) - Choco Lovers Cheese Tart Set (3pcs).jpg'),
(48, 'Set Bánh Tart Phô Mai Đủ Vị Tiệc Tùng (9pcs) - Party Cheese Tart Set (9pcs)', 'Đế Mỏng Giòn, Xốt Kem Chua, Mật Ong, Dứa (Thơm), Xốt Socola Đen, Socola Chip, Xốt Quả Mọng, Phô Mai Mozzarella', 199000.00, '2026-01-02 03:37:12', '2026-01-02 03:37:12', 7, 0, '1767346632_Set Bánh Tart Phô Mai Đủ Vị Tiệc Tùng (9pcs) - Party Cheese Tart Set (9pcs).jpg'),
(49, 'Chai Coca-Cola 390ml', 'Coca cola\r\n', 25000.00, '2026-01-02 03:38:00', '2026-01-02 03:38:00', 8, 0, '1767346680_Chai Coca-Cola 390ml - Coca Cola 390ml bottle.jpg'),
(50, 'Chai Fanta 390ml', 'Fanta', 25000.00, '2026-01-02 09:44:20', '2026-01-02 03:44:20', 8, 0, '1767347060_Chai Fanta 390ml - Fanta 390ml Bottle.jpg'),
(51, 'Chai Sprite 390ml', 'Sup Srite', 25000.00, '2026-01-02 03:39:03', '2026-01-02 03:39:03', 8, 0, '1767346743_Chai Sprite 390ml - Sprite 390ml Bottle.jpg'),
(52, 'Lon Coca-Cola zero 320ml', 'Lon coca', 25000.00, '2026-01-02 03:39:31', '2026-01-02 03:39:31', 8, 0, '1767346771_Lon Coca-Cola zero 320ml - Coca Cola zero 320ml can.jpg'),
(53, 'Chai Coca-Cola 1.5L', 'Chai Coca-Cola 1.5L', 35000.00, '2026-01-02 03:40:06', '2026-01-02 03:40:06', 8, 0, '1767346806_Chai Coca-Cola 1.5L - Coca Cola 1.5L Bottle.jpg'),
(54, 'Chai Sprite 1.5L', 'Chai Sprite 1.5L', 35000.00, '2026-01-02 03:40:53', '2026-01-02 03:40:53', 8, 0, '1767346853_Chai Sprite 1.5L - Sprite 1.5L Bottle.jpg'),
(55, 'Chai Fanta 1.5L', 'Chai Fanta 1.5L', 35000.00, '2026-01-02 03:41:19', '2026-01-02 03:41:19', 8, 0, '1767346879_Chai Fanta 1.5L - Fanta 1.5L Bottle.jpg');

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
(1, 'an', 'an@gmail.com', '$2y$10$XrG6uF9ucn3KnY1eovvM.umo1.yMLmUrZOPXsxnIKNnrfD7pfoVRO', '0363547545', 'Hòa Hảo', '2026-01-02 08:26:24', '2026-01-02 02:26:24'),
(2, 'Khánh Đăng', 'dang@gmail.com', '$2y$10$pLM8BArrYIs5MBrCta2hOe6nTwrXdakz.tfjbQwYyD7ek7nhbQ7AS', '0987654321', 'Trà Vinh', '2026-01-05 06:46:41', '2026-01-05 00:46:41'),
(3, 'Hữu Luân', 'luan@gmail.com', '$2y$10$ixKzQsB.JKtIi8vXjqn7lu9jLQAbgYxQP1G7dXqqSkUbVQYAd4l3m', '0123123123', 'Hưng Mỹ', '2026-01-05 06:48:07', NULL),
(4, 'Thanh Đỉnh', 'dinh@gmail.com', '$2y$10$tyZmGqFPMb5it33ik2GM5OArrywqBxk8U7GXO1/90ExEKS/.QCxka', '0123123456', 'Mỹ Thoa', '2026-01-05 06:51:26', NULL);

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
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`wishlist_id`, `tao_luc`, `nguoi_dung_id`, `product_id`) VALUES
(1, '2026-01-05', 3, 1),
(2, '2026-01-05', 3, 3),
(3, '2026-01-05', 3, 4),
(4, '2026-01-05', 3, 5),
(5, '2026-01-05', 4, 9),
(8, '2026-01-05', 1, 1),
(9, '2026-01-05', 1, 3),
(10, '2026-01-05', 1, 4),
(11, '2026-01-05', 1, 5);

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
  MODIFY `categories_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
