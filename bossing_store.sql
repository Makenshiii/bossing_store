-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2026 at 07:14 AM
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
-- Database: `bossing_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'Soft Drinks'),
(2, 'Coffee'),
(3, 'Instant Noodles'),
(4, 'Snacks'),
(5, 'Canned Goods'),
(6, 'Biscuits'),
(7, 'Candies'),
(8, 'Soap'),
(9, 'Shampoo'),
(10, 'Rice');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `product_name`, `price`, `stock`) VALUES
(1, 1, 'Coca-Cola 1.5L', 72.00, 20),
(2, 1, 'Sprite 1.5L', 72.00, 20),
(3, 1, 'Royal 1.5L', 72.00, 15),
(4, 2, 'Nescafe Stick', 9.00, 100),
(5, 3, 'Lucky Me Beef', 18.00, 80),
(6, 3, 'Lucky Me Chicken', 18.00, 75),
(7, 4, 'Piattos Cheese', 25.00, 40),
(8, 4, 'Oishi Prawn Crackers', 12.00, 50),
(9, 5, 'Mega Sardines', 28.00, 35),
(10, 6, 'Skyflakes', 10.00, 70),
(11, 7, 'Maxx Candy', 1.00, 300),
(12, 8, 'Safeguard Soap', 38.00, 30),
(13, 9, 'Palmolive Shampoo Sachet', 8.00, 120),
(14, 10, 'Sinandoming', 55.00, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `product_id`, `quantity`, `total_price`, `sale_date`) VALUES
(1, 1, 2, 144.00, '2026-07-02 03:34:46'),
(2, 2, 1, 72.00, '2026-07-02 03:34:46'),
(3, 4, 5, 45.00, '2026-07-02 03:34:46'),
(4, 5, 3, 54.00, '2026-07-02 03:34:46'),
(5, 8, 2, 24.00, '2026-07-02 03:34:46'),
(6, 9, 4, 112.00, '2026-07-02 03:34:46'),
(7, 10, 6, 60.00, '2026-07-02 03:34:46'),
(8, 11, 20, 20.00, '2026-07-02 03:34:46'),
(9, 12, 2, 76.00, '2026-07-02 03:34:46'),
(10, 14, 5, 275.00, '2026-07-02 03:34:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Staff') NOT NULL DEFAULT 'Staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$5rD7CwWm0O4N5rMtwF4rQeXvF4Z4Q3m5vP9A6V8Qz5l8tG5zvJrD2', 'Admin', '2026-07-02 03:34:46'),
(2, 'staff', 'staff@gmail.com', '$2y$10$5rD7CwWm0O4N5rMtwF4rQeXvF4Z4Q3m5vP9A6V8Qz5l8tG5zvJrD2', 'Staff', '2026-07-02 03:34:46'),
(3, 'ngalis', 'ngalis@gmail.com', '$2y$10$aSUa8vCBjYGEeNwoQgyv2.TBYPF3qvGSCu0AIQPBk44R3Kp5HCpTS', 'Admin', '2026-07-02 03:59:45'),
(4, 'Jeo', 'Jeo@gmail.com', '$2y$10$go7ywwBlJi.3BmG98OxU1edH2nFwaGVZ228jCS.9F0f92Im713wGy', 'Staff', '2026-07-02 04:32:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
