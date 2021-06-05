-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 05, 2021 at 04:36 PM
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
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` blob,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `name`, `icon`, `created_at`, `updated_at`, `created_by`, `reason`, `status`) VALUES
(424, 'LCD', 0x433a5c66616b65706174685c6d757369632e706e67, '2017-10-05 13:45:17', '2021-02-27 11:37:07', 'Admin', NULL, 2),
(425, 'example 1', 0x64656d6f2031, '2017-10-05 13:45:17', '2021-05-13 05:35:29', 'Admin', NULL, 1),
(426, 'Water Bottle', 0x77622e737667, '2017-10-05 13:45:17', '2017-10-05 13:45:17', NULL, NULL, 1),
(427, 'Blankets', 0x426c616e6b6574732e737667, '2017-10-05 13:45:17', '2017-10-05 13:45:17', NULL, NULL, 1),
(428, 'Charging Point', 0x63702e737667, '2017-10-05 13:45:17', '2017-10-05 13:45:17', NULL, NULL, 1),
(429, 'Reading Light', 0x726c2e737667, '2017-10-05 13:45:17', '2017-10-05 13:45:17', NULL, NULL, 1),
(430, 'No Amenities', 0x6e6f616d656e69746965732e737667, '2017-10-05 13:45:17', '2017-10-05 13:45:17', NULL, NULL, 1),
(431, 'Wifi', 0x574946492e737667, '2017-10-05 13:45:17', '2017-10-05 13:45:17', NULL, NULL, 1),
(432, 'CCTV', 0x636374762e737667, '2017-10-05 13:45:17', '2017-10-05 13:45:17', NULL, NULL, 1),
(433, 'Cold Water', 0x77622e737667, '2017-10-05 13:45:17', '2021-01-27 10:06:16', NULL, NULL, 2),
(434, 'Music System', 0x6d732e737667, '2017-10-05 13:45:17', '2017-10-05 13:45:17', NULL, NULL, 1),
(435, 'Pillow', 0x50696c6c6f772e737667, '2017-10-05 13:45:17', '2017-10-05 13:45:17', NULL, NULL, 1),
(436, 'Snacks', 0x536e61636b732e737667, '2017-10-05 13:45:17', '2017-10-05 13:45:17', NULL, NULL, 1),
(437, 'M-Ticket', 0x6d7469636b65742e737667, '2017-10-05 13:45:17', '2017-10-05 13:45:17', NULL, NULL, 1),
(438, 'Fire Extinguisher', 0x66652e737667, '2017-10-05 13:45:17', '2017-10-05 13:45:17', NULL, NULL, 1),
(439, 'Personal TV', 0x6c65642e737667, '2017-10-05 13:45:17', '2017-10-05 13:45:17', NULL, NULL, 1),
(441, 'Newspaper', 0x4e65777370617065722e737667, '2017-10-05 13:45:17', '2017-10-05 13:45:17', NULL, NULL, 1),
(442, 'Cup Holder', 0x6375702d686f6c6465722e737667, '2017-10-05 13:45:17', '2017-10-05 13:45:17', NULL, NULL, 1),
(443, 'E-Ticket', 0x657469636b65742e737667, '2017-10-05 13:45:17', '2017-10-05 13:45:17', NULL, NULL, 1),
(445, 'Movie', 0x6d6f762e737667, '2018-10-11 17:59:48', '2018-10-11 17:59:48', NULL, NULL, 1),
(446, 'Soft Drink', 0x736f66746472696e6b2e737667, '2018-08-18 11:04:48', '2018-08-18 11:04:48', NULL, NULL, 1),
(449, 'test123', 0x433a5c66616b65706174685c636c6f73652e706e67, '2021-03-05 05:14:51', '2021-03-05 05:14:51', 'Admin', NULL, 0),
(450, 'Green Seat', 0x6956424f5277304b47676f414141414e5355684555674141414273414141416243414d41414143364367526e414141415046424d5645582f2f2f3841414144392f663243676f4b416749414141414141414141414141424c53307341414141414141437171717171717171367572704b536b704953456741414143377537753575626e2f2f2f2f7a62734d634141414145335253546c4d4153763672717741575335594d43372f41795a57564663724a4359614b664141414148684a5245465565463539306b6b4f6743415152464561465647632b2f353346596d627a364a71426279514d465359756f5175562b6954666c6e7374493773734c5852764d575261454d73383465327556636b755a65366b6e4c30686953504f6258686a3643687a6f456b496f6c4949704b494f346a6f49434149654464375147496643436a4f4b653948456b386d6e7870494175702f46333152505a50396641473349417942534a6530696741414141424a52553545726b4a6767673d3d, '2021-05-07 11:19:48', '2021-05-07 11:20:01', 'Admin', 'active it', 1);

-- --------------------------------------------------------

--
-- Table structure for table `appdownload`
--

CREATE TABLE `appdownload` (
  `id` bigint UNSIGNED NOT NULL,
  `mobileno` bigint UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appversion`
--

CREATE TABLE `appversion` (
  `id` int NOT NULL,
  `info` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mandatory` int NOT NULL DEFAULT '1' COMMENT '0-not mandatory 1- manadatory',
  `version` int NOT NULL,
  `new_version_names` mediumtext COLLATE utf8mb4_unicode_ci,
  `new_version_codes` mediumtext COLLATE utf8mb4_unicode_ci,
  `allowed_days` int DEFAULT NULL,
  `has_issues` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Dumping data for table `boarding_droping`
--

INSERT INTO `boarding_droping` (`id`, `location_id`, `boarding_point`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(16, 1291, 'Bermunda', '2021-06-03 10:56:48', '2021-06-03 10:56:48', 'Admin', 0),
(17, 1291, 'Fire Station', '2021-06-03 10:56:48', '2021-06-03 10:56:48', 'Admin', 0),
(18, 1291, 'CRPF Square', '2021-06-03 10:56:48', '2021-06-03 10:56:48', 'Admin', 0),
(19, 1291, 'Acharya Vihar', '2021-06-03 10:56:48', '2021-06-03 10:56:48', 'Admin', 0),
(20, 1291, 'Vani Vihar', '2021-06-03 10:56:48', '2021-06-03 10:56:48', 'Admin', 0),
(21, 1291, 'Rasulgarh', '2021-06-03 10:56:48', '2021-06-03 10:56:48', 'Admin', 0),
(22, 1291, 'Palasuni', '2021-06-03 10:56:48', '2021-06-03 10:56:48', 'Admin', 0),
(23, 1291, 'Shakti nagar', '2021-06-03 10:56:48', '2021-06-03 10:56:48', 'Admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int UNSIGNED NOT NULL,
  `transaction_id` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pnr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_customer_id` int NOT NULL COMMENT 'Customers ID',
  `bus_operator_id` int NOT NULL COMMENT 'Operator Id',
  `bus_id` int UNSIGNED NOT NULL,
  `source_id` int UNSIGNED NOT NULL,
  `destination_id` int UNSIGNED NOT NULL,
  `j_day` int NOT NULL DEFAULT '0' COMMENT 'journey day | 0-same day 1-nxt day',
  `journey_dt` date NOT NULL,
  `boardingPoint_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `droppingPoint_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `boarding_time` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dropping_time` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_fare` double(8,2) UNSIGNED NOT NULL,
  `ownr_fare` double(8,2) DEFAULT NULL,
  `is_coupon` int NOT NULL DEFAULT '0' COMMENT '0-no 1-yes',
  `coupon_code` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_discount` decimal(9,2) DEFAULT NULL,
  `discounted_fare` decimal(9,2) DEFAULT NULL,
  `origin` enum('ODBUS','RPBOA','GRANDBUS','JANARDANBUS','KHAMBESWARI','MOBUS') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_type` set('WEB','MOB','ANDROID','CLNTWEB','CLNTMOB','ASSNWEB','ASSNMOB','CONDUCTOR','AGENT','MANAGER','OPERATOR') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `typ_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of Users booking Ticket',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_customer`
--

CREATE TABLE `booking_customer` (
  `id` int NOT NULL,
  `first_name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_detail`
--

CREATE TABLE `booking_detail` (
  `id` int NOT NULL,
  `booking_id` int UNSIGNED NOT NULL,
  `pnr` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jrny_dt` date NOT NULL,
  `j_day` int NOT NULL DEFAULT '0' COMMENT 'journey day | 0-same day 1-nxt day journey day | 0-same day 1-nxt day',
  `bus_id` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `seat_no` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `passenger_name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `passenger_gender` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `passenger_age` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus`
--

CREATE TABLE `bus` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `bus_operator_id` int NOT NULL DEFAULT '1',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `via` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_description` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bus_type_id` int UNSIGNED NOT NULL,
  `bus_sitting_id` int UNSIGNED NOT NULL,
  `bus_seat_layout_id` int UNSIGNED NOT NULL,
  `cancellationslabs_id` int NOT NULL DEFAULT '2',
  `running_cycle` int UNSIGNED NOT NULL,
  `popularity` int UNSIGNED DEFAULT NULL COMMENT 'Higher the number higher will be posotioning in buslist',
  `admin_notes` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `has_return_bus` int NOT NULL COMMENT '0-no 1-yes',
  `return_bus_id` int DEFAULT NULL,
  `cancelation_points` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0',
  `sequence` int NOT NULL DEFAULT '1000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bus`
--

INSERT INTO `bus` (`id`, `user_id`, `bus_operator_id`, `name`, `via`, `bus_number`, `bus_description`, `bus_type_id`, `bus_sitting_id`, `bus_seat_layout_id`, `cancellationslabs_id`, `running_cycle`, `popularity`, `admin_notes`, `has_return_bus`, `return_bus_id`, `cancelation_points`, `created_at`, `updated_at`, `created_by`, `status`, `sequence`) VALUES
(1, 1, 1, 'DEMO BUS', 'Angul', 'OD DEMO 0001', 'Luxury Bus', 315, 3, 1, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-06-04 16:44:05', '2021-06-04 16:44:05', 'Admin', 0, 1000),
(2, 1, 1, 'DEMO BUS', 'Angul', 'OD DEMO 0001', 'Luxury Bus', 315, 3, 1, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-06-04 16:44:55', '2021-06-04 16:44:55', 'Admin', 0, 1000),
(3, 1, 1, 'DEMO BUS', 'Angul', 'OD DEMO 0001', 'Luxury Bus', 315, 3, 1, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-06-04 16:45:52', '2021-06-04 16:45:52', 'Admin', 0, 1000),
(4, 1, 1, 'DEMO BUS', 'Angul', 'OD DEMO 0001', 'Luxury Bus', 315, 3, 1, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-06-04 16:46:19', '2021-06-04 16:46:19', 'Admin', 0, 1000),
(5, 1, 1, 'DEMO BUS', 'Angul', 'OD DEMO 0001', 'Luxury Bus', 315, 3, 1, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-06-04 16:46:42', '2021-06-04 16:46:42', 'Admin', 0, 1000),
(6, 1, 1, 'DEMO BUS', 'Angul', 'OD DEMO 0001', 'Luxury Bus', 315, 3, 1, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-06-04 16:50:42', '2021-06-04 16:50:42', 'Admin', 0, 1000),
(7, 1, 1, 'DEMO BUS', 'Angul', 'OD DEMO 0001', 'Luxury Bus', 315, 3, 1, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-06-04 16:50:51', '2021-06-04 16:50:51', 'Admin', 0, 1000),
(8, 1, 1, 'DEMO BUS', 'Angul', 'OD DEMO 0001', 'Luxury Bus', 315, 3, 1, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-06-04 16:54:30', '2021-06-04 16:54:30', 'Admin', 0, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `bus_amenities`
--

CREATE TABLE `bus_amenities` (
  `id` int UNSIGNED NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `amenities_id` int UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bus_amenities`
--

INSERT INTO `bus_amenities` (`id`, `bus_id`, `amenities_id`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(1, 1, 425, '2021-06-04 16:44:05', '2021-06-04 16:44:05', 'Admin', 1),
(2, 1, 426, '2021-06-04 16:44:05', '2021-06-04 16:44:05', 'Admin', 1),
(3, 2, 425, '2021-06-04 16:44:55', '2021-06-04 16:44:55', 'Admin', 1),
(4, 2, 426, '2021-06-04 16:44:56', '2021-06-04 16:44:56', 'Admin', 1),
(5, 3, 425, '2021-06-04 16:45:52', '2021-06-04 16:45:52', 'Admin', 1),
(6, 3, 426, '2021-06-04 16:45:52', '2021-06-04 16:45:52', 'Admin', 1),
(7, 4, 425, '2021-06-04 16:46:19', '2021-06-04 16:46:19', 'Admin', 1),
(8, 4, 426, '2021-06-04 16:46:19', '2021-06-04 16:46:19', 'Admin', 1),
(9, 5, 425, '2021-06-04 16:46:42', '2021-06-04 16:46:42', 'Admin', 1),
(10, 5, 426, '2021-06-04 16:46:42', '2021-06-04 16:46:42', 'Admin', 1),
(11, 6, 425, '2021-06-04 16:50:42', '2021-06-04 16:50:42', 'Admin', 1),
(12, 6, 426, '2021-06-04 16:50:42', '2021-06-04 16:50:42', 'Admin', 1),
(13, 7, 425, '2021-06-04 16:50:51', '2021-06-04 16:50:51', 'Admin', 1),
(14, 7, 426, '2021-06-04 16:50:51', '2021-06-04 16:50:51', 'Admin', 1),
(21, 8, 428, '2021-06-05 16:14:35', '2021-06-05 16:14:35', 'Admin', 1),
(22, 8, 429, '2021-06-05 16:14:35', '2021-06-05 16:14:35', 'Admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `bus_cancelled`
--

CREATE TABLE `bus_cancelled` (
  `id` int UNSIGNED NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `bus_operator_id` int NOT NULL,
  `month` varchar(50) DEFAULT NULL,
  `year` varchar(50) DEFAULT NULL,
  `reason` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `cancelled_by` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_cancelled_date`
--

CREATE TABLE `bus_cancelled_date` (
  `id` int UNSIGNED NOT NULL,
  `bus_cancelled_id` int UNSIGNED NOT NULL,
  `cancelled_date` date NOT NULL,
  `created_by` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus_class`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `bus_closing_hours`
--

CREATE TABLE `bus_closing_hours` (
  `id` int NOT NULL,
  `bus_id` int NOT NULL,
  `city_id` int NOT NULL,
  `dep_time` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `closing_hours` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus_contacts`
--

CREATE TABLE `bus_contacts` (
  `id` int NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `type` int NOT NULL COMMENT '0-operator 1-manager 2-conductor',
  `phone` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_sms_send` int NOT NULL DEFAULT '0' COMMENT '0-dontsend 1-send',
  `cancel_sms_send` int NOT NULL DEFAULT '0' COMMENT '0-dontsend 1-send',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bus_contacts`
--

INSERT INTO `bus_contacts` (`id`, `bus_id`, `type`, `phone`, `booking_sms_send`, `cancel_sms_send`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(1, 1, 2, '1234567890', 1, 1, '2021-06-04 16:44:05', '2021-06-04 16:44:05', 'Admin', 1),
(2, 1, 1, '1234567890', 1, 1, '2021-06-04 16:44:05', '2021-06-04 16:44:05', 'Admin', 1),
(3, 1, 0, '1234567890', 1, 1, '2021-06-04 16:44:05', '2021-06-04 16:44:05', 'Admin', 1),
(4, 2, 2, '1234567890', 1, 1, '2021-06-04 16:44:56', '2021-06-04 16:44:56', 'Admin', 1),
(5, 2, 1, '1234567890', 1, 1, '2021-06-04 16:44:56', '2021-06-04 16:44:56', 'Admin', 1),
(6, 2, 0, '1234567890', 1, 1, '2021-06-04 16:44:56', '2021-06-04 16:44:56', 'Admin', 1),
(7, 3, 2, '1234567890', 1, 1, '2021-06-04 16:45:52', '2021-06-04 16:45:52', 'Admin', 1),
(8, 3, 1, '1234567890', 1, 1, '2021-06-04 16:45:52', '2021-06-04 16:45:52', 'Admin', 1),
(9, 3, 0, '1234567890', 1, 1, '2021-06-04 16:45:52', '2021-06-04 16:45:52', 'Admin', 1),
(10, 4, 2, '1234567890', 1, 1, '2021-06-04 16:46:19', '2021-06-04 16:46:19', 'Admin', 1),
(11, 4, 1, '1234567890', 1, 1, '2021-06-04 16:46:19', '2021-06-04 16:46:19', 'Admin', 1),
(12, 4, 0, '1234567890', 1, 1, '2021-06-04 16:46:19', '2021-06-04 16:46:19', 'Admin', 1),
(13, 5, 2, '1234567890', 1, 1, '2021-06-04 16:46:42', '2021-06-04 16:46:42', 'Admin', 1),
(14, 5, 1, '1234567890', 1, 1, '2021-06-04 16:46:42', '2021-06-04 16:46:42', 'Admin', 1),
(15, 5, 0, '1234567890', 1, 1, '2021-06-04 16:46:42', '2021-06-04 16:46:42', 'Admin', 1),
(16, 6, 2, '1234567890', 1, 1, '2021-06-04 16:50:42', '2021-06-04 16:50:42', 'Admin', 1),
(17, 6, 1, '1234567890', 1, 1, '2021-06-04 16:50:42', '2021-06-04 16:50:42', 'Admin', 1),
(18, 6, 0, '1234567890', 1, 1, '2021-06-04 16:50:42', '2021-06-04 16:50:42', 'Admin', 1),
(19, 7, 2, '1234567890', 1, 1, '2021-06-04 16:50:51', '2021-06-04 16:50:51', 'Admin', 1),
(20, 7, 1, '1234567890', 1, 1, '2021-06-04 16:50:51', '2021-06-04 16:50:51', 'Admin', 1),
(21, 7, 0, '1234567890', 1, 1, '2021-06-04 16:50:51', '2021-06-04 16:50:51', 'Admin', 1),
(22, 8, 2, '1234567890', 1, 1, '2021-06-04 16:54:30', '2021-06-04 16:54:30', 'Admin', 1),
(23, 8, 1, '1234567890', 1, 1, '2021-06-04 16:54:30', '2021-06-04 16:54:30', 'Admin', 1),
(24, 8, 0, '1234567890', 1, 1, '2021-06-04 16:54:30', '2021-06-04 16:54:30', 'Admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `bus_extra_fare`
--

CREATE TABLE `bus_extra_fare` (
  `id` bigint UNSIGNED NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `type` int UNSIGNED NOT NULL COMMENT '1 - Operator, 2 - ODBUS',
  `journey_date` date DEFAULT NULL,
  `seat_fare` int NOT NULL COMMENT 'extra 30rs.. added to all seaters',
  `sleeper_fare` int NOT NULL COMMENT 'extra 70rs.. added to all sleapers',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus_gallery`
--

CREATE TABLE `bus_gallery` (
  `id` int UNSIGNED NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `image` mediumblob NOT NULL,
  `alt_tag` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus_operator`
--

CREATE TABLE `bus_operator` (
  `id` int NOT NULL,
  `email_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `operator_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organisation_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `additional_email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional_contact` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_ifsc` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bus Operators';

--
-- Dumping data for table `bus_operator`
--

INSERT INTO `bus_operator` (`id`, `email_id`, `password`, `operator_name`, `contact_number`, `organisation_name`, `location_name`, `address`, `additional_email`, `additional_contact`, `bank_account_name`, `bank_name`, `bank_ifsc`, `bank_account_number`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(1, 'chandrakantaphp@gmail.com', 'Admin@2010', 'Chandra', '7008559705', 'DURGA', 'Patamundai', 'Patamundai Housing Colony', NULL, NULL, NULL, NULL, NULL, NULL, '2021-05-27 13:32:47', '2021-05-27 13:32:47', 'Admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `bus_owner_fare`
--

CREATE TABLE `bus_owner_fare` (
  `id` int NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `owner_fare_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus_safety`
--

CREATE TABLE `bus_safety` (
  `id` int NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `safety_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bus_safety`
--

INSERT INTO `bus_safety` (`id`, `bus_id`, `safety_id`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 1, 3, '2021-06-04 16:44:05', '2021-06-04 16:44:05', 'Admin'),
(2, 1, 4, '2021-06-04 16:44:05', '2021-06-04 16:44:05', 'Admin'),
(3, 2, 3, '2021-06-04 16:44:56', '2021-06-04 16:44:56', 'Admin'),
(4, 2, 4, '2021-06-04 16:44:56', '2021-06-04 16:44:56', 'Admin'),
(5, 3, 3, '2021-06-04 16:45:52', '2021-06-04 16:45:52', 'Admin'),
(6, 3, 4, '2021-06-04 16:45:52', '2021-06-04 16:45:52', 'Admin'),
(7, 4, 3, '2021-06-04 16:46:19', '2021-06-04 16:46:19', 'Admin'),
(8, 4, 4, '2021-06-04 16:46:19', '2021-06-04 16:46:19', 'Admin'),
(9, 5, 3, '2021-06-04 16:46:42', '2021-06-04 16:46:42', 'Admin'),
(10, 5, 4, '2021-06-04 16:46:42', '2021-06-04 16:46:42', 'Admin'),
(11, 6, 3, '2021-06-04 16:50:42', '2021-06-04 16:50:42', 'Admin'),
(12, 6, 4, '2021-06-04 16:50:42', '2021-06-04 16:50:42', 'Admin'),
(13, 7, 3, '2021-06-04 16:50:51', '2021-06-04 16:50:51', 'Admin'),
(14, 7, 4, '2021-06-04 16:50:51', '2021-06-04 16:50:51', 'Admin'),
(17, 8, 5, '2021-06-05 16:14:35', '2021-06-05 16:14:35', 'Admin'),
(18, 8, 4, '2021-06-05 16:14:35', '2021-06-05 16:14:35', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `bus_schedule`
--

CREATE TABLE `bus_schedule` (
  `id` int UNSIGNED NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Admin',
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus_schedule_date`
--

CREATE TABLE `bus_schedule_date` (
  `id` int UNSIGNED NOT NULL,
  `bus_schedule_id` int UNSIGNED NOT NULL,
  `entry_date` date NOT NULL,
  `created_by` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus_seats`
--

CREATE TABLE `bus_seats` (
  `id` int UNSIGNED NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `ticket_price_id` int UNSIGNED NOT NULL,
  `category` int UNSIGNED NOT NULL COMMENT '0-odbus 1-conductor',
  `seat_type` int UNSIGNED NOT NULL COMMENT '1-seater 2-sleeper  3-vertical Sleeper',
  `berth_type` int NOT NULL DEFAULT '1' COMMENT '1 - Lower Berth \r\n2- Upper Berth',
  `bookStatus` int NOT NULL DEFAULT '0' COMMENT '0=Not Booked,\r\n1= Booked,\r\n2=Reserved',
  `seat_number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT 'if grater than 0 its additional seats/ sleepers in minutes THE  gap after which full seats will be given to odbus',
  `new_fare` double(8,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bus_seats`
--

INSERT INTO `bus_seats` (`id`, `bus_id`, `ticket_price_id`, `category`, `seat_type`, `berth_type`, `bookStatus`, `seat_number`, `duration`, `new_fare`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(1, 8, 1, 0, 1, 1, 0, '6', '0', 0.00, '2021-06-04 16:54:30', '2021-06-04 16:54:30', 'Admin', 0),
(2, 8, 1, 0, 1, 1, 0, '7', '0', 0.00, '2021-06-04 16:54:30', '2021-06-04 16:54:30', 'Admin', 0),
(3, 8, 1, 0, 1, 1, 0, '8', '0', 0.00, '2021-06-04 16:54:30', '2021-06-04 16:54:30', 'Admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `bus_seats_extra`
--

CREATE TABLE `bus_seats_extra` (
  `id` int UNSIGNED NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `journey_dt` date NOT NULL,
  `type` int UNSIGNED NOT NULL COMMENT '1 - Block, 2 - Open',
  `seat_type` int UNSIGNED NOT NULL COMMENT '0-seater 1-sleeper',
  `seat_number` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus_seat_layout`
--

CREATE TABLE `bus_seat_layout` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bus_seat_layout`
--

INSERT INTO `bus_seat_layout` (`id`, `name`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(1, 'Only Seater Demo 1', '2021-04-29 11:40:07', '2021-04-29 11:40:16', 'Admin', 1),
(2, 'Only Sleeper Demo 001', '2021-04-29 12:10:30', '2021-04-29 12:29:13', 'Admin', 2),
(3, '11 Sleeper Only', '2021-04-29 12:30:14', '2021-04-29 12:30:21', 'Admin', 1),
(4, '41 Seater 11 Sleeper', '2021-04-29 12:36:38', '2021-04-29 12:39:58', 'Admin', 2),
(5, 'Mix Layout', '2021-04-30 09:46:43', '2021-04-30 09:47:04', 'Admin', 1),
(6, '4 row seater sleeper', '2021-04-30 11:53:28', '2021-04-30 11:54:06', 'Admin', 1),
(7, '41 Seater', '2021-05-05 11:01:01', '2021-05-05 11:01:12', 'Admin', 1),
(8, '21 Sleeper', '2021-05-05 11:07:51', '2021-05-05 11:08:04', 'Admin', 1),
(9, '2/2 Sleeper (45/10/V4)', '2021-05-05 11:19:55', '2021-05-05 11:21:56', 'Admin', 2),
(10, '41/12 Seater Sleeper', '2021-05-05 14:13:57', '2021-05-05 14:15:30', 'Admin', 2),
(11, 'Only Sleeper 8', '2021-05-05 14:18:52', '2021-05-05 14:19:32', 'Admin', 1),
(12, 'Master 9 Seater Only', '2021-06-02 09:52:00', '2021-06-02 09:52:00', 'Admin', 0),
(13, '6 Seater Testings', '2021-06-02 09:56:57', '2021-06-02 09:56:57', 'Admin', 0),
(14, '6 Seater Testingss', '2021-06-02 09:57:47', '2021-06-02 09:57:47', 'Admin', 0),
(15, '6 Seater Testingssd', '2021-06-02 09:58:32', '2021-06-02 09:58:32', 'Admin', 0),
(16, '6 Seater Only', '2021-06-02 09:59:44', '2021-06-02 09:59:44', 'Admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `bus_sitting`
--

CREATE TABLE `bus_sitting` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bus_sitting`
--

INSERT INTO `bus_sitting` (`id`, `name`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(1, 'demo 56ss', '2021-02-16 11:01:14', '2021-05-08 06:26:32', 'Admin', 1),
(2, 'sss', '2021-02-16 11:04:07', '2021-02-24 11:33:50', 'Admin', 1),
(3, '2+2', '2021-02-16 11:06:05', '2021-02-24 11:33:48', 'Admin', 1),
(4, '6+2', '2021-02-16 11:07:25', '2021-02-26 13:53:32', 'Admin', 1),
(5, '3/2', '2021-02-25 13:58:46', '2021-02-25 13:58:50', 'Admin', 1),
(6, '6+2+2', '2021-02-26 13:53:41', '2021-02-26 13:54:05', 'Admin', 2),
(7, '7-2', '2021-02-27 06:19:52', '2021-02-27 06:46:03', 'Admin', 2),
(8, '5+2+1', '2021-02-27 06:44:50', '2021-02-27 06:45:31', 'Admin', 2),
(9, '5+9', '2021-02-27 09:18:20', '2021-02-27 12:43:15', 'Admin', 2),
(10, '5+99', '2021-02-27 09:19:01', '2021-02-27 12:43:20', 'Admin', 2),
(11, 'New Demo 5/6', '2021-05-08 06:26:43', '2021-05-08 06:26:48', 'Admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `bus_slots`
--

CREATE TABLE `bus_slots` (
  `id` int UNSIGNED NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `name` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int NOT NULL DEFAULT '0' COMMENT '0- ODBUS    1- conductor ',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus_special_fare`
--

CREATE TABLE `bus_special_fare` (
  `id` int NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `special_fare_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus_stoppage_additional_fare`
--

CREATE TABLE `bus_stoppage_additional_fare` (
  `id` int UNSIGNED NOT NULL,
  `ticket_price_id` int UNSIGNED NOT NULL,
  `bus_seats_id` int UNSIGNED NOT NULL,
  `additional_fare` double(8,2) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus_stoppage_timing`
--

CREATE TABLE `bus_stoppage_timing` (
  `id` int UNSIGNED NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `location_id` int UNSIGNED NOT NULL,
  `boarding_dropping_id` int NOT NULL,
  `stoppage_name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stoppage_time` time NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Admin',
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bus_stoppage_timing`
--

INSERT INTO `bus_stoppage_timing` (`id`, `bus_id`, `location_id`, `boarding_dropping_id`, `stoppage_name`, `stoppage_time`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(1, 8, 1291, 16, 'Bermunda', '20:00:00', '2021-06-04 16:54:30', '2021-06-04 16:54:30', 'Admin', 0),
(2, 8, 1291, 17, 'Fire Station', '21:00:00', '2021-06-04 16:54:30', '2021-06-04 16:54:30', 'Admin', 0),
(3, 8, 1291, 22, 'Palasuni', '22:00:00', '2021-06-04 16:54:30', '2021-06-04 16:54:30', 'Admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `bus_type`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `cancellationslabs`
--

CREATE TABLE `cancellationslabs` (
  `id` int NOT NULL,
  `api_id` int DEFAULT NULL,
  `rule_name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deduction` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cancellationslabs`
--

INSERT INTO `cancellationslabs` (`id`, `api_id`, `rule_name`, `duration`, `deduction`, `status`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 1, '1', '24-99#$18-23#$7-17#$0-6', '40#$70#$80#$100', 1, '2021-02-03 09:14:33', '2021-02-26 13:57:00', NULL),
(3, 1, 'ddd', 'sss#$eeess', 'sssddd#$gggdddd', 1, '2021-02-16 05:51:41', '2021-02-16 05:51:41', NULL),
(4, 1, 'New Slot', '25-999#$18-24#$0-17', '20#$40#$100', 2, '2021-02-26 13:57:38', '2021-02-26 14:24:20', NULL),
(5, 1, '1', '24-999#$18-23#$0-17', '45#$50#$100', 1, '2021-02-27 06:24:18', '2021-02-27 06:24:51', NULL),
(6, 1, 'OD Rule 2', '25-999#$18-24#$8-17#$0-7', '30#$50#$80#$100', 1, '2021-02-27 06:58:31', '2021-02-27 06:58:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `city_closing`
--

CREATE TABLE `city_closing` (
  `id` int UNSIGNED NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `location_id` int UNSIGNED NOT NULL,
  `closing_hours` int UNSIGNED DEFAULT NULL COMMENT 'Time in minutes',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Admin',
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `city_closing_extended`
--

CREATE TABLE `city_closing_extended` (
  `id` int NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `location_id` int UNSIGNED DEFAULT NULL,
  `journey_date` date NOT NULL,
  `closing_hours` int UNSIGNED DEFAULT NULL COMMENT 'Time in minutes',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `id` int UNSIGNED NOT NULL,
  `coupon_title` varchar(254) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_code` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('Percent','CutOff') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(8,2) DEFAULT NULL COMMENT 'in % or in cash',
  `max_discount_price` double(8,2) DEFAULT NULL COMMENT 'incase of % deduction',
  `min_tran_amount` double(8,2) DEFAULT NULL,
  `max_redeem` int DEFAULT NULL,
  `max_use_limit` int DEFAULT NULL,
  `category` int DEFAULT NULL COMMENT '0-booking date 1-journey date',
  `from_date` datetime DEFAULT NULL,
  `to_date` datetime DEFAULT NULL,
  `short_desc` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_desc` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_assigned_bus`
--

CREATE TABLE `coupon_assigned_bus` (
  `id` int UNSIGNED NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `coupon_id` int UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_query`
--

CREATE TABLE `customer_query` (
  `id` int NOT NULL,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `query_typ` enum('RESERVATION','CONTACT') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'json_data',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_query_category`
--

CREATE TABLE `customer_query_category` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_query_category_issues`
--

CREATE TABLE `customer_query_category_issues` (
  `id` int UNSIGNED NOT NULL,
  `customer_query_category_id` int UNSIGNED NOT NULL,
  `name` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_pages`
--

CREATE TABLE `custom_pages` (
  `id` int NOT NULL,
  `origin` int DEFAULT '0' COMMENT '0-odbus 1-rpboa 2-janardana ',
  `type` int DEFAULT '0' COMMENT '0-custom pages  1-route pages 2-news',
  `source_id` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'only for route pages',
  `destination_id` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'only for route pages',
  `name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` mediumtext COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_descriptiom` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `extended_bus_closing_hours`
--

CREATE TABLE `extended_bus_closing_hours` (
  `id` int NOT NULL,
  `bus_id` int NOT NULL,
  `city_id` int NOT NULL,
  `dep_time` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `closing_hours` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `synonym` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `name`, `synonym`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(1291, 'Bhubaneswar', 'BBSR,bbs, bhobneswar', '2017-10-05 13:01:12', '2021-02-22 05:39:37', 'Admin', 1),
(1292, 'Cuttack', 'katak,CTC', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1293, 'Dhenkanal', 'DNKL', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1294, 'Sambalpur', 'SBP', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1295, 'Jharsuguda', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1296, 'Sora', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1297, 'Bargarh', '', '2017-10-05 13:01:12', '2018-09-17 12:18:40', '', 1),
(1298, 'Khurda', 'Khordha,Khurdha', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1299, 'JajpurTown', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1300, 'Talcher', 'TLHR', '2017-10-05 13:01:12', '2021-02-26 13:53:16', 'Admin', 1),
(1301, 'Baripada', 'BPO', '2017-10-05 13:01:12', '2018-09-17 12:18:40', '', 1),
(1302, 'Sundargarh', 'Sundergarh', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1303, 'Balasore', 'BLS,Baleswar', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1304, 'Kolkata', 'KOL', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1305, 'Durg', '', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1306, 'Nayagarh', 'NYG', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1307, 'Birmitrapur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1308, 'Banharpali', 'Banaharpali', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1309, 'Rourkela', 'ROU, RKL, RAURKELA', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1310, 'Balisankara', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1311, 'Parlakhemundi', 'Paralakhemundi', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1312, 'Phulbani', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1313, 'Angul', 'ANGL,Anugul', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1314, 'Padmapur-Rayagada', 'Padampur', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1315, 'Gunupur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1316, 'Rayagada', 'RGDA', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1317, 'Madhabpur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1318, 'Umerkote', '', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1319, 'Kuchinda', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1320, 'Deogarh', '', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1321, 'Bundia', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1322, 'Dabugaon', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1323, 'Papadahandi', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1324, 'Mahidalpur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1325, 'Bhawanipatna', 'bwip', '2017-10-05 13:01:12', '2018-09-17 12:18:40', '', 1),
(1326, 'Daspalla', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1327, 'Brajaraj Nagar', 'Brajaraj Nagar', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1328, 'Gandhi Chak', 'Gandhi Chak', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1329, 'IBThermal', 'Thermal,IB Thermal', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1330, 'Nalda', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1331, 'Chandikhole', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1332, 'Kuakhia', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1333, 'Panikoili', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1334, 'JajpurRoad', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1335, 'Barbil', '', '2017-10-05 13:01:12', '2018-09-17 12:18:40', '', 1),
(1336, 'Joda', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1337, 'Keonjhar', '', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1338, 'Anandpur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1339, 'Tikabali', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1340, 'Choudwar', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1341, 'Nakhara', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1342, 'Gunupur ByPass', 'Gunupur ByPass', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1343, 'Sambalpur ByPass', 'Sambalpur ByPass', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1344, 'Junagarh', 'Junagad,Junagadh', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1345, 'Puri', 'PURI', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1346, 'Bhitarkanika', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1347, 'Betanati', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1348, 'Bhusan', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1349, 'Manguli ', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1350, 'Jeypore', 'Jaypore,Jeypure,JYP', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1351, 'Nabarangpur', 'NBG,Nawarangpur', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1352, 'Berhampur', 'BAM', '2017-10-05 13:01:12', '2018-09-17 12:18:40', '', 1),
(1353, 'Chhatrapur', 'CAP', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1354, 'Rambha', 'RBA', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1355, 'Kespur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1356, 'Balugaon', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1357, 'Tangi', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1358, 'Banei', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1359, 'Chandanpur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1360, 'Sakhigopal', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1361, 'Pipili', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1362, 'Barkote', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1363, 'Rengali', 'RGL', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1364, 'Asika', 'Aska', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1365, 'Digapahandi', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1366, 'Mohana', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1367, 'Hinjilikatu', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1368, 'Koraput', 'KRPU', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1369, 'Indravati', '', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1370, 'Lakshmipur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1371, 'Tentulikhunti', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1372, 'Adaba', '', '2017-10-05 13:01:12', '2021-02-04 04:58:31', '', 2),
(1373, 'Belpahar', 'BPH', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1374, 'Bhadrak', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1375, 'Kiakata', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1376, 'Bhanjanagar', '', '2017-10-05 13:01:12', '2018-09-17 12:18:40', '', 1),
(1377, 'Kalinga', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1378, 'Rajgangpur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1379, 'Kansbahal', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1380, 'Badagaon', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1381, 'Burla', '', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1383, 'Khatiguda', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1384, 'Sunabeda', '', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1385, 'Birmaharajpur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1386, 'Balangir', 'BLGR,Bolangir', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1387, 'Sonepur', '', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1388, 'Semiliguda', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1389, 'Gurundia', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1390, 'Koida', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1391, 'Rajamunda', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1392, 'Tata-Nagar', 'Tata', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1393, 'Rajkanika', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1394, 'Kendrapara', '', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1395, 'Salepur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1396, 'Pattamundai', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1397, 'Aul', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1398, 'Olaver', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1399, 'Jagatpur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1400, 'Champua', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1401, 'Bolani', 'Balani', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1402, 'Kiriburu', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1403, 'Tensa', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1404, 'Paradip', 'Paradip', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1405, 'Rairakhol', 'Redhakhol', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1406, 'Asureswar', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1407, 'Bhilai', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1408, 'Raipur-chhattisgarh', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1409, 'Hirakud', 'HKG', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1410, 'Kakatpur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1412, 'Chakradharpur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1413, 'Ranchi', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1414, 'Kantabanji', 'KBJ', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1415, 'RajaKhadial', 'Raj khariar, Raj Khadial', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1416, 'Patnagarh', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1417, 'Jaleswar', 'JER', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1418, 'Karanjia', '', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1419, 'Kaptipada', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1420, 'Udala', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1421, 'Rairangpur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1422, 'Sarat', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1423, 'Patsanipur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1424, 'Malkangiri', '', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1428, 'Nalco', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1429, 'Padampur-Bargarh', 'Padmapur, Padamapur', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1430, 'Kaipadar', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1431, 'Charampa', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1432, 'Bhandaripokhari', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1433, 'Dhamanagar', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1434, 'Khordha', 'Khurda,Khurdha', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1435, 'Mukhiguda', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1436, 'Kesinga', 'KSNG', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1437, 'Jaipatna', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1438, 'Kotapada', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1439, 'Kodala', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1440, 'Polasara', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1441, 'Buguda', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1442, 'Odagaon', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1443, 'Luhagudi', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1444, 'Raygad', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1445, 'Khajuripada', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1446, 'R.Udayagiri', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1447, 'Cheligarh', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1448, 'Mahendragarh', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1449, 'Chandragiri', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1450, 'Chandiput', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1451, 'Bramhanigaon', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1453, 'Raighar-Umerkote', 'Raigarh', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1454, 'Gorumahisani', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1455, 'Jagatsinghpur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1456, 'Sohela', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1457, 'Nuapada', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1458, 'Komna', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1459, 'Jashipur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1460, 'Rimuli', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1461, 'Deulia', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1462, 'Santragachhi', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1463, 'Kalighat', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1464, 'Singhpur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1465, 'Duharia', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1466, 'Rugudi', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1467, 'Baisinga', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1468, 'Betnoti', 'BTQ', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1469, 'Bisoi', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1470, 'Ghatagaon', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1471, 'Dhenkikote', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1472, 'chowringhee', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1473, 'Bangamunda', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1474, 'Machagaon', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1475, 'Paikamala', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1476, 'Godbhaga', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1477, 'Daringbadi', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1478, 'Raikia', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1479, 'Bombay Chawk', 'Bombay Chawk', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1481, 'Tiring', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1482, 'Dhenkikot', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1483, 'Jagannathprasad', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1484, 'Belaguntha', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1485, 'Benisagar', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1486, 'Thakarmunda', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1489, 'Bijatala', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1490, 'Kanaktura', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1491, 'Kalampur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1493, 'Gangpur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1494, 'Balia', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1497, 'Bhutamundi', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1498, 'Kujanga', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1499, 'Rahama', 'RHMA', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1500, 'Jeypure', 'Jeypore,Jaypore', '2018-07-21 10:36:32', '0000-00-00 00:00:00', '', 0),
(1501, 'Tarpur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1502, 'Raghunathpur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1503, 'Sinapali', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1504, 'Dharamgarh', 'Dharamgarh', '2019-03-18 11:20:12', '0000-00-00 00:00:00', '', 1),
(1505, 'Purusottampur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1506, 'Asansol', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1507, 'Bankura', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1508, 'Durgapur', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1509, 'Jamshedpur', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1510, 'Khallikot', 'KIT', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1511, 'Korba', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1512, 'Patamundai', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1513, 'Barpali', 'BRPL', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1514, 'Boudha', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1515, 'Khariar Road', 'KRAR,Khariar Road', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1516, 'Konark', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1517, 'Lanjigarh', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1518, 'Rajkot', '', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1519, 'Rampur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1520, 'Saintala', 'SFC', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1521, 'Sunakhala', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1522, 'Titilagarh', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1523, 'Ambapani', '', '2017-10-05 13:01:12', '2021-02-04 05:08:28', '', 2),
(1524, 'Baipariguda', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1525, 'Bilaspur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1526, 'Binika', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1527, 'Borigumma', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1528, 'Chatia', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1529, 'Dharmagarh', 'Dharamgarh', '2019-03-18 11:54:58', '2018-09-17 12:18:41', '', 0),
(1530, 'J K ROAD', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1531, 'J.I.T.M. Parlakhemundi', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1532, 'Jarka', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1533, 'Junagad', 'Junagarh,Junagadh', '2019-03-18 11:19:46', '0000-00-00 00:00:00', '', 0),
(1534, 'Junagadh', 'Junagarh,Junagad', '2019-03-18 11:19:35', '2018-09-17 12:18:41', '', 0),
(1535, 'Karsingh', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1536, 'Kenduguda', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1537, 'Kiribur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1538, 'Kotagarh', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1539, 'Motu', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1540, 'Muniguda', 'MNGD', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1541, 'Orissajharkhand Border', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1542, 'Purunapani', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1543, 'Sarankul', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1544, 'Tangi Chandpur', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1545, 'Utkella', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1546, 'Chaibasa', '', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(1547, 'Noamundi', '', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1548, 'Panposh', 'PPO', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1549, 'Chandabali', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1550, 'Thidi', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1551, 'Pirhat', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1552, 'Balikuda', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1553, 'Kandarpur', 'KDRP', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1554, 'Simdega', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1555, 'Jharpokharia', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1556, 'Raj khariar', 'Raja Khariar', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1557, 'Chatiguda', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1558, 'Narla', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1559, 'Kashipur', '', '0000-00-00 00:00:00', '2018-09-17 12:18:41', '', 1),
(1560, 'Tikiri', 'TKRI', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1561, 'JK Pur', 'J.K. Pur', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1562, 'Tumudibandha', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1563, 'Baliguda', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1564, 'Therubali', 'THV,Terubali', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1565, 'Bisam Cuttack', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1566, 'Athagarh', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1567, 'Narasingpur', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1568, 'Barsuan', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1569, 'Jhumpura', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1570, 'Palashpanga', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1571, 'Padmapur-Keonjhar', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1572, 'Palahada', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1573, 'Lahunipada', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1574, 'Jharadi', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1575, 'Bahalada', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1576, 'Nuagaon', 'NXN', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1577, 'Udayagiri', '', '2018-03-01 13:03:25', '2018-09-17 12:18:41', '', 1),
(1578, 'Moter', '', '2018-03-01 15:43:49', '0000-00-00 00:00:00', '', 1),
(1579, 'Gumuda', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1580, 'Podamari', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1581, 'Thelkoloi ', 'Telkoi', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1582, 'Damonjodi', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1583, 'Chipilima', 'Cipilima', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1584, 'Raigarh-Paralakhemundi', 'Raighar', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1585, 'Raighar-chhattisgarh', 'Raigarh', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1586, 'Kantamal', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1587, 'Palsaguda', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1588, 'Manmunda', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1589, 'Bausuni', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1590, 'Madhapur', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1591, 'Athamallick', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1592, 'Bhanjakia', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1593, 'Raruan', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1594, 'Sikuli', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1595, 'Thakurmunda', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1596, 'Anandapur', 'Anandpur', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1597, 'Jasbantapur', 'Jasabantapur', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1598, 'Laxmipur', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1599, 'Rakhukana-Rayagada', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1600, 'Narayanpatna-Rayagada', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1601, 'Demo', '', '2019-06-13 17:11:04', '0000-00-00 00:00:00', '', 0),
(1602, 'Dhanbad', '', '0000-00-00 00:00:00', '2018-09-17 12:18:41', '', 1),
(1603, 'Purulia', '', '0000-00-00 00:00:00', '2018-09-17 12:18:41', '', 1),
(1604, 'Jamda', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1605, 'Jagannathpur', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1606, 'Koksora', 'Kokasara', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1607, 'Ampani', 'Amapani,Ambapani', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1608, 'Maidalpur', 'Maidalpur', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1609, 'Ambikapur', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1610, 'Baghicha', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1611, 'Kunkuri', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1612, 'Tapkera', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1613, 'Bheden', 'Veden', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1614, 'Rengali Camp', 'Rengali Camp', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1615, 'Khetamundali', 'Khetamundali', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1616, 'Gereda', 'Gereda', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1617, 'Hyderabad', 'Hyd', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1618, 'Bengaluru', 'Bangalore', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1619, 'Ghens', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1620, 'Mandosil', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1621, 'Dunguripali', 'Dunguripaly, Dunguripalli', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1622, 'S Rampur', 'S.Rampur,S Rampur', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1623, 'Ulunda', 'Ulunda', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1624, 'Binka', 'binika', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1625, 'Kinjirkela', 'Kin', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1626, 'Vijayawada', 'Bijayawada', '2018-11-10 13:01:12', '2018-11-10 13:01:12', '', 1),
(1627, 'Visakhapatnam', 'Vizag', '2018-11-10 13:01:12', '2018-11-10 13:01:12', '', 1),
(1628, 'Balimela', 'Balimela', '2018-11-10 13:01:12', '2018-11-10 13:01:12', '', 1),
(1629, 'Chennai', 'Madras', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1630, 'Coimbatore', 'Coimbatore', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1631, 'Madurai', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1632, 'Ghatsila', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1633, 'Baharaguda', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1634, 'Jamsola', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1635, 'Basudevpur', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1636, 'CenturionUniversity-Parlakhemundi', 'Paralakhemundi', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1637, 'Balichandrapur', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1638, 'Rajnagar', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1639, 'Kundei-Umerkot', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1640, 'Laxmisagar', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1641, 'Nayapalli', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1648, 'Bomikhal', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1649, 'DASARATHPUR', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1650, 'Balipatpur', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1651, 'JHUMPURI', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1652, 'BHUSANDPUR', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1654, 'Uttara', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1655, 'Salapada', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1657, 'Rameswar-Khurdha', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1658, 'Nirakarpur', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1659, 'Tapang', '', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(1660, 'Gobindabali', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1661, 'Siliguri', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1662, 'M Rampur', 'M. Rampur,M Rampur', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1663, 'Yungthan', '', '2019-06-13 17:04:02', '0000-00-00 00:00:00', '', 0),
(1664, 'Lachung', '', '2019-06-13 17:16:33', '0000-00-00 00:00:00', '', 0),
(1665, 'Siphong', '', '2019-06-13 17:06:28', '0000-00-00 00:00:00', '', 0),
(1666, 'Syber', '', '2019-06-13 17:05:31', '0000-00-00 00:00:00', '', 0),
(1667, 'Tumba', 'Tumba', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1668, 'Chikiti', 'Chikiti', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1669, 'Bhismagiri', 'Bhismagiri', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1670, 'Sheragada', 'Seragada', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1671, 'Aska', 'aska,asika', '2019-06-13 18:11:28', '0000-00-00 00:00:00', '', 0),
(1672, 'Tikarpada', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1673, 'Dengaosta', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1674, 'Singipur', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1675, 'Simanbadi', 'Simanbadi', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1676, 'Pabuaria', 'Pabuaria', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1677, 'Ruchida-Bargarh', 'Ruchida', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1678, 'Banspal', 'Banspal', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1679, 'Oupada', 'Oupada', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1680, 'Kupari', 'Kupari', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1681, 'Khaira', 'Khaira', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1682, 'Khajuripada-Phulbani', 'Khajuripada', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1683, 'Ramjibanpur-WB', 'Ramjibanpur', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1684, 'Kharar-WB', 'Kharar', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1685, 'Ghatal-WB', 'Ghatal', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1686, 'MechoGram-WB', 'MechoGram', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1687, 'Jharigaon', 'jharigaon', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1688, 'Hatabharandi', 'Hatabharandi', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1689, 'Kantilo', 'Kantilo', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1690, 'Khandapada', 'Khandapada', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1691, 'Bokaro', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1692, 'Digha', 'Udaipur Beach, Digha Border', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1693, 'Agalpuraa', 'Agalpura', '0000-00-00 00:00:00', '2021-02-10 12:22:57', 'Admin', 1),
(1694, 'K Nuagaon', 'K.Nuagaon, K Nuagaon,', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1695, 'Gutingia', 'Gutingia', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1696, 'G Udayagiri', 'G.Udayagiri, G Udayagiri', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1697, 'Sarangada', 'Sarangada', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1698, 'Budaguda', 'Budaguda', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1699, 'Ramgiri', 'Ramgiri', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1700, 'Jiranga', 'Jiranga', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1701, 'Mandalsahi', 'Mandalsahi', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1702, 'Saramuli', 'SARAMULI', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1703, 'Gadapur', 'GADAPUR', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1704, 'Gajalbarhi', 'Gajalbadi', '2019-10-29 13:28:20', '0000-00-00 00:00:00', '', 1),
(1705, 'Asurabandha', 'ASURABANDHA', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1706, 'Gajalbadi', 'Gajalbarhi', '2019-10-29 13:29:44', '0000-00-00 00:00:00', '', 0),
(1707, 'Seragarh-Ganjam', 'Seragarh (Ganjam)', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1708, 'Paniganda', 'Paniganda', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1709, 'Sorada', 'Sorada', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1710, 'Madhapur-Kantilo', 'Madhapur-Kantilo', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1711, 'Rajkiari', 'Rajkiari', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1712, 'Gania', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1713, 'Jalespata', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1714, 'Bataguda', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1715, 'Kurtamgarh', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1716, 'Kalapathar', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1717, 'Phiringia', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1718, 'Boipariguda', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1719, 'Hyderabad-City', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1720, 'Bhadrachalam', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1721, 'Khammam', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1722, 'Kothagudam', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1723, 'MV79-Malkangiri', 'MV 79, Malkangiri', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1724, 'Kalimela-Malkangiri', 'Kalimela', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1725, 'Chandili-Kotapad', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1726, 'Kakiriguma', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1727, 'Pune', 'Pune', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1728, 'Mumbai', 'Bombay', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1729, 'SHIRIDI SAI TEMPLE', 'shiridi,sai,siridi', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1730, 'Govinda Palli-Malkangiri', 'Gobinda Palli-Malkangiri,Govindpali', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1731, 'Badagada', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1732, 'Balipadar', 'BALIPADAR', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(1733, 'Keonjhar Bypass', '', '2020-03-12 13:17:55', '0000-00-00 00:00:00', '', 0),
(1734, 'Chandaneswar', 'Chandaneswar', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1735, 'Kamarda-Baleswar', 'Kamarda', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1736, 'Thanachak-Baleswar', 'Thanachak', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1737, 'Baliapal', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1738, 'Bamur', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1739, 'Boinda', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1740, 'Rafukana', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1741, 'Khairaput', 'Khairaput-Malkangiri', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1742, 'Guma', 'Gumma', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1743, 'Chandahandi', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1744, 'Haladiagada', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1745, 'Nalco Colony', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1746, 'FCI', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1747, 'Samal', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1748, 'Khamar', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1749, 'Remuli', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1750, 'Gopili', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1751, 'Gurandi', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1752, 'Garabandha', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1753, 'Adaspur', '', '0000-00-00 00:00:00', '2021-02-04 05:08:21', '', 2),
(1754, 'Niali', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1755, 'Mathili', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1756, 'Udaypur-Balasore', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1757, 'Chitrada-Baripada', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1758, 'AmardaRoad-Baripada', 'Amarda Road', '0000-00-00 00:00:00', '2021-02-06 06:39:33', '', 1),
(1759, 'GandhiChhak-Balasore', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1760, 'Kansabahal', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1761, 'Tirupati', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1762, 'Padmapur-Gunupur', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1764, 'Tusura-Balangir', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1765, 'Gudvela', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1766, 'Jamut', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1767, 'Khuntigora', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1768, 'Saraipali-Chhattisgarh', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1769, 'Basna-Chhattisgarh', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1770, 'Sankara-Chhattisgarh', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1771, 'Pithora-Chhattisgarh', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1772, 'Jhalap-Chhattisgarh', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1773, 'Patewa-Chhattisgarh', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1774, 'Arang-Chhattisgarh', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(1775, 'DumaDuma', 'DumDum', '2021-02-10 12:04:15', '2021-02-10 12:04:24', 'Admin', 1),
(1776, 'demo_datas', NULL, '2021-02-25 13:46:10', '2021-02-25 13:46:18', 'Admin', 0),
(1777, 'chandrakanta', NULL, '2021-02-27 06:15:15', '2021-02-27 06:15:15', 'Admin', 0),
(1778, 'Rourkela 12', 'RKL12', '2021-02-27 06:39:20', '2021-02-27 06:41:20', 'Admin', 1),
(1779, 'Demo 001', NULL, '2021-03-13 07:19:59', '2021-03-13 07:20:15', 'Admin', 1),
(1780, 'fffff', 'holi', '2021-04-10 14:20:58', '2021-04-10 14:20:58', 'admin', 0),
(1781, 'fffff', 'holi', '2021-04-12 05:40:56', '2021-04-12 05:40:56', 'admin', 0),
(1782, 'examp2', 'fty', '2021-05-08 09:33:35', '2021-05-08 10:01:40', 'admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `locationcode`
--

CREATE TABLE `locationcode` (
  `id` int UNSIGNED NOT NULL,
  `location_id` int UNSIGNED NOT NULL,
  `type` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '0-Odbus 1- red bus 2-dolphin 3-bus india',
  `providerid` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locationcode`
--

INSERT INTO `locationcode` (`id`, `location_id`, `type`, `providerid`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(1, 1291, 1, '501', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(2, 1292, 1, '502', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(3, 1293, 1, '516', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(4, 1294, 1, '541', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(5, 1295, 1, '2567', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(6, 1297, 1, '28689', '2017-10-05 13:01:12', '2018-09-17 12:18:40', '', 1),
(7, 1300, 1, '2569', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(8, 1301, 1, '2566', '2017-10-05 13:01:12', '2018-09-17 12:18:40', '', 1),
(9, 1302, 1, '2568', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(10, 1303, 1, '504', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(11, 1304, 1, '1308', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(12, 1305, 1, '1121', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(13, 1309, 1, '544', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(14, 1313, 1, '511', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(15, 1318, 1, '27301', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(16, 1320, 1, '27442', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(17, 1325, 1, '534', '2017-10-05 13:01:12', '2018-09-17 12:18:40', '', 1),
(18, 1335, 1, '27300', '2017-10-05 13:01:12', '2018-09-17 12:18:40', '', 1),
(19, 1337, 1, '24707', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(20, 1345, 1, '503', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(21, 1350, 1, '24744', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(22, 1351, 1, '25305', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(23, 1352, 1, '500', '2017-10-05 13:01:12', '2018-09-17 12:18:40', '', 1),
(24, 1368, 1, '3941', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(25, 1369, 1, '25307', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(26, 1376, 1, '25285', '2017-10-05 13:01:12', '2018-09-17 12:18:40', '', 1),
(27, 1381, 1, '540', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(28, 1384, 1, '24748', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(29, 1386, 1, '537', '2017-10-05 13:01:12', '0000-00-00 00:00:00', '', 1),
(30, 1387, 1, '539', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(31, 1392, 1, '1896', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(32, 1394, 1, '523', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(33, 1404, 1, '530', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(34, 1407, 1, '1118', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(35, 1408, 1, '1116', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(36, 1413, 1, '1401', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(37, 1418, 1, '25230', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(38, 1424, 1, '3945', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(39, 1436, 1, '535', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(40, 1504, 1, '546', '2019-03-18 11:20:12', '0000-00-00 00:00:00', '', 1),
(41, 1506, 0, '1062', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(42, 1507, 1, '26251', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(43, 1508, 1, '1060', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(44, 1509, 1, '1402', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(45, 1511, 1, '27096', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(46, 1518, 1, '1158', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(47, 1534, 1, '1199', '2019-03-18 11:19:35', '2018-09-17 12:18:41', '', 0),
(48, 1546, 1, '27766', '2017-10-05 13:01:12', '2018-09-17 12:18:41', '', 1),
(49, 1559, 1, '27328', '0000-00-00 00:00:00', '2018-09-17 12:18:41', '', 1),
(50, 1577, 1, '2414', '2018-03-01 13:03:25', '2018-09-17 12:18:41', '', 1),
(51, 1602, 1, '1890', '0000-00-00 00:00:00', '2018-09-17 12:18:41', '', 1),
(52, 1603, 1, '26254', '0000-00-00 00:00:00', '2018-09-17 12:18:41', '', 1),
(53, 1617, 0, '6', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(54, 1618, 0, '3', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(55, 1626, 0, '11', '2018-11-10 13:01:12', '2018-11-10 13:01:12', '', 1),
(56, 1627, 0, '17', '2018-11-10 13:01:12', '2018-11-10 13:01:12', '', 1),
(57, 1628, 1, '27299', '2018-11-10 13:01:12', '2018-11-10 13:01:12', '', 1),
(58, 1629, 0, '102', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(59, 1630, 0, '109', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(60, 1631, 1, '104', '2017-10-05 13:01:12', '2018-09-19 11:12:29', '', 1),
(61, 1661, 1, '1293', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1),
(62, 1780, 4, '122', '2021-04-10 14:20:58', '2021-04-10 14:20:58', 'admin', 0),
(63, 1780, 5, '123', '2021-04-10 14:20:58', '2021-04-10 14:20:58', 'admin', 0),
(64, 1781, 4, '122', '2021-04-12 05:40:57', '2021-04-12 05:40:57', 'admin', 0),
(65, 1781, 5, '123', '2021-04-12 05:40:57', '2021-04-12 05:40:57', 'admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `owner_fare`
--

CREATE TABLE `owner_fare` (
  `id` int UNSIGNED NOT NULL,
  `bus_operator_id` int DEFAULT NULL,
  `source_id` int DEFAULT NULL,
  `destination_id` int DEFAULT NULL,
  `date` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `seater_price` double NOT NULL,
  `sleeper_price` double NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `owner_fare`
--

INSERT INTO `owner_fare` (`id`, `bus_operator_id`, `source_id`, `destination_id`, `date`, `seater_price`, `sleeper_price`, `reason`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(1, 1, NULL, NULL, '2021-03-30', 44, 55, 'null', '2021-03-30 08:03:58', '2021-04-03 06:41:55', 'Admin', 2),
(3, 1, NULL, NULL, '2021-03-31', 66, 99, 'mmm', '2021-03-31 03:24:51', '2021-04-03 01:35:53', 'Admin', 2),
(5, 1, NULL, NULL, '2021-04-02', 1, 2, 'null', '2021-04-02 12:11:34', '2021-04-02 12:11:34', 'Admin', 0),
(6, 1, NULL, NULL, '2021-04-12', 555, 777, 'null', '2021-04-03 01:34:23', '2021-04-03 01:34:23', 'Admin', 0),
(7, NULL, 1291, 1304, '2021-04-06', 333, 444, 'null', '2021-04-06 08:46:27', '2021-04-06 08:46:27', 'Admin', 0),
(8, NULL, 1291, 1304, '2021-04-28', 333, 444, 'null', '2021-04-06 08:47:25', '2021-04-06 08:47:25', 'Admin', 0),
(9, 1, NULL, NULL, '2021-04-30', 111, 222, 'null', '2021-04-06 08:57:42', '2021-04-06 08:57:42', 'Admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pre_booking`
--

CREATE TABLE `pre_booking` (
  `id` int UNSIGNED NOT NULL,
  `transaction_id` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `j_day` int NOT NULL DEFAULT '0' COMMENT 'journey day | 0-same day 1-nxt day',
  `journey_dt` date NOT NULL,
  `bus_info` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'json data',
  `customer_info` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'json data',
  `total_fare` double(8,2) UNSIGNED NOT NULL,
  `is_coupon` int NOT NULL DEFAULT '0' COMMENT '0-no 1-yes',
  `coupon_code` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_discount` decimal(9,2) DEFAULT NULL,
  `discounted_fare` decimal(9,2) DEFAULT NULL,
  `customer_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pre_booking_detail`
--

CREATE TABLE `pre_booking_detail` (
  `id` int NOT NULL,
  `pre_booking_id` int UNSIGNED NOT NULL,
  `journey_date` date NOT NULL,
  `j_day` int NOT NULL DEFAULT '0' COMMENT 'journey day | 0-same day 1-nxt day',
  `bus_id` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seat_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reason`
--

CREATE TABLE `reason` (
  `id` int NOT NULL,
  `name` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int UNSIGNED NOT NULL,
  `pnr` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `customer_id` int NOT NULL,
  `reference_key` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'link for email',
  `rating_overall` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'out of 5',
  `rating_comfort` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'out of 5',
  `rating_clean` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'out of 5',
  `rating_behavior` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'out of 5',
  `rating_timing` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'out of 5',
  `comments` varchar(2500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `safety`
--

CREATE TABLE `safety` (
  `id` int NOT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` mediumblob,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `safety`
--

INSERT INTO `safety` (`id`, `name`, `icon`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(3, 'Fire Safety', NULL, '2021-06-03 13:40:24', '2021-06-03 13:40:24', 'admin', 1),
(4, 'Foot Safety', NULL, '2021-06-03 14:05:56', '2021-06-03 14:07:07', 'Admin', 1),
(5, 'Road Safety', NULL, '2021-06-03 14:34:21', '2021-06-03 14:37:46', 'Admin', 1),
(6, 'Demo Test11', NULL, '2021-06-05 10:36:47', '2021-06-05 14:00:25', 'Admin', 0);
INSERT INTO `safety` (`id`, `name`, `icon`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(7, 'demo test 002', 0x2f396a2f34414151536b5a4a52674142416741415a41426b4141442f374141525248566a61336b414151414541414141504141412f2b45444c576830644841364c793975637935685a4739695a53356a62323076654746774c7a45754d433841504439346347466a6132563049474a6c5a326c7550534c767537386949476c6b50534a584e553077545842445a576870534870795a564e36546c526a656d746a4f575169507a34675048673665473177625756305953423462577875637a703450534a685a4739695a547075637a70745a5852684c7949676544703462584230617a306951575276596d55675745315149454e76636d55674e5334324c574d774e6a63674e7a6b754d5455334e7a51334c4341794d4445314c7a417a4c7a4d774c54497a4f6a51774f6a517949434167494341674943416950694138636d526d4f6c4a455269423462577875637a70795a475939496d6830644841364c79393364336375647a4d7562334a6e4c7a45354f546b764d4449764d6a4974636d526d4c584e35626e52686543317563794d6950694138636d526d4f6b526c63324e79615842306157397549484a6b5a6a7068596d3931644430694969423462577875637a703462584139496d6830644841364c793975637935685a4739695a53356a62323076654746774c7a45754d433869494868746247357a4f6e68746345314e50534a6f644852774f693876626e4d7559575276596d5575593239744c336868634338784c6a4176625730764969423462577875637a707a64464a6c5a6a30696148523063446f764c32357a4c6d466b62324a6c4c6d4e7662533934595841764d5334774c334e556558426c4c314a6c63323931636d4e6c556d566d49794967654731774f6b4e795a57463062334a556232397350534a425a4739695a5342516147393062334e6f6233416751304d674d6a41784e5342586157356b6233647a496942346258424e5454704a626e4e305957356a5a556c4550534a346258417561576c6b4f6a677a524445344d546b344d7a5577516a457852546c424e54684452454e4552544d774e3049304d6a4d33496942346258424e5454704562324e316257567564456c4550534a34625841755a476c6b4f6a677a524445344d546b354d7a5577516a457852546c424e54684452454e4552544d774e3049304d6a4d33496a3467504868746345314e4f6b526c636d6c325a575247636d397449484e30556d566d4f6d6c7563335268626d4e6c53555139496e687463433570615751364f444e454d5467784f54597a4e5442434d5446464f5545314f454e45513052464d7a4133516a51794d7a636949484e30556d566d4f6d5276593356745a57353053555139496e68746343356b615751364f444e454d5467784f54637a4e5442434d5446464f5545314f454e45513052464d7a4133516a51794d7a63694c7a3467504339795a4759365247567a59334a70634852706232342b49447776636d526d4f6c4a45526a3467504339344f6e68746347316c6447452b4944772f654842685932746c6443426c626d5139496e4969507a372f3767414f51575276596d55415a4d4141414141422f397341684141474241514542515147425155474351594642676b4c434159474341734d43676f4c43676f4d4541774d4441774d4442414d446738514477344d45784d5546424d544842736247787766487838664878386648783866415163484277304d4452675145426761465245564768386648783866487838664878386648783866487838664878386648783866487838664878386648783866487838664878386648783866487838664878386648782f2f7741415243414851417451444152454141684542417845422f38514170774141416749444151454141414141414141414141414141414545425149444267634941514542415145424151454141414141414141414141414141514944424155474541414341514d434177554542775945424159434177454241674d414551516842544642456c466849684d4763594579464a476873554a535967667777584b4349785852345a497a38554e544a4b4b7959334f44466a524577744b5446784542415141434167494242414942417751434177414141414552416945444d5151535156456942544954595846434649475273564a694936487846662f61414177444151414345514d52414438416c555555446f4457674b416f44766f485252514641443761494b4b43514b49307a35554d4b39556a42523338614372794e366b636c635a625830367a2f6855745a74517a464c4d33564b78596e55337149337877714c57466a54433462516f414e744c5547517679464650335547534b7a4f45555859337376736f4a635745427249626e3849304656634a4b68416f364c4265524843714b6a64386155734a7357337a41595434334947654d654a442f414f346c536f6c72752b484a456a78686d647743494170447166777466346255567165584c6e49566e45434532456145466a37572f77414b4979323648476c67453052416a5a6d42412b496c535650555472786f7369656f564c4b7132424845634e4f303152727959444e43794b62534168346d3457645464614373326d5a594d7954464136594d734e6c34696e5470653973694c58384c2b4b6f5263657967317a35475043414a574855654344566a2f4b4b47555a7038755877786a356444774a4161516a7548426170797a68775172467962534d4c47566a314f527831765177654c4c636b4e6f77596f36396a4b62554573332f79716771554641694b416f474b4255446f4e636a57556d67357a645a7571517266757247315a7456366f306a72456f4a5a79454139707457635a485a4b696f716f75696f41712b77433164576d5646416f676f43786f6f74524374784e4269527a6f4d537442716d466f6d493474705151664b6f68474955434d57745441424672776f44795432554159653667504a377141386b3042354270675a43447446426d7550335663446173494134565274574f6732716c714c68735652796f4d71416f4453696a57675641714964464b6943675646464171494b4b4b425551554252534e4171412f343052757142305563744b49422b786f4367644646415542616944682f6a51424946426f6e79345951544977573341637a517456632b38537553754f765350784869666455797a61696555386a6455724669655a4e366d55626b6956624143314d4b326757476c4178514d6138723056736a696b6b305253653038414b6f6c78594369786c505566776a51557775454463347063535a4a34415359627a777065355a56306d692f306d346f6934696b53574e4a596d366f3546446f77357177765656466c7a385a435934564d7a676d36523273436530384b4757674c6c3545714e4951466a4a5a59554868444545585a6a554a6c4c5446484751334a346761413077595a7a593653774e434c4a3144774d5075734e566236616f7139736e4d4f347445773649382f716b564f535a5551365a6b2f6d4136716b497543534263433574777167424a414a466a7a486651564f646a46386f69423153654e6c79344734685a446448527261394d69314b4a4454356370433345462b4b786e72632f7a4557464552705a344d4f54487568435454726a54796b6b756a4f4355366966784e59554675714b747742626c666e5655433968666a7a6f4930362b586b724950686d736a647a7143565076476c424b5275705165664130446f44374b416f466254736f48514671416f493257345643657755484c5a546c7053644f504f7556724b547363506d3769726b58574653353976426131724f5436756c417262522f5851464646454f696c524166726f46616759554541647073665a51614d704c4d453467616a76717752796e303051696c5252306132745141546851506f6f4479782f6e51486c6730444343675951646c426b456f4d676c426b4530716a4d4b4b444d4367644155425146464671426371425551364b564546714255556439454c323042514936635064514f696c524262736f6f6f4d61494e622f5851622b56514847674b416f436764464641582b7169455451614a38324341586b59447341346d6861713539336e6c4a5742656b4854714f70715773326f6f685a323670474c4d654a4f76323145625652514e414254417a4139776f724f67425162496f5a5a445a464a486164414b71345935666d595a563545447168447341623955594e6e74337251584936656b644248535264534f424246787771715342774358494e39624157416f494f586c5179644d555447535a48444b7969367059326271622b476f5a5234647548515949576b474e3145724231455272633349423439506451776e523443496e54316441745964414141503736474548446d6c78393038755532544c764777766f7552434f4176796b5457694c63692f455871714e44374b436f336a466c4d683867685a5a534a38566a6f46796f5263412f2b346d6c53695846752b4e4c436a716a2b633448566a64506a5275617466686268564d74552b524f555a3535426a51416746554e324e79425a6e2f77715a52496877565145614944717972784a2f4d786f7545684652515656656b41324e714376336643697945614f556b515a692f4c7a4d4f4b7354654751643674536a505a4d32624c3239546b414c6d514d32506d4b4f55305a73334c373378443231534a327437385165585a5151397a796f493861534d6b764f562f70516f4f7079344e31304835714653494762525873474942594467477472516271416f436756413641306f455459554662756b765445327574536c726e474f70506272584e6c65656e59656e476c6d49316c6670422f4b672f7872657334574c61744b644155446f43696c5242514146426d6f2b48764e426f7943576c593867626652565271496f4d656b31465070496f414c514d41304261675946412b6e33396c412b6d6759576779412b6d675946555a41555537555155556371494c4767564155555837614956714b56454f696c5242514c32304252536f68666251426f436756416673614230557462384b59527571416f48514b676441555569514f644552386a4f6767424c7541655147702b69686171703932794a695668586f55366452314e53314d6f6f684c4573354c4d6453536231475735564134443255566c79396c426b427a34554447677565584367476b56564c4d624262467238675461356f4c534862346b7358506d4e79423443726872446572715346554777754c6757414930716a546e4a31774d774632687534767749745a313936304b68596d546c59754f4d59516d614e4e4d655973414448785550665546616d55797a386e4b79745a6d4c4c2b42627167392f467144554a30677a49494a4977734c4f595a42795752686550683931714c4675525957476e494163716f42653276486e51566d37346a53616f656835536f562f777a786e716862332f445571564d774d74637a45697946485358424569633164545a3150736169734a74787834324b52417a796a696b646941667a4e77465574527a38316b796f5a414149323631695158554d4259466e505a554f557863652b736a584f6c31476c424733544667654168312f37655147484a4135492b6766326f314c45777932624b6d6d772f4c79542f774235694d63664a767a64414c502f4144725a7153716d6e513349346d784e55597a51705043384c2f444943704934676e6e376a51552b4f4d75484f664e6a547a467945454f34774b514747524153717a4c66513953364e3771694a54795a6b37644c76354b66394b4856794f39755875716e4e626f4d4a49564e67496c4a753174575038544769776d4a69796c55456d475a53596964534854566c39363630453154314b434f6647674b416f433141665651486451597362412f58515565387961425237367a7455716d61774637634e51425745645a677765526877513231564154375471613653634e4e2f477148525252446f6f6f43674b49414b444b2f53765632587437614350622f4f7146616758534b674f6b55443661426866706f433141774b4267433941576f4d725542616759464136716755514367505a514246514246554969674b4b44514b314147674b425551714b5645426f43674b4b526f676f6f6f46335551714b33314546413642554157416f4975526e343841505777767955616d6861724a3930795a695669486c70323837566d314d6f6f6975537a6b73334d6b33715a5a62514c614155475667506656557833615541414f664138364352446954793273765376346d30464d4b6e77344d4d6469336a59634365462b367268574f355936795935667044474945736f4772526b5764666f705374653135612b5163616152664f78674675534231526b58523965366b49796b33564353754b706d594733576643675038523431544b4a4d356670624d6c366c4c4b76514c71696c6a595846544b4c4e4d534e44642f45313962384165366d4662516233467247397263716f72643478456c547259394b79415179762b456b33696b2f6b6570594a4f325a625a574772796a70794979597368657952445a767034306c456a7049637466546953665a56454c4e7a4d655747584869764f3744704a512b4654653459742b55363052705843575233614d4e61553955775669736274594173514b6934625a73623558484d6967474e4c47574e56745a4c324c4c626d7647674e6b6e6b4f504a687a4e315a4f47356a642f7741614e646f3550356c70434a354142367232307365793155446f7271794f4c71774b734f3045574e425252795067376b6b307257535332486d756541645266486d5038536e704e543669316d7a7364574d612f774261582f705232596a326e674b706c6f5a38756469724d5977654d4d4a7533387a6e39314d6a6646686859315452455557434a783766694e5177336f696f746c414137754e55425253317a72666744774642727a49476d78797145435a5348674a35534962722f6851474a4f737361756f7372674d6f5045486752376a516236416f43674b416f4d5a445a61446d7432653874757a6c374b78745761695973506e5a6345504a3355483241334e5a6b7a534f754a424a5061613674416361423055613051446a52546f4155515555586f67633642657a552b2b7242727451424641756d6d5157355641774b42326f433141554474514f67414b42304251484f71446e5341765148736f48514641714b5645464163364b5641752b6944376141494641714b56454641554374514641726155446f6f6f4e745242776f4174625367695a4f34593841495a67572f434e54554c5658507565565063526a79304f6c2b64716c724e714d49674775784a626d547255794e67303461643141786133615251506a564476323865586651536f4d4765617849364550336d343077736a646c37636b654f586a5a6a304338674f743134456a3256634669547475515a73554b35426c68506c795734456761482b595569797437694a4c7975516f48336d4e6750707169452b36717a4559696561526347527268422f6a5247754862673863617569743043776b59432b75756c544334546f7365425459414d366758754f462b775545666463644a49444977756967704d42783874744c36666750697059566e746d5338324e30536e2f414c69416d4766765a526f33387936315a534a4c466751514c6a51473141534a484a473653676555344b7666515749747a6f4b62456249696c624b685a4a424d416b364d624b37526b7173794d4f3044576f4e37523547514330356156414c6d4e41565141636564326f6a4c416543576566485a414442304e476f7346614e78645841486670534b7353516f46687077414655414b73756f75704242423445485457676f533339757a3435696636634a584679536565504b627753612f6762776d7039555835484557376231565258334346543563494f544b756843573651667a4d644b4757694e5a705a5a5a4a4656336c436f593046305656766f78507848576f6a484e687959635a347351496b38694e38763069796d5252634966347156624576623871444b776f636e484854444d6f5a56356738475539366e53714a46415543594d62426566453836434e4a6d5938546c41576e6e2f77436e474f6f6a326e6774444c5668435a556b61554b73736b72796d4a54314246613368767a37545157414949754f426f43674b41316f4367776c506850736f4f573346696367673875646339764c4e6264696a44376a313275496b5a683754346175766c5a35644633317454765146416d6270734c584e41684a636a69427a71344d733667644646417871523255516d314e366f787452515270555176746f4331364267554261674b42326f41436764464641696149784a4e41756f38366f5a665367585859387143763366664974766942432b624d78736958734e4f4e7a5574776c75465269657649705a7a424c69734a4c5842547843726d4d2f4b744754367833434f52705968484c4344595167456b4438784653307531644e744f644a754743755530506b67337570494f6f396c584c557555756969674b4b4e614255436f6831417532714639464171416f43675651426f433274417673374b6f326c674b67695a5735593041494c6454636c4770715a4658507557565064562f706f657a6a53316d3148434c65374573334d6d73354756394f377346416674616779414f6c71426b3274663671446438766b4c455a4447336c67584c6467396c584268596258486a76414a516f4d796b713939534746574c453431565979534a474f71526c575067785932466a5155734d6a78794e4a68535753355142314a446f70384a393349316c47394e75794d6c784c6c4f5874714132696a324b4b706a37742b56694c486a4d38643236426430766f796665467532314d4c575731546c3863777333584a6a6b4b572f45684630663372534554475a56467a374352564151704242415a574242484967693171436d6a5a38444f4a6271614941513548456e6f34777932485a384c565071695a4c756974635971475732686c6136786a366454564d6f7752387164597035524c4b564c69492b46416f49462b6b63616d5447566846695249426364567441434146467551416f72634461316a773457346155464c6d6639686d4a6c4c634a6a334c714c6d2b4c4d77442f77442b622b4b694c706c444167324b6b6375466a714b716d51414c6e5141616b3641436771733573544d6e5245744d6853534c4b49423873784d4c394a6274367546536d5353416c49345a70354a59525a4931633242734e417a4165493052763277342b56694c4d6f3656444d6867305549794d565a574135365569784f41494e67414641304130716a586b77744e4379496253697a524e324f7075744257625a4d494d2b54472b48487a7732566a4b6441736f4e73694c5838336a2b6d70426367486c56455354634951356a6842795a516246593956422f4d3538496f5a61534d6e494a575a7a30383449435174767a4f6461435244694b694241416966394f4d5748764e515962684134782f4e7831486e343538794e52703142666a5454386131527678706b6c6a566b50556a71486a6274566865673230425146415547456f757442797534676a4a49503761317a323873314e394f4b4f764b5938656c462b6b6b31725659753731705446416362665651613541626732355659424c46686657694e7452546f43696d446f543236436945616f56415771425544416f4331415544745146464847694367524e42697a572f665647683567766455476c3874516454616d544b4c4e7645455a737a61396731706c474d4f354c4f724d70496a554573783034565a5579356e636337356d655849662f414731754555396735567a747a5572443039746a354755636c6751436233476d6c58575a523173337033626369505242444d50686c6a304950665737476f3162786b376e6937656d4a44414934724258796b50556f417439304336336f584f4f46716d52416d504737796f415648694c43784a48746f314c77306274757365333765325949326e41495656515875574e687262536957385a53636153535446696d6d6a4d447967487957494c44546870555756747171576c4174663836494b416f45614247674e666f6f43674b415030564171413736436a6e33444c6e4a41506c703244695237617a617a6c48434b70756453654a4f7071576a4c71754e4b4956786654746f4d683974464d66583330457246777063693555685542737a4855337179474d725448774d65477841366e48336d315030565a47704d4a424b3273326f4f6842344548513152554935322f4c6457444e41774159714c6b44696a574830476f6e68736d336152795578497a7270357a676744324c784e4d6a57753335557639616138386731437551423746586851776b625a4a484a35696b41794951796d33464734663455574a7a423767716452787677716a49456a5767706d5039767a673137514c3457502f414b4568384a2f2b4e366e3152627974476945794d465163574a4148627a71716750755a627734636655426f4a6e757165346357715a516f4d584b64326c6c636c3373433744704155587371714b4c457950466951334e33596332317451564f35525069355335454b3361416e4a6755633041433545492f6c38516f6935563435596c654e7278794b4752687a56686355556c55676b6b4158414241344569676a376c4770786a4d796868426373702b394777365a453137566f5647784a633346785678504c453568756b453561774d592b4872487858556155686b31676d796e506e796563564944494430784b534c2f434f5076706c4d5a545578496c41367646625143316c48754646777a7949566e6765456e70446977493036534e5659657730465474383751626d5663644d653558363148424d7945576b482f77416944713931434c725778746f54774e5548496b3842784a30416f4b6d6547484b6d6b47504b564d4d715478546f4c684a37454f4277366c5a6669486651625448506b3345726d5665635556306948385458756168684a6a78465641725743446845673656483056527641414853414142774146685150396a514975464e78715164414f4e4241784c5932544c6943335170382f472f39747965706635482b32677364434152774f6f716771414e71416f45347574427a4f38776c5a756f4451316a614a5733303234456d556e4d71724164774a4658576b586c3630703042514d47674141446f4c586f4851503355425254747262734776744e574956416371674b416f43674150716f433274415555554251464559456b554769615470426f4b6a4d7a6c6a76725574776c71742b5a6e795a5669513941636851536546367a6e4e5245795a38534463477735455a41535678386b73474d306f734f6c55476f7179524c634d7a6e51747453523473676c61646d36354631466c4a4274373662584577537138774e6b7a726a7071716b467538316963307364727447436d504371415749477464704d4c49587158314a7476707a44686e7a65706d794a4248444567425938326133596f34306b797475456e6174363233646356636a436d57614a68625136673968423455735362532b4652366b39484c75574f7832334b626238734d485870414d544d4e66476e2b465a7361736c387465303737767a35696250753232684a6f31466e6875593541756e574c38744b7a4e726e4668654b3643484b68795a476b56777a49544756424868495069476e4f7479354569716f6f455252426254374b67567564554671424767564155425252554374564255527a5a636e68584e6b726b2b2b67597665714d6b556e51416d2b67413170676b54386261706e316b506c6f65584d6972497369786a776352554b65574756685a6964536231634b67344c4e695a723473687572454b473753526447393430704f434c5357614b464338724246484e6a61714b366264336677347161482f414a7267676679714e5455544c5869786e496e496b6c4c534d76563147326f553273505a51576363456351384367742b49366b305673756549304e5556655466437a684f6f2f706d37674453364d514a562f6c2b4b7052613342467762676745456343447265714d486b6a686a4c53737149436453624367724d724b6a7935492b694d6d4a4377615678594d724378514b654e366c544f5468327a71433355694e525a424b53775544384b6b3058444863496369446f614a69787631784567433770346a47317678727770596c34576b4d3063384b54784736534b4755383747696e633952423457424246426f7a6f586b67366f68656545695745486d796733552f7872346146514e6f7a6365414e6950494567735a73463349414d4c366c4e6676527470616b2b786c4a66632b734835564f70522f7a354c7167396c39576f5a616c7870386c6c65596d577842557634597752773655484768684d5843694b6b53737a6b3857424b322f6874517772736433784e30426c506879534d6249504143644154444a2f386961552b7152636b58754b4b513131344367716433776d6b59694d39445a4256345a4465795a5541366b622b5a525930714a4b37686b504774735a6b6e742f55567a306f6874723476764432565652386d574e596a506d532b616973716c4234596c4c6b4b74774f3838366d55574334694b414a4348433642414c4950634b71742b67466859446b414c4165796756413642636953624332704f67413736434763324e6d595971664d506656776252412f786e3931444c4750486b4f54387a504a3179684447694b4f6c455669433172366b7459616d676d784e6357374f48736f4d7a514b674b416f49473559596d69494131746f616c6d5573632f697a4e742b656b7267394175736f2f49326874574a785477366934734c57494942566871434472705852526567594a34304144656779426f48514641416a6965416f485932313438366f4b41397451484f674b4b4c555155555542796f672f59554162554359324642476d6d4333463643757a63734c475344727971573453317a7338786c636b6b6b5835317a7479684b5556575a32436f6f4a5a79656b414158766330673554314a367553535348447863694b5445554d7235305346353036785a75676d7736756e773372704a66717861754e703366594a385348473236634b794b4934735a2f433443693274367a744c4b73737736545a6342593244745975534353652b72724d4b366a46565263736241416b6e754662616551626c752b332b702f5657526b6270492b506834524d654c6a794170614e5439386645476b6256716d31736e4447336e4652634263754c66466a32544d497a5a35694963614a534656415264707675394b6a6c78707262395758724c376d4d5765484362717963316c4264554864784e584c704c684d50796d64455970314242754471515154706f774e366c6a55726c55394335757a2b6f6f743532624c65584636725a5733797354314b515153477634694c334639616d7378776c3535646975524650346b4e774e434f4242373631685979316f45614b564155436f67714174514b314172554272514b674e4b6f35734b434e644f64633247614953514143536541417561534c496e592b307a50713543446c63585030565a466b626352526a5a70676b415055414661326c2b5031315a4d4c4f466952653175584b714d4a736947434c716d63494f517672394642565a4d347a4a305a4969455653685a7447636b6772623256457a6c766932706e59535a444d62445173537a41652b6934574545454d51486c714c2f694f704e555164776a65444a544b694770505541427031416549667a72557056696a6f364b3647367541796e754f74554159395a556a516344335545626378474d55737a425869386366567a49304b2f7a44536769527a356b4d5167695a4367306a5a6753367164656b676362564a546c726d7870554b793548564a4935365935484949446e345673506876536f7364764544343063364c346e48694a314959614d5063615255686d4134366b57763341315271796f505067614965467a5a6f322f43366d366e36616855485a38674c492b4d52304c49476d6854384a42744e48722b4636543745574d7373554b46355843494f4c4d5142396442426c334f56682f7742704741764b6559454c66745642346d6f685975464a354b526b585653574c79414337456c69775564356f754769624a6b7873794a7077504b69635235497434656d62534b5958356457686f6936494e794478476c56574149595848764859616772393378466d533550537377454d6a6a37724133686b2f6c656c4b335947347050676962495a59706f7955796c5967644569614d43443949704c6b79786263586b4a474a4631412f382b5336703768385456544c4350476e6d6b575a334d7a7143456b62777872635750516f714745744d654948786b794d4e624557556536673037706a52537745797265466c4d4f514270654a394c2f794e725677566a7332544e4a6974426b6d2b5a6873636649504e696f7572386676705a7168452b71457a4b716c6d495656314c456741665451524733447a4c6a456a383463444d31316a48764f726536686c724f4f3070445a556e6e637847423078442b556366665177336741414141414157414173414f3631426b44377142706453435270774e42763555436f43674b4245416978316f4b375032794f6453514c4e794934314c4d6c6946695a4d2b3367515a4b732b4944344855457448632f2b57704c6a796b3457794f6a7869534e6738523144716267317057576834554431306f48514f39414133346361444a526632446e326d6b444655465147702f665146415542514c3255425252514671494b4b776332556d694b6663636e6f42505a657065455547546d504b53427272584f334b492f4145697042793372626432434c73304457655942387868725a4f4b72372b4e644e4e63334c4f32324934365a6c4a436f4c496f73414b3631696542437268316453564b6e77754c67676a6e635577577531394e2f71466c344c4a6a377144506a67324751426552514e5045427871592b784e713955327264734c4f78316e784a566c695958444b62697059367a6156453952656a396d3334436152666c747851447973364944724248414f4434585875616d467173394e656d47394f4a6d37727572787a3578756b5478447055526a3462582b382f774236707868506a697150315a36676d325861704a51335476323967694d6a346f4d6139693276416e6c566b5333484c6d7653333667353231464d624c445a4f474c41584e335564784e584445746e4c31765a6655654275474f4a73535a5a5937447148426c4a2f45447771574e7a615650794d4c477a4c53787330475142345a347a5a682f46795965326a65556463724d78474557344b436849435a69413942762b4d4834443956434a34494942476f504f6756714b4c5542514b6f674e4171416f46397441714230464a4269673543525441703163516444575a475a507575593865474262526f415478504531634e4e694d53547a46716f68376d7164456368626f6c366769692b70424e2f7171564b30766e5a3869694f4656562b426358596e32436d56794d6661485a2f4d796e4c4d64535362742f6c5441797a734d514b6b30424936574855436267483770312b696c68566a444b73304b794c7759587432486e5644485372446b5734646c426a50414a345769344667436a646a44565451562b446e4a416a5254686b53354b477849553338536164683456495a4f5863736955686364524368502b374c6271492f4b7638416a544b4d63544345354d7a7946325669724f3272584874305769794c474f474b4965425143654c48556d674a34456e67654354344a41564a356a6d44376a72564664746337706b50424e594e4b7a42774f416e515766384131723468554973325545334e37384e44784859614447616147464f755a315254774c47317a374b43706b3875624a6b6d51504570645868623458387a7036585a52324e70783430714d636a6f6859793548573871674d7a5369354345674631423073744c52635259384d5a756f366d4f6f636d35496f725071427541626b63543247714b2f6549496d69453769385155773551356d4351324a2f6b617a564c42733269655354464d475133566c596a65544f6678644f71502f4d7574494a55736b55534753566c6a546d7a45414771494f566d4765466f6f6f37525344706161554543782f43767848756f5a4d59516d63536d4a51644c534f415749396c51777a7a5954424235364670504a733079477844786666734f524131464270324b517878793763376462595a486b7945334c34386e696962582f41452b366b466d6241645859507171675a565a575668314b774b73447a4246694b436c5154596d34725059756e534d58503664543072346f4a37446a6f656c71694a7a35387269324c4864656338743151643458346d7171314a69764f7765556e496269486c4854455034497855457863644c686e4a6c4934587430693335525161796f53597077522f484832666d556662564158436b41334c48554b6f755350594b42694f5a745749695873466978392f41564d6a586b78726a714d694d4542435050755353305a50694f764e66696f4a735a75746a596b615846554641554251464145586f4e636b45623645652b67684e74686a6379597a6d467a784b6d77507442304e54426743624c6a306d694467666654776e364470516246796f4730366970504a7751616f32686764515649505951614449427556426d455050516658515a6157396c554f706b4b674b416f43674b416f4155436f6f4e454c6c51615a6e41426f4f6533647751527776574e71696c4146376e366135694e7565355162626779356b3271786a774a774c4f64465774535a75457a6835724e504d37535a4d3536736e4b5975357677423556336b784d4f5635714f716b6e545563536577643958433234627a475656622b454567432f48576d474d7972445976542b3462356e7468344b6f575265756161516c59305147774c4d4f2b6c73693679314e4b657150522b654151324b574e2b717866486c4137434e5033314a744b3164624f586f6e706239524e7433587078736748467a7261784e384c486d55626e537864642f753650647352382f414d5554425a415136453669366d395a7364504c6950554f773447395343506477324276434b4938664e58574e6c424a436d2b68486453566d3865584b4c36627a6654386b353350474576583463664a5857466c50477a4565467534317159736339355a632f524677354a386649624c326d567357614d334d567a714f4e5844487935643336582f41464a7773746c787478746935674e6734306a59385035616c6a704e38655866775a53536f46617a4b7734364545476f3667346a7844717853436734777364503565796d516f356c636c6246484878497773525257776a376144456967666455416144486e2b36674b416f43675866705152647a694c52435a4434345343534f4e72314373313354453867504b345637575a4e53622b36714972376a6c3542364d574d784b65444558592b376c55796a4239756d6a52736953377341537a4d535741504f6c466a67474a735a5755414d4e487478754f4e497264306e724c412b456a7841396f7168756979493062693675437244754e4258376449384f524a69536e57353654794c41582f3851317151695a6b354f4e4176564d34585852654a50754655514833504b6e50546a49593150426d46335073586c55796d637475507438317279755675537a6133596b6d397a5443345331786364554b464179734c4d434c6b672b326d4258596a4e695a37514f784b7552477a486d6258696638416d58776d68467179676767334859527846554d4132462b7a57677164316943356b544934535849476c75496b6848556b6c765a34545571566e4a6e5a7a725a5553416758647765763239497439744d683432336958707947637358415a5a5750553542374c364c525a452b4b434b4c56463852347364575030315248335345766a656171396277457630573164434c534a2f4d7443734e6f6e36736334374e31506a324373654c524d4c784e2f70307151544f6865767241737874636a6e6253714d636954486a6a507a4c4b73546771797366694246694c55465069527952684a306c614b626f4d52366c424d6b6173664b4c6f66764261676c44436d613837495a705143564d7a654932313849745a614742747553736d5a4e4534425971732b4c495271304c4470493135712f476b466b774a344778356e6a70564434637239336451632f4f547432556b347630594a364a653173476336482f34587155587330734d5346355856452f4554595737716f694e6e5453662f6a7839436e684e4d434c2f414d4b4458366144434b4d6f575975306b6a6d37794e6135374f48414367313538452b546854525937394751793367636e547255686c4237695259314b4a753335715a2b46446d49436f6d554d5550465742495a442f4377745647526c69516c45425a726b6c46317353626d3951614d79504c654553715172774d4a56695855754642366b4a492b38765a516164736e595a6d586a4f7866724979385a7a596455456f2b482f343248543946434a3072526f424c4b345256754c6b325533377143504c4e4c6b52736d4f6c6b6346544e4d434673777434552b4a7143546a6f49346c51456c555656556e69516f4176564779674b416f43674b416f4367434165496f4d44456834714466756f4d526a78413343674874734b4459464174625155794865674b416f445367516f43674f2b674b416f465252796f674a6f4d48613142583563345653536257465330726d63796379796b583076584f334b49784276596353644f645a537544395537717534626b5955612b44676b69344f6a793843663356333031784d7362586c51534f7a75575050366857325a4d5274685377366a636939674f525066656b5a32756547544d5344715472714e4e525653526462427638416934474c6c375a75474b5a74757a79707948694a5756517567476838536a73726e7670626379756d6d32486135752b2b6d4e343239746c6a7a764d674d4b6b57366865556e70696a587148577858346a575a4c4f61336d5759636276336f7664396e634d704f5a416f4447614d45465741366a345166752f697263336c59756c2b696636562f5562634e73364963776e4d7774414775544b6f7678312b4b7463564a6250443154447a396b395162654a4932544b6763613643366b3869447170724e31773661375a697679746e7a73474a6f346c47667472416954456c737a4b763553654e547774312b7a6c6431394d2b64687979656d315179322f71594d316c6b55486c47782b7871314e6e4f3653764d7034706f4d6c34636c48687949327338546771367432613079746d4f4b39682f54446264316932305a655a4d375254663841343044456b4b6f2b39723230745853665636416f4941724c6f6735576469535a5277315270736c42646d516642667461715a4a4d6c3049575958484a7872396c444b51724b774255676738434b42326f70473145465146414767782b32674b43716d7a387249563434554d555467686d746469446f6546544b5735624d4444696d44466a6f684332484569314a43525968566855434e414263416763625656624e44634541673645486d4451562b4d54695a6a514d54304d51464a374438502b465169785a6267715471616f305a47626a5979677a7941486746347366634b4671726d6c4f626b4631685a515643686676735162395274384e716965556d44615463764d5372486a5939543237324e4d4c68696f4f446e674d78614a774231486955593276707a567165453856616b635277506256556c7661783149304a3761434275384374474a39514548524b77476f526943472f6b6278564b566e4675734168487a424b35436a706b69414a4a59615857773142704b5a614a63374d6e50544570783162687031536e364e46706c4d32737366613244475279555a74437850584b5233736546466b534a38434d776e7956437a72346f6e4a75656f61324a504a75464d43507330366750696745494235324f4478434d5347542b52394b543745574c416b433345454556526b4c6733484561696770504c6b7774774968554e355175493767467365596b68666247344e723145534a73374c6b5054456f786c50426d4165512b78426f4b5a476d434f42737445366d4d307973776e66784d536848556f76774e4d6d4f56716b4d55514a5262747a593673666561717378633249305048766f4b506345664579685045704c51467371425239364e72444a69482f6e465369375352486a57524744527541794d4f4256686347714d4a386d4447554e4f34533573713857507355616d6861725a70576e796f356d6a386e48574e304379574d6b6e6d574854304166434c587147545747484761492f4c6d4e48595278756451684f6972596b394150415545693576727837366f5934323939414a314d624943354234693168377a51527355774a6e744176556b5759725a4371443068706c62706d48626667784651576654304952476f4241305867436142537a77774b486d635267327463366b39774847714b3747787574566d365867654b5351346c374231696b49384c4b65525033543356424b6a7859772f5731355a662b6f353669505a7958335652494361334a7561444d447334554251483736416f43674b416f43674b416f43674b416f443761416f4367526f43674b416f43674b4255434a74515270354c41362b3267352f644d79774b67362f54574e717a61714263334a4f703176584e564a3671336c734441454d42746d356c306874785665445057396463316e61346a675a4f6d4e4243756f58556e744a7275357a6e6c676f414e795063652f32556733325172634d6571326749304946566e6c68634b4352636757423743614174665543397954636e5132714e4d526131314a445875707541515162364555485537482b6f5735344153445043353243622b6168414d704237575078566d36785a74593337726837503667796337637341786266695973506d7a536b2b4f56674434664c466c57704c6a6862666c584d375a753235625a6b6a4b775a327835425973416271772f4f70304e625977396339466576354e36557735574738636b5948566b4943304c48326e685575485458613578567a7577324a4c5a32513478444762745031424c6a763761782f6f33636656794871443178364e38355762414f666b494c4a4d3051413654324d773146616b724e3269752f77442b73356152337863414a436c67743247674767344372686e3558776c593336795a51505450686c6572525858556a33557357625650782f557542756d337a344f3335625975345a5a4a6e6d59394d774a4e2f44666e5766424e73743244366a33625a6259322f7741587a57454c42647a67556b7142702f5751662b5956654b53324f72785a345a34567973435a5a6f5a42314b79454d7241396c714e5335384a554f556b683654344a42785536555756757455614768716f434b42564269514b42304243736171504b41433931424465324a6e427870444e787477424e52466752326536717246535353447259386546364342757a5144797647505036676f51487846573438507738616c4b31484a334b63434f4e78613169364c34694f386e5155546d6f307547635352476d5573584e793550557a4161734c3974714a686652527849674551436f5143434f594f74566f4277584b38434e625545666359504e78697972643472736f484571525a313934714647335a506e51644a6271654b796b38797046316233696b704779664e786f443075393350434e52314d66634b6f677a5a475a6c71304b4c354d62677179697a536c54322f64576f6e4e6234384b5572346d4564685a5142315735436d467731344570544c6b67634147532f547a7336437a72394869464952594d534266694c363237447065714253534231437a445132345546546e52795975597338497553786e6955637a594365502b5a66454b694a736d363765714b776d442b594c786f674c4f66355256584b4c4c6e5a6b6f41412b5569617748427053536266777255796d61325932324d705a6e4a517551584e2b7152694262784d614b6d78517852442b6d6f425046754a50764e555675365153724b7373502b347a435748756e6a48772f7744794a7055714c4c4879493869434f654d336a6b554d76614c38766456566e6358747a34304544643352593469704279306b567365496173317a3073746839316c4a7651714f69506852664c525a6178593355664b36677657697353516973546270374c31496a5045676a6b6e6e574a756d5346676b387233655871494443335677706b6b574d4f4e4443626f74325046324a4c48336d71724849786c79494a594a54344a4156754f49356876614472515149386c764c547a7244494d6e793772794d332b44664655714a713471385a54316e38493055663430567555686c466756417541434c57736255465a6e51645756356354684d70437558447a364842364736674e656d525461676b744e6d536d7968635a656258456a6e2b48376f716e49687855566936677449654d726e71592b3830456c59674f4f704e426e6253772b696742623355425146415542514671416f43674b412f61314155425146714135304161416f44397230425146416a325542796f4367584b6744616731794d4144515657345a51524463324a7158684d75616d6c4d6b684a4f6c2b4e6372637068706e6e6767676b6e6d594a4445706432504a514c3068586d753437684e6e356375355458426c756d4e48663449313046656a57596a6c626d34514c466d314e68326b3156624371715162686944323661555a3867737846796245634344786f595958485543526363624334706c63413978314f74755831304d4d53644c366a36714c67756f3376666a78706c634d34495a3869565949464c75317643436261637a374b6c75437532324830526967517a3574386c6e4943524148705a75785232643959753938516d755865536b3764696a46776f456d7a776e5646694b516b614c2b4a3241304832306b2b37666a7738356c39564a35755532384a2f634e7852797349582f5a5378745a462b473161786e77783956487575667547377a4a4c4f69524c4770574a46466c414a765770717a647045466f484173584141356371594a742f6749325245346447757967324f6874663230775a6a555a4a424b5a43784574373951304e2f645561642f77436c66587369716d4a76436463427371354a31595830756238617a59532f52665a327a352b3045377636586c74472f7744557964764869686d553639614c66512b79724b746e325757782b73396d3376706779503841737479497349334e67782f49352b4c32555762532b585143664978644a675a4951644a427848746f306d5253787971476a594d70356969736a776f4655436f436779466c50534259637144566c342f6e343749426478346b50654b465249643169544855536869366977436939774b6b706c476d334c4c6e754166496934575533632b3175564d7332314a77747378326a575a6957446939726d3576326b3077316859717361414b6f436a6b414c565271793866356a48614d61506f3062486b363667304c476a61636e72684d5275476a31565478414a74622b553656494a76534362323137656455614d6a634d5848594b376c706555534471632b34554c56586a784d374d5931654e6d5a697178735156526a66705a687055534d3459346f38744958516f4862706b4e2f463148564c6d2f4271665558436f694c306f41716a6b4261716f444136634461396a7a767a6f4947357775475365505279567365795264552f77425877314b6c54594a6b6e68535a42345a426578346738435064525772497a38614275686d4c7a4561516f4f707a3948443331544b464a4e6c5a556b594b42466a6272574666473559416764624477727871484b5246677353576c5053654a564c64522f6961314444547557436e6c65416d4e5873724d43624b35494d637570354e787067715a6735527973564a5748544c38457966686b553259556c497943792b5a636d344249424f6d68494e716f65544235384c5267394c47786a666d727162713330304664675a44774c4a65466d676c597949694145704953524a4751547736787055686c6c4a6c5a6b724243336b4138495976484d523374617930546c6e6a376334755461414e713455396372582f45356f595331784d565932693874576a63465a517736697749743469617171614a32774d35576b596c597975486c73656362482f74706a774835477166555835466a7278476c5546774f4a7433554658756d494a53664555544c41694c6a51704f68366f4a5070306f5673787431527364446b4b795a6c756d624756535736786f3176796e69445541306d624e653744476a5033554961512b317668583356526e6a596152672b5567554d62753575575939724d645451536c69556364543956426e62733443674b42387142634b416f436750736f436750726f43674b416f43674b416f43674b413530425146415542514b674b414e416a514a7442374b43486b7a42515354536a6d747a7969376c51644e62317932714b3371734e6638414f73706c794872446476506d4730777342464661544e5961676b61716d6e5a585872312b7459323278484d54534632466859415741476c674b36316d514b504362693534676b364433554c54596b4b414e534e54707776516a57546251387142645774726b486e32555569654e2f717144485536486c394e465a77777a547a4a4243706561566771494f4a4a706150515054657759734a654932654b43337a32522f314a427235536e384b38363557353557544c7463456554696e4f626f47564d436d46473543716f7434522b38315a4d4e54683572366e336e4b794d365842787053325348746c356354454356674c573050426546644e5a39584c626237716950456867736f486d7938546255443343743852693572526b545a436b6771454135566d374530694730306a614533706c75617867574e3767325064544c574444335069462b2b6d55732b7a6247354442445a314e67684a73465062557349394139412b7157677a4532624d6d57524a5461475548524850426659315271564d39612b6d7365484a584b69547068796d4e776f2b4355616d3175462b4e574d37797a6d4932792b74743632566c78647856747732384377636b475a463461452f454f343073545866486c322b325a6d33376e423839736d5570422b4f4b3967447a56314f716d706a44724c4b6d48656c6a506c5a4d52696d413176384a7452637445507176614a4a7a417a6d4f5147313242436e324773384756716b6b6271475267796e5545473956575837586f4e4756754f4a41514762726c48434e4e575030564d6c71436376637331697349386c4f59552b4b7837573555546d704f4e744b496f457264567543416b4b50625443707251784e45304a55434e675151414261395551397464347048784a44346c4a3666614e543950476f524f5a5353434459672f534b6f65764c6a5156475449734f364d2b4d366c79766d4f76465178505377613334754e524775664f7a5a377158454d5949425749334a75626173614a6e4b646a625244455047626b38565734425065654a7068724361716f696856415652775543772b7171494f37774b30516e4a4b68423053734f49516b46572f6b62577053704f484f302b4f7275414a564a535a527964644453446346414e674433446a7056454c507a63555253592b73307a4c595252366b486943547757786f576f614a4d345a49586b4263336c534932557352346a636a77337149326e41624767615142517136796f677533547a6271507845555649327151746a474e7265644178535870344d654b762f41444c5343553773756742746134493134477142306a6b6a5a484634354156596379434b4372785a6a695a7a4a4d51456d49696c596d77457944774e722f31452b75703955545a397878346d3646764e4e2f306f68314566784867745662554e386a4e79474b64526a422f35474f627550343566752b36706c504c586b4c6b34735978346f6c526e52686946574c4b58516452694e786f7857396a5371734d52345778497063525173637968682b4b35343958665153515459456933614b6f64425737784245564530677641564f506d442f30704462712f6b66576c4733615a35704d646f4d67337938526a426b486d785541712f77444f746d71444f584c78346e4d5a4a6d6e2f414f6d67366d4838584a6666564d7445707963686b4d7845635373484543654973564e3136335059655330456c497047467a6f4f30396c4273534a467470636a6d616f325641667361416f43674b416f43674b416f48656756415542514847674b416f43674b416f4432304251484f674b41306f465148665148766f456144564931676556425362706c68564942314e5a32714f636c6373537835363179464e762b3944624d467056494f53354b5936666d492b4c2b5774613635724f3344676d597247515736355a43576c6b4a7553784e2b4e656a77352b626c726a4632414274666a626b4b4662724b41624d6f4a304949497451616d504857775041584f74444457547272725252653344364b69344b674c6a6a516462365932624d786f426e42414d374f595975324b782b45754431536e73734b78746338474d3876514e70324e4d644d665a79335541432b55343473527133306d73795a72636a6e5031527935593530534b574d78522f3841627877412f7742514d7933636c5237685853655764724d347935375939686e6e6b68776f5146794a77476c6b4930524f327432346a6a722b5665684e366532765a646e6c62486a44757145764f39697a4541387a58792b2f753232754a58304f76726d7338504770576156326c647273354c472f656231394754457738725564505a5647494935634c58484b346f48514e534464547750443231597a5a39557947566c6a56784973526859465142346951653673324538766138535650555070424a7a597a53513958736d694848367153756b6d5a68353948315a444e4979456c39414c614163363038746e4b47567a74757a506e4e746c624779467463706655666d4230595575477462593750595031453237504359472f77415178636b6b4b6d5459694679644f50334439565a73647464382b557a314236546e65457a3759345a626451515749494f756c717a59315a6d63496d305a475274477a766e533534584a6a4c64653379432f563036644b36334250496a53724a694a4f504c70662f7375482f592f377430743558546670743472396c4d385a617a776d7762524176696b3854486a30334171595841787838706d4e4354344a4c4653657a6c51546e36756b6c65505a7a71676a4441454e32334248736f495735784d6a783563656a4b51726e76763453667371564b796c33654255485447377a66394941676739354f6c4d6d55526d334c4f4a566955546e4645536f2f6d63307963314b7839706852514a624e7266793130572f667a4e4d4c68737a63524a4d632b5767456b514a554b414c7262784c7032696d437374756e383348365365706f724b53654a42463162364b51534f6d7a46723852714f3863366f5a5657444b77366c59454d70344545574e425459737a3430306f68496e56574d546774627136414f6c677748454b656c716e68446b6b7a4d734d475a764b48785134774a46767a4e785075706b355a37644269536c3477504167566b52545a58566838576e4857697861486f6a5141414b6f30437141423956554d5749424669434e527842426f4b685438686e693574454c52536438546b2b552f386a65453142626b473530312b7967687a626e6a7135696942795a6878534b784150356d506846444b496a545a45737064466c6554705879597864465662364f3761453630524b683231516f4570437078386d4c77722f4d3345307775457845534e416b61684648425641412b71714e4f6469746b347a526f656d594550412f345a454e305030304544614d6b4c4f596264455755476e676a50334a564e703476355738565343315a6776485539673431517647334164492b75314246794d76474353516f767a556a4b56654a534f6b6767676833506857676a4a694b4554714a4d336c72484a496a465336714c5761783171496c77346e536f56564561646741463673693453466952654176336e6a56476674715a425146412b6441637142554251464155425146415542786f43674b416f43674b416f436750594b4135646c41667465674b41355542514641476756416a776f4957572f53742b3670527a4f637a4f35767141613537566d494d6b62574e6866734e5a5658344f78375675572f77354f364f4778734d4552514d4c4b373376347a32563130345a73796c656f66525870374d6a6d7a46543547646a3077746a454243667a4a7770647250425a4b344464505475376253412b5648654274467945463049372f77313031326c63374b71324931385855644c454739367152714c584a4a31765561596b2b2b6969675032464662734b487a63714a414c335961577666576b59327549395777646d33544a3372434f4a474468375643664e5a6a622b72494238492f463031797435747736545868663750315362746c546b334563617142666d784c477271736e4c7a7231466a4c6c2b716f556243474935445453743168326b4259325a72634b36614f5862625a77796d7a382f42424f33495833444c636846433350516f734c566a757576787679713955756547572f6231362b6262764a7a735035624859644c4f6f75534c573131727739577654624d584c3137664f544f48475451796f514a464b584677434c6156394357504d37583066364477747a787350637369647059692f564c6968514649556b644c47754733626332534e3636532b56742b7275323430573262664a6a5936524c444b56366b5541685758346442777657394c793174724a7138757458567949486e524b32724d305445716f50554c65495874656c69535a6a3176394a636c35396b6c6859672b584e6341384147477632566e3774366f6d36656e73766173687936483552335978797162725a6954592f6871355932317863707532375a675a47426c537a4a655641516a613657463647736a6c386e625963676c57417333594e4b5373345a3764366b3372307736517753484e7757594b4d4e795351536266306d34725677733273656e2f414e6a3276654d635335754a3079756f4a354f70497678484d5648627a47762f414f7379664a663279362f4b3376353168316450385034714a686268775341644c337437716a5350754d4a65447a462f334962734c6353764f6f4e754a4f4a6f4663454567576275497169506b37746a52457048656155635653316765396a70554c555148634d34326177697544304c6f6d6876346d507855546d706e39754a6a4938306d5869746741742f596159584262584d5447304c437a49535144787354596a2b55306845743769784249484f7776725647533330504136616439425775506b632f7141506b754353414c2b416e55442b4271696547326264385a5261414849653333644648745969715a516d664e7a5a424737697a676c59554a564c44746269616d54796e592b315178716f6b733441734931485367353842787068634a713255414c59416341425944364b6f7163692b446e4356522f54316b41484f4e69424b6e3870385971555778437374726771774242484d63616f46577932484c556b384b43727a3538584a6c6a6a6a2f724665705a3355654152735045706274766131536f302b586b5a49455353535a4d536744704a437059636e634478554d4a734f324971675373475563495977556a48747471314d4c684e5656565171674b6f344b4141423946554f2b6c41646c4155465075474e4c486b6c6f41413072664d597a4852567949785a314a374a5571564573376968414d5544764b7738534d4f6749534f444d663356567930734d696654496373702f3545563154332f6561706c45694c4549554c594967344b6f412b6f56634c684a534e452b45613970314e426c51506c514b674b416f48616756412b36674f644171416f4367446f4b444173522f685159475a516454593046567676716e62647243343832516b4f646b7875324a356c2f4c44415755794563463671754c6a4d533753575371376176577552356172764f4971324144352b41777963596e745a562f7170394658476644507a6b38756e78637247796f52506a537050433342304959665657664463755732674b416f43674b416f43674b415047674b416f4367567a515973644b43767a574a4248477053716d6248444868725762475552344c47735956585a6d47575058445a5835716668616b754573796a785a62454743634557346f7874596a6d4b336d564a7773493874764b386d64526b5937437a416745322f4d703430567a472f65673861644779746a5949784a5934685067596e38444834665a566d396e6c6d36754579594a3861646f4d6d4e6f5a304e6d6a6357494e644a5a5a6d4a6a4458514641554672365a6845753777673841792f575254364a7439487247795a5754486c6274477273735a644346466741656978746356797877365a7178324a6266334269447152596a6a594952705631384b3876323662476e3954545377724b735a5142524f31334a427357314e644a6e446874356a6f4e686d5750315a6a4939674441797144326767383638507654385a6638414c3165725a6d756e39626c4732615643335431697759445558726a7269377833323431727954314244354f57696d55792b45454d33486833563944563476384152364a2b6c7566677073444a4e6c5249364f31346d5a565943392b424e3634626362584c7272346a522b70752f62426c374b754e426c4c506c6c77593055456b42654a4e7858665335355865346c6c65556e6e5852774b67324648637146467a6274745671546836722b6b634c4a7455374f5145643943546358424e632f72573958597a6a4e78764d59714d33426b4a4c78324264516551422b49566130714d33595963764865585a4a796f665758444c454b54372f685074717333576652782b64466b347065475654464d4c39534d4c4d4232326f3557574a586f585a5037727570334f5a62346d4733546a6a69486c483374667731625630317939626951496746717937483170313948554f753356303837634c30456177477468336d696f4d2b3859734c4659377a754f4b72627048745936564d6c714242693547516f7368414a4a4a424b72596b6d312b64455363484769544a6143614d426c4868545470754e6236554d4c516d79384e414e414b716853434c6a33393142417a5662477930796b48686b5069412f454234682f4d74536f73465a57514f70485151474463425936337171675437786a71536d4f446b7944516c545a4165397a55796d555a4633484d6c45722f4374776c68307872666a612f6959304d5a546f647468556870535a6e4849364b44374254436f2b6644387650466b517230686d2b4543773637634c4438613665326c466b6a704c47736947364f41565063616f4642424e7a634569783532416f492b3478423852704e41304635555a744264516267397a4c70554b6959325a50426a4c456b596c51414747526e436749526342376a3774496a55557938342b4d6d644c333652654f41667665686a4b6246746b56683537656142384d594854475035527839394d4b6d6977414141436a5141574141396771676f43674b414a4142596b42514c6b6b674144326d676779626d6a615971656565486d487778412f7848347635614757674a504c4d736b7a6d575158364555644d61334674463765383145533078474f736873507767334e5843704349714477674474504f67796f4861675641554251464155425146415542514d3042396c417144467a594767685a4f53497753546253706157714850395237646954675a655a46415472304f774457356163616e4e384d58615235722b6f4f554d37314563714b64636e456b686a474d7947366741454d762b7174793568743579353748797372466b456d4c4d384467697a49785536657974537357537233626658573859556f6c5a75755166464d7667632f78574853333877715a575444764e682f5672624d6b724475616d435132426c4141467a32692f32566272506f73337669783173337158302f42476b732b34515252536641386a64494e2b386973347a34644c636557325065646e6c594a486e34374f77424365616f59673669774a71546d5a68654f4b6d6a56656f574b6e554d4343443946415542796f43674b416f4457674b41502f43674f2b6777626851524a6f37337655454b57473137445769496373523130724e6949636b5176727835316d78556166456a6d587064516263434e43506655786751486879635539514a6b6947765550694657564c4d65472f48796f5a504572424750466c34483269745846544c58752b306266756b416a7a346753422f54796c305945396a66754e5a356e4d4c486e2b2b656c4e773273744b7438724476704f673155666e55563031336c3471565267676934726f48555263656c4843377847447a476e744242705a776c754c48716d425a636e4b414772394c4539756846636d3474746f4b724e4d6e4e67477432394a713671386f7a3459747239587951717a73424b365373347341574a5a6266545854573850484c74625a74393132366552756d4475476e516b6e544a666b72693347764e37656d644c683750573278746975323951694b665a7043746a3443516258317458676d336978374d655938527a4d71664a6b363554646c485459637261563957523449373730726e656a746f325443794d394957334f636b6c776f6551584a4f74754668586d32317532317a486658615365576a39546655657a352b4a695932336c4a584c475361565673565543774637633639576d754935623779385235342b68467564615a6a47313766625346535945566849374b47436977424e726e7370576277396539495971374e364e62497957434470664959486741464a4131724538756d7334636436582f5544654e6f49544a4a79384752697868632b4e4f6f6b6e6f592f5a5853794f553375586f2b33352b79656f452b64326e4a386a4e55654d437975442b644478724e6d48576253744738596d50755558794739774e464d5152426d77337672706457412f7744436152625075763841303974474c7475337734324d4349596c436f534c456a6a632b326c4a4d4c4c4a794973654235355736596f314c4f7835415646634e2f6339302b5a2f2b785762794f766f3869326e6b63503836706c5a53797a357369704e4b5172454152714371674538652b736e6c613432313473414678356a446753414642376c464d4c684c424275427930734f5655517479526c435a53477a526b427a2b572b6839787146544970466b6a57526441773148596564554d394b715759685647724d534142377a51566d34626e6a7977764241504e596b663175434b516267676e6a37716c53317054426e796c7341797745337337454a72324a7a6f4e2b5274697734785a484c4262645973414150784b4232557759574f4e4e3530437677626779396a445131565a584961787659384261344e42686b514c5043384c457148466777347151627133754e4241322f4d574550426b6b5257596b466a5a51344e6e572f384134685567796e33694d416a46547a4c47786d66775244336e567664544b5a5231677a4d3468704c7a4c65344d674b51442b464f4c5546684674384b6e726d4a6e6361335957554875555577715639673074564261674e4b416f4d586445517537424558557378414139356f494c37703161596b666d412f3835377247505a393571686c6f4d4d7551344d7a4e6b4d4e5170466f31506230445436614745314d50675a47346664586861726753565656466c41413532716831413755436f482b776f46514641554251464155443455436f483330436f43674b444678634767716478684c43397a59612b327337636c65575a33366162376c6268504f6d58444b737a732f6d533951657a472f69734f56616d33474d4f66785264373941626e73657950754f586c7779786f3652694646594d433757304a7179382b4673345250544870484d3332535755466f7344472f2f41434a305872646d746352784a39357a3941706e424e6375686b394b78784a355550704b57534e54704e6c5a36724d3365566a384b2b7970786e4f5335786a43425036593264414a4d336174787749314e7056676d544c4675304b41487457647472346c58575436784a5863656a4c6a47797870764f7a4256783277637153376f4362654a474855767474586b31367439646264727a3534656d397332784a44336630426a37686c736473443766754241622b333572645375663841304a65423774616576376b7378687a374f723635632b6b2f716e302f6d50446835655443384a486d784b57556a6e346f574a303778587631337a4d7650645a4c694f6f3266395a6479685952627469706c786a517a786630356665767730784b3138724b372f5976573370726579715965597135422f384131707636636e7373644739314c725770744b76534c477834396c5a55554251483747674b416f46514a686366626167314f6f4e36434e49674e36434a4e48396647736f69535139315377523269495064396451616d6a3039745a7346626c376172457643664b6c3752384a396f706d777368726d787759775178754d706269534a6a314a494f3162317235634a497a675a4d69455459684b7178494d4c69774a4768414a70645a65594f5a337630666835636a53595947486c6b6b744552614e6a3776687136375763564c48465a75446c344d3567796f6a46494f463951522b5531306c6c384d746d30355178647978706d2b4658415939783071784e7644317847435a45453270535665676b4853354768726e5a79334b73636159785a43536a55413263647834306c35574f592f56505a356d2b583353467251614a4c3071537763617874372b46616e46656275306d7530336b3556477937694d3745387159663159783079782f653035324e62736c687274693856643550727a62634862446a7443383742656c4342706531746231383362314e356354772b68723747746d61387779636b797a795371416764697755634263337458763131784a486d747a62574555786a6271417578307561315a6c424a49386a6454456b396c435267622b2b677936656c5354785041445853715a7a56743664775a39327a736662496f683046773830674236676f4e79617a62684d6375382f554c64467739756732654a77476d41447232524a2f2f5930316a573178486e6a414d7743416b6343655a72626732592b585068354b5434387a592b536d7175684e376439714c4b394b394b66716c6a53736d4a7661694b5532433559486759387572384a715858374f6d752b4f4b394a696c696c6957534a673862693673756f4950665748567933714c4b4f35376c48736b4432686a49664e59486a7a56503841476d532b467638414a77664b2f4c644938727036656d326c72565678774e79786a4e414851586d68385357346c62654a617a5373384c49452b4f72413359437a5547304a5a69317a726251634b427559776a656151496d42446c69414c4854573942543475624c444577685a4869366d434d3977434162426862746f684e695a2b59504d6e44536f4e51704952645077707a39394f524d327548475a4378514e4d6874633667413669776f735433594b4c6d39753271485a53434341564949596369447051562b4954693562597a6e777351464a353665412b386156434a386b6b635346355843494f4c4d624436366f724a3937485354696f436f30382b5736702f4b506961706c4d2f5a717838584f794f703234537431504e4b4f6b45324148544878746167734964757834794765383067344f3969422f436f38496f71555463334a76564239564155425159545451776f5a4a6e574e4270314d5142396442416c335352376a466a73764b615945416a38714478483331444c516d4e4c6b534235433252494f4476384143763841436f384b305250697756477372466a32445156634b6b716f55644b6741446b4e4b755136674b42384b4255425146415542514641554446417142696756415544416f46514641455846424679342b70445963716c465047536b7042504f73357769692f55724d77492f54734f4e6d5279794c6c5a43724349534659534943796e78635233566a736d396b2b466d6638414c656c317a2b54705054327a592b7a37506937624466706857386a4d42314e492f69646d747a76585757343553347a7773576769635749426f694e4e745554366757493142374b5751773576666652574675423879614730362f426c524879356c50623172782f6d704d7a777a68523536656f3854625a4e72334979626e74626e544d6a482f6577416343464f6a39503564613437644f6d316c73785931727664633455475a447566796b5575584f642b3268535568334c484c484c78656b6348754f7462666861756b323131754c506a2f344c7264707879687273625a47624732524c466c5945674d696268312b535a4648464765784379647a6973393364384e66484b39585638726e4b4e75655a7475492b566a3749736b55453672484b5a53726b6864573753473676764b61353958547474386475793575766876667431316c6d7338725030352b706671546154486a79502f636354514343636e7241346543543476707232584635727a79334f4939503950667142366333737244484e38706e4851346d51517245396974384c566e342f574e664c365630746944714c476f304b416f46796f446e514930474c43394270646230455756527a39395247686f77644b67315044707770676158783738716d42706b786951644c6a6e6570594965526871796c57554d7035486c374b7a594b36564d7646446551424972364654594f4c613376566d333353786c6a352b506c4b49636f5864655a306357374b336d58796b725875657a7835574d305571444b7837584241386166522b36733257584d5847584337783654796356544e6873636a48314a582f6d4c6237613372766e797a593748305275556538624b32464b772b65777741564f684b6a3457713754366d764844706b517644356f485346384d79637777307657634e703841697a634e384b634267796c62486751652f374b766d4d37617a615757504b66552b785a33707663467946646d38316a356551525a4848486f61333371337261386b367474623863666839454f54646475334542705363584a745a75614d527a4e616d4b335a5a3556302b3348714c49565a654e7751623639315834306e624563346367314e6866675257666a573576474a694b6e7449346b316348797977435859425158636d79714c6b335073714c6c6334754a4c685378644d666e37726b4330474d4147436b6d3369724e7553544c30625a39737750534f7a543774756855353077764b454175574975734d6666575a4c61365353544e65623770756552756d664e6e5a51744c4d626851627169445255572f594b36535963647473334b483567444b563041477062532f506c565a6b497347612f776738674f4e47735a4e547146346b387a3247695868307579667142766e703743624278676d52433576417331795979644c4a5939764930736c62313272722f54473237686c796e6363735351506375335743724f33472f7672474d56755a3275613676356e4c2b5238337048586531374731723276616a617a47684248476f71734a47426e4e6348356159466c4131747271506361696543796436507734735a596e5153535856523742784e4d6c714e44424c6e7a4154546561316931324868414274345546436372614442786f53474339546a3737574a4873374b59567644412b49472f66564547542f744d30534453475335494841416e7866516461694c4167634f497171776c6c686754726d645930484e6a59453046586c5a4d65544b6a4b68574a515638782f43584a494b685154665136314b6a464d4a38755173584c6555656c704a5358495047797161437767774d6142673455764b502b612f6959657a6b4b59564a346d354e7a323151554272514642716e7963664754726e6b45616e683148552b774455304d712b62644d6954773430666b702f317068646a2f4447442f774361706c477148436b6c667a54315453482f414a387075522f4466526635614b734973474a64584a64757a674b75424a41745941414161414451566368314155425146412b644171416f43674b4230423330436f43674b416f48514b67656c417142326f41304744726454333046466d6f59736d3972412f62574e754b567a6e72454a506c656e59486738395a4d385862553946756b6c744b35334e766e4470706953356d5861646433596b334a4a313939646e4f4e717451625165462f665644494234366967305459634d6f494b6a58694b6c6770636a3077734f5763374174446b734f6d55676547564f6153715069465a32306c6d4b5469356968797467774e7232724b527364704e736d4c664e347a6d3567366a63736a4158614d482f54584c61375379572f6a395038414465746b7a5a4f662f4c7a666550544f6474374e4e43725a4f324778544b51645956547963443761395675504c7a79532b4562456e67784a5a52454973715352517347515659684750466c423538713562363365544e2b4c7470744e4c6671695442784b5249515a464e69774e77534f38563131786a68787563387572394f667156764f79346934374638384b2f3841743544335152572b4647743171312f6457624e72746e5034747a625761346e386e702f703731377347394969724b4d544b622f3961646c424a2f49345053314a593364624a6d783068304e694c48766f676f46796f45614446714457346f49386771445152616945514b4445786730556a454452477438594d44705573454449777462675874777478465a756f71387a626b6c5055774b7944345a463059566e6d4635524579637a42596564646f686f4a5631414835685735737a5a684d4b345761686443497053506a41384a396f46506a4b756375647a4e6f7a6473334e4e797731454757684a366c50394f5654785672647457625763566d792b593748624e7978747a692b5a78374c6c4b414d724559324a30712f35616c796e72436a674e6a48705a64537644704e542f525567793432566a7668626c6a704e484b4f6c306b554d72443247724c6c6334634a7648365759426e616662706e6a7879435469456c724838726e78443331724e5975763263726c2b6c4d6a466e454b356f6a4e7a667a77554142506850555044566d3163374a395972386e6263374754716c7949537653577373674a737074774658354a4a507331625a68665073796d5667514e46564764696264777061754d58456446366539456572354a75754342634f4a77413032534157554138565561316d324e66484c70495a7653586f6c584a6b4f34373277505859686e424f746966686a576b6c7132794f4f333731467557395a6e7a4f5934565675494d644c3945536e6b4f2f3831626b6b6364747261717a4947414a75434f41467a635554424e34686535586f467944623671704b7844416d354a4a356736665a554132677570462b59483255574f742f546a30782f64747a47345a5345346549774d64786f386f397634616c7265757561396b4b72594c62514377465a64797350645156325476674a3663524f6f6e51534f43416634564772566e4b572f5a68446762686b7635325249564a46677a676451424e2f436730576d444833575547466a776171765539745862566a394e4d4c68457a306247796f387550345a43466b4130485861772f7742513070525a49367569757571734151666256416f494242494f704974515239794d4b346a4756776848696a36754a6268596532685549352b556b4b7871794c705a58494c534544735563616d5535595137566c545365644f785676384171792b4b542b5666685768685952596d45743432547a53774964706645572b6d717148694d32486d746a7553554a43645276717076355433502b6b31434c596a585851396c5546417144546c5a6d4c6972315475452f4367425a322f685565493046664e7557584c70416f786b2f473444534564792f437676715a52686a34456b6a2b5941576476696e6b4a4c65346e39314d55574d4f44416875773878687a5041483256634c684a2b6f63674b755156417a514b674b416f47614255425146415542514f675641554251464136425542514641554251506e5142463643763350443836456b66454e516538564c4d777268665630325646746357644357544a326a49544a594b62457858365a42374f6d7555316c754c43625765485a4a4f6a326c6a494d5579724c455271436a674d702b7575714a4350653146626c62766f4e6f613946424f6c55414e455954593845364e484b6f5a48425667526345455749724f3273326c6c575779356a78317656302f70725079646c7773595351596d5249722b627139722f774332747675646c367a316137597a74563375754d617a6c756246394365707948787044736d374e715641436f5737314f6e3056316b73384f4f5a77715055667062314667516d584d786f70346b4e7a75654d43786454773831562f77444e6175656b6d747848546238706d756469386831496d42614957764a455231432f743431327a4847797a6d4c2b5045327a633856353874326d792b6f663931687141346a56514172514870572b6c7135376232586d666936616136376138583876737376542f7137316a737168494a56337262345636736a48366d6c614252795a774f704350654b365a6c6d57655a63505176546636692b6d743936596b6d2b557a57345975515170592f6b652f53315a757255326e6975704950416978374455614930474a6f4e62446a516158572b6e3155476c6b37763861694d4370374b42574e415542396e4f67784b7178317461676a5434617343514c4875715743756e78434c693364596a694b78594b65666257516c38552b5733456f62394a396c4a74596c6a4a4e326b4258486e674255693030626e553936317557564f59636d30597a79444978485a485558485162534c7a2f6d46444377697a393169514d30533570537744784879357264366e776d72444e6a4276583370337a446a35386a34575176464a346d526879374c55786c62743930714c316436643036647a783546747a63416a3661754b5461444939522b694d6c536d586e597a4b52714749594836425446506c4541502b6c4d62476470384a6a653562704c612b345663564d36354f583952505175334a3034454c354c416546594951696b2f774154644e5834302b556a6c642b2f5533667479566f4d5246323346594548797a31544545634f732f442f414369724e5978643766446b78596b6c376b73626c67626b6b36334e36726d79455250693136446f5478743768524f5264564a436854663462584f703531526779674d416264524f707554783071426c46424942766255646c46624d4442794e797a3463484657387337425165536a6d78396c4d724939373248614d62614e7368776f4141736167453879655a4e59747937367a4557477447693530474f5068342b4f43596c38523475645750764e5a77534e39565251595451705043384c2f4141754c456a69447876524666683567783165484a75436a45457143316d4231346476476f536c50756d5137424945386b4e774c44716c50384b446837365a52486b78636d4969655a475a337342493742324448686638414465677473534447534e5a4956414c67457948566a66764e46534b6f514175546255385451516432786738516e4770694245674845787462712f302f454b6c4b333457515a344157494d71486f6b74774a41342f7a44576b4d6c6c6268695970437a502f414654384d4b417449663552564d712b62634d36596b4a6245684f6d6c6d6d4950663841436e31314d6f4d586258596c3155723161744e49537a74377a725442685977344d45566a597533346d346652566b56492f645679436f43674b416f43674b416f4872514b674b416f4851483130436f4851464155416638416a5146415542514641576f43674c43674c554349754e614368337a5a784d6a53496f627155724968467779734c4545566e6258504d4c484d656d39786b32365650546d61316a47542f59386839424e455463347274796b54377662566e4d5a6458693543794c6348675343446f51526f515165644a5654466253394662566131464476774862515a686861714e556d584446314d7841534d4670584a7371716f7553616c754565582b68385744314e366d3362644a4c774a464b3030636b576a7635354956574c427643465739637533313964356a613858364f6e58333358784f5766714439483833726b79646d7a426b6c69584f506b3252376b3350544976682b6b563231346b6b6339706d323156375a366b336630794a4e743333487a596a612b4e495350435150683657384d69487561724d5669356b567535626e36563353455a5752694e675a3355524b32494141347466715a443461316a446e62667365306266733252624a774d4864733779324b753250304b416258486941726832397678754d794f2b6e584c4d324c4f54444a7738724b793970334845654545484d6a49696c4b6b574965336863653056692b31704e707238764b7a70754c634f66622b783578557a3543522b5570486d5278474f5232754c4e4a715675423256367138386c6a6f4e703958657364676547474c713358626e514f6d504d79794d464e78345a454c4d74637465325758354f2b3358637a3476516654333669656e4e365951656238686e384777386f68474a2f4978384c56764765552b583072706d424768466a33314659455547444c653942724b30474a5155474253395177586c3043364f366d412f4c4131376159434d5a416f4e4d734b734c456130735242794d456b4567566d77565758674a4970563144446c32672b327359734662305a6d45345a435a5968722b596532745462377331595975357754324a62706b484278787633317379734d6a48327a636f666c3931785938694e685a5a47414a487359617256686c784f2f6670646b774b2b56736a664f342b704f4939765055666c5936503974616c2b3658585068786f587969564b464a6b4a566f3355426c493073515270576e4b79353549584a73704e754e74427251744336726139786657334b676167425356424a35456766766f47517132494131467a593630526b7a73526345397737766451777a6a43794e5a574b32463962433144444879315a6953313943515465353931444c426946556b61647568734b4c35656f2f706a36582b5678547532557470386c52354b6b61724878484838565a72727072395866564851554239765a516261696c52476e497a636247414d7a6853666851617366596f6f4b326664637557346841786b48463273306c76646f7452447763447a455a6d6c4b70314575312b703259366b6c6a53517774594949495674436f55486933466a37536171737049306c69614a3955634657375265676837664938636a34736873774a742f454e542f712b4b6f524e4c4144552b376e564375376342596369654e4248794e77773856696a735a4a542f414d684231756664793939433154786d55494c4d38444545644d623668626e70556b44587046524576453274395371694a57315a32755762764a4f7039394a425977346345576f5871596665625531634b33366e5531636771416f43674b416f4833304237364255425146412b6441714230436f4367644155414f464171416f485146415542514641554251464161554349424242316f4f653952656c4d48644d6434354977513274686f5177344d70484268576250724447584e4c6d62767354456275377a3430594154646b51752f514f435a63533676596638414d5857733762584d6d4631316d4c79767475395337666b78712f6d4945593257645744774f6679796a682f43396a57756671786c63724970554d4343447749494950764656724c4e417a734462546c53434a6d377a742b504d63547a664d7931414c343851366e5545583864744548653153375934616b3479346e66747a7a7656426b32503036776b786e505475473469346769514857507148786458507435566e584e323568385a3863756f394f624c7476702f626b774d495831447a7a7459504c4a61785a763343743273535958615341674555567858367062314375337737434d6635724d334564554357755649594257552f6431715861546e5067724430642b6d4f3359654e486c3737436d5a754c4871454448716968374259614f31617a6e6c4a726959645a6e3744675a654d59464434692f644f4b78684950386c717a645a6557764c7a33314a365339626259476e323364636e507842637443376b79674458676643395336362f574a7a4c6d5670783239506274387474753778524c756a4b436856664a4c7352775054384c3931634f6e6d32794f6e5a624d5331736839443764695a4d6b2b412f6d5a4b416d4c427a4356587237664d53782b6d74647333326b6b7031626136323278515a2b4e674d78783937686e327a4e422f33636854506a6d35355349424b6f2f774256656a58347a77382b2f77417263355732302b6f765648703849734f576d34625a59644565512f6e514666384130386c4e592f5939617638416b317479377a5a2f5865783768496d4e6b4537646e754230342b5351466638413971556542367a68764c6f5755676d347365773655566952515939502b6441756e3636424661413665664b675955634b414b413871445738664567576f4e4452364734765552476d786c652b6776557356585a4745515362634f46717a6455564f5674697378644c78536733444c6f4437616b7469596c595975626b597333546b6c756b364161465439497263326c5436726e487a315542385a3941645561396856795a6139323950374a366d42664a51342b3471746c7a49774134483575546a3231715837466b766c3570366839483778734c6c7370424c6845326a7a6f726d4d39676238423974616c79786462477230366e7031737832337953534f4256427878474e486b4a344d773555724d786e6c31546568635463746c387a624d5a6d336832444b5963674e694b684e77475a787a587346373079334e5a5a5847626a742b5a7432584a685a7350792b564459764578424e694c6767676d393673726e64624f4b6a43784f704b6a5778342f5a51424141433242767151644451626c5549464a4a7661344130734f4645786c632b692f546a6233764b71366b59574f512b51623342494e315433314c57745a6d76626f34316a525555414b6f4141476d67724c3053595a5542514866396442686c5a324c6967656134446e68474e575075465174567a376a6e3554695047517771334143786b49487430576f6d6374754c73316d4c7a75517a6645716b6c6a2f45352f645177736f59596f56364930564537414f507471716751714d4c4f6141365153574b5834414532482b6b36564a774c506856437541654f706f495734776b4663704e4855674f6577672b467633564b4239327731554e5a6e6d74637771766942392f68464d6d5543664f7a7367394a6235654d36434b456b75666139762f4c524d356273546170417641516f645470646a332f3861594a466a42695151366f7432357332707171335776784e4d672f6139412f5a51426f46514641554251464155425146415542515057674b4255446f43674b416f43674b416f4367564136416f43674e61412f595542514641473142486e773470515179672b30564c4d6a6c64772f543361355a6e794d4e70647479582b4f5844626f44632f484752304e54435857496839502b75635a3162457a38504b436741655a472b4b35412f4635524b6656556d73685a557859503147654552434841696467524a504c6b53794b4164423071697166727166484e38745a343461345030396d79454b373775545a474d5431507432436e79754f782f77445559457953667a477479534d756b69322f44777352635843675447786f783449593143714f2f536c56416d56675342787246477a62386c6d4c524e38532f574b533552794f37593533443957634346795244675961354441473179417874373261726957584d5732797a44763059394937624339584b73776141304e7751434478426f4f42396638416f69444b6966644d42504c7a6f68314d55304c394f7574756666556c2b4e7a45326d5979394d62786a65704e72387563475064735262534567687045587739657678572b3956326b764d53537a69747553736f5134755769354f4f64504b6c415a534f346d7557624c79754f484f62743657786b784a387a30364a4d544e5653302b45474a535642385854662f774174616c756335344f4d5977346a45334c4968517871566648593366476c55504332742f6762683771372b58463258707a31356d59534a42695339494767327a4e647049482f4143775a4238634a2f4b3931715979314e7363505859486b6b7834354a4545556a6f7250454744685377463136686f317532733435644a63786c616743744142666f6f4630363044433974417774416967316f4e6278336f4e447845554d4e4478673645652b7067524a38494e65773931537846586b3449494b736f4b6e6944774e59734666354d324b7a4e436f6c5169786a653977442b4567315a74596d4737427a6f3241526d59534c6f574a73775066573555644469353052694d4f536f6d6963574e774742422f457072557034637836672f544c427a49337974675a4d655a727332452f2b772f384141654d5a2b71724b6c316c35635a6a3774366f394e356a5954764e69644b394c346b6e416f4466774e2f3841795772356a6e69363130683333306c7665314b322b4a496e796f416a687845506d67327357655a6a647666705446616d30737858424f774c7356305145394a4a462b6d2b6c2b2b31567a754c34436745616d784f746961467549796a696c6d6d534346444a4e4b775645414a757a4731435063665358702b4c5a4e6f6978685a706d485850494259733534316d3876527072694c723931526f55425146425859757771703673682b706a71797154632f784f645457634a68746d6754436e696e68557245664379676b67452b33746f654669705569344e775263487476565536434e6e34356d7837714c7978585a514f4a467645767671556f77357a6b5141687275746735356b57304e42726e33504467596f6c3535687852434341667a4d66434b4671746e79637a4d594a49316b75443876454459324e78314d66453147633553344e736d63417a4e304c78365271787049316859515930454f6b5367486d7831592f545773446255425146415544396c4171416f43674b416f43674b416f43674b423042514641554251484b674b414e415542514641554251464161304251476c41554251464155436f41674561363042306939375542613343674c55477431754b4b727375456a784b4f484773324a554844494f6178485a59317a31383156586e7a34574a3635367a41787a4a397636316d424853795275464b3237525763622f4144742f322f5a7537612f475448354f7155573076634163613775624d4858736f70676d67474373705668314b51515232673655486c6e712f77447558706a63345a38494b4d45354979596e49753062734372726366636c585231726c3139556d2f792b75503841762f3841704e397678773666417938586534566d69485370315a623671334e644b3658586c6e58624d79747476326d474b51734c6b67333131476e74704e5a476e6965373762493238627338436f6d50424d376b4d3671656b735434564a385653643273785075753352747a6673683447335a2b35544a6a34474c4a6b79796b4b716f704b334f6c7931756b43757a6a4a58304236613264396d32484432783554504a6a6f424a49626b4669626b4c66376f35566e61357270724d525a32714b4c55426167414b416f4742514246785149727051594d6c394c58376144533849504c325545646f794f38564d44524a41725842346d6c694947526738534263566d366971793976567a636771343044726f525765596d4d6f535435654649444c64304877796a6b44323171624a6978653766756f4e6d527743654e6a345458535579737333463266664d59596d35774c49507545364d7050336f33477130584d764665646570663079335462412b54745a62634d45585a6b482f354341612f434e484873725532633975766e4d635a3457754141434459676767676a6a6539567a78686e307349757134366541493761475866667066365961575137336c4b656b41726941366148526e312b7170613661544e793950303977724c734451423465796750736f4369746d6c52474d6b537a524e453342785948734935304562416c59423865516631496962447474556845717a47354a3656417565576c55515a7436785972706a67354d7138656b67494433756633564d706c5649733033683665715679537978334141596b323050436f696669374d3353444d52476f3452494265724973697a6867686857305342653038536666576c62414b6d51554251503355436f4367656c4171416f43674b416f4367656e4f6756415542514f674b416f43674b416f43674b416f43674b416f43674b416f43674b41316f43674b412b71674b42554251464155425161354967774e7851564f54694e46495a5968727a41353169363835673533315649365259752b5930487a475874444d307341305a736151576d41396e477337613573356131326b6c6c6939324c6473626373474b65467736756f644748336b76594e3775446468726334345a7a777446506656475641364b702f5665797737747338304467466c556c5462573174616d3333682f6835332b6e6d387274652b74736534454b4a7a354d55784f676c552b43392f78634b366357634f556c6c727576572f714f48303973637377492b646e426978592b5a5969317a334c574c7a77362b4a6c792f6f4c394e424b553372314644314e4a2f557864766b476d75766d544139764a5070725869596a486e6d76546f30534a42484571786f426f69414b6f4863414b6d576d52426f466257674b416f4851464155553649567142577651596c52616731744870776f4e44773854516147546b52705552476d7845652b6c365743767964764a767065347461326c5a756f703574756e78334d6d4f656b38304e2b6b6d6b746a4e69627475357249336c536770494f4b6e53756b73715a64466a5a4d364b43435a4978332b494372685a55486650516d79656f6c624b4638544e4974383343426476384133464f6a557a6862724c3565573466704c4d795056446245573678413547526b523343474e534c73742b3368566c6e6c79756c6c773975773853484578593861465173635368565543774141734b7a613779596a6262542f41416f4861674b675656532f34336f6a594746794f664d56417951415353416f314a4a7342394e4254356d666a746d42385a2b70315878794158587147674776485370616d554f5a7337506b36485a356c42306851644b4432322f66557a616e4e57474c73746c486e73416f3452706f42335871794c49736f6f596f6c364931434c3241616d744b7a414654496441554476514b674b416f43674b416f4839644171416f43675a6f4651464137554251464155425146415542514636416f43674b416f43674b416f43674b412f613141554251464171416f43674b416f43674b416f4d57514d43434b43757a4e7053533749537367344d4b7a645a52787333707665646b79665032634754426b6b4d755274796e6f4b4d654d754b782b46767948776d733578355754506975693266655a4d714575343831565054314244444f4747685753423757622b4532724637704c68762b7534797354754f476a425a5a524335344c4c64442f774349563031336c384d57574e7935474f526354495165595a5350714e6152467a4e7967534d7247664e6367674b674c456b2b797061563562366f323759635064506e64797a41416f44486238556873695171657056754e4967764e71613538526d347a6c30506f2f625476322b536233366a426263594653586264756339554d63444477544b542f7565326e587672744c64626c72616334736569366b334a75655a4e616f4c5542626a514671412f6131413755426167664767584b674b416f4437614245643141694b44426c767976323047706f6752776f4e4c7738774b4455305a5045586f4e5459694f4c464c697067776a743665785a704659715649494949304f6c4a4f5577746b775669514c47545944673270725754347457546a5a6a34306b454d7a512b59434f70434151534c5846444661746b325047327a4843716f4d3541456b78315a7264356f534c4b696e5253745242796f4662583255447143712f766a4c436f5748716e41415a69514575505a7255796d55492f503769316d4c544c66345234596855357163315a597579496f42794736726663585252566b616b5755614969685931434b4f4141734b304d774b674b416f487a6f465146412b4671425542514d5542514b676471413771416f4651505767414b416f43674c554251464155425146415542514641554251467461416f446e5146415542374b416f43674b4255446f46514641394b425542514641665a51464155434b71525967472f476769356d31594f58475938694d4f6a57754466694f6463392b6e54627a47396439746646556d54364955743159473635754144786956784e45542f376377635664644a724d524e397274633146583064366d51465976554d5a57392b715442684c32396f4972566b596b482f414e427a63676a2b352b6f737a496a50785134367834716e7538414a707775456966304836646732716247322f426a6a6c494a4d7a44726c666a634e493132317654624e6e424a486d577a376c75507062315246466b7538754f6c6f6363794d5371776c69564333344c72596972706935732f3673625a6a33474b524a59306c6a4e306b554d703532595870573266373641306f43674b423042514c39394136413530434e71416f433141576f45514b4445727051596c61444578333555444566645162416f47764f67596f416a54393141694b5a436f43776f433141667461675641576f497937526742756f783335327562564f44435971717168564156527741466856794d6750716f43674b4146415544485a5148436756412f5a514b674b416f485146415542514b674b422b2b674e4b416f4367564136416f43674b416f43674b416f436764464b694852536f67306f43674b416f43674b416f446e51464155436f48397441576f46514641554251464166766f4367445148756f43676f643539466242753838552b5843776d6859535253524e307344652b756830724e316c6c6a5775316d4b756f6f6b696a534a425a454156526539674e4f4e57535353524c62626d733671485146414555425148665148756f43674b416f43674f5641554350313042373642576f6f7472524142514276514646426f676f4652536f67416f413042514c3972554732674b416f432f313042514641554251464155446f46514f674b416f43674b414641554251464155436f4865674b416f43674e61416f43675259436758554e614268685148574b422b4c6a592f516141755362646e4c6e514c3347674f712f4f67642b5139314145324f6f316f433539744158306f4631554265674c69674f6f55423164394164596f4472464141696766736f46514641554251464155425146416662514641554150736f43674b416f43674b416f44583230425146416371416f43674b416f4661674b416f4331416a51484b674b4b4b425551555548374b4956414767564274745146415541644b416f43674b422f5a514b674b416f485146415542636579675264527a6f4b374c39526250694b576e796b55446a593334636536724a617a6470504e56442f715a36506a666f624d4c47396a30497a4165384331583431503749743975395262507555617959655173736248705677626a7137442b452b327068716253724d69326e4131464b674b416f43674b416f43674b416f41384b4456492f5344666c786f49475675734d436e714e7a7941314a71577946726c643239635a755075734f49735945466c656670315971787459486c586270362f6c4c613850732b31644c4d4f76574f4b624751775a4568696c5736534b3932496257345969754e746c7858746d4c4d7975593372624e70774230484d7a6e797041577638777a64494f7655774e6576706d32317a6959664b39336654716b6d64766b31506e5a6538706a596b4b3261437a4e50646753414c487136545737704f724f31727a66386a6232724e4a4d592b726442732b516a7445636963724356596f4f71374f546363547270586d76666e6d79505a70366c31784a7473364d2f504c4d67386c4445784165594568674c58506872456b7835652b3366355359344736592b6450675378595571785a444379733178707a4677644c39744e4c4a5a624475303232307331754b346f2b6f393432694449322f4c6a645a6a6377794f355630627444472f5774657662723133737574664930396a74364a644e356638564a783837654d6a62386249664e6d487a48556762377430617749743964633974645a624939485832396c316d31766c506d6b33444c32325849677a4a4d664a776e5a4a664c4a437568413653564a2b4c767161535461537a79333362373764647574787471653062766c7274576130706b7a48786b6159794679475944377133476c72587656372b75537a4844486f657a64744e733335574b546276574764466c467370336d786d7376537a4669716b2f454c41613272653372356e466566722f593262632b48574461637158446a454f385a51612f574d6b46575a67626b4133467256355a745a65592b726575326362566f6b2b6132667935737664357377746f4d6152554858666951514c6931644e5a642b4a484474374a3079586262502b46564c766d3735576152675a4c497368486c77454b62446862714972302f3036363635326a35762f4e374f7a73787074695661346d6275793544444b6c45696f674c496841494a4e72384e6231344c744d635072366262533831596e4c796b654d43426d6966702f71686751417835676974535a6d63756c3373736d474f367737323859626263684935457554473667682f35695044563032316e386f7a336137325434625963707558726e66594a2f4b57426357534e656d5743516462645130754e42346137366447746d63766e392f7637363348782b4b54692b7139346d776f386e71514d584d54686b50515341434742376536733764636c77363966743762617a624470646f336463354a4664516b304a4379572b45334637693963747463505a316473326c57565a64536f43674f46415542514b2f5a51462b5641586f433941586f47443956415542514641554251484f674b416f43674b416f43674f796756415542514969674b4b4b415031305171416f6f6f46786f674e417558756f4e744161304251464155422b786f43674b416f48776f4334464175712b67424a37425152637a644e76776c4c5a6556466a714e54356a71702b73315a4c55746b3875617a2f77425550536d4e314c48504a6d4d4f554345722f716270465834316d396b2b6a6e4e772f574b6336594f464847704878544d5a57487553792f5856777a65792f534f573354312f366f7a7756624f6b696a504649677361322f6c312b756b777a62623571676c6d6e6d62716d6470473546795774374c6d717a4a4941654776766f726f6651323551344f2b52475a69735572434f63584944524f43707632394a73315738784e654c6c3764746d5235324d564d676b65426a475a464949594456573037713576524f55796f6f6f43674b416f43674b416f436754634b43723353646b6863716245416b47706277567a544d784a5a695378346b3836355a5a725868656b5939313347664e795a3369674b6f493434774157365151543148685870362b363636346a79396e7161396d32646e51714a747441773739654d6f42785a546f775544784b2f4b34376135623757334c764a384e5a7246466c595752765738795378426f73434e416a5a68554e317541522f54513275422b4b765431642f77414e6352344f2f77424b64335a3874716737786e3772737474757732534f4e3144746d776a2b744e793858563848543243743653646c75327a6e33372f38656644546a2f4b70586374302f71466369556959447a437a46697a642b756e757276384130363863506e2f387273764879726f66533256367065594c4665544456756d5935462b6854784a424a367231783739644a5038414c332b6a7433322f2f482f4c7335356a4443387651306855453943616b3239746554575a7548324e39766a4c634b6e4b2f77447347566a6d52594d58703653386345792b5978307662586e5858476b754c61386e793739706d54582f4145596264746f4731784a4f464c4d546b517771766c72435846796d68345834317a32326b7644707072666a4a744f5737626342477838304e496b795a6c6d615349336a36677653656b3931573738797a364c72307a4730762b3576326661497348446146774a486c4238362b6f49494936666f7139336264376c6a3150566e567038584c4a36436d6b334c496a2b59454f484552354c41466e5a4775514271423465466435374f4a4f4f586976367a4f39352f46302b456a37626a4c67745a3069414545774148557034334134466138752b3262612b6c7072384e5a72506f353365343574323374494d555853474d444b7957424d555a7553463850784d65775633364f3661613259664f3976314c33377a6e455273334f79765459574348486a624d6e557564772b4e436c37576a543774767a56755a374c6d33686d2f48316463617a387239565376715066444b386879475a355141533455445157485459615772702f78394c6a68354c37335a623558487037314a366a6b794668455a33434e6d43757236644e2b666d4161652b73647654704a6e7739507165333362625978386f3768355669694d6b6f4b685153774136694c44757278795a75492b31624a4d3153357235473577683032654c4b68735445636768575944384e754664707072726358626c34723362377a4f756d6466386f2b303759733230464a596c78735761547a386248694c46347a657a6739586165565a3332786379356136394d3659326e782f776d37547430304f566c4e4d717235386171455667656e704a343235314e74387959623675717932333672314c68464231494142506542557230476167564145304251496e5432304376514636416f6f6f67316f4865674b4230425148506a51464155422b3171416f43674b416f43674b416f43674c55433555436f6f6f44374b49584f674b413139394171416f446e61677a2b7967664b6750746f43674b412b75674b416f485159794d465573644142636d6734373164362f78746b6956595547526d79676d4f4d6b684655473357354866774657544c4f32324f4938303354312f367033454d736d61384d5463596f414931486434646672725578484b32326331517953535373576c5a6e6131697a6b7366727132314a49774a4234384f563669737930586b6853684574376879644f6e7374524d584938747a475a654d59594b7848496b5846364b784150472b6c41774e4c2f384143714e6b546c4a6f3355324b734e6232346d7871797333773934394a7a3437596b4978345678344a7364475746564b7172522b466774367859376157574f68427156735641554251484b674b416f43674b424e714b436d33662f61666c345457647642584f4d645074726b776e624a6b352b526d6e6234702f6c73654e664d5a6b6a566e61357552317366443946656a5854384a733554752f774473756d50706c5a627a4268795a4744685453794270706a4b697132726942537a42372f64376178586579665674793438364b7a34454d6379332f71524d537241666b746f663461736b74355a757478776f316d6a6d7a576a334f4935454d6a336b69654d6771784667774934563378695a3172792f79754e356c6651374873654934795938614f4d78335953584a41467538317a765a7474786137362b763136584d6b694475587133487833364d654d543373664d4a4b7162396c68725862723957325a74773850732f7464644c6a57664a6c74507176417a62785a425847796871454a4e6d42374e4b7832394631764474366e76363973356e78717778736a4e79514a44464844435365687573757a67473177414261754e6d4f48746c7a4d7872614a35354d694b514b2b494346434b53474c57386176555378466a334c5965746f6d6b57464e7649563159474a596976424c6166514b334e64735a592b6575624c66427a65724e705452504d6c4a48554346365151644e43317136612b7676666f38335a2b79366466726b7362664a3978796844674c464741704c795464544d427a3656585130333662724d3176312f643037725a716d626a4435695159375a4c527979796a70634164544251575a52326547754431325a6a5250464c677231596d4b3252434464306949566b425078644a2b4c37615353756431787a46466b5a57486d5a586c376c476a59737a4b4356366c654d674541332b327652724c724d797648766464376a65634c654430627355547249736276306b4d466469796d77356a73724e3739374d5a6464665136705a5a47334c33766164745a6b696a566e5941757349565277734f6f697270303762387339337539585463662b475731656f397633456c4662795a6863694e7941574843366b38617a32644f326e6c7231766430377379655569484c6c6d5a6c697858534e53514a6e4b716873626541416b6d75582b7231356c384d4845375a457341557041464465636a4471366d4a366c4150443230724e69506944613143344f4c6b434951457336527551796b473765623161362f6d717a4d6d634d356c754d72774d475546534370414949344547744f6771414a6f466567563966625146417269674c304265696939415848756f676f485146464d4853694865674b416f43674b416f43674b416f43674c66384b416f4639643641377142576f4457674e66384b42554162304252536f67762f6e514b2f4b673267304161416f416155425146415542514f3142443354712b55634b6245672f5a552b6858682f72384f5055556e5553563649676d6c6741454671317234637235633363323736307a522b2f6c515a4934526778554e6f52306e55616931437a67726939774e547a345544366d36656b45394a4e796f3458476c4142574937416146724d494f3375302f774136715a5a464673514f66766f7a6c36722b6d6d633737564649374f78686e434d574a4b685a4636644354326970655854723465695773626654575859564147674b416f43674b416f436754384b436d33662f61662b45316e62776c6330352b6d75534f67394b3430613438755343544a4d335378303043614156313132746b696136535733377558395735323953656f495a38462b7337644b304d4d5561416b4f34485663452b5071476c656e7230317879385066336233624776306f792f574f374849686d69682b586b6844527a52324c497a456a6972577352585854313963575a655474392f736c6c7838585662663668452b334c6c5a474e4a4335385053562b49674456623871386e5a724e626956395870372f6c724c5a687479493866646f6d78576b654c70735371485378476c394e665a5472374c7263794a3239553774627262687a5737345777375133526b354d302b5142314a69773244414561467966673939657658763333385238766630656a712f6e74622f67384c497755774e7466485141644c4e6b45676b6951745a7574695046626c584b7a624e7939656d326e77312b4d34587530506b5137424f5546706f6d6c474f74756f673375764876726c7669375056305a6d6d4666365a79386a6273463866644c4d664e655835744431686d6b50552f566238315866535877783164746d5a73714e34784d6a493343546359482b626a6b5054484f457346353950534f664c7172313946316b78395879503245374e74737a3874477a41326a4d796e505643313249566a497073426253787270763361367a79382f5236652b39356a71746d32534c6242495133584a4a596456694c4b4f5774654874377276683937302f5331364a63586d75643956626c75713770695a574a457054623364564975355a33415667774849725775765357584e59397276333176347a78576a493965792b626a79597342526b4444496763335269624157596136563031396163357279372f73724c4c72503958513764763230376a686e4d6c69564a46384d797567617a57755172573851727a396d6c30754c58304f6e324e657a5835595370794d2b467366476e386872416b41616c5350626f4b7a70744a63324e646b752b746d742b4c6d382f59735062315639793342496f69627852717064337350757258736e73322f786a354e2f5636363837372f6930347357316a6273444a6951537a544f37544f317573414779716266446175653232313275586f36394f76585366434f6c32584a5664726c736851597a4f50455333553175712f737268764f5875364c2b506857656d747a793158495865554d4f56504d5a6c6c4e756768674c495077394971376153535959362b323232624b3366345a706433664b41535743514b6f61496b4b335438506e486d793136656a585848506c3833332b7a656279536669377941577834683249756e754665572f56397258784752306f6f4a71424530436f4651463642586f70586f416d676436494c326f70336f4744524265696d4452475636416f4367644171416f43674b416f43674b42643141586f4561416f6f7472524252533736494f644171414e4171445a514f674b416f43675032745150546a51416f44576730355342346d423445476738662f557a414b506a5a6167336138543636585133476e384a7070396e4c6279345145386865747373724d5272703761594c52303842652f4c53716c724e564131747a35304d6e6632554b42634868796f6a49642b766451414f7034485868325552312f36633550546e7a597775586c566c436931674c6459502b706174384e613235657a77796562436b6731363142507449726d39455a6a7371416f43674b416f43674b416f45313755464e752f7744737566796d73376545726d6e4768377546636d575779626a506962784d7173544555517647626b4561693965377036357431766e643373626466664a2f747572547636664b656f335a587375544a466c526e73476974773432616d764f754b765a506a325a6c38387364302b6367336565634d30636e6d47514d756c69515046304545454776547070727472487a7659396a74362b79382f5662596d3672756f454f543078356f4852446b644a36574a2f4b543461387664363931356e68394831766631377354626a5a4e33584b585a4d493546684a4c70446a512f644c4158367a374b35395858643973505233397336646274586e5567794d795a6d636d576564756f6e55733773316878723658786b6d506f2f502f50626666506e6175344870374b773968785930426b793435504d6b684669765539676550344b384f335a4c74612b357036313036354a2f4a75335465492f547531523476554a74796b566d565238494c4856322f4c3264745a3030753934644f33763136644a4c354c46322f48335447546374736b38687037484a785846304d69697a58412b4671753231317678724f6d6d765a7238394c35614d5847335044334656786b555371515a49326c48517750763430327373354e4e64746473526662794d773758504a69466f38754e4f75507049755375724c3336567930786d5a6572756d313076782f6b35765950554f65732f546c53476547516a566a647464415676587537756a58475a48777652392f732b574e376d4e75344c4e6962684f6f4e7254696444794165787561382b746c6b6653336c3132762b716f3349736d39547939437065547a47547056347977417553434b394f6e584c724d563872763841613231376470645a6556356a376842757353343456494d744151694c64595861326e534c56342b336f3231352b6a36665237656e644a4a7855325a6f396e784779386a56596c56466a4238556b68345759317a3030753178486f33326e584c747439486e65345a6d567557584a6b5a4236705a4c69344a4e68665246484a525830744e4a724d522b6637752f6273327a58563432795a4f4836616a6b6b556d547a524d3048535377566746556166536138322f5a4c76772b72316576747230382b664b324f644673577969584f73636d6373306341314c4f52634c37427a4e63624c7474695058387031365a32714e4468486363524d2f624a41384f5141636a436b4e674a426f2f53543850737264322b4e7874484c5854357a356158692f5247786f397777737859343865566c4c6454346a4b4f6c6877766356625a5a6e4c4f7575327533683259493652595730476e5a7077726b3935314b4d54514b67563642586f4d614b663230515542514641714b657441584e41776149476c5242314d7755637236554767356f4f694b534f3036564d6a487a3557346d773742705179595a6a78596e3330526b506166706f30794678774a46426d4a48484f2f746f6a4d53672f455066796f4d775152634739554641554251464171416f46374b416f7055516579674b4b5645464157317651624b413555414b416f436766746f465146412b2b6754433474326967344839516476382f5a6370656b45776b544c323248684e767071547978763465514b543067634e4c4733614b3675564f2f4963534b46463763443764655645414e7a39517652543538644f426f5a4f3968725244486672514f34754f46425a2b6d73787354656f5a51543472413950784571516243353531616d743850657470652b4955494b6d4a69765364434166454166707246656a58776d4373316f554251416f43674b423055714954384b436e33662f41476e2f41495455766763772f4f755444667475486a5473307432584a4e304c63564b675835634b39505233585757504637507261396d30756679545a6348624a6649774d325750353643595359615273444d464a44414732746a7a76563332747473644f7272316d733032764d384c766364727873386631567449425a5a427841374b7a31397431764472332b7470327a473055452f704c4b696b36385351503041434d73656c754e2f717231612b314c4d6252387266395674726336564c33375a647733694443694253466f62744f5775515749432b4731636576746d6c746537326657323774645a65472f593970326242686b5349695364584b79795442524947484141566e7433323278613665723064656d5a72355a354f666c5173594967732b58476f4944487057782b4633747746635866613256797671625974306b6c47347a53706b766b4d735a696a55715131724b714b62335556374f6a7431786a4435507665767662383563756f326662493844594938584a6b386f734338306e56304657665569392b56656673332b57317366523966706e58317a57315244476a457272687a525a3639567641513070484878443939646333484d7738324a62666a666b6c62666b535975566a70464f38496d636f636559466f695343654c6136564e704c4c6d4e39647575306b70627274634279444e6853434f53556c6e69427370494e7955376a57756e324a4a6a5a357662395035625862533471796661317a4d4847664a4a68793467464c45335a6744667049504739637474736258486837394f753761612f4c2b535475657a592b63566c4145655167737267445557745a7255362b3236384a3748716139754c66354b47623037754f4e4d4868416c5256486a58513342757467657976544f2f577a46664c322f58396d6d3075764b58366a776430335862734f444869506d3952664944454b464b6932742b2b75505674727074626139337464572f62317953632f566c734870586263614a6e6e4b35655172644c4f515149794c6545413037653761723676703661632f793254386e634a73634153495a4a6c3045616358504c6a79727a505874745a58482b726348653871542b355a55534c6932574d527875584d616b2f4364423852346b563766583231786a36766b2f734e4e37666c2f746a707653753254597670394979786a6d6e764b53427176554c4c7846634f376158624c33656c316648726b766d717a4a78733747795765615535424442546b49354f6831486855334662317373346a472b75307562585a723843396c6871665a584a376f526f45536167786f4554514636416f436756464641586f43346f4334464158482b564551386e6341726d4b414235523854483456393953307461464a4a4c4f78647a784a2f77715a526e3171425449444f6f7672725449596e7562436b6f324c4b7835305673575136586f4d31634769737751616f795669446f6662516246634832396c56475832304251422b756756416579675646464171494b4b4b49564155427051624b413555414b416f43674b422b366756417839564145554648763249733054787350444d7252472f5977494653386370744d7834446b7774426c54514d4c4e47374b623862676b56306a68394746686654676633565647685066665767664148746f6d4259453638447a464441747237656444444c6c714f504555517877734b444b4f566f7059356c4e6d6a594d703543786f6b2f773933394a357a5a4f4a444b35753838495a7954636c347a306e6e32566d783330755851446a55725971416f4147674b416f43674b4250776f4b66643765552f384a71626545726c334637387231785a7262746d5246466b6e7739665343636761334345654733383164757653325a634e39354e734957586954525a6d345a3244454d48487842357a357a664557554267674a343954615772305a6b6b6c65616162586137546a446f396739535a4f5a6b4c6a35694b736a4c645855454174594731696176643054575a69656c2b77765a7438646f747477335841322b4d506c7a4c483158364534757848494b4b3461365861346b665137653758535a3271722f4150764f776458546559482f414e736b2f5561366638666637504e503250546a7933625a6d656d73724c6e334845614e737952415a657062544256483457724730326b6b73642b7262546133625773646c33374433563873786851524b5569527242336855414239646454656d2f58645a4b6e5833363732794973325068374b30753735307a7a35485533397667647965674d4c6557742f747265756473617879374a723132396d334e2b6a6b743133624e33764f756b625346724c486a526c6d73427a73507472313661613654792b523239752f6674782f77426b725a50542b364a6d746c5a526c774d664475383034384c384c39456662666e585073375a5a696376523676716261333537666a7245744e77334b666359736758794a695448436b347546446e5143335a7a4e617652724e624c584765373262396b736d5a396c6e6d506a515a6951537356436f524d7947776a594545614468315872775453325a6a363232306c7855614673694d547a346276506b514b306a5a6b354c44706a3173623661384b37356d4a4b3536357a624672736671644e796d45457349676c4b33466a63453275514c3037665875737a4b6e7166734a323766477a46584f526b775938526d794a56686948463349413931363453573345652f6261617a4e71422f396f395058492b666a7638417a663456762b7262374f502f414375722f77426f787773665a386e506e33544779426b65597168305753364b5630366967504770626353574e6465757432753274387364757a3974334c4a7a704d64566b454c4c42356f3473716939782b58717162615857637731376464375a4b696e466d785a4a397833544c5a38484659766834783652645144385941385466687257754c4a4a4f584c625834333537583864584e62393672794e776e364d646e68784341424347737a4739376d31657672365a724d33792b5637507537646c78727871302b6e6348644a4e3352735872787845512b524b796e705262583851507864584956653766576134703658547664355a7848714173564859515073727850766b6144456d6f465159336f416d67562b79696c31554231443230517269696c3155433678514c7235636143757a63396e6377514e59445357556376797233316d37665a4c556447524259414144376177794879677645366a68544b6b736b73703030486251626b565278314e61472b4e485943777433384b734739596c41757876334371754759614d6537746f474a6c3557393141784b54796f4e69733574705257514c33766131756442744572573147764f6d51435148694e61444b345050335655426f46525251464171494b4174514b69696943677a6f48784641634461674b416f43676574417142304251514e31694c774d62616a55653061314c7a4376442f414633682f4b2b6f356d41736b345755667a41477436334d634c357167467a797456444e674c3331716776666e51476f48666569477472584e3730442b336e51467638416a524d4d72585569334566625248712f3661357362374c6a76314d5a494d67787a334973424b6f465375326a304b316a6273724c6f4e61674b416f43674b416f436754384b436e33662f6166734b6d733765427a44665a584a685934454f333475305a5737797769544a4631554d5356384a3841734f2b76523035746d73726a335858545737326375537763724a3344316c6a525a37746b517a53714869596e6f4a5a434133515044346556657275316d7375487a76543762765a3875666b3748612f545754446c72504e4d4169506f674244454c6f4470553750596c31784933362f36376254663557385a5370396832664f79686e5a474d356e613653524d78414a556b586178727a7a7532316d4a5875375057363974766c74484a5a6e706a4e6c794a6e3235567938554d316e787a5a565948344c6b366c65476c657a54324e624a4b2b5633667239357462724d77596e707a4a7770307939315256686949644d655277476c59666441423464745466746d307847756a3164744c387435686279354c3547526a3562702f7742326a6a354848685656497562574a48336532754d316b6c6a3237646d6358362f523057554e74696b686d334e6f6a6b4d436b4b74646c366a596b49684275613454613879505a6464625a64707931354359754c6b6e4b6769364a73734c433351746d636f437938425574746d4c5336795a736a544e48754766746b6b4d6e536a73774d55535849365162325a6a39367439573031326c7268333964374f75367874326a5934734a466555423867437974725a52572b337575317850445071656c4f7153332b5346764f333762742b506b37706b7338387a4d47574e6a5a576b4f697259446855307432733169392b756d6b75396331732b37356d35627044747555775862736c3245754e4741696b6c535271504659455633374f75617a4d654831665932374e2f6a742f4772725a3967334b504e575a7771527879574c58467946384949713976667264634d2b7436472b765a38722f47564f334859634c6463704d7565655978367163634777366c4a427466346138326e64645a6952394432505531374e7337572f364f503354596337467935593459586b69556b6f59795a5346767031454453765a313932746b7a58782b2f30393964726966695730624c754b356135633654592b4e445a706e556d4a325339756853624672316e743331764831646656366439626d353131586250694a4e486d34324f7541754f34454b524b51386f4a74307542385856584753347861393232387a4e704d4f6b7a4d44447a42464c756755526f4379347a7341675a68613534584e634e64727234657a667131336b2b55513232765a38444c2b6678345930383552454259454267535155422b3831586266617a467245364e4e6679316a5938325a6c344d794a45496957554b70506a646269354e75464f7179575a54747532326c6b3872735743693349416656523649524e515930474a4e4169652b67784c55474a6167526167526258767142466a51596c782f6c51596d5331424233484f5a46454d52744e4c7066384b39745a3276304c5546696b456171704844556354657357345a52354d68693168652f596579706b6234494366452b7035437249715975704372394134567045714f494b4c747161314972615a41425963425656695a474a7350706f4d6b69647a726533625545684d645647757471444d744767755342616c4561626473474b346b6d5662636952574c32617a7a577072623945522f564f7a6f624e6b6f4c633769702f6472397a3458374846366f326551674c6b6f53654136682f6a542b3358376e777632546f6479787062464a4659486d43434b334e7066465443536b364e78735233566f62425969366d34374454494c6e67644b71436969674f583771495641476756414767506651624b41316f445832304251483230425146415542514f67315a433955524231754b44795039554d4b793475574272477a524d62637239517070666f3562546e4c677444573254307167734f363941582f414d714233476e62524430477034436844763736464d614474426f6a732f30307a5375646c594f674571456f5350764139536a36716c613172324f47547a59556c4776576f62336b566c335a696f4852536f676f4852536f683056692f43694b62642f39702b33704e5a32384663793534317959534d6b786a3073537862726163694d43355545637a627572312b702f4a3476324e7830334b6f394762655a2f56637554592b5869495a4c486a31734f68663331333972624844792f71396338333650514f724f66496441304b777061355871615335462b6b672b4556346e326d70316b6c7a4a4978496a5979496f6e6873512f6d4e71703667654854526d717a50384156473362544d32474970426b524b434d5a45434a3074775a572b4731644f767075334d656276384161313676494f4b322f466477783874524141455643684a516741734e6564617a644f4c44347a75787472654674742b31596d4733576f4c7a6b42576e61786133594f77567a323374656a54716d7668796b66714b474831506c5a4f356952766c5330474848474151684a7438504735484f76523850776b6a3530396a486274642f70784670692b706d794e364d4f54424c6759336c66384162444a55715a4a43626c69546f42625156797656635a6e4c31543274666c4a742b4e2f7972392b33444947364d6d46504c4647464335437153496d66743046656a6f365a646337523833332f41484e357638644c686c746a377738697041376d556774316456304e7533717266624e4a4d324d657474333757535676396365596d7a3479797a687054494c7059414f514c6b3666687268362b506c62493976374c4d36704c564e3646776a6b623063672f774333694b306c794f4c4f4f6c66333132396e6247727966712b76383774396f37707063737a4e47754f465262457a4d3449495035514f7176412b363079664d4e6c74456f5559356a36704a46617a687962414432696c5a71436436325061466247575259536a58664656574c3954613952422f4632317654723232385279374f2f58722f6c57766363584f3369574b66476147544551414947593344455859746174613261356c59374e6232597376347032336248426a794a4e4b664f7946466b4a466b54583771316e626533683130365a7263315154623574382f71642f7743367573574a74705959306271584453634378494847753339642b45784f613864396d6633576258477571797866564731626c753659654f56614f4e433676497058716c34414947484a65646374757661544e6a31612b7870745a4a556664747a79735864347366446d554934487a454a55456f6236465750432f5a585870365a74726471386e746535644e3570723564576558752b79754c3654416d67774a343142675451596c744b44457451596c7465366f4d53776f4d53394267306f47743647577470752b706b614a4d6c56556b6e5143357157696f4f51575a3532315a376865355277726e61492f6e4635414353464a755433566a4f614a3053396233493048432f495630694a4a4a4f696a33316f544945434b43514c31715441326456416864694261353543676c7734313746766f35305844624c4c4643704c454b427a4e533753544e57533377704d2f3146596c4d5a656f366a714a734b38665a37636e47727672302f64515a655a75475466726d49423136564e68586c323774723572724e4a5045566332437a456c69574a356b6b6e36367a4e6c77695334494950684874745735756c31566d54674b4e564675386147752b75376e6455534c4e7a734a2b7143643073654159322b6731326b6c597473644c7333727650526c5449496358417564445575323276684d537534327631506935514136756c376343625631303770664c4e3073583857516b67467944666e5861566c73346634315155436f676f4367564155436f4e76756f43674b416f43674b416f474b41316f4367544334496f4f442f554462666d646e7a554333614e524f6e626444592f56556e4659333850486c4e31483131306377442f685148743174786f486363665a514d456d336256414f48743531417751534f4a3134555373676444727935634e4b6f7476532b656348664d65594d56556b416b416e675154773771597954683776745569746a4d674e784735436e3872654a6674724e643962776d437331546f70615551364b4b425551364446754866515532372f414f302f384a30724f3367726d5874714b35496d62493073795a754a316e7967464930446448574c47793970727070624c6c78336d6379746d38774a7365796d506277306332564b717954332f714e6f53543156367572505a766e5a342f5a78362f546a546874394b626a6c5359382b4d345730554a6b6a6b463773354a7631456e7856665a306b326c682b74396a626653792f37577630376c6e4661525a324d7a355a4f566b354445583677414774334b4261316374744d2b4870362b33486c51376a506a376c75306b2b55434d6376595a43584c78786a52626f66695832563639657662545868386e66324f7676337874782f6c31327762434e724c5351356879494a3142434651463131444b5161386e62322f507a483176563957645763584f567a6533443356796574792b3734574e6735506e795242347067784f516f7449726b3338667437613761584d786c3465375761375a73527369627a492f4a7a7576497856316a6c7541794675647a7872707062726336755064704f796648667774635030317434565a5579486c52686457556741672b797337657a74654d4e39663633726e4f6256704e4c4469347a434d685751414b6f467a663243754674764e65376a57596a6e74794d2b363463304576516a39514555736932575071494934666570707664624c486c377466374e624b6b664c4c366432514c6958624b6d5942354748695a72472b6e6479723061332b7a626c79374a2f78756e384a2b54503033756330384d365449665052444d30704e7937584931396c717a3336545862687630652b37365850386b6659747763547a6e4d59795435724449647750436968656b495079714b7874706a77363966646d38756533695448335465336b6c6b386c51336b785a58474d4975696837616a586e586f3031756d7563506e64335a7033372f41426c773672303373653537533069545a435459736f3667696b3344332b4c556378584c74374a7669795064366e726264575a626d4c3753396348746368764f30594d4f342b666c78447279437a4c6c4456657539374d702b45396c656a546132596c6650372b7657625332663957764c6b544d43513578414d5976426c784b4377766133565944773172546136336879372b7564736b32765033574f4c3658645a524c4c6c42777844456746693172574e7a532b7a4d596b585439645a7438747473756b4a2b7975443662452f585547746a5161324e714441745161793151594d2b74444c42704e4f50736f4e6253314d6a5538317236314c555270636b432b745a74575243795a3264536f5078473330316d3349307a794259784341434c676c72576251577465735559777771416a4268314d54314b4f4b67556b354b7345495650724e6449796b77432b70356167477452556b4e7033316f414e7a59616e6c5154386247436a7159584a3170467757626e5234385a4a4e323441446954585073375a724d31765853374f64793871624a596c79656b63464843766c396e62643779395775736e68474d51766253756254487951614442736364676f4938754943547078706b562b52683864506632567162595378523768676c626b44754a72303965376e767171485571334778484376544c6d4f4e6d46727465354657434f7830493657356975572b6d4f593372637532326231444c4379785474314962644c316576757334716261532b485a346d576b794167676769344972325335636b6f4771445771673736414e4171414e4176736f4e6c4136416f4155425146415544306f465146412b56425337336a7249434746316b566f337677733449715836564c4d7a4477484e786d784d37497857466a43374b52324147316449347a773061384f664771483754705148667948495541434f523430475850586a776f474e446636514b49595045366d334f694d672f51364f4c2b456736646c3747683948764870504e452b486a756f4957664855334a7634346a306b6139315a727470562f55624f6f485252514b694852536f684e774e425462755036542f77414a724f33684b35687a2f6a6232567953704779766c52545474444733544956426d5662326343796939656a716b7335634e37744c784572665a4a732f594a704a6f475449785a523164514b2b454778646238694b3764466d765a4a4b382f7653373946746e4d5350522b4b79517a5a4c43346c736f4e7443415354577662326c736a6e2b6f30733074763154385059386544497948627877796772464752634972664661396566353368394458706b747a39584e5a65315334325936494c4d684236313851364742314e653758756c6b7a58776533314c70765a497550532b593351634279534143384c64674a315776503336542b556653394476746e7771767976575735344f645069354f504649305439415a537936412f4672783668577466586d327373726e326673647576653637612b45687477693344436c7a454c6551377247794f4f78674430714f477465546653363346657254736e5a506c473531322b4c4a68673353454632424b544471414242745a746133706e4677332b4f5a4e6f754a5a4d4c62634a70416f6a6754677161334a35436d7574327549366237363965743276694f666933484a335365664d687877793434565934754d6971774e6e30343131333635722b4e72783958662f64506c724565444f6646457036435a5a484d6a656343517a6a526c746273465976584c345762335738786237706b7735757a52356b56794152645272303330594774644d7332785639797a62712b55617653324d5647524b77505177434145334146795342577659764d6a6e2b7431736d3162634830387357546b2b6165714571557837453356584776437556374c77396576524a624b356e4b327558476e6b696b734a417742307547556a546858304e65795753782b633766573230743164423654334a706366354756695869424d4c4739776f4e756b332f41413135505936766a6378396a396437667a312b4f33386f305365743438624b6c78737644644a496d364430734343516245362f534b6b3965325446586638415a613662576261336738764e683348476c6e52784e6873565455574347344273547831727a375336336c332b63336d5a345035586270704963584e5a73575677664c4373416a4147335477304e623032754d776d7574784b764949636262385549474b77523644714e375850433572504e723063617a2f4359543964616161324e427163324a374b67314f3142725a75796f4e54503964444c55306e30564561326b356b36564d6a524a4f42667571577268466b7943644c33724e7068705a74535362316b61796279714f776b2f525152356a6551323458724b7055517356476d6e3771314755754e6c444871554d4c455750492b36744356456257476c6147304853726b54735048765a32346d72466a626d5a535938525050674233317a376579617a4e62303174726e63695a35584c4f626b38427946664a333375317a58726b6b6d4930322f34566c52627346554f772f796f447042397454415269423563616f6a54593450416138716771632f447570467550303176586243574f557a38637049624377356439653372327a486e326947435177493072725a6c695630577a356f6d6a38707a34674e4433563574396356326c7a4857624475306b456977536e7733384a504b756e563259754b3537362f563232504d4a5542423556375a6375626272617143694130436f4436714255477755447451464155425146415542514d55436f485151647a6a4c774e59616758487531715763447848313969664c2b704a5a514c4c6c49736f4e72416b697a567657356a6a5a69317a7037755a3464396151472f445139394157754b41413464394137397647674e522f6c524758492b7a536948594653447a465565712f706e755a6b32324b4a6d422b576b414134454a49437266585762485453383465696e512b7973756f71423055554252425253306f684e776f4b6665503841616364716d73376543755861784248623956636d616834573535654436675a6f484e68476861496d797344653474587539665362615756387a32753762723764625073377779342b666a46674f714764534347484969784276586e73757466536c3133312f77415641336265736261596f73544855655971674b4c614b6f48316d765231644e337474654c32766331364a4e645a797163504f3366644d372b693931537a53416875686663445858743030303173654c312f59376537737a503470793473736879595a4178485571797174305a6d494274652f4470727753325757507133544d78596b346578746862684650435163645156735362674d4f2f734e656e62752b57754c35636572302f362b79625477783952656d496432557a524d497331567348507775427744572b32733958646465506f33375870363976506a5a7757664475323339574e4f4a496c4c6454493177724d4e4f7057476a56374a747274637838626270376575664779794f6853544c797358625a386b466e6e694b74493169485653656b6b446870586e736b746b665331323232313175336c5a3545723558703042724134736e5249474e7268545a622b343036704a756531627430585030552b50464d6d7a376a6e347a50446b34716f495a6f7956627036695730504b3164652f4879316c6554304f4f72666165555059665532626935716a4f6335474b7a576b563748704c47335770493071396e544c4c686e3176643231326b3275646139446543436647614a5170686c57774b32414949754f466543577935666432316d32746c562b62754f48732b4d6d4f674453416546436258734c6c6d4e64744f76627374727939337361657672497034665547375a3257457831486c6b676d4e54306856747a613164657a6f31303174766c344f6a332b7a74374a4a507853566a6d6c47556b6862725971736a714f70786344777263646c655058617979766f37617a61575836746d3362526b344f367876306c735967714a415271534352635636642b32626159766c35756a314e7572756c6e38577631523656665059357546595a56674a4954594277423930386d71645064385a692b472f6339482b792f4c572f6b3468733363634f4758414a4d61462b7557496a705a574675527231585858612f4a386d62396e5850682f46306b6d5a4e755547337a35494a65614d7249534f6b4d5178485570727a66476132795071663258665857324c5a7371544d324b45715357686d574b596e6a5a4459452b3270704a4e6e5475327533564d666430683444324375543374624851314b6a51352f7a6f49377459477052705a2b2b673075397832646c544b4e4c534163655654496a535467413231724e716f37757a486a39465a7447484f31525162615547424e70443744394a6f56474773747a32316a36716d52456458614c56754d70455a7564655661694a4d625872516c3473666d796763687161734a4675537355642b4668787132346d57704d33446e387a49616555735434526f6f37712b5433646c327558733031784d4968346d754c5447314d41392f6656446f4d675038414f6766374767785a425441695a574f4370484930534f52336e4643334e72647471394856747935377a68514f4c483761396b726a596b594d3752544b774e72456658574e356d4c72585878743178704b70735141644f4e6561634f6a722f5475346d534a5659366a516a757232394f2b593462544664466348586b6137784263647456436f4367564136444c322b2b67644178514832554251464155425150376142554251595471476a4935576f504a763151322b2b4c6a5a674869676b61466a324b3369464e6239485061633565656b6d3162594775745550685542596e687835315570384e66726f6f4a31374f3667594e726738744c305a777942304f6e665172735030327a5754634a73516d776d52676f3031594472586a33725374612b5873384d6f6c685351473464513376497244757a427142305571494b416f4367546336436c33673268662b45316e62776c63773331636a5849546468394e3475646d54353255574d5a5659306a5532445776315872303958626464635235757a316464392f6c73754d68447470614b4a72596245474e542f77417577385333506278726e7462626d7574316d736b6e68547937584e752b37795a55697444743671716f774945306a4157366c566834567230616439313178486a3766533037643774737266554f56756d327375335973356a77516f365a496759336b636935457a6a58714864585871316d2f77435638754873396d33544a70702b4f716b78337a58646f595a5a4c7a477852537a466a652f694636394877316e4e6a3575766276626957334c725053324c366e53525736326977777857564a376e6871656c5434683761383366644c4f504c366e6f366438382f7741663875727970705959486b69694d306969346a5532756666586b316b7478612b7076745a72624a6c585a4d667142385635456d6845675574486a6c41774a4176303361756d644a635965657a75737a4c71776877496f64736a5764315a6f6730797a5341496b4a635849414842567247322f5044704e4d61795879325957426a4e742b54454a466c697a435a52495262714c41447141504c7372587a755a596c364a646474622f7553734c62494d66414f49344569757057636e3733554c476d2b39327556364f6964656b316a6d4d5030444732546b664d354c4c456a394d496a41366d6a7464574c477539396d346d49384776367657336d384f6a68616245684749396949674244494e4f714d4469335977727937584e792b6c4a645a49357a4c784d6a654e346c614d474c4269554b63746c4c426e417434464238513736394856332f41423178487a752f3035336235743451647a336663646b4a3237466a6a68637148664d6a48573877623777755044374b3372702f5a666c7458507437762b505068704a503871744e39336b4351444b632b6459737a4737467450457041384a72742f52727877384e393373357a58522b6c643139535a4567516f63724436697279545848515271624f4f4e634f2f545365504c36506f392f6474786564667536334a79457834476d64575a56467945467a39566558585732346a36665a764e5a62564e75677a386e4666496c32764779455253346a6c496151714266537772724a724c6a4c7a62376475307a3849574a74492f746d4c484b464d6b494c3479495346524848557362467549577337623474773372706e575a6a6474323139474c6c787556597a5369594654645162445157374c5662767a4c44586f78725a6671756a2f6857586f61324f6c515235547051525a47494652455a33734f5074715769504a4c7078724e716f306b784a747a724e7068707553626b314b4553665a3331464b35484868514b2b74754e42724a5056707874594370536f7761303175562f72724f565334325062797255724e535979434e443761314562305943357255463374635054454749314f746269786875303556424744713268396772792b317669596a743036383555372f415043766e5636576f6a6a66364b675234363151576f4742515a634b413134665851507670457247564c72532b427a472b7732567244686531644f713870744d787945793259322b7176647265486e7335594b534e5277374f646173535631757954656268726655674378353661563564754c58534c72614a7a426c675873436133316259724f387a48633430676b6748626176644c6d4f555657626e5334306a4b4331787735316d3759524348714c4b56694170594474465a2f73713454494e2b6c61335845515432634b334e386f736f38774f743745583556715579322b63765465327442497656447651484b676574415542374b416f43674b416f43674746776144682f586d332f4d624e6e52426273453835427a366f7a66374b6b3472472f68347970485344794664493530455876726f4f4e55484157504f6f4742332b79336656536e62674f564646752b3366514d6a6a70726567643744766f6c575070374c4f46766d4a6b71316d52776232766578312b71684a7a4b3939326d514e6a4d67314554454c2f4333695773313331384a6f71565255425146415542514a2b464253627952354c2f414d4e5a3238466379783030504375544e576e706d664c6e797063495450486a527148495143393231735749754c31364e644a384a73347a7576384162644d6366484b783365506232334c627343614d73306a795a4d514249414f4f6f4e3231385878566a46387539732b72646c775a35496641614c6a6557475547374138305948512b32724d5a355a3231746e436f782f6d6f3834726c34387552424b7838394a4544414d52594f4e4b36327a484665665762664c47334d587679653034514f51734555505143524971674558374c566a4f3231786c3375756d6b2b58455647346572664c636a47565855614b7a584a617735577230616572624f587a652f39724e622b4d79326254367378737057697967596370626a525355617737527a377178323948787646656a3150656e5a722b552b4e5765484a6e5a456154534e457362674e477356324a4234456c713457597548746c7a4a57714e426b2b63574b5a474d4a534945413048546f77612b6a654b6f6c6d554a665547786d52354a324d636d4978514c4b705678494c6a70525271667372704e4e7278484b39756d7562746359456e712f4356756c496e4c616647516e48333131313958612b586d332f6164577645356273446373766373686c686463654641432f67366e4f76414d5430316a733672726a4e6476573971647475496b5a3057504c6c596d49387a72494f71666f42424c496741627150346274584636724a664c546c6a4f786250695977795965443262706442626971323859704a4c6558506157544d696c686c7873764e454736524c50464b354e7968526f3349413467364376527a724d3631354a38643973627a4b396739506248684f4d69504856476a756574695341447a314e6337323762635776527236765870666c496a5a33716a4378475a4959784b4650694b6b4b747a3261563130396262615a727a642f374c5472754a4d704f33656f4d444e69506a574f59416b77735143665a6575665a31585734656a312f6131374e6379742b4e6b5a57516f633434676a4f716c6e44456a2b5775623053356d59304e464a6b664d517a6f506c5659724749324a4c7062784272643952697a4e59595758744d72493047536f697872494652756c4151656e6f59486d4f7974535848684e624c66506862743948645664477071676a7930454f593656455170584976576369493745397772466f312f566637616a5263394b4956525349377243675776486a51613375474237626a36616c56474a7378505068574276684e37456e6a6348323171564c45754536483236317556472b496454714f3069716a703864516b49413041484375697162506b4c3544646746765a58792f59327a74587336356949626431634b32316d6751746f616b414f4e55506e55442f414f465546514d47724132467872565252623346654a6a335531754b57634f477956416b623238712b687065486e326e4c514c584e625a644a3662636d4a6c766f44586d374a79365477764550544d72634e617a4c696c384f3232655574436f504d43766f61584d635071335a474a43376c6d414a5062577245736168742b4e2b4158396c4d5177335234554159414b4f366b6b4d4e37343061674541416467716d475053747263714b6b565155446f676f436764415837506f6f43674b416f48656756425462334172416451384c416f33446777747a724f33335376416337464f4a754754697461384d6a4c66674c416b56316c34635769777433554165323347675073374b4238506677374b6f434f594845384b677937655271673073543946364a5763636e6c537879634f68675352787465786f506366525734444a7734764631466f79684a4e7957684e682f34617a593661584d64525564425542514641554161424f644b436a33722f5a662b4839395a3253755963642f377135466a70765355455359636b7936797a4f664d4e77534f6e5252585857327a4361367957324f5639544c752b5836686a7974766c597a34737a59324f6c774f6c68612f5466547863373136744a724a792b6633623737622f6a394b7879642f7744556b325a484e46473065524372514d49314a3669443475704466573964646572535a6d586b33396e757530754d5631754e766d536d44472b646974426b747035505541534262785750437646764a4c6956396272376472724c744d56745a4d5864464d632f5543683842556b4b536466442b4b727032585738472f5872327a477a6d64386c394e3757307345534e6c356f42566c44644d614d52393968396772316162396d387a6e45664e37656a312b6e697a35624a454f586a484732343436326757415236697738776d7a36637a31567a75746c7372307a7331757576783859576d474d73656c57544759706b6c5a45783358586f75784162324c585062487935656a717a2f587769656e73332b31375848695a524530454a5a5973714d334a424a506a424e37337137365a7644505632325447797179397279354d743833465a38684d6b6b43566c445332477653335a58723674395a4d5868386a322b6a7432337a50793153397539505a5572714a496d6957354d6a505a722f54725466766b6e4658312f313232316e796d4854375a746b4f4245304d524c6462584c45414873355634757a7375317a5832765839665871312b4d6368764f647652337648335044525a44476a5152786f70613639586a56687a766175756d6d754c4b386e6632396e796c306e68717950572b34444b696e783442454651787977735338624e652f55434c47756d76727a6d57764e762b78326d30736a71735466635849776b79354948685a39436a727154634334505a586c33312b4e786c395472373574724e724d4d737147506459576857646f696849644673527279617231646e7875634d3933564f375847634f613366443262615762356e4d6b6c7967704d574c43415746786f574a76302b2b765672333737636178387a6630656e72756439762b6a616f77424867664b6f70523865377662587a482b4c71593939637563334c30796153617a5763595869356d516e70777a34714c486b704755786f7a63714855394b334a726a5a2b574b397575312b475a454c30356e746962624669626d664c79595331357965705a4c7357367572743171373663384d6466626d666b7054675444663079574379706b54682f50514552734c3851507856374e506a4e4c6a792b5433663258766b7638666b394162696138543739616e4652456155554547634778376556515170464a2f77724669493749527931724e693561797076376556514b3274417256464b3141694272333049315441394a49476f314675366f714a4d624e78304f6f392b74596f7a786e4242484d574e75367243707344584a7255724e696468414e6b526a6a714b33504936633245507346644c34574f656b4e3548504d6b313866613574723254773050596579735670724f7451464156512b64514f674c30414b7347584b7268464e76514168612b70414e4a354c3463486c66377248764e652f5477382b336c474234387a7a726f786c30507073477a583977727a646e6c306e686663574874465a6931312b7873664b572f5a587536764468664b306b343130714d61444a5749494e6a7056566d306a4d4c4555474e6a783530472b71432f6452415451416167644136416f43674b416f43674b4346756b526648594469427037716c6e4138532f5548432b5839524e4d414175576979693334674f6c76724661307559343754466335626a6574495676716f4832577168363844726569514739774f79685431502b4a6f41693431353836464d693474784242316f6a306e394d4e7a764338544e633438694e71626d7a676f394c4f47744f4c5939524f684e59647a71416f43674b416f452f43676f7436503841536533345439745a70584d4f644f4f6f726b6a6236667a707362654a776850533351585854704f6731723364576b32367558792b33753230396e4830735065314f50366b44526b2b58504e486b524f757050565a5736527a73314e6564635676747a7232537a366f323434325a6a377a4e4b513855775a7041366b71327076314b52705872316d75327334664a37742b3772376475624f63726641335264306635504e50526b6b4249386b4158595875657276727964337258586d6548307655392b64316d753347332f6c4b39525a7a624e676c344e4d764a764643334549716a56726669484b756652312f4b3876543758642f54706d66797267386242794d7956596f6c4d6b7273416f37475938575037362b68745a724d3138485458627332346e4e64354e36636144624d4c477854315451456952696243386d7273506658672f737a74625836442f6a664853617a36492f7144314248736d4a487465462f557930526575516e524153442f716172313956337474384d6578374f76544a7250354a6b47426837706a707565457878576e426157496a77462b44416a393471626258572f47756d6d6d765a724e39654d6f654248506a376d66496d676a36545a37766458427463613164724c4f597a3136335862797476555557524c74556b3249374361477a67784e7179412b4e644f50687248565a4e706c31397258626272767876354b443033767556466d4c6a7a755a595a57734f6f6b6b4538434361396e66305336356a3476362f77422f65627a5461356c62475354443342596573686f4d676c536541447464626a33313538356d58307358586247667171355779735863355a564a684b534f3438495a4157596e56434b395536396474592b56743750623137335033586b4f58467650394e756d444f41364154636f3341735646395044586937656936382f5239586f397258753438624a47373533396b77326d41445a457430786f6a384b6c52712b6e6455367571375844703339303664666c664c7a34523557626b32733075524f77492f45377365646652784e5a2f682b667a7432622f666175356d32544b773974322b4746664e6d6a4a57516351725072316577563462325462613276757a31377070724a356264333372473244626f635053664d4b6930517461784e325a722f565764644c7662593664336672303679587958396e544a67584b3275587a4d4c49426b58466c344b5747765178346138713138374c69786d644d326e79307634317132686478787477454b777330536d30795346656c5162654a625532785a6e4a317a61625977366c7671726d3972573945527052554557564c3335314248654f70596a5130586455735671614773344742684e53776a575939654654426c67554e4c46496736314659734e43447a7143464c4631417162335868336a6a5762426f697372396831414930465355543447385150614c47747773577533365a55642b46363372355a644e4d4c5248546c2b36756c5765584f74653542375439746647766d766247707754575661694b4255442f41474e41646c2b4e412b644172564b4862683231594d7543393161525237354a614a74506455313570664468636f336b62326d3166513038505074355274626e533373726f773666303546614d7352375064586c33764c724a77756c463546486659566d4664667379326951634e4b392f584f48432b56783067675846644172447371673661423241484369463355477747674b444679517050594b43736e335a59796971515864756c5164627354617770444c4e4e7879494a78466d4a30713573736f2b456d715a575149494242754f526f41476f4866746f4865674b41426f4367317a7231526b63644b447966395573442f414c584579775046424b304c48387269362f574b615846736339707a4b382b3558726241754233453871413075644f58313044303750706f4866555846396452564162613871426e756f6c476773516666374b46644c2b6e2b554974384d4a766249526f77535141436452396c506f54693565345973336e593055764e6c425074476872466a3053384e7451506e5148503931415542515976773736436933732f77424a2f77434839395a32384663772f4375534a7530775963746956364d6b4667306749494942485463477652303931316c6a78642f527276746e2f6374595a646f797059747465564d764e785a41792b5743516c6a316645426174625a7a64704858712b4e6b30747a747174383741786331514a6c75562b46786f77486457644f793633683137656e58736d4e6f70636a3071797558784a676748534544445541472f4556364e6661347874487a742f316d4c6e533462743539505462764c696d61627955783049645646797a456939723977726e313973307a69505437487133742b507976687632662b7a515937344f49565352475a4a6f3768706733433745612b7973646c32747a58583139644e6462727250444449794d304f38474b33396141716a7a79416c564241494e76764775646a706379356331766e70664a624d6a65484a4f586d5a6245764534565734584c36666472326450644d5973346a3550742b70647473363338746e546d4461747632574c437a706c534655434d7a4e3073574f7245573531353762747462492b6c4e644f76726d75313455474f6d4c6b536d4c624a6b796770596948703657433970754c6d75747a4a6d783564666a746361584b6474376e4433434b41744a694d3673307354366f56555836756b384b78745a5a613764656464705044546e3758687935676c7737704a49432f6c6a525353654b6b64746236765a784d5634765a394c58666635363856634e685975524a69504d516d664156444b68424a364e656c724868584b3757573438507054535753332b5562647a3258467a79486138637641794c784934574e58723772723459396e314e4f337a3555386e707663494d6e7a735a673555723074657a47334d33723054324e624d5638362f7239394e766c7057333146736566764d2b495536596f6f6b506e64524e2b70694c3949413557726c31647330793966742b74743358584678684d326261647078634544484b744b43774f5134426b44693434666c724862767462793765743039656d76346e507557536a6c495557624a516848516d794b53415357492b6d75566a7264724b35483146736d372f4f6a4b6c4b354435626b4b4962334441614c306e57774665336f3764666a683862336658374e742f6c352b54736354626c78646b68776a4f59475242655a534673784e3234313564747337577672395858384e4a726c5259654c4c466e7873724c6b4a356c6d6c69637553516676574f6c64637a447a36363262537935646b31726d755433745461314b6a54494c6967304d756d6e437052705a4e6548446c5547746b42355552724d593443706759474c513077724577393152474267485a725377613278366c69745459783541314c466c5270385637645369355867447a485a574c46797270557333554f424e77652b734e4d6f4a6d567245364533425045476b705a6c66345a48564534356b584e643435337936686831516a764664466a6e35314b794d7643784f6c6649374e636257505a7263794e4a31484f756254557746536a452f5433304263564d67716830425148325567556a674c727970614f5a333664656c726e757258584d314e727734325a777a6e585147766f617a456561336c69674c4d414153547972563469546c324f305165586971434c47317a586b747a637472474265756452626d4b75737a557477375062454b787270797233367a68785756644646454c37614b4452426567796f486567785958424831554848376f446a2b6f4e73367449766d376b4868346c49464a35536558565a454563364e46494c71626a764271324b724e6a7938694c4a794e727a43576c6750564249644f754a75465347637273477143674b416f483761674b424d4e434f326734723133747079396d7a6f51435738767a59774239364939565363566a6163504745494b67672b2b756a6d59502b642b2b714744394e4161637a7032476752595773546339315177586d5739766651774f6f6e53396765516f43392b3267734e69797a693772444d4e5370756f5041734e5672544e754f58766d777a504a67487a4230734736756748714369514267742b64723169752b743457565a61503841613141634b41396c41714250384e42513730625275507936665457647642584e507a41345679724e5a37526e4350635768574d4f7149524d7041757a4f523559577533563135317a6c35642b7a47396a4c50676e677838764c4247444641354f496957456a7a41674b4c63653831364a5a624a356362725a726476343458657762356c5a4d2f792b57517a4d4345634143374457326e6257752f706d737a47665139376273767832544e313953625674674b7a53655a4e2f30597947596678612b4775476e567474346a32392f74616466387279716c2f554c624761337973773432494b6e51652b757439586237764e50326e58395a553362765558707954356a4a685a59736d514353654e6c43537430693350347135376162544759394856336465326474533248656a6e78756d55706879355a486b54456b5571336b6b2b445167583850477076706a7856362b3735356c6e78714c6b4a67656d6f6e6c6852737264636b6b784b775a7946596a715145616846726573752f482b3178374e74656e4f302f4c665a79306b6538623775457270437a354855504f42425649786251456b36563638363661795a664a765832392b39746978327230785074356b3350634f714a4d634551786f354453534851616a676c63642b7962343131657a703965394d2b6539384e32316a63357479506b79735a5a77466b6d6b4857517073784f7643334b7439756d6b30356562312b3774333772692b55764d6e77317a70496e55744356434d794567695545697774335634396575325a665532336b7547674449784e7666634d4a6649584873797a5361744d5377554953666972744d5779567a7564646276506f75396839514e754474444f676a6c414a5572657a573050477033644877356c583066652f757a4c4d56507a3931322f6230445a6379783957717265374832415678313075317849396e5a323636667975466576724c302b54627a324248616a663456753947382b6a68506536662f5a733236583036767a653434737362427a356d53344e325141574a4b385255327a784b363964306b75327438734e6b3350443358456c6e6a52515a4a584c526977666f76614e6d742b4a525533307574785472377464356669684e3558702b4a7477334c4a664d7a6e4a57434e6d41595273525a56552f682b3831644e5a642f77415a4844653639576439726d7554334c644e7833724d6279773869733134735a4353464844674b39576d6d756b35664937752f667532787a6a374c58306874473578352f7a6b6a506934384a3647754f6b794f53423057504c76726e33646b73784f58723944313974647274742b4c3042754a2b79764b2b7731734f4e4271595848325642724b386144426c2f345642725a4f3667774d64426959366d4269596a51486c45696d416a4554797067596d45486c376159474a674235564d43757a39724e6a4c47704b6e56304131422f454b35376166574c4b716e78443933572b756e416975566a57567073374d5561462f6954784b65305631307647476470395858597268385a547a41736664585956323659344245716a6a6f33743556347661362f7248667132347772434c437645377462416e33566d6a41696750746f44536f47644c65797146514249416f4965564f41703174373667342f667372714a46376a6d4f2b765430367565396338784a5938613973385050552f616352707031494868556733726c3262635961316e31646a457651675563414258467050326d41795a4161334136563036706d733756324f4b6e536f486458746b636b71744b5645486451426f467a76515a647441364b5234555253373774497a5a735a673351795342673448426c4e3170395261426a59453862612b32714d57624853587a7041716b69336d4777494131343142692b664247686c6552566a41755859674c62326d715a613858664e70796d36594d754b52687843734361754b6d596e426861344f6834476f7249576f43674277714230465475384b736f4c43366d36734f304d4c4773374665416268696e4233484b7847477345724a5964674f6e3156306c793449785963514b755441366a323664314677427162452b3039776f6c70686d4b6c67775262324174784e75464333364e365936793475524d307178533435516553516279645a49384e75485454366e2b6950772f77416142304b6b62663553354a6551674973636a416b4532626f495731754c6452307170583048366478356f4e6f787650554a4f385558576746756e70514c5933724e6435466d4b796f343044306f46514f3942672f43676f743750394e2f346633316e6277567a626e5873726b7973556c7864743950746e7877714d2f4a596f6b31675744584b68746677697654362b7432736c38504e3766624f72533753666b356a3075725a507135466e4a6d45336e4978636c6a5a6f69474f764f7656337a4775593848362f623562535836783347312b6e686976484e4c4b576443624a5941646775613564766638706952367657394764646d3171536344626e635a732b4a45755352615a33414958704a4873393963666e744a6956367475725337664b7a6c79622b6b63724d766b6263346d78484a4d547933694a31504262563639665a6b6b6c6a35665a2b7332747431724c463246396a796f38764c614f584c55467362477358573575433747334c6c57647579627a45613676567654666c74655668464e6c354f644151576e334d4d47446e345955494a4c4777374f5663374a4a6673394d323232326d50354f68664b7763624f6a6861373538366b72306f57646b55366d342b466134795778377379582f4143315a71794a503151516b7a5a51764a595746343173724f523361566d38707450736a7a5947566e62657355387761594f574c674255747736514f36756e547638626c352f593662323666484b62747532342b48414568496553316d6c3036757a6c5473374c7463313039666f3136746352542b6f45322f5a6345354f5045446e54503077795064374d52646d31374b33315a33754c58443274746572543553666b352f77424e54354f36626d4d4c4e6b6165475347666f6a59335648494236676f35313237745a724a5a486a395074765a627074637a4470646b324c4a785a56794a704154346d4343344e794c566e753770744d534f3370656a7431333557737076542b7a5a63787a35595761535a51576a6b5968564942476f7658436432306d4a5870375057363974766c5a6d75536c394d626d574c347359793441543563304a48517775526f536461396d7666725a792b52762b76374a7463544d6274733253666273775a32357867644a496978575964546c675272592f434f2b75665a764e754939487264473358743874762b7935686c64743067795552526e7565694f4b4e51712b57527231322b364b3534784c48732b567530736e4b3879496471584c53544d4d623562414c45723259675876344649726a4e724a695056644e625a624f5561534c453237496b2b556943506e45796d4e42596b6f414735614370747462357250776d75627247582f41482b5846694d3456476a6d5635596c75514642375478726658744a6e4c48624e74766a6a36566374715455656c67514e616c5272616777496f45566f4d536f2f77416167784b5543364b4264414e41644171413873646c4165554f7971415244736f41516a73316f59524d726149356276465a48346b66644a2f645764744a566c7772686a533430774c4b5659486e77493772567a6d746c4c562f7463774e304a34366975736f335a69454b6269366e516a757248624d787253387153614d6f78413463765a587a4e396356367063786f4f6f4e63363031734c48737142616652554266537144576752594161304561656344532f74724e7068555a2b574170736131724d314c5849376a4f586b49374f49723339577549382b2b32616951784e4a494655584a4946646472694d53637576326a424545495a6c4855654e2b32764c626d3564466a61354367584a504432306b4853624c6739434272574a31317231395775493437584e5838616743753432634b714651426f456141754c3939426c5146416d594161387143446e7a777441364d77556b487061397245566e626153633163572b4654742b385152786d47575330685932366d424c6372697555396e58786c762b756f2b39627869474e634b6156566d795430777063584a4774644a7438706d4f64347548497962487650714c4a387a4c793267324b412b58464770757a737568365150744e616d324978744d75503343464e723365654442796d6b6a686179544b62483247335a58545732787a764868316670763951393677564335616e4d78454944734c6c6c4861613169553137626e46656e374c7632323776696a49777067366b41737478314c6674465973773753797a4d57494e3669736761413071434a755558584177484731537a6772784c3951734d77656f444f46736d5647726739724b4f6c71317065484c6279356e537449423956426c477757525749366c4238513752777045737a4778495a5665384945716b3357344274727065356f6d5a664c46756d4e57526d44544f774c324e794143644e4f5a4e4d4c6c4e322f302f7632344d42673766504f44393449516f2f6d61776f546c314733667048366e795347793367775534325a764d662f53744d787161327532394f66706a734f7a7a706c7a4d32666d4951794e4a595271773175714439395337665a7161536331324276784f703531477a4651464155422b3136416f4d5834554642765a4851347679302b6d7362654563354a7a3736357057336367673950595854473753466e5a7046314372657876326456653330374d383138373970502f414b35694d50302f323479626a6d626d363253496556455477363331623646466450613238526a39583138664a3255516e6d6b64686c4234466270565555416b67612b4b2b7465537a443630737668716a364d6a4c794853587a496f574544516b416f4a6b414c48323631457355323765736c322f4f6c776a69743831435636325a6830465741494b323436563236756937544f5869396a334a3158474d70654c6759573941376e466b79675445686c466830394f6e5472777062644c38624854545858746e7a6c58574a6834324d766c7749464249366a784c483878353179747438765472724e5a69527775302b6f357358656336584a78354d7a4f6e637778737041437147493664526131657666535857535868387271373974657a61376132375664596539357962784f4e35674f436b794b75477655476a43715354314d50764d613433707a4d363876582f793572744e6435385658752b533252755573754f576a684e6b4a566956646c30362b6b47765830395531316c7335664a397a32647439374e622b4b5a74473337724a4b414865464648554a726c51774a35437339322b6b6e683039506f37726562694e503667644372676f5859794c3147787551564668667336717836766d753337584878316a4439506475764c6b5a376a534d6556443758385466757037573369483676722f6c742f774248576f325a493739526a574a474b676f537a456a6a783456343331326b6f3030383673795359796b4b497863454f42347731476246626b65724e72774a577851722b5a41336c795936703069506765664b335a5858547032326d5938766237656e5863576874716b3353646478584c523865556630674675565147335472543566486978762b7638417373326c34572b4474324c6964526957387244787974717a41634b35376257752b6e584e66446b646e3954596e393479387a6369375a626e794d574f4e4f6f4b67592b46514e623372316239566d75492b62302b314c7674642f35654a46787466714e632f63387147574e3854704372697735433944734266726258743543754733565a4d765a72374f753233783856446a7a356e39534c6a3430372f4a4751426f744f6b4f4e4736577466703771394776544a7038724f58677675626265784e4e622b4c72324669543946655a39686761694d434b44456755474e744b4b5246454668514c70715955644e5546685542616d412b6d71433141754641576f426b566830734177504969394270474d495745734e7756347265347433566e4750417341566d68366871434e5256737a447772356f6f372b564d4c4b542f546b374b38322b6b3856313174387858354f4c4a43326f7570344d4f427278396e546461376137536f7a7270584778707149736461696b5342515974496f7672593142476d7962412f7477714375796371774a4a35315a4d6d5850376c6d45676748545734723039656a6c7474685364447a535757354a4e7139655a4934336d756a3266614245424a49504565327650767461334a6864414251644c64315a466c744f33744c4b4a4747673141727631615a357247312b6a713861454b6f417231794f615542595670546f685542514c53674f64426c51424a416f4b546574776542656c5461344a4a37414b787676385a6c5a4d33446d636e634338526b6c6177494a434538712b5032646d3239657a5857617a687957587643494a386f4f4c4d664c516736324770745873364f6d3345727a39752b4d34637a6c5a4f584b365a6258565378386f6b362b453935723645736e456557532b6173313958626d757a663232492b58314d784d774a4442547259564a724d35573758474550624e6d334c63417877736470416838545841462b4e726e6e57657a763130736d3164657231742b7a2b50686c675a732b446b466c384c4b536b694e774e6a5971316439624c4d764a76725a624c4857594d753279742f63765447636d48764543396556746b6a644b54676174355162374b6c7265755a4d765166536e7172453337444c71504a7a4976446b347a614d72445467617a5a683131326c6d56384455615a56426936686c7365426f4f4a39622b6933336a43417869467934474c776457697463654a5437616b34724f3079386d79746c336e456d4d4752677a78796732432b577a58396e53434b334f574c776d34486f3331566e324f4e746b3353644f74313874662f455256534f6a775030633336617a5a755842694c784b7265567671734b5a6a5878727074763841306638415463466a6c7a54356a63775745536e334c557973302b39644e742f706230357474766b397567696238665147622f5531366c74612b4d69314677414f414841445155796f74554474514f674b416f436744514642692f4367352f656a3448396776394e593238463875646338625679524b324c474764426e34726c6d67615655634953484e77474b676e674b3636577979783539394a74624b6c2b71514d44614d5841784149595763396170635843692f48765047765a36732b573174654c396c742f5831613636385a5a2b6e63334d6b326e4d6a3677666c34423875514143704b6b486833316e324e4a4e355854396433626264562f2b4c56734765754245384b4c313469496369526953585a795158622b4a6d724f326d624a485854762b4d7476694f664d734f5675547a62676a53774e49786c4b61536f474e77626a34677465762b75367a38587839666130374e38646b2f77437275746d3254413231576b777058654f64565069594d704131444453764476325862792b393065767231792f465a4855456136693163336f6378764d53344d34495153346a71716767654b4e7232356362313230326c6d4c586937706462343451705a6b68506b357745324e634164525057696733756f727072624f646134646d75753078764d7830574a73323042556d67514f724b436a6b6b33424e373179323774727861395858366e56727a49335a326173635a69694e386e3773597343414e654235567975635a643939704a6a4b697963655464735159383870556563464d6f415a69564e2b6b6668374b756d39317373655874363532543432742b39742f6164726777734147414d72456c654e674e535772303947767a3274326566337437303963313034505a3930795832544d4b68566c7849314d4d6746376c6c76314d447a765765375354664474366666642b6e50316a58734f66466851535938354c6f6c386d5849593359744b6273783970724f33586d7a44656e664a4c647135777042754f354e4e75442b514a5849624b5458704a4e6c563150416439657234376154695a664b6e5a7037473335583431327578374b32315279512f4d476546794756574141552f6c73656465507337506c6334665a3962312f3674666a4c6c5a6b4567324e695151434f524e63336f63647557426937666e4d7278434d794254426c6f4c45746658713747723061375a6e6c383774316d75334d2f77437247646b7a4a556933526a314956574c4c57773652665871767872656d31313531637537713137624a76656675753974394e596d4a4f6b367a5049796d363349436b386236566a66324c744d5964756a396470313754615732727731796539695270554742416f4d5350726f46616973534b41373642576f44756f4439394136424339414147675957676654515a4261426852773555474b45774f522f793250304770344763384b796f515143445532316c6d4b5334764341336d515852683178486b6556656179363865593679796f306b4f4e494330546444633150625848627231764d626d316e6c416d516f534461764e747259367935524a484976584f72454f57596a747143484e4d64536173677138756479534642504b31647464597a616744624d724a594569774a4e7961375465547735575a57324474454d466d4942626d65645337572b5578797356734c4141585041566d4b7364753275536467376739494f6c3637366456764e63397476733666467845695541433171396575736a6d6d71756e4374534b7a716855515542514b675866515a55416461437033664145384c433270424137617876726d594a63584c7a6e654e7479736a4961416b704643705a32494e72446c37362b62704c70624c486666616253574f50337670584e554c4745514b4371446754657670645038586c32383146793875584c6c3879554b43414146555741413035563031316b6d497a6c612b6d2f544f547530776b6b7646676f66366b6e417462377131352f59396d6154452f6b3976712b6e657a387476342f2b585562333667777467784632376256555a4957794b423459782b4a752b7644306447336274387472772b6837507361395776786b2f4c374f4379524c304938674a3834732f575238524a317237474d5353506754623557322b58642f707442365a6d32626546335551656261346b6d4b6831514954654d6e5734627370632f5271536332746d3059755a6c62486a6571396e5a6a7675336b70756d4e6f506d49303075464833756a5876713234386d75504d656a374476574a7647317762686974654f5a515374395659614d70396c5a737736793557494e5150536f4167486c656759734f5641456b38546567414b41306f4331412b56415542335542633836416f41304265674b417651597948776e746f4f653376344839672b3273626545766c7a7a6b324f747556636974327952626b7a3549784353475946516a414d72697875335a657656312f48347931356470746472685a373475666b2b6e59354d7141523563447130774e6a70636a715731646658736d2b4a58443968727474305a766d4a76705443614462326c64624e6b454e714e536f466754667470375732647352723958315854717a6639795669375667346935536164475354314b7847696b66434c31787539754b396b36745a4c50384132636a6b3751594a5a796a6778593773444f44707141514b392b6e735462452b723833322b6a644c742f367a367272306a6d764a6a74674d624b696c6f6d4275563675502b4972683758584a666c48307631667333665736582f6170707656337144417a5a63584b4b534e464c30755367556b44384e6a3934613171657672744a585073392f74303275746b34546c33434c4e77344d786636587a5753714e45574249596b7143312b7a6a586a3236374c5a6837644f3358655462506c59484e784d504e58457a34566579723035425545717850426a57746462646378312b657575306d30574f36376c427465474a756a7175656d4e467341546139587136377663513972324e656e5435574f65322f4c334c64426c3563624932514836577877624e48455642526c4a355631374e4a70666a586c364f33627531753859593258754f49692f4c343749704a6c55644a594d43627944537333545731765866655973697839526b5a4f32515a61524d473667475674436f596643775066572f57754e724d756637505850564e704737307467434c6135664e54544a5939536e677968656d732b7a746e626876395a31585870356e6c73772f547345474a6c51534e312b6553714f52717144345258502b32356c6a3066384147312b4f32742b726c4a397579344a70454946304c4c497746304a417541312b32766f363975746b772f4e646e713736625756303370724f6e794e764f4b577650436e3946323175702b48712f684f6c654c76363572637a772b372b763969396d6d4c2f414369715431356c527974466c5969686c63712f5153436f585268596a57743331737a4d726a2f2f41464c72624e745569536362686a34383862466f637155434a58463244432f52396c65533632577976584e70764d78756a545a354d38596d34517175563072304f536568376a526266697270726470726d4e666864704e707976544a6a3467686948684473496f55467a72617336793232752b323031784b6c4556576949714445696778496f4d534b4b524641725543496f43314141554474514854514d436764744b4267436779416f474251425545454558464267724e436248564f52374b6e6762486953564e4c45476c6b70345675547435424a555841354775472f544b334e6c625069324e6943443333727a37644d644a3256436b784c6e6964613433702f79314f78476644572b7039745a7654562b614f2b4247654e7a536461664e68386a41446f76303171613453375767784b7567466878734b31497932513463387a414970462b59476c61313074384a647046316762437157615558493547765270307963317a32327458634f4f714141433364586f6b77796b4b6f71794b7934634b6f4b416f674e4155434e415542565536694e557964536b637a556f346a667070734c4d365a492b764759454f5278317277657a624c683136395a59382b33394d504c56704d4e697a77447164446f77516d7652362b5a4d5678374a4a65485071567543777574775741346b58723175662b72737333317669773762486a62544359354f6b4a636979786931744f3031387a543074727462766548324f7a3968704e6677382f2b48494f37794f7a757864324a5a6e4a7553545830704a4a69506b62625861323238706d546e4e4a747550684d6f4b52585a474f68556b6d347256384f55312f4b314356454c576336633763616b627276505233714d625a76525852594d754b4a696f314855677361315a6c6e53346456684e4673507168526a6b44592f55424c52414877785a59463255446b48724d356d4857584677375148577834316c746b44536a495771416f44766f432f6251415038416e5148736f43696e65694665676441763274514f6750746f4652546f6a435136554850623138442b77612b2b73622b45633833446c65754e7134564f33376e6b626236697a4d6946725764664d552f43796b43344972366652704e75724666473972324e7576756d30656c775a454f6468787a6748794d684133533334573547764a5a5a635072363754665758365655622f366966466e2b537850444967425a37583443345661395852305461664b766c2b3937393637384e504b74326e463354634d683835356d5746435138725773787461777670572f59323131312b4d6e4c7a2b6a7033646d2f77445a746678544d646353564a566c6c696b6757636f51374c304572594d784950506c58686b7373772b705a4c7856706762504469356779735631624864574853414459485557497274743233625846547039545872332b577668687633706e43335a664d30687a46466b6e41304e75416363366466646466445873657070327a502b35774f342b6e39377753776c7870476a556c67794179523237515672313639327435792b4e7636586272394d2f77436a6f6a444f3379556d514f74386a46517577314d6e68494856666e586e7a4f63506f2f4862386274396b6e635a6d7a6654654c6b4f33696863784f4c58424b6b716256766f6d4e3748443337382b6962666171764b774a6836576c7a4977556d53654d6d5653565952676444616a2b4b74646d30765a4a585031394e7036317631512f53322f7743587475636b63376c385351684a30596b68626d776462384c5676753670744d7a79352b703766773278622b4e656a7a7751354d4c5253414e4734462b776a6a587a3964724c6d50763736546157574b6265765553594c2f4c596f567059774f712f7772623774685870366569376331383333503245366238645a2b5374323749337a637368356c6b4a78314a444567394e795068573161396a585458584538764e36666233397539327638457544442b5969614755466f6d6d594d6f4e6953445a69547a31727836375757575070626463326e7873544e71326566413342337572514f6e537472676978424678586f37653335617966567a3962314c3162327a2b4e5176556e70495a7a506d344a435a70505538524e6c6b4935672f64616e56333358692b4766623943646c75327638414a7732584c75654356686d3832415150316f7241715565392b6f63754e657158573573664b2b50627069584d7736764d58496d796c6564537330304b53507a42634b44314c6268586d6d4a6d523958664e73746e4f467a464f2b61646d797a61335552494c384873522b3670724a4a74473972643770737679506f726b3970476f4d534b42455543492b6d696b526167787451467141745146714174514f3141774b42674433554142514f3141774b444b3274454241497365486651617568347a644463666871595673575a48466d466a324767776c78495a41644162314c4a535658546251684a4b6b67317a76564b733271484a7373683144653273587058354e54624a4d546f31542b6d6e7a433741782b4a744f4e4a30487a71544473654f6842596452477574626e56496c3274546f73534a425a5142627572704a497933716e437772556973777475587670494d754655493866746f44536943674b4245304161416f454b6f79317142455548456573356f697a41437a4b4c453978727765335a64704862716c7861355030587366384163553344506c57386273596b4247684134327230626132346b2b6a6c4a6d5a7631637a36673253666163353432556a48646a3545684273527874586654624d63624d634e6e706630786e656f6477624678794934595148793532344a47546251637a57354333434a764f506734753635574c74383579734f462b694b6472586177462b484b394b6b7a6a6c466b4a49554455425154536b693566597477327642776432335062672b32354432594d78446c57463136674434626a3461597835544f6269492b3577346d4a757a4a6753744a6846566b786d4a38515678667050384e5763566e7a485152657038656630356b596d65786a797351706b625a494c6d38714d4e4f3672664f596133456b65785963356e7738656369786c6952794f396c4272466d4b394d5352556f64514837576f432b7441364b4f584769433936412f61314155415451462b32674b4b4c3051582f7a6f4147674b4b776b4f6e3230527a32394877754f34582b6d73622b443675665936646772694c6e30783664322b382b3435454b7a54354c42305a7864565544707341644f56656e547373316b6c6366364e62746472796b356373573379434f4b5144476e646d6869556732594164534942394e597474725738784a4972344e6b6b334863636e50334a6636636c6b68776b5971516f4148553744373364586164396d73316c65572b727074766474706d7148315775364c6b6551736b6b7532522f7742504755447052516f415a574338574861613948544e624d3379387675626279346c2f42575965335a6d564b755069784e497a36685646683465322b6d6c656a626257544e72352b6e58767663534f31394b3742765747776d794d687365456b6838503469534e464a766f5064586a372b7a586269523966307657374e4f647278396e52357235717748355256615932486a4a41415050537547736c764c336476796d742b4d355632647475367469794e427545767a49415a564141513249366c74627372704e3963387878323664374f4e376c6e504467346533724558614f434957535567764b4f6f33384f6c363558613579362f4753534d347348454f337474742b6b536c6e52435148485565713971334e374e707378656e57365854364a6f777358354d34624947786d517875683446534c4773336132356464657657612f4754687a2b32656839736a45687a4f7565554f775878465146427568303532727474374731385046312f7275755a79745a4a3563574c79703242574b35575144566b423850446e586e74657a6132634f6568324f626338334b7a4d76727873615a6a3873694547546c346d754f665a587031396d7a5753506e58396670767664747671714e2b33666538624b4f334c49494d4f4c77784848764773692f6a4a343337613664585872744d336c7939763264394c384e66786b56324f2b357a41342b4f386a396264666c6f5762555831754e6137585457633250427232646d33347932757a394a772b70555657793374677343566a6d753067494e72432b7131354f2f345a343876732b6c4f3748352f782f77447936484d6e6d68694451776d5a79514141624158356d754f736c764e657a733275737a4a6c56627044766234724574424d704957534578395236474944644e7a797263756d66446a747233593475725a4e673438474c43727a4472786c4553356375684b67384c437366506c75365379524a3233416a67784963634548794a43386248695153542b2b7458613232727031536179665a6163616a71784e4b4655437465675641555572554374514676665146744b4174514f3171426755426167594641774b49646851506c514641554744497263525939744269555a66684f6e5a55777045747a463659437632696f46703256515776796f48304e374b42684f326d426c59446856444e426a2b376a514f675645464646454b674e6142582b69676441586f4132467a79416f504d50583236787435356a42566c42554548556b36437644746a66746b2b7a72507830746458365332556266366277735a7861526b456b76623150725873316e47574e706a68703956624a446e62564e444a48316a704c4b514e5152726347733758343878693635654f5a6b656674737a434b5753474f5a656a7a59324b4352423931724775757538737a4850474f4b7239414c4453714c7648395048634e6762634d4755535a5749534d76454a41626f766f7969725a77786e467857473565714e3833544468776336667a63614870365230685750534c44714934307479767835797253784c39524f76416477706c72447076517670687655573742706752746d4556664b666b37445659783765644d344e6458743441414155414b4141716a51414157465a6457514a355547563942554476514c57696d4b417651464155425146415542514f69463355446f46335547456e43673533657668663244376178763450716f474a736231795261656b495a4d724d7a47794765544678797177786c6a355965312f68466572347961613265586d367439723237532f7869797a636e4562314e6837664a456a535234387558457a415856756f526a70373758726e693474656d32533462637644795a6d50795755495a6258664849425667644166784c5631784c6d786a6257325846563230346d39596565504d7879324b784b796775474175666a5739644e72725a773564576d2b75334d34586d646e345733776d6161794437717142314e633230465a303075317848547537394f765835625679755a3678334235472b56437852676e7042415a7264354e653354314e5a4f58777537397676622b457846687476717157544366357542307946526e6a6d432f303355412b4c57764e3264556c78725831656a327472706e65593258574b4a6d6854496e79524947514f436f434946493672317776466579584d6c614e76764e6a444a69667a316e4c533478634157526a3456464b6d465a6a65734e734a637951534a6c5273592f6c79415a46494e6a3145614b4b3636394f3138504c74376658706e3556692f7255395a564d6270414a424c4e6539763452586658314c6a6d7648762b34316c2f48565032664a79747a65616561526b686a5942495541436b6b6669346d75506231545779537662366e7333756c746d456955594d32384c41774c543455486e5775656b435a696f75503561347658634e65584275615866424b50455151305a4670564a3571783050767136795a35633974626a4f717532745a446c664c5a2b4b303044456c576d51486f636d2f4d6336363757535a6c634f7557334730586869327662314d346a6a7878594b58554145676e6870574a38747268337677306d62776f382f316738544559384959584e693139514f423072303665726d63313876762f6254573431695a672b714d544c77336b4138764a52535445344955393461334375472f5664626950643065334e396332597179692b614b2b5a6b46465731797158494174666961355055687878732b4b4a73674c6b3950553858514c2b4339317370346d314b785a6c6a744f3862546d544a4a4249476c6b50524863454d5142632b45396c622b4e6b5a30374e62664b386f36736141494e514b675641555572554262746f436756762b4641375544412b75674c5544416f43676474614964414767583761554251483230427a745149304152514668326155426167564155427a6f7055427251464171494b4247696a6a725242515930442f64514f3941694e4c646f745163644e2b6e365a7565302b644f5767456f6c6a6954533442765a713461644f4e72625737744c4a4859424141414e41414142374e4b377335614d704349584b6935414f6c532b45727a6264634447794a706f70347759486656414c4545366451723538374c7274634f6c316c6e4c694e7832444d78576c614a476d676a5a6c596745736f422b3842587630375a74484462577971324b5a3059744535556b454571534c6a3356315a73686c79535354666e66766f59577577656d74773371634349474c46482b376b73443067666c76784e63393935717573793955324a495054384359324b6c3854517a4a785a6d504679667856776d396c7a5861617a47485852537879524c4c4777614e786457466435637a4d537a444f315647514e46464158464158316f4339413641464161554376514f674f56417236304265674365416f432f3055474570384e427a6d39487776626d415072726e76345436714669656650533374726d5631587053584566424d6342587a493349795648454f54667856316b754a6c6e577932794f437938505033446634637a436e4b5a38737a786f586333416959686248326371396378724d57506e5737623763586c6b6d64366b7a393156345a434d396c386f6557517246554a306257334775754e4e6463587738337a376474355a6679646d322b626869775252357355597a62417a4243656b43397231344e38533850717a73326b6b326e354a454b5965364b567a59417a786b674f32684934333050687136647532766970657654746d4e706c782b2f623773304b54596d7a3461534d344d625a7a33497565506c6738666258723131333278387138472b2f5431353130313557552b34726b533438794b5568794d5655525459324371565038414b4b784e635a6a7674325462462b38576d54675435586f754c42526d51797771736a6f664549776274302b30615679746e797472307a577a716b694c732b374842326d4c486b4a7934496c4d55556b656a6856505430735079317266544e7a484c7137736179586c5570734532544c3575332f31496e4a6371574264626b697a333432723136642b736d4b2b5233656a32623757362f6c4b754e72394c537879587a575649774c7445726352333372485a374d7378713948712f713772633731306550444269345a5344534a465a67623334416d396550626137584e66593030313031787234634e4e4a763533746431775838366249683675674141474a4266704b6b366976527272726979766e6237396c336d326e324b4c314436696d3344357645686138366f44416f4c6f77546d41654636334f725361347463623750666438367a7a3948582f7742384b70454a3444445049423178456739424f6d7046654c624575492b744f32346d5a794a4d504533614d4e4b585631757568494848734e644f76747576687937656a58756b6d7a6b39387a665475416b754a69712b586b6643306e565a464949754c387a374b394f74374e2b6278486733303966702f475435624c4c4a79346e7a48654251494a59454d415957557146427461755531784d563664743564737a374c5066426d355870766f686b2b586d794555537971445a5559655036744b356179664c4431623758345a6a546762736d4e746b4d4761536a5278434e4a34686457414853704675646132307a63786a54756b316e7951746732764a783933696d42456b4c4579476341654c7142414a747772306237363354456650364f6e732f766d32334d646e586c66594c756f436f4632304374394641555557376142554261674c5542514f3141577452414b42326f4861674c3042396c41637142554272514c57674b4b4b416f4367564546464774416a5146454b674b4b4b49584f69696946776f4851662f32513d3d, '2021-06-05 14:01:23', '2021-06-05 14:01:31', 'Admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` int NOT NULL,
  `bus_seat_layout_id` int UNSIGNED NOT NULL,
  `seatType` enum('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1=Seater       \r\n2 = Sleeper     \r\n3=Vertical Sleeper\r\n4=None',
  `berthType` enum('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1=Lower Berth\r\n2=Upper Berth',
  `seatText` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rowNumber` int NOT NULL,
  `colNumber` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`id`, `bus_seat_layout_id`, `seatType`, `berthType`, `seatText`, `rowNumber`, `colNumber`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(1, 1, '1', '1', '1', 0, 0, '2021-04-29 11:40:07', '2021-04-29 11:40:07', NULL, 1),
(2, 1, '1', '1', '2', 0, 1, '2021-04-29 11:40:07', '2021-04-29 11:40:07', NULL, 1),
(3, 1, '1', '1', '3', 0, 2, '2021-04-29 11:40:07', '2021-04-29 11:40:07', NULL, 1),
(4, 1, '1', '1', '4', 0, 3, '2021-04-29 11:40:07', '2021-04-29 11:40:07', NULL, 1),
(5, 1, '1', '1', '5', 1, 0, '2021-04-29 11:40:07', '2021-04-29 11:40:07', NULL, 1),
(6, 1, '1', '1', '6', 1, 1, '2021-04-29 11:40:07', '2021-04-29 11:40:07', NULL, 1),
(7, 1, '1', '1', '7', 1, 2, '2021-04-29 11:40:07', '2021-04-29 11:40:07', NULL, 1),
(8, 1, '1', '1', '8', 1, 3, '2021-04-29 11:40:07', '2021-04-29 11:40:07', NULL, 1),
(9, 1, '4', '1', 'NULL', 2, 0, '2021-04-29 11:40:07', '2021-04-29 11:40:07', NULL, 1),
(10, 1, '4', '1', 'NULL', 2, 1, '2021-04-29 11:40:07', '2021-04-29 11:40:07', NULL, 1),
(11, 1, '1', '1', '9', 2, 2, '2021-04-29 11:40:07', '2021-04-29 11:40:07', NULL, 1),
(12, 1, '1', '1', '10', 2, 3, '2021-04-29 11:40:07', '2021-04-29 11:40:07', NULL, 1),
(13, 2, '2', '2', 'SL1', 0, 0, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(14, 2, '2', '2', 'SL2', 0, 1, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(15, 2, '2', '2', 'SL3', 0, 2, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(16, 2, '2', '2', 'SL4', 0, 3, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(17, 2, '2', '2', 'SL5', 0, 4, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(18, 2, '2', '2', 'SL6', 0, 5, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(19, 2, '4', '2', 'SL7', 1, 0, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(20, 2, '4', '2', 'NULL', 1, 1, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(21, 2, '4', '2', 'NULL', 1, 2, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(22, 2, '4', '2', 'NULL', 1, 3, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(23, 2, '4', '2', 'NULL', 1, 4, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(24, 2, '3', '2', NULL, 1, 5, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(25, 2, '2', '2', 'SL8', 2, 0, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(26, 2, '2', '2', 'SL9', 2, 1, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(27, 2, '2', '2', 'SL10', 2, 2, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(28, 2, '2', '2', 'SL11', 2, 3, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(29, 2, '2', '2', 'SL12', 2, 4, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(30, 2, '2', '2', 'SL13', 2, 5, '2021-04-29 12:10:30', '2021-04-29 12:10:30', NULL, 1),
(31, 3, '2', '2', 'SL1', 0, 0, '2021-04-29 12:30:14', '2021-04-29 12:30:14', NULL, 1),
(32, 3, '2', '2', 'SL2', 0, 1, '2021-04-29 12:30:14', '2021-04-29 12:30:14', NULL, 1),
(33, 3, '2', '2', 'SL3', 0, 2, '2021-04-29 12:30:14', '2021-04-29 12:30:14', NULL, 1),
(34, 3, '2', '2', 'SL4', 0, 3, '2021-04-29 12:30:14', '2021-04-29 12:30:14', NULL, 1),
(35, 3, '2', '2', 'SL5', 0, 4, '2021-04-29 12:30:14', '2021-04-29 12:30:14', NULL, 1),
(36, 3, '4', '2', 'NULL', 1, 0, '2021-04-29 12:30:14', '2021-04-29 12:30:14', NULL, 1),
(37, 3, '4', '2', 'NULL', 1, 1, '2021-04-29 12:30:14', '2021-04-29 12:30:14', NULL, 1),
(38, 3, '4', '2', 'NULL', 1, 2, '2021-04-29 12:30:14', '2021-04-29 12:30:14', NULL, 1),
(39, 3, '4', '2', 'NULL', 1, 3, '2021-04-29 12:30:14', '2021-04-29 12:30:14', NULL, 1),
(40, 3, '3', '2', 'SL6', 1, 4, '2021-04-29 12:30:14', '2021-04-29 12:30:14', NULL, 1),
(41, 3, '2', '2', 'SL7', 2, 0, '2021-04-29 12:30:14', '2021-04-29 12:30:14', NULL, 1),
(42, 3, '2', '2', 'SL8', 2, 1, '2021-04-29 12:30:14', '2021-04-29 12:30:14', NULL, 1),
(43, 3, '2', '2', 'SL9', 2, 2, '2021-04-29 12:30:14', '2021-04-29 12:30:14', NULL, 1),
(44, 3, '2', '2', 'SL10', 2, 3, '2021-04-29 12:30:14', '2021-04-29 12:30:14', NULL, 1),
(45, 3, '2', '2', 'SL11', 2, 4, '2021-04-29 12:30:14', '2021-04-29 12:30:14', NULL, 1),
(111, 4, '1', '1', '1', 0, 0, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(112, 4, '1', '1', '5', 0, 1, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(113, 4, '1', '1', '9', 0, 2, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(114, 4, '1', '1', '13', 0, 3, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(115, 4, '1', '1', '17', 0, 4, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(116, 4, '1', '1', '21', 0, 5, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(117, 4, '1', '1', '25', 0, 6, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(118, 4, '1', '1', '29', 0, 7, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(119, 4, '1', '1', '33', 0, 8, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(120, 4, '1', '1', '37', 0, 9, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(121, 4, '1', '1', '2', 1, 0, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(122, 4, '1', '1', '6', 1, 1, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(123, 4, '1', '1', '10', 1, 2, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(124, 4, '1', '1', '14', 1, 3, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(125, 4, '1', '1', '18', 1, 4, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(126, 4, '1', '1', '22', 1, 5, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(127, 4, '1', '1', '26', 1, 6, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(128, 4, '1', '1', '30', 1, 7, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(129, 4, '1', '1', '34', 1, 8, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(130, 4, '1', '1', '38', 1, 9, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(131, 4, '4', '1', '39', 2, 0, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(132, 4, '4', '1', '3', 2, 1, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(133, 4, '4', '1', '7', 2, 2, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(134, 4, '4', '1', '11', 2, 3, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(135, 4, '4', '1', '15', 2, 4, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(136, 4, '4', '1', '19', 2, 5, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(137, 4, '4', '1', '23', 2, 6, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(138, 4, '4', '1', '27', 2, 7, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(139, 4, '4', '1', '31', 2, 8, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(140, 4, '1', '1', '35', 2, 9, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(141, 4, '1', '1', '40', 3, 0, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(142, 4, '1', '1', '4', 3, 1, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(143, 4, '1', '1', '8', 3, 2, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(144, 4, '1', '1', '12', 3, 3, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(145, 4, '1', '1', '16', 3, 4, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(146, 4, '1', '1', '20', 3, 5, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(147, 4, '1', '1', '24', 3, 6, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(148, 4, '1', '1', '28', 3, 7, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(149, 4, '1', '1', '32', 3, 8, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(150, 4, '1', '1', '36', 3, 9, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(151, 4, '1', '1', '41', 4, 0, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(152, 4, '1', '1', '1', 4, 1, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(153, 4, '1', '1', '5', 4, 2, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(154, 4, '1', '1', '9', 4, 3, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(155, 4, '1', '1', '13', 4, 4, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(156, 4, '1', '1', '17', 4, 5, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(157, 4, '1', '1', '2', 4, 6, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(158, 4, '1', '1', '6', 4, 7, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(159, 4, '1', '1', '10', 4, 8, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(160, 4, '1', '1', '14', 4, 9, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(161, 4, '2', '2', 'SL1', 0, 0, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(162, 4, '2', '2', 'SL2', 0, 1, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(163, 4, '2', '2', 'SL3', 0, 2, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(164, 4, '2', '2', 'SL4', 0, 3, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(165, 4, '2', '2', 'SL5', 0, 4, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(166, 4, '4', '2', NULL, 1, 0, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(167, 4, '4', '2', NULL, 1, 1, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(168, 4, '4', '2', NULL, 1, 2, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(169, 4, '4', '2', NULL, 1, 3, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(170, 4, '3', '2', NULL, 1, 4, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(171, 4, '2', '2', NULL, 2, 0, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(172, 4, '2', '2', NULL, 2, 1, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(173, 4, '2', '2', NULL, 2, 2, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(174, 4, '2', '2', NULL, 2, 3, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(175, 4, '2', '2', 'SL6', 2, 4, '2021-04-29 12:38:35', '2021-04-29 12:38:35', NULL, 1),
(176, 5, '1', '1', '1', 0, 0, '2021-04-30 09:46:44', '2021-04-30 09:46:44', NULL, 1),
(177, 5, '1', '1', '2', 0, 1, '2021-04-30 09:46:44', '2021-04-30 09:46:44', NULL, 1),
(178, 5, '1', '1', '3', 1, 0, '2021-04-30 09:46:44', '2021-04-30 09:46:44', NULL, 1),
(179, 5, '1', '1', '4', 1, 1, '2021-04-30 09:46:44', '2021-04-30 09:46:44', NULL, 1),
(180, 5, '2', '2', 'NS1', 0, 0, '2021-04-30 09:46:44', '2021-04-30 09:46:44', NULL, 1),
(181, 5, '2', '2', 'NS2', 0, 1, '2021-04-30 09:46:44', '2021-04-30 09:46:44', NULL, 1),
(182, 5, '4', '2', 'NULL', 1, 0, '2021-04-30 09:46:44', '2021-04-30 09:46:44', NULL, 1),
(183, 5, '3', '2', 'NS3', 1, 1, '2021-04-30 09:46:44', '2021-04-30 09:46:44', NULL, 1),
(184, 6, '1', '1', '1', 0, 0, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(185, 6, '1', '1', '2', 0, 1, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(186, 6, '1', '1', '3', 0, 2, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(187, 6, '1', '1', '4', 0, 3, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(188, 6, '1', '1', '5', 0, 4, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(189, 6, '1', '1', '6', 0, 5, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(190, 6, '1', '1', '7', 0, 6, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(191, 6, '1', '1', '8', 0, 7, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(192, 6, '1', '1', '9', 0, 8, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(193, 6, '1', '1', '10', 0, 9, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(194, 6, '1', '1', '11', 1, 0, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(195, 6, '1', '1', '12', 1, 1, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(196, 6, '1', '1', '13', 1, 2, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(197, 6, '1', '1', '14', 1, 3, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(198, 6, '1', '1', '15', 1, 4, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(199, 6, '1', '1', '16', 1, 5, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(200, 6, '1', '1', '17', 1, 6, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(201, 6, '1', '1', '18', 1, 7, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(202, 6, '1', '1', '19', 1, 8, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(203, 6, '1', '1', '20', 1, 9, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(204, 6, '1', '1', '21', 2, 0, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(205, 6, '1', '1', '22', 2, 1, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(206, 6, '1', '1', '23', 2, 2, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(207, 6, '1', '1', '24', 2, 3, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(208, 6, '1', '1', '25', 2, 4, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(209, 6, '1', '1', '26', 2, 5, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(210, 6, '1', '1', '27', 2, 6, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(211, 6, '1', '1', '28', 2, 7, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(212, 6, '1', '1', '29', 2, 8, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(213, 6, '1', '1', '30', 2, 9, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(214, 6, '4', '1', 'NULL', 3, 0, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(215, 6, '4', '1', 'NULL', 3, 1, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(216, 6, '1', '1', '32', 3, 2, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(217, 6, '1', '1', '31', 3, 3, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(218, 6, '1', '1', '33', 3, 4, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(219, 6, '1', '1', '34', 3, 5, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(220, 6, '2', '1', 'SL1', 3, 6, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(221, 6, '2', '1', 'SL2', 3, 7, '2021-04-30 11:53:28', '2021-04-30 11:53:28', NULL, 1),
(222, 7, '1', '1', '1', 0, 0, '2021-05-05 11:01:01', '2021-05-05 11:01:01', NULL, 1),
(223, 7, '1', '1', '8', 0, 1, '2021-05-05 11:01:01', '2021-05-05 11:01:01', NULL, 1),
(224, 7, '1', '1', '9', 0, 2, '2021-05-05 11:01:01', '2021-05-05 11:01:01', NULL, 1),
(225, 7, '1', '1', '16', 0, 3, '2021-05-05 11:01:01', '2021-05-05 11:01:01', NULL, 1),
(226, 7, '1', '1', '17', 0, 4, '2021-05-05 11:01:01', '2021-05-05 11:01:01', NULL, 1),
(227, 7, '1', '1', '24', 0, 5, '2021-05-05 11:01:01', '2021-05-05 11:01:01', NULL, 1),
(228, 7, '1', '1', '25', 0, 6, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(229, 7, '1', '1', '32', 0, 7, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(230, 7, '1', '1', '33', 0, 8, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(231, 7, '1', '1', '41', 0, 9, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(232, 7, '1', '1', '2', 1, 0, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(233, 7, '1', '1', '7', 1, 1, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(234, 7, '1', '1', '10', 1, 2, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(235, 7, '1', '1', '15', 1, 3, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(236, 7, '1', '1', '18', 1, 4, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(237, 7, '1', '1', '23', 1, 5, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(238, 7, '1', '1', '26', 1, 6, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(239, 7, '1', '1', '31', 1, 7, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(240, 7, '1', '1', '34', 1, 8, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(241, 7, '1', '1', '40', 1, 9, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(242, 7, '4', '1', 'NULL', 2, 0, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(243, 7, '4', '1', 'NULL', 2, 1, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(244, 7, '4', '1', 'NULL', 2, 2, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(245, 7, '4', '1', 'NULL', 2, 3, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(246, 7, '4', '1', 'NULL', 2, 4, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(247, 7, '4', '1', 'NULL', 2, 5, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(248, 7, '4', '1', 'NULL', 2, 6, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(249, 7, '4', '1', 'NULL', 2, 7, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(250, 7, '4', '1', 'NULL', 2, 8, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(251, 7, '1', '1', '39', 2, 9, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(252, 7, '1', '1', '3', 3, 0, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(253, 7, '1', '1', '6', 3, 1, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(254, 7, '1', '1', '11', 3, 2, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(255, 7, '1', '1', '14', 3, 3, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(256, 7, '1', '1', '19', 3, 4, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(257, 7, '1', '1', '22', 3, 5, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(258, 7, '1', '1', '27', 3, 6, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(259, 7, '1', '1', '30', 3, 7, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(260, 7, '1', '1', '35', 3, 8, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(261, 7, '1', '1', '38', 3, 9, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(262, 7, '1', '1', '4', 4, 0, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(263, 7, '1', '1', '5', 4, 1, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(264, 7, '1', '1', '12', 4, 2, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(265, 7, '1', '1', '13', 4, 3, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(266, 7, '1', '1', '20', 4, 4, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(267, 7, '1', '1', '21', 4, 5, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(268, 7, '1', '1', '28', 4, 6, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(269, 7, '1', '1', '29', 4, 7, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(270, 7, '1', '1', '36', 4, 8, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(271, 7, '1', '1', '37', 4, 9, '2021-05-05 11:01:02', '2021-05-05 11:01:02', NULL, 1),
(272, 8, '2', '2', 'SL1', 0, 0, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(273, 8, '2', '2', 'SL3', 0, 1, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(274, 8, '2', '2', 'SL5', 0, 2, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(275, 8, '2', '2', 'SL7', 0, 3, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(276, 8, '2', '2', 'SL9', 0, 4, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(277, 8, '2', '2', 'Sl2', 1, 0, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(278, 8, '2', '2', 'SL4', 1, 1, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(279, 8, '2', '2', 'SL6', 1, 2, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(280, 8, '2', '2', 'SL8', 1, 3, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(281, 8, '2', '2', 'SL10', 1, 4, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(282, 8, '4', '2', 'NULL', 2, 0, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(283, 8, '4', '2', 'NULL', 2, 1, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(284, 8, '4', '2', 'NULL', 2, 2, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(285, 8, '4', '2', 'NULL', 2, 3, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(286, 8, '3', '2', 'SL11', 2, 4, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(287, 8, '2', '2', 'SL12', 3, 0, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(288, 8, '2', '2', 'SL14', 3, 1, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(289, 8, '2', '2', 'SL16', 3, 2, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(290, 8, '2', '2', 'SL18', 3, 3, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(291, 8, '2', '2', 'SL20', 3, 4, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(292, 8, '2', '2', 'SL13', 4, 0, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(293, 8, '2', '2', 'SL15', 4, 1, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(294, 8, '2', '2', 'SL17', 4, 2, '2021-05-05 11:07:51', '2021-05-05 11:07:51', NULL, 1),
(295, 8, '2', '2', 'SL19', 4, 3, '2021-05-05 11:07:52', '2021-05-05 11:07:52', NULL, 1),
(296, 8, '2', '2', 'SL21', 4, 4, '2021-05-05 11:07:52', '2021-05-05 11:07:52', NULL, 1),
(437, 9, '1', '1', 'V4', 0, 0, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(438, 9, '1', '1', '4', 0, 1, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(439, 9, '1', '1', '8', 0, 2, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(440, 9, '1', '1', '12', 0, 3, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(441, 9, '1', '1', '16', 0, 4, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(442, 9, '1', '1', '20', 0, 5, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(443, 9, '1', '1', '24', 0, 6, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(444, 9, '1', '1', '28', 0, 7, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(445, 9, '1', '1', '32', 0, 8, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(446, 9, '1', '1', '36', 0, 9, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(447, 9, '1', '1', '41', 0, 10, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(448, 9, '1', '1', '45', 0, 11, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(449, 9, '1', '1', 'V3', 1, 0, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(450, 9, '1', '1', '3', 1, 1, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(451, 9, '1', '1', '7', 1, 2, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(452, 9, '1', '1', '11', 1, 3, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(453, 9, '1', '1', '15', 1, 4, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(454, 9, '1', '1', '19', 1, 5, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(455, 9, '1', '1', '23', 1, 6, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(456, 9, '1', '1', '27', 1, 7, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(457, 9, '1', '1', '31', 1, 8, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(458, 9, '1', '1', '35', 1, 9, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(459, 9, '1', '1', '39', 1, 10, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(460, 9, '1', '1', '44', 1, 11, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(461, 9, '4', '1', '38', 2, 0, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(462, 9, '4', '1', '42', 2, 1, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(463, 9, '4', '1', 'V1', 2, 2, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(464, 9, '4', '1', '1', 2, 3, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(465, 9, '4', '1', '5', 2, 4, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(466, 9, '4', '1', '9', 2, 5, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(467, 9, '4', '1', '13', 2, 6, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(468, 9, '4', '1', '17', 2, 7, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(469, 9, '4', '1', '21', 2, 8, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(470, 9, '4', '1', '25', 2, 9, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(471, 9, '4', '1', '29', 2, 10, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(472, 9, '1', '1', '33', 2, 11, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(473, 9, '1', '1', '37', 3, 0, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(474, 9, '1', '1', '41', 3, 1, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(475, 9, '1', '1', 'V4', 3, 2, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(476, 9, '1', '1', '4', 3, 3, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(477, 9, '1', '1', '8', 3, 4, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(478, 9, '1', '1', '12', 3, 5, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(479, 9, '1', '1', '16', 3, 6, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(480, 9, '1', '1', 'V3', 3, 7, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(481, 9, '1', '1', '3', 3, 8, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(482, 9, '1', '1', '7', 3, 9, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(483, 9, '1', '1', '11', 3, 10, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(484, 9, '1', '1', '15', 3, 11, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(485, 9, '1', '1', 'V4', 4, 0, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(486, 9, '1', '1', '4', 4, 1, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(487, 9, '1', '1', '8', 4, 2, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(488, 9, '1', '1', '12', 4, 3, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(489, 9, '1', '1', '16', 4, 4, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(490, 9, '1', '1', 'V3', 4, 5, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(491, 9, '1', '1', '3', 4, 6, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(492, 9, '1', '1', '7', 4, 7, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(493, 9, '1', '1', '11', 4, 8, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(494, 9, '1', '1', '15', 4, 9, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(495, 9, '1', '1', '', 4, 10, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(496, 9, '1', '1', '', 4, 11, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(497, 9, '2', '2', '', 0, 0, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(498, 9, '2', '2', '', 0, 1, '2021-05-05 11:21:09', '2021-05-05 11:21:09', NULL, 1),
(499, 9, '2', '2', '', 0, 2, '2021-05-05 11:21:10', '2021-05-05 11:21:10', NULL, 1),
(500, 9, '2', '2', '', 0, 3, '2021-05-05 11:21:10', '2021-05-05 11:21:10', NULL, 1),
(501, 9, '2', '2', '', 0, 4, '2021-05-05 11:21:10', '2021-05-05 11:21:10', NULL, 1),
(502, 9, '2', '2', '', 1, 0, '2021-05-05 11:21:10', '2021-05-05 11:21:10', NULL, 1),
(503, 9, '2', '2', '', 1, 1, '2021-05-05 11:21:10', '2021-05-05 11:21:10', NULL, 1),
(504, 9, '2', '2', '', 1, 2, '2021-05-05 11:21:10', '2021-05-05 11:21:10', NULL, 1),
(505, 9, '2', '2', '', 1, 3, '2021-05-05 11:21:10', '2021-05-05 11:21:10', NULL, 1),
(506, 9, '2', '2', '', 1, 4, '2021-05-05 11:21:10', '2021-05-05 11:21:10', NULL, 1),
(575, 10, '1', '1', '1', 0, 0, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(576, 10, '1', '1', '8', 0, 1, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(577, 10, '1', '1', '9', 0, 2, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(578, 10, '1', '1', '16', 0, 3, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(579, 10, '1', '1', '17', 0, 4, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(580, 10, '1', '1', '24', 0, 5, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(581, 10, '1', '1', '25', 0, 6, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(582, 10, '1', '1', '32', 0, 7, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(583, 10, '1', '1', '33', 0, 8, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(584, 10, '1', '1', '41', 0, 9, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(585, 10, '1', '1', '2', 1, 0, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(586, 10, '1', '1', '7', 1, 1, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(587, 10, '1', '1', '10', 1, 2, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(588, 10, '1', '1', '15', 1, 3, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(589, 10, '1', '1', '18', 1, 4, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(590, 10, '1', '1', '23', 1, 5, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(591, 10, '1', '1', '26', 1, 6, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(592, 10, '1', '1', '31', 1, 7, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(593, 10, '1', '1', '34', 1, 8, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(594, 10, '1', '1', '40', 1, 9, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(595, 10, '4', '1', '39', 2, 0, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(596, 10, '4', '1', '3', 2, 1, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(597, 10, '4', '1', '6', 2, 2, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(598, 10, '4', '1', '11', 2, 3, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(599, 10, '4', '1', '14', 2, 4, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(600, 10, '4', '1', '19', 2, 5, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(601, 10, '4', '1', '22', 2, 6, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(602, 10, '4', '1', '27', 2, 7, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(603, 10, '4', '1', '30', 2, 8, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(604, 10, '1', '1', '35', 2, 9, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(605, 10, '1', '1', '38', 3, 0, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(606, 10, '1', '1', '4', 3, 1, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(607, 10, '1', '1', '5', 3, 2, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(608, 10, '1', '1', '12', 3, 3, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(609, 10, '1', '1', '13', 3, 4, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(610, 10, '1', '1', '20', 3, 5, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(611, 10, '1', '1', '21', 3, 6, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(612, 10, '1', '1', '28', 3, 7, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(613, 10, '1', '1', '29', 3, 8, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(614, 10, '1', '1', '36', 3, 9, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(615, 10, '1', '1', '37', 4, 0, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(616, 10, '1', '1', '1', 4, 1, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(617, 10, '1', '1', '8', 4, 2, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(618, 10, '1', '1', '9', 4, 3, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(619, 10, '1', '1', '16', 4, 4, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(620, 10, '1', '1', '17', 4, 5, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(621, 10, '1', '1', '24', 4, 6, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(622, 10, '1', '1', '2', 4, 7, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(623, 10, '1', '1', '7', 4, 8, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(624, 10, '1', '1', '10', 4, 9, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(625, 10, '2', '2', 'SL1', 0, 0, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(626, 10, '2', '2', 'SL3', 0, 1, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(627, 10, '2', '2', 'SL5', 0, 2, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(628, 10, '2', '2', 'SL7', 0, 3, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(629, 10, '2', '2', 'SL9', 0, 4, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(630, 10, '2', '2', 'SL11', 0, 5, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(631, 10, '4', '2', NULL, 1, 0, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(632, 10, '4', '2', NULL, 1, 1, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(633, 10, '4', '2', NULL, 1, 2, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(634, 10, '4', '2', NULL, 1, 3, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(635, 10, '4', '2', NULL, 1, 4, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(636, 10, '4', '2', NULL, 1, 5, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(637, 10, '2', '2', NULL, 2, 0, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(638, 10, '2', '2', NULL, 2, 1, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(639, 10, '2', '2', NULL, 2, 2, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(640, 10, '2', '2', NULL, 2, 3, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(641, 10, '2', '2', NULL, 2, 4, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(642, 10, '2', '2', NULL, 2, 5, '2021-05-05 14:14:25', '2021-05-05 14:14:25', NULL, 1),
(655, 11, '2', '2', 'SL1', 0, 0, '2021-05-05 14:19:12', '2021-05-05 14:19:12', NULL, 1),
(656, 11, '2', '2', 'SL2', 0, 1, '2021-05-05 14:19:12', '2021-05-05 14:19:12', NULL, 1),
(657, 11, '2', '2', 'SL3', 0, 2, '2021-05-05 14:19:12', '2021-05-05 14:19:12', NULL, 1),
(658, 11, '2', '2', 'SL4', 0, 3, '2021-05-05 14:19:12', '2021-05-05 14:19:12', NULL, 1),
(659, 11, '4', '2', NULL, 1, 0, '2021-05-05 14:19:12', '2021-05-05 14:19:12', NULL, 1),
(660, 11, '4', '2', NULL, 1, 1, '2021-05-05 14:19:12', '2021-05-05 14:19:12', NULL, 1),
(661, 11, '4', '2', NULL, 1, 2, '2021-05-05 14:19:12', '2021-05-05 14:19:12', NULL, 1),
(662, 11, '4', '2', NULL, 1, 3, '2021-05-05 14:19:12', '2021-05-05 14:19:12', NULL, 1),
(663, 11, '2', '2', 'SL5', 2, 0, '2021-05-05 14:19:12', '2021-05-05 14:19:12', NULL, 1),
(664, 11, '2', '2', 'SL6', 2, 1, '2021-05-05 14:19:12', '2021-05-05 14:19:12', NULL, 1),
(665, 11, '2', '2', 'SL7', 2, 2, '2021-05-05 14:19:12', '2021-05-05 14:19:12', NULL, 1),
(666, 11, '2', '2', 'SL8', 2, 3, '2021-05-05 14:19:12', '2021-05-05 14:19:12', NULL, 1),
(667, 14, '1', '1', '1', 0, 0, '2021-06-02 09:57:47', '2021-06-02 09:57:47', NULL, 1),
(668, 14, '1', '1', '2', 0, 1, '2021-06-02 09:57:47', '2021-06-02 09:57:47', NULL, 1),
(669, 14, '1', '1', '3', 0, 2, '2021-06-02 09:57:47', '2021-06-02 09:57:47', NULL, 1),
(670, 14, '1', '1', '4', 1, 0, '2021-06-02 09:57:47', '2021-06-02 09:57:47', NULL, 1),
(671, 14, '1', '1', '5', 1, 1, '2021-06-02 09:57:47', '2021-06-02 09:57:47', NULL, 1),
(672, 14, '1', '1', '6', 1, 2, '2021-06-02 09:57:47', '2021-06-02 09:57:47', NULL, 1),
(673, 15, '1', '1', '1', 0, 0, '2021-06-02 09:58:32', '2021-06-02 09:58:32', NULL, 1),
(674, 15, '1', '1', '2', 0, 1, '2021-06-02 09:58:32', '2021-06-02 09:58:32', NULL, 1),
(675, 15, '1', '1', '3', 0, 2, '2021-06-02 09:58:32', '2021-06-02 09:58:32', NULL, 1),
(676, 15, '1', '1', '4', 1, 0, '2021-06-02 09:58:32', '2021-06-02 09:58:32', NULL, 1),
(677, 15, '1', '1', '5', 1, 1, '2021-06-02 09:58:32', '2021-06-02 09:58:32', NULL, 1),
(678, 15, '1', '1', '6', 1, 2, '2021-06-02 09:58:32', '2021-06-02 09:58:32', NULL, 1),
(679, 16, '1', '1', '1', 0, 0, '2021-06-02 09:59:44', '2021-06-02 09:59:44', NULL, 1),
(680, 16, '1', '1', '2', 0, 1, '2021-06-02 09:59:44', '2021-06-02 09:59:44', NULL, 1),
(681, 16, '1', '1', '3', 0, 2, '2021-06-02 09:59:44', '2021-06-02 09:59:44', NULL, 1),
(682, 16, '1', '1', '4', 1, 0, '2021-06-02 09:59:44', '2021-06-02 09:59:44', NULL, 1),
(683, 16, '1', '1', '5', 1, 1, '2021-06-02 09:59:44', '2021-06-02 09:59:44', NULL, 1),
(684, 16, '1', '1', '6', 1, 2, '2021-06-02 09:59:44', '2021-06-02 09:59:44', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `site_master`
--

CREATE TABLE `site_master` (
  `id` int UNSIGNED NOT NULL,
  `site_live` int UNSIGNED NOT NULL DEFAULT '0',
  `live_at` datetime NOT NULL,
  `extra_price` double(8,2) UNSIGNED NOT NULL,
  `calender_days` int UNSIGNED NOT NULL,
  `service_charge` int UNSIGNED NOT NULL,
  `per_trasaction` double(8,2) UNSIGNED NOT NULL,
  `max_seat_booked` int UNSIGNED NOT NULL,
  `support_email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no1` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no2` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no3` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no4` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `facebook_url` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `twitter_url` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `linkedin_url` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instagram_url` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `googleplus_url` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_fare_amt` int NOT NULL,
  `earned_pts` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int UNSIGNED NOT NULL,
  `occassion` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` int DEFAULT NULL COMMENT '0-main slider 1-adv-slider1 2-adv-slider 2, 3-adv-slider-3',
  `url` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slider_img` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_tag` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `special_fare`
--

CREATE TABLE `special_fare` (
  `id` int UNSIGNED NOT NULL,
  `bus_operator_id` int DEFAULT NULL,
  `source_id` int UNSIGNED DEFAULT NULL,
  `destination_id` int UNSIGNED DEFAULT NULL,
  `date` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `seater_price` double(8,2) NOT NULL,
  `sleeper_price` double(8,2) NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `special_fare`
--

INSERT INTO `special_fare` (`id`, `bus_operator_id`, `source_id`, `destination_id`, `date`, `seater_price`, `sleeper_price`, `reason`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(1, 1, NULL, NULL, 'check', 40.00, 60.00, 'null', '2021-05-08 11:21:04', '2021-05-08 11:21:04', 'Admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_cancelation`
--

CREATE TABLE `ticket_cancelation` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_cancelation`
--

INSERT INTO `ticket_cancelation` (`id`, `name`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(1, 'Master Rule', '2020-11-01 13:17:28', '2020-11-01 13:17:28', 'Admin ', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_cancelation_rule`
--

CREATE TABLE `ticket_cancelation_rule` (
  `id` int UNSIGNED NOT NULL,
  `ticket_cancelation_id` int UNSIGNED NOT NULL,
  `hour_lag_start` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hour_lag_end` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cancelation_percentage` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_cancelation_rule`
--

INSERT INTO `ticket_cancelation_rule` (`id`, `ticket_cancelation_id`, `hour_lag_start`, `hour_lag_end`, `cancelation_percentage`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(1, 1, '0', '11', '100', '2020-11-01 14:08:00', '2020-11-01 14:08:00', 'Admin ', 1),
(2, 1, '12', '18', '35', '2020-11-01 14:08:00', '2020-11-01 14:08:00', 'Admin ', 1),
(3, 1, '19', '24', '25', '2020-11-01 14:08:00', '2020-11-01 14:08:00', 'Admin ', 1),
(4, 1, '25', '999', '20', '2020-11-01 14:08:00', '2020-11-01 14:08:00', 'Admin ', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_price`
--

CREATE TABLE `ticket_price` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `bus_operator_id` int NOT NULL,
  `bus_id` int UNSIGNED NOT NULL,
  `source_id` int UNSIGNED NOT NULL,
  `destination_id` int UNSIGNED NOT NULL,
  `base_seat_fare` double(8,2) UNSIGNED NOT NULL,
  `base_sleeper_fare` double(8,2) UNSIGNED NOT NULL,
  `dep_time` time DEFAULT NULL,
  `arr_time` time DEFAULT NULL,
  `start_j_days` int NOT NULL DEFAULT '0',
  `j_day` int NOT NULL DEFAULT '0' COMMENT '0-same day 1- next day so on.. ',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_price`
--

INSERT INTO `ticket_price` (`id`, `user_id`, `bus_operator_id`, `bus_id`, `source_id`, `destination_id`, `base_seat_fare`, `base_sleeper_fare`, `dep_time`, `arr_time`, `start_j_days`, `j_day`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(1, 1, 1, 8, 1291, 1291, 50.00, 70.00, '22:00:00', '20:00:00', 1, 1, '2021-06-04 16:54:30', '2021-06-04 16:54:30', 'Admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `user_pin` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `middle_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_name` varchar(254) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternate_phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'additional phone',
  `alternate_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'additional email',
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_role` int DEFAULT NULL,
  `rand_key` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user_pin`, `first_name`, `middle_name`, `last_name`, `thumbnail`, `email`, `location`, `org_name`, `address`, `phone`, `alternate_phone`, `alternate_email`, `password`, `user_role`, `rand_key`, `last_login`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(1, '756051', 'Arun', 'Kumar ', 'Lakhani', 'nopic.jpg', 'jagakalia@gmail.com', 'Bhadrak', 'Jagakalia', 'Bhadrak', '9040799050', NULL, NULL, 'Admin@2020', 1, 'sdfwegtb55rety43563456', '2020-11-01 14:12:29', '2020-11-01 14:12:29', '2020-11-01 14:12:29', 'Admin ', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_bank_details`
--

CREATE TABLE `user_bank_details` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `banking_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ifsc_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appdownload`
--
ALTER TABLE `appdownload`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appversion`
--
ALTER TABLE `appversion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boarding_droping`
--
ALTER TABLE `boarding_droping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `user_id` (`bus_operator_id`),
  ADD KEY `booking_customer_id` (`booking_customer_id`);

--
-- Indexes for table `booking_customer`
--
ALTER TABLE `booking_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_detail`
--
ALTER TABLE `booking_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `bus`
--
ALTER TABLE `bus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_operator_FK` (`bus_operator_id`),
  ADD KEY `bus_type_fk` (`bus_type_id`),
  ADD KEY `bus_sitting_fk` (`bus_sitting_id`),
  ADD KEY `cancellation_slab_fk` (`cancellationslabs_id`),
  ADD KEY `bus_seatlayout_id_fk` (`bus_seat_layout_id`);

--
-- Indexes for table `bus_amenities`
--
ALTER TABLE `bus_amenities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `amenities_id` (`amenities_id`);

--
-- Indexes for table `bus_cancelled`
--
ALTER TABLE `bus_cancelled`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `bus_operator_for_cancelled_FK` (`bus_operator_id`);

--
-- Indexes for table `bus_cancelled_date`
--
ALTER TABLE `bus_cancelled_date`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_cancelled_id` (`bus_cancelled_id`);

--
-- Indexes for table `bus_class`
--
ALTER TABLE `bus_class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bus_closing_hours`
--
ALTER TABLE `bus_closing_hours`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bus_contacts`
--
ALTER TABLE `bus_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `bus_extra_fare`
--
ALTER TABLE `bus_extra_fare`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `bus_gallery`
--
ALTER TABLE `bus_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `bus_operator`
--
ALTER TABLE `bus_operator`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bus_owner_fare`
--
ALTER TABLE `bus_owner_fare`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `owner_fare_id` (`owner_fare_id`);

--
-- Indexes for table `bus_safety`
--
ALTER TABLE `bus_safety`
  ADD PRIMARY KEY (`id`),
  ADD KEY `safety_bus_id_fk` (`bus_id`),
  ADD KEY `safety_id_fk` (`safety_id`);

--
-- Indexes for table `bus_schedule`
--
ALTER TABLE `bus_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `bus_schedule_date`
--
ALTER TABLE `bus_schedule_date`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_schedule_id` (`bus_schedule_id`);

--
-- Indexes for table `bus_seats`
--
ALTER TABLE `bus_seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_seats_ibfk_1` (`bus_id`),
  ADD KEY `ticket_price_FK` (`ticket_price_id`);

--
-- Indexes for table `bus_seats_extra`
--
ALTER TABLE `bus_seats_extra`
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `bus_seat_layout`
--
ALTER TABLE `bus_seat_layout`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bus_sitting`
--
ALTER TABLE `bus_sitting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bus_slots`
--
ALTER TABLE `bus_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `bus_special_fare`
--
ALTER TABLE `bus_special_fare`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `special_fare_id` (`special_fare_id`);

--
-- Indexes for table `bus_stoppage_additional_fare`
--
ALTER TABLE `bus_stoppage_additional_fare`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_stoppage_id` (`ticket_price_id`),
  ADD KEY `bus_seats_id` (`bus_seats_id`);

--
-- Indexes for table `bus_stoppage_timing`
--
ALTER TABLE `bus_stoppage_timing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stoppage_timing_bus_id_fk` (`bus_id`),
  ADD KEY `location_timing_fk` (`location_id`),
  ADD KEY `boarding_dropping_id` (`boarding_dropping_id`);

--
-- Indexes for table `bus_type`
--
ALTER TABLE `bus_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_type_fk` (`type`);

--
-- Indexes for table `cancellationslabs`
--
ALTER TABLE `cancellationslabs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `city_closing`
--
ALTER TABLE `city_closing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `location_closing_ibfk_1` (`location_id`);

--
-- Indexes for table `city_closing_extended`
--
ALTER TABLE `city_closing_extended`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `city_closing_extended_location_fk` (`location_id`);

--
-- Indexes for table `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupon_assigned_bus`
--
ALTER TABLE `coupon_assigned_bus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `coupon_assigned_id_fk` (`coupon_id`);

--
-- Indexes for table `customer_query`
--
ALTER TABLE `customer_query`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_query_category`
--
ALTER TABLE `customer_query_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_query_category_issues`
--
ALTER TABLE `customer_query_category_issues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_query_category_id` (`customer_query_category_id`);

--
-- Indexes for table `custom_pages`
--
ALTER TABLE `custom_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `extended_bus_closing_hours`
--
ALTER TABLE `extended_bus_closing_hours`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locationcode`
--
ALTER TABLE `locationcode`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `owner_fare`
--
ALTER TABLE `owner_fare`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_booking`
--
ALTER TABLE `pre_booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pre_booking_detail`
--
ALTER TABLE `pre_booking_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pre_booking_id` (`pre_booking_id`);

--
-- Indexes for table `reason`
--
ALTER TABLE `reason`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `safety`
--
ALTER TABLE `safety`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seats_ibfk_1` (`bus_seat_layout_id`);

--
-- Indexes for table `site_master`
--
ALTER TABLE `site_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `special_fare`
--
ALTER TABLE `special_fare`
  ADD PRIMARY KEY (`id`),
  ADD KEY `special_fare_operator_fk` (`bus_operator_id`),
  ADD KEY `special_fare_source_fk` (`source_id`),
  ADD KEY `special_fare_destination_fk` (`destination_id`);

--
-- Indexes for table `ticket_cancelation`
--
ALTER TABLE `ticket_cancelation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_cancelation_rule`
--
ALTER TABLE `ticket_cancelation_rule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_cancelation_id` (`ticket_cancelation_id`);

--
-- Indexes for table `ticket_price`
--
ALTER TABLE `ticket_price`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `ticket_price_bus_operator_fk` (`bus_operator_id`),
  ADD KEY `ticket_price_source_fk` (`source_id`),
  ADD KEY `ticket_price_destination_fk` (`destination_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_bank_details`
--
ALTER TABLE `user_bank_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=451;

--
-- AUTO_INCREMENT for table `appdownload`
--
ALTER TABLE `appdownload`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appversion`
--
ALTER TABLE `appversion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boarding_droping`
--
ALTER TABLE `boarding_droping`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bus`
--
ALTER TABLE `bus`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bus_amenities`
--
ALTER TABLE `bus_amenities`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `bus_cancelled`
--
ALTER TABLE `bus_cancelled`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_cancelled_date`
--
ALTER TABLE `bus_cancelled_date`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_class`
--
ALTER TABLE `bus_class`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bus_closing_hours`
--
ALTER TABLE `bus_closing_hours`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_contacts`
--
ALTER TABLE `bus_contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `bus_extra_fare`
--
ALTER TABLE `bus_extra_fare`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_gallery`
--
ALTER TABLE `bus_gallery`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_operator`
--
ALTER TABLE `bus_operator`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bus_owner_fare`
--
ALTER TABLE `bus_owner_fare`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_safety`
--
ALTER TABLE `bus_safety`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `bus_schedule`
--
ALTER TABLE `bus_schedule`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_schedule_date`
--
ALTER TABLE `bus_schedule_date`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_seats`
--
ALTER TABLE `bus_seats`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bus_seat_layout`
--
ALTER TABLE `bus_seat_layout`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `bus_sitting`
--
ALTER TABLE `bus_sitting`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `bus_slots`
--
ALTER TABLE `bus_slots`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_special_fare`
--
ALTER TABLE `bus_special_fare`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_stoppage_additional_fare`
--
ALTER TABLE `bus_stoppage_additional_fare`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_stoppage_timing`
--
ALTER TABLE `bus_stoppage_timing`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bus_type`
--
ALTER TABLE `bus_type`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=339;

--
-- AUTO_INCREMENT for table `cancellationslabs`
--
ALTER TABLE `cancellationslabs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `city_closing`
--
ALTER TABLE `city_closing`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `city_closing_extended`
--
ALTER TABLE `city_closing_extended`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_assigned_bus`
--
ALTER TABLE `coupon_assigned_bus`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_query`
--
ALTER TABLE `customer_query`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_query_category`
--
ALTER TABLE `customer_query_category`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_query_category_issues`
--
ALTER TABLE `customer_query_category_issues`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_pages`
--
ALTER TABLE `custom_pages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `extended_bus_closing_hours`
--
ALTER TABLE `extended_bus_closing_hours`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1783;

--
-- AUTO_INCREMENT for table `locationcode`
--
ALTER TABLE `locationcode`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `owner_fare`
--
ALTER TABLE `owner_fare`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pre_booking`
--
ALTER TABLE `pre_booking`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pre_booking_detail`
--
ALTER TABLE `pre_booking_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reason`
--
ALTER TABLE `reason`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `safety`
--
ALTER TABLE `safety`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=685;

--
-- AUTO_INCREMENT for table `site_master`
--
ALTER TABLE `site_master`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `special_fare`
--
ALTER TABLE `special_fare`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ticket_cancelation`
--
ALTER TABLE `ticket_cancelation`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ticket_cancelation_rule`
--
ALTER TABLE `ticket_cancelation_rule`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ticket_price`
--
ALTER TABLE `ticket_price`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_bank_details`
--
ALTER TABLE `user_bank_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `boarding_droping`
--
ALTER TABLE `boarding_droping`
  ADD CONSTRAINT `boarding_droping_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`);

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`bus_operator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`booking_customer_id`) REFERENCES `booking_customer` (`id`),
  ADD CONSTRAINT `booking_ibfk_4` FOREIGN KEY (`booking_customer_id`) REFERENCES `booking_customer` (`id`);

--
-- Constraints for table `bus`
--
ALTER TABLE `bus`
  ADD CONSTRAINT `bus_operator_FK` FOREIGN KEY (`bus_operator_id`) REFERENCES `bus_operator` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `bus_seatlayout_id_fk` FOREIGN KEY (`bus_seat_layout_id`) REFERENCES `bus_seat_layout` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `bus_sitting_fk` FOREIGN KEY (`bus_sitting_id`) REFERENCES `bus_sitting` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `bus_type_fk` FOREIGN KEY (`bus_type_id`) REFERENCES `bus_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `cancellation_slab_fk` FOREIGN KEY (`cancellationslabs_id`) REFERENCES `cancellationslabs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `bus_amenities`
--
ALTER TABLE `bus_amenities`
  ADD CONSTRAINT `bus_amenities_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`),
  ADD CONSTRAINT `bus_amenities_ibfk_2` FOREIGN KEY (`amenities_id`) REFERENCES `amenities` (`id`);

--
-- Constraints for table `bus_cancelled`
--
ALTER TABLE `bus_cancelled`
  ADD CONSTRAINT `bus_operator_for_cancelled_FK` FOREIGN KEY (`bus_operator_id`) REFERENCES `bus_operator` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `bus_cancelled_date`
--
ALTER TABLE `bus_cancelled_date`
  ADD CONSTRAINT `bus_cancelled_date_ibfk_1` FOREIGN KEY (`bus_cancelled_id`) REFERENCES `bus_cancelled` (`id`);

--
-- Constraints for table `bus_contacts`
--
ALTER TABLE `bus_contacts`
  ADD CONSTRAINT `bus_contacts_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`);

--
-- Constraints for table `bus_extra_fare`
--
ALTER TABLE `bus_extra_fare`
  ADD CONSTRAINT `bus_extra_fare_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`);

--
-- Constraints for table `bus_gallery`
--
ALTER TABLE `bus_gallery`
  ADD CONSTRAINT `bus_gallery_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`);

--
-- Constraints for table `bus_safety`
--
ALTER TABLE `bus_safety`
  ADD CONSTRAINT `safety_bus_id_fk` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `safety_id_fk` FOREIGN KEY (`safety_id`) REFERENCES `safety` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `bus_schedule_date`
--
ALTER TABLE `bus_schedule_date`
  ADD CONSTRAINT `bus_schedule_date_ibfk_1` FOREIGN KEY (`bus_schedule_id`) REFERENCES `bus_schedule` (`id`);

--
-- Constraints for table `bus_seats`
--
ALTER TABLE `bus_seats`
  ADD CONSTRAINT `bus_seats_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`),
  ADD CONSTRAINT `ticket_price_FK` FOREIGN KEY (`ticket_price_id`) REFERENCES `ticket_price` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `bus_seats_extra`
--
ALTER TABLE `bus_seats_extra`
  ADD CONSTRAINT `bus_seats_extra_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`);

--
-- Constraints for table `bus_slots`
--
ALTER TABLE `bus_slots`
  ADD CONSTRAINT `bus_slots_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`);

--
-- Constraints for table `bus_stoppage_additional_fare`
--
ALTER TABLE `bus_stoppage_additional_fare`
  ADD CONSTRAINT `bus_stoppage_additional_fare_ibfk_1` FOREIGN KEY (`ticket_price_id`) REFERENCES `ticket_price` (`id`),
  ADD CONSTRAINT `bus_stoppage_additional_fare_ibfk_2` FOREIGN KEY (`bus_seats_id`) REFERENCES `bus_seats` (`id`);

--
-- Constraints for table `bus_stoppage_timing`
--
ALTER TABLE `bus_stoppage_timing`
  ADD CONSTRAINT `boarding_dropping_id` FOREIGN KEY (`boarding_dropping_id`) REFERENCES `boarding_droping` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `location_timing_fk` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `stoppage_timing_bus_id_fk` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `bus_type`
--
ALTER TABLE `bus_type`
  ADD CONSTRAINT `class_type_fk` FOREIGN KEY (`type`) REFERENCES `bus_class` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `city_closing`
--
ALTER TABLE `city_closing`
  ADD CONSTRAINT `city_closing_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`),
  ADD CONSTRAINT `location_closing_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `city_closing_extended`
--
ALTER TABLE `city_closing_extended`
  ADD CONSTRAINT `city_closing_extended_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`),
  ADD CONSTRAINT `city_closing_extended_location_fk` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `coupon_assigned_bus`
--
ALTER TABLE `coupon_assigned_bus`
  ADD CONSTRAINT `coupon_assigned_bus_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`),
  ADD CONSTRAINT `coupon_assigned_id_fk` FOREIGN KEY (`coupon_id`) REFERENCES `coupon` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `customer_query_category_issues`
--
ALTER TABLE `customer_query_category_issues`
  ADD CONSTRAINT `customer_query_category_issues_ibfk_1` FOREIGN KEY (`customer_query_category_id`) REFERENCES `customer_query_category` (`id`);

--
-- Constraints for table `locationcode`
--
ALTER TABLE `locationcode`
  ADD CONSTRAINT `locationcode_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`);

--
-- Constraints for table `pre_booking`
--
ALTER TABLE `pre_booking`
  ADD CONSTRAINT `pre_booking_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`),
  ADD CONSTRAINT `pre_booking_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `pre_booking_detail`
--
ALTER TABLE `pre_booking_detail`
  ADD CONSTRAINT `pre_booking_detail_ibfk_1` FOREIGN KEY (`pre_booking_id`) REFERENCES `pre_booking` (`id`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`);

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`bus_seat_layout_id`) REFERENCES `bus_seat_layout` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `special_fare`
--
ALTER TABLE `special_fare`
  ADD CONSTRAINT `special_fare_destination_fk` FOREIGN KEY (`destination_id`) REFERENCES `location` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `special_fare_operator_fk` FOREIGN KEY (`bus_operator_id`) REFERENCES `bus_operator` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `special_fare_source_fk` FOREIGN KEY (`source_id`) REFERENCES `location` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `ticket_cancelation_rule`
--
ALTER TABLE `ticket_cancelation_rule`
  ADD CONSTRAINT `ticket_cancelation_rule_ibfk_1` FOREIGN KEY (`ticket_cancelation_id`) REFERENCES `ticket_cancelation` (`id`);

--
-- Constraints for table `ticket_price`
--
ALTER TABLE `ticket_price`
  ADD CONSTRAINT `ticket_price_bus_operator_fk` FOREIGN KEY (`bus_operator_id`) REFERENCES `bus_operator` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `ticket_price_destination_fk` FOREIGN KEY (`destination_id`) REFERENCES `location` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `ticket_price_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `ticket_price_ibfk_2` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`),
  ADD CONSTRAINT `ticket_price_source_fk` FOREIGN KEY (`source_id`) REFERENCES `location` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `user_bank_details`
--
ALTER TABLE `user_bank_details`
  ADD CONSTRAINT `user_bank_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
