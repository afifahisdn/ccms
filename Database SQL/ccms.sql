-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2025 at 11:57 PM
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
-- Database: `ccms1`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `is_deleted`) VALUES
(1, 'plumbing', 0),
(2, 'electrical', 0),
(3, 'furniture', 0),
(4, 'cleaning', 0),
(5, 'internet', 0),
(6, 'security', 0),
(7, 'other', 0);

-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

CREATE TABLE `complaint` (
  `complaint_id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `dormitory_id` int(255) NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `complaint_title` varchar(255) NOT NULL,
  `complaint_description` mediumtext NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `urgency_level` enum('low','medium','high','critical') NOT NULL,
  `complaint_status` enum('Open','In Progress','Resolved','Closed','Withdrawn') NOT NULL DEFAULT 'Open',
  `assigned_staff_id` int(11) DEFAULT NULL,
  `resolution_notes` text DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL,
  `date_resolved` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaint`
--

INSERT INTO `complaint` (`complaint_id`, `student_id`, `category_id`, `dormitory_id`, `room_number`, `complaint_title`, `complaint_description`, `photo`, `urgency_level`, `complaint_status`, `assigned_staff_id`, `resolution_notes`, `is_deleted`, `created_at`, `date_updated`, `date_resolved`) VALUES
(1000001, 'STU2024001', 1, 1, 'A-101', 'Leaking Faucet in Bathroom', 'The faucet in my bathroom is constantly dripping and wasting water. It has been going on for 2 days now.', 'server/uploads/complaints/faucet_leak_a101.jpg', 'medium', 'Open', 2, 'Maintenance staff has been assigned to fix the faucet.', 0, '2025-10-24 11:59:57', '2025-11-15 05:23:31', NULL),
(1000002, 'STU2024002', 5, 4, 'B-205', 'WiFi Connection Issues', 'My room has very poor WiFi signal. Cannot connect to online classes properly.', NULL, 'high', 'Closed', NULL, NULL, 0, '2025-10-24 11:59:57', '2025-11-15 02:15:43', '2025-11-15 02:15:43'),
(1000003, 'STU2024003', 3, 5, 'C-312', 'Broken Study Table', 'The study table in my room has a broken leg and is unstable. Need urgent repair.', NULL, 'medium', 'Closed', 3, 'Table has been repaired and reinforced. Student confirmed it is now stable.', 0, '2025-10-24 11:59:57', '2024-12-01 09:15:45', '2024-12-01 16:20:33'),
(1000006, 'STU2024004', 7, 6, 'A-108', 'ceiling pecah', 'masuk hujannnnn', NULL, 'medium', 'In Progress', NULL, NULL, 0, '2025-11-13 22:44:28', '2025-11-15 05:21:44', NULL),
(1000007, 'STU2024005', 2, 2, 'A-102', 'Test', 'test', NULL, 'medium', 'Open', NULL, NULL, 0, '2025-11-15 06:08:32', '2025-11-15 06:08:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `department_type` enum('maintenance','it','cleaning','security','administration') NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_name`, `department_type`, `is_deleted`) VALUES
(1, 'Maintenance Department', 'maintenance', 0),
(2, 'IT Support Department', 'it', 0),
(3, 'Cleaning Services', 'cleaning', 0),
(4, 'Campus Security', 'security', 0),
(5, 'Student Affairs', 'administration', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dormitory`
--

CREATE TABLE `dormitory` (
  `dormitory_id` int(11) NOT NULL,
  `dormitory_name` varchar(255) NOT NULL,
  `dormitory_code` varchar(10) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dormitory`
--

INSERT INTO `dormitory` (`dormitory_id`, `dormitory_name`, `dormitory_code`, `is_deleted`) VALUES
(1, 'Dormitory A - North Wing', 'DORM-A', 0),
(2, 'Dormitory A - South Wing', 'DORM-AS', 0),
(3, 'Dormitory B - East Wing', 'DORM-BE', 0),
(4, 'Dormitory B - West Wing', 'DORM-BW', 0),
(5, 'Dormitory C - Main Building', 'DORM-C', 0),
(6, 'Dormitory D - New Block', 'DORM-D', 0);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `date_updated` datetime NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `student_id`, `subject`, `message`, `date_updated`, `is_deleted`) VALUES
(1, 'STU2024005', 'Great Complaint System', 'The new complaint system is very efficient and easy to use. Thank you for implementing this!', '2024-12-01 10:00:00', 0),
(2, 'STU2024007', 'Suggestion for Improvement', 'It would be great if we could upload photos with our complaints to show the issues better.', '2024-12-01 15:30:00', 0),
(3, 'STU2024005', 'test', 'test', '2025-11-15 06:31:22', 0);

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
('server/uploads/settings/header_image.png', 'College Complaint Management System', 'Your Voice Matters: Efficient Complaint Resolution for Better Campus Living', 'About Our System', 'Welcome to our College Complaint Management System – designed to make reporting and resolving campus issues simple and efficient. Whether it\'s maintenance problems, IT issues, or general concerns, our system ensures your voice is heard and your complaints are addressed promptly. \nExperience streamlined complaint resolution and contribute to making our campus a better place for everyone. Your satisfaction is our priority!', '+60 3-1234 5678', 'complaints@college.edu', 'No. 25, Jalan Campus, 50480 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 'server/uploads/settings/sub_image.jpg', 'server/uploads/settings/about_image.png', 'https://www.facebook.com/ourcollege', 'https://www.twitter.com/ourcollege', 'https://www.instagram.com/ourcollege', 'campus_background.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `nric` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `department_id` int(255) NOT NULL,
  `staff_role` enum('admin','staff') NOT NULL DEFAULT 'staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `name`, `email`, `phone`, `nric`, `address`, `gender`, `password`, `is_deleted`, `department_id`, `staff_role`) VALUES
(1, 'System Administrator', 'admin', '0123456789', '800101-01-0001', 'College Administration Building', 1, '12345', 0, 5, 'admin'),
(2, 'Ahmad bin Ismail', 'ahmad.ismail@college.edu', '0123456789', '850505-10-1234', 'Staff Quarters, Block A', 1, 'staff123', 0, 1, 'staff'),
(3, 'Lim Mei Ling', 'mei.ling@college.edu', '0134567890', '880202-11-5678', 'Staff Quarters, Block B', 2, 'staff123', 0, 1, 'staff'),
(4, 'IT Support Team', 'itsupport@college.edu', '0145678901', '900303-12-9012', 'IT Building, Room 101', 1, 'itstaff123', 0, 2, 'staff');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `password` varchar(255) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`name`, `email`, `phone`, `address`, `gender`, `password`, `student_id`, `room_number`, `is_deleted`) VALUES
('Ali bin Ahmad', 'ali.ahmad@college.edu', '0123456789', 'Dormitory A, Room 101, College Campus', 1, '123', 'STU2024001', 'A-101', 0),
('Siti binti Rahman', 'siti.rahman@college.edu', '0134567891', 'Dormitory B, Room 205, College Campus', 2, 'student123', 'STU2024002', 'B-205', 0),
('Wei Chen', 'wei.chen@college.edu', '0118576424', 'Dormitory C, Room 312, College Campus', 1, '123456', 'STU2024003', 'C-312', 0),
('Aina Sofea', 'aina.sofea@college.edu', '01128576424', 'Dormitory A, Room 108, College Campus', 2, '123', 'STU2024004', 'A-108', 0),
('Alya Amira', 'test@gmail.com', '0127213527', 'test', 2, '123', 'STU2024005', 'A-102', 0),
('Irdina', 'irdina@gmail.com', '0127213527', 'test', 2, 'test', 'STU2024007', 'A-102', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name_unique` (`category_name`);

--
-- Indexes for table `complaint`
--
ALTER TABLE `complaint`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `dormitory_id` (`dormitory_id`),
  ADD KEY `assigned_staff_id` (`assigned_staff_id`),
  ADD KEY `fk_complaint_category` (`category_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `dormitory`
--
ALTER TABLE `dormitory`
  ADD PRIMARY KEY (`dormitory_id`),
  ADD UNIQUE KEY `dormitory_code` (`dormitory_code`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `fk_feedback_student` (`student_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `complaint`
--
ALTER TABLE `complaint`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000008;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dormitory`
--
ALTER TABLE `dormitory`
  MODIFY `dormitory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaint`
--
ALTER TABLE `complaint`
  ADD CONSTRAINT `complaint_ibfk_2` FOREIGN KEY (`dormitory_id`) REFERENCES `dormitory` (`dormitory_id`),
  ADD CONSTRAINT `complaint_ibfk_3` FOREIGN KEY (`assigned_staff_id`) REFERENCES `staff` (`staff_id`),
  ADD CONSTRAINT `fk_complaint_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `fk_complaint_student` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_feedback_student` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
