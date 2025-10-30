-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2025 at 01:35 PM
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
-- Database: `ccms`
--

-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

CREATE TABLE `complaint` (
  `complaint_id` int(11) NOT NULL,
  `student_id` int(255) NOT NULL,
  `dormitory_id` int(255) NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `complaint_title` varchar(255) NOT NULL,
  `complaint_description` mediumtext NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `complaint_category` enum('plumbing','electrical','furniture','cleaning','internet','security','pest_control','heating_cooling','other') NOT NULL,
  `urgency_level` enum('low','medium','high','critical') NOT NULL,
  `complaint_status` int(10) NOT NULL DEFAULT 1,
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

INSERT INTO `complaint` (`complaint_id`, `student_id`, `dormitory_id`, `room_number`, `complaint_title`, `complaint_description`, `photo`, `complaint_category`, `urgency_level`, `complaint_status`, `assigned_staff_id`, `resolution_notes`, `is_deleted`, `created_at`, `date_updated`, `date_resolved`) VALUES
(1000001, 1, 1, 'A-101', 'Leaking Faucet in Bathroom', 'The faucet in my bathroom is constantly dripping and wasting water. It has been going on for 2 days now.', 'server/uploads/complaints/faucet_leak_a101.jpg', 'plumbing', 'medium', 2, 2, 'Maintenance staff has been assigned to fix the faucet.', 0, '2025-10-24 11:59:57', '2024-12-01 10:14:09', NULL),
(1000002, 2, 4, 'B-205', 'WiFi Connection Issues', 'My room has very poor WiFi signal. Cannot connect to online classes properly.', NULL, 'internet', 'high', 1, NULL, NULL, 0, '2025-10-24 11:59:57', '2024-12-01 14:30:22', NULL),
(1000003, 3, 5, 'C-312', 'Broken Study Table', 'The study table in my room has a broken leg and is unstable. Need urgent repair.', NULL, 'furniture', 'medium', 4, 3, 'Table has been repaired and reinforced. Student confirmed it is now stable.', 0, '2025-10-24 11:59:57', '2024-12-01 09:15:45', '2024-12-01 16:20:33'),
(1000004, 4, 1, 'A-108', 'Air Conditioning Not Working', 'The air conditioning unit in my room is not cooling properly. Room temperature is very uncomfortable.', NULL, 'heating_cooling', 'high', 3, 2, 'Technician has inspected the unit. Waiting for replacement parts to arrive.', 1, '2025-10-24 11:59:57', '2024-12-01 11:45:18', NULL);

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
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `date_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `name`, `email`, `subject`, `message`, `date_updated`) VALUES
(1, 'Ali bin Ahmad', 'ali.ahmad@student.college.edu', 'Great Complaint System', 'The new complaint system is very efficient and easy to use. Thank you for implementing this!', '2024-12-01 10:00:00'),
(2, 'Siti binti Rahman', 'siti.rahman@student.college.edu', 'Suggestion for Improvement', 'It would be great if we could upload photos with our complaints to show the issues better.', '2024-12-01 15:30:00');

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
('college_header.png', 'College Complaint Management System', 'Your Voice Matters: Efficient Complaint Resolution for Better Campus Living', 'About Our System', 'Welcome to our College Complaint Management System – designed to make reporting and resolving campus issues simple and efficient. Whether it\'s maintenance problems, IT issues, or general concerns, our system ensures your voice is heard and your complaints are addressed promptly. \nExperience streamlined complaint resolution and contribute to making our campus a better place for everyone. Your satisfaction is our priority!', '+60 3-1234 5678', 'complaints@college.edu', 'No. 25, Jalan Campus, 50480 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 'campus_background.jpeg', 'about_campus.png', 'https://www.facebook.com/ourcollege', 'https://www.twitter.com/ourcollege', 'https://www.instagram.com/ourcollege', 'campus_background.jpg');

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
  `student_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `password` varchar(255) NOT NULL,
  `student_id_number` varchar(50) NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `name`, `email`, `phone`, `address`, `gender`, `password`, `student_id_number`, `room_number`, `is_deleted`) VALUES
(1, 'Ali bin Ahmad', 'ali.ahmad@student.college.edu', '0123456789', 'Dormitory A, Room 101, College Campus', 1, '123', 'STU2024001', 'A-101', 0),
(2, 'Siti binti Rahman', 'siti.rahman@student.college.edu', '0134567891', 'Dormitory B, Room 205, College Campus', 2, 'student123', 'STU2024002', 'B-205', 0),
(3, 'Wei Chen', 'wei.chen@student.college.edu', '0118576424', 'Dormitory C, Room 312, College Campus', 1, '123456', 'STU2024003', 'C-312', 0),
(4, 'Aina Sofea', 'aina.sofea@student.college.edu', '01128576424', 'Dormitory A, Room 108, College Campus', 2, '123', 'STU2024004', 'A-108', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `complaint`
--
ALTER TABLE `complaint`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `dormitory_id` (`dormitory_id`),
  ADD KEY `assigned_staff_id` (`assigned_staff_id`);

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
  ADD PRIMARY KEY (`feedback_id`);

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
  ADD UNIQUE KEY `student_id_number` (`student_id_number`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaint`
--
ALTER TABLE `complaint`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000005;

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
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaint`
--
ALTER TABLE `complaint`
  ADD CONSTRAINT `complaint_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`),
  ADD CONSTRAINT `complaint_ibfk_2` FOREIGN KEY (`dormitory_id`) REFERENCES `dormitory` (`dormitory_id`),
  ADD CONSTRAINT `complaint_ibfk_3` FOREIGN KEY (`assigned_staff_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
