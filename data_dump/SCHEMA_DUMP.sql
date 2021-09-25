-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 25, 2021 at 06:15 AM
-- Server version: 10.2.39-MariaDB
-- PHP Version: 7.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `neoflix_odbusbackend`
--

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon` blob DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `reason` varchar(250) DEFAULT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `appdownload`
--

CREATE TABLE `appdownload` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mobileno` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `appversion`
--

CREATE TABLE `appversion` (
  `id` int(11) NOT NULL,
  `info` varchar(250) DEFAULT NULL,
  `name` varchar(120) NOT NULL,
  `mandatory` int(11) NOT NULL DEFAULT 1 COMMENT '0-not mandatory 1- manadatory',
  `version` int(11) NOT NULL,
  `new_version_names` mediumtext DEFAULT NULL,
  `new_version_codes` mediumtext DEFAULT NULL,
  `allowed_days` int(11) DEFAULT NULL,
  `has_issues` varchar(1) NOT NULL DEFAULT 'N',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `boarding_droping`
--

CREATE TABLE `boarding_droping` (
  `id` int(11) NOT NULL,
  `location_id` int(10) UNSIGNED NOT NULL,
  `boarding_point` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(10) UNSIGNED NOT NULL,
  `transaction_id` varchar(200) NOT NULL,
  `pnr` varchar(100) NOT NULL,
  `users_id` int(11) NOT NULL COMMENT 'Users ID',
  `bus_id` int(10) UNSIGNED NOT NULL,
  `source_id` int(10) UNSIGNED NOT NULL,
  `destination_id` int(10) UNSIGNED NOT NULL,
  `j_day` int(11) NOT NULL DEFAULT 1 COMMENT 'journey day | 1-same day 2-nxt day so on',
  `journey_dt` date NOT NULL,
  `boarding_point` varchar(120) NOT NULL,
  `dropping_point` varchar(120) NOT NULL,
  `boarding_time` time NOT NULL,
  `dropping_time` time NOT NULL,
  `origin` enum('ODBUS','RPBOA','GRANDBUS','JANARDANBUS','KHAMBESWARI','MOBUS') DEFAULT NULL,
  `app_type` set('WEB','MOB','ANDROID','CLNTWEB','CLNTMOB','ASSNWEB','ASSNMOB','CONDUCTOR','AGENT','MANAGER','OPERATOR') NOT NULL,
  `typ_id` varchar(50) NOT NULL COMMENT 'Type of Users booking Ticket',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=Not Booked, 1= Booked(based on successful payment), 2=booking cancelled',
  `total_fare` double(8,2) DEFAULT 0.00,
  `owner_fare` double(8,2) DEFAULT 0.00,
  `odbus_gst_charges` double(8,2) DEFAULT 0.00,
  `odbus_gst_amount` double(8,2) DEFAULT 0.00,
  `owner_gst_charges` double(8,2) DEFAULT 0.00,
  `owner_gst_amount` double(8,2) DEFAULT 0.00,
  `odbus_charges` double(8,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `booking_detail`
--

CREATE TABLE `booking_detail` (
  `id` int(11) NOT NULL,
  `booking_id` int(10) UNSIGNED NOT NULL,
  `bus_seats_id` int(10) UNSIGNED NOT NULL,
  `passenger_name` varchar(250) NOT NULL,
  `passenger_gender` varchar(120) NOT NULL,
  `passenger_age` varchar(80) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=Not Booked,1= Booked(based on successful payment), 2=booking cancelled'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `booking_seized`
--

CREATE TABLE `booking_seized` (
  `id` int(11) NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `location_id` int(10) UNSIGNED NOT NULL,
  `seize_booking_minute` int(11) NOT NULL COMMENT 'value in minute',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `booking_sequence`
--

CREATE TABLE `booking_sequence` (
  `id` int(11) NOT NULL,
  `booking_id` int(10) UNSIGNED NOT NULL,
  `sequence_start_no` int(11) NOT NULL,
  `sequence_end_no` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus`
--

CREATE TABLE `bus` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `bus_operator_id` int(11) NOT NULL DEFAULT 1,
  `name` varchar(200) NOT NULL,
  `via` varchar(200) NOT NULL,
  `bus_number` varchar(50) NOT NULL,
  `bus_description` varchar(250) DEFAULT NULL,
  `bus_type_id` int(10) UNSIGNED NOT NULL,
  `bus_sitting_id` int(10) UNSIGNED NOT NULL,
  `bus_seat_layout_id` int(10) UNSIGNED NOT NULL,
  `cancellationslabs_id` int(11) NOT NULL,
  `running_cycle` int(10) UNSIGNED NOT NULL,
  `popularity` int(10) UNSIGNED DEFAULT NULL COMMENT 'Higher the number higher will be posotioning in buslist',
  `admin_notes` mediumtext DEFAULT NULL,
  `has_return_bus` int(11) NOT NULL COMMENT '0-no 1-yes',
  `return_bus_id` int(11) DEFAULT NULL,
  `cancelation_points` mediumtext DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `sequence` int(11) NOT NULL DEFAULT 1000,
  `max_seat_book` int(11) NOT NULL DEFAULT 6
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_amenities`
--

CREATE TABLE `bus_amenities` (
  `id` int(10) UNSIGNED NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `amenities_id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_cancelled`
--

CREATE TABLE `bus_cancelled` (
  `id` int(10) UNSIGNED NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `bus_operator_id` int(11) NOT NULL,
  `month` varchar(50) DEFAULT NULL,
  `year` varchar(50) DEFAULT NULL,
  `reason` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `cancelled_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_cancelled_date`
--

CREATE TABLE `bus_cancelled_date` (
  `id` int(10) UNSIGNED NOT NULL,
  `bus_cancelled_id` int(10) UNSIGNED NOT NULL,
  `cancelled_date` date NOT NULL,
  `created_by` varchar(200) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_class`
--

CREATE TABLE `bus_class` (
  `id` int(11) NOT NULL,
  `class_name` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_closing_hours`
--

CREATE TABLE `bus_closing_hours` (
  `id` int(11) NOT NULL,
  `bus_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `dep_time` varchar(250) NOT NULL,
  `closing_hours` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_contacts`
--

CREATE TABLE `bus_contacts` (
  `id` int(11) NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL COMMENT '0-operator 1-manager 2-conductor',
  `phone` varchar(100) NOT NULL,
  `booking_sms_send` int(11) NOT NULL DEFAULT 0 COMMENT '0-dontsend 1-send',
  `cancel_sms_send` int(11) NOT NULL DEFAULT 0 COMMENT '0-dontsend 1-send',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_extra_fare`
--

CREATE TABLE `bus_extra_fare` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `type` int(10) UNSIGNED NOT NULL COMMENT '1 - Operator, 2 - ODBUS',
  `journey_date` date DEFAULT NULL,
  `seat_fare` int(11) NOT NULL COMMENT 'extra 30rs.. added to all seaters',
  `sleeper_fare` int(11) NOT NULL COMMENT 'extra 70rs.. added to all sleapers',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_festival_fare`
--

CREATE TABLE `bus_festival_fare` (
  `id` int(11) NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `festival_fare_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_gallery`
--

CREATE TABLE `bus_gallery` (
  `id` int(10) UNSIGNED NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `image` mediumblob NOT NULL,
  `alt_tag` varchar(250) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_location_sequence`
--

CREATE TABLE `bus_location_sequence` (
  `id` int(11) NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `location_id` int(10) UNSIGNED NOT NULL,
  `sequence` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_operator`
--

CREATE TABLE `bus_operator` (
  `id` int(11) NOT NULL,
  `email_id` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `operator_name` varchar(50) NOT NULL,
  `operator_info` text DEFAULT NULL,
  `contact_number` varchar(15) NOT NULL,
  `organisation_name` varchar(50) NOT NULL,
  `location_name` varchar(150) NOT NULL,
  `address` text DEFAULT NULL,
  `additional_email` varchar(50) DEFAULT NULL,
  `additional_contact` varchar(15) DEFAULT NULL,
  `bank_account_name` varchar(50) DEFAULT NULL,
  `bank_name` varchar(50) DEFAULT NULL,
  `bank_ifsc` varchar(50) DEFAULT NULL,
  `bank_account_number` varchar(50) DEFAULT NULL,
  `need_gst_bill` int(11) NOT NULL DEFAULT 0,
  `gst_number` varchar(250) DEFAULT NULL,
  `gst_amount` double(8,2) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Bus Operators';

-- --------------------------------------------------------

--
-- Table structure for table `bus_owner_fare`
--

CREATE TABLE `bus_owner_fare` (
  `id` int(11) NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `owner_fare_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_safety`
--

CREATE TABLE `bus_safety` (
  `id` int(11) NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `safety_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_schedule`
--

CREATE TABLE `bus_schedule` (
  `id` int(10) UNSIGNED NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL DEFAULT 'Admin',
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_schedule_date`
--

CREATE TABLE `bus_schedule_date` (
  `id` int(10) UNSIGNED NOT NULL,
  `bus_schedule_id` int(10) UNSIGNED NOT NULL,
  `entry_date` date NOT NULL,
  `created_by` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_seats`
--

CREATE TABLE `bus_seats` (
  `id` int(10) UNSIGNED NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `ticket_price_id` int(10) UNSIGNED NOT NULL,
  `seats_id` int(11) NOT NULL,
  `category` int(10) UNSIGNED NOT NULL COMMENT '0-odbus 1-conductor',
  `duration` varchar(10) NOT NULL DEFAULT '0' COMMENT 'if grater than 0 its additional seats/ sleepers in minutes THE  gap after which full seats will be given to odbus',
  `new_fare` double(8,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_seats_extra`
--

CREATE TABLE `bus_seats_extra` (
  `id` int(10) UNSIGNED NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `journey_dt` date NOT NULL,
  `type` int(10) UNSIGNED NOT NULL COMMENT '1 - Block, 2 - Open',
  `seat_type` int(10) UNSIGNED NOT NULL COMMENT '0-seater 1-sleeper',
  `seat_number` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_seat_layout`
--

CREATE TABLE `bus_seat_layout` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(254) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_sitting`
--

CREATE TABLE `bus_sitting` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(254) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_slots`
--

CREATE TABLE `bus_slots` (
  `id` int(10) UNSIGNED NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(254) NOT NULL,
  `type` int(11) NOT NULL DEFAULT 0 COMMENT '0- ODBUS    1- conductor ',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_special_fare`
--

CREATE TABLE `bus_special_fare` (
  `id` int(11) NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `special_fare_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_stoppage_additional_fare`
--

CREATE TABLE `bus_stoppage_additional_fare` (
  `id` int(10) UNSIGNED NOT NULL,
  `ticket_price_id` int(10) UNSIGNED NOT NULL,
  `bus_seats_id` int(10) UNSIGNED NOT NULL,
  `additional_fare` double(8,2) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_stoppage_timing`
--

CREATE TABLE `bus_stoppage_timing` (
  `id` int(10) UNSIGNED NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `location_id` int(10) UNSIGNED NOT NULL,
  `boarding_droping_id` int(11) NOT NULL,
  `stoppage_name` varchar(250) DEFAULT NULL,
  `stoppage_time` time NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `created_by` varchar(50) NOT NULL DEFAULT 'Admin',
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_type`
--

CREATE TABLE `bus_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `bus_class_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cancellationslabs`
--

CREATE TABLE `cancellationslabs` (
  `id` int(11) NOT NULL,
  `api_id` int(11) DEFAULT NULL,
  `rule_name` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cancellationslabs_info`
--

CREATE TABLE `cancellationslabs_info` (
  `id` int(11) NOT NULL,
  `cancellation_slab_id` int(11) NOT NULL,
  `duration` varchar(250) NOT NULL,
  `deduction` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `city_closing`
--

CREATE TABLE `city_closing` (
  `id` int(10) UNSIGNED NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `location_id` int(10) UNSIGNED NOT NULL,
  `closing_hours` int(10) UNSIGNED DEFAULT NULL COMMENT 'Time in minutes',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) NOT NULL DEFAULT 'Admin',
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `city_closing_extended`
--

CREATE TABLE `city_closing_extended` (
  `id` int(11) NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `location_id` int(10) UNSIGNED DEFAULT NULL,
  `journey_date` date NOT NULL,
  `closing_hours` int(10) UNSIGNED DEFAULT NULL COMMENT 'Time in minutes',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `id` int(10) UNSIGNED NOT NULL,
  `coupon_title` varchar(254) DEFAULT NULL,
  `coupon_code` varchar(25) DEFAULT NULL,
  `type` enum('Percent','CutOff') NOT NULL,
  `amount` double(8,2) DEFAULT NULL COMMENT 'in % or in cash',
  `max_discount_price` double(8,2) DEFAULT NULL COMMENT 'incase of % deduction',
  `min_tran_amount` double(8,2) DEFAULT NULL,
  `max_redeem` int(11) DEFAULT NULL,
  `max_use_limit` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL COMMENT '0-booking date 1-journey date',
  `from_date` datetime DEFAULT NULL,
  `to_date` datetime DEFAULT NULL,
  `short_desc` varchar(200) NOT NULL,
  `full_desc` mediumtext NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_assigned_bus`
--

CREATE TABLE `coupon_assigned_bus` (
  `id` int(10) UNSIGNED NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `coupon_id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `credentials`
--

CREATE TABLE `credentials` (
  `id` int(10) UNSIGNED NOT NULL,
  `sms_textlocal_key` varchar(254) NOT NULL,
  `mail_username` varchar(254) NOT NULL,
  `mail_password` varchar(254) NOT NULL,
  `razorpay_key` varchar(256) NOT NULL,
  `razorpay_secret` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_payment`
--

CREATE TABLE `customer_payment` (
  `id` int(11) NOT NULL,
  `name` varchar(254) DEFAULT '',
  `booking_id` int(10) UNSIGNED NOT NULL,
  `amount` double(8,2) DEFAULT 0.00,
  `order_id` varchar(200) NOT NULL DEFAULT '',
  `razorpay_id` varchar(200) DEFAULT NULL,
  `razorpay_signature` varchar(200) DEFAULT NULL,
  `payment_done` int(11) NOT NULL DEFAULT 0 COMMENT '0:payment not done, 1:payment done, 2:refunded ',
  `refund_id` varchar(120) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_query`
--

CREATE TABLE `customer_query` (
  `id` int(11) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `phone` varchar(120) DEFAULT NULL,
  `query_typ` enum('RESERVATION','CONTACT') DEFAULT NULL,
  `data` mediumtext DEFAULT NULL COMMENT 'json_data',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_query_category`
--

CREATE TABLE `customer_query_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(254) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_query_category_issues`
--

CREATE TABLE `customer_query_category_issues` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_query_category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(254) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `custom_pages`
--

CREATE TABLE `custom_pages` (
  `id` int(11) NOT NULL,
  `origin` int(11) DEFAULT 0 COMMENT '0-odbus 1-rpboa 2-janardana ',
  `type` int(11) DEFAULT 0 COMMENT '0-custom pages  1-route pages 2-news',
  `source_id` varchar(120) NOT NULL COMMENT 'only for route pages',
  `destination_id` varchar(120) NOT NULL COMMENT 'only for route pages',
  `name` varchar(120) DEFAULT NULL,
  `url` varchar(120) DEFAULT NULL,
  `content` mediumtext DEFAULT NULL,
  `meta_title` varchar(120) DEFAULT NULL,
  `meta_keyword` varchar(600) DEFAULT NULL,
  `meta_descriptiom` varchar(500) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `extended_bus_closing_hours`
--

CREATE TABLE `extended_bus_closing_hours` (
  `id` int(11) NOT NULL,
  `bus_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `dep_time` varchar(250) NOT NULL,
  `closing_hours` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `festival_fare`
--

CREATE TABLE `festival_fare` (
  `id` int(11) NOT NULL,
  `bus_operator_id` int(11) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `seater_price` double NOT NULL,
  `sleeper_price` double NOT NULL,
  `reason` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gateway_information`
--

CREATE TABLE `gateway_information` (
  `id` int(11) NOT NULL,
  `sender` varchar(120) NOT NULL,
  `channel_type` int(11) DEFAULT NULL COMMENT 'channel | 0-sms 1-email',
  `service_provider` varchar(50) DEFAULT NULL,
  `contents` varchar(250) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `created_by` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(254) NOT NULL,
  `synonym` varchar(250) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `locationcode`
--

CREATE TABLE `locationcode` (
  `id` int(10) UNSIGNED NOT NULL,
  `location_id` int(10) UNSIGNED NOT NULL,
  `type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0-Odbus 1- red bus 2-dolphin 3-bus india',
  `providerid` varchar(254) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `odbus_charges`
--

CREATE TABLE `odbus_charges` (
  `id` int(11) NOT NULL,
  `payment_gateway_charges` double(8,2) NOT NULL COMMENT 'Value in %',
  `email_sms_charges` double(8,2) NOT NULL,
  `odbus_gst_charges` double(8,2) NOT NULL COMMENT 'Value in %',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `offer_category_id` int(11) NOT NULL,
  `offer_image` blob NOT NULL,
  `offer_text` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `offer_category`
--

CREATE TABLE `offer_category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `owner_fare`
--

CREATE TABLE `owner_fare` (
  `id` int(10) UNSIGNED NOT NULL,
  `bus_operator_id` int(11) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `date` text NOT NULL,
  `seater_price` double NOT NULL,
  `sleeper_price` double NOT NULL,
  `reason` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `owner_payment`
--

CREATE TABLE `owner_payment` (
  `id` int(11) NOT NULL,
  `bus_operator_id` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `transaction_id` varchar(250) NOT NULL,
  `remark` text NOT NULL,
  `created_by` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_booking`
--

CREATE TABLE `pre_booking` (
  `id` int(10) UNSIGNED NOT NULL,
  `transaction_id` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `j_day` int(11) NOT NULL DEFAULT 0 COMMENT 'journey day | 0-same day 1-nxt day',
  `journey_dt` date NOT NULL,
  `bus_info` mediumtext NOT NULL COMMENT 'json data',
  `customer_info` mediumtext DEFAULT NULL COMMENT 'json data',
  `total_fare` double(8,2) UNSIGNED NOT NULL,
  `is_coupon` int(11) NOT NULL DEFAULT 0 COMMENT '0-no 1-yes',
  `coupon_code` varchar(80) DEFAULT NULL,
  `coupon_discount` decimal(9,2) DEFAULT NULL,
  `discounted_fare` decimal(9,2) DEFAULT NULL,
  `customer_id` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_booking_detail`
--

CREATE TABLE `pre_booking_detail` (
  `id` int(11) NOT NULL,
  `pre_booking_id` int(10) UNSIGNED NOT NULL,
  `journey_date` date NOT NULL,
  `j_day` int(11) NOT NULL DEFAULT 0 COMMENT 'journey day | 0-same day 1-nxt day',
  `bus_id` varchar(120) NOT NULL,
  `seat_name` varchar(120) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reason`
--

CREATE TABLE `reason` (
  `id` int(11) NOT NULL,
  `name` varchar(254) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(10) UNSIGNED NOT NULL,
  `pnr` varchar(60) NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `users_id` int(11) NOT NULL,
  `title` varchar(120) NOT NULL,
  `reference_key` varchar(250) NOT NULL COMMENT 'link for email',
  `rating_overall` varchar(25) NOT NULL COMMENT 'out of 5',
  `rating_comfort` varchar(25) NOT NULL COMMENT 'out of 5',
  `rating_clean` varchar(25) NOT NULL COMMENT 'out of 5',
  `rating_behavior` varchar(25) NOT NULL COMMENT 'out of 5',
  `rating_timing` varchar(25) NOT NULL COMMENT 'out of 5',
  `comments` varchar(2500) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `safety`
--

CREATE TABLE `safety` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `icon` mediumblob DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` int(11) NOT NULL,
  `bus_seat_layout_id` int(10) UNSIGNED NOT NULL,
  `seat_class_id` int(11) NOT NULL,
  `berthType` enum('1','2') NOT NULL COMMENT '1=Lower Berth\r\n2=Upper Berth',
  `seatText` varchar(20) DEFAULT '',
  `rowNumber` int(11) NOT NULL,
  `colNumber` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` varchar(250) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `seat_block`
--

CREATE TABLE `seat_block` (
  `id` int(11) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `date_applied` datetime NOT NULL,
  `reason` varchar(500) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `seat_block_seats`
--

CREATE TABLE `seat_block_seats` (
  `id` int(11) NOT NULL,
  `seat_block_id` int(11) NOT NULL,
  `seats_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `seat_class`
--

CREATE TABLE `seat_class` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `seat_open`
--

CREATE TABLE `seat_open` (
  `id` int(11) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `date_applied` datetime NOT NULL,
  `reason` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `seat_open_seats`
--

CREATE TABLE `seat_open_seats` (
  `id` int(11) NOT NULL,
  `seat_open_id` int(11) NOT NULL,
  `seats_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `site_master`
--

CREATE TABLE `site_master` (
  `id` int(10) UNSIGNED NOT NULL,
  `site_live` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `live_at` datetime NOT NULL,
  `extra_price` double(8,2) UNSIGNED NOT NULL,
  `calender_days` int(10) UNSIGNED NOT NULL,
  `service_charge` int(10) UNSIGNED NOT NULL,
  `per_trasaction` double(8,2) UNSIGNED NOT NULL,
  `max_seat_booked` int(10) UNSIGNED NOT NULL,
  `support_email` varchar(200) NOT NULL,
  `booking_email` varchar(200) NOT NULL,
  `request_email` varchar(200) NOT NULL,
  `other_email` varchar(200) NOT NULL,
  `contact_no1` varchar(50) NOT NULL,
  `contact_no2` varchar(50) NOT NULL,
  `contact_no3` varchar(50) NOT NULL,
  `contact_no4` varchar(50) NOT NULL,
  `facebook_url` varchar(254) NOT NULL,
  `twitter_url` varchar(254) NOT NULL,
  `linkedin_url` varchar(254) NOT NULL,
  `instagram_url` varchar(254) NOT NULL,
  `googleplus_url` varchar(254) NOT NULL,
  `min_fare_amt` int(11) NOT NULL,
  `earned_pts` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int(10) UNSIGNED NOT NULL,
  `occassion` varchar(250) NOT NULL,
  `category` int(11) DEFAULT NULL COMMENT '0-main slider 1-adv-slider1 2-adv-slider 2, 3-adv-slider-3',
  `url` varchar(250) DEFAULT NULL,
  `slider_img` varchar(254) NOT NULL,
  `alt_tag` varchar(250) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `special_fare`
--

CREATE TABLE `special_fare` (
  `id` int(10) UNSIGNED NOT NULL,
  `bus_operator_id` int(11) DEFAULT NULL,
  `source_id` int(10) UNSIGNED DEFAULT NULL,
  `destination_id` int(10) UNSIGNED DEFAULT NULL,
  `date` text NOT NULL,
  `seater_price` double(8,2) NOT NULL,
  `sleeper_price` double(8,2) NOT NULL,
  `reason` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_cancelation`
--

CREATE TABLE `ticket_cancelation` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_cancelation_rule`
--

CREATE TABLE `ticket_cancelation_rule` (
  `id` int(10) UNSIGNED NOT NULL,
  `ticket_cancelation_id` int(10) UNSIGNED NOT NULL,
  `hour_lag_start` varchar(10) NOT NULL,
  `hour_lag_end` varchar(10) NOT NULL,
  `cancelation_percentage` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_fare_slab`
--

CREATE TABLE `ticket_fare_slab` (
  `id` int(11) NOT NULL,
  `starting_fare` double NOT NULL,
  `upto_fare` double NOT NULL,
  `odbus_commision` double NOT NULL COMMENT 'Value in %	',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_price`
--

CREATE TABLE `ticket_price` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `bus_operator_id` int(11) NOT NULL,
  `bus_id` int(10) UNSIGNED NOT NULL,
  `source_id` int(10) UNSIGNED NOT NULL,
  `destination_id` int(10) UNSIGNED NOT NULL,
  `base_seat_fare` double(8,2) UNSIGNED NOT NULL,
  `base_sleeper_fare` double(8,2) UNSIGNED NOT NULL,
  `dep_time` datetime DEFAULT NULL,
  `arr_time` datetime DEFAULT NULL,
  `start_j_days` int(11) NOT NULL DEFAULT 0,
  `j_day` int(11) NOT NULL DEFAULT 0 COMMENT '0-same day 1- next day so on.. ',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `user_pin` varchar(50) NOT NULL,
  `first_name` varchar(120) DEFAULT NULL,
  `middle_name` varchar(120) DEFAULT NULL,
  `last_name` varchar(120) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `email` varchar(120) NOT NULL,
  `location` varchar(250) DEFAULT NULL,
  `org_name` varchar(254) DEFAULT NULL,
  `address` varchar(600) DEFAULT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `alternate_phone` varchar(30) DEFAULT NULL COMMENT 'additional phone',
  `alternate_email` varchar(100) DEFAULT NULL COMMENT 'additional email',
  `password` varchar(60) NOT NULL,
  `user_role` int(11) DEFAULT NULL,
  `rand_key` varchar(254) NOT NULL,
  `last_login` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(120) DEFAULT '',
  `phone` varchar(40) DEFAULT '',
  `pincode` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_image` blob DEFAULT NULL,
  `password` varchar(100) DEFAULT '',
  `otp` varchar(50) DEFAULT '',
  `is_verified` int(11) NOT NULL DEFAULT 0,
  `msg_id` varchar(50) DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_bank_details`
--

CREATE TABLE `user_bank_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `banking_name` varchar(250) DEFAULT NULL,
  `bank_name` varchar(200) DEFAULT NULL,
  `ifsc_code` varchar(50) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

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
ALTER TABLE `boarding_droping` ADD FULLTEXT KEY `boarding_point` (`boarding_point`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pnr` (`pnr`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `users_id` (`users_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `boarding_point` (`boarding_point`),
  ADD KEY `dropping_point` (`dropping_point`),
  ADD KEY `boarding_time` (`boarding_time`),
  ADD KEY `dropping_time` (`dropping_time`);

--
-- Indexes for table `booking_detail`
--
ALTER TABLE `booking_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `passenger_name` (`passenger_name`),
  ADD KEY `passenger_gender` (`passenger_gender`),
  ADD KEY `passenger_age` (`passenger_age`),
  ADD KEY `bus_seats_id` (`bus_seats_id`);

--
-- Indexes for table `booking_seized`
--
ALTER TABLE `booking_seized`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `booking_sequence`
--
ALTER TABLE `booking_sequence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `bus`
--
ALTER TABLE `bus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bus_number` (`bus_number`) USING BTREE,
  ADD KEY `bus_operator_FK` (`bus_operator_id`),
  ADD KEY `bus_type_fk` (`bus_type_id`),
  ADD KEY `bus_sitting_fk` (`bus_sitting_id`),
  ADD KEY `cancellation_slab_fk` (`cancellationslabs_id`),
  ADD KEY `bus_seatlayout_id_fk` (`bus_seat_layout_id`),
  ADD KEY `name` (`name`),
  ADD KEY `via` (`via`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_name` (`class_name`);

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
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `phone` (`phone`);

--
-- Indexes for table `bus_extra_fare`
--
ALTER TABLE `bus_extra_fare`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `bus_festival_fare`
--
ALTER TABLE `bus_festival_fare`
  ADD PRIMARY KEY (`id`),
  ADD KEY `festival_fare_id` (`festival_fare_id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `bus_gallery`
--
ALTER TABLE `bus_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `bus_location_sequence`
--
ALTER TABLE `bus_location_sequence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `bus_operator`
--
ALTER TABLE `bus_operator`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_id` (`email_id`),
  ADD UNIQUE KEY `contact_number` (`contact_number`),
  ADD KEY `operator_name` (`operator_name`),
  ADD KEY `location_name` (`location_name`);

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
  ADD KEY `ticket_price_FK` (`ticket_price_id`),
  ADD KEY `seats_id_fk` (`seats_id`),
  ADD KEY `new_fare` (`new_fare`);

--
-- Indexes for table `bus_seats_extra`
--
ALTER TABLE `bus_seats_extra`
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `bus_seat_layout`
--
ALTER TABLE `bus_seat_layout`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `bus_sitting`
--
ALTER TABLE `bus_sitting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

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
  ADD KEY `boardin_droping_id_fk` (`boarding_droping_id`),
  ADD KEY `stoppage_name` (`stoppage_name`),
  ADD KEY `stoppage_time` (`stoppage_time`);

--
-- Indexes for table `bus_type`
--
ALTER TABLE `bus_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_type_fk` (`bus_class_id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `cancellationslabs`
--
ALTER TABLE `cancellationslabs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rule_name` (`rule_name`);

--
-- Indexes for table `cancellationslabs_info`
--
ALTER TABLE `cancellationslabs_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cancellationslab_id_FK` (`cancellation_slab_id`),
  ADD KEY `duration` (`duration`),
  ADD KEY `deduction` (`deduction`);

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
-- Indexes for table `credentials`
--
ALTER TABLE `credentials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_payment`
--
ALTER TABLE `customer_payment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD UNIQUE KEY `razorpay_id` (`razorpay_id`),
  ADD KEY `name` (`name`),
  ADD KEY `payment_done` (`payment_done`),
  ADD KEY `transaction_id` (`booking_id`);

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
-- Indexes for table `festival_fare`
--
ALTER TABLE `festival_fare`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gateway_information`
--
ALTER TABLE `gateway_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `synonym` (`synonym`);

--
-- Indexes for table `locationcode`
--
ALTER TABLE `locationcode`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `odbus_charges`
--
ALTER TABLE `odbus_charges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offer_category_id` (`offer_category_id`);

--
-- Indexes for table `offer_category`
--
ALTER TABLE `offer_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `owner_fare`
--
ALTER TABLE `owner_fare`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `owner_payment`
--
ALTER TABLE `owner_payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_operator_id` (`bus_operator_id`);

--
-- Indexes for table `pre_booking`
--
ALTER TABLE `pre_booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `journey_dt` (`journey_dt`),
  ADD KEY `coupon_code` (`coupon_code`);

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
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `users_id` (`users_id`),
  ADD KEY `pnr` (`pnr`);

--
-- Indexes for table `safety`
--
ALTER TABLE `safety`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seats_ibfk_1` (`bus_seat_layout_id`),
  ADD KEY `seats_ibfk_12` (`seat_class_id`),
  ADD KEY `berthType` (`berthType`),
  ADD KEY `seatText` (`seatText`),
  ADD KEY `colNumber` (`colNumber`),
  ADD KEY `rowNumber` (`rowNumber`);

--
-- Indexes for table `seat_block`
--
ALTER TABLE `seat_block`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operator_id` (`operator_id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `seat_block_seats`
--
ALTER TABLE `seat_block_seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seat_block_id` (`seat_block_id`);

--
-- Indexes for table `seat_class`
--
ALTER TABLE `seat_class`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `seat_open`
--
ALTER TABLE `seat_open`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operator_id` (`operator_id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `seat_open_seats`
--
ALTER TABLE `seat_open_seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seat_id` (`seats_id`),
  ADD KEY `seat_open_id` (`seat_open_id`);

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
-- Indexes for table `ticket_fare_slab`
--
ALTER TABLE `ticket_fare_slab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_price`
--
ALTER TABLE `ticket_price`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bus_id` (`bus_id`),
  ADD KEY `ticket_price_bus_operator_fk` (`bus_operator_id`),
  ADD KEY `ticket_price_source_fk` (`source_id`),
  ADD KEY `ticket_price_destination_fk` (`destination_id`),
  ADD KEY `start_j_days` (`start_j_days`),
  ADD KEY `j_day` (`j_day`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `email` (`email`) USING BTREE,
  ADD KEY `phone` (`phone`),
  ADD KEY `otp` (`otp`),
  ADD KEY `is_verified` (`is_verified`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appdownload`
--
ALTER TABLE `appdownload`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appversion`
--
ALTER TABLE `appversion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boarding_droping`
--
ALTER TABLE `boarding_droping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_detail`
--
ALTER TABLE `booking_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_seized`
--
ALTER TABLE `booking_seized`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_sequence`
--
ALTER TABLE `booking_sequence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus`
--
ALTER TABLE `bus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_amenities`
--
ALTER TABLE `bus_amenities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_cancelled`
--
ALTER TABLE `bus_cancelled`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_cancelled_date`
--
ALTER TABLE `bus_cancelled_date`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_class`
--
ALTER TABLE `bus_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_closing_hours`
--
ALTER TABLE `bus_closing_hours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_contacts`
--
ALTER TABLE `bus_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_extra_fare`
--
ALTER TABLE `bus_extra_fare`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_festival_fare`
--
ALTER TABLE `bus_festival_fare`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_gallery`
--
ALTER TABLE `bus_gallery`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_location_sequence`
--
ALTER TABLE `bus_location_sequence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_operator`
--
ALTER TABLE `bus_operator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_owner_fare`
--
ALTER TABLE `bus_owner_fare`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_safety`
--
ALTER TABLE `bus_safety`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_schedule`
--
ALTER TABLE `bus_schedule`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_schedule_date`
--
ALTER TABLE `bus_schedule_date`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_seats`
--
ALTER TABLE `bus_seats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_seat_layout`
--
ALTER TABLE `bus_seat_layout`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_sitting`
--
ALTER TABLE `bus_sitting`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_slots`
--
ALTER TABLE `bus_slots`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_special_fare`
--
ALTER TABLE `bus_special_fare`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_stoppage_additional_fare`
--
ALTER TABLE `bus_stoppage_additional_fare`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_stoppage_timing`
--
ALTER TABLE `bus_stoppage_timing`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_type`
--
ALTER TABLE `bus_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cancellationslabs`
--
ALTER TABLE `cancellationslabs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cancellationslabs_info`
--
ALTER TABLE `cancellationslabs_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `city_closing`
--
ALTER TABLE `city_closing`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `city_closing_extended`
--
ALTER TABLE `city_closing_extended`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_assigned_bus`
--
ALTER TABLE `coupon_assigned_bus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credentials`
--
ALTER TABLE `credentials`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_payment`
--
ALTER TABLE `customer_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_query`
--
ALTER TABLE `customer_query`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_query_category`
--
ALTER TABLE `customer_query_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_query_category_issues`
--
ALTER TABLE `customer_query_category_issues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_pages`
--
ALTER TABLE `custom_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `extended_bus_closing_hours`
--
ALTER TABLE `extended_bus_closing_hours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `festival_fare`
--
ALTER TABLE `festival_fare`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gateway_information`
--
ALTER TABLE `gateway_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locationcode`
--
ALTER TABLE `locationcode`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `odbus_charges`
--
ALTER TABLE `odbus_charges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offer_category`
--
ALTER TABLE `offer_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `owner_fare`
--
ALTER TABLE `owner_fare`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `owner_payment`
--
ALTER TABLE `owner_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pre_booking`
--
ALTER TABLE `pre_booking`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pre_booking_detail`
--
ALTER TABLE `pre_booking_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reason`
--
ALTER TABLE `reason`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `safety`
--
ALTER TABLE `safety`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seat_block`
--
ALTER TABLE `seat_block`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seat_block_seats`
--
ALTER TABLE `seat_block_seats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seat_class`
--
ALTER TABLE `seat_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seat_open`
--
ALTER TABLE `seat_open`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seat_open_seats`
--
ALTER TABLE `seat_open_seats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_master`
--
ALTER TABLE `site_master`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `special_fare`
--
ALTER TABLE `special_fare`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_cancelation`
--
ALTER TABLE `ticket_cancelation`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_cancelation_rule`
--
ALTER TABLE `ticket_cancelation_rule`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_fare_slab`
--
ALTER TABLE `ticket_fare_slab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_price`
--
ALTER TABLE `ticket_price`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_bank_details`
--
ALTER TABLE `user_bank_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `booking_detail`
--
ALTER TABLE `booking_detail`
  ADD CONSTRAINT `booking_detail_ibfk_1` FOREIGN KEY (`bus_seats_id`) REFERENCES `bus_seats` (`id`);

--
-- Constraints for table `booking_seized`
--
ALTER TABLE `booking_seized`
  ADD CONSTRAINT `booking_seized_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`),
  ADD CONSTRAINT `booking_seized_ibfk_2` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`);

--
-- Constraints for table `booking_sequence`
--
ALTER TABLE `booking_sequence`
  ADD CONSTRAINT `booking_sequence_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`);

--
-- Constraints for table `bus`
--
ALTER TABLE `bus`
  ADD CONSTRAINT `bus_operator_FK` FOREIGN KEY (`bus_operator_id`) REFERENCES `bus_operator` (`id`),
  ADD CONSTRAINT `bus_seatlayout_id_fk` FOREIGN KEY (`bus_seat_layout_id`) REFERENCES `bus_seat_layout` (`id`),
  ADD CONSTRAINT `bus_sitting_fk` FOREIGN KEY (`bus_sitting_id`) REFERENCES `bus_sitting` (`id`),
  ADD CONSTRAINT `bus_type_id_fk` FOREIGN KEY (`bus_type_id`) REFERENCES `bus_type` (`id`),
  ADD CONSTRAINT `cancellation_slab_fk` FOREIGN KEY (`cancellationslabs_id`) REFERENCES `cancellationslabs` (`id`);

--
-- Constraints for table `bus_cancelled`
--
ALTER TABLE `bus_cancelled`
  ADD CONSTRAINT `bus_operator_for_cancelled_FK` FOREIGN KEY (`bus_operator_id`) REFERENCES `bus_operator` (`id`);

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
-- Constraints for table `bus_festival_fare`
--
ALTER TABLE `bus_festival_fare`
  ADD CONSTRAINT `bus_festival_fare_ibfk_1` FOREIGN KEY (`festival_fare_id`) REFERENCES `festival_fare` (`id`),
  ADD CONSTRAINT `bus_festival_fare_ibfk_2` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`);

--
-- Constraints for table `bus_gallery`
--
ALTER TABLE `bus_gallery`
  ADD CONSTRAINT `bus_gallery_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`);

--
-- Constraints for table `bus_location_sequence`
--
ALTER TABLE `bus_location_sequence`
  ADD CONSTRAINT `bus_location_sequence_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`),
  ADD CONSTRAINT `bus_location_sequence_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`);

--
-- Constraints for table `bus_safety`
--
ALTER TABLE `bus_safety`
  ADD CONSTRAINT `safety_bus_id_fk` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`),
  ADD CONSTRAINT `safety_id_fk` FOREIGN KEY (`safety_id`) REFERENCES `safety` (`id`);

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
  ADD CONSTRAINT `seats_id_fk` FOREIGN KEY (`seats_id`) REFERENCES `seats` (`id`),
  ADD CONSTRAINT `ticket_price_FK` FOREIGN KEY (`ticket_price_id`) REFERENCES `ticket_price` (`id`);

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
  ADD CONSTRAINT `bus_seats_id_fk` FOREIGN KEY (`bus_seats_id`) REFERENCES `bus_seats` (`id`),
  ADD CONSTRAINT `bus_stoppage_additional_fare_ibfk_1` FOREIGN KEY (`ticket_price_id`) REFERENCES `ticket_price` (`id`);

--
-- Constraints for table `bus_stoppage_timing`
--
ALTER TABLE `bus_stoppage_timing`
  ADD CONSTRAINT `boardin_droping_id_fk` FOREIGN KEY (`boarding_droping_id`) REFERENCES `boarding_droping` (`id`),
  ADD CONSTRAINT `location_timing_fk` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`),
  ADD CONSTRAINT `stoppage_timing_bus_id_fk` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`);

--
-- Constraints for table `bus_type`
--
ALTER TABLE `bus_type`
  ADD CONSTRAINT `class_type_fk` FOREIGN KEY (`bus_class_id`) REFERENCES `bus_class` (`id`);

--
-- Constraints for table `cancellationslabs_info`
--
ALTER TABLE `cancellationslabs_info`
  ADD CONSTRAINT `cancellationslab_id_FK` FOREIGN KEY (`cancellation_slab_id`) REFERENCES `cancellationslabs` (`id`);

--
-- Constraints for table `city_closing`
--
ALTER TABLE `city_closing`
  ADD CONSTRAINT `city_closing_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`),
  ADD CONSTRAINT `location_closing_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`);

--
-- Constraints for table `city_closing_extended`
--
ALTER TABLE `city_closing_extended`
  ADD CONSTRAINT `city_closing_extended_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`),
  ADD CONSTRAINT `city_closing_extended_location_fk` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`);

--
-- Constraints for table `coupon_assigned_bus`
--
ALTER TABLE `coupon_assigned_bus`
  ADD CONSTRAINT `coupon_assigned_bus_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`),
  ADD CONSTRAINT `coupon_assigned_id_fk` FOREIGN KEY (`coupon_id`) REFERENCES `coupon` (`id`);

--
-- Constraints for table `customer_payment`
--
ALTER TABLE `customer_payment`
  ADD CONSTRAINT `customer_payment_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`);

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
-- Constraints for table `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `offers_ibfk_1` FOREIGN KEY (`offer_category_id`) REFERENCES `offer_category` (`id`);

--
-- Constraints for table `owner_payment`
--
ALTER TABLE `owner_payment`
  ADD CONSTRAINT `owner_payment_ibfk_1` FOREIGN KEY (`bus_operator_id`) REFERENCES `bus_operator` (`id`);

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
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`bus_seat_layout_id`) REFERENCES `bus_seat_layout` (`id`),
  ADD CONSTRAINT `seats_ibfk_12` FOREIGN KEY (`seat_class_id`) REFERENCES `seat_class` (`id`);

--
-- Constraints for table `seat_block`
--
ALTER TABLE `seat_block`
  ADD CONSTRAINT `seat_block_ibfk_1` FOREIGN KEY (`operator_id`) REFERENCES `bus_operator` (`id`),
  ADD CONSTRAINT `seat_block_ibfk_2` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`);

--
-- Constraints for table `seat_block_seats`
--
ALTER TABLE `seat_block_seats`
  ADD CONSTRAINT `seat_block_seats_ibfk_1` FOREIGN KEY (`seat_block_id`) REFERENCES `seat_block` (`id`);

--
-- Constraints for table `seat_open`
--
ALTER TABLE `seat_open`
  ADD CONSTRAINT `seat_open_ibfk_1` FOREIGN KEY (`operator_id`) REFERENCES `bus_operator` (`id`),
  ADD CONSTRAINT `seat_open_ibfk_2` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`);

--
-- Constraints for table `seat_open_seats`
--
ALTER TABLE `seat_open_seats`
  ADD CONSTRAINT `seat_open_seats_ibfk_1` FOREIGN KEY (`seats_id`) REFERENCES `seats` (`id`),
  ADD CONSTRAINT `seat_open_seats_ibfk_2` FOREIGN KEY (`seat_open_id`) REFERENCES `seat_open` (`id`);

--
-- Constraints for table `special_fare`
--
ALTER TABLE `special_fare`
  ADD CONSTRAINT `special_fare_destination_fk` FOREIGN KEY (`destination_id`) REFERENCES `location` (`id`),
  ADD CONSTRAINT `special_fare_operator_fk` FOREIGN KEY (`bus_operator_id`) REFERENCES `bus_operator` (`id`),
  ADD CONSTRAINT `special_fare_source_fk` FOREIGN KEY (`source_id`) REFERENCES `location` (`id`);

--
-- Constraints for table `ticket_cancelation_rule`
--
ALTER TABLE `ticket_cancelation_rule`
  ADD CONSTRAINT `ticket_cancelation_rule_ibfk_1` FOREIGN KEY (`ticket_cancelation_id`) REFERENCES `ticket_cancelation` (`id`);

--
-- Constraints for table `ticket_price`
--
ALTER TABLE `ticket_price`
  ADD CONSTRAINT `ticket_price_bus_operator_fk` FOREIGN KEY (`bus_operator_id`) REFERENCES `bus_operator` (`id`),
  ADD CONSTRAINT `ticket_price_destination_fk` FOREIGN KEY (`destination_id`) REFERENCES `location` (`id`),
  ADD CONSTRAINT `ticket_price_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `ticket_price_ibfk_2` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`),
  ADD CONSTRAINT `ticket_price_source_fk` FOREIGN KEY (`source_id`) REFERENCES `location` (`id`);

--
-- Constraints for table `user_bank_details`
--
ALTER TABLE `user_bank_details`
  ADD CONSTRAINT `user_bank_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
