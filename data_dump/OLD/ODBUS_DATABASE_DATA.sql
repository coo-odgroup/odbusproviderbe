-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 03, 2021 at 02:40 PM
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
(1, 1, 1, 'Durga Shakti', 'Angul', 'OD 02 BE 1002', 'Luxury AC', 315, 3, 7, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-05-27 13:39:14', '2021-05-27 14:25:37', 'Admin', 2, 1000),
(2, 1, 1, 'Durga Shakti', 'Angul', 'OD 02 BE 1002', 'Luxury AC', 315, 3, 7, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-05-27 13:40:25', '2021-05-27 14:25:46', 'Admin', 2, 1000),
(3, 1, 1, 'Durga Shakti', 'Angul', 'OD 02 BE 1002', 'Luxury AC', 315, 3, 7, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-05-27 14:22:53', '2021-05-27 14:25:40', 'Admin', 2, 1000),
(4, 1, 1, 'Durga Shakti', 'Angul', 'OD 02 BE 1002', 'Luxury AC', 315, 3, 7, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-05-27 14:23:20', '2021-05-27 14:25:43', 'Admin', 2, 1000),
(5, 1, 1, 'Durga Shakti', 'Angul', 'OD 02 BE 1002', 'Luxury AC', 315, 3, 7, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0, 1000),
(6, 1, 1, 'Durga Shakti', 'Angul', 'OD 02 BE 1005', 'Luxury AC', 315, 3, 7, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-05-27 14:36:46', '2021-05-27 14:37:15', 'Admin', 2, 1000),
(7, 1, 1, 'Sana', 'Angul', 'OD 02 BE 1001', 'Luxury Volvo', 317, 3, 7, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-05-28 14:32:35', '2021-05-28 14:32:35', 'Admin', 0, 1000),
(8, 1, 1, 'Durga Shakti', 'Angul', 'OD 02 BE 1002', 'Luxury AC', 315, 3, 7, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-05-31 05:17:40', '2021-05-31 05:17:40', 'Admin', 0, 1000),
(9, 1, 1, 'Durga Shakti', 'Angul', 'OD 02 BE 1002', 'Luxury AC', 315, 3, 7, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-05-31 05:19:37', '2021-05-31 05:19:37', 'Admin', 0, 1000),
(10, 1, 1, 'Durga Shakti', 'Angul', 'OD 02 BE 1002', 'Luxury AC', 315, 3, 7, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0, 1000),
(11, 1, 1, 'Durga Shakti', 'Angul', 'OD 02 BE 1002', 'Luxury AC', 315, 3, 7, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0, 1000),
(12, 1, 1, 'Durga Shakti', 'Angul', 'OD 02 BE 1005', 'Luxury AC', 315, 3, 1, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-05-31 13:23:38', '2021-05-31 13:23:38', 'Admin', 0, 1000),
(13, 1, 1, 'Durga Shakti', 'Angul', 'OD 02 BE 1002', 'Luxury AC', 315, 3, 7, 6, 0, NULL, NULL, 0, NULL, NULL, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0, 1000),
(14, 1, 1, 'Demo 1001', 'Angul', 'OD DEMO 001', 'Luxury bus', 315, 3, 1, 6, 0, NULL, NULL, 0, NULL, 'None', '2021-06-02 05:57:05', '2021-06-02 05:57:05', 'Admin', 0, 1000);

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
(1, 1, 426, '2021-05-27 13:39:14', '2021-05-27 13:39:14', 'Admin', 1),
(2, 1, 428, '2021-05-27 13:39:14', '2021-05-27 13:39:14', 'Admin', 1),
(3, 2, 426, '2021-05-27 13:40:25', '2021-05-27 13:40:25', 'Admin', 1),
(4, 2, 428, '2021-05-27 13:40:25', '2021-05-27 13:40:25', 'Admin', 1),
(5, 3, 426, '2021-05-27 14:22:53', '2021-05-27 14:22:53', 'Admin', 1),
(6, 3, 428, '2021-05-27 14:22:53', '2021-05-27 14:22:53', 'Admin', 1),
(7, 4, 426, '2021-05-27 14:23:20', '2021-05-27 14:23:20', 'Admin', 1),
(8, 4, 428, '2021-05-27 14:23:20', '2021-05-27 14:23:20', 'Admin', 1),
(9, 5, 426, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 1),
(10, 5, 428, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 1),
(11, 7, 428, '2021-05-28 14:32:35', '2021-05-28 14:32:35', 'Admin', 1),
(12, 7, 431, '2021-05-28 14:32:35', '2021-05-28 14:32:35', 'Admin', 1),
(13, 8, 426, '2021-05-31 05:17:40', '2021-05-31 05:17:40', 'Admin', 1),
(14, 8, 428, '2021-05-31 05:17:40', '2021-05-31 05:17:40', 'Admin', 1),
(15, 9, 426, '2021-05-31 05:19:37', '2021-05-31 05:19:37', 'Admin', 1),
(16, 9, 428, '2021-05-31 05:19:37', '2021-05-31 05:19:37', 'Admin', 1),
(17, 10, 426, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 1),
(18, 10, 428, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 1),
(19, 11, 426, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 1),
(20, 11, 428, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 1),
(21, 13, 426, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 1),
(22, 13, 428, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 1),
(23, 14, 426, '2021-06-02 05:57:06', '2021-06-02 05:57:06', 'Admin', 1),
(24, 14, 428, '2021-06-02 05:57:06', '2021-06-02 05:57:06', 'Admin', 1),
(25, 14, 429, '2021-06-02 05:57:06', '2021-06-02 05:57:06', 'Admin', 1);

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
(1, 1, 2, '2545875891', 1, 1, '2021-05-27 13:39:14', '2021-05-27 13:39:14', 'Admin', 1),
(2, 1, 1, '9865986545', 1, 1, '2021-05-27 13:39:14', '2021-05-27 13:39:14', 'Admin', 1),
(3, 1, 0, '8978456859', 1, 1, '2021-05-27 13:39:14', '2021-05-27 13:39:14', 'Admin', 1),
(4, 2, 2, '2545875891', 1, 1, '2021-05-27 13:40:25', '2021-05-27 13:40:25', 'Admin', 1),
(5, 2, 1, '9865986545', 1, 1, '2021-05-27 13:40:25', '2021-05-27 13:40:25', 'Admin', 1),
(6, 2, 0, '8978456859', 1, 1, '2021-05-27 13:40:25', '2021-05-27 13:40:25', 'Admin', 1),
(7, 3, 2, '2545875891', 1, 1, '2021-05-27 14:22:53', '2021-05-27 14:22:53', 'Admin', 1),
(8, 3, 1, '9865986545', 1, 1, '2021-05-27 14:22:53', '2021-05-27 14:22:53', 'Admin', 1),
(9, 3, 0, '8978456859', 1, 1, '2021-05-27 14:22:53', '2021-05-27 14:22:53', 'Admin', 1),
(10, 4, 2, '2545875891', 1, 1, '2021-05-27 14:23:20', '2021-05-27 14:23:20', 'Admin', 1),
(11, 4, 1, '9865986545', 1, 1, '2021-05-27 14:23:20', '2021-05-27 14:23:20', 'Admin', 1),
(12, 4, 0, '8978456859', 1, 1, '2021-05-27 14:23:20', '2021-05-27 14:23:20', 'Admin', 1),
(13, 5, 2, '2545875891', 1, 1, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 1),
(14, 5, 1, '9865986545', 1, 1, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 1),
(15, 5, 0, '8978456859', 1, 1, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 1),
(16, 7, 2, '4562154252', 1, 1, '2021-05-28 14:32:35', '2021-05-28 14:32:35', 'Admin', 1),
(17, 7, 1, '4562154251', 1, 1, '2021-05-28 14:32:35', '2021-05-28 14:32:35', 'Admin', 1),
(18, 7, 0, '4562154253', 1, 1, '2021-05-28 14:32:35', '2021-05-28 14:32:35', 'Admin', 1),
(19, 8, 2, '2545875891', 1, 1, '2021-05-31 05:17:40', '2021-05-31 05:17:40', 'Admin', 1),
(20, 8, 1, '9865986545', 1, 1, '2021-05-31 05:17:40', '2021-05-31 05:17:40', 'Admin', 1),
(21, 8, 0, '8978456859', 1, 1, '2021-05-31 05:17:40', '2021-05-31 05:17:40', 'Admin', 1),
(22, 9, 2, '2545875891', 1, 1, '2021-05-31 05:19:37', '2021-05-31 05:19:37', 'Admin', 1),
(23, 9, 1, '9865986545', 1, 1, '2021-05-31 05:19:37', '2021-05-31 05:19:37', 'Admin', 1),
(24, 9, 0, '8978456859', 1, 1, '2021-05-31 05:19:37', '2021-05-31 05:19:37', 'Admin', 1),
(25, 10, 2, '2545875891', 1, 1, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 1),
(26, 10, 1, '9865986545', 1, 1, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 1),
(27, 10, 0, '8978456859', 1, 1, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 1),
(28, 11, 2, '2545875891', 1, 1, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 1),
(29, 11, 1, '9865986545', 1, 1, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 1),
(30, 11, 0, '8978456859', 1, 1, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 1),
(31, 13, 2, '2545875891', 0, 0, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 1),
(32, 13, 1, '9865986545', 0, 0, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 1),
(33, 13, 0, '8978456859', 0, 0, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 1),
(34, 14, 2, '1234568785', 1, 1, '2021-06-02 05:57:06', '2021-06-02 05:57:06', 'Admin', 1),
(35, 14, 1, '9875874585', 1, 1, '2021-06-02 05:57:06', '2021-06-02 05:57:06', 'Admin', 1),
(36, 14, 0, '5458745858', 1, 1, '2021-06-02 05:57:06', '2021-06-02 05:57:06', 'Admin', 1);

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
  `created_date` datetime NOT NULL,
  `updated_date` tinyint NOT NULL,
  `created_by` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 5, 5, 0, 1, 1, 0, '9', '0', 325.00, '2021-05-27 14:24:13', '2021-05-28 13:12:01', 'Admin', 0),
(2, 5, 5, 0, 1, 1, 0, '16', '0', 320.00, '2021-05-27 14:24:13', '2021-05-28 13:12:01', 'Admin', 0),
(3, 5, 5, 0, 1, 1, 0, '17', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(4, 5, 5, 0, 1, 1, 0, '24', '0', 0.00, '2021-05-27 14:24:13', '2021-05-28 13:14:37', 'Admin', 0),
(5, 5, 5, 0, 1, 1, 0, '25', '0', 0.00, '2021-05-27 14:24:13', '2021-05-28 13:14:37', 'Admin', 0),
(6, 5, 5, 0, 1, 1, 0, '32', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(7, 5, 5, 0, 1, 1, 0, '10', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(8, 5, 5, 0, 1, 1, 0, '15', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(9, 5, 5, 0, 1, 1, 0, '18', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(10, 5, 5, 0, 1, 1, 0, '23', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(11, 5, 5, 0, 1, 1, 0, '26', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(12, 5, 5, 0, 1, 1, 0, '31', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(13, 5, 5, 0, 1, 1, 0, '11', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(14, 5, 5, 0, 1, 1, 0, '14', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(15, 5, 5, 0, 1, 1, 0, '19', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(16, 5, 5, 0, 1, 1, 0, '22', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(17, 5, 5, 0, 1, 1, 0, '27', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(18, 5, 5, 0, 1, 1, 0, '30', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(19, 5, 5, 0, 1, 1, 0, '12', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(20, 5, 5, 0, 1, 1, 0, '13', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(21, 5, 5, 0, 1, 1, 0, '20', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(22, 5, 5, 0, 1, 1, 0, '21', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(23, 5, 5, 0, 1, 1, 0, '28', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(24, 5, 5, 0, 1, 1, 0, '29', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(25, 5, 6, 0, 1, 1, 0, '9', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(26, 5, 6, 0, 1, 1, 0, '16', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(27, 5, 6, 0, 1, 1, 0, '17', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(28, 5, 6, 0, 1, 1, 0, '24', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(29, 5, 6, 0, 1, 1, 0, '25', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(30, 5, 6, 0, 1, 1, 0, '32', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(31, 5, 6, 0, 1, 1, 0, '10', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(32, 5, 6, 0, 1, 1, 0, '15', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(33, 5, 6, 0, 1, 1, 0, '18', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(34, 5, 6, 0, 1, 1, 0, '23', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(35, 5, 6, 0, 1, 1, 0, '26', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(36, 5, 6, 0, 1, 1, 0, '31', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(37, 5, 6, 0, 1, 1, 0, '11', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(38, 5, 6, 0, 1, 1, 0, '14', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(39, 5, 6, 0, 1, 1, 0, '19', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(40, 5, 6, 0, 1, 1, 0, '22', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(41, 5, 6, 0, 1, 1, 0, '27', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(42, 5, 6, 0, 1, 1, 0, '30', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(43, 5, 6, 0, 1, 1, 0, '12', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(44, 5, 6, 0, 1, 1, 0, '13', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(45, 5, 6, 0, 1, 1, 0, '20', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(46, 5, 6, 0, 1, 1, 0, '21', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(47, 5, 6, 0, 1, 1, 0, '28', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(48, 5, 6, 0, 1, 1, 0, '29', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(49, 5, 7, 0, 1, 1, 0, '9', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(50, 5, 7, 0, 1, 1, 0, '16', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(51, 5, 7, 0, 1, 1, 0, '17', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(52, 5, 7, 0, 1, 1, 0, '24', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(53, 5, 7, 0, 1, 1, 0, '25', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(54, 5, 7, 0, 1, 1, 0, '32', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(55, 5, 7, 0, 1, 1, 0, '10', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(56, 5, 7, 0, 1, 1, 0, '15', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(57, 5, 7, 0, 1, 1, 0, '18', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(58, 5, 7, 0, 1, 1, 0, '23', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(59, 5, 7, 0, 1, 1, 0, '26', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(60, 5, 7, 0, 1, 1, 0, '31', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(61, 5, 7, 0, 1, 1, 0, '11', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(62, 5, 7, 0, 1, 1, 0, '14', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(63, 5, 7, 0, 1, 1, 0, '19', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(64, 5, 7, 0, 1, 1, 0, '22', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(65, 5, 7, 0, 1, 1, 0, '27', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(66, 5, 7, 0, 1, 1, 0, '30', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(67, 5, 7, 0, 1, 1, 0, '12', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(68, 5, 7, 0, 1, 1, 0, '13', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(69, 5, 7, 0, 1, 1, 0, '20', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(70, 5, 7, 0, 1, 1, 0, '21', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(71, 5, 7, 0, 1, 1, 0, '28', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(72, 5, 7, 0, 1, 1, 0, '29', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(73, 5, 8, 0, 1, 1, 0, '9', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(74, 5, 8, 0, 1, 1, 0, '16', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(75, 5, 8, 0, 1, 1, 0, '17', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(76, 5, 8, 0, 1, 1, 0, '24', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(77, 5, 8, 0, 1, 1, 0, '25', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(78, 5, 8, 0, 1, 1, 0, '32', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(79, 5, 8, 0, 1, 1, 0, '10', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(80, 5, 8, 0, 1, 1, 0, '15', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(81, 5, 8, 0, 1, 1, 0, '18', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(82, 5, 8, 0, 1, 1, 0, '23', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(83, 5, 8, 0, 1, 1, 0, '26', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(84, 5, 8, 0, 1, 1, 0, '31', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(85, 5, 8, 0, 1, 1, 0, '11', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(86, 5, 8, 0, 1, 1, 0, '14', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(87, 5, 8, 0, 1, 1, 0, '19', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(88, 5, 8, 0, 1, 1, 0, '22', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(89, 5, 8, 0, 1, 1, 0, '27', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(90, 5, 8, 0, 1, 1, 0, '30', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(91, 5, 8, 0, 1, 1, 0, '12', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(92, 5, 8, 0, 1, 1, 0, '13', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(93, 5, 8, 0, 1, 1, 0, '20', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(94, 5, 8, 0, 1, 1, 0, '21', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(95, 5, 8, 0, 1, 1, 0, '28', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(96, 5, 8, 0, 1, 1, 0, '29', '0', 0.00, '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(97, 7, 9, 0, 1, 1, 0, '1', '0', 0.00, '2021-05-28 14:32:35', '2021-05-28 14:32:35', 'Admin', 0),
(98, 7, 9, 0, 1, 1, 0, '41', '0', 355.00, '2021-05-28 14:32:35', '2021-05-28 14:33:39', 'Admin', 0),
(99, 7, 9, 0, 1, 1, 0, '2', '0', 0.00, '2021-05-28 14:32:35', '2021-05-28 14:32:35', 'Admin', 0),
(100, 7, 9, 0, 1, 1, 0, '40', '0', 355.00, '2021-05-28 14:32:35', '2021-05-28 14:33:39', 'Admin', 0),
(101, 7, 9, 0, 1, 1, 0, '39', '0', 355.00, '2021-05-28 14:32:35', '2021-05-28 14:33:39', 'Admin', 0),
(102, 7, 9, 0, 1, 1, 0, '3', '0', 0.00, '2021-05-28 14:32:35', '2021-05-28 14:32:35', 'Admin', 0),
(103, 7, 9, 0, 1, 1, 0, '38', '0', 355.00, '2021-05-28 14:32:35', '2021-05-28 14:33:39', 'Admin', 0),
(104, 7, 9, 0, 1, 1, 0, '4', '0', 0.00, '2021-05-28 14:32:35', '2021-05-28 14:32:35', 'Admin', 0),
(105, 7, 9, 0, 1, 1, 0, '37', '0', 355.00, '2021-05-28 14:32:35', '2021-05-28 14:33:39', 'Admin', 0),
(106, 7, 10, 0, 1, 1, 0, '1', '0', 0.00, '2021-05-28 14:32:35', '2021-05-28 14:32:35', 'Admin', 0),
(107, 7, 10, 0, 1, 1, 0, '41', '0', 345.00, '2021-05-28 14:32:36', '2021-05-28 14:33:39', 'Admin', 0),
(108, 7, 10, 0, 1, 1, 0, '2', '0', 0.00, '2021-05-28 14:32:36', '2021-05-28 14:32:36', 'Admin', 0),
(109, 7, 10, 0, 1, 1, 0, '40', '0', 345.00, '2021-05-28 14:32:36', '2021-05-28 14:33:39', 'Admin', 0),
(110, 7, 10, 0, 1, 1, 0, '39', '0', 345.00, '2021-05-28 14:32:36', '2021-05-28 14:33:39', 'Admin', 0),
(111, 7, 10, 0, 1, 1, 0, '3', '0', 0.00, '2021-05-28 14:32:36', '2021-05-28 14:32:36', 'Admin', 0),
(112, 7, 10, 0, 1, 1, 0, '38', '0', 345.00, '2021-05-28 14:32:36', '2021-05-28 14:33:39', 'Admin', 0),
(113, 7, 10, 0, 1, 1, 0, '4', '0', 0.00, '2021-05-28 14:32:36', '2021-05-28 14:32:36', 'Admin', 0),
(114, 7, 10, 0, 1, 1, 0, '37', '0', 345.00, '2021-05-28 14:32:36', '2021-05-28 14:33:39', 'Admin', 0),
(115, 10, 11, 0, 1, 1, 0, '9', '0', 0.00, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(116, 10, 11, 0, 1, 1, 0, '16', '0', 0.00, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(117, 10, 11, 0, 1, 1, 0, '17', '0', 0.00, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(118, 10, 11, 0, 1, 1, 0, '24', '0', 0.00, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(119, 10, 11, 0, 1, 1, 0, '25', '0', 0.00, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(120, 10, 11, 0, 1, 1, 0, '32', '0', 0.00, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(121, 10, 11, 0, 1, 1, 0, '10', '0', 0.00, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(122, 10, 11, 0, 1, 1, 0, '15', '0', 0.00, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(123, 10, 11, 0, 1, 1, 0, '18', '0', 0.00, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(124, 10, 11, 0, 1, 1, 0, '23', '0', 0.00, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(125, 10, 11, 0, 1, 1, 0, '26', '0', 0.00, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(126, 10, 11, 0, 1, 1, 0, '31', '0', 0.00, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(127, 10, 11, 0, 1, 1, 0, '11', '0', 0.00, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(128, 10, 11, 0, 1, 1, 0, '14', '0', 0.00, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(129, 10, 11, 0, 1, 1, 0, '19', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(130, 10, 11, 0, 1, 1, 0, '22', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(131, 10, 11, 0, 1, 1, 0, '27', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(132, 10, 11, 0, 1, 1, 0, '30', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(133, 10, 11, 0, 1, 1, 0, '12', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(134, 10, 11, 0, 1, 1, 0, '13', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(135, 10, 11, 0, 1, 1, 0, '20', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(136, 10, 11, 0, 1, 1, 0, '21', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(137, 10, 11, 0, 1, 1, 0, '28', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(138, 10, 11, 0, 1, 1, 0, '29', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(139, 10, 12, 0, 1, 1, 0, '9', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(140, 10, 12, 0, 1, 1, 0, '16', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(141, 10, 12, 0, 1, 1, 0, '17', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(142, 10, 12, 0, 1, 1, 0, '24', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(143, 10, 12, 0, 1, 1, 0, '25', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(144, 10, 12, 0, 1, 1, 0, '32', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(145, 10, 12, 0, 1, 1, 0, '10', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(146, 10, 12, 0, 1, 1, 0, '15', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(147, 10, 12, 0, 1, 1, 0, '18', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(148, 10, 12, 0, 1, 1, 0, '23', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(149, 10, 12, 0, 1, 1, 0, '26', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(150, 10, 12, 0, 1, 1, 0, '31', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(151, 10, 12, 0, 1, 1, 0, '11', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(152, 10, 12, 0, 1, 1, 0, '14', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(153, 10, 12, 0, 1, 1, 0, '19', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(154, 10, 12, 0, 1, 1, 0, '22', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(155, 10, 12, 0, 1, 1, 0, '27', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(156, 10, 12, 0, 1, 1, 0, '30', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(157, 10, 12, 0, 1, 1, 0, '12', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(158, 10, 12, 0, 1, 1, 0, '13', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(159, 10, 12, 0, 1, 1, 0, '20', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(160, 10, 12, 0, 1, 1, 0, '21', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(161, 10, 12, 0, 1, 1, 0, '28', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(162, 10, 12, 0, 1, 1, 0, '29', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(163, 10, 13, 0, 1, 1, 0, '9', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(164, 10, 13, 0, 1, 1, 0, '16', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(165, 10, 13, 0, 1, 1, 0, '17', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(166, 10, 13, 0, 1, 1, 0, '24', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(167, 10, 13, 0, 1, 1, 0, '25', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(168, 10, 13, 0, 1, 1, 0, '32', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(169, 10, 13, 0, 1, 1, 0, '10', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(170, 10, 13, 0, 1, 1, 0, '15', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(171, 10, 13, 0, 1, 1, 0, '18', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(172, 10, 13, 0, 1, 1, 0, '23', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(173, 10, 13, 0, 1, 1, 0, '26', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(174, 10, 13, 0, 1, 1, 0, '31', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(175, 10, 13, 0, 1, 1, 0, '11', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(176, 10, 13, 0, 1, 1, 0, '14', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(177, 10, 13, 0, 1, 1, 0, '19', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(178, 10, 13, 0, 1, 1, 0, '22', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(179, 10, 13, 0, 1, 1, 0, '27', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(180, 10, 13, 0, 1, 1, 0, '30', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(181, 10, 13, 0, 1, 1, 0, '12', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(182, 10, 13, 0, 1, 1, 0, '13', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(183, 10, 13, 0, 1, 1, 0, '20', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(184, 10, 13, 0, 1, 1, 0, '21', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(185, 10, 13, 0, 1, 1, 0, '28', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(186, 10, 13, 0, 1, 1, 0, '29', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(187, 10, 14, 0, 1, 1, 0, '9', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(188, 10, 14, 0, 1, 1, 0, '16', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(189, 10, 14, 0, 1, 1, 0, '17', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(190, 10, 14, 0, 1, 1, 0, '24', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(191, 10, 14, 0, 1, 1, 0, '25', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(192, 10, 14, 0, 1, 1, 0, '32', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(193, 10, 14, 0, 1, 1, 0, '10', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(194, 10, 14, 0, 1, 1, 0, '15', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(195, 10, 14, 0, 1, 1, 0, '18', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(196, 10, 14, 0, 1, 1, 0, '23', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(197, 10, 14, 0, 1, 1, 0, '26', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(198, 10, 14, 0, 1, 1, 0, '31', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(199, 10, 14, 0, 1, 1, 0, '11', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(200, 10, 14, 0, 1, 1, 0, '14', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(201, 10, 14, 0, 1, 1, 0, '19', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(202, 10, 14, 0, 1, 1, 0, '22', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(203, 10, 14, 0, 1, 1, 0, '27', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(204, 10, 14, 0, 1, 1, 0, '30', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(205, 10, 14, 0, 1, 1, 0, '12', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(206, 10, 14, 0, 1, 1, 0, '13', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(207, 10, 14, 0, 1, 1, 0, '20', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(208, 10, 14, 0, 1, 1, 0, '21', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(209, 10, 14, 0, 1, 1, 0, '28', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(210, 10, 14, 0, 1, 1, 0, '29', '0', 0.00, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(211, 11, 15, 0, 1, 1, 0, '9', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(212, 11, 15, 0, 1, 1, 0, '16', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(213, 11, 15, 0, 1, 1, 0, '17', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(214, 11, 15, 0, 1, 1, 0, '24', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(215, 11, 15, 0, 1, 1, 0, '25', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(216, 11, 15, 0, 1, 1, 0, '32', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(217, 11, 15, 0, 1, 1, 0, '10', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(218, 11, 15, 0, 1, 1, 0, '15', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(219, 11, 15, 0, 1, 1, 0, '18', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(220, 11, 15, 0, 1, 1, 0, '23', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(221, 11, 15, 0, 1, 1, 0, '26', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(222, 11, 15, 0, 1, 1, 0, '31', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(223, 11, 15, 0, 1, 1, 0, '11', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(224, 11, 15, 0, 1, 1, 0, '14', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(225, 11, 15, 0, 1, 1, 0, '19', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(226, 11, 15, 0, 1, 1, 0, '22', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(227, 11, 15, 0, 1, 1, 0, '27', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(228, 11, 15, 0, 1, 1, 0, '30', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(229, 11, 15, 0, 1, 1, 0, '12', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(230, 11, 15, 0, 1, 1, 0, '13', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(231, 11, 15, 0, 1, 1, 0, '20', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(232, 11, 15, 0, 1, 1, 0, '21', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(233, 11, 15, 0, 1, 1, 0, '28', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(234, 11, 15, 0, 1, 1, 0, '29', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(235, 11, 16, 0, 1, 1, 0, '9', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(236, 11, 16, 0, 1, 1, 0, '16', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(237, 11, 16, 0, 1, 1, 0, '17', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(238, 11, 16, 0, 1, 1, 0, '24', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(239, 11, 16, 0, 1, 1, 0, '25', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(240, 11, 16, 0, 1, 1, 0, '32', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(241, 11, 16, 0, 1, 1, 0, '10', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(242, 11, 16, 0, 1, 1, 0, '15', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(243, 11, 16, 0, 1, 1, 0, '18', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(244, 11, 16, 0, 1, 1, 0, '23', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(245, 11, 16, 0, 1, 1, 0, '26', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(246, 11, 16, 0, 1, 1, 0, '31', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(247, 11, 16, 0, 1, 1, 0, '11', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(248, 11, 16, 0, 1, 1, 0, '14', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(249, 11, 16, 0, 1, 1, 0, '19', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(250, 11, 16, 0, 1, 1, 0, '22', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(251, 11, 16, 0, 1, 1, 0, '27', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(252, 11, 16, 0, 1, 1, 0, '30', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(253, 11, 16, 0, 1, 1, 0, '12', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(254, 11, 16, 0, 1, 1, 0, '13', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(255, 11, 16, 0, 1, 1, 0, '20', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(256, 11, 16, 0, 1, 1, 0, '21', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(257, 11, 16, 0, 1, 1, 0, '28', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(258, 11, 16, 0, 1, 1, 0, '29', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(259, 11, 17, 0, 1, 1, 0, '9', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(260, 11, 17, 0, 1, 1, 0, '16', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(261, 11, 17, 0, 1, 1, 0, '17', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(262, 11, 17, 0, 1, 1, 0, '24', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(263, 11, 17, 0, 1, 1, 0, '25', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(264, 11, 17, 0, 1, 1, 0, '32', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(265, 11, 17, 0, 1, 1, 0, '10', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(266, 11, 17, 0, 1, 1, 0, '15', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(267, 11, 17, 0, 1, 1, 0, '18', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(268, 11, 17, 0, 1, 1, 0, '23', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(269, 11, 17, 0, 1, 1, 0, '26', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(270, 11, 17, 0, 1, 1, 0, '31', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(271, 11, 17, 0, 1, 1, 0, '11', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(272, 11, 17, 0, 1, 1, 0, '14', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(273, 11, 17, 0, 1, 1, 0, '19', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(274, 11, 17, 0, 1, 1, 0, '22', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(275, 11, 17, 0, 1, 1, 0, '27', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(276, 11, 17, 0, 1, 1, 0, '30', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(277, 11, 17, 0, 1, 1, 0, '12', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(278, 11, 17, 0, 1, 1, 0, '13', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(279, 11, 17, 0, 1, 1, 0, '20', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(280, 11, 17, 0, 1, 1, 0, '21', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(281, 11, 17, 0, 1, 1, 0, '28', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(282, 11, 17, 0, 1, 1, 0, '29', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(283, 11, 18, 0, 1, 1, 0, '9', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(284, 11, 18, 0, 1, 1, 0, '16', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(285, 11, 18, 0, 1, 1, 0, '17', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(286, 11, 18, 0, 1, 1, 0, '24', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(287, 11, 18, 0, 1, 1, 0, '25', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(288, 11, 18, 0, 1, 1, 0, '32', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(289, 11, 18, 0, 1, 1, 0, '10', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(290, 11, 18, 0, 1, 1, 0, '15', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(291, 11, 18, 0, 1, 1, 0, '18', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(292, 11, 18, 0, 1, 1, 0, '23', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(293, 11, 18, 0, 1, 1, 0, '26', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(294, 11, 18, 0, 1, 1, 0, '31', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(295, 11, 18, 0, 1, 1, 0, '11', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(296, 11, 18, 0, 1, 1, 0, '14', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(297, 11, 18, 0, 1, 1, 0, '19', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(298, 11, 18, 0, 1, 1, 0, '22', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(299, 11, 18, 0, 1, 1, 0, '27', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(300, 11, 18, 0, 1, 1, 0, '30', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(301, 11, 18, 0, 1, 1, 0, '12', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(302, 11, 18, 0, 1, 1, 0, '13', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(303, 11, 18, 0, 1, 1, 0, '20', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(304, 11, 18, 0, 1, 1, 0, '21', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(305, 11, 18, 0, 1, 1, 0, '28', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(306, 11, 18, 0, 1, 1, 0, '29', '0', 0.00, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(307, 13, 19, 0, 1, 1, 0, '9', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(308, 13, 19, 0, 1, 1, 0, '16', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(309, 13, 19, 0, 1, 1, 0, '17', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(310, 13, 19, 0, 1, 1, 0, '24', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(311, 13, 19, 0, 1, 1, 0, '25', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(312, 13, 19, 0, 1, 1, 0, '32', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(313, 13, 19, 0, 1, 1, 0, '10', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(314, 13, 19, 0, 1, 1, 0, '15', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(315, 13, 19, 0, 1, 1, 0, '18', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(316, 13, 19, 0, 1, 1, 0, '23', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(317, 13, 19, 0, 1, 1, 0, '26', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(318, 13, 19, 0, 1, 1, 0, '31', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(319, 13, 19, 0, 1, 1, 0, '11', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(320, 13, 19, 0, 1, 1, 0, '14', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(321, 13, 19, 0, 1, 1, 0, '19', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(322, 13, 19, 0, 1, 1, 0, '22', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(323, 13, 19, 0, 1, 1, 0, '27', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(324, 13, 19, 0, 1, 1, 0, '30', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(325, 13, 19, 0, 1, 1, 0, '12', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(326, 13, 19, 0, 1, 1, 0, '13', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(327, 13, 19, 0, 1, 1, 0, '20', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(328, 13, 19, 0, 1, 1, 0, '21', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(329, 13, 19, 0, 1, 1, 0, '28', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(330, 13, 19, 0, 1, 1, 0, '29', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(331, 13, 20, 0, 1, 1, 0, '9', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(332, 13, 20, 0, 1, 1, 0, '16', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(333, 13, 20, 0, 1, 1, 0, '17', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(334, 13, 20, 0, 1, 1, 0, '24', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(335, 13, 20, 0, 1, 1, 0, '25', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(336, 13, 20, 0, 1, 1, 0, '32', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(337, 13, 20, 0, 1, 1, 0, '10', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(338, 13, 20, 0, 1, 1, 0, '15', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(339, 13, 20, 0, 1, 1, 0, '18', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(340, 13, 20, 0, 1, 1, 0, '23', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(341, 13, 20, 0, 1, 1, 0, '26', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(342, 13, 20, 0, 1, 1, 0, '31', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(343, 13, 20, 0, 1, 1, 0, '11', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(344, 13, 20, 0, 1, 1, 0, '14', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(345, 13, 20, 0, 1, 1, 0, '19', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(346, 13, 20, 0, 1, 1, 0, '22', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(347, 13, 20, 0, 1, 1, 0, '27', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(348, 13, 20, 0, 1, 1, 0, '30', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(349, 13, 20, 0, 1, 1, 0, '12', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(350, 13, 20, 0, 1, 1, 0, '13', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(351, 13, 20, 0, 1, 1, 0, '20', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(352, 13, 20, 0, 1, 1, 0, '21', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(353, 13, 20, 0, 1, 1, 0, '28', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(354, 13, 20, 0, 1, 1, 0, '29', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(355, 13, 21, 0, 1, 1, 0, '9', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(356, 13, 21, 0, 1, 1, 0, '16', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(357, 13, 21, 0, 1, 1, 0, '17', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(358, 13, 21, 0, 1, 1, 0, '24', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(359, 13, 21, 0, 1, 1, 0, '25', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(360, 13, 21, 0, 1, 1, 0, '32', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(361, 13, 21, 0, 1, 1, 0, '10', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(362, 13, 21, 0, 1, 1, 0, '15', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(363, 13, 21, 0, 1, 1, 0, '18', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(364, 13, 21, 0, 1, 1, 0, '23', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(365, 13, 21, 0, 1, 1, 0, '26', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(366, 13, 21, 0, 1, 1, 0, '31', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(367, 13, 21, 0, 1, 1, 0, '11', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(368, 13, 21, 0, 1, 1, 0, '14', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(369, 13, 21, 0, 1, 1, 0, '19', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(370, 13, 21, 0, 1, 1, 0, '22', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(371, 13, 21, 0, 1, 1, 0, '27', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(372, 13, 21, 0, 1, 1, 0, '30', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(373, 13, 21, 0, 1, 1, 0, '12', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(374, 13, 21, 0, 1, 1, 0, '13', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(375, 13, 21, 0, 1, 1, 0, '20', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(376, 13, 21, 0, 1, 1, 0, '21', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(377, 13, 21, 0, 1, 1, 0, '28', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(378, 13, 21, 0, 1, 1, 0, '29', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(379, 13, 22, 0, 1, 1, 0, '9', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(380, 13, 22, 0, 1, 1, 0, '16', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(381, 13, 22, 0, 1, 1, 0, '17', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(382, 13, 22, 0, 1, 1, 0, '24', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(383, 13, 22, 0, 1, 1, 0, '25', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(384, 13, 22, 0, 1, 1, 0, '32', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(385, 13, 22, 0, 1, 1, 0, '10', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(386, 13, 22, 0, 1, 1, 0, '15', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(387, 13, 22, 0, 1, 1, 0, '18', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(388, 13, 22, 0, 1, 1, 0, '23', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(389, 13, 22, 0, 1, 1, 0, '26', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(390, 13, 22, 0, 1, 1, 0, '31', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(391, 13, 22, 0, 1, 1, 0, '11', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(392, 13, 22, 0, 1, 1, 0, '14', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(393, 13, 22, 0, 1, 1, 0, '19', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(394, 13, 22, 0, 1, 1, 0, '22', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(395, 13, 22, 0, 1, 1, 0, '27', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(396, 13, 22, 0, 1, 1, 0, '30', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(397, 13, 22, 0, 1, 1, 0, '12', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(398, 13, 22, 0, 1, 1, 0, '13', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(399, 13, 22, 0, 1, 1, 0, '20', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(400, 13, 22, 0, 1, 1, 0, '21', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(401, 13, 22, 0, 1, 1, 0, '28', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(402, 13, 22, 0, 1, 1, 0, '29', '0', 0.00, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(403, 14, 23, 0, 1, 1, 0, '2', '0', 0.00, '2021-06-02 05:57:06', '2021-06-02 05:57:06', 'Admin', 0),
(404, 14, 23, 0, 1, 1, 0, '3', '0', 0.00, '2021-06-02 05:57:06', '2021-06-02 05:57:06', 'Admin', 0),
(405, 14, 23, 0, 1, 1, 0, '4', '0', 0.00, '2021-06-02 05:57:06', '2021-06-02 05:57:06', 'Admin', 0),
(406, 14, 24, 0, 1, 1, 0, '2', '0', 0.00, '2021-06-02 05:57:06', '2021-06-02 05:57:06', 'Admin', 0),
(407, 14, 24, 0, 1, 1, 0, '3', '0', 0.00, '2021-06-02 05:57:06', '2021-06-02 05:57:06', 'Admin', 0),
(408, 14, 24, 0, 1, 1, 0, '4', '0', 0.00, '2021-06-02 05:57:06', '2021-06-02 05:57:06', 'Admin', 0);

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

INSERT INTO `bus_stoppage_timing` (`id`, `bus_id`, `location_id`, `stoppage_name`, `stoppage_time`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(1, 1, 1291, 'Rasulgarh', '20:00:00', '2021-05-27 13:39:14', '2021-05-27 13:39:14', 'Admin', 0),
(2, 1, 1291, 'Palasuni', '20:20:00', '2021-05-27 13:39:14', '2021-05-27 13:39:14', 'Admin', 0),
(3, 1, 1313, 'Angul ByPass', '03:00:00', '2021-05-27 13:39:14', '2021-05-27 13:39:14', 'Admin', 0),
(4, 1, 1294, 'Sambalpur Town', '08:00:00', '2021-05-27 13:39:14', '2021-05-27 13:39:14', 'Admin', 0),
(5, 1, 1345, 'Puri Bus Stand', '18:00:00', '2021-05-27 13:39:14', '2021-05-27 13:39:14', 'Admin', 0),
(6, 1, 1345, 'Check Gate', '18:30:00', '2021-05-27 13:39:14', '2021-05-27 13:39:14', 'Admin', 0),
(7, 1, 1292, 'Badambadi', '21:20:00', '2021-05-27 13:39:14', '2021-05-27 13:39:14', 'Admin', 0),
(8, 2, 1291, 'Rasulgarh', '20:00:00', '2021-05-27 13:40:25', '2021-05-27 13:40:25', 'Admin', 0),
(9, 2, 1291, 'Palasuni', '20:20:00', '2021-05-27 13:40:25', '2021-05-27 13:40:25', 'Admin', 0),
(10, 2, 1313, 'Angul ByPass', '03:00:00', '2021-05-27 13:40:25', '2021-05-27 13:40:25', 'Admin', 0),
(11, 2, 1294, 'Sambalpur Town', '08:00:00', '2021-05-27 13:40:25', '2021-05-27 13:40:25', 'Admin', 0),
(12, 2, 1345, 'Puri Bus Stand', '18:00:00', '2021-05-27 13:40:25', '2021-05-27 13:40:25', 'Admin', 0),
(13, 2, 1345, 'Check Gate', '18:30:00', '2021-05-27 13:40:25', '2021-05-27 13:40:25', 'Admin', 0),
(14, 2, 1292, 'Badambadi', '21:20:00', '2021-05-27 13:40:25', '2021-05-27 13:40:25', 'Admin', 0),
(15, 3, 1291, 'Rasulgarh', '20:00:00', '2021-05-27 14:22:53', '2021-05-27 14:22:53', 'Admin', 0),
(16, 3, 1291, 'Palasuni', '20:20:00', '2021-05-27 14:22:53', '2021-05-27 14:22:53', 'Admin', 0),
(17, 3, 1313, 'Angul ByPass', '03:00:00', '2021-05-27 14:22:53', '2021-05-27 14:22:53', 'Admin', 0),
(18, 3, 1294, 'Sambalpur Town', '08:00:00', '2021-05-27 14:22:53', '2021-05-27 14:22:53', 'Admin', 0),
(19, 3, 1345, 'Puri Bus Stand', '18:00:00', '2021-05-27 14:22:53', '2021-05-27 14:22:53', 'Admin', 0),
(20, 3, 1345, 'Check Gate', '18:30:00', '2021-05-27 14:22:53', '2021-05-27 14:22:53', 'Admin', 0),
(21, 3, 1292, 'Badambadi', '21:20:00', '2021-05-27 14:22:53', '2021-05-27 14:22:53', 'Admin', 0),
(22, 4, 1291, 'Rasulgarh', '20:00:00', '2021-05-27 14:23:20', '2021-05-27 14:23:20', 'Admin', 0),
(23, 4, 1291, 'Palasuni', '20:20:00', '2021-05-27 14:23:21', '2021-05-27 14:23:21', 'Admin', 0),
(24, 4, 1313, 'Angul ByPass', '03:00:00', '2021-05-27 14:23:21', '2021-05-27 14:23:21', 'Admin', 0),
(25, 4, 1294, 'Sambalpur Town', '08:00:00', '2021-05-27 14:23:21', '2021-05-27 14:23:21', 'Admin', 0),
(26, 4, 1345, 'Puri Bus Stand', '18:00:00', '2021-05-27 14:23:21', '2021-05-27 14:23:21', 'Admin', 0),
(27, 4, 1345, 'Check Gate', '18:30:00', '2021-05-27 14:23:21', '2021-05-27 14:23:21', 'Admin', 0),
(28, 4, 1292, 'Badambadi', '21:20:00', '2021-05-27 14:23:21', '2021-05-27 14:23:21', 'Admin', 0),
(29, 5, 1291, 'Rasulgarh', '20:00:00', '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(30, 5, 1291, 'Palasuni', '20:20:00', '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(31, 5, 1313, 'Angul ByPass', '03:00:00', '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(32, 5, 1294, 'Sambalpur Town', '08:00:00', '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(33, 5, 1345, 'Puri Bus Stand', '18:00:00', '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(34, 5, 1345, 'Check Gate', '18:30:00', '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(35, 5, 1292, 'Badambadi', '21:20:00', '2021-05-27 14:24:13', '2021-05-27 14:24:13', 'Admin', 0),
(36, 7, 1291, 'Barmunda', '20:00:00', '2021-05-28 14:32:35', '2021-05-28 14:32:35', 'Admin', 0),
(37, 7, 1291, 'Palasuni', '20:40:00', '2021-05-28 14:32:35', '2021-05-28 14:32:35', 'Admin', 0),
(38, 7, 1292, 'OMP', '22:00:00', '2021-05-28 14:32:35', '2021-05-28 14:32:35', 'Admin', 0),
(39, 7, 1294, 'Sambalpur Town', '06:00:00', '2021-05-28 14:32:35', '2021-05-28 14:32:35', 'Admin', 0),
(40, 8, 1291, 'Rasulgarh', '20:00:00', '2021-05-31 05:17:40', '2021-05-31 05:17:40', 'Admin', 0),
(41, 8, 1291, 'Palasuni', '20:20:00', '2021-05-31 05:17:40', '2021-05-31 05:17:40', 'Admin', 0),
(42, 8, 1313, 'Angul ByPass', '03:00:00', '2021-05-31 05:17:40', '2021-05-31 05:17:40', 'Admin', 0),
(43, 8, 1294, 'Sambalpur Town', '08:00:00', '2021-05-31 05:17:40', '2021-05-31 05:17:40', 'Admin', 0),
(44, 8, 1345, 'Puri Bus Stand', '18:00:00', '2021-05-31 05:17:40', '2021-05-31 05:17:40', 'Admin', 0),
(45, 8, 1345, 'Check Gate', '18:30:00', '2021-05-31 05:17:40', '2021-05-31 05:17:40', 'Admin', 0),
(46, 8, 1292, 'Badambadi', '21:20:00', '2021-05-31 05:17:40', '2021-05-31 05:17:40', 'Admin', 0),
(47, 9, 1291, 'Rasulgarh', '20:00:00', '2021-05-31 05:19:37', '2021-05-31 05:19:37', 'Admin', 0),
(48, 9, 1291, 'Palasuni', '20:20:00', '2021-05-31 05:19:37', '2021-05-31 05:19:37', 'Admin', 0),
(49, 9, 1313, 'Angul ByPass', '03:00:00', '2021-05-31 05:19:37', '2021-05-31 05:19:37', 'Admin', 0),
(50, 9, 1294, 'Sambalpur Town', '08:00:00', '2021-05-31 05:19:37', '2021-05-31 05:19:37', 'Admin', 0),
(51, 9, 1345, 'Puri Bus Stand', '18:00:00', '2021-05-31 05:19:37', '2021-05-31 05:19:37', 'Admin', 0),
(52, 9, 1345, 'Check Gate', '18:30:00', '2021-05-31 05:19:37', '2021-05-31 05:19:37', 'Admin', 0),
(53, 9, 1292, 'Badambadi', '21:20:00', '2021-05-31 05:19:37', '2021-05-31 05:19:37', 'Admin', 0),
(54, 10, 1291, 'Rasulgarh', '20:00:00', '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(55, 10, 1291, 'Palasuni', '20:20:00', '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(56, 10, 1313, 'Angul ByPass', '03:00:00', '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(57, 10, 1294, 'Sambalpur Town', '08:00:00', '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(58, 10, 1345, 'Puri Bus Stand', '18:00:00', '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(59, 10, 1345, 'Check Gate', '18:30:00', '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(60, 10, 1292, 'Badambadi', '21:20:00', '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(61, 11, 1291, 'Rasulgarh', '20:00:00', '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(62, 11, 1291, 'Palasuni', '20:20:00', '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(63, 11, 1313, 'Angul ByPass', '03:00:00', '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(64, 11, 1294, 'Sambalpur Town', '08:00:00', '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(65, 11, 1345, 'Puri Bus Stand', '18:00:00', '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(66, 11, 1345, 'Check Gate', '18:30:00', '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(67, 11, 1292, 'Badambadi', '21:20:00', '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(68, 13, 1291, 'Rasulgarh', '20:00:00', '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(69, 13, 1291, 'Palasuni', '20:20:00', '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(70, 13, 1313, 'Angul ByPass', '03:00:00', '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(71, 13, 1294, 'Sambalpur Town', '08:00:00', '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(72, 13, 1345, 'Puri Bus Stand', '18:00:00', '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(73, 13, 1345, 'Check Gate', '18:30:00', '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(74, 13, 1292, 'Badambadi', '21:20:00', '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(75, 14, 1291, 'Barmunda', '20:00:00', '2021-06-02 05:57:06', '2021-06-02 05:57:06', 'Admin', 0),
(76, 14, 1292, 'OMP', '22:00:00', '2021-06-02 05:57:06', '2021-06-02 05:57:06', 'Admin', 0),
(77, 14, 1294, 'Sambalpur Town', '07:00:00', '2021-06-02 05:57:06', '2021-06-02 05:57:06', 'Admin', 0);

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
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `safety`
--

INSERT INTO `safety` (`id`, `name`, `created_at`, `updated_at`, `created_by`, `status`) VALUES
(3, 'Fire Safety', '2021-06-03 13:40:24', '2021-06-03 13:40:24', 'admin', 0),
(4, 'Foot Safety', '2021-06-03 14:05:56', '2021-06-03 14:07:07', 'Admin', 2),
(5, 'Road Safety', '2021-06-03 14:34:21', '2021-06-03 14:37:46', 'Admin', 1);

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
(1, 1, 1, 1, 1345, 1294, 550.00, 650.00, NULL, NULL, 1, 2, '2021-05-31 13:39:14', '2021-05-31 13:39:14', 'Admin', 0),
(2, 1, 1, 2, 1345, 1294, 550.00, 650.00, NULL, NULL, 1, 2, '2021-05-31 13:40:25', '2021-05-31 13:40:25', 'Admin', 0),
(3, 1, 1, 3, 1345, 1294, 550.00, 650.00, NULL, NULL, 1, 2, '2021-05-31 14:22:53', '2021-05-31 14:22:53', 'Admin', 0),
(4, 1, 1, 4, 1345, 1294, 550.00, 650.00, NULL, NULL, 1, 2, '2021-05-31 14:23:21', '2021-05-31 14:23:21', 'Admin', 0),
(5, 1, 1, 5, 1345, 1294, 550.00, 650.00, NULL, NULL, 1, 2, '2021-05-31 14:24:13', '2021-05-31 14:24:13', 'Admin', 0),
(6, 1, 1, 5, 1291, 1294, 450.00, 550.00, NULL, NULL, 1, 2, '2021-05-31 14:24:13', '2021-05-31 14:24:13', 'Admin', 0),
(7, 1, 1, 5, 1292, 1294, 420.00, 520.00, NULL, NULL, 1, 2, '2021-05-31 14:24:13', '2021-05-31 14:24:13', 'Admin', 0),
(8, 1, 1, 5, 1345, 1313, 440.00, 540.00, NULL, NULL, 1, 2, '2021-05-31 14:24:13', '2021-05-31 14:24:13', 'Admin', 0),
(9, 1, 1, 7, 1291, 1294, 455.00, 555.00, NULL, NULL, 1, 2, '2021-05-31 14:32:35', '2021-05-31 14:32:35', 'Admin', 0),
(10, 1, 1, 7, 1292, 1294, 435.00, 535.00, NULL, NULL, 1, 2, '2021-05-31 14:32:35', '2021-05-31 14:32:35', 'Admin', 0),
(11, 1, 1, 10, 1345, 1294, 550.00, 650.00, NULL, NULL, 1, 2, '2021-05-31 05:20:21', '2021-05-31 05:20:21', 'Admin', 0),
(12, 1, 1, 10, 1291, 1294, 450.00, 550.00, NULL, NULL, 1, 2, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(13, 1, 1, 10, 1292, 1294, 420.00, 520.00, NULL, NULL, 1, 2, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(14, 1, 1, 10, 1345, 1313, 440.00, 540.00, NULL, NULL, 1, 2, '2021-05-31 05:20:22', '2021-05-31 05:20:22', 'Admin', 0),
(15, 1, 1, 11, 1345, 1294, 550.00, 650.00, '08:00:00', '18:00:00', 1, 2, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(16, 1, 1, 11, 1291, 1294, 450.00, 550.00, '08:00:00', '20:00:00', 1, 2, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(17, 1, 1, 11, 1292, 1294, 420.00, 520.00, '08:00:00', '21:20:00', 1, 2, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(18, 1, 1, 11, 1345, 1313, 440.00, 540.00, '03:00:00', '18:00:00', 1, 2, '2021-05-31 05:20:54', '2021-05-31 05:20:54', 'Admin', 0),
(19, 1, 1, 13, 1345, 1294, 550.00, 650.00, '08:00:00', '18:00:00', 1, 2, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(20, 1, 1, 13, 1291, 1294, 450.00, 550.00, '08:00:00', '20:00:00', 1, 2, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(21, 1, 1, 13, 1292, 1294, 420.00, 520.00, '08:00:00', '21:20:00', 1, 2, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(22, 1, 1, 13, 1345, 1313, 440.00, 540.00, '03:00:00', '18:00:00', 1, 2, '2021-05-31 13:27:23', '2021-05-31 13:27:23', 'Admin', 0),
(23, 1, 1, 14, 1291, 1294, 450.00, 550.00, '07:00:00', '20:00:00', 1, 2, '2021-06-02 05:57:06', '2021-06-02 05:57:06', 'Admin', 0),
(24, 1, 1, 14, 1292, 1294, 430.00, 530.00, '07:00:00', '22:00:00', 1, 2, '2021-06-02 05:57:06', '2021-06-02 05:57:06', 'Admin', 0);

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
  ADD KEY `location_timing_fk` (`location_id`);

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `bus_amenities`
--
ALTER TABLE `bus_amenities`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=409;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
