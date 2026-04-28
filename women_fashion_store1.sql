-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2026 at 05:45 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `women_fashion_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Clothes'),
(2, 'Shoes'),
(3, 'Bags'),
(4, 'Accessories');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `created_at`) VALUES
(1, 1, 336.00, 'completed', '2026-04-27 14:50:27');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 3, 5, 56.00),
(2, 1, 19, 1, 56.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image`, `category_id`, `created_at`) VALUES
(1, 'NIKE SHOE', 50.00, 'This shoes is perfect for women , espicially ladies that like sport-fashioned shoes', '1777294497_204470705-1-white.avif', 2, '2026-04-27 12:54:57'),
(2, 'new balance shoes', 40.00, 'these are shoes you can run with in the morning', '1777294694_New-Balance-WX608v5-Women-s-Workout-Walking-Running-Training-Shoes-Sneakers_7c9f2057-b5ab-4772-8aad-2d7a3c390437.2635d1da2d5067def10bd4dc0027b395.avif', 2, '2026-04-27 12:58:14'),
(3, 'short dress', 56.00, 'elegant square neck long sleeve dress frill detail - Temu Nigeria', '1777294796_8d4ccb68-8ce9-4c52-beed-ca9a28d43634.webp', 1, '2026-04-27 12:59:56'),
(4, 'high heels', 45.00, 'these are best for parties fashion killing', '1777296772_apostolos-vamvouras-tISS8kXY7gQ-unsplash.jpg', 2, '2026-04-27 13:32:52'),
(5, 'air force', 100.00, 'these are best for street fashion killing', '1777296815_danilo-capece-NoVnXXmDNi0-unsplash.jpg', 2, '2026-04-27 13:33:35'),
(6, 'rose heels', 120.00, 'these are best for parties fashion killing', '1777296839_dario-gomes-nTu4n0fpRho-unsplash.jpg', 2, '2026-04-27 13:33:59'),
(7, 'blue mamaid shoes', 100.00, 'these are best for kids', '1777296907_rakesh-sitnoor-24UV2VJnwT8-unsplash.jpg', 2, '2026-04-27 13:35:07'),
(8, 'dress', 150.00, 'na', '1777298459_pew-nguyen-3nnG9JLbDSI-unsplash.jpg', 1, '2026-04-27 14:00:59'),
(9, 'kid dress', 30.00, 'best for parties and out', '1777298820_arto-suraj-kzziJN4Exao-unsplash.jpg', 1, '2026-04-27 14:07:00'),
(10, 'dress', 123.00, 'wedding dress', '1777298856_alexander-mass-vqykG3tH_Wo-unsplash.jpg', 1, '2026-04-27 14:07:36'),
(11, 'dress', 45.00, 'for kids', '1777298939_celine-druguet-jH6nZse9o6E-unsplash.jpg', 1, '2026-04-27 14:08:59'),
(12, 'pant', 50.00, 'stylish', '1777299119_engin-akyurt-5raPrOhbKQo-unsplash.jpg', 1, '2026-04-27 14:11:59'),
(13, 'ring', 45.00, 'wedding ring', '1777299338_sabrianna-CCpQ12CZ2Pc-unsplash.jpg', 4, '2026-04-27 14:15:38'),
(14, 'watch', 34.00, 'rolex', '1777299994_ady-teenagerinro-P6rFv09Z34o-unsplash.jpg', 4, '2026-04-27 14:26:34'),
(15, 'glasses', 56.00, 'smart glasses', '1777300021_claudio-schwarz-e8TtkC5xyv4-unsplash.jpg', 4, '2026-04-27 14:27:01'),
(16, 'necklace', 43.00, 'necklace made in diamonds', '1777300251_coppertist-wu-LUapVlCxqn8-unsplash.jpg', 4, '2026-04-27 14:30:51'),
(17, 'jewerly', 57.00, 'jewerly', '1777300356_istockphoto-477455932-612x612.jpg', 4, '2026-04-27 14:32:36'),
(18, 'mini bag', 23.00, 'luis vitton', '1777300720_godz1-V5ZDSSe5BvY-unsplash.jpg', 3, '2026-04-27 14:38:40'),
(19, 'bag', 56.00, 'carriable', '1777300754_laura-chouette-irG6YMkrrcQ-unsplash.jpg', 3, '2026-04-27 14:39:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@store.com', '$2y$10$BZiOELqcoufcciD1QRNogO/eoJssZOZXpnD7d/Cdtu7AXpTLlIJD6', 'admin', '2026-04-27 12:29:59'),
(2, 'NDAYISENGA ELIEL', 'elitech@gmail.com', '$2y$10$LHTV6QGoUUl09uHK54ek.ub77Ul5Y8JpR5j7SMzmlmGd075yE4rPW', 'customer', '2026-04-27 12:36:03');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
