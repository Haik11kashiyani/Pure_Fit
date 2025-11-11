-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2025 at 06:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pure_fit`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_us`
--

CREATE TABLE `about_us` (
  `id` int(11) NOT NULL,
  `section` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_us`
--

INSERT INTO `about_us` (`id`, `section`, `title`, `content`, `image_path`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'hero', 'About Pure Fit', 'We\'re passionate about creating high-quality fitness apparel that empowers your workout journey', NULL, 1, 1, '2025-10-30 10:24:52', '2025-10-30 10:24:52'),
(2, 'story', 'Our Story', 'Founded in 2025, Pure Fit Cloths began with a simple mission: to create workout clothes that don\'t just look good, but feel amazing during every rep, every run, and every yoga session.\r\n\r\nWhat started as a small collection has grown into a comprehensive line of performance wear that athletes and fitness enthusiasts trust for their most challenging workouts.', 'assets/img/hero2.png', 2, 1, '2025-10-30 10:24:52', '2025-10-30 10:26:15'),
(3, 'mission', 'Our Mission', 'To empower every individual on their fitness journey by providing premium, sustainable, and stylish activewear that performs as hard as you do.', NULL, 3, 1, '2025-10-30 10:24:52', '2025-10-30 10:24:52'),
(4, 'vision', 'Our Vision', 'To become the world\'s most trusted fitness apparel brand, known for innovation, quality, and commitment to our community.', NULL, 4, 1, '2025-10-30 10:24:52', '2025-10-30 10:24:52'),
(5, 'values_quality', 'Quality First', 'We use only the finest materials and construction techniques to ensure every piece meets our high standards.', 'fas fa-award', 5, 1, '2025-10-30 10:24:52', '2025-10-30 10:24:52'),
(6, 'values_sustainability', 'Sustainability', 'We\'re committed to reducing our environmental impact through eco-friendly materials and ethical manufacturing.', 'fas fa-leaf', 6, 1, '2025-10-30 10:24:52', '2025-10-30 10:24:52'),
(7, 'values_innovation', 'Innovation', 'We constantly push boundaries to create cutting-edge designs that enhance your performance.', 'fas fa-lightbulb', 7, 1, '2025-10-30 10:24:52', '2025-10-30 10:24:52'),
(8, 'values_community', 'Community', 'We believe in building a supportive community of fitness enthusiasts who inspire and motivate each other.', 'fas fa-users', 8, 1, '2025-10-30 10:24:52', '2025-10-30 10:24:52');

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='User shipping addresses with default address support';

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`address_id`, `user_id`, `full_name`, `phone`, `address_line1`, `address_line2`, `city`, `state`, `pincode`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 1, 'prakashdharaviya2005', '8154919492', 'khimrana', '', 'Jamnagar', 'Gujarat', '361120', 1, '2025-10-29 06:12:16', '2025-10-29 06:12:16');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='User shopping cart items. Unique constraint prevents duplicate rows for same variant.';

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `subcategory _id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Product categories. parent_id points to parent category (allows subcategories).';

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `subcategory _id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mens', 'Ohk updated', 1, '2025-10-24 11:29:25', '2025-10-26 05:57:27'),
(2, 2, 'Female ', 'Lai liyo Ben', 1, '2025-10-28 12:32:09', '2025-10-30 04:44:12');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `message_id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Messages submitted via contact form.';

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`message_id`, `name`, `email`, `subject`, `message`, `is_read`, `created_at`) VALUES
(1, 'Prakash Dharaviya', 'haik@gmail.com', 'Superb', 'that is amzing site..', 1, '2025-10-30 04:35:47'),
(2, 'Prakash Dharaviya', 'haik@gmail.com', 'Superb', 'that is amzing site..', 0, '2025-10-30 04:49:29');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='User wishlist / favorites.';

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `shipping_address` text NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `order_status` varchar(30) NOT NULL DEFAULT 'pending',
  `payment_status` varchar(30) NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(150) DEFAULT NULL,
  `payment_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Customer orders. Tracks chosen payment method and payment details.';

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `shipping_address`, `payment_method_id`, `order_status`, `payment_status`, `transaction_id`, `payment_notes`, `created_at`, `updated_at`) VALUES
(5, 1, 944.00, 'khimrana, Jamnagar, Gujarat - 361120\\nPhone: 8154919492', 1, 'delivered', 'completed', 'COD-1761719204-1', 'Cash on Delivery - Payment will be collected at the time of delivery', '2025-10-29 06:26:44', '2025-10-30 04:39:09');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price_at_time` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Items within an order; stores price snapshot for audit.';

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `variant_id`, `quantity`, `price_at_time`, `created_at`) VALUES
(1, 5, 6, NULL, 2, 400.00, '2025-10-29 06:26:44');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `payment_method_id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `display_name` varchar(120) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Available payment methods (cod, online, etc.).';

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`payment_method_id`, `name`, `display_name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'cod', 'Cash on Delivery', 'Pay with cash when your order is delivered', 1, '2025-10-29 06:26:36', '2025-10-29 06:26:36'),
(2, 'online', 'Online Payment', 'Pay online using UPI, Card, or Net Banking', 1, '2025-10-29 06:26:36', '2025-10-29 06:26:36');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `subcategory_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Products catalog. Use is_active to show/hide products.';

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `name`, `description`, `price`, `stock_quantity`, `image_path`, `is_active`, `created_at`, `updated_at`, `subcategory_id`) VALUES
(2, 1, 'Pent', 'Lay lio Bhai', 500.00, 3, 'assets/products/1761654162_6c890c25.png', 1, '2025-10-26 05:32:02', '2025-10-28 12:22:42', 1),
(3, 2, 'T-Shirt', 'Jordar', 600.00, 0, 'assets/products/1761654800_ea74a42d.png', 1, '2025-10-28 12:33:20', '2025-10-28 12:33:20', 2),
(4, 2, 'Jeans', 'jeans Is Here..!', 250.00, 0, 'assets/products/1761654912_b973f718.png', 1, '2025-10-28 12:35:12', '2025-10-28 12:35:12', 3),
(5, 1, 'T-Shirt', 'T shirt', 400.00, 0, 'assets/products/1761654999_0e025e6e.png', 1, '2025-10-28 12:36:39', '2025-10-28 12:36:39', 2),
(6, 1, 'Shirt', 'shirt for mens', 400.00, 0, 'assets/products/1761655144_d0de1ce8.png', 1, '2025-10-28 12:39:04', '2025-10-28 12:39:04', 4),
(7, 1, 'Jeans', 'jeans for mens', 550.00, 0, 'assets/products/1761655213_91e68c7c.png', 1, '2025-10-28 12:40:13', '2025-10-28 12:40:13', 3),
(8, 2, 'Shirt', 'Ladies Shirt', 200.00, 0, 'assets/products/1761655262_74157813.png', 1, '2025-10-28 12:41:02', '2025-10-28 12:41:02', 4);

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `variant_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(32) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Optional product variants (size/color) with individual stock counts.';

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`variant_id`, `product_id`, `size`, `stock_quantity`, `is_active`, `created_at`, `updated_at`) VALUES
(34, 2, '28', 3, 1, '2025-10-28 12:22:42', '2025-10-28 12:22:42'),
(35, 2, '30', 3, 1, '2025-10-28 12:22:42', '2025-10-28 12:22:42'),
(36, 2, '32', 2, 1, '2025-10-28 12:22:42', '2025-10-28 12:22:42'),
(37, 3, 'Small', 3, 1, '2025-10-28 12:33:20', '2025-10-28 12:33:20'),
(38, 4, 'Small', 4, 1, '2025-10-28 12:35:12', '2025-10-28 12:35:12'),
(39, 5, 'Small', 5, 1, '2025-10-28 12:36:39', '2025-10-28 12:36:39'),
(40, 6, 'Small', 3, 1, '2025-10-28 12:39:04', '2025-10-28 12:39:04'),
(41, 7, 'Small', 4, 1, '2025-10-28 12:40:13', '2025-10-28 12:40:13'),
(42, 8, 'Small', 5, 1, '2025-10-28 12:41:02', '2025-10-28 12:41:02');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(32) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Defines available user roles (admin, customer, etc.)';

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `description`, `created_at`) VALUES
(1, 'admin', 'Administrator with full access', '2025-10-24 11:04:26'),
(2, 'customer', 'Regular customer', '2025-10-24 11:04:26');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `subcategory_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_name` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Separate subcategory table linked to categories (optional alternative to parent_id).';

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`subcategory_id`, `category_id`, `subcategory_name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pents', 1, '2025-10-24 12:34:30', '2025-10-26 05:57:08'),
(2, 2, 'T-Shirts', 1, '2025-10-28 12:32:34', '2025-10-28 12:32:34'),
(3, 2, 'Jeans', 1, '2025-10-28 12:34:21', '2025-10-28 12:34:21'),
(4, 1, 'Shirt', 1, '2025-10-28 12:38:13', '2025-10-28 12:38:13');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT 'fas fa-user',
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `name`, `position`, `bio`, `image_path`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'RKU', 'Founder & CEO', 'Former fitness trainer with 10+ years in the industry', 'fas fa-user', 1, 1, '2025-10-30 10:24:52', '2025-10-30 10:27:01'),
(2, 'Hardik & Prakash', 'Head of Design', 'Fashion designer specializing in athletic wear', 'fas fa-user', 2, 1, '2025-10-30 10:24:52', '2025-10-30 10:27:35'),
(3, 'Devraj', 'Marketing Director', 'Digital marketing expert with passion for fitness', 'fas fa-user', 3, 1, '2025-10-30 10:24:52', '2025-10-30 10:27:55'),
(4, 'Prakash & Hardik', 'Operations Manager', 'Supply chain specialist ensuring quality delivery', 'fas fa-user', 4, 1, '2025-10-30 10:24:52', '2025-10-30 10:28:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(80) DEFAULT NULL,
  `last_name` varchar(80) DEFAULT NULL,
  `phone` varchar(24) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role_id` int(11) NOT NULL DEFAULT 2,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='User accounts (customers and admins). Use is_active to enable/disable accounts.';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `first_name`, `last_name`, `phone`, `address`, `role_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'prakashdharaviya2005', 'prakashdharaviya2005@gmail.com', '$2y$10$IsX54ldBsbpU9K2az0XKDu8Q5eL5fR0kWx1yiPn6KBluInInfiXq.', 'Prakash', 'Dharaviya', '8154919492', 'Khimrana', 2, 1, '2025-10-26 11:16:58', '2025-10-29 11:09:03'),
(3, 'admin', 'admin@purefit.com', '$2y$10$kWJ4/OjvOYTtK3pS1sygzOeof2Mziox2yEnGFw5tTv2L95Mx0ssPC', 'Admin', 'User', '1234567890', NULL, 1, 1, '2025-10-29 11:31:50', '2025-10-29 11:56:26'),
(4, 'haik', 'haik@gmail.com', '$2y$10$ifzCo7wkoH9io9nguHDhkO0nVos04Y4UmhriPV/w5O1LjI8q4HsxO', 'Hardik', 'Kashiyani', '1234567890', 'GulabNagar', 2, 1, '2025-10-29 11:58:11', '2025-10-30 04:06:28'),
(5, 'devraj', 'devu@gmail.com', '$2y$10$TM2s51EKwfVcz790jBTAW.brn8xDF8eKnm5rrEBhMFYGn1vSCYQBa', 'dev', 'raj', '0987643215', NULL, 1, 1, '2025-10-29 12:00:18', '2025-10-29 12:00:18');

-- --------------------------------------------------------

--
-- Table structure for table `verification_tokens`
--

CREATE TABLE `verification_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(128) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_section` (`section`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_user_default` (`user_id`,`is_default`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD UNIQUE KEY `ux_cart_user_product_variant` (`user_id`,`product_id`,`variant_id`),
  ADD KEY `fk_cart_product` (`product_id`),
  ADD KEY `fk_cart_variant` (`variant_id`),
  ADD KEY `idx_cart_user` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `fk_categories_parent` (`subcategory _id`),
  ADD KEY `idx_categories_name` (`name`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD UNIQUE KEY `ux_fav_user_product` (`user_id`,`product_id`),
  ADD KEY `fk_fav_product` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_orders_payment` (`payment_method_id`),
  ADD KEY `idx_orders_user` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `fk_orderitems_order` (`order_id`),
  ADD KEY `fk_orderitems_product` (`product_id`),
  ADD KEY `fk_orderitems_variant` (`variant_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`payment_method_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_products_category` (`category_id`),
  ADD KEY `idx_products_name` (`name`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`variant_id`),
  ADD KEY `fk_variants_product` (`product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`subcategory_id`),
  ADD KEY `fk_subcat_category` (`category_id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_role` (`role_id`);

--
-- Indexes for table `verification_tokens`
--
ALTER TABLE `verification_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_us`
--
ALTER TABLE `about_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `payment_method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `variant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `subcategory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `verification_tokens`
--
ALTER TABLE `verification_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_cart_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`);

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_parent` FOREIGN KEY (`subcategory _id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_fav_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `fk_fav_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_payment` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`payment_method_id`),
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_orderitems_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_orderitems_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `fk_orderitems_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `fk_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `fk_subcat_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

--
-- Constraints for table `verification_tokens`
--
ALTER TABLE `verification_tokens`
  ADD CONSTRAINT `verification_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
