-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jan 01, 2026 at 08:35 AM
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
  `ngay_cap_nhap_categories` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categories_id`, `ten_categories`, `mo_ta_categories`, `ngay_tao_categories`, `ngay_cap_nhap_categories`) VALUES
(1, 'Pizza', '', '2025-12-31 07:37:30', '2025-12-31 07:37:30'),
(3, 'Pizza Muffin', '', '2025-12-31 07:45:00', '2025-12-31 07:45:00'),
(4, 'Gà', '', '2025-12-31 07:45:20', '2025-12-31 07:45:20'),
(5, 'Mỳ Ý', '', '2025-12-31 07:45:32', '2025-12-31 07:45:32'),
(6, 'Khai Vị', '', '2025-12-31 07:45:40', '2025-12-31 07:45:40'),
(7, 'Tráng Miệng', '', '2025-12-31 07:45:48', '2025-12-31 07:45:48'),
(8, 'Thức Uống', '', '2025-12-31 07:45:54', '2025-12-31 07:45:54');

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
(4, 329000.00, 'Trực tiếp', 'Chờ xác nhận', '2026-01-01 01:30:10', 1, '', '', '');

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
(3, 1, 299000.00, 'Nhỏ', '2026-01-01 01:30:10', 4, 1);

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
(1, 'Pizza Tôm & Bò Xốt Parmesan', 'Bánh ngoan nhắm', 299000.00, '2026-01-01 05:52:32', '2025-12-31 23:52:32', 1, '1767190336_Pizza Tôm & Bò Xốt Parmesan.jpg');

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
  ADD PRIMARY KEY (`categories_id`);

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
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
