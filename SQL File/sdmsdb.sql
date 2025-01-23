-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 06, 2024 at 02:18 AM
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
-- Database: `sdmsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `deadline` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `topic` text DEFAULT NULL,
  `resources` varchar(255) DEFAULT NULL,
  `format` varchar(50) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `work_results` varchar(255) DEFAULT NULL,
  `work_is_done` tinyint(1) DEFAULT 0,
  `priority_level` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `email`, `deadline`, `time`, `pages`, `subject`, `topic`, `resources`, `format`, `instructions`, `file`, `work_results`, `work_is_done`, `priority_level`) VALUES
(8, '', '2024-10-25', '16:08:00', 133, 'Sociology', 'Veniam et deserunt ', '334', 'MLA', 'Corrupti debitis in', '', NULL, 0, 'Very Urgent'),
(9, '', '2024-11-07', '16:18:00', 42, 'Mathematics', 'Consequatur autem ea', '844', 'APA', 'Magna officiis omnis', '', NULL, 0, 'Normal'),
(10, 'degotujobi@mailinator.com', '2024-10-31', '00:49:00', 11, 'Psychology', 'Fugiat labore nulla ', '842', 'APA', 'Veniam sed harum ad', '', NULL, 0, 'Normal');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `class` varchar(255) DEFAULT NULL,
  `studentno` varchar(255) NOT NULL,
  `studentName` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `stream` varchar(255) DEFAULT NULL,
  `gender` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contactno` int(10) DEFAULT NULL,
  `nextphone` int(10) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `village` varchar(255) DEFAULT NULL,
  `studentImage` varchar(255) DEFAULT NULL,
  `parentName` varchar(255) DEFAULT NULL,
  `relation` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT current_timestamp(),
  `updationDate` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `class`, `studentno`, `studentName`, `age`, `stream`, `gender`, `email`, `contactno`, `nextphone`, `country`, `district`, `state`, `village`, `studentImage`, `parentName`, `relation`, `occupation`, `postingDate`, `updationDate`) VALUES
(10, 'S3', 'U0001', 'Betty Gloria', 15, 'West', 'Female', 'gloria@gmail.com', 770546590, 757537271, 'United States', 'Kenburg', 'United State', 'Andrea', 'face5.jpg', 'Ketty Perry', 'Mother', 'Doctor', '2021-01-19 13:22:01', NULL),
(16, 'S4', 'U0002', 'Harry Morgan', 16, 'East', 'Male', 'morgan@gmail.com', 770546590, 775456789, 'Chaina', 'Hongkong', 'Kongoh', 'Kongberry', 'face22.jpg', 'Agaba James', 'Father', 'Lecture', '2021-05-05 19:58:04', NULL),
(20, 'S6', 'U0003', 'George Williams ', 20, 'West', 'Male', 'williams@gmail.com', 770546590, 770546598, 'Uganda', 'Kampala', 'Kampala', 'Muyenga', 'face3.jpg', 'Toney  Rushford', 'Father', 'Engineer', '2021-07-06 12:58:19', NULL),
(21, 'S4', 'U004', 'Mickie Dorothy ', 17, 'West', 'Female', 'gerald@gmail.com', 770546590, 757537271, 'Uganda', 'Kampala', 'Kampala', 'Muyenga', 'face26.jpg', 'Arinaitwe Gerald', 'Father', 'Engineer', '2021-07-20 20:37:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sex` varchar(255) NOT NULL,
  `permission` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile` int(11) NOT NULL,
  `userimage` varchar(255) NOT NULL DEFAULT 'but.jpg',
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`id`, `name`, `lastname`, `username`, `email`, `sex`, `permission`, `password`, `mobile`, `userimage`, `status`) VALUES
(15, 'John', 'Smith', 'admin', 'john@gmail.com', 'Male', 'Super User', '81dc9bdb52d04dc20036dbd8313ed055', 770546590, 'Screenshot from 2024-09-11 00-58-05.png', 1),
(20, 'Rihanna', 'Gloria ', 'gloria', 'gloria@gmail.com', 'Female', 'Admin', '81dc9bdb52d04dc20036dbd8313ed055', 770546590, 'face23.jpg', 1),
(21, 'Eliana Price', 'Washington', 'sipegigaf', 'tafelu@mailinator.com', 'Male', 'User', 'f3ed11bbdb94fd9ebdefbaf646ab94d3', 24343433, 'default.jpg', 1),
(22, 'Jerome Moreno', 'Cross', 'velix', 'xevif@mailinator.com', 'Female', 'User', 'f3ed11bbdb94fd9ebdefbaf646ab94d3', 32, 'default.jpg', 1),
(23, 'jim', 'jim', 'jim', 'jim@jim.com', 'Male', 'User', '5e027396789a18c37aeda616e3d7991b', 87654, 'default.jpg', 1),
(24, 'Timothy Duncan', 'Savage', 'maxicigam', 'nilijop@mailinator.com', 'Female', 'User', 'f3ed11bbdb94fd9ebdefbaf646ab94d3', 28, 'default.jpg', 1),
(25, 'Zorita Crosby', 'Herrera', 'megapizar', 'rizore@mailinator.com', 'Female', 'User', 'f3ed11bbdb94fd9ebdefbaf646ab94d3', 88, 'default.jpg', 1),
(26, 'Rinah Davidson', 'Shepard', 'dyzisebe', 'tymaliv@mailinator.com', 'Female', 'User', 'f3ed11bbdb94fd9ebdefbaf646ab94d3', 93, 'default.jpg', 1),
(27, 'Preston Chaney', 'Rowe', 'tecywug', 'remetu@mailinator.com', 'Male', 'User', 'f3ed11bbdb94fd9ebdefbaf646ab94d3', 72, 'default.jpg', 1),
(28, 'Alexis Tucker', 'Terrell', 'cyfujyq', 'weguz@mailinator.com', 'Male', 'User', 'f3ed11bbdb94fd9ebdefbaf646ab94d3', 87, 'default.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE `userlog` (
  `id` int(11) NOT NULL,
  `username` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `lastname` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `userEmail` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `userip` binary(16) DEFAULT NULL,
  `loginTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `logout` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userlog`
--

INSERT INTO `userlog` (`id`, `username`, `name`, `lastname`, `userEmail`, `userip`, `loginTime`, `logout`, `status`) VALUES
(204, 'admin', 'John', 'Smith', 'john@gmail.com', 0x3a3a3100000000000000000000000000, '2021-07-20 20:55:14', '20-07-2021 11:55:31 PM', 1),
(205, 'gloria', 'Potential Hacker', NULL, 'Not registered in system', 0x3a3a3100000000000000000000000000, '2021-07-20 20:55:45', NULL, 0),
(207, 'gloria', 'Rihanna', 'Gloria ', 'gloria@gmail.com', 0x3a3a3100000000000000000000000000, '2021-07-20 20:57:48', '20-07-2021 11:57:52 PM', 1),
(208, 'mike', 'Potential Hacker', NULL, 'Not registered in system', 0x3a3a3100000000000000000000000000, '2021-07-20 20:58:17', NULL, 0),
(209, 'admin', 'John', 'Smith', 'john@gmail.com', 0x3a3a3100000000000000000000000000, '2021-07-20 20:58:26', '20-07-2021 11:59:20 PM', 1),
(210, 'admin', 'John', 'Smith', 'john@gmail.com', 0x3a3a3100000000000000000000000000, '2024-10-25 11:47:00', '25-10-2024 02:49:19 PM', 1),
(211, 'admin@example.com', 'Potential Hacker', NULL, 'Not registered in system', 0x3a3a3100000000000000000000000000, '2024-10-25 12:15:23', NULL, 0),
(212, 'admin', 'John', 'Smith', 'john@gmail.com', 0x3a3a3100000000000000000000000000, '2024-10-25 13:57:59', '25-10-2024 04:58:20 PM', 1),
(213, 'admin', 'John', 'Smith', 'john@gmail.com', 0x3a3a3100000000000000000000000000, '2024-10-25 14:08:14', NULL, 1),
(214, 'admin', 'John', 'Smith', 'john@gmail.com', 0x3a3a3100000000000000000000000000, '2024-10-25 14:09:10', '25-10-2024 10:18:50 PM', 1),
(215, 'admin', 'John', 'Smith', 'john@gmail.com', 0x3a3a3100000000000000000000000000, '2024-10-25 19:21:32', '25-10-2024 10:24:44 PM', 1),
(216, 'admin', 'John', 'Smith', 'john@gmail.com', 0x3a3a3100000000000000000000000000, '2024-10-25 19:26:42', '25-10-2024 11:23:08 PM', 1),
(217, 'admin', 'John', 'Smith', 'john@gmail.com', 0x3a3a3100000000000000000000000000, '2024-10-25 20:39:42', '25-10-2024 11:40:20 PM', 1),
(218, 'jim', 'jim', 'jim', 'jim@jim.com', 0x3a3a3100000000000000000000000000, '2024-10-25 20:41:19', '25-10-2024 11:41:59 PM', 1),
(219, 'admin', 'John', 'Smith', 'john@gmail.com', 0x3a3a3100000000000000000000000000, '2024-10-25 20:47:40', NULL, 1),
(220, 'admin', 'John', 'Smith', 'john@gmail.com', 0x3a3a3100000000000000000000000000, '2024-10-26 16:13:29', '26-10-2024 07:15:45 PM', 1),
(221, 'admin', 'John', 'Smith', 'john@gmail.com', 0x3a3a3100000000000000000000000000, '2024-11-06 01:16:25', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
