-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Nov 28, 2024 at 12:59 AM
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
-- Database: `db_kapitansisig`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `firstname` varchar(191) NOT NULL,
  `lastname` varchar(191) NOT NULL,
  `username` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `is_banned` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=not_banned,1=banned',
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `position` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `firstname`, `lastname`, `username`, `password`, `is_banned`, `created_at`, `position`) VALUES
(1, 'Kristyle Marie', 'Modin', 'kmgmodin', '$2y$10$Aj1WGyrfovmy5VQA3wXKleP0nrV42X5knV.jfYJI1AStwXaV3kzDK', 0, '2024-10-16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`) VALUES
(1, 'Sisig Meal', 0),
(2, 'Barkada Meals', 0),
(5, 'Shawarma Meals', 0),
(6, 'Meryenda Meals', 0),
(7, 'Extra', 0);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(566) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`) VALUES
(1, 'khim'),
(2, 'Dawn'),
(3, 'Shiloh Millondaga'),
(4, 'Evanica'),
(5, 'Kristyle'),
(6, 'Shiloh'),
(7, 'AJ');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `category` varchar(191) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `reorder_point` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `name`, `unit_id`, `category`, `quantity`, `reorder_point`) VALUES
(4, 'Pork Belly', 13, 'Meat & Poultry', 3900.00, 100.00),
(5, 'Spoon', 14, 'Cutlery', -14.00, 10.00),
(6, 'Soy Sauce', 18, 'Condiments', 150.00, 10.00),
(7, 'Tuna', 13, 'Meat & Poultry', 4500.00, 10.00),
(8, 'Egg', 14, 'Others', 170.00, 10.00),
(21, 'Chopstick', 14, 'Cutlery', 0.00, 1.00);

-- --------------------------------------------------------

--
-- Table structure for table `ingredients_items`
--

CREATE TABLE `ingredients_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients_items`
--

INSERT INTO `ingredients_items` (`id`, `order_id`, `ingredient_id`, `unit_id`, `price`, `quantity`) VALUES
(1, 1, 8, 15, 120.00, 2.00),
(2, 2, 8, 15, 120.00, 1.00),
(3, 3, 5, 14, 10.00, 1.00),
(4, 4, 5, 14, 10.00, 1.00),
(5, 5, 5, 14, 10.00, 1.00),
(6, 6, 4, 16, 250.00, 2.00),
(7, 7, 8, 15, 120.00, 100.00),
(8, 8, 5, 14, 10.00, 100.00),
(9, 9, 8, 15, 120.00, 1.00),
(10, 10, 21, 14, 1.00, 2.00),
(11, 11, 21, 14, 1.00, 2.00),
(12, 12, 5, 14, 10.00, 13.00),
(13, 13, 21, 14, 1.00, 100.00),
(14, 14, 4, 16, 250.00, 5.00),
(15, 14, 6, 19, 70.00, 1.00),
(16, 14, 8, 15, 120.00, 15.00),
(17, 14, 5, 14, 10.00, 100.00),
(18, 15, 7, 16, 170.00, 5.00);

-- --------------------------------------------------------

--
-- Table structure for table `ingredients_reports`
--

CREATE TABLE `ingredients_reports` (
  `id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients_reports`
--

INSERT INTO `ingredients_reports` (`id`, `start_date`, `end_date`, `created_at`) VALUES
(1, '2024-11-26', '0000-00-00', '2024-11-26 04:55:57'),
(2, '2024-11-26', '0000-00-00', '2024-11-26 05:01:27');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients_report_details`
--

CREATE TABLE `ingredients_report_details` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `ingredient_name` varchar(255) DEFAULT NULL,
  `quantity_used` decimal(10,2) DEFAULT NULL,
  `unit_name` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `batch_number` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_quantity` decimal(10,2) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients_report_details`
--

INSERT INTO `ingredients_report_details` (`id`, `report_id`, `ingredient_name`, `quantity_used`, `unit_name`, `type`, `batch_number`, `created_at`, `total_quantity`, `expiry_date`) VALUES
(768, 1, 'Pork Belly', 500.00, 'g', 'Used', NULL, '2024-11-26 04:55:57', NULL, NULL),
(769, 1, 'Spoon', 10.00, 'pcs', 'Used', NULL, '2024-11-26 04:55:57', NULL, NULL),
(770, 1, 'Soy Sauce', 750.00, 'ml', 'Used', NULL, '2024-11-26 04:55:57', NULL, NULL),
(771, 1, 'Tuna', 500.00, 'g', 'Used', NULL, '2024-11-26 04:55:57', NULL, NULL),
(772, 1, 'Egg', 10.00, 'pcs', 'Used', NULL, '2024-11-26 04:55:57', NULL, NULL),
(773, 1, 'Chopstick', 0.00, '0', 'Stock In', NULL, '2024-11-26 04:55:58', 100.00, '2024-11-26'),
(774, 1, 'Pork Belly', 4.50, '0', 'Stock In', NULL, '2024-11-26 04:55:58', 5000.00, '2024-11-26'),
(775, 1, 'Spoon', 100.00, '0', 'Stock In', NULL, '2024-11-26 04:55:58', 100.00, '2024-11-26'),
(776, 1, 'Soy Sauce', 1.00, '0', 'Stock In', NULL, '2024-11-26 04:55:58', 250.00, '2024-11-26'),
(777, 1, 'Egg', 15.00, '0', 'Stock In', NULL, '2024-11-26 04:55:58', 180.00, '2024-11-26'),
(778, 1, 'Tuna', 5.00, '0', 'Stock In', NULL, '2024-11-26 04:55:58', 4500.00, '2024-11-26'),
(779, 1, 'Pork Belly', 500.00, 'g', 'Stock Out', '14', '2024-11-26 04:55:58', 4500.00, '2024-11-26'),
(780, 1, 'Pork Belly', 4000.00, 'g', 'Remaining', NULL, '2024-11-26 04:55:58', NULL, NULL),
(781, 1, 'Spoon', 90.00, 'pcs', 'Remaining', NULL, '2024-11-26 04:55:58', NULL, NULL),
(782, 1, 'Soy Sauce', 250.00, 'ml', 'Remaining', NULL, '2024-11-26 04:55:58', NULL, NULL),
(783, 1, 'Tuna', 4500.00, 'g', 'Remaining', NULL, '2024-11-26 04:55:58', NULL, NULL),
(784, 1, 'Egg', 170.00, 'pcs', 'Remaining', NULL, '2024-11-26 04:55:58', NULL, NULL),
(785, 1, 'Chopstick', 0.00, 'pcs', 'Remaining', NULL, '2024-11-26 04:55:58', NULL, NULL),
(786, 2, 'Pork Belly', 500.00, 'g', 'Used', NULL, '2024-11-26 05:01:27', NULL, NULL),
(787, 2, 'Spoon', 10.00, 'pcs', 'Used', NULL, '2024-11-26 05:01:27', NULL, NULL),
(788, 2, 'Soy Sauce', 750.00, 'ml', 'Used', NULL, '2024-11-26 05:01:27', NULL, NULL),
(789, 2, 'Tuna', 500.00, 'g', 'Used', NULL, '2024-11-26 05:01:27', NULL, NULL),
(790, 2, 'Egg', 10.00, 'pcs', 'Used', NULL, '2024-11-26 05:01:27', NULL, NULL),
(791, 2, 'Chopstick', 0.00, '0', 'Stock In', NULL, '2024-11-26 05:01:27', 100.00, '2024-11-26'),
(792, 2, 'Pork Belly', 4.50, '0', 'Stock In', NULL, '2024-11-26 05:01:27', 5000.00, '2024-11-26'),
(793, 2, 'Spoon', 100.00, '0', 'Stock In', NULL, '2024-11-26 05:01:27', 100.00, '2024-11-26'),
(794, 2, 'Soy Sauce', 1.00, '0', 'Stock In', NULL, '2024-11-26 05:01:27', 250.00, '2024-11-26'),
(795, 2, 'Egg', 15.00, '0', 'Stock In', NULL, '2024-11-26 05:01:27', 180.00, '2024-11-26'),
(796, 2, 'Tuna', 5.00, '0', 'Stock In', NULL, '2024-11-26 05:01:27', 4500.00, '2024-11-26'),
(797, 2, 'Pork Belly', 500.00, 'g', 'Stock Out', '14', '2024-11-26 05:01:27', 4500.00, '2024-11-26'),
(798, 2, 'Spoon', 1.00, 'pcs', 'Stock Out', '3', '2024-11-26 05:01:27', 4500.00, '2024-11-26'),
(799, 2, 'Spoon', 3.00, 'pcs', 'Stock Out', '13', '2024-11-26 05:01:27', 4500.00, '2024-11-26'),
(800, 2, 'Pork Belly', 4000.00, 'g', 'Remaining', NULL, '2024-11-26 05:01:27', NULL, NULL),
(801, 2, 'Spoon', 86.00, 'pcs', 'Remaining', NULL, '2024-11-26 05:01:27', NULL, NULL),
(802, 2, 'Soy Sauce', 250.00, 'ml', 'Remaining', NULL, '2024-11-26 05:01:27', NULL, NULL),
(803, 2, 'Tuna', 4500.00, 'g', 'Remaining', NULL, '2024-11-26 05:01:27', NULL, NULL),
(804, 2, 'Egg', 170.00, 'pcs', 'Remaining', NULL, '2024-11-26 05:01:27', NULL, NULL),
(805, 2, 'Chopstick', 0.00, 'pcs', 'Remaining', NULL, '2024-11-26 05:01:27', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_reports`
--

CREATE TABLE `inventory_reports` (
  `id` int(11) NOT NULL,
  `report_date` date NOT NULL,
  `report_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_report_details`
--

CREATE TABLE `inventory_report_details` (
  `id` int(11) NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `tracking_no` varchar(50) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `report_type` enum('Remaining','Completed','Cancelled') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `tracking_no` varchar(100) NOT NULL,
  `invoice_no` varchar(100) NOT NULL,
  `total_amount` varchar(100) NOT NULL,
  `amount_received` decimal(10,2) NOT NULL,
  `change_money` varchar(200) NOT NULL,
  `order_date` datetime DEFAULT NULL,
  `order_status` enum('Placed','Preparing','Completed','Cancelled') NOT NULL DEFAULT 'Placed' COMMENT 'placed, preparing, completed, cancelled',
  `payment_mode` varchar(100) NOT NULL COMMENT 'cash,online',
  `order_placed_by_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `tracking_no`, `invoice_no`, `total_amount`, `amount_received`, `change_money`, `order_date`, `order_status`, `payment_mode`, `order_placed_by_id`) VALUES
(54, 1, '000001', 'INV-370967', '1100', 10000.00, '8900', '2024-11-26 12:53:40', 'Completed', 'Online Payment', 'Kristyle Marie'),
(55, 1, '000002', 'INV-866469', '100', 100.00, '0', '2024-11-28 06:25:58', 'Completed', 'Cash Payment', 'Kristyle Marie'),
(56, 1, '000003', 'INV-368064', '100', 1000.00, '900', '2024-11-28 06:27:31', 'Cancelled', 'Cash Payment', 'Kristyle Marie');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` varchar(100) NOT NULL,
  `quantity` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `price`, `quantity`) VALUES
(1, 1, 8, '10.00', '2'),
(2, 2, 8, '10.00', '21'),
(3, 3, 8, '10.00', '2'),
(4, 4, 8, '10.00', '25'),
(5, 5, 8, '10.00', '10'),
(6, 6, 5, '100.00', '1'),
(7, 6, 8, '10.00', '1'),
(8, 6, 6, '100.00', '1'),
(9, 7, 8, '10.00', '39'),
(10, 8, 8, '10.00', '1'),
(11, 9, 5, '100.00', '2'),
(12, 10, 8, '10.00', '10'),
(13, 11, 8, '10.00', '16'),
(14, 11, 5, '100.00', '1'),
(15, 11, 6, '100.00', '1'),
(16, 12, 8, '10.00', '900'),
(17, 13, 5, '100.00', '3'),
(18, 14, 6, '100.00', '3'),
(19, 15, 8, '10.00', '900'),
(20, 46, 5, '100.00', '5'),
(21, 46, 6, '100.00', '5'),
(22, 46, 8, '10.00', '5'),
(23, 47, 5, '100.00', '1'),
(24, 48, 5, '100.00', '1'),
(25, 49, 8, '10.00', '10'),
(26, 50, 5, '100.00', '2'),
(27, 51, 8, '10.00', '1'),
(28, 52, 8, '10.00', '500'),
(29, 53, 5, '100.00', '1'),
(30, 53, 8, '10.00', '1'),
(31, 54, 5, '100.00', '5'),
(32, 54, 6, '100.00', '5'),
(33, 54, 8, '10.00', '10'),
(34, 55, 5, '100.00', '1'),
(35, 56, 5, '100.00', '1');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `productname` varchar(191) NOT NULL,
  `description` varchar(191) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `productname`, `description`, `price`, `image`, `created_at`, `quantity`) VALUES
(5, 1, 'Pork Sisig', '', 100.00, 'pics/uploads/products/1724471945.jpg\r\n', '2024-11-08', 1),
(6, 1, 'Tuna Sisig', '', 100.00, 'pics/uploads/products/1724471945.jpg\r\n', '2024-11-08', 5),
(8, 7, 'Fried Egg', 'Wow', 10.00, 'pics/uploads/products/1724471945.jpg\r\n', '2024-11-08', 170);

-- --------------------------------------------------------

--
-- Table structure for table `purchaseorders`
--

CREATE TABLE `purchaseorders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `tracking_no` varchar(100) NOT NULL,
  `invoice_no` varchar(100) NOT NULL,
  `total_amount` varchar(100) NOT NULL,
  `order_date` datetime NOT NULL,
  `order_status` varchar(100) NOT NULL,
  `ingPayment_mode` varchar(100) NOT NULL,
  `order_placed_by_id` varchar(100) NOT NULL,
  `supplierName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchaseorders`
--

INSERT INTO `purchaseorders` (`id`, `customer_id`, `tracking_no`, `invoice_no`, `total_amount`, `order_date`, `order_status`, `ingPayment_mode`, `order_placed_by_id`, `supplierName`) VALUES
(1, 1, '000001', 'INV-701266', '240', '2024-11-19 08:36:57', 'Delivered', 'Online Payment', 'Kristyle Marie', '1'),
(2, 1, '000002', 'INV-497049', '120', '2024-11-19 08:50:18', 'Delivered', 'Online Payment', 'Kristyle Marie', '1'),
(3, 1, '000003', 'INV-968122', '10', '2024-11-21 19:48:58', 'Delivered', 'Cash Payment', 'Kristyle Marie', '1'),
(4, 1, '000004', 'INV-290634', '10', '2024-11-21 20:14:51', 'Delivered', 'Online Payment', 'Kristyle Marie', '1'),
(5, 1, '000005', 'INV-781854', '10', '2024-11-21 20:17:51', 'Delivered', 'Cash Payment', 'Kristyle Marie', '1'),
(6, 1, '000006', 'INV-611176', '500', '2024-11-23 00:03:57', 'Delivered', 'Cash Payment', 'Kristyle Marie', '1'),
(7, 1, '000007', 'INV-892454', '12000', '2024-11-24 10:10:26', 'Delivered', 'Cash Payment', 'Kristyle Marie', '1'),
(8, 1, '000008', 'INV-213809', '1000', '2024-11-24 10:53:49', 'Delivered', 'Cash Payment', 'Kristyle Marie', '1'),
(9, 1, '000009', 'INV-472535', '120', '2024-11-24 11:04:55', 'Delivered', 'Cash Payment', 'Kristyle Marie', '1'),
(10, 1, '000010', 'INV-582204', '2', '2024-11-24 18:49:31', 'Delivered', 'Cash Payment', 'Kristyle Marie', '1'),
(11, 1, '000011', 'INV-526827', '2', '2024-11-24 18:57:30', 'Delivered', 'Cash Payment', 'Kristyle Marie', '1'),
(12, 1, '000012', 'INV-350961', '130', '2024-11-25 08:11:48', 'Delivered', 'Cash Payment', 'Kristyle Marie', '1'),
(13, 1, '000013', 'INV-566639', '100', '2024-11-26 06:46:12', 'Delivered', 'Cash Payment', 'Kristyle Marie', '1'),
(14, 1, '000014', 'INV-758274', '4120', '2024-11-26 12:51:01', 'Delivered', 'Cash Payment', 'Kristyle Marie', '1'),
(15, 1, '000015', 'INV-462247', '850', '2024-11-26 12:52:24', 'Delivered', 'Cash Payment', 'Kristyle Marie', '2');

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `product_id`, `name`, `created_at`) VALUES
(1, 1, '', '2024-10-22 05:38:04'),
(2, 2, '', '2024-10-22 06:57:40'),
(3, 3, '', '2024-10-22 07:03:38'),
(4, 4, '', '2024-11-08 12:49:51'),
(5, 5, '', '2024-11-08 13:05:37'),
(6, 6, '', '2024-11-08 13:25:18'),
(7, 8, '', '2024-11-08 13:50:49');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ingredients`
--

CREATE TABLE `recipe_ingredients` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipe_ingredients`
--

INSERT INTO `recipe_ingredients` (`id`, `recipe_id`, `ingredient_id`, `quantity`, `unit_id`) VALUES
(1, 1, 15, 1.00, 16),
(5, 2, 19, 50.00, 13),
(6, 2, 22, 50.00, 18),
(7, 2, 21, 50.00, 13),
(8, 2, 18, 1.00, 14),
(9, 2, 20, 50.00, 13),
(10, 2, 23, 50.00, 18),
(11, 3, 24, 100.00, 13),
(12, 1, 16, 1.00, 14),
(13, 4, 4, 100.00, 13),
(14, 4, 5, 1.00, 14),
(15, 4, 6, 50.00, 18),
(16, 5, 4, 100.00, 13),
(17, 5, 5, 1.00, 14),
(18, 5, 6, 100.00, 18),
(19, 6, 7, 100.00, 13),
(20, 6, 5, 1.00, 14),
(21, 6, 6, 50.00, 13),
(22, 7, 8, 1.00, 14);

-- --------------------------------------------------------

--
-- Table structure for table `saved_ingredients_reports`
--

CREATE TABLE `saved_ingredients_reports` (
  `id` int(11) NOT NULL,
  `report_date` date NOT NULL,
  `report_time` time NOT NULL,
  `report_type` enum('products','ingredients') NOT NULL,
  `report_data` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved_ingredients_reports`
--

INSERT INTO `saved_ingredients_reports` (`id`, `report_date`, `report_time`, `report_type`, `report_data`, `created_at`, `start_date`, `end_date`) VALUES
(1, '2024-11-26', '05:55:58', 'ingredients', '[{\"ingredient_name\":\"Pork Belly\",\"quantity_used\":\"500\",\"unit_name\":\"g\",\"type\":\"Used\"},{\"ingredient_name\":\"Spoon\",\"quantity_used\":\"10\",\"unit_name\":\"pcs\",\"type\":\"Used\"},{\"ingredient_name\":\"Soy Sauce\",\"quantity_used\":\"750\",\"unit_name\":\"ml\",\"type\":\"Used\"},{\"ingredient_name\":\"Tuna\",\"quantity_used\":\"500\",\"unit_name\":\"g\",\"type\":\"Used\"},{\"ingredient_name\":\"Egg\",\"quantity_used\":\"10\",\"unit_name\":\"pcs\",\"type\":\"Used\"},{\"ingredient_name\":\"Chopstick\",\"quantity_used\":\"0.00\",\"unit_name\":\"pcs\",\"total_quantity\":\"100.00\",\"expiry_date\":\"2024-11-26\",\"type\":\"Stock In\"},{\"ingredient_name\":\"Pork Belly\",\"quantity_used\":\"4.50\",\"unit_name\":\"kg\",\"total_quantity\":\"5000.00\",\"expiry_date\":\"2024-11-26\",\"type\":\"Stock In\"},{\"ingredient_name\":\"Spoon\",\"quantity_used\":\"100.00\",\"unit_name\":\"pcs\",\"total_quantity\":\"100.00\",\"expiry_date\":\"2024-11-26\",\"type\":\"Stock In\"},{\"ingredient_name\":\"Soy Sauce\",\"quantity_used\":\"1.00\",\"unit_name\":\"L\",\"total_quantity\":\"250.00\",\"expiry_date\":\"2024-11-26\",\"type\":\"Stock In\"},{\"ingredient_name\":\"Egg\",\"quantity_used\":\"15.00\",\"unit_name\":\"dozen\",\"total_quantity\":\"180.00\",\"expiry_date\":\"2024-11-26\",\"type\":\"Stock In\"},{\"ingredient_name\":\"Tuna\",\"quantity_used\":\"5.00\",\"unit_name\":\"kg\",\"total_quantity\":\"4500.00\",\"expiry_date\":\"2024-11-26\",\"type\":\"Stock In\"},{\"batch_number\":\"14\",\"ingredient_name\":\"Pork Belly\",\"quantity_used\":\"500.00\",\"unit_name\":\"g\",\"type\":\"Stock Out\",\"reason\":\"Production\"},{\"ingredient_name\":\"Pork Belly\",\"quantity_used\":\"4000.00\",\"unit_name\":\"g\",\"type\":\"Remaining\"},{\"ingredient_name\":\"Spoon\",\"quantity_used\":\"90.00\",\"unit_name\":\"pcs\",\"type\":\"Remaining\"},{\"ingredient_name\":\"Soy Sauce\",\"quantity_used\":\"250.00\",\"unit_name\":\"ml\",\"type\":\"Remaining\"},{\"ingredient_name\":\"Tuna\",\"quantity_used\":\"4500.00\",\"unit_name\":\"g\",\"type\":\"Remaining\"},{\"ingredient_name\":\"Egg\",\"quantity_used\":\"170.00\",\"unit_name\":\"pcs\",\"type\":\"Remaining\"},{\"ingredient_name\":\"Chopstick\",\"quantity_used\":\"0.00\",\"unit_name\":\"pcs\",\"type\":\"Remaining\"}]', '2024-11-26 04:55:58', '2024-11-26', '0000-00-00'),
(2, '2024-11-26', '06:01:27', 'ingredients', '[{\"ingredient_name\":\"Pork Belly\",\"quantity_used\":\"500\",\"unit_name\":\"g\",\"type\":\"Used\"},{\"ingredient_name\":\"Spoon\",\"quantity_used\":\"10\",\"unit_name\":\"pcs\",\"type\":\"Used\"},{\"ingredient_name\":\"Soy Sauce\",\"quantity_used\":\"750\",\"unit_name\":\"ml\",\"type\":\"Used\"},{\"ingredient_name\":\"Tuna\",\"quantity_used\":\"500\",\"unit_name\":\"g\",\"type\":\"Used\"},{\"ingredient_name\":\"Egg\",\"quantity_used\":\"10\",\"unit_name\":\"pcs\",\"type\":\"Used\"},{\"ingredient_name\":\"Chopstick\",\"quantity_used\":\"0.00\",\"unit_name\":\"pcs\",\"total_quantity\":\"100.00\",\"expiry_date\":\"2024-11-26\",\"type\":\"Stock In\"},{\"ingredient_name\":\"Pork Belly\",\"quantity_used\":\"4.50\",\"unit_name\":\"kg\",\"total_quantity\":\"5000.00\",\"expiry_date\":\"2024-11-26\",\"type\":\"Stock In\"},{\"ingredient_name\":\"Spoon\",\"quantity_used\":\"100.00\",\"unit_name\":\"pcs\",\"total_quantity\":\"100.00\",\"expiry_date\":\"2024-11-26\",\"type\":\"Stock In\"},{\"ingredient_name\":\"Soy Sauce\",\"quantity_used\":\"1.00\",\"unit_name\":\"L\",\"total_quantity\":\"250.00\",\"expiry_date\":\"2024-11-26\",\"type\":\"Stock In\"},{\"ingredient_name\":\"Egg\",\"quantity_used\":\"15.00\",\"unit_name\":\"dozen\",\"total_quantity\":\"180.00\",\"expiry_date\":\"2024-11-26\",\"type\":\"Stock In\"},{\"ingredient_name\":\"Tuna\",\"quantity_used\":\"5.00\",\"unit_name\":\"kg\",\"total_quantity\":\"4500.00\",\"expiry_date\":\"2024-11-26\",\"type\":\"Stock In\"},{\"batch_number\":\"14\",\"ingredient_name\":\"Pork Belly\",\"quantity_used\":\"500.00\",\"unit_name\":\"g\",\"type\":\"Stock Out\",\"reason\":\"Production\"},{\"batch_number\":\"3\",\"ingredient_name\":\"Spoon\",\"quantity_used\":\"1.00\",\"unit_name\":\"pcs\",\"type\":\"Stock Out\",\"reason\":\"Expired\"},{\"batch_number\":\"13\",\"ingredient_name\":\"Spoon\",\"quantity_used\":\"3.00\",\"unit_name\":\"pcs\",\"type\":\"Stock Out\",\"reason\":\"Expired\"},{\"ingredient_name\":\"Pork Belly\",\"quantity_used\":\"4000.00\",\"unit_name\":\"g\",\"type\":\"Remaining\"},{\"ingredient_name\":\"Spoon\",\"quantity_used\":\"86.00\",\"unit_name\":\"pcs\",\"type\":\"Remaining\"},{\"ingredient_name\":\"Soy Sauce\",\"quantity_used\":\"250.00\",\"unit_name\":\"ml\",\"type\":\"Remaining\"},{\"ingredient_name\":\"Tuna\",\"quantity_used\":\"4500.00\",\"unit_name\":\"g\",\"type\":\"Remaining\"},{\"ingredient_name\":\"Egg\",\"quantity_used\":\"170.00\",\"unit_name\":\"pcs\",\"type\":\"Remaining\"},{\"ingredient_name\":\"Chopstick\",\"quantity_used\":\"0.00\",\"unit_name\":\"pcs\",\"type\":\"Remaining\"}]', '2024-11-26 05:01:27', '2024-11-26', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `saved_products_reports`
--

CREATE TABLE `saved_products_reports` (
  `id` int(11) NOT NULL,
  `report_date` date NOT NULL,
  `report_time` time NOT NULL,
  `report_type` enum('products','ingredients') NOT NULL,
  `report_data` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved_products_reports`
--

INSERT INTO `saved_products_reports` (`id`, `report_date`, `report_time`, `report_type`, `report_data`, `created_at`, `start_date`, `end_date`) VALUES
(28, '2024-11-28', '06:38:49', 'products', '{\"remaining_products\":[{\"productname\":\"Pork Sisig\",\"quantity\":\"1\"},{\"productname\":\"Tuna Sisig\",\"quantity\":\"5\"},{\"productname\":\"Fried Egg\",\"quantity\":\"170\"}],\"completed_orders\":[{\"id\":\"34\",\"order_id\":\"55\",\"product_id\":\"5\",\"price\":\"100.00\",\"quantity\":\"1\",\"tracking_no\":\"000002\",\"productname\":\"Pork Sisig\"}],\"cancelled_orders\":[{\"id\":\"35\",\"order_id\":\"56\",\"product_id\":\"5\",\"price\":\"100.00\",\"quantity\":\"1\",\"tracking_no\":\"000003\",\"productname\":\"Pork Sisig\"}]}', '2024-11-27 22:38:49', '2024-11-28', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `stockin`
--

CREATE TABLE `stockin` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `purchaseorder_id` int(11) NOT NULL,
  `invoice_no` varchar(100) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `stockin_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stockin`
--

INSERT INTO `stockin` (`id`, `admin_id`, `purchaseorder_id`, `invoice_no`, `supplier_id`, `stockin_date`) VALUES
(1, 1, 1, 'INV-701266', 1, '2024-11-19 08:42:43'),
(2, 1, 2, 'INV-497049', 1, '2024-11-19 08:50:30'),
(3, 1, 3, 'INV-968122', 1, '2024-11-21 19:58:55'),
(4, 1, 4, 'INV-290634', 1, '2024-11-21 20:15:03'),
(5, 1, 5, 'INV-781854', 1, '2024-11-21 20:21:28'),
(6, 1, 6, 'INV-611176', 1, '2024-11-23 00:04:10'),
(7, 1, 7, 'INV-892454', 1, '2024-11-24 10:10:40'),
(8, 1, 8, 'INV-213809', 1, '2024-11-24 10:54:00'),
(9, 1, 9, 'INV-472535', 1, '2024-11-24 11:05:12'),
(10, 1, 10, 'INV-582204', 1, '2024-11-24 18:49:42'),
(11, 1, 11, 'INV-526827', 1, '2024-11-24 18:57:41'),
(12, 1, 12, 'INV-350961', 1, '2024-11-25 08:12:02'),
(13, 1, 13, 'INV-566639', 1, '2024-11-26 06:46:25'),
(14, 1, 14, 'INV-758274', 1, '2024-11-26 12:51:15'),
(15, 1, 15, 'INV-462247', 2, '2024-11-26 12:52:37');

-- --------------------------------------------------------

--
-- Table structure for table `stockin_ingredients`
--

CREATE TABLE `stockin_ingredients` (
  `id` int(11) NOT NULL,
  `stockin_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `totalQuantity` decimal(10,2) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `totalPrice` int(11) NOT NULL,
  `expiryDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stockin_ingredients`
--

INSERT INTO `stockin_ingredients` (`id`, `stockin_id`, `ingredient_id`, `quantity`, `totalQuantity`, `unit_id`, `totalPrice`, `expiryDate`) VALUES
(1, 1, 8, 2.00, 0.00, 15, 240, '2024-11-19'),
(2, 2, 8, 1.00, 0.00, 15, 120, '2024-11-19'),
(3, 3, 5, 0.00, 0.00, 14, 10, '2024-11-21'),
(4, 4, 5, 0.00, 0.00, 14, 0, '2024-11-21'),
(5, 6, 4, 0.00, 1200.00, 16, 500, '2024-11-23'),
(6, 7, 8, 100.00, 1188.00, 15, 12000, '2024-11-24'),
(7, 8, 5, 90.00, 89.00, 14, 1000, '2024-11-24'),
(8, 9, 8, 1.00, 12.00, 15, 120, '2024-11-24'),
(9, 10, 21, 0.00, 2.00, 14, 2, '2024-11-24'),
(10, 11, 21, 0.00, 2.00, 14, 2, '2024-11-24'),
(11, 12, 5, 10.00, 13.00, 14, 130, '2024-11-25'),
(12, 13, 21, 0.00, 100.00, 14, 100, '2024-11-26'),
(13, 14, 4, 4.50, 5000.00, 16, 1250, '2024-11-26'),
(14, 14, 5, 0.00, 100.00, 14, 1000, '2024-11-26'),
(15, 14, 6, 1.00, 250.00, 19, 70, '2024-11-26'),
(16, 14, 8, 15.00, 180.00, 15, 1800, '2024-11-26'),
(17, 15, 7, 5.00, 4500.00, 16, 850, '2024-11-26');

-- --------------------------------------------------------

--
-- Table structure for table `stock_out`
--

CREATE TABLE `stock_out` (
  `id` int(11) NOT NULL,
  `stockin_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `batch_number` varchar(50) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `reason` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_out`
--

INSERT INTO `stock_out` (`id`, `stockin_id`, `ingredient_id`, `batch_number`, `quantity`, `reason`, `created_at`) VALUES
(73, 14, 4, '', 500.00, 'Production', '2024-11-26 12:55:46'),
(74, 3, 5, '', 1.00, 'Expired', '2024-11-26 13:01:11'),
(75, 12, 5, '', 3.00, 'Expired', '2024-11-26 13:01:18'),
(76, 14, 5, '', 100.00, 'Expired', '2024-11-26 13:02:03');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `firstname` varchar(191) NOT NULL,
  `lastname` varchar(191) NOT NULL,
  `phonenumber` varchar(191) NOT NULL,
  `address` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `firstname`, `lastname`, `phonenumber`, `address`) VALUES
(1, 'Kristyle Marie', 'Modin', '09094192413', 'Purok Lomboy Coog, Mandug,'),
(2, 'Kassandra Mae', 'Modin', '0934567890', 'Coog'),
(3, 'Kim 3', 'Modin', '1234567890', 'bfjdh');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_ingredients`
--

CREATE TABLE `supplier_ingredients` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_ingredients`
--

INSERT INTO `supplier_ingredients` (`id`, `supplier_id`, `ingredient_id`, `price`, `unit_id`) VALUES
(4, 1, 5, 10.00, 14),
(5, 2, 4, 150.00, 16),
(6, 2, 7, 170.00, 16),
(7, 1, 6, 70.00, 19),
(8, 2, 8, 120.00, 15),
(9, 1, 4, 250.00, 16),
(10, 1, 8, 120.00, 15),
(11, 1, 21, 1.00, 14);

-- --------------------------------------------------------

--
-- Table structure for table `units_of_measure`
--

CREATE TABLE `units_of_measure` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `uom_name` varchar(255) DEFAULT NULL,
  `type` enum('reference','bigger','smaller') DEFAULT NULL,
  `ratio` decimal(10,5) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `rounding_precision` decimal(10,5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units_of_measure`
--

INSERT INTO `units_of_measure` (`id`, `category_id`, `uom_name`, `type`, `ratio`, `active`, `rounding_precision`) VALUES
(13, 5, 'g', 'reference', 1.00000, 1, 0.01000),
(14, 6, 'pcs', 'reference', 1.00000, 1, 0.01000),
(15, 6, 'dozen', 'bigger', 12.00000, 1, 0.01000),
(16, 5, 'kg', 'bigger', 1000.00000, 1, 0.01000),
(18, 7, 'ml', 'reference', 1.00000, 1, 0.01000),
(19, 7, 'L', 'bigger', 1000.00000, 1, 0.01000),
(20, 7, 'gl', 'bigger', 0.00026, 1, 0.01000),
(22, 5, 'mg', 'smaller', 0.00100, 1, 0.00000);

-- --------------------------------------------------------

--
-- Table structure for table `unit_categories`
--

CREATE TABLE `unit_categories` (
  `id` int(11) NOT NULL,
  `category_unit_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unit_categories`
--

INSERT INTO `unit_categories` (`id`, `category_unit_name`) VALUES
(5, 'Weight'),
(6, 'Quantity'),
(7, 'Volume');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `ingredients_items`
--
ALTER TABLE `ingredients_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- Indexes for table `ingredients_reports`
--
ALTER TABLE `ingredients_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ingredients_report_details`
--
ALTER TABLE `ingredients_report_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_id` (`report_id`);

--
-- Indexes for table `inventory_reports`
--
ALTER TABLE `inventory_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_report_details`
--
ALTER TABLE `inventory_report_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_id` (`report_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchaseorders`
--
ALTER TABLE `purchaseorders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`),
  ADD KEY `ingredient_id` (`ingredient_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `saved_ingredients_reports`
--
ALTER TABLE `saved_ingredients_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saved_products_reports`
--
ALTER TABLE `saved_products_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stockin`
--
ALTER TABLE `stockin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stockin_ingredients`
--
ALTER TABLE `stockin_ingredients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_out`
--
ALTER TABLE `stock_out`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stockin_id` (`stockin_id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_ingredients`
--
ALTER TABLE `supplier_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_ingredients_ibfk_1` (`supplier_id`),
  ADD KEY `supplier_ingredients_ibfk_2` (`ingredient_id`);

--
-- Indexes for table `units_of_measure`
--
ALTER TABLE `units_of_measure`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `unit_categories`
--
ALTER TABLE `unit_categories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `ingredients_items`
--
ALTER TABLE `ingredients_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `ingredients_reports`
--
ALTER TABLE `ingredients_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ingredients_report_details`
--
ALTER TABLE `ingredients_report_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=806;

--
-- AUTO_INCREMENT for table `inventory_reports`
--
ALTER TABLE `inventory_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory_report_details`
--
ALTER TABLE `inventory_report_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `purchaseorders`
--
ALTER TABLE `purchaseorders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `saved_ingredients_reports`
--
ALTER TABLE `saved_ingredients_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `saved_products_reports`
--
ALTER TABLE `saved_products_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `stockin`
--
ALTER TABLE `stockin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `stockin_ingredients`
--
ALTER TABLE `stockin_ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `stock_out`
--
ALTER TABLE `stock_out`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `supplier_ingredients`
--
ALTER TABLE `supplier_ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `units_of_measure`
--
ALTER TABLE `units_of_measure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `unit_categories`
--
ALTER TABLE `unit_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD CONSTRAINT `ingredients_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units_of_measure` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_report_details`
--
ALTER TABLE `inventory_report_details`
  ADD CONSTRAINT `inventory_report_details_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `inventory_reports` (`id`);

--
-- Constraints for table `stock_out`
--
ALTER TABLE `stock_out`
  ADD CONSTRAINT `stock_out_ibfk_1` FOREIGN KEY (`stockin_id`) REFERENCES `stockin` (`id`),
  ADD CONSTRAINT `stock_out_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`);

--
-- Constraints for table `supplier_ingredients`
--
ALTER TABLE `supplier_ingredients`
  ADD CONSTRAINT `supplier_ingredients_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `supplier_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
