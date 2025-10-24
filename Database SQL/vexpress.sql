-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2024 at 05:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vexpress`
--

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE `area` (
  `area_id` int(11) NOT NULL,
  `area_name` varchar(255) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `area`
--

INSERT INTO `area` (`area_id`, `area_name`, `is_deleted`) VALUES
(1, 'Kuala Lumpur', 0),
(2, 'Selangor', 0),
(3, 'Penang', 0),
(4, 'Johor', 0),
(5, 'Perak', 0),
(6, 'Kedah', 0),
(7, 'Kelantan', 0),
(8, 'Terengganu', 0),
(9, 'Pahang', 0),
(10, 'Perlis', 0),
(11, 'Sabah', 0),
(12, 'Sarawak', 0),
(13, 'Negeri Sembilan', 0),
(14, 'Melaka', 0);

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `branch_id` int(11) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`branch_id`, `branch_name`, `is_deleted`) VALUES
(1, 'Kuala Lumpur', 0),
(2, 'Selangor', 0),
(3, 'Penang', 0),
(4, 'Johor Bahru', 0),
(5, 'Kota Kinabalu', 0),
(6, 'Kuching', 0),
(7, 'Ipoh', 0),
(8, 'Melaka', 0),
(9, 'George Town', 0),
(10, 'Shah Alam', 0),
(11, 'Petaling Jaya', 0);

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `contact_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `date_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`contact_id`, `name`, `email`, `subject`, `message`, `date_updated`) VALUES
(1, 'Nur Inas', 'inas1@gmail.com', 'Inquiry about Delivery', 'Hello, I would like to inquire about delivery options.', '2024-05-11 10:00:00'),
(2, 'Alya Sorf', 'alyasorf@gmail.com', 'Report a Problem', 'Hi, I encountered an issue with my recent delivery. Can you please assist?', '2024-05-10 15:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `name`, `email`, `phone`, `address`, `gender`, `password`, `is_deleted`) VALUES
(1, 'Test User 1', 'test1@vexpress.com', '0123456789', '123, Jalan Bukit Jelutong, Shah Alam, Selangor, Malaysia', 1, '123', 0),
(2, 'Test User 2', 'test2@vexpress.com', '0134567891', '456, Kampung Hilir, Alor Setar, Kedah, Malaysia', 2, 'testuser2', 0),
(3, 'edlin', 'edlin123@gmail.com', '0118576424', '1234, rumah', 2, '123456', 0),
(4, 'ed', 'ed23@gmail.com', '01128576424', '123, house', 2, '123', 0);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emp_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `nric` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `branch_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_id`, `name`, `email`, `phone`, `nric`, `address`, `gender`, `password`, `is_deleted`, `branch_id`) VALUES
(1, '', 'admin', '', '', '', 0, '12345', 0, 0),
(2, 'EMP1', 'emp1@vexpress.com', '0123456789', '960101-10-1234', '789, Jalan Bukit Tinggi, Klang, Selangor, Malaysia', 1, 'emp1', 0, 1),
(3, 'EMP2', 'emp2@vexpress.com', '0123456789', '970202-11-5678', '321, Jalan Sungai Petani, Sungai Petani, Kedah, Malaysia', 1, 'emp2', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `price_table`
--

CREATE TABLE `price_table` (
  `price_id` int(11) NOT NULL,
  `start_area` int(11) NOT NULL,
  `end_area` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `date_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `price_table`
--

INSERT INTO `price_table` (`price_id`, `start_area`, `end_area`, `price`, `is_deleted`, `date_updated`) VALUES
(3, 1, 2, 4.00, 0, '2024-06-01 03:41:26'),
(4, 2, 1, 4.00, 0, '2024-06-01 03:41:38'),
(5, 1, 3, 7.00, 0, '2024-06-01 03:42:05'),
(6, 3, 1, 7.00, 0, '2024-06-01 03:42:13'),
(7, 1, 4, 4.00, 0, '2024-06-01 03:42:29'),
(8, 4, 1, 4.00, 0, '2024-06-01 03:42:49'),
(9, 1, 5, 6.00, 0, '2024-06-01 03:45:11'),
(10, 5, 1, 6.00, 0, '2024-06-01 03:45:29'),
(11, 1, 6, 7.00, 0, '2024-06-01 03:45:56'),
(12, 6, 1, 7.00, 0, '2024-06-01 03:46:08'),
(13, 1, 7, 5.00, 0, '2024-06-01 03:46:48'),
(14, 7, 1, 5.00, 0, '2024-06-01 04:10:32'),
(15, 1, 8, 5.00, 0, '2024-06-01 04:11:02'),
(16, 8, 1, 5.00, 0, '2024-06-01 04:11:08'),
(17, 1, 9, 4.00, 0, '2024-06-01 04:11:18'),
(18, 9, 1, 4.00, 0, '2024-06-01 04:11:44'),
(19, 1, 10, 8.00, 0, '2024-06-01 04:12:08'),
(20, 10, 1, 8.00, 0, '2024-06-01 04:12:19'),
(21, 1, 13, 4.00, 0, '2024-06-01 04:13:08'),
(22, 13, 1, 4.00, 0, '2024-06-01 04:13:15'),
(23, 1, 14, 5.00, 0, '2024-06-01 04:13:33'),
(24, 14, 1, 5.00, 0, '2024-06-01 04:13:40'),
(25, 1, 1, 4.00, 0, '2024-06-01 04:13:47'),
(26, 2, 2, 4.00, 0, '2024-06-06 00:16:27'),
(27, 2, 3, 8.00, 0, '2024-06-06 00:17:23'),
(28, 3, 2, 8.00, 0, '2024-06-06 00:17:37'),
(33, 2, 6, 8.00, 0, '2024-06-06 00:19:04'),
(34, 6, 2, 8.00, 0, '2024-06-06 00:19:18'),
(35, 2, 7, 6.00, 0, '2024-06-06 00:19:34'),
(36, 2, 4, 7.00, 0, '2024-06-06 00:20:48'),
(37, 4, 2, 7.00, 0, '2024-06-06 00:21:04'),
(38, 2, 5, 7.00, 0, '2024-06-06 00:21:17'),
(39, 5, 2, 7.00, 0, '2024-06-06 00:21:31'),
(40, 2, 8, 6.00, 0, '2024-06-06 00:21:49'),
(41, 7, 2, 6.00, 0, '2024-06-06 00:22:12'),
(42, 8, 2, 6.00, 0, '2024-06-06 00:22:25'),
(43, 2, 9, 6.00, 0, '2024-06-06 00:22:37'),
(44, 9, 2, 6.00, 0, '2024-06-06 00:22:50'),
(45, 2, 10, 8.00, 0, '2024-06-06 00:23:04'),
(46, 10, 2, 8.00, 0, '2024-06-06 00:23:18'),
(47, 2, 13, 7.00, 0, '2024-06-06 00:23:59'),
(48, 13, 2, 7.00, 0, '2024-06-06 00:24:10'),
(49, 2, 14, 7.00, 0, '2024-06-06 00:24:21'),
(50, 14, 2, 7.00, 0, '2024-06-06 00:24:33'),
(51, 3, 4, 7.00, 0, '2024-06-06 00:25:25'),
(52, 4, 3, 7.00, 0, '2024-06-06 00:25:59'),
(53, 3, 5, 6.00, 0, '2024-06-06 00:26:19'),
(54, 5, 3, 6.00, 0, '2024-06-06 00:26:32'),
(55, 3, 6, 6.00, 0, '2024-06-06 00:26:47'),
(56, 6, 3, 6.00, 0, '2024-06-06 00:26:59'),
(57, 3, 7, 8.00, 0, '2024-06-06 00:27:14'),
(58, 7, 3, 8.00, 0, '2024-06-06 00:27:28'),
(59, 3, 8, 8.00, 0, '2024-06-06 00:27:40'),
(60, 8, 3, 8.00, 0, '2024-06-06 00:27:52'),
(61, 3, 9, 8.00, 0, '2024-06-06 00:28:07'),
(62, 9, 3, 8.00, 0, '2024-06-06 00:28:20'),
(63, 3, 10, 6.00, 0, '2024-06-06 00:28:35'),
(64, 10, 3, 6.00, 0, '2024-06-06 00:28:48'),
(65, 3, 13, 7.00, 0, '2024-06-06 00:29:04'),
(66, 13, 3, 7.00, 0, '2024-06-06 00:29:16'),
(67, 3, 14, 7.00, 0, '2024-06-06 00:29:29'),
(68, 14, 3, 7.00, 0, '2024-06-06 00:29:42'),
(69, 3, 3, 4.00, 0, '2024-06-06 00:30:02'),
(70, 4, 5, 6.00, 0, '2024-06-06 00:30:21'),
(71, 5, 4, 6.00, 0, '2024-06-06 00:30:34'),
(72, 4, 6, 7.00, 0, '2024-06-06 00:30:55'),
(73, 6, 4, 7.00, 0, '2024-06-06 00:31:09'),
(74, 4, 7, 8.00, 0, '2024-06-06 00:31:24'),
(75, 7, 4, 8.00, 0, '2024-06-06 00:31:38'),
(76, 4, 8, 8.00, 0, '2024-06-06 00:31:51'),
(77, 8, 4, 8.00, 0, '2024-06-06 00:32:04'),
(78, 4, 9, 8.00, 0, '2024-06-06 00:32:17'),
(79, 9, 4, 8.00, 0, '2024-06-06 00:32:32'),
(80, 4, 10, 7.00, 0, '2024-06-06 00:32:52'),
(81, 10, 4, 7.00, 0, '2024-06-06 00:33:04'),
(82, 4, 13, 6.00, 0, '2024-06-06 00:33:23'),
(83, 13, 4, 6.00, 0, '2024-06-06 00:33:35'),
(84, 4, 14, 6.00, 0, '2024-06-06 00:34:03'),
(85, 14, 4, 6.00, 0, '2024-06-06 00:34:17'),
(86, 4, 4, 4.00, 0, '2024-06-06 00:34:31'),
(87, 5, 6, 6.00, 0, '2024-06-06 00:35:07'),
(88, 6, 5, 6.00, 0, '2024-06-06 00:35:22'),
(89, 5, 7, 8.00, 0, '2024-06-06 00:35:39'),
(90, 7, 5, 8.00, 0, '2024-06-06 00:35:53'),
(91, 5, 8, 8.00, 0, '2024-06-06 00:36:14'),
(92, 8, 5, 8.00, 0, '2024-06-06 00:36:29'),
(93, 5, 9, 8.00, 0, '2024-06-06 00:36:51'),
(94, 9, 5, 8.00, 0, '2024-06-06 00:37:07'),
(95, 5, 10, 6.00, 0, '2024-06-06 00:37:23'),
(96, 10, 5, 6.00, 0, '2024-06-06 00:37:38'),
(97, 5, 13, 7.00, 0, '2024-06-06 00:37:55'),
(98, 13, 5, 7.00, 0, '2024-06-06 00:38:12'),
(99, 5, 14, 7.00, 0, '2024-06-06 00:38:26'),
(100, 14, 5, 7.00, 0, '2024-06-06 00:38:40'),
(101, 5, 5, 4.00, 0, '2024-06-06 23:11:27'),
(102, 6, 7, 8.00, 0, '2024-06-06 23:11:41'),
(103, 7, 6, 8.00, 0, '2024-06-06 23:11:55'),
(104, 6, 8, 8.00, 0, '2024-06-06 23:12:09'),
(105, 8, 6, 8.00, 0, '2024-06-06 23:12:23'),
(106, 6, 9, 8.00, 0, '2024-06-06 23:12:38'),
(107, 9, 6, 8.00, 0, '2024-06-06 23:12:54'),
(108, 6, 10, 6.00, 0, '2024-06-06 23:13:09'),
(109, 10, 6, 6.00, 0, '2024-06-06 23:13:27'),
(110, 6, 13, 8.00, 0, '2024-06-06 23:17:02'),
(111, 13, 6, 8.00, 0, '2024-06-06 23:17:19'),
(112, 6, 14, 8.00, 0, '2024-06-06 23:17:37'),
(113, 14, 6, 8.00, 0, '2024-06-06 23:17:53'),
(114, 7, 8, 6.00, 0, '2024-06-06 23:18:49'),
(115, 8, 7, 6.00, 0, '2024-06-06 23:19:04'),
(116, 6, 6, 4.00, 0, '2024-06-06 23:19:45'),
(117, 7, 9, 6.00, 0, '2024-06-06 23:20:31'),
(118, 9, 7, 6.00, 0, '2024-06-06 23:20:45'),
(119, 7, 10, 8.00, 0, '2024-06-06 23:21:03'),
(120, 10, 7, 8.00, 0, '2024-06-06 23:21:42'),
(121, 7, 13, 7.00, 0, '2024-06-06 23:22:01'),
(122, 13, 7, 7.00, 0, '2024-06-06 23:22:15'),
(123, 7, 14, 7.00, 0, '2024-06-06 23:22:40'),
(124, 14, 7, 7.00, 0, '2024-06-06 23:22:55'),
(125, 7, 7, 4.00, 0, '2024-06-06 23:23:14'),
(126, 8, 9, 6.00, 0, '2024-06-06 23:23:32'),
(127, 9, 8, 6.00, 0, '2024-06-06 23:23:46'),
(128, 8, 10, 8.00, 0, '2024-06-06 23:24:12'),
(129, 10, 8, 8.00, 0, '2024-06-06 23:24:24'),
(130, 8, 13, 7.00, 0, '2024-06-06 23:24:42'),
(131, 13, 8, 7.00, 0, '2024-06-06 23:24:57'),
(132, 8, 14, 7.00, 0, '2024-06-06 23:25:16'),
(133, 14, 8, 7.00, 0, '2024-06-06 23:25:30'),
(134, 8, 8, 4.00, 0, '2024-06-06 23:25:49'),
(135, 9, 10, 8.00, 0, '2024-06-06 23:26:03'),
(136, 10, 9, 8.00, 0, '2024-06-06 23:26:18'),
(137, 9, 13, 7.00, 0, '2024-06-06 23:26:36'),
(138, 13, 9, 7.00, 0, '2024-06-06 23:26:51'),
(139, 9, 14, 7.00, 0, '2024-06-06 23:27:08'),
(140, 14, 9, 7.00, 0, '2024-06-06 23:27:22'),
(141, 9, 9, 4.00, 0, '2024-06-06 23:27:36'),
(142, 10, 13, 8.00, 0, '2024-06-06 23:27:54'),
(143, 13, 10, 8.00, 0, '2024-06-06 23:28:08'),
(144, 10, 14, 8.00, 0, '2024-06-06 23:28:27'),
(145, 14, 10, 8.00, 0, '2024-06-06 23:28:41'),
(146, 10, 10, 4.00, 0, '2024-06-06 23:28:58'),
(147, 13, 14, 6.00, 0, '2024-06-06 23:29:20'),
(148, 14, 13, 6.00, 0, '2024-06-06 23:29:36'),
(149, 13, 13, 4.00, 0, '2024-06-06 23:29:51'),
(150, 14, 14, 4.00, 0, '2024-06-06 23:30:04');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `tracking_id` int(11) NOT NULL,
  `customer_id` int(255) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `sender_phone` varchar(20) NOT NULL,
  `sender_address` mediumtext NOT NULL,
  `weight` decimal(10,2) NOT NULL,
  `send_location` int(255) NOT NULL,
  `end_location` int(255) NOT NULL,
  `total_fee` decimal(10,2) NOT NULL,
  `rec_phone` varchar(20) NOT NULL,
  `rec_address` mediumtext NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `date_updated` datetime NOT NULL,
  `tracking_status` int(10) NOT NULL,
  `rec_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`tracking_id`, `customer_id`, `sender_name`, `sender_phone`, `sender_address`, `weight`, `send_location`, `end_location`, `total_fee`, `rec_phone`, `rec_address`, `is_deleted`, `date_updated`, `tracking_status`, `rec_name`) VALUES
(1000001, 1, 'Test User 1', '0123456789', '123, Jalan Bukit Jelutong, Shah Alam, Selangor, Malaysia', 2.00, 2, 1, 7.20, '0121234567', 'No 235, Jalan Hartamas, Kuala Lumpur', 0, '2023-01-15 11:14:09', 1, 'Receiver 1'),
(1000004, 3, 'test', '0123456789', '123, home', 2.00, 7, 9, 12.00, '0123456789', 'test jap', 0, '2024-06-18 20:59:41', 2, 'Syaza');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `header_image` varchar(255) NOT NULL,
  `header_title` varchar(255) NOT NULL,
  `header_desc` varchar(1000) NOT NULL,
  `about_title` varchar(255) NOT NULL,
  `about_desc` varchar(1000) NOT NULL,
  `company_phone` varchar(255) NOT NULL,
  `company_email` varchar(255) NOT NULL,
  `company_address` varchar(255) NOT NULL,
  `sub_image` varchar(255) NOT NULL,
  `about_image` varchar(255) NOT NULL,
  `link_facebook` varchar(255) NOT NULL,
  `link_twitter` varchar(255) NOT NULL,
  `link_instagram` varchar(255) NOT NULL,
  `background_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`header_image`, `header_title`, `header_desc`, `about_title`, `about_desc`, `company_phone`, `company_email`, `company_address`, `sub_image`, `about_image`, `link_facebook`, `link_twitter`, `link_instagram`, `background_image`) VALUES
('Sub_Header.png', 'Track Your Parcel', 'Efficiency in Motion: Your Express Delivery Partner', 'About Us', 'Welcome to Velocity Express – your ultimate solution for hassle-free parcel delivery! Say goodbye to long queues and inconvenient delivery centers. With us, you can schedule pickups from the comfort of your couch with just a few clicks. \r\nExperience the ease and speed of modern delivery. Join Velocity Express today and let us take the stress out of sending packages!', '+60 3-1234 5678', 'connect@vexpress.com', 'No. 25, Jalan Hartamas, 50480 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 'First_Background.jpeg', 'About.png', 'https://www.facebook.com/', 'https://www.twitter.com/', 'https://www.instagram.com/', 'Background.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`area_id`);

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`branch_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `price_table`
--
ALTER TABLE `price_table`
  ADD PRIMARY KEY (`price_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`tracking_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `area`
--
ALTER TABLE `area`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `price_table`
--
ALTER TABLE `price_table`
  MODIFY `price_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `tracking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000005;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
