-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 03, 2021 at 10:59 AM
-- Server version: 8.0.22
-- PHP Version: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `odbusbackend`
--

-- --------------------------------------------------------

--
-- Table structure for table `boarding_droping`
--

CREATE TABLE `boarding_droping` (
  `id` int NOT NULL,
  `location_id` int UNSIGNED NOT NULL,
  `boarding_point` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boarding_droping`
--
ALTER TABLE `boarding_droping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boarding_droping`
--
ALTER TABLE `boarding_droping`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `boarding_droping`
--
ALTER TABLE `boarding_droping`
  ADD CONSTRAINT `boarding_droping_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`);
COMMIT;



CREATE TABLE `bus_class` (
  `id` int NOT NULL,
  `class_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bus_class`
--

INSERT INTO `bus_class` (`id`, `class_name`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 'AC', '2021-06-03 17:02:39', '2021-06-03 17:02:39', 'Admin'),
(2, 'Non AC', '2021-06-03 17:02:39', '2021-06-03 17:02:39', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bus_class`
--
ALTER TABLE `bus_class`
  ADD PRIMARY KEY (`id`);
  
  
  

CREATE TABLE `bus_type` (
  `id` int UNSIGNED NOT NULL,
  `type` int NOT NULL DEFAULT '0',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bus_type`
--

INSERT INTO `bus_type` (`id`, `type`, `name`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(315, 1, 'AC', '2018-07-28 10:38:59', '2021-02-06 06:39:49', 'ty6754yt', 2),
(316, 2, 'All Sleeper', '2017-10-05 13:17:21', '2021-02-10 10:55:53', 'ty6754yt', 1),
(317, 1, 'VOLVO', '2017-10-05 13:17:21', '2017-10-05 13:17:21', 'ty6754yt', 1),
(318, 1, 'AC Delux', '2017-10-30 11:09:52', '2017-10-30 11:09:52', 'ty6754yt', 1),
(319, 1, 'AC Non Sleeper', '2017-10-30 11:10:36', '2017-10-30 11:10:36', 'ty6754yt', 1),
(320, 1, 'AC Sleeper (Rear Engine)', '2017-10-30 11:10:41', '2017-10-30 11:10:41', 'ty6754yt', 1),
(321, 1, 'AC VOLVO', '2017-10-30 11:10:54', '2017-10-30 11:10:54', 'ty6754yt', 1),
(322, 1, 'AC Sleeper', '2017-10-30 11:11:05', '2017-10-30 11:11:05', 'ty6754yt', 1),
(323, 2, 'Delux Non AC', '2017-10-30 11:11:14', '2017-10-30 11:11:14', 'ty6754yt', 1),
(324, 2, 'Non AC Sleepers', '2017-10-30 11:11:31', '2021-02-26 13:48:45', 'ty6754yt', 2),
(325, 1, 'Non Sleeper', '2017-10-30 11:11:41', '2017-10-30 11:11:41', 'ty6754yt', 1),
(326, 1, 'Sleeper', '2018-03-01 15:17:00', '2018-03-01 15:17:00', 'ty6754yt', 1),
(327, 1, 'Multi Axle Volvo A/C', '2018-04-09 10:45:09', '2018-04-09 10:45:09', 'ty6754yt', 1),
(328, 2, 'Non AC Seater', '2018-05-09 11:30:24', '2018-05-09 11:30:24', 'ty6754yt', 1),
(330, 1, 'A/C Seater', '2018-07-06 14:46:46', '2018-07-06 14:46:46', 'ty6754yt', 1),
(331, 2, 'Non AC Push Back Seater', '2018-07-25 13:01:22', '2018-07-25 13:01:22', 'ty6754yt', 1),
(332, 1, 'A/C Semi Sleeper', '2018-07-28 10:38:04', '2018-07-28 10:38:04', 'ty6754yt', 1),
(333, 1, 'A/C Luxury Sleeper ', '2018-08-28 11:42:04', '2018-08-28 11:42:04', 'ty6754yt', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bus_type`
--
ALTER TABLE `bus_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_type_fk` (`type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bus_type`
--
ALTER TABLE `bus_type`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=339;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bus_type`
--
ALTER TABLE `bus_type`
  ADD CONSTRAINT `class_type_fk` FOREIGN KEY (`type`) REFERENCES `bus_class` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;  

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
