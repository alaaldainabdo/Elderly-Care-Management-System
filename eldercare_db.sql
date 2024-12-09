-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2024 at 05:46 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eldercare_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `elderly_profiles`
--

CREATE TABLE `elderly_profiles` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` date DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `emergency_contact` varchar(100) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `health_condition` text DEFAULT NULL,
  `current_medications` text DEFAULT NULL,
  `additional_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `alert_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `elderly_profiles`
--

INSERT INTO `elderly_profiles` (`id`, `name`, `age`, `medical_history`, `emergency_contact`, `created_by`, `full_name`, `gender`, `address`, `phone`, `health_condition`, `current_medications`, `additional_notes`, `created_at`, `alert_id`) VALUES
(24, 'ala aldain', '2024-10-08', 'Nice>>..', '4554354354', NULL, '', 'male', 'sanaa', '4545454523', 'Good!', 'No thing', 'No thing', '2024-10-16 18:28:44', NULL),
(25, 'Abdullah hamid ghaleb hasan', '0000-00-00', 'Arthritis', '5425424545', NULL, 'Alice Marie Johnson', 'Male', '789 Oak St, Anytown', '555-8765', 'Controlled', 'Ibuprofen', '', '2024-10-17 22:36:46', NULL),
(28, 'ضياء', '0000-00-00', 'Arthritis', '45245245454', NULL, 'Alice Marie Johnson', 'Male', '789 Oak St, Anytown', '555-8765', 'Controlled', 'Ibuprofen', '', '2024-10-18 10:27:58', NULL),
(29, 'ali ahmed', '0000-00-00', 'Nice>>..', '4566788787', NULL, '', 'male', 'sanaa', '8542452456', 'Good!', 'No thing', 'No thing', '2024-11-14 12:16:38', NULL),
(30, 'allli ahmed', '0000-00-00', 'good', '45523545454', 49, '', 'male', 'sanaa', '4523542452', 'good', 'good', 'good', '2024-11-17 01:58:41', NULL),
(33, ' ahmed1111', '1995-02-17', 'dfghfdgh', '452452', NULL, '', 'male', 'sanaa', '454242', 'trhfdh', 'fdgh', 'dfghdfgh', '2024-11-17 02:15:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `emergency_alerts`
--

CREATE TABLE `emergency_alerts` (
  `id` int(11) NOT NULL,
  `elderly_id` int(11) NOT NULL,
  `alert_message` text NOT NULL,
  `alert_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `sent_to` int(11) DEFAULT NULL,
  `is_opened` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_alerts`
--

INSERT INTO `emergency_alerts` (`id`, `elderly_id`, `alert_message`, `alert_date`, `sent_to`, `is_opened`) VALUES
(95, 28, 'dsfsad', '2024-10-15 10:36:00', NULL, 0),
(96, 28, 'asdfsdf', '2024-10-11 11:40:00', NULL, 0),
(120, 28, 'عليك بسرعة الحضور الى المركز للضرورة  !!!!!! مركز الطوارئ :elderly_cear	', '2024-10-18 15:39:00', NULL, 0),
(124, 24, 'You must come to the center quickly, it is urgent!!! Emergency center', '2024-10-20 23:00:00', NULL, 0),
(128, 24, 'go to the center', '2024-12-05 21:11:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employeeID` int(11) NOT NULL,
  `fName` varchar(50) NOT NULL,
  `lName` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `email` varchar(100) NOT NULL,
  `DOB` date NOT NULL,
  `phone` varchar(15) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `hire_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employeeID`, `fName`, `lName`, `role`, `salary`, `email`, `DOB`, `phone`, `user_id`, `hire_date`) VALUES
(10, 'ali', 'ahmed', 'admin', '313.00', 'ali@gmail.com', '2024-10-26', '555-8765', NULL, '2024-10-13'),
(11, 'ALA', 'MAROAN', 'admin', '3000.00', 'admain@gmail.com', '2024-10-20', '075378XX', 44, '2024-10-20'),
(12, 'ALA', 'AHMAD', 'admin', '4000.00', 'email@gmail.com', '2024-10-20', '5378XXXXX', NULL, '2024-10-20'),
(13, 'ALA', 'ALI', 'doctor', '32131.00', 'doctor@example.com', '2024-10-10', '075753XXXXX', NULL, '2024-11-01'),
(14, 'mohamad', 'MOHAMAD', 'admin', '31.00', 'ala34@gmail.com', '2005-03-31', '08578XXXX', NULL, '2024-02-11'),
(16, 'abdo ', 'ahmed', 'admin', '2000.00', 'alaa@gmail.com', '2024-12-05', '305557025', 16, '2024-12-10'),
(17, 'ala aldain', 'ALI', 'admin', '3000.00', 'ala@gmail.com', '1999-01-05', '24246XXX', 52, '2024-12-05');

-- --------------------------------------------------------

--
-- Table structure for table `health_records`
--

CREATE TABLE `health_records` (
  `id` int(11) NOT NULL,
  `elderly_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `vital_signs` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `health_condition` text DEFAULT NULL,
  `prescriptions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `health_records`
--

INSERT INTO `health_records` (`id`, `elderly_id`, `date`, `vital_signs`, `notes`, `updated_by`, `health_condition`, `prescriptions`) VALUES
(19, 28, '2024-12-05', 'good', '', 16, 'good', 'no'),
(20, 24, '2024-12-05', 'good', '', 52, 'good', 'there is no ');

-- --------------------------------------------------------

--
-- Table structure for table `medication_schedule`
--

CREATE TABLE `medication_schedule` (
  `id` int(11) NOT NULL,
  `elderly_id` int(11) NOT NULL,
  `after_morning` varchar(100) NOT NULL,
  `after_lunch` varchar(100) NOT NULL,
  `after_dinner` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medication_schedule`
--

INSERT INTO `medication_schedule` (`id`, `elderly_id`, `after_morning`, `after_lunch`, `after_dinner`) VALUES
(28, 24, 'aaa', 'bbbb', 'sss'),
(29, 24, 'مهداء', 'اسبرين', 'مهداء');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `description` text NOT NULL,
  `status` enum('pending','in_progress','completed') NOT NULL,
  `due_date` date NOT NULL,
  `employee_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `description`, `status`, `due_date`, `employee_id`) VALUES
(2, 'Check medication adherence for Jane Smith', 'pending', '2024-10-23', NULL),
(4, 'Check medication adherence for Jane Smith', 'pending', '2024-10-10', 10),
(12, 'Check medication adherence for Jane Smith', 'pending', '2024-10-10', 10),
(25, 'check the medicine for the elders >>>', 'pending', '2024-12-05', 16),
(26, 'check the medicine for the elders >>>', 'pending', '2024-12-06', 11),
(27, 'go to the center', 'pending', '2024-12-05', 11);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('caregiver','doctor','family','supervisor','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(3, 'Caregiver User', 'caregiver@example.com', '$2y$10$QJjC6/lBj4QfqlZPmkjT6ePV8irKh/OPB6LpCSRZUiVXq0Kp/3iHq', 'caregiver'),
(4, 'Family User', 'family@example.com', 'hashed_password', 'family'),
(16, 'ala aldain', 'alaa@gmail.com', '$2y$10$s2pUmJg2Bg4MTYGWcIpoYeO0WpEqq/PJ7lCgJfTy/3v.B2pDd.NLS', 'admin'),
(44, 'Ali ABDO', 'admin1234@gmail.com', '$2y$10$2lqGEXRPNgoNddndXP0tfej9AGE.ItU1adxBSko9KDMGUq08P2e5q', 'caregiver'),
(49, 'Abdullah  hasan', 'aa1@outlook.com', '$2y$10$nesg0bRIEMU6QIlhHlEI2.FJWOBQOnGj/oEYxFQIZPXTsMPNry3mS', 'family'),
(50, 'Abdullah  hasan', 'aa11@outlook.com', '$2y$10$/Kgo2g2203xQ2R0v27iGd.1E0xnpKUnzY3e4i8fH321sChIyXKBKO', 'family'),
(52, 'ala aldain', 'ala@gmail.com', '$2y$10$q3HpvQriOo3tdvvLlPb5lerYcTcSDZM5dLHn.6n2FlgtoPHsdM/mC', 'admin'),
(53, 'Mohammed', 'mohammed@gmail.com', '$2y$10$rC3vtVnxa9x9Vk7ujvmKP.nTns1llobklCCfrR2Vm8RPl1w5Uxo8a', 'doctor');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `elderly_profiles`
--
ALTER TABLE `elderly_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `fk_alert` (`alert_id`);

--
-- Indexes for table `emergency_alerts`
--
ALTER TABLE `emergency_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sent_to` (`sent_to`),
  ADD KEY `emergency_alerts_ibfk_1` (`elderly_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employeeID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `unique_user_employee` (`user_id`);

--
-- Indexes for table `health_records`
--
ALTER TABLE `health_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `elderly_id` (`elderly_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `medication_schedule`
--
ALTER TABLE `medication_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `elderly_id` (`elderly_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `elderly_profiles`
--
ALTER TABLE `elderly_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `emergency_alerts`
--
ALTER TABLE `emergency_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `health_records`
--
ALTER TABLE `health_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `medication_schedule`
--
ALTER TABLE `medication_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `elderly_profiles`
--
ALTER TABLE `elderly_profiles`
  ADD CONSTRAINT `elderly_profiles_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_alert` FOREIGN KEY (`alert_id`) REFERENCES `emergency_alerts` (`id`);

--
-- Constraints for table `emergency_alerts`
--
ALTER TABLE `emergency_alerts`
  ADD CONSTRAINT `emergency_alerts_ibfk_1` FOREIGN KEY (`elderly_id`) REFERENCES `elderly_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emergency_alerts_ibfk_2` FOREIGN KEY (`sent_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_elderly_profiles` FOREIGN KEY (`elderly_id`) REFERENCES `elderly_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `health_records`
--
ALTER TABLE `health_records`
  ADD CONSTRAINT `health_records_ibfk_1` FOREIGN KEY (`elderly_id`) REFERENCES `elderly_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `health_records_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medication_schedule`
--
ALTER TABLE `medication_schedule`
  ADD CONSTRAINT `medication_schedule_ibfk_1` FOREIGN KEY (`elderly_id`) REFERENCES `elderly_profiles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employeeID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
