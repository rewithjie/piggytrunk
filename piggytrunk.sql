-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2026 at 02:49 AM
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
-- Database: `piggytrunk`
--

-- --------------------------------------------------------

--
-- Table structure for table `batches`
--

CREATE TABLE `batches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `raiser_id` bigint(20) UNSIGNED NOT NULL,
  `pig_type_id` bigint(20) UNSIGNED NOT NULL,
  `initial_quantity` int(11) NOT NULL,
  `current_quantity` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Planning',
  `total_investment` decimal(15,2) NOT NULL DEFAULT 0.00,
  `expected_profit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `batches`
--

-- --------------------------------------------------------

--
-- Table structure for table `batch_lifecycle_stages`
--

CREATE TABLE `batch_lifecycle_stages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `sequence` int(11) NOT NULL,
  `duration_days` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `batch_lifecycle_stages`
--

INSERT INTO `batch_lifecycle_stages` (`id`, `name`, `sequence`, `duration_days`, `created_at`, `updated_at`) VALUES
(1, 'Booster', 1, 7, '2026-04-15 07:19:56', '2026-04-15 07:19:56'),
(2, 'Pre-Starter', 2, 45, '2026-04-15 07:19:56', '2026-04-15 07:19:56'),
(3, 'Starter', 3, 60, '2026-04-15 07:19:56', '2026-04-15 07:19:56'),
(4, 'Grower', 4, 120, '2026-04-15 07:19:56', '2026-04-15 07:19:56'),
(5, 'Finisher', 5, 60, '2026-04-15 07:19:56', '2026-04-15 07:19:56'),
(6, 'Gilt Developer', 6, 180, '2026-04-15 07:19:56', '2026-04-15 07:19:56'),
(7, 'Gestation Feed', 7, 115, '2026-04-15 07:19:56', '2026-04-15 07:19:56'),
(8, 'Lactation Feed', 8, 21, '2026-04-15 07:19:56', '2026-04-15 07:19:56'),
(9, 'Separation', 9, 1, '2026-04-15 07:19:56', '2026-04-15 07:19:56'),
(10, 'Selling', 10, 1, '2026-04-15 15:15:46', '2026-04-15 15:15:46');

-- --------------------------------------------------------

--
-- Table structure for table `batch_stage_history`
--

CREATE TABLE `batch_stage_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `batch_id` bigint(20) UNSIGNED NOT NULL,
  `lifecycle_stage_id` bigint(20) UNSIGNED NOT NULL,
  `started_at` date DEFAULT NULL,
  `completed_at` date DEFAULT NULL,
  `status` enum('pending','in-progress','completed') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_categories`
--

CREATE TABLE `inventory_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_categories`
--

INSERT INTO `inventory_categories` (`id`, `name`, `code`, `description`, `created_at`, `updated_at`) VALUES
(1, 'FEEDS', 'FDS', 'Animal feed products', '2026-04-15 07:19:56', '2026-04-15 07:19:56'),
(2, 'VITAMINS', 'VIT', 'Vitamin supplements', '2026-04-15 07:19:56', '2026-04-15 07:19:56'),
(3, 'MEDICINE', 'MED', 'Medicines and treatments', '2026-04-15 07:19:56', '2026-04-15 07:19:56'),
(4, 'ADDITIVES', 'ADD', 'Feed additives and growth promoters', '2026-04-15 07:19:56', '2026-04-15 07:19:56');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `unit` varchar(255) NOT NULL,
  `cost_per_unit` decimal(10,2) NOT NULL,
  `quantity_on_hand` int(11) NOT NULL,
  `reorder_level` int(11) NOT NULL DEFAULT 10,
  `reorder_quantity` int(11) NOT NULL DEFAULT 50,
  `status` enum('Active','Inactive','Discontinued') NOT NULL DEFAULT 'Active',
  `supplier` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `raiser_id` bigint(20) UNSIGNED DEFAULT NULL,
  `movement_type` enum('in','out','distribute','adjust') NOT NULL,
  `quantity` int(11) NOT NULL,
  `cost_per_unit` decimal(10,2) NOT NULL,
  `total_cost` decimal(12,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investments`
--

CREATE TABLE `investments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `batch_id` bigint(20) UNSIGNED NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `current_value` decimal(12,2) NOT NULL,
  `expected_profit` decimal(12,2) NOT NULL DEFAULT 0.00,
  `actual_profit` decimal(12,2) NOT NULL DEFAULT 0.00,
  `investment_date` date NOT NULL,
  `expected_return_date` date DEFAULT NULL,
  `actual_return_date` date DEFAULT NULL,
  `status` enum('Pending','Active','Completed','Cancelled') NOT NULL DEFAULT 'Active',
  `roi_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investments`
--

-- --------------------------------------------------------

--
-- Table structure for table `investment_investors`
--

CREATE TABLE `investment_investors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `investment_id` bigint(20) UNSIGNED NOT NULL,
  `investor_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `expected_return` decimal(12,2) NOT NULL,
  `actual_return` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('Pending','Active','Completed') NOT NULL DEFAULT 'Active',
  `payout_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investors`
--

CREATE TABLE `investors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `type` enum('Individual','Organization') NOT NULL DEFAULT 'Individual',
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `total_invested` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_returned` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL DEFAULT 'default',
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL DEFAULT 'public',
  `size` bigint(20) UNSIGNED DEFAULT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_properties`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(22, '2024_01_01_000001_create_users_table', 1),
(23, '2024_01_01_000002_create_pig_types_table', 1),
(24, '2024_01_01_000003_create_raisers_table', 1),
(25, '2024_01_01_000004_create_batch_lifecycle_stages_table', 1),
(26, '2024_01_01_000005_create_batches_table', 1),
(27, '2024_01_01_000006_create_batch_stage_history_table', 1),
(28, '2024_01_01_000007_create_investors_table', 1),
(29, '2024_01_01_000008_create_investments_table', 1),
(30, '2024_01_01_000009_create_investment_investors_table', 1),
(31, '2024_01_01_000010_create_inventory_categories_table', 1),
(32, '2024_01_01_000011_create_inventory_items_table', 1),
(33, '2024_01_01_000012_create_inventory_movements_table', 1),
(34, '2024_01_01_000013_create_retail_categories_table', 1),
(35, '2024_01_01_000014_create_retail_products_table', 1),
(36, '2024_01_01_000015_create_retail_transactions_table', 1),
(37, '2024_01_01_000016_create_retail_transaction_details_table', 1),
(38, '2024_01_01_000017_create_system_settings_table', 1),
(39, '2024_01_01_000018_create_media_table', 1),
(40, '2024_01_01_000019_add_profile_image_to_users_table', 1),
(41, '2024_01_01_000020_add_images_to_retail_tables', 1),
(42, '2024_01_01_000021_create_safe_calculation_views', 1),
(44, '2026_04_15_225110_add_pig_type_to_raisers_table', 2),
(45, '2026_04_15_225334_make_raisers_code_nullable', 3),
(46, '2024_01_01_000015_add_supplier_to_retail_products_table', 4),
(47, '2026_04_15_230933_create_pig_type_lifecycle_stage_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `pig_types`
--

CREATE TABLE `pig_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pig_types`
--

INSERT INTO `pig_types` (`id`, `name`, `description`, `code`, `created_at`, `updated_at`) VALUES
(2, 'Fattening', 'Fattening stage', 'FAT', '2026-04-15 07:19:56', '2026-04-15 07:19:56'),
(3, 'Sow', 'Breeding female', 'SOW', '2026-04-15 07:19:56', '2026-04-15 07:19:56'),
(4, 'Boar', 'Breeding male', 'BOA', '2026-04-15 07:19:56', '2026-04-15 07:19:56');

-- --------------------------------------------------------

--
-- Table structure for table `raisers`
--

CREATE TABLE `raisers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `pig_type` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive','Suspended') NOT NULL DEFAULT 'Active',
  `total_capacity` int(11) NOT NULL DEFAULT 0,
  `total_investment` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `raisers`
--

INSERT INTO `raisers` (`id`, `code`, `name`, `location`, `contact_person`, `phone`, `email`, `address`, `pig_type`, `status`, `total_capacity`, `total_investment`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'Rejie Rosario', NULL, NULL, '09764257410', 'rejie@gmail.com', 'San Carlos City', 'Fattening', 'Active', 0, 0.00, '2026-04-15 14:54:54', '2026-04-15 14:54:54', NULL),
(2, NULL, 'marya', NULL, NULL, '09123456789', 'marya@gmail.com', 'Malasiqui', 'Sow', 'Active', 0, 0.00, '2026-04-15 15:18:40', '2026-04-15 15:18:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `retail_categories`
--

CREATE TABLE `retail_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category_image` varchar(255) DEFAULT NULL,
  `category_image_path` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `retail_categories`
--

INSERT INTO `retail_categories` (`id`, `name`, `slug`, `category_image`, `category_image_path`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Feeds', 'feeds', NULL, NULL, 'Animal feed products', '2026-04-15 07:19:56', '2026-04-15 07:19:56'),
(2, 'Vitamins', 'vitamins', NULL, NULL, 'Vitamin supplements', '2026-04-15 07:19:56', '2026-04-15 07:19:56'),
(3, 'Medicines', 'medicines', NULL, NULL, 'Medicines', '2026-04-15 07:19:56', '2026-04-15 07:19:56'),
(4, 'Growth Additives', 'growth-additives', NULL, NULL, 'Growth promoters', '2026-04-15 07:19:56', '2026-04-15 07:19:56');

-- --------------------------------------------------------

--
-- Table structure for table `retail_products`
--

CREATE TABLE `retail_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `reorder_level` int(11) NOT NULL DEFAULT 10,
  `image` varchar(255) DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `retail_products`
--

INSERT INTO `retail_products` (`id`, `code`, `name`, `category_id`, `description`, `price`, `cost`, `stock`, `reorder_level`, `image`, `supplier`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'RP-0001', 'test feeds', 1, NULL, 120.00, NULL, 9, 10, 'retail-products/MaUm7GoWv7SNV7IWJy2xUX3QFw9uhN9rOqnglE3M.png', NULL, 'Active', '2026-04-15 15:33:55', '2026-04-15 15:34:50', '2026-04-15 15:34:50'),
(2, 'RP-0002', 'mewo', 2, NULL, 12.00, NULL, 0, 10, 'retail-products/TBrIz105oNZTjOQyPxhf1cR2UHPqHW6yY4oXAqKm.jpg', NULL, 'Active', '2026-04-15 15:34:18', '2026-04-15 15:34:52', '2026-04-15 15:34:52'),
(3, 'RP-0003', 'feed1', 1, NULL, 111.00, NULL, 2, 10, 'retail-products/IyBNjEqpEWADmaNpLkuw1jVjbITxEURR5QlG24Al.jpg', NULL, 'Active', '2026-04-15 15:34:42', '2026-04-15 15:34:46', '2026-04-15 15:34:46'),
(4, 'INV-0001', 'Booster Feeds', 1, NULL, 0.00, 0.00, 100, 10, NULL, 'JVL Agri Supply', 'Active', '2026-04-15 16:21:52', '2026-04-15 16:21:52', NULL),
(5, 'INV-0002', 'pang medisina', 1, NULL, 0.00, 0.00, 50, 10, NULL, 'Vet Care Supplies', 'Active', '2026-04-15 16:21:52', '2026-04-15 16:21:52', NULL),
(6, 'INV-0003', 'vitamins c', 1, NULL, 10.00, 10.00, 10, 10, NULL, 'sa tabi lang ng gilid', 'Active', '2026-04-15 16:24:02', '2026-04-15 16:24:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `retail_transactions`
--

CREATE TABLE `retail_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `raiser_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `channel` enum('Walk-in','Online','Wholesale','Delivery') NOT NULL DEFAULT 'Walk-in',
  `subtotal` decimal(12,2) NOT NULL,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL,
  `payment_method` enum('Cash','Check','Card','Bank Transfer','Online') NOT NULL DEFAULT 'Cash',
  `status` enum('Pending','Completed','Cancelled') NOT NULL DEFAULT 'Completed',
  `transaction_date` date NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `retail_transaction_details`
--

CREATE TABLE `retail_transaction_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `line_total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `type` enum('string','integer','decimal','boolean','json') NOT NULL DEFAULT 'string',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'admin',
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `profile_photo_path` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `address`, `profile_photo`, `profile_photo_path`, `profile_image`, `is_active`, `remember_token`, `email_verified_at`, `created_at`, `updated_at`) VALUES
(1, 'Admin Rej', 'admin@piggytrunk', '$2y$12$eYOi/pDxOCmUmTXAGSh5ve31K2UN.3XLkRv4w97cgOO3jcna9YZYy', 'System Administrator', '09123456789', NULL, NULL, NULL, NULL, 1, NULL, NULL, '2026-04-15 07:19:56', '2026-04-15 08:38:12');

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_batch_summary`
-- (See below for the actual view)
--
CREATE TABLE `vw_batch_summary` (
`id` bigint(20) unsigned
,`status` varchar(255)
,`pig_type` varchar(255)
,`initial_quantity` int(11)
,`current_quantity` int(11)
,`start_date` date
,`end_date` date
,`total_investment` decimal(15,2)
,`expected_profit` decimal(15,2)
,`survival_rate` decimal(17,4)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_dashboard_summary`
-- (See below for the actual view)
--
CREATE TABLE `vw_dashboard_summary` (
`active_raisers` bigint(21)
,`active_batches` bigint(21)
,`total_capital` decimal(34,2)
,`expected_profit` decimal(34,2)
,`active_investments` bigint(21)
,`avg_roi_percentage` decimal(40,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_inventory_low_stock`
-- (See below for the actual view)
--
CREATE TABLE `vw_inventory_low_stock` (
`id` bigint(20) unsigned
,`code` varchar(255)
,`name` varchar(255)
,`quantity_on_hand` int(11)
,`reorder_level` int(11)
,`reorder_quantity` int(11)
,`stock_status` varchar(12)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_investment_allocation_summary`
-- (See below for the actual view)
--
CREATE TABLE `vw_investment_allocation_summary` (
`total_investment` decimal(37,2)
,`pig_type` varchar(255)
,`allocation_amount` decimal(37,2)
,`allocation_percentage` decimal(43,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_investment_summary`
-- (See below for the actual view)
--
CREATE TABLE `vw_investment_summary` (
`id` bigint(20) unsigned
,`code` varchar(255)
,`status` enum('Pending','Active','Completed','Cancelled')
,`batch_id` bigint(20) unsigned
,`total_amount` decimal(12,2)
,`current_value` decimal(12,2)
,`expected_profit` decimal(12,2)
,`actual_profit` decimal(12,2)
,`roi_percentage` decimal(5,2)
,`investor_count` bigint(21)
,`total_investor_contribution` decimal(34,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_sales_summary`
-- (See below for the actual view)
--
CREATE TABLE `vw_sales_summary` (
`sale_date` date
,`transaction_count` bigint(21)
,`total_sales` decimal(34,2)
,`avg_transaction_value` decimal(16,6)
);

-- --------------------------------------------------------

--
-- Structure for view `vw_batch_summary`
--
DROP TABLE IF EXISTS `vw_batch_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_batch_summary`  AS SELECT `b`.`id` AS `id`, `b`.`status` AS `status`, `pt`.`name` AS `pig_type`, `b`.`initial_quantity` AS `initial_quantity`, `b`.`current_quantity` AS `current_quantity`, `b`.`start_date` AS `start_date`, `b`.`end_date` AS `end_date`, `b`.`total_investment` AS `total_investment`, `b`.`expected_profit` AS `expected_profit`, `b`.`current_quantity`/ `b`.`initial_quantity` * 100 AS `survival_rate` FROM (`batches` `b` join `pig_types` `pt` on(`b`.`pig_type_id` = `pt`.`id`)) WHERE `b`.`deleted_at` is null ;

-- --------------------------------------------------------

--
-- Structure for view `vw_dashboard_summary`
--
DROP TABLE IF EXISTS `vw_dashboard_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_dashboard_summary`  AS SELECT (select count(0) from `raisers` where `raisers`.`status` = 'Active' and `raisers`.`deleted_at` is null) AS `active_raisers`, (select count(0) from `batches` where `batches`.`status` = 'Active' and `batches`.`deleted_at` is null) AS `active_batches`, coalesce((select sum(`investments`.`total_amount`) from `investments` where `investments`.`deleted_at` is null),0) AS `total_capital`, coalesce((select sum(`investments`.`expected_profit`) from `investments` where `investments`.`deleted_at` is null),0) AS `expected_profit`, (select count(0) from `investments` where `investments`.`status` = 'Active' and `investments`.`deleted_at` is null) AS `active_investments`, CASE WHEN coalesce((select sum(`investments`.`total_amount`) from `investments` where `investments`.`deleted_at` is null),0) = 0 THEN 0 ELSE round(coalesce((select sum(`investments`.`expected_profit`) from `investments` where `investments`.`deleted_at` is null),0) / nullif(coalesce((select sum(`investments`.`total_amount`) from `investments` where `investments`.`deleted_at` is null),0),1) * 100,2) END AS `avg_roi_percentage` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_inventory_low_stock`
--
DROP TABLE IF EXISTS `vw_inventory_low_stock`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_inventory_low_stock`  AS SELECT `inventory_items`.`id` AS `id`, `inventory_items`.`code` AS `code`, `inventory_items`.`name` AS `name`, `inventory_items`.`quantity_on_hand` AS `quantity_on_hand`, `inventory_items`.`reorder_level` AS `reorder_level`, `inventory_items`.`reorder_quantity` AS `reorder_quantity`, CASE WHEN `inventory_items`.`quantity_on_hand` = 0 THEN 'OUT_OF_STOCK' WHEN `inventory_items`.`quantity_on_hand` <= `inventory_items`.`reorder_level` THEN 'LOW_STOCK' ELSE 'IN_STOCK' END AS `stock_status` FROM `inventory_items` WHERE `inventory_items`.`status` = 'Active' AND `inventory_items`.`deleted_at` is null ORDER BY `inventory_items`.`quantity_on_hand` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `vw_investment_allocation_summary`
--
DROP TABLE IF EXISTS `vw_investment_allocation_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_investment_allocation_summary`  AS SELECT coalesce(sum(`b`.`total_investment`),0) AS `total_investment`, `pt`.`name` AS `pig_type`, coalesce(sum(`b`.`total_investment`),0) AS `allocation_amount`, CASE WHEN coalesce(sum(case when `b`.`deleted_at` is null then `b`.`total_investment` else 0 end) over (),0) = 0 THEN 0 ELSE round(coalesce(sum(`b`.`total_investment`),0) / nullif(coalesce(sum(case when `b`.`deleted_at` is null then `b`.`total_investment` else 0 end) over (),0),0) * 100,2) END AS `allocation_percentage` FROM (`batches` `b` join `pig_types` `pt` on(`b`.`pig_type_id` = `pt`.`id`)) WHERE `b`.`deleted_at` is null GROUP BY `pt`.`id`, `pt`.`name` ORDER BY coalesce(sum(`b`.`total_investment`),0) DESC ;

-- --------------------------------------------------------

--
-- Structure for view `vw_investment_summary`
--
DROP TABLE IF EXISTS `vw_investment_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_investment_summary`  AS SELECT `i`.`id` AS `id`, `i`.`code` AS `code`, `i`.`status` AS `status`, `b`.`id` AS `batch_id`, `i`.`total_amount` AS `total_amount`, `i`.`current_value` AS `current_value`, `i`.`expected_profit` AS `expected_profit`, `i`.`actual_profit` AS `actual_profit`, `i`.`roi_percentage` AS `roi_percentage`, count(distinct `ii`.`investor_id`) AS `investor_count`, sum(`ii`.`amount`) AS `total_investor_contribution` FROM ((`investments` `i` join `batches` `b` on(`i`.`batch_id` = `b`.`id`)) left join `investment_investors` `ii` on(`i`.`id` = `ii`.`investment_id`)) WHERE `i`.`deleted_at` is null GROUP BY `i`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_sales_summary`
--
DROP TABLE IF EXISTS `vw_sales_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_sales_summary`  AS SELECT cast(`rt`.`transaction_date` as date) AS `sale_date`, count(0) AS `transaction_count`, sum(`rt`.`total_amount`) AS `total_sales`, avg(`rt`.`total_amount`) AS `avg_transaction_value` FROM `retail_transactions` AS `rt` WHERE `rt`.`deleted_at` is null GROUP BY cast(`rt`.`transaction_date` as date) ORDER BY cast(`rt`.`transaction_date` as date) DESC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `batches`
--
ALTER TABLE `batches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `batches_code_unique` (`code`),
  ADD KEY `batches_raiser_id_foreign` (`raiser_id`),
  ADD KEY `batches_pig_type_id_foreign` (`pig_type_id`);

--
-- Indexes for table `batch_lifecycle_stages`
--
ALTER TABLE `batch_lifecycle_stages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `batch_lifecycle_stages_name_unique` (`name`);

--
-- Indexes for table `batch_stage_history`
--
ALTER TABLE `batch_stage_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `batch_stage_history_batch_id_foreign` (`batch_id`),
  ADD KEY `batch_stage_history_lifecycle_stage_id_foreign` (`lifecycle_stage_id`);

--
-- Indexes for table `inventory_categories`
--
ALTER TABLE `inventory_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_categories_name_unique` (`name`),
  ADD UNIQUE KEY `inventory_categories_code_unique` (`code`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_items_code_unique` (`code`),
  ADD KEY `inventory_items_category_id_foreign` (`category_id`);

--
-- Indexes for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_movements_item_id_foreign` (`item_id`),
  ADD KEY `inventory_movements_raiser_id_foreign` (`raiser_id`);

--
-- Indexes for table `investments`
--
ALTER TABLE `investments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `investments_code_unique` (`code`),
  ADD KEY `investments_batch_id_foreign` (`batch_id`);

--
-- Indexes for table `investment_investors`
--
ALTER TABLE `investment_investors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `investment_investors_investment_id_investor_id_unique` (`investment_id`,`investor_id`),
  ADD KEY `investment_investors_investor_id_foreign` (`investor_id`);

--
-- Indexes for table `investors`
--
ALTER TABLE `investors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `investors_email_unique` (`email`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `media_order_column_index` (`order_column`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pig_types`
--
ALTER TABLE `pig_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pig_types_name_unique` (`name`),
  ADD UNIQUE KEY `pig_types_code_unique` (`code`);

--
-- Indexes for table `raisers`
--
ALTER TABLE `raisers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `raisers_code_unique` (`code`);

--
-- Indexes for table `retail_categories`
--
ALTER TABLE `retail_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `retail_categories_name_unique` (`name`),
  ADD UNIQUE KEY `retail_categories_slug_unique` (`slug`);

--
-- Indexes for table `retail_products`
--
ALTER TABLE `retail_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `retail_products_code_unique` (`code`),
  ADD KEY `retail_products_category_id_foreign` (`category_id`);

--
-- Indexes for table `retail_transactions`
--
ALTER TABLE `retail_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `retail_transactions_code_unique` (`code`),
  ADD KEY `retail_transactions_raiser_id_foreign` (`raiser_id`);

--
-- Indexes for table `retail_transaction_details`
--
ALTER TABLE `retail_transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `retail_transaction_details_transaction_id_foreign` (`transaction_id`),
  ADD KEY `retail_transaction_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_settings_key_unique` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batches`
--
ALTER TABLE `batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `batch_lifecycle_stages`
--
ALTER TABLE `batch_lifecycle_stages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `batch_stage_history`
--
ALTER TABLE `batch_stage_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_categories`
--
ALTER TABLE `inventory_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `investment_investors`
--
ALTER TABLE `investment_investors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investors`
--
ALTER TABLE `investors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `pig_types`
--
ALTER TABLE `pig_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `raisers`
--
ALTER TABLE `raisers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `retail_categories`
--
ALTER TABLE `retail_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `retail_products`
--
ALTER TABLE `retail_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `retail_transactions`
--
ALTER TABLE `retail_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `retail_transaction_details`
--
ALTER TABLE `retail_transaction_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `batches`
--
ALTER TABLE `batches`
  ADD CONSTRAINT `batches_pig_type_id_foreign` FOREIGN KEY (`pig_type_id`) REFERENCES `pig_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `batches_raiser_id_foreign` FOREIGN KEY (`raiser_id`) REFERENCES `raisers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `batch_stage_history`
--
ALTER TABLE `batch_stage_history`
  ADD CONSTRAINT `batch_stage_history_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `batch_stage_history_lifecycle_stage_id_foreign` FOREIGN KEY (`lifecycle_stage_id`) REFERENCES `batch_lifecycle_stages` (`id`);

--
-- Constraints for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD CONSTRAINT `inventory_items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `inventory_categories` (`id`);

--
-- Constraints for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_movements_raiser_id_foreign` FOREIGN KEY (`raiser_id`) REFERENCES `raisers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `investments`
--
ALTER TABLE `investments`
  ADD CONSTRAINT `investments_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `investment_investors`
--
ALTER TABLE `investment_investors`
  ADD CONSTRAINT `investment_investors_investment_id_foreign` FOREIGN KEY (`investment_id`) REFERENCES `investments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `investment_investors_investor_id_foreign` FOREIGN KEY (`investor_id`) REFERENCES `investors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `retail_products`
--
ALTER TABLE `retail_products`
  ADD CONSTRAINT `retail_products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `retail_categories` (`id`);

--
-- Constraints for table `retail_transactions`
--
ALTER TABLE `retail_transactions`
  ADD CONSTRAINT `retail_transactions_raiser_id_foreign` FOREIGN KEY (`raiser_id`) REFERENCES `raisers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `retail_transaction_details`
--
ALTER TABLE `retail_transaction_details`
  ADD CONSTRAINT `retail_transaction_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `retail_products` (`id`),
  ADD CONSTRAINT `retail_transaction_details_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `retail_transactions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
