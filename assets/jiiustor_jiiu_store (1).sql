-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 30, 2019 at 05:12 PM
-- Server version: 5.7.27
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jiiustor_jiiu_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_access`
--

CREATE TABLE `admin_access` (
  `id` int(11) NOT NULL,
  `view_page` varchar(100) NOT NULL DEFAULT '0',
  `access` tinyint(4) NOT NULL DEFAULT '0',
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_access`
--

INSERT INTO `admin_access` (`id`, `view_page`, `access`, `timestemp`) VALUES
(1, 'View Chat', 0, '2019-06-24 08:35:59');

-- --------------------------------------------------------

--
-- Table structure for table `assing_technician_complaint`
--

CREATE TABLE `assing_technician_complaint` (
  `id` int(10) NOT NULL,
  `complaint_id` int(15) NOT NULL,
  `technician_id` int(15) NOT NULL,
  `date` varchar(30) NOT NULL,
  `tech_type` int(5) NOT NULL DEFAULT '0',
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL,
  `update_des` varchar(2000) DEFAULT NULL,
  `update_img` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `assing_technician_complaint`
--

INSERT INTO `assing_technician_complaint` (`id`, `complaint_id`, `technician_id`, `date`, `tech_type`, `timestemp`, `update_time`, `update_des`, `update_img`) VALUES
(1, 55361463, 8756, '20-05-2019 17:41', 0, '2019-05-20 12:11:56', NULL, NULL, NULL),
(2, 864793782, 7312, '20-05-2019 17:43', 0, '2019-05-20 12:13:45', NULL, NULL, NULL),
(3, 864793782, 8756, '20-05-2019 17:43', 0, '2019-05-20 12:13:45', NULL, NULL, NULL),
(4, 864793782, 7326, '20-05-2019 17:43', 0, '2019-05-20 12:13:45', NULL, NULL, NULL),
(5, 986616315, 7312, '22-05-2019 16:53', 0, '2019-05-22 11:23:14', NULL, NULL, NULL),
(6, 986616315, 8756, '22-05-2019 16:53', 0, '2019-05-22 11:23:14', NULL, NULL, NULL),
(7, 557234482, 9474, '23-05-2019 13:59', 0, '2019-05-23 08:29:52', NULL, NULL, NULL),
(8, 557234482, 9569, '23-05-2019 13:59', 0, '2019-05-23 08:29:52', NULL, NULL, NULL),
(9, 975334693, 8726, '23-05-2019 14:01', 0, '2019-05-23 08:31:12', NULL, NULL, NULL),
(10, 975334693, 8756, '23-05-2019 14:01', 0, '2019-05-23 08:31:12', NULL, NULL, NULL),
(11, 133522249, 8726, '03-06-2019 11:14', 0, '2019-06-03 05:44:46', NULL, NULL, NULL),
(12, 264883604, 7312, '03-06-2019 11:33', 0, '2019-06-03 06:03:18', NULL, NULL, NULL),
(13, 279409655, 3408, '14-06-2019 13:10', 0, '2019-06-14 07:40:29', NULL, NULL, NULL),
(14, 973460097, 3408, '18-06-2019 16:45', 0, '2019-06-18 11:15:49', NULL, NULL, NULL),
(15, 973460097, 8726, '18-06-2019 16:45', 0, '2019-06-18 11:15:49', NULL, NULL, NULL),
(16, 537823743, 3408, '03-07-2019 17:00', 0, '2019-07-03 11:30:05', NULL, NULL, NULL),
(17, 537823743, 8726, '03-07-2019 17:00', 0, '2019-07-03 11:30:05', NULL, NULL, NULL),
(18, 537823743, 7312, '03-07-2019 17:00', 0, '2019-07-03 11:30:05', NULL, NULL, NULL),
(19, 537823743, 8756, '03-07-2019 17:00', 0, '2019-07-03 11:30:05', NULL, NULL, NULL),
(20, 537823743, 7581, '03-07-2019 17:00', 0, '2019-07-03 11:30:05', NULL, NULL, NULL),
(21, 537823743, 9474, '03-07-2019 17:00', 0, '2019-07-03 11:30:05', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `clg_flr_lby_asset`
--

CREATE TABLE `clg_flr_lby_asset` (
  `id` int(20) NOT NULL,
  `clg_id` int(20) NOT NULL,
  `flr_id` int(20) NOT NULL,
  `lby_wash_stair` int(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `asset_type` varchar(50) NOT NULL,
  `qty` int(20) NOT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `clg_flr_lby_asset`
--

INSERT INTO `clg_flr_lby_asset` (`id`, `clg_id`, `flr_id`, `lby_wash_stair`, `name`, `asset_type`, `qty`, `timestemp`) VALUES
(39787, 904629, 11040, 1, 'washbasin', 'Plumbing', 2, '2019-06-14 10:57:40'),
(81258, 904629, 11040, 1, 'cvc', 'ELECTRICAL', 2, '2019-07-27 10:07:24'),
(64744, 803397, 40779, 54, 'xtx', 'LAUNDRY', 2, '2019-07-27 10:13:09');

-- --------------------------------------------------------

--
-- Table structure for table `clg_flr_lby_asset_des`
--

CREATE TABLE `clg_flr_lby_asset_des` (
  `id` int(20) NOT NULL,
  `asset_id` int(50) NOT NULL,
  `des` varchar(200) NOT NULL,
  `uniq_code` varchar(50) NOT NULL,
  `delete_status` int(2) NOT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `clg_flr_lby_asset_des`
--

INSERT INTO `clg_flr_lby_asset_des` (`id`, `asset_id`, `des`, `uniq_code`, `delete_status`, `timestemp`) VALUES
(1, 39787, 'xyz', '123', 0, '2019-06-14 10:57:40'),
(2, 39787, 'xcb', '124', 0, '2019-06-14 10:57:40'),
(3, 81258, 'gff', '123', 0, '2019-07-27 10:07:24'),
(4, 81258, 'hg', '745', 0, '2019-07-27 10:07:24'),
(5, 64744, 'dd', '234', 0, '2019-07-27 10:13:09'),
(6, 64744, 'd', '239', 0, '2019-07-27 10:13:09');

-- --------------------------------------------------------

--
-- Table structure for table `cms_chat`
--

CREATE TABLE `cms_chat` (
  `id` int(10) NOT NULL,
  `type` varchar(50) NOT NULL,
  `sender_id` int(10) NOT NULL,
  `reciver_id` int(10) NOT NULL,
  `msg` varchar(2000) NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0',
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_chat`
--

INSERT INTO `cms_chat` (`id`, `type`, `sender_id`, `reciver_id`, `msg`, `status`, `timestemp`) VALUES
(5, 'HOSTEL', 7, 8, 'hay bruhh..!', 0, '2019-06-24 09:51:45'),
(6, 'HOSTEL', 8, 7, 'i am good man..!', 0, '2019-06-24 09:51:45'),
(7, 'HOSTEL', 7, 8, 'hay there', 0, '2019-06-24 10:36:06'),
(8, 'HOSTEL', 8, 7, 'jalse bhai', 0, '2019-06-24 10:36:35'),
(19, 'HOSTEL', 7, 8, 'hii', 0, '2019-07-10 16:29:34'),
(11, 'HOSTEL', 7, 8, 'hows', 0, '2019-07-04 05:00:08'),
(12, 'HOSTEL', 7, 9, 'kdjfsk', 0, '2019-07-06 08:23:21'),
(13, 'HOSTEL', 10, 7, 'i m 10', 0, '2019-07-06 08:23:21'),
(14, 'HOSTEL', 8, 11, 'vkldfk', 0, '2019-07-06 08:25:55'),
(18, 'HOSTEL', 7, 8, '', 0, '2019-07-10 16:28:47'),
(17, 'HOSTEL', 12, 13, 'hjghhj', 0, '2019-07-06 08:27:28'),
(20, 'HOSTEL', 7, 8, 'ok', 0, '2019-07-10 16:29:42'),
(21, 'HOSTEL', 7, 8, 'hii', 0, '2019-07-10 16:56:01'),
(22, 'HOSTEL', 7, 8, 'test', 0, '2019-07-10 16:56:08'),
(23, 'HOSTEL', 7, 8, 'hii', 0, '2019-07-10 17:04:23'),
(24, 'HOSTEL', 7, 8, 'ok', 0, '2019-07-10 17:04:27'),
(25, 'HOSTEL', 7, 8, 'chhc', 0, '2019-07-11 05:25:49'),
(26, 'HOSTEL', 7, 8, 'chvn', 0, '2019-07-11 05:25:59'),
(27, 'HOSTEL', 7, 8, 'bsbsbbsbd\nnnsndnbjjd\nnndndb', 0, '2019-07-11 05:48:09'),
(28, 'HOSTEL', 7, 8, 'ffncnnvbkkbkkbb', 0, '2019-07-11 06:54:55'),
(29, 'HOSTEL', 7, 8, 'xgch\nhffh\ndggch', 0, '2019-07-11 06:55:03'),
(30, 'HOSTEL', 7, 8, 'hjd', 0, '2019-07-15 09:58:03'),
(31, 'HOSTEL', 7, 8, 'kijldhd', 0, '2019-07-15 09:58:12'),
(32, 'HOSTEL', 7, 8, 'abuzer ansari', 0, '2019-07-15 09:59:57'),
(33, 'HOSTEL', 7, 8, 'Jnnhu', 0, '2019-07-16 06:45:48');

-- --------------------------------------------------------

--
-- Table structure for table `cms_clg_flr_asset_des`
--

CREATE TABLE `cms_clg_flr_asset_des` (
  `id` int(15) NOT NULL,
  `asset_id` varchar(20) NOT NULL,
  `des` varchar(50) DEFAULT NULL,
  `uniq_code` varchar(20) NOT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_clg_flr_asset_des`
--

INSERT INTO `cms_clg_flr_asset_des` (`id`, `asset_id`, `des`, `uniq_code`, `timestemp`) VALUES
(34, '92942', 'demodes', '123', '2019-04-24 09:51:12'),
(35, '92942', 'demodes2', '1234', '2019-04-24 09:51:12'),
(36, '53873', 'hghg', 'fan', '2019-04-27 11:29:11'),
(37, '53873', 'kjkj', 'gf', '2019-04-27 11:29:11'),
(38, '53873', 'kjkj', 'fan1', '2019-04-27 11:29:11'),
(39, '21381', 'aaaaa', 'xyz', '2019-06-15 07:16:10'),
(40, '21381', 'bbbbb', 'abcd', '2019-06-15 07:16:10'),
(41, '7139', 'fghfh', 'fghfhfg', '2019-07-11 06:32:28'),
(42, '29216', 'fff', '125', '2019-07-27 10:19:13'),
(43, '29216', 'dh', '453', '2019-07-27 10:19:13'),
(44, '9114', 'ffgfggf', '24', '2019-07-27 10:24:00');

-- --------------------------------------------------------

--
-- Table structure for table `cms_clg_room`
--

CREATE TABLE `cms_clg_room` (
  `clg_id` int(20) NOT NULL,
  `flr_id` int(20) NOT NULL,
  `room_id` int(20) NOT NULL,
  `room_name` varchar(100) DEFAULT NULL,
  `prefix` varchar(10) DEFAULT NULL,
  `room_no` varchar(20) DEFAULT NULL,
  `des` varchar(300) DEFAULT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_clg_room`
--

INSERT INTO `cms_clg_room` (`clg_id`, `flr_id`, `room_id`, `room_name`, `prefix`, `room_no`, `des`, `timestemp`) VALUES
(904629, 11040, 20, 'comp lab', NULL, '', '', '2019-03-13 09:02:48'),
(904629, 11040, 21, 'elec lab', NULL, '', '', '2019-03-13 09:02:48'),
(904629, 14074, 22, NULL, 'A', 'A-1', NULL, '2019-03-15 09:17:35'),
(904629, 14074, 23, NULL, 'A', 'A-2', NULL, '2019-03-15 09:17:35'),
(410644, 18987, 102, 'fora23', NULL, '2', 'fghfhf', '2019-07-11 06:31:57'),
(410644, 18987, 101, 'forarrom12', NULL, '1', 'gfhghg', '2019-07-11 06:31:57'),
(868346, 81573, 42, NULL, 'G', 'G-10', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 43, NULL, 'G', 'G-11', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 44, NULL, 'G', 'G-12', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 45, NULL, 'G', 'G-13', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 46, NULL, 'G', 'G-14', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 47, NULL, 'G', 'G-15', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 48, NULL, 'G', 'G-16', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 49, NULL, 'G', 'G-17', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 50, NULL, 'G', 'G-18', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 51, NULL, 'G', 'G-19', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 52, NULL, 'G', 'G-20', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 53, NULL, 'G', 'G-21', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 54, NULL, 'G', 'G-22', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 55, NULL, 'G', 'G-23', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 56, NULL, 'G', 'G-24', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 57, NULL, 'G', 'G-25', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 58, NULL, 'G', 'G-26', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 59, NULL, 'G', 'G-27', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 60, NULL, 'G', 'G-28', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 61, NULL, 'G', 'G-29', NULL, '2019-03-24 06:50:20'),
(868346, 81573, 62, NULL, 'G', 'G-30', NULL, '2019-03-24 06:50:20'),
(508205, 49762, 63, NULL, 's', 's-1', NULL, '2019-04-30 07:51:01'),
(508205, 49762, 64, NULL, 's', 's-2', NULL, '2019-04-30 07:51:01'),
(508205, 49762, 65, NULL, 's', 's-3', NULL, '2019-04-30 07:51:01'),
(508205, 49762, 66, NULL, 's', 's-4', NULL, '2019-04-30 07:51:01'),
(508205, 49762, 67, NULL, 's', 's-5', NULL, '2019-04-30 07:51:01'),
(508205, 49762, 68, NULL, 's', 's-6', NULL, '2019-04-30 07:51:01'),
(508205, 49762, 69, NULL, 's', 's-7', NULL, '2019-04-30 07:51:01'),
(508205, 49762, 70, NULL, 's', 's-8', NULL, '2019-04-30 07:51:01'),
(508205, 49762, 71, NULL, 's', 's-9', NULL, '2019-04-30 07:51:01'),
(508205, 49762, 72, NULL, 's', 's-10', NULL, '2019-04-30 07:51:01'),
(508205, 49762, 73, NULL, 'S', 'S-1', NULL, '2019-04-30 07:53:20'),
(508205, 49762, 74, NULL, 'S', 'S-2', NULL, '2019-04-30 07:53:20'),
(508205, 49762, 75, NULL, 'S', 'S-3', NULL, '2019-04-30 07:53:20'),
(508205, 49762, 76, NULL, 'S', 'S-4', NULL, '2019-04-30 07:53:20'),
(508205, 49762, 77, NULL, 'S', 'S-5', NULL, '2019-04-30 07:53:20'),
(508205, 49762, 78, NULL, 'S', 'S-6', NULL, '2019-04-30 07:53:20'),
(508205, 49762, 79, NULL, 'S', 'S-7', NULL, '2019-04-30 07:53:20'),
(508205, 49762, 80, NULL, 'S', 'S-8', NULL, '2019-04-30 07:53:20'),
(508205, 49762, 81, NULL, 'S', 'S-9', NULL, '2019-04-30 07:53:20'),
(508205, 49762, 82, NULL, 'S', 'S-10', NULL, '2019-04-30 07:53:20'),
(508205, 49762, 83, 'gvyvgbh', NULL, '12', 'fctggv', '2019-04-30 07:56:28'),
(508205, 49762, 84, 'gygyg', NULL, '13', 'njjnj', '2019-04-30 07:56:28'),
(508205, 49762, 85, NULL, 'c', 'c-10', NULL, '2019-04-30 09:52:32'),
(508205, 49762, 86, NULL, 'c', 'c-11', NULL, '2019-04-30 09:52:32'),
(508205, 49762, 87, NULL, 'c', 'c-12', NULL, '2019-04-30 09:52:32'),
(508205, 49762, 88, NULL, 'c', 'c-13', NULL, '2019-04-30 09:52:32'),
(508205, 49762, 89, NULL, 'c', 'c-14', NULL, '2019-04-30 09:52:32'),
(508205, 49762, 90, NULL, 'c', 'c-15', NULL, '2019-04-30 09:52:32'),
(728346, 14739, 91, NULL, 'B', 'B-B-1', NULL, '2019-04-30 09:54:34'),
(728346, 14739, 92, NULL, 'B', 'B-B-2', NULL, '2019-04-30 09:54:34'),
(728346, 14739, 93, NULL, 'B', 'B-B-3', NULL, '2019-04-30 09:54:34'),
(728346, 14739, 94, NULL, 'B', 'B-B-4', NULL, '2019-04-30 09:54:34'),
(728346, 14739, 95, NULL, 'B', 'B-B-5', NULL, '2019-04-30 09:54:34'),
(728346, 14739, 96, NULL, 's', 's-1', NULL, '2019-04-30 10:16:31'),
(728346, 14739, 97, NULL, 's', 's-2', NULL, '2019-04-30 10:16:31'),
(728346, 14739, 98, NULL, 's', 's-3', NULL, '2019-04-30 10:16:31'),
(728346, 14739, 99, NULL, 's', 's-4', NULL, '2019-04-30 10:16:31'),
(728346, 14739, 100, NULL, 's', 's-5', NULL, '2019-04-30 10:16:31');

-- --------------------------------------------------------

--
-- Table structure for table `cms_clg_room_asset`
--

CREATE TABLE `cms_clg_room_asset` (
  `id` int(10) NOT NULL,
  `clg_id` int(10) NOT NULL,
  `flr_id` int(10) NOT NULL,
  `room_id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `asset_type` varchar(100) NOT NULL,
  `qty` int(10) NOT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_clg_room_asset`
--

INSERT INTO `cms_clg_room_asset` (`id`, `clg_id`, `flr_id`, `room_id`, `name`, `asset_type`, `qty`, `timestemp`) VALUES
(92942, 904629, 11040, 20, 'demo', 'Elec', 2, '2019-04-24 09:51:12'),
(53873, 868346, 81573, 42, 'fan', 'Elec', 3, '2019-04-27 11:29:11'),
(21381, 904629, 11040, 20, 'new', 'ELECTRICAL', 2, '2019-06-15 07:16:10'),
(7139, 410644, 18987, 102, 'demo12', 'GARDENER', 1, '2019-07-11 06:32:28'),
(29216, 904629, 11040, 20, 'comp', 'ELECTRICAL', 2, '2019-07-27 10:19:13'),
(9114, 410644, 18987, 102, 'dddd', 'ELECTRICAL', 1, '2019-07-27 10:24:00');

-- --------------------------------------------------------

--
-- Table structure for table `cms_college_department`
--

CREATE TABLE `cms_college_department` (
  `dep_id` int(20) NOT NULL,
  `dep_name` varchar(100) NOT NULL,
  `dep_des` varchar(500) NOT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_college_department`
--

INSERT INTO `cms_college_department` (`dep_id`, `dep_name`, `dep_des`, `timestemp`) VALUES
(3, 'scate department', 'scate des', '2019-03-24 06:50:49'),
(2, 'computer', 'computer des', '2019-03-13 10:40:06');

-- --------------------------------------------------------

--
-- Table structure for table `cms_college_details`
--

CREATE TABLE `cms_college_details` (
  `id` int(15) NOT NULL,
  `college_name` varchar(70) NOT NULL,
  `college_des` varchar(100) DEFAULT NULL,
  `no_floor` int(10) NOT NULL DEFAULT '0',
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_college_details`
--

INSERT INTO `cms_college_details` (`id`, `college_name`, `college_des`, `no_floor`, `timestemp`) VALUES
(904629, 'prime', 'xx', 2, '2019-03-12 10:23:48'),
(868346, 'scate', 'scae dep', 2, '2019-03-24 06:49:29'),
(508205, 'sutex', 'amroli', 4, '2019-04-30 07:48:35'),
(728346, 's.v.patel', 'sumul dairy road', 3, '2019-04-30 09:10:41'),
(52364, 'r.v.patel', 'd', 3, '2019-04-30 10:04:21'),
(382390, 'iit', 'dd', 1, '2019-05-17 15:23:30'),
(803397, 'IITN', 'N/A', 1, '2019-05-17 15:29:47'),
(785233, 'sv', 'fhgjhdgf', 1, '2019-07-09 05:34:23'),
(410644, 'foram', 'fghfh', 2, '2019-07-11 06:29:08');

-- --------------------------------------------------------

--
-- Table structure for table `cms_college_floor_assets`
--

CREATE TABLE `cms_college_floor_assets` (
  `flr_id` int(20) NOT NULL,
  `asset_type` varchar(15) NOT NULL,
  `asset_id` int(15) NOT NULL,
  `asset_name` varchar(75) NOT NULL,
  `asset_des` varchar(100) NOT NULL DEFAULT 'N/A',
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_college_floor_assets`
--

INSERT INTO `cms_college_floor_assets` (`flr_id`, `asset_type`, `asset_id`, `asset_name`, `asset_des`, `timestemp`) VALUES
(11040, 'STAIR', 1, 'first stair', 'xcxxx', '2019-03-12 10:23:48'),
(11040, 'LOBBY', 2, ' ground firstt lobby', 'lobydes', '2019-03-12 10:23:48'),
(11040, 'LOBBY', 3, 'ground second loby', 'lobyxx', '2019-03-12 10:23:48'),
(11040, 'WASH', 4, 'man wash', 'man', '2019-03-12 10:23:48'),
(14074, 'STAIR', 5, 'first stair', 'cvb', '2019-03-12 10:23:48'),
(14074, 'LOBBY', 6, 'first flr loby', 'cvc', '2019-03-12 10:23:48'),
(14074, 'WASH', 7, 'ladies washroom', 'cx', '2019-03-12 10:23:48'),
(67833, 'STAIR', 8, '', '', '2019-03-24 06:41:53'),
(67833, 'LOBBY', 9, '', '', '2019-03-24 06:41:53'),
(67833, 'WASH', 10, '', '', '2019-03-24 06:41:53'),
(5537, 'STAIR', 11, '', '', '2019-03-24 06:41:53'),
(5537, 'LOBBY', 12, '', '', '2019-03-24 06:41:53'),
(5537, 'WASH', 13, '', '', '2019-03-24 06:41:53'),
(81573, 'STAIR', 14, 'ground stari', '', '2019-03-24 06:49:29'),
(81573, 'LOBBY', 15, 'ground loby', 'f', '2019-03-24 06:49:29'),
(81573, 'WASH', 16, '', '', '2019-03-24 06:49:29'),
(15498, 'STAIR', 17, 'frist starir', 'scate first stair', '2019-03-24 06:49:29'),
(15498, 'LOBBY', 18, '', '', '2019-03-24 06:49:29'),
(15498, 'WASH', 19, '', '', '2019-03-24 06:49:29'),
(49762, 'STAIR', 20, 'hdusho', 'uiwh', '2019-04-30 07:48:35'),
(49762, 'LOBBY', 21, 'dfjdjff', 'idjsif', '2019-04-30 07:48:35'),
(49762, 'WASH', 22, 'disi', 'idi', '2019-04-30 07:48:35'),
(26073, 'STAIR', 23, 'iiu', 'kio', '2019-04-30 07:48:35'),
(26073, 'LOBBY', 24, '', '', '2019-04-30 07:48:35'),
(26073, 'WASH', 25, '', '', '2019-04-30 07:48:35'),
(38612, 'STAIR', 26, '', '', '2019-04-30 07:48:35'),
(38612, 'LOBBY', 27, '', '', '2019-04-30 07:48:35'),
(38612, 'WASH', 28, '', '', '2019-04-30 07:48:35'),
(69937, 'STAIR', 29, '', '', '2019-04-30 07:48:35'),
(69937, 'LOBBY', 30, '', '', '2019-04-30 07:48:35'),
(69937, 'WASH', 31, '', '', '2019-04-30 07:48:35'),
(14739, 'STAIR', 32, 'practical', 'dsfdfdv', '2019-04-30 09:10:41'),
(14739, 'STAIR', 33, '', '', '2019-04-30 09:10:42'),
(14739, 'LOBBY', 34, '2', 'dfdvv', '2019-04-30 09:10:42'),
(14739, 'WASH', 35, '2', 'dfvd', '2019-04-30 09:10:42'),
(11525, 'STAIR', 36, 'admin', '', '2019-04-30 09:10:42'),
(11525, 'LOBBY', 37, 'admin', 'rggdvd', '2019-04-30 09:10:42'),
(11525, 'WASH', 38, '1', 'dfef', '2019-04-30 09:10:42'),
(52946, 'STAIR', 39, '', '', '2019-04-30 09:10:42'),
(52946, 'LOBBY', 40, '', '', '2019-04-30 09:10:42'),
(52946, 'WASH', 41, '', '', '2019-04-30 09:10:42'),
(92729, 'STAIR', 42, 'nhju', 'nun', '2019-04-30 10:04:21'),
(92729, 'LOBBY', 43, 'nhunhu', 'nhun', '2019-04-30 10:04:21'),
(92729, 'WASH', 44, 'nhun', 'nhu', '2019-04-30 10:04:21'),
(66033, 'STAIR', 45, 'nhu', 'unhunhu', '2019-04-30 10:04:21'),
(66033, 'LOBBY', 46, 'nhu', 'nhunhu', '2019-04-30 10:04:21'),
(66033, 'WASH', 47, 'nhunhu', 'unhu', '2019-04-30 10:04:21'),
(35885, 'STAIR', 48, 'unhu', 'nhunu', '2019-04-30 10:04:21'),
(35885, 'LOBBY', 49, 'nhuu', 'nhun', '2019-04-30 10:04:21'),
(35885, 'WASH', 50, 'nhunhu', 'nhunhu', '2019-04-30 10:04:21'),
(43851, 'STAIR', 51, 'stair', 'central', '2019-05-17 15:23:30'),
(43851, 'LOBBY', 52, 'loby', 'central', '2019-05-17 15:23:30'),
(43851, 'WASH', 53, 'cental', 'centd', '2019-05-17 15:23:30'),
(40779, 'STAIR', 54, 'IITN STSIR', 'xyz', '2019-05-17 15:29:47'),
(40779, 'LOBBY', 55, 'IITN LOBY', 'xyz', '2019-05-17 15:29:47'),
(40779, 'WASH', 56, 'IITN Wahsrom', 'xyz', '2019-05-17 15:29:47'),
(62827, 'STAIR', 57, 'svstair', 'jdfhgf', '2019-07-09 05:34:23'),
(62827, 'LOBBY', 58, 'svlobby', 'jfhgjdh', '2019-07-09 05:34:23'),
(62827, 'WASH', 59, 'svwashroom', 'fgfg', '2019-07-09 05:34:23'),
(18987, 'STAIR', 60, 'foram1stair', 'gfhgfh', '2019-07-11 06:29:08'),
(18987, 'STAIR', 61, 'demo1', 'gfhgfhfg', '2019-07-11 06:29:08'),
(18987, 'LOBBY', 62, 'foram1lobby', 'fdgfdg', '2019-07-11 06:29:08'),
(18987, 'LOBBY', 63, 'fan1', 'ghfhf', '2019-07-11 06:29:08'),
(18987, 'WASH', 64, 'foram1wash', 'fgfh', '2019-07-11 06:29:08'),
(18987, 'WASH', 65, 'no', 'fgfdg', '2019-07-11 06:29:08'),
(81949, 'STAIR', 66, 'fora2stair', 'gfhf', '2019-07-11 06:29:08'),
(81949, 'STAIR', 67, 'demo2', 'gfgf', '2019-07-11 06:29:08'),
(81949, 'LOBBY', 68, 'fora2lobby', 'fdgdfg', '2019-07-11 06:29:08'),
(81949, 'LOBBY', 69, 'fan2', 'fdgdfg', '2019-07-11 06:29:08'),
(81949, 'WASH', 70, 'fora2wash', 'fdgfdg', '2019-07-11 06:29:08'),
(11040, 'WASHROOM', 92, 'sff-1', 'N/A', '2019-07-15 13:06:50'),
(11040, 'LOBBY', 91, 'sdfdsf', 'sdfsdf', '2019-07-15 13:06:50'),
(11040, 'STAIR', 90, 'ddsfsdf', 'sdfdffds', '2019-07-15 13:06:50'),
(11040, 'LOBBY', 98, 'zeus loby', 'zeus lobydes', '2019-07-16 06:10:22'),
(11040, 'STAIR', 97, 'zeus', 'zeus stair ', '2019-07-16 06:10:22'),
(11040, 'WASHROOM', 96, 'fd,hjhk1-1', 'N/A', '2019-07-16 06:07:17'),
(11040, 'LOBBY', 95, 'frfer1', 'fhjfh1', '2019-07-16 06:07:17'),
(11040, 'STAIR', 94, 'foram1', 'fhfj', '2019-07-16 06:07:17'),
(11040, 'WASHROOM', 93, 'sff-2', 'N/A', '2019-07-15 13:06:50'),
(43851, 'STAIR', 84, 'iit-ssss', 'sssssss', '2019-07-15 11:04:29'),
(43851, 'LOBBY', 85, 'iit-llllllllll', 'llllllllllllll', '2019-07-15 11:04:29'),
(43851, 'WASHROOM', 86, 'iit-wwwwwww-1', 'N/A', '2019-07-15 11:04:29'),
(62827, 'STAIR', 87, 'ftgfdgfg', 'fgfg', '2019-07-15 13:04:46'),
(62827, 'LOBBY', 88, 'dfdgd', 'fffffffffffffffffff', '2019-07-15 13:04:46'),
(62827, 'WASHROOM', 89, 'aaaaaaaaaaaaaa-1', 'N/A', '2019-07-15 13:04:46'),
(11040, 'WASHROOM', 99, 'zeus washroom-1', 'N/A', '2019-07-16 06:10:22'),
(11040, 'WASHROOM', 100, 'zeus washroom-2', 'N/A', '2019-07-16 06:10:22'),
(11040, 'WASHROOM', 101, 'zeus washroom-3', 'N/A', '2019-07-16 06:10:22'),
(11040, 'WASHROOM', 102, 'zeus washroom-4', 'N/A', '2019-07-16 06:10:22'),
(11040, 'WASHROOM', 103, 'zeus washroom-5', 'N/A', '2019-07-16 06:10:22'),
(11040, 'WASHROOM', 104, 'zeus washroom-6', 'N/A', '2019-07-16 06:10:22'),
(11040, 'WASHROOM', 105, 'zeus washroom-7', 'N/A', '2019-07-16 06:10:22');

-- --------------------------------------------------------

--
-- Table structure for table `cms_college_floor_details`
--

CREATE TABLE `cms_college_floor_details` (
  `clg_id` int(15) NOT NULL,
  `flr_id` int(15) NOT NULL,
  `floor_name` varchar(70) NOT NULL,
  `floor_des` varchar(100) NOT NULL DEFAULT 'N/A',
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_college_floor_details`
--

INSERT INTO `cms_college_floor_details` (`clg_id`, `flr_id`, `floor_name`, `floor_des`, `timestemp`) VALUES
(904629, 11040, 'firstd', 'cvc', '2019-03-12 10:23:48'),
(904629, 14074, 'seconf floor', 'seconf des', '2019-03-12 10:23:48'),
(868346, 81573, 'frist scate', 'frist scate', '2019-03-24 06:49:29'),
(868346, 15498, 'frist scatexnmhgsgdgmhffgdsgmhgfgf', 'frist scate', '2019-03-24 06:49:29'),
(508205, 49762, 'dfs', 'jdsi', '2019-04-30 07:48:35'),
(508205, 26073, 'dhhu', 'sjhfw', '2019-04-30 07:48:35'),
(508205, 38612, 'dhfsuh', 'dfsh', '2019-04-30 07:48:35'),
(508205, 69937, 'dfs', 'jdsi', '2019-04-30 07:48:35'),
(728346, 14739, 'lab', 'fgfg', '2019-04-30 09:10:41'),
(728346, 11525, 'mutimedia hall', 'for session', '2019-04-30 09:10:42'),
(728346, 52946, 'lab', 'fgfg', '2019-04-30 09:10:42'),
(52364, 92729, 'f', 'hn', '2019-04-30 10:04:21'),
(52364, 66033, 'nhu', 'nhunhu', '2019-04-30 10:04:21'),
(52364, 35885, 'nhu', 'nhunhu', '2019-04-30 10:04:21'),
(382390, 43851, 'first floor', 'xyz', '2019-05-17 15:23:30'),
(803397, 40779, 'First Floor IIT', 'xyz', '2019-05-17 15:29:47'),
(785233, 62827, 'svground', 'ghdg', '2019-07-09 05:34:23'),
(410644, 18987, 'jhjhhfgfddfghjk', 'fgfdg', '2019-07-11 06:29:08'),
(410644, 81949, 'foram2233423', 'fgdfg', '2019-07-11 06:29:08'),
(410644, 18987, 'jhjhhfgfddfghjk', 'fgfdg', '2019-07-15 10:05:51'),
(410644, 81949, 'foram2233423', 'fgdfg', '2019-07-15 10:05:51'),
(785233, 62827, '', 'N/A', '2019-07-15 10:17:50'),
(410644, 18987, 'jhjhhfgfddfghjk', 'fgfdg', '2019-07-15 10:30:03'),
(410644, 81949, 'foram2233423', 'fgdfg', '2019-07-15 10:30:03'),
(410644, 18987, 'jhjhhfgfddfghjk', 'fgfdg', '2019-07-15 10:30:03'),
(410644, 81949, 'foram2233423', 'fgdfg', '2019-07-15 10:30:03');

-- --------------------------------------------------------

--
-- Table structure for table `cms_department_room`
--

CREATE TABLE `cms_department_room` (
  `clg_id` int(50) NOT NULL,
  `flr_id` int(50) NOT NULL,
  `department_id` int(50) NOT NULL,
  `room_id` varchar(500) DEFAULT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_department_room`
--

INSERT INTO `cms_department_room` (`clg_id`, `flr_id`, `department_id`, `room_id`, `timestemp`) VALUES
(904629, 11040, 2, '21', '2019-03-16 04:41:49'),
(904629, 14074, 2, '26#@#27#@#41', '2019-03-16 04:41:49'),
(868346, 81573, 3, '44#@#45#@#46#@#47#@#48#@#49', '2019-03-24 06:51:06'),
(868346, 15498, 3, '', '2019-03-24 06:51:06');

-- --------------------------------------------------------

--
-- Table structure for table `cms_floore_details`
--

CREATE TABLE `cms_floore_details` (
  `hostel_id` int(20) NOT NULL,
  `id` int(20) NOT NULL,
  `flore_name` varchar(100) NOT NULL,
  `no_of_room` varchar(20) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `prefix` varchar(15) NOT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_floore_details`
--

INSERT INTO `cms_floore_details` (`hostel_id`, `id`, `flore_name`, `no_of_room`, `description`, `prefix`, `timestemp`) VALUES
(277, 15215, 'Ground', '11', '4', 'A', '2019-02-21 13:20:57'),
(831, 7082, 'Admin', '0', '9', 'F', '2019-05-04 09:25:49'),
(858, 86240, 'groundx', '10', 'piby180des', 'R', '2019-02-21 16:39:34'),
(858, 29331, 'firstx', '10', 'zeus', 'a', '2019-02-21 16:39:34'),
(580, 80021, 'Ground Floor', '20', '4', 'G', '2019-02-21 19:47:51'),
(913, 4934, 'Ground Floor', '20', '4', 'G', '2019-02-21 19:52:05'),
(855, 93205, 'Ground Floor', '20', '4', 'G', '2019-02-21 19:53:55'),
(352, 28229, 'Ground Floor', '20', '4', 'G', '2019-02-21 19:54:30'),
(23, 96762, 'Ground Floor', '20', '4', 'G', '2019-02-21 19:55:04'),
(676, 31254, 'Ground Floor', '20', '4', 'G', '2019-02-21 19:55:57'),
(273, 63929, '1', '30', '6', 'G', '2019-02-22 07:20:59'),
(273, 49971, '2', '32', '4', 'F', '2019-02-22 07:20:59'),
(273, 94395, '3', '32', '6', 'S', '2019-02-22 07:20:59'),
(643, 89672, 'groundx', '5', '10', 'a', '2019-02-22 11:27:31'),
(643, 1464, 'first', '5', '10', 'b', '2019-02-22 11:27:31'),
(994, 1808, 'ground', '10', '10', 'P', '2019-02-26 05:35:01'),
(994, 41688, 'first', '10', '10', 'Q', '2019-02-26 05:35:01'),
(964, 54669, 'GROUND FLOOR', '19', '4', 'A', '2019-03-01 04:47:46'),
(964, 22268, 'FIRST FLOOR', '19', '4', 'B', '2019-03-01 04:47:46'),
(964, 14850, 'SECOND FLOOR', '19', '4', 'C', '2019-03-01 04:47:46'),
(945, 56106, 'GROUND', '29', '4', 'G', '2019-03-03 06:19:55'),
(945, 84066, 'FIRST', '29', '4', 'F', '2019-03-03 06:19:55'),
(831, 75947, 'lab', '4', '9', 'L', '2019-05-04 09:25:49');

-- --------------------------------------------------------

--
-- Table structure for table `cms_groups`
--

CREATE TABLE `cms_groups` (
  `group_id` int(20) NOT NULL,
  `college_id` int(20) NOT NULL,
  `hostel_id` int(20) NOT NULL,
  `hostel_flr_id` int(20) NOT NULL,
  `group_name` varchar(70) NOT NULL,
  `total_seat_group` int(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_groups`
--

INSERT INTO `cms_groups` (`group_id`, `college_id`, `hostel_id`, `hostel_flr_id`, `group_name`, `total_seat_group`) VALUES
(127852, 868346, 273, 63929, 'first year', 23),
(1114, 868346, 273, 49971, 'xyz', 20),
(135462, 904629, 273, 63929, 'xxx', 8),
(810472, 868346, 273, 63929, 'vcv', 10),
(419387, 904629, 273, 49971, 'First Year', 110);

-- --------------------------------------------------------

--
-- Table structure for table `cms_hostel_details`
--

CREATE TABLE `cms_hostel_details` (
  `id` int(30) NOT NULL,
  `hostel_name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `timestemp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_hostel_details`
--

INSERT INTO `cms_hostel_details` (`id`, `hostel_name`, `type`, `description`, `timestemp`) VALUES
(858, 'piby180 Science', 'Boys', '439,arihant,ushna', '2019-02-21 16:39:33'),
(273, 'Huzaifa Bin yaman', 'Boys', 'Near Bab E Siddique', '2019-02-22 07:20:59'),
(643, 'arihantxxxx', 'Girls', '', '2019-02-22 11:27:31'),
(994, 'piby180eng', 'Boys', '', '2019-02-26 05:35:01'),
(964, 'HUZAIFA BIN YAMAN', 'Boys', 'PHARMECY&BUMS HOSTEL', '2019-03-01 04:47:46'),
(945, 'HAWA BIBI GIRLS HOSTEL', 'Girls', 'HOSPITAL AREA', '2019-03-03 06:19:55');

-- --------------------------------------------------------

--
-- Table structure for table `cms_hostel_flr_asset_des`
--

CREATE TABLE `cms_hostel_flr_asset_des` (
  `id` int(11) NOT NULL,
  `asset_id` varchar(50) NOT NULL,
  `des` varchar(500) DEFAULT NULL,
  `uniq_code` varchar(50) NOT NULL,
  `delete_status` int(11) NOT NULL DEFAULT '0',
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='room_asset_descirption';

--
-- Dumping data for table `cms_hostel_flr_asset_des`
--

INSERT INTO `cms_hostel_flr_asset_des` (`id`, `asset_id`, `des`, `uniq_code`, `delete_status`, `timestemp`) VALUES
(1, '85591', 'des', '123', 0, '2019-04-25 05:44:42'),
(2, '85591', 'desw', '1234', 0, '2019-04-25 05:44:42'),
(3, '2492', 'white fan', '111', 0, '2019-05-14 07:14:20'),
(4, '2492', 'black fan', '112', 0, '2019-05-14 07:14:20');

-- --------------------------------------------------------

--
-- Table structure for table `cms_hostel_room_asset`
--

CREATE TABLE `cms_hostel_room_asset` (
  `id` int(20) NOT NULL,
  `hstl_id` int(20) NOT NULL,
  `flr_id` int(20) NOT NULL,
  `room_id` int(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `asset_type` varchar(50) NOT NULL,
  `qty` int(10) NOT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_hostel_room_asset`
--

INSERT INTO `cms_hostel_room_asset` (`id`, `hstl_id`, `flr_id`, `room_id`, `name`, `asset_type`, `qty`, `timestemp`) VALUES
(85591, 858, 86240, 28, 'demo', 'Elec', 2, '2019-04-25 05:44:42'),
(2492, 858, 86240, 28, 'fan', 'Elec', 2, '2019-05-14 07:14:20');

-- --------------------------------------------------------

--
-- Table structure for table `cms_jiiu_assing_user`
--

CREATE TABLE `cms_jiiu_assing_user` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `hs_cl_id` int(10) NOT NULL,
  `hs_cl_name` varchar(200) NOT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_jiiu_assing_user`
--

INSERT INTO `cms_jiiu_assing_user` (`id`, `user_id`, `hs_cl_id`, `hs_cl_name`, `timestemp`) VALUES
(6, 2, 858, 'piby1800', '2019-04-18 13:35:37'),
(7, 2, 858, 'piby1800', '2019-04-18 13:47:28'),
(8, 2, 858, 'piby1800', '2019-04-18 13:50:59'),
(9, 2, 858, 'piby1800', '2019-04-18 13:51:06');

-- --------------------------------------------------------

--
-- Table structure for table `cms_register_students`
--

CREATE TABLE `cms_register_students` (
  `Reg_id` int(20) NOT NULL,
  `college_id` int(11) NOT NULL,
  `uid` int(25) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `mother_name` varchar(100) NOT NULL,
  `student_contact` varchar(20) NOT NULL,
  `alt_contact` varchar(20) DEFAULT NULL,
  `father_contact` varchar(20) DEFAULT NULL,
  `address` varchar(500) NOT NULL,
  `acedmic_year` varchar(10) NOT NULL,
  `hostel_leaving_date` varchar(50) NOT NULL,
  `group_id` int(10) DEFAULT NULL,
  `floor_id` varchar(20) DEFAULT NULL,
  `room_id` varchar(20) DEFAULT NULL,
  `pic_path` varchar(50) NOT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_register_students`
--

INSERT INTO `cms_register_students` (`Reg_id`, `college_id`, `uid`, `student_name`, `father_name`, `mother_name`, `student_contact`, `alt_contact`, `father_contact`, `address`, `acedmic_year`, `hostel_leaving_date`, `group_id`, `floor_id`, `room_id`, `pic_path`, `timestemp`) VALUES
(21255656, 904629, 123459, 'zeUS fhgfg', 'zeus Father', 'zeus Mother', '556956656', '5565454545', '5656656565', 'UDHNA SURAT', '2015', '<br /><b>Notice</b>:  Undefined variable: hostel_l', 135462, '49971', '165', '', '2019-04-02 09:40:52'),
(789658, 0, 2147483647, 'foram', 'xyz demo', 'demo', '8209331987', '8209331987', '8209331987', 'surat gujrat', '2003', '2015-03-15', 127852, '63929', '128', '', '2019-05-17 08:07:58'),
(2147483647, 0, 2147483647, 'rishi ', 'xyz', 'zyxy', '1234567890', '9874561230', '2589631470', 'surat gujrat', '2004', '2019-06-13', 127852, '63929', '129', '', '2019-06-07 04:48:12');

-- --------------------------------------------------------

--
-- Table structure for table `cms_room_details`
--

CREATE TABLE `cms_room_details` (
  `flore_id` int(10) NOT NULL,
  `id` int(20) NOT NULL,
  `room_no` varchar(10) NOT NULL,
  `capacity` int(8) NOT NULL DEFAULT '0',
  `occupied` varchar(10) NOT NULL DEFAULT '0',
  `description` varchar(500) DEFAULT NULL,
  `group_id` int(12) NOT NULL DEFAULT '0',
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_room_details`
--

INSERT INTO `cms_room_details` (`flore_id`, `id`, `room_no`, `capacity`, `occupied`, `description`, `group_id`, `timestemp`) VALUES
(86240, 28, 'R-1', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(86240, 29, 'R-2', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(86240, 30, 'R-3', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(86240, 31, 'R-4', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(86240, 32, 'R-5', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(86240, 33, 'R-6', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(86240, 34, 'R-7', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(86240, 35, 'R-8', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(86240, 36, 'R-9', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(86240, 37, 'R-10', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(29331, 38, 'a-1', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(29331, 39, 'a-2', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(29331, 40, 'a-3', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(29331, 41, 'a-4', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(29331, 42, 'a-5', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(29331, 43, 'a-6', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(29331, 44, 'a-7', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(29331, 45, 'a-8', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(29331, 46, 'a-9', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(29331, 47, 'a-10', 0, '0', NULL, 0, '2019-02-21 16:39:34'),
(1808, 239, 'P-10', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(49971, 160, 'F-1', 6, '0', '', 1114, '2019-02-22 07:20:59'),
(49971, 161, 'F-2', 8, '0', '', 1114, '2019-02-22 07:20:59'),
(49971, 162, 'F-3', 4, '0', '', 1114, '2019-02-22 07:20:59'),
(49971, 163, 'F-4', 4, '0', '', 419387, '2019-02-22 07:20:59'),
(49971, 164, 'F-5', 4, '0', '', 419387, '2019-02-22 07:20:59'),
(49971, 165, 'F-6', 4, '0', '', 419387, '2019-02-22 07:20:59'),
(49971, 166, 'F-7', 4, '0', '', 419387, '2019-02-22 07:20:59'),
(49971, 167, 'F-8', 4, '0', '', 419387, '2019-02-22 07:20:59'),
(49971, 168, 'F-9', 4, '0', '', 419387, '2019-02-22 07:20:59'),
(49971, 169, 'F-10', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 170, 'F-11', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 171, 'F-12', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 172, 'F-13', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 173, 'F-14', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 174, 'F-15', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 175, 'F-16', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 176, 'F-17', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 177, 'F-18', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 178, 'F-19', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 179, 'F-20', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 180, 'F-21', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 181, 'F-22', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 182, 'F-23', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 183, 'F-24', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 184, 'F-25', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 185, 'F-26', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 186, 'F-27', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 187, 'F-28', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 188, 'F-29', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 189, 'F-30', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 190, 'F-31', 4, '0', '', 0, '2019-02-22 07:20:59'),
(49971, 191, 'F-32', 4, '0', '', 0, '2019-02-22 07:20:59'),
(94395, 192, 'S-1', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 193, 'S-2', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 194, 'S-3', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 195, 'S-4', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 196, 'S-5', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 197, 'S-6', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 198, 'S-7', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 199, 'S-8', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 200, 'S-9', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 201, 'S-10', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 202, 'S-11', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 203, 'S-12', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 204, 'S-13', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 205, 'S-14', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 206, 'S-15', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 207, 'S-16', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 208, 'S-17', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 209, 'S-18', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 210, 'S-19', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 211, 'S-20', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 212, 'S-21', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 213, 'S-22', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 214, 'S-23', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 215, 'S-24', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 216, 'S-25', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 217, 'S-26', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 218, 'S-27', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 219, 'S-28', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 220, 'S-29', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 221, 'S-30', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 222, 'S-31', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(94395, 223, 'S-32', 4, '0', NULL, 0, '2019-02-22 07:20:59'),
(89672, 224, 'a-1', 10, '0', NULL, 0, '2019-02-22 11:27:31'),
(89672, 225, 'a-2', 10, '0', NULL, 0, '2019-02-22 11:27:31'),
(89672, 226, 'a-3', 10, '0', NULL, 0, '2019-02-22 11:27:31'),
(89672, 227, 'a-4', 10, '0', NULL, 0, '2019-02-22 11:27:31'),
(89672, 228, 'a-5', 10, '0', NULL, 0, '2019-02-22 11:27:31'),
(1464, 229, 'b-1', 10, '0', NULL, 0, '2019-02-22 11:27:31'),
(1464, 230, 'b-2', 10, '0', NULL, 0, '2019-02-22 11:27:31'),
(1464, 231, 'b-3', 10, '0', NULL, 0, '2019-02-22 11:27:31'),
(1464, 232, 'b-4', 10, '0', NULL, 0, '2019-02-22 11:27:31'),
(1464, 233, 'b-5', 10, '0', NULL, 0, '2019-02-22 11:27:31'),
(1808, 240, 'P-11', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(1808, 241, 'P-12', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(1808, 242, 'P-13', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(1808, 243, 'P-14', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(1808, 244, 'P-15', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(1808, 245, 'P-16', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(1808, 246, 'P-17', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(1808, 247, 'P-18', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(1808, 248, 'P-19', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(1808, 249, 'P-20', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(41688, 250, 'Q-20', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(41688, 251, 'Q-21', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(41688, 252, 'Q-22', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(41688, 253, 'Q-23', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(41688, 254, 'Q-24', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(41688, 255, 'Q-25', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(41688, 256, 'Q-26', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(41688, 257, 'Q-27', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(41688, 258, 'Q-28', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(41688, 259, 'Q-29', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(41688, 260, 'Q-30', 10, '0', NULL, 0, '2019-02-26 05:35:01'),
(54669, 261, 'A-1', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 262, 'A-2', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 263, 'A-3', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 264, 'A-4', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 265, 'A-5', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 266, 'A-6', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 267, 'A-7', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 268, 'A-8', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 269, 'A-9', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 270, 'A-10', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 271, 'A-11', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 272, 'A-12', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 273, 'A-13', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 274, 'A-14', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 275, 'A-15', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 276, 'A-16', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 277, 'A-17', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 278, 'A-18', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 279, 'A-19', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(54669, 280, 'A-20', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 281, 'B-21', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 282, 'B-22', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 283, 'B-23', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 284, 'B-24', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 285, 'B-25', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 286, 'B-26', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 287, 'B-27', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 288, 'B-28', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 289, 'B-29', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 290, 'B-30', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 291, 'B-31', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 292, 'B-32', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 293, 'B-33', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 294, 'B-34', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 295, 'B-35', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 296, 'B-36', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 297, 'B-37', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 298, 'B-38', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 299, 'B-39', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(22268, 300, 'B-40', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 301, 'C-41', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 302, 'C-42', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 303, 'C-43', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 304, 'C-44', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 305, 'C-45', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 306, 'C-46', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 307, 'C-47', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 308, 'C-48', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 309, 'C-49', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 310, 'C-50', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 311, 'C-51', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 312, 'C-52', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 313, 'C-53', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 314, 'C-54', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 315, 'C-55', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 316, 'C-56', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 317, 'C-57', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 318, 'C-58', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 319, 'C-59', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(14850, 320, 'C-60', 4, '0', NULL, 0, '2019-03-01 04:47:46'),
(56106, 321, 'G-1', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 322, 'G-2', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 323, 'G-3', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 324, 'G-4', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 325, 'G-5', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 326, 'G-6', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 327, 'G-7', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 328, 'G-8', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 329, 'G-9', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 330, 'G-10', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 331, 'G-11', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 332, 'G-12', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 333, 'G-13', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 334, 'G-14', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 335, 'G-15', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 336, 'G-16', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 337, 'G-17', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 338, 'G-18', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 339, 'G-19', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 340, 'G-20', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 341, 'G-21', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 342, 'G-22', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 343, 'G-23', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 344, 'G-24', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 345, 'G-25', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 346, 'G-26', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 347, 'G-27', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 348, 'G-28', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 349, 'G-29', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(56106, 350, 'G-30', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 351, 'F-31', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 352, 'F-32', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 353, 'F-33', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 354, 'F-34', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 355, 'F-35', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 356, 'F-36', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 357, 'F-37', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 358, 'F-38', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 359, 'F-39', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 360, 'F-40', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 361, 'F-41', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 362, 'F-42', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 363, 'F-43', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 364, 'F-44', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 365, 'F-45', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 366, 'F-46', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 367, 'F-47', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 368, 'F-48', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 369, 'F-49', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 370, 'F-50', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 371, 'F-51', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 372, 'F-52', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 373, 'F-53', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 374, 'F-54', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 375, 'F-55', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 376, 'F-56', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 377, 'F-57', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 378, 'F-58', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 379, 'F-59', 4, '0', NULL, 0, '2019-03-03 06:19:55'),
(84066, 380, 'F-60', 4, '0', NULL, 0, '2019-03-03 06:19:55');

-- --------------------------------------------------------

--
-- Table structure for table `cms_technician_register`
--

CREATE TABLE `cms_technician_register` (
  `id` int(10) NOT NULL,
  `name` varchar(70) NOT NULL,
  `uid` varchar(30) NOT NULL,
  `con` varchar(15) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `address` varchar(150) NOT NULL,
  `password` varchar(40) NOT NULL DEFAULT '123456789',
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms_technician_register`
--

INSERT INTO `cms_technician_register` (`id`, `name`, `uid`, `con`, `email`, `address`, `password`, `timestemp`) VALUES
(3408, 'gautam', '5685585', '1234567890', 'gp21@gmail.com', 'surat', '123456789', '2019-05-01 12:27:14'),
(8726, 'abuzer', '74586969655', '9173883777', '', 'my own address', '123456789', '2019-05-02 13:44:49'),
(7312, 'ansari', '45869587', '9173883777', 'gp213011@gmail.com', 'surat', '123456789', '2019-05-02 14:07:50'),
(8756, 'asdf', '55465465454', '7405305073', '', 'surat', '123456789', '2019-05-03 05:12:28'),
(7581, 'foram', '556956656', '8490866287', 'foramsavaliya98@gmail.com', 'punagam', '123456789', '2019-05-04 11:19:22'),
(9474, 'prince', '45869587', '8480866287', 'gp213011@gmail.com', 'punagam', '123456789', '2019-05-04 11:28:39'),
(9569, 'bansii', '54674645646456', '8490866287', 'foramsavaliya98@gmail.com', 'puna', '123456789', '2019-05-07 08:04:44'),
(7326, 'x emo', '123456789', '9173883777', 'gp2139011@gmail.com', 'xyz', '123456789', '2019-05-12 13:48:44'),
(3927, 'foram', '13234545', '8490866287', 'foramsavaliya@gmail.com', 'surat', '1234', '2019-07-06 08:19:25'),
(1773, 'foram', '76756456', '8490866287', 'foramsavaliya@gmail.com', 'surat', '123', '2019-07-06 11:15:28'),
(3520, 'Firdosh', '4545654656', '8238375356', 'dfd@erre.fgdfg', 'surat', '1234', '2019-07-06 11:25:50'),
(4661, 'bansi', '76576767', '2323232323', 'fgfgf@dg.hjgh', 'ghgfhgh', '1234', '2019-07-06 11:38:49'),
(255, 'hth', '454', '1212121212', 'fgfd@dfd.jkj', 'ghfg', '456', '2019-07-06 11:41:30'),
(761953, 'namrata', '32434', '0987654321', 'sfdfd@fdgfd.kk', 'surat', '1234', '2019-07-06 12:06:23'),
(113958, 'kdfjk', '34343545', '6767565654', 'dgfd@dgfd.hjhj', 'surat', '1234', '2019-07-06 12:11:37'),
(829655, 'bhavika', '2343435', '9848378474', 'dffgd@dfd.kjkhj', 'surat', '1234', '2019-07-08 04:32:12'),
(256317, 'fggfhgfh', '343543', '1234567890', 'dfgdg@tytu.hjghj', 'fgf', '123', '2019-07-08 04:38:02'),
(854341, 'rytryty', '45654656', '0978564312', 'gfhgf@dfgfd.gfhfg', 'dgdfgfd', '12345', '2019-07-08 04:39:55'),
(934227, 'weewr', '23243435', '1234556789', 'dfd@fd.jk', 'cvc', '123', '2019-07-08 04:47:59'),
(363607, 'sdgsh', '34628', '2683726432', 'hghhgf@t.ds', 'sfsg', '123', '2019-07-08 04:51:03'),
(846336, 'dgfdg', '3645', '3546354365', 'gfg@gf.f', 'dfsf', '123', '2019-07-08 04:54:21'),
(893168, 'gfsdhgf', '47567346', '6475437324', 'fvbvb@gfj.fgf', 'fdsghf', '123', '2019-07-08 04:56:44'),
(396756, 'prince', '3243435', '8490866287', 'prince@gmail.com', 'surat', '12345', '2019-07-17 08:42:17'),
(549491, 'prince savaliya', '454354', '9979575816', 'savaliya@gmail.com', 'punagam', '123456', '2019-07-17 08:47:59'),
(776281, 'ramu', '776787867', '3435435452', 'ghfg@fhtu.hh', 'puna', '9090', '2019-07-17 08:53:21'),
(31721, 'rgr', '7879', '1212121212', 'df@dgdf.hjh', 'hjgh', '123', '2019-07-17 08:56:16'),
(911711, 'rtryrty', '123434', '0909090909', 'fdgfd@dsfdg.hjgh', 'gff', '123', '2019-07-17 08:58:00'),
(107009, 'shyamu', '45454', '1234123412', 'fgd@fgdf.hjk', 'ghfg', '123', '2019-07-17 09:08:23'),
(640504, 'ramish', '43243', '1234512345', 'dfd@rytr.hjgh', 'fgbv', '123', '2019-07-17 09:09:30'),
(199281, '123djfkds', '454535', '0909090909', 'fgf@fgfh.ghg', 'gvbv', '123', '2019-07-17 09:12:07'),
(815244, '12', '56565', '1234567890', 'fhg@fgfd.jhj', 'fhfh', '1234', '2019-07-17 09:14:04'),
(344092, 'zeusfx', '45454545445', '9173883777', 'gp213011@gmail.com', 'udhna arihant complex', '5394', '2019-07-17 09:49:40'),
(644243, 'gfdg', '546456', '1111111111', 'dgd@fgdf.jjhj', 'ghg', '123', '2019-07-17 09:53:01'),
(534357, 'fffffffffffff', '354354', '1212121212', 'fgf@hgfj.hjg', 'fgf', '123', '2019-07-17 09:59:31'),
(380490, 'testing', '454545455', '9173883777', 'DF@GMAIL.COM', 'ksfkskdf', '123456', '2019-07-17 10:02:01');

-- --------------------------------------------------------

--
-- Table structure for table `college_dep_details`
--

CREATE TABLE `college_dep_details` (
  `college_id` int(15) NOT NULL,
  `id` int(15) NOT NULL,
  `dep_name` varchar(50) NOT NULL,
  `dep_desc` varchar(100) DEFAULT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `college_dep_details`
--

INSERT INTO `college_dep_details` (`college_id`, `id`, `dep_name`, `dep_desc`, `timestemp`) VALUES
(631, 929, 'computer', 'x', '2019-02-24 11:32:22');

-- --------------------------------------------------------

--
-- Table structure for table `complaint_cat`
--

CREATE TABLE `complaint_cat` (
  `id` int(20) NOT NULL,
  `cat_name` varchar(25) NOT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `complaint_cat`
--

INSERT INTO `complaint_cat` (`id`, `cat_name`, `timestemp`) VALUES
(65814, 'ELECTRICAL', '2019-04-20 07:06:26'),
(52641, 'CLEANER', '2019-04-30 12:00:18'),
(17282, 'PLUMBING', '2019-04-30 12:00:47'),
(35042, 'CARPENTRY', '2019-04-30 12:01:09'),
(31117, 'FABRICATION', '2019-04-30 12:01:28'),
(52280, 'LAUNDRY', '2019-04-30 12:01:51'),
(83937, 'PEST CONTROL', '2019-04-30 12:02:12'),
(77366, 'PAINTING', '2019-04-30 12:02:27'),
(64914, 'MOVERS AND PACKING', '2019-04-30 12:02:47'),
(21616, 'HEALTH CARE', '2019-04-30 12:03:08'),
(84266, 'CONTRACTOR', '2019-04-30 12:03:54'),
(36842, 'GARDENER', '2019-04-30 12:04:02');

-- --------------------------------------------------------

--
-- Table structure for table `guest_complainter_detail`
--

CREATE TABLE `guest_complainter_detail` (
  `id` int(10) NOT NULL,
  `guest_name` varchar(100) NOT NULL,
  `mail` varchar(50) NOT NULL DEFAULT 'N/A',
  `contect` varchar(15) NOT NULL,
  `des` varchar(700) NOT NULL DEFAULT 'N/A',
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `guest_complainter_detail`
--

INSERT INTO `guest_complainter_detail` (`id`, `guest_name`, `mail`, `contect`, `des`, `timestemp`) VALUES
(95273, 'gautam', 'gp213011@gmail.com', '9173883777', 'nothing', '2019-04-21 04:54:24');

-- --------------------------------------------------------

--
-- Table structure for table `hostel_actual_flr_asset`
--

CREATE TABLE `hostel_actual_flr_asset` (
  `id` int(15) NOT NULL,
  `hsid` int(15) NOT NULL,
  `flrid` int(15) NOT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='loby_wash_begin from hear';

--
-- Dumping data for table `hostel_actual_flr_asset`
--

INSERT INTO `hostel_actual_flr_asset` (`id`, `hsid`, `flrid`, `timestemp`) VALUES
(593010073, 945, 56106, '2019-05-31 11:42:24'),
(312525593, 945, 84066, '2019-05-31 11:42:24'),
(390734284, 945, 60728, '2019-05-31 11:42:24'),
(698995795, 945, 56106, '2019-05-31 14:16:19'),
(308999264, 945, 84066, '2019-05-31 14:16:19'),
(975311125, 945, 60728, '2019-05-31 14:16:19'),
(628370512, 945, 56106, '2019-05-31 14:18:04'),
(489284465, 945, 84066, '2019-05-31 14:18:04'),
(699905078, 945, 60728, '2019-05-31 14:18:04'),
(437567344, 273, 63929, '2019-05-31 14:43:00'),
(695728241, 273, 49971, '2019-05-31 14:43:00'),
(463111189, 273, 94395, '2019-05-31 14:43:00'),
(264136731, 858, 86240, '2019-07-15 10:25:02'),
(802838872, 858, 29331, '2019-07-15 10:25:02'),
(30123321, 643, 89672, '2019-07-16 04:24:12'),
(379439177, 643, 1464, '2019-07-16 04:24:12');

-- --------------------------------------------------------

--
-- Table structure for table `hostel_actual_flr_asset_des`
--

CREATE TABLE `hostel_actual_flr_asset_des` (
  `id` int(15) NOT NULL,
  `asset_id` int(15) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `name` varchar(70) DEFAULT NULL,
  `des` varchar(100) DEFAULT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='data connected to hostel_actual_flr_asset';

--
-- Dumping data for table `hostel_actual_flr_asset_des`
--

INSERT INTO `hostel_actual_flr_asset_des` (`id`, `asset_id`, `type`, `name`, `des`, `timestemp`) VALUES
(41, 390734284, 'STAIR', 'right side', 'xcv', '2019-05-31 11:42:24'),
(40, 312525593, 'LOBBY', 'first floor loby', 'df', '2019-05-31 11:42:24'),
(39, 312525593, 'STAIR', 'corner', 'cvc', '2019-05-31 11:42:24'),
(38, 593010073, 'LOBBY', 'ground loby', 'cvb', '2019-05-31 11:42:24'),
(37, 593010073, 'STAIR', 'ground', 'xyx', '2019-05-31 11:42:24'),
(42, 628370512, 'WASHROOM', 'p-1', NULL, '2019-05-31 14:18:04'),
(43, 628370512, 'WASHROOM', 'p-2', NULL, '2019-05-31 14:18:04'),
(44, 628370512, 'WASHROOM', 'p-3', NULL, '2019-05-31 14:18:04'),
(45, 628370512, 'WASHROOM', 'p-4', NULL, '2019-05-31 14:18:04'),
(46, 628370512, 'WASHROOM', 'p-5', NULL, '2019-05-31 14:18:04'),
(47, 437567344, 'STAIR', 'ground stair', 'xyz', '2019-05-31 14:43:00'),
(48, 437567344, 'LOBBY', 'ground loby', 'xyc', '2019-05-31 14:43:00'),
(49, 437567344, 'WASHROOM', 'g-1', NULL, '2019-05-31 14:43:00'),
(50, 437567344, 'WASHROOM', 'g-2', NULL, '2019-05-31 14:43:00'),
(51, 437567344, 'WASHROOM', 'g-3', NULL, '2019-05-31 14:43:00'),
(52, 437567344, 'WASHROOM', 'g-4', NULL, '2019-05-31 14:43:00'),
(53, 437567344, 'WASHROOM', 'g-5', NULL, '2019-05-31 14:43:00'),
(54, 437567344, 'WASHROOM', 'g-6', NULL, '2019-05-31 14:43:00'),
(55, 437567344, 'WASHROOM', 'g-7', NULL, '2019-05-31 14:43:00'),
(56, 437567344, 'WASHROOM', 'g-8', NULL, '2019-05-31 14:43:00'),
(57, 437567344, 'WASHROOM', 'g-9', NULL, '2019-05-31 14:43:00'),
(58, 437567344, 'WASHROOM', 'g-10', NULL, '2019-05-31 14:43:00'),
(59, 437567344, 'WASHROOM', 'g-11', NULL, '2019-05-31 14:43:00'),
(60, 437567344, 'WASHROOM', 'g-12', NULL, '2019-05-31 14:43:00'),
(61, 437567344, 'WASHROOM', 'g-13', NULL, '2019-05-31 14:43:00'),
(62, 437567344, 'WASHROOM', 'g-14', NULL, '2019-05-31 14:43:00'),
(63, 437567344, 'WASHROOM', 'g-15', NULL, '2019-05-31 14:43:00'),
(64, 437567344, 'WASHROOM', 'g-16', NULL, '2019-05-31 14:43:00'),
(65, 437567344, 'WASHROOM', 'g-17', NULL, '2019-05-31 14:43:00'),
(66, 437567344, 'WASHROOM', 'g-18', NULL, '2019-05-31 14:43:00'),
(67, 437567344, 'WASHROOM', 'g-19', NULL, '2019-05-31 14:43:00'),
(68, 437567344, 'WASHROOM', 'g-20', NULL, '2019-05-31 14:43:00'),
(69, 264136731, 'STAIR', 'ghfgh', 'ghgfh', '2019-07-15 10:25:02'),
(70, 264136731, 'LOBBY', 'qqqqq', 'qqq', '2019-07-15 10:25:02'),
(71, 264136731, 'WASHROOM', 'wwww-1', NULL, '2019-07-15 10:25:02'),
(72, 30123321, 'STAIR', 'hjh', NULL, '2019-07-16 04:24:12');

-- --------------------------------------------------------

--
-- Table structure for table `hostel_room_asset`
--

CREATE TABLE `hostel_room_asset` (
  `hostel_id` int(15) NOT NULL,
  `flr_id` int(15) NOT NULL,
  `floor_name` varchar(70) NOT NULL,
  `floor_des` varchar(500) NOT NULL DEFAULT 'N/A',
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hstl_flr_lby_asset`
--

CREATE TABLE `hstl_flr_lby_asset` (
  `id` int(20) NOT NULL,
  `hstl_id` int(20) NOT NULL,
  `flr_id` int(20) NOT NULL,
  `lby_wash_stair` int(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `asset_type` varchar(50) NOT NULL,
  `qty` int(20) NOT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='data connected to hostel_actual_flr_asset_des';

--
-- Dumping data for table `hstl_flr_lby_asset`
--

INSERT INTO `hstl_flr_lby_asset` (`id`, `hstl_id`, `flr_id`, `lby_wash_stair`, `name`, `asset_type`, `qty`, `timestemp`) VALUES
(8176, 945, 84066, 40, 'fan', 'Elec', 2, '2019-05-31 13:48:29'),
(38341, 273, 63929, 47, 'bulb', 'Elec', 2, '2019-05-31 14:44:13'),
(50037, 273, 63929, 47, 'tubelight', 'Elec', 2, '2019-06-01 04:47:11'),
(31150, 273, 63929, 48, 'fan', 'Elec', 1, '2019-06-01 04:49:08'),
(48497, 273, 63929, 48, 'fan', 'Elec', 1, '2019-06-01 05:33:52'),
(87019, 273, 63929, 49, 'washbasin', 'Plumbing', 2, '2019-06-03 06:14:46'),
(16734, 273, 63929, 49, 'washbasin', 'Plumbing', 2, '2019-06-06 12:45:12');

-- --------------------------------------------------------

--
-- Table structure for table `hstl_flr_lby_asset_des`
--

CREATE TABLE `hstl_flr_lby_asset_des` (
  `id` int(20) NOT NULL,
  `asset_id` int(50) NOT NULL,
  `des` varchar(200) DEFAULT NULL,
  `uniq_code` varchar(50) NOT NULL,
  `delete_status` int(2) NOT NULL DEFAULT '0',
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='data connected to hstl_flr_lby_asset';

--
-- Dumping data for table `hstl_flr_lby_asset_des`
--

INSERT INTO `hstl_flr_lby_asset_des` (`id`, `asset_id`, `des`, `uniq_code`, `delete_status`, `timestemp`) VALUES
(1, 8176, 'xc', '111', 0, '2019-05-31 13:48:29'),
(2, 8176, 'xv', '112', 0, '2019-05-31 13:48:29'),
(3, 38341, 'white bulb', '1254d', 0, '2019-05-31 14:44:13'),
(4, 38341, 'tube light', '1256', 0, '2019-05-31 14:44:13'),
(7, 50037, 'right side on stair', '7854785', 0, '2019-06-01 04:47:11'),
(8, 50037, 'left side on stair', '954745', 0, '2019-06-01 04:47:11'),
(9, 31150, 'center fan', '1254', 0, '2019-06-01 04:49:08'),
(10, 48497, 'center fan', '1254', 0, '2019-06-01 05:33:52'),
(11, 87019, 'front', '124', 0, '2019-06-03 06:14:46'),
(12, 87019, 'back side', '1254', 0, '2019-06-03 06:14:46'),
(13, 16734, 'white', '123254', 0, '2019-06-06 12:45:12'),
(14, 16734, 'black basin', '51444', 0, '2019-06-06 12:45:12');

-- --------------------------------------------------------

--
-- Table structure for table `jiiu_users`
--

CREATE TABLE `jiiu_users` (
  `id` int(20) NOT NULL,
  `user type` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `uid` varchar(20) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `contact` varchar(15) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `pass` varchar(15) NOT NULL,
  `active` int(5) NOT NULL DEFAULT '0',
  `image` varchar(10) DEFAULT NULL,
  `hs_cl_id` int(10) DEFAULT '0',
  `email` varchar(50) DEFAULT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jiiu_users`
--

INSERT INTO `jiiu_users` (`id`, `user type`, `name`, `uid`, `address`, `contact`, `user_name`, `pass`, `active`, `image`, `hs_cl_id`, `email`, `timestemp`) VALUES
(7, 'Hostel', 'gautam', '1212123', 'surat gujrat', '9173883777', 'gautam', '007', 0, NULL, 273, NULL, '2019-04-19 09:47:23'),
(8, 'College', 'abuzer', '45968558774', 'surat gujrat', '7405305073', 'abuzer', '008', 0, NULL, 904629, NULL, '2019-04-19 09:48:35'),
(9, 'College', 'NASA', '15121614013', 'Surat', '7383988833', 'nasa', 'nasa', 0, NULL, 0, NULL, '2019-04-22 05:23:47'),
(10, 'College', 'ABCD', '14254512451', 'Surat', '7405305073', 'abc1', 'sas', 0, NULL, 868346, NULL, '2019-04-27 11:31:14'),
(12, 'College', 'foram', '7365453237', 'dhfhsg', '8490866287', 'foram', 'foram', 0, NULL, 0, 'foram@gmail.com', '2019-05-03 12:10:37'),
(13, 'College', 'bansi', '837487754', 'punagam', '8490866287', 'bansi', 'bansi', 0, NULL, 0, 'bansi@gmail.com', '2019-05-03 12:14:42'),
(14, 'Hostel', 'namrata', '84758732047', 'varachha', '8490866287', 'namrata', 'namrata', 0, NULL, 994, 'foramsavaliya98@gmail.com', '2019-05-07 08:08:06'),
(15, 'College', 'foram', '5738474535', 'fgfbgfb', '8490866287', 'foram12', 'foram123', 0, NULL, 0, 'foramsavaliya98@gmail.com', '2019-05-08 06:10:41'),
(16, 'Technician', 'gautam', '5685585', 'surat', '1234567890', '12345', '123456', 0, NULL, 0, NULL, '2019-07-06 04:26:26'),
(247414, 'Technician', 'bansi', '4754675674', 'surat', '834758347756', '834758347756', '123', 0, NULL, 0, 'dhgsh@1.df', '2019-07-08 05:01:08'),
(61733, 'Admin', 'bansi', '8347387423', 'surat', '9979575816', 'bansi', 'bansi@savaliya', 0, NULL, 0, 'bansi@gmail.com', '2019-07-08 05:18:05'),
(32959, 'College', 'bhavika', '45435454', 'fgd', '8490866287', 'hghhg', '123', 0, NULL, 0, 'dg@fdgf.jhkh', '2019-07-08 05:35:35'),
(396756, 'Technician', 'prince', '3243435', 'surat', '8490866287', '8490866287', '12345', 0, NULL, 0, 'prince@gmail.com', '2019-07-17 08:42:17'),
(549491, 'Technician', 'prince savaliya', '454354', 'punagam', '9979575816', '9979575816', '123456', 0, NULL, 0, 'savaliya@gmail.com', '2019-07-17 08:47:59'),
(776281, 'Technician', 'ramu', '776787867', 'puna', '3435435452', '3435435452', '9090', 0, NULL, 0, 'ghfg@fhtu.hh', '2019-07-17 08:53:21'),
(31721, 'Technician', 'rgr', '7879', 'hjgh', '1212121212', '1212121212', '123', 0, NULL, 0, 'df@dgdf.hjh', '2019-07-17 08:56:16'),
(911711, 'Technician', 'rtryrty', '123434', 'gff', '0909090909', '0909090909', '123', 0, NULL, 0, 'fdgfd@dsfdg.hjgh', '2019-07-17 08:58:00'),
(107009, 'Technician', 'shyamu', '45454', 'ghfg', '1234123412', '1234123412', '123', 0, NULL, 0, 'fgd@fgdf.hjk', '2019-07-17 09:08:23'),
(640504, 'Technician', 'ramish', '43243', 'fgbv', '1234512345', '1234512345', '123', 0, NULL, 0, 'dfd@rytr.hjgh', '2019-07-17 09:09:30'),
(199281, 'Technician', '123djfkds', '454535', 'gvbv', '0909090909', '0909090909', '123', 0, NULL, 0, 'fgf@fgfh.ghg', '2019-07-17 09:12:07'),
(815244, 'Technician', '12', '56565', 'fhfh', '1234567890', '1234567890', '1234', 0, NULL, 0, 'fhg@fgfd.jhj', '2019-07-17 09:14:04'),
(344092, 'Technician', 'zeusfx', '45454545445', 'udhna arihant complex', '9173883777', '9173883777', '5394', 0, NULL, 0, 'gp213011@gmail.com', '2019-07-17 09:49:40'),
(644243, 'Technician', 'gfdg', '546456', 'ghg', '1111111111', '1111111111', '123', 0, NULL, 0, 'dgd@fgdf.jjhj', '2019-07-17 09:53:01'),
(534357, 'Technician', 'fffffffffffff', '354354', 'fgf', '1212121212', '1212121212', '123', 0, NULL, 0, 'fgf@hgfj.hjg', '2019-07-17 09:59:31'),
(380490, 'Technician', 'testing', '454545455', 'ksfkskdf', '9173883777', '9173883777', '123456', 0, NULL, 0, 'DF@GMAIL.COM', '2019-07-17 10:02:01'),
(30540, 'Admin', 'foram', '74637534543', 'surat', '8490866287', 'foram12', '1234', 0, NULL, 0, 'foram@gmail.com', '2019-07-20 06:01:47'),
(68026, '', 'ramu', '23243435', 'surat', '8490866287', 'ramu', '12345', 0, NULL, 0, 'ramu@gmail.com', '2019-07-20 06:33:46'),
(16349, '', 'krishna', '4857849675', 'mumbai', '7568463454', 'krishna', '1234', 0, NULL, 0, 'krishna@gmail.com', '2019-07-20 06:42:33'),
(84507, '', 'radha', '95684785', 'goa', '3748574445', 'radha', '123', 0, NULL, 0, 'radha@gmail.com', '2019-07-20 06:44:32'),
(42394, 'Admin', 'foru', '4545', '5654', '1234567890', 'fory', '123', 0, NULL, 0, '5fkjbfngjn@ccjc.fgf', '2019-07-20 07:34:20'),
(49944, 'College', 'prince', '5656', 'surat', '8490866287', 'prince', '123', 0, NULL, 904629, 'prince@gmail.com', '2019-07-22 08:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `login_state`
--

CREATE TABLE `login_state` (
  `uid` int(10) NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login_state`
--

INSERT INTO `login_state` (`uid`, `last_login`) VALUES
(1, '2019-07-30 04:23:04');

-- --------------------------------------------------------

--
-- Table structure for table `login_state1`
--

CREATE TABLE `login_state1` (
  `last_login1` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `uid1` int(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login_state1`
--

INSERT INTO `login_state1` (`last_login1`, `uid1`) VALUES
('2019-07-20 05:16:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quatation_detail`
--

CREATE TABLE `quatation_detail` (
  `quat_id` int(10) NOT NULL,
  `quat_name` varchar(100) DEFAULT NULL,
  `creat_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `qty_heading` varchar(200) NOT NULL,
  `cat_id` int(8) NOT NULL,
  `quat_status` int(2) NOT NULL DEFAULT '0',
  `final_vendor_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quatation_detail`
--

INSERT INTO `quatation_detail` (`quat_id`, `quat_name`, `creat_date`, `qty_heading`, `cat_id`, `quat_status`, `final_vendor_id`) VALUES
(1, 'Abc', '2018-12-22 05:17:32', '[Project 2]<br>Qty#%#[Stock]<br>Qty', 0, 9, NULL),
(2, 'Abcd', '2018-12-22 05:18:28', '[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 9, NULL),
(74, 's', '2018-12-23 17:40:56', '[Project 2]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 9, NULL),
(111, 'awd', '2018-12-23 19:45:39', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 9, NULL),
(110, 'awd', '2018-12-23 19:44:29', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(109, 'awd', '2018-12-23 19:43:31', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 9, NULL),
(106, 'awd', '2018-12-23 19:40:23', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 9, NULL),
(105, 'awd', '2018-12-23 19:39:32', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(107, 'awd', '2018-12-23 19:41:04', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(108, 'awd', '2018-12-23 19:42:14', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(104, 'awd', '2018-12-23 19:36:51', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 9, NULL),
(103, 'awd', '2018-12-23 19:29:12', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(102, 'awd', '2018-12-23 19:26:09', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(101, 'awd', '2018-12-23 19:25:06', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(100, 'awd', '2018-12-23 19:24:21', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(99, 'awd', '2018-12-23 19:13:37', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(98, 'awd', '2018-12-23 19:08:16', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(97, 'awd', '2018-12-23 19:02:27', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(96, 'awd', '2018-12-23 18:58:16', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(95, 'awd', '2018-12-23 18:56:43', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(93, 'awd', '2018-12-23 18:35:08', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(91, 'awd', '2018-12-23 18:29:15', '[Project 1]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(89, 'awd', '2018-12-23 18:27:48', '[Project 1]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(88, 'awd', '2018-12-23 18:26:31', '[Project 1]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(87, 'awd', '2018-12-23 18:24:45', '[Project 1]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(86, 'awd', '2018-12-23 18:21:25', '[Project 1]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(85, 'awd', '2018-12-23 18:20:10', '[Project 1]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(84, 'awd', '2018-12-23 18:17:15', '[Project 1]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(83, 'awd', '2018-12-23 18:14:48', '[Project 1]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(82, 'awd', '2018-12-23 17:57:05', '[Project 1]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(81, 'awd', '2018-12-23 17:53:26', '[Project 1]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(80, 'awd', '2018-12-23 17:51:26', '[Project 1]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(79, 'awd', '2018-12-23 17:47:48', '[Project 1]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(78, 'awd', '2018-12-23 17:46:17', '[Project 1]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(77, 'awd', '2018-12-23 17:44:53', '[Project 1]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(76, 'awd', '2018-12-23 17:44:15', '[Project 1]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(94, 'awd', '2018-12-23 18:36:58', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(92, 'awd', '2018-12-23 18:33:40', '[Project 1]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(90, 'awd', '2018-12-23 18:28:40', '[Project 1]<br>Qty#%#[Project 3]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(75, 'dadw', '2018-12-23 17:41:44', '[Project 3]<br>Qty#%#[Project 4]<br>Qty#%#[Stock]<br>Qty', 0, 0, NULL),
(112, 'awd', '2018-12-23 19:46:59', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(113, 'awd', '2018-12-23 19:48:22', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(114, 'awd', '2018-12-23 19:52:27', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(115, 'awd', '2018-12-23 19:53:21', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(116, 'awd', '2018-12-23 20:06:11', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(117, 'awd', '2018-12-23 20:08:06', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(118, 'awd', '2018-12-23 20:15:24', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(119, 'awd', '2018-12-23 20:17:36', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(120, 'awd', '2018-12-23 20:23:25', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(121, 'awd', '2018-12-23 20:24:30', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(122, 'awd', '2018-12-23 20:44:35', '[Project 1]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(123, 'awd', '2018-12-24 08:15:43', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(124, 'awd', '2018-12-24 08:16:06', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(125, 'awd', '2018-12-24 08:18:37', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(126, 'stg', '2018-12-24 10:23:56', '[Project 1]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(127, 'stg', '2018-12-24 10:26:50', '[Project 1]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(128, 'stg', '2018-12-24 10:28:11', '[Project 1]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(129, 'stg', '2018-12-24 10:31:05', '[Project 1]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(130, 'stg', '2018-12-24 10:34:10', '[Project 1]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(131, 'stg', '2018-12-24 10:38:08', '[Project 1]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(132, 'stg', '2018-12-24 10:40:29', '[Project 1]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(133, 'stg', '2018-12-24 10:43:17', '[Project 1]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(134, 'bhu', '2018-12-24 10:47:07', '[Project 2]<br>Qty', 0, 0, NULL),
(135, 'bhu', '2018-12-24 10:48:11', '[Project 2]<br>Qty', 0, 0, NULL),
(136, 'bhu', '2018-12-24 10:49:02', '[Project 2]<br>Qty', 0, 0, NULL),
(137, 'bhu', '2018-12-24 10:49:51', '[Project 2]<br>Qty', 0, 0, NULL),
(138, 'bhu', '2018-12-24 10:50:19', '[Project 2]<br>Qty', 0, 0, NULL),
(139, 'bhu', '2018-12-24 10:55:14', '[Project 2]<br>Qty', 0, 0, NULL),
(140, 'bhu', '2018-12-24 10:58:51', '[Project 2]<br>Qty', 0, 0, NULL),
(141, 'Abc', '2018-12-24 11:04:05', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Project 4]<br>Qty', 0, 0, NULL),
(142, 'awd', '2018-12-24 11:06:08', '[Stock]<br>Qty', 0, 0, NULL),
(143, 'Abc', '2018-12-24 11:08:46', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty', 0, 0, NULL),
(144, 'zxzxcz', '2018-12-24 11:10:15', '[Stock]<br>Qty', 0, 0, NULL),
(145, 'zxzxcz', '2018-12-24 11:10:24', '[Stock]<br>Qty', 0, 0, NULL),
(146, 'awd', '2018-12-24 11:12:12', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(147, 'awd', '2018-12-24 11:44:38', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(148, 'awd', '2018-12-24 11:45:42', '[Project 2]<br>Qty', 0, 0, NULL),
(149, 'dtfh', '2018-12-24 11:47:12', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(150, 'sefse', '2018-12-25 10:41:17', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(151, 'electrical', '2018-12-25 11:47:10', '[Stock]<br>Qty', 0, 0, NULL),
(152, 'vhgj', '2018-12-28 07:22:18', '[Stock]<br>Qty', 0, 0, NULL),
(153, 'awd', '2018-12-31 05:33:56', '[Stock]<br>Qty', 0, 0, NULL),
(154, 'awd', '2018-12-31 05:34:18', '[Stock]<br>Qty', 0, 0, NULL),
(155, 'awd', '2018-12-31 05:39:45', '[Stock]<br>Qty', 0, 0, NULL),
(156, 'awd', '2018-12-31 05:51:52', '[Stock]<br>Qty', 0, 0, NULL),
(157, 'awd', '2018-12-31 05:51:54', '[Stock]<br>Qty', 0, 0, NULL),
(158, 'awd', '2018-12-31 05:54:28', '[Stock]<br>Qty', 0, 0, NULL),
(159, 'awd', '2018-12-31 05:56:37', '[Stock]<br>Qty', 0, 0, NULL),
(160, 'awd', '2018-12-31 05:57:34', '[Stock]<br>Qty', 0, 0, NULL),
(161, 'awd', '2018-12-31 05:58:00', '[Stock]<br>Qty', 0, 0, NULL),
(162, 'awd', '2018-12-31 05:58:53', '[Stock]<br>Qty', 0, 0, NULL),
(163, 'awd', '2018-12-31 05:59:15', '[Stock]<br>Qty', 0, 0, NULL),
(164, 'awd', '2018-12-31 06:01:08', '[Stock]<br>Qty', 0, 0, NULL),
(165, 'awd', '2018-12-31 06:03:56', '[Stock]<br>Qty', 0, 0, NULL),
(166, 'awd', '2018-12-31 07:03:20', '[Stock]<br>Qty', 0, 0, NULL),
(167, 'awd', '2018-12-31 07:05:45', '[Stock]<br>Qty', 0, 0, NULL),
(168, 'asdadasd', '2018-12-31 07:24:06', '[Stock]<br>Qty', 0, 0, NULL),
(169, 'asdadasd', '2018-12-31 07:25:42', '[Stock]<br>Qty', 0, 0, NULL),
(170, 'asdadasd', '2018-12-31 07:29:30', '[Stock]<br>Qty', 0, 0, NULL),
(171, 'asdadasd', '2018-12-31 07:32:22', '[Stock]<br>Qty', 0, 0, NULL),
(172, 'asdadasd', '2018-12-31 07:41:22', '[Stock]<br>Qty', 0, 0, NULL),
(173, 'awdsdacvdv', '2018-12-31 10:00:50', '[Project 2]<br>Qty', 0, 0, NULL),
(174, 'awdsdacvdv', '2018-12-31 10:01:10', '[Project 2]<br>Qty', 0, 0, NULL),
(175, 'awdsdacvdv', '2018-12-31 10:01:33', '[Project 2]<br>Qty', 0, 0, NULL),
(176, 'XYZ', '2018-12-31 11:15:35', '[Project 1]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(177, 'XYZ', '2018-12-31 11:25:06', '[Project 1]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(178, 'XYZ', '2018-12-31 11:29:11', '[Project 1]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(179, 'XYZ', '2018-12-31 11:30:58', '[Project 1]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(180, 'XYZ', '2018-12-31 11:32:09', '[Project 1]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(181, 'XYZ', '2018-12-31 11:32:30', '[Project 1]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(182, 'XYZ', '2018-12-31 11:33:41', '[Project 1]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(183, 'serfg', '2018-12-31 11:43:47', '[Stock]<br>Qty', 0, 0, NULL),
(184, 'serfg', '2018-12-31 11:47:51', '[Stock]<br>Qty', 0, 0, NULL),
(185, 'serfg', '2018-12-31 11:49:09', '[Stock]<br>Qty', 0, 0, NULL),
(186, 'serfg', '2018-12-31 11:53:55', '[Stock]<br>Qty', 0, 0, NULL),
(187, 'serfg', '2018-12-31 12:19:46', '[Stock]<br>Qty', 0, 0, NULL),
(188, 'serfg', '2018-12-31 12:20:58', '[Stock]<br>Qty', 0, 0, NULL),
(189, 'serfg', '2018-12-31 12:25:23', '[Stock]<br>Qty', 0, 0, NULL),
(190, 'awd', '2018-12-31 12:26:20', '[Stock]<br>Qty', 0, 0, NULL),
(191, 'awd', '2018-12-31 12:27:48', '[Stock]<br>Qty', 0, 0, NULL),
(192, 'zdfg', '2018-12-31 12:28:09', '[Stock]<br>Qty', 0, 0, NULL),
(193, 'zdfg', '2018-12-31 12:38:00', '[Stock]<br>Qty', 0, 0, NULL),
(194, 'zdfg', '2018-12-31 12:39:32', '[Stock]<br>Qty', 0, 0, NULL),
(195, 'zdfg', '2018-12-31 12:40:37', '[Stock]<br>Qty', 0, 0, NULL),
(196, 'zdfg', '2018-12-31 12:42:28', '[Stock]<br>Qty', 0, 0, NULL),
(197, 'zdfg', '2018-12-31 12:45:35', '[Stock]<br>Qty', 0, 0, NULL),
(198, 'zdfg', '2018-12-31 12:46:43', '[Stock]<br>Qty', 0, 0, NULL),
(199, 'zdfg', '2018-12-31 12:49:55', '[Stock]<br>Qty', 0, 0, NULL),
(200, 'zdfg', '2018-12-31 12:53:41', '[Stock]<br>Qty', 0, 0, NULL),
(201, 'zdfg', '2018-12-31 12:54:21', '[Stock]<br>Qty', 0, 0, NULL),
(202, 'ada', '2019-01-01 10:24:59', '[Stock]<br>Qty', 0, 0, NULL),
(203, 'sdfa', '2019-01-01 14:26:48', '[Stock]<br>Qty', 0, 0, NULL),
(204, 'asdf', '2019-01-03 03:55:13', '[Stock]<br>Qty', 0, 0, NULL),
(205, 'asdf', '2019-01-03 04:12:51', '[Stock]<br>Qty', 0, 0, NULL),
(206, 'asdf', '2019-01-03 04:14:43', '[Stock]<br>Qty', 0, 0, NULL),
(207, 'asdf', '2019-01-03 04:16:17', '[Stock]<br>Qty', 0, 0, NULL),
(208, 'asdf', '2019-01-03 04:17:56', '[Stock]<br>Qty', 0, 0, NULL),
(209, 'asdf', '2019-01-03 04:22:47', '[Stock]<br>Qty', 0, 0, NULL),
(210, 'asdf', '2019-01-03 04:24:09', '[Stock]<br>Qty', 0, 0, NULL),
(211, 'asdf', '2019-01-03 04:25:02', '[Stock]<br>Qty', 0, 0, NULL),
(212, 'asdf', '2019-01-03 04:25:41', '[Stock]<br>Qty', 0, 0, NULL),
(213, 'asdf', '2019-01-03 04:29:13', '[Stock]<br>Qty', 0, 0, NULL),
(214, 'asdf', '2019-01-03 04:31:09', '[Stock]<br>Qty', 0, 0, NULL),
(215, 'asf', '2019-01-03 04:32:25', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Project 4]<br>Qty', 0, 0, NULL),
(216, 'asfa', '2019-01-03 05:13:08', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty-infx-[Project 3]<br>Qty', 0, 0, NULL),
(217, 'adsr', '2019-01-05 09:38:33', '[Stock]<br>Qty', 0, 0, NULL),
(218, 'ddvg', '2019-01-10 17:03:24', '[Stock]<br>Qty', 0, 0, NULL),
(219, 'jamia store', '2019-01-11 16:50:03', '[Stock]<br>Qty', 0, 0, NULL),
(220, 'drt6ud', '2019-01-16 06:35:18', '[Stock]<br>Qty', 0, 0, NULL),
(221, 'drt6ud', '2019-01-16 06:52:01', '[Stock]<br>Qty', 0, 0, NULL),
(222, 'drt6ud', '2019-01-16 06:54:19', '[Stock]<br>Qty', 0, 0, NULL),
(223, 'drt6ud', '2019-01-16 06:54:53', '[Stock]<br>Qty', 0, 0, NULL),
(224, 'drt6ud', '2019-01-16 06:56:36', '[Stock]<br>Qty', 0, 0, NULL),
(225, 'adws', '2019-01-17 03:00:23', '[Stock]<br>Qty', 0, 0, NULL),
(226, 'adws', '2019-01-17 03:13:11', '[Stock]<br>Qty', 0, 0, NULL),
(227, 'jamia', '2019-01-18 04:14:10', '[Stock]<br>Qty', 0, 0, NULL),
(228, 'adag', '2019-01-18 06:09:10', '[Stock]<br>Qty', 0, 0, NULL),
(229, 'adag', '2019-01-18 06:10:16', '[Stock]<br>Qty', 0, 0, NULL),
(230, 'adag', '2019-01-18 06:10:58', '[Stock]<br>Qty', 0, 0, NULL),
(231, 'adag', '2019-01-18 06:21:58', '[Stock]<br>Qty', 0, 0, NULL),
(232, 'adag', '2019-01-18 06:31:01', '[Stock]<br>Qty', 0, 0, NULL),
(233, 'adag', '2019-01-18 06:31:46', '[Stock]<br>Qty', 0, 0, NULL),
(234, 'jhgjhgjhjhg', '2019-01-18 09:23:21', '[Stock]<br>Qty', 0, 0, NULL),
(235, 'awd', '2019-01-18 09:29:56', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 0, 0, NULL),
(236, 'awd', '2019-01-18 09:33:56', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 0, 0, NULL),
(237, 'awd', '2019-01-18 09:35:09', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 0, 0, NULL),
(238, 'awd', '2019-01-18 09:42:07', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 0, 0, NULL),
(239, 'awd', '2019-01-18 09:43:26', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 0, 0, NULL),
(240, 'awd', '2019-01-18 09:44:21', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 0, 0, NULL),
(241, 'awd', '2019-01-18 09:50:21', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 0, 0, NULL),
(242, 'awd', '2019-01-18 09:51:07', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 0, 0, NULL),
(243, 'awd', '2019-01-18 09:52:06', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 0, 0, NULL),
(244, 'awd', '2019-01-18 09:54:01', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 0, 0, NULL),
(245, 'awd', '2019-01-18 10:02:58', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 0, 0, NULL),
(246, 'awd', '2019-01-18 10:12:39', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 0, 0, NULL),
(247, 'awd', '2019-01-18 10:14:52', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 0, 0, NULL),
(248, 'awd', '2019-01-18 10:15:38', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 0, 0, NULL),
(249, 'awd', '2019-01-18 10:16:12', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 0, 0, NULL),
(250, 'dfsg', '2019-01-18 12:21:44', '[Stock]<br>Qty', 0, 0, NULL),
(251, 'dfsg', '2019-01-18 12:22:47', '[Stock]<br>Qty', 0, 0, NULL),
(252, 'kdpadf', '2019-01-18 12:27:01', '[Stock]<br>Qty', 0, 0, NULL),
(253, 'dfsg', '2019-01-18 12:27:10', '[Stock]<br>Qty', 0, 0, NULL),
(254, 'dfsg', '2019-01-18 12:27:53', '[Stock]<br>Qty', 0, 0, NULL),
(255, 'dfsg', '2019-01-18 12:31:37', '[Stock]<br>Qty', 0, 0, NULL),
(256, 'sdfg', '2019-01-18 12:38:56', '[Stock]<br>Qty', 0, 0, NULL),
(257, 'sdfg', '2019-01-18 12:42:18', '[Stock]<br>Qty', 0, 0, NULL),
(258, 'dfsg', '2019-01-18 12:43:11', '[Stock]<br>Qty', 0, 0, NULL),
(259, 'asfa', '2019-01-18 12:43:37', '[Stock]<br>Qty', 0, 0, NULL),
(260, 'sdfg', '2019-01-18 12:43:45', '[Stock]<br>Qty', 0, 0, NULL),
(261, 'asfa', '2019-01-18 12:54:00', '[Stock]<br>Qty', 0, 0, NULL),
(262, 'asfa', '2019-01-18 12:58:16', '[Stock]<br>Qty', 0, 0, NULL),
(263, 'asfa', '2019-01-18 13:09:04', '[Stock]<br>Qty', 0, 0, NULL),
(264, 'asfa', '2019-01-18 13:10:42', '[Stock]<br>Qty', 0, 0, NULL),
(265, 'asfa', '2019-01-18 13:17:26', '[Stock]<br>Qty', 0, 0, NULL),
(266, 'asfa', '2019-01-18 13:18:31', '[Stock]<br>Qty', 0, 0, NULL),
(267, 'asfa', '2019-01-18 13:20:36', '[Stock]<br>Qty', 0, 0, NULL),
(268, 'assalam', '2019-01-18 16:28:55', '[Stock]<br>Qty', 0, 0, NULL),
(269, 'jk,', '2019-01-19 14:15:50', '[Stock]<br>Qty', 0, 0, NULL),
(270, 'rty', '2019-01-19 14:27:52', '[Stock]<br>Qty', 0, 0, NULL),
(271, 'adas', '2019-01-19 14:35:32', '[Stock]<br>Qty', 0, 0, NULL),
(272, 'adas', '2019-01-19 14:39:26', '[Stock]<br>Qty', 0, 0, NULL),
(273, 'fgghfx', '2019-01-19 14:39:38', '[Stock]<br>Qty', 0, 0, NULL),
(274, 'fgghfx', '2019-01-19 14:39:53', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(275, 'fgghfx', '2019-01-19 14:48:06', '[Project 3]<br>Qty', 0, 0, NULL),
(276, 'fgghfx', '2019-01-19 14:48:27', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(277, 'fgghfx', '2019-01-19 14:48:42', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(278, 'asd', '2019-01-19 14:51:42', '[Stock]<br>Qty', 0, 0, NULL),
(279, 'awd', '2019-01-19 14:52:12', '[Stock]<br>Qty', 0, 0, NULL),
(280, 'awd', '2019-01-19 14:57:47', '[Stock]<br>Qty', 0, 0, NULL),
(281, 'awd', '2019-01-19 15:21:41', '[Stock]<br>Qty', 0, 0, NULL),
(282, 'awd', '2019-01-19 15:22:41', '[Stock]<br>Qty', 0, 0, NULL),
(283, 'awd', '2019-01-19 15:23:20', '[Stock]<br>Qty', 0, 0, NULL),
(284, 'awd', '2019-01-19 15:24:05', '[Stock]<br>Qty', 0, 0, NULL),
(285, 'awd', '2019-01-19 15:29:09', '[Stock]<br>Qty', 0, 0, NULL),
(286, 'awd', '2019-01-19 15:33:08', '[Stock]<br>Qty', 0, 0, NULL),
(287, 'awd', '2019-01-19 15:34:05', '[Stock]<br>Qty', 0, 0, NULL),
(288, 'awd', '2019-01-19 15:42:18', '[Stock]<br>Qty', 0, 0, NULL),
(289, 'ghfhg', '2019-01-19 17:34:35', '[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(290, 'Abuzer Qit', '2019-01-19 17:37:46', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 0, 0, NULL),
(291, 'hi', '2019-01-19 18:01:00', '[Stock]<br>Qty', 0, 0, NULL),
(292, 'asef', '2019-01-21 05:33:57', '[Stock]<br>Qty', 0, 0, NULL),
(293, 'asef', '2019-01-21 05:34:33', '[Stock]<br>Qty', 0, 0, NULL),
(294, 'asef', '2019-01-21 05:34:57', '[Stock]<br>Qty', 0, 0, NULL),
(295, 'asef', '2019-01-21 05:35:30', '[Stock]<br>Qty', 0, 0, NULL),
(296, 'asef', '2019-01-21 05:35:47', '[Stock]<br>Qty', 0, 0, NULL),
(297, 'asef', '2019-01-21 05:36:36', '[Stock]<br>Qty', 0, 0, NULL),
(298, 'asef', '2019-01-21 05:36:55', '[Stock]<br>Qty', 0, 0, NULL),
(299, 'asef', '2019-01-21 05:37:10', '[Stock]<br>Qty', 0, 0, NULL),
(300, 'asef', '2019-01-21 05:40:11', '[Stock]<br>Qty', 0, 0, NULL),
(301, 'asef', '2019-01-21 05:40:57', '[Stock]<br>Qty', 0, 0, NULL),
(302, 'asef', '2019-01-21 06:10:19', '[Stock]<br>Qty', 0, 0, NULL),
(303, 'asef', '2019-01-21 06:10:48', '[Stock]<br>Qty', 0, 0, NULL),
(304, 'asef', '2019-01-21 06:11:24', '[Stock]<br>Qty', 0, 0, NULL),
(305, 'asef', '2019-01-21 06:12:08', '[Stock]<br>Qty', 0, 0, NULL),
(306, 'asef', '2019-01-21 06:12:56', '[Stock]<br>Qty', 0, 0, NULL),
(307, 'asef', '2019-01-21 06:14:40', '[Stock]<br>Qty', 0, 0, NULL),
(308, 'asef', '2019-01-21 06:16:41', '[Stock]<br>Qty', 0, 0, NULL),
(309, 'asef', '2019-01-21 06:17:14', '[Stock]<br>Qty', 0, 0, NULL),
(310, 'asef', '2019-01-21 06:21:19', '[Stock]<br>Qty', 0, 0, NULL),
(311, 'asef', '2019-01-21 06:23:36', '[Stock]<br>Qty', 0, 0, NULL),
(312, 'asef', '2019-01-21 06:30:19', '[Stock]<br>Qty', 0, 0, NULL),
(313, 'asef', '2019-01-21 06:54:54', '[Stock]<br>Qty', 0, 0, NULL),
(314, 'asef', '2019-01-21 06:55:29', '[Stock]<br>Qty', 0, 0, NULL),
(315, 'assalam', '2019-01-21 16:55:41', '[Stock]<br>Qty', 0, 0, NULL),
(316, 'Hdu', '2019-01-21 17:12:44', '[Stock]<br>Qty', 0, 0, NULL),
(317, 'ghd', '2019-01-22 11:29:38', '[Stock]<br>Qty', 0, 0, NULL),
(318, 'asdad', '2019-01-22 12:20:28', '[Project 3]<br>Qty', 0, 0, NULL),
(319, 'asdad', '2019-01-22 12:22:25', '[Project 3]<br>Qty', 0, 0, NULL),
(320, 'asdad', '2019-01-22 12:25:32', '[Project 3]<br>Qty', 0, 0, NULL),
(321, 'asdad', '2019-01-22 12:39:23', '[Project 3]<br>Qty', 0, 0, NULL),
(322, 'asdad', '2019-01-22 12:49:00', '[Project 3]<br>Qty', 0, 0, NULL),
(323, 'rt7u', '2019-01-23 09:39:22', '[Stock]<br>Qty', 0, 0, NULL),
(324, 'asd', '2019-01-23 10:37:13', '[Project 1]<br>Qty', 0, 0, NULL),
(325, 'asd', '2019-01-23 10:38:43', '[Project 1]<br>Qty', 0, 0, NULL),
(326, 'asd', '2019-01-23 10:39:46', '[Project 1]<br>Qty', 0, 0, NULL),
(327, 'asd', '2019-01-23 10:41:36', '[Project 1]<br>Qty', 0, 0, NULL),
(328, 'asd', '2019-01-23 10:42:00', '[Project 1]<br>Qty', 0, 0, NULL),
(329, 'assalam', '2019-01-23 16:22:19', '[Stock]<br>Qty', 0, 0, NULL),
(330, 'asda', '2019-01-24 05:43:39', '[Stock]<br>Qty', 0, 0, NULL),
(331, 'asda', '2019-01-24 06:15:05', '[Stock]<br>Qty', 0, 0, NULL),
(332, 'mnnsc', '2019-01-26 04:52:17', '[Stock]<br>Qty', 0, 0, NULL),
(333, 's', '2019-01-27 14:51:15', '[Stock]<br>Qty', 0, 0, NULL),
(334, 'Gt', '2019-01-27 14:56:09', '[Stock]<br>Qty', 0, 0, NULL),
(335, 'hi', '2019-01-27 16:24:07', '[Stock]<br>Qty', 0, 0, NULL),
(336, 'awd', '2019-01-28 05:49:01', '[Stock]<br>Qty', 0, 0, NULL),
(337, 'awd', '2019-01-28 05:53:03', '[Stock]<br>Qty', 0, 0, NULL),
(338, 'awd', '2019-01-28 05:53:26', '[Stock]<br>Qty', 0, 0, NULL),
(339, 'awd', '2019-01-28 05:54:30', '[Stock]<br>Qty', 0, 0, NULL),
(340, 'awd', '2019-01-28 05:55:33', '[Stock]<br>Qty', 0, 0, NULL),
(341, 'awd', '2019-01-28 05:57:57', '[Stock]<br>Qty', 0, 0, NULL),
(342, 'awd', '2019-01-28 05:59:33', '[Stock]<br>Qty', 0, 0, NULL),
(343, 'adw', '2019-01-30 06:19:37', '[Project 2]<br>Qty', 0, 0, NULL),
(344, 'assalam', '2019-01-30 13:28:44', '[Stock]<br>Qty', 0, 0, NULL),
(345, 'HI', '2019-02-01 06:30:37', '[Stock]<br>Qty', 0, 0, NULL),
(346, 'adw', '2019-02-01 06:49:58', '[Project 3]<br>Qty', 0, 0, NULL),
(347, 'awd', '2019-02-01 06:58:11', '[Stock]<br>Qty', 2, 0, NULL),
(348, 'awd', '2019-02-01 07:05:58', '[Stock]<br>Qty', 2, 0, NULL),
(349, 'awd', '2019-02-01 07:06:48', '[Stock]<br>Qty', 2, 0, NULL),
(350, 'awd', '2019-02-01 07:09:47', '[Stock]<br>Qty', 2, 0, NULL),
(351, 'awd', '2019-02-01 07:10:36', '[Project 1]<br>Qty-infx-[Stock]<br>Qty', 1, 0, NULL),
(352, 'zsdv', '2019-02-01 07:10:51', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 1, 0, NULL),
(353, 'zsdv', '2019-02-01 07:12:29', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 1, 0, NULL),
(354, 'zsdv', '2019-02-01 07:20:53', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 1, 0, NULL),
(355, 'dfg', '2019-02-01 07:39:26', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(356, 'dfg', '2019-02-01 07:40:08', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(357, 'dfg', '2019-02-01 07:41:41', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(358, 'dfg', '2019-02-01 07:42:27', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(359, 'dfg', '2019-02-01 07:43:06', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(360, 'dfg', '2019-02-01 07:47:12', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(361, 'dfg', '2019-02-01 07:48:16', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(362, 'dfg', '2019-02-01 07:48:50', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(363, 'dfg', '2019-02-01 07:50:22', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(364, 'dfg', '2019-02-01 07:52:40', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(365, 'dfg', '2019-02-01 07:53:40', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(366, 'dfg', '2019-02-01 07:54:56', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(367, 'dfg', '2019-02-01 07:56:43', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(368, 'dfg', '2019-02-01 07:57:19', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(369, 'dfg', '2019-02-01 07:58:07', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(370, 'dfg', '2019-02-01 07:58:33', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(371, 'dfg', '2019-02-01 07:59:45', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(372, 'dfg', '2019-02-01 08:03:29', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(373, 'dfg', '2019-02-01 08:05:22', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(374, 'dfg', '2019-02-01 08:07:00', '[Project 2]<br>Qty-infx-[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(375, 'awd', '2019-02-02 11:03:35', '[Project 3]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(376, 'dgs', '2019-02-02 11:04:24', '[Project 3]<br>Qty-infx-[Project 4]<br>Qty', 2, 0, NULL),
(377, 'sdfg', '2019-02-03 04:18:12', '[Project 3]<br>Qty-infx-[Project 4]<br>Qty', 1, 0, NULL),
(378, 'sdfg', '2019-02-03 04:23:04', '[Project 3]<br>Qty-infx-[Project 4]<br>Qty', 1, 0, NULL),
(379, 'sdfg', '2019-02-03 04:26:30', '[Project 3]<br>Qty-infx-[Project 4]<br>Qty', 1, 0, NULL),
(380, 'sdfg', '2019-02-03 04:41:36', '[Project 3]<br>Qty-infx-[Project 4]<br>Qty', 1, 0, NULL),
(381, 'sdfg', '2019-02-03 04:45:58', '[Project 3]<br>Qty-infx-[Project 4]<br>Qty', 1, 0, NULL),
(382, 'sdfg', '2019-02-03 04:49:08', '[Project 3]<br>Qty-infx-[Project 4]<br>Qty', 1, 0, NULL),
(383, 'asdads', '2019-02-03 04:53:31', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 1, 0, NULL),
(384, 'asdads', '2019-02-03 05:07:05', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 1, 0, NULL),
(385, 'asdads', '2019-02-03 05:08:54', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 1, 0, NULL),
(386, 'asdads', '2019-02-03 05:27:43', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 1, 0, NULL),
(387, 'asdads', '2019-02-03 05:42:32', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty', 1, 0, NULL),
(388, 'sf', '2019-02-03 06:34:41', '[Stock]<br>Qty', 1, 0, NULL),
(389, 'asdf', '2019-02-03 07:34:41', '[Project 4]<br>Qty', 1, 0, NULL),
(390, 'asdf', '2019-02-03 07:35:35', '[Project 4]<br>Qty', 1, 0, NULL),
(391, 'asdf', '2019-02-03 07:38:36', '[Project 4]<br>Qty', 1, 0, NULL),
(392, 'asdf', '2019-02-03 07:42:21', '[Project 4]<br>Qty', 1, 0, NULL),
(393, 'asdf', '2019-02-03 07:43:45', '[Project 4]<br>Qty', 1, 0, NULL),
(394, 'asdf', '2019-02-03 07:47:30', '[Project 4]<br>Qty', 1, 0, NULL),
(395, 'asdf', '2019-02-03 07:54:36', '[Project 4]<br>Qty', 1, 0, NULL),
(396, 'asdf', '2019-02-03 07:56:53', '[Project 4]<br>Qty', 1, 0, NULL),
(397, 'asdf', '2019-02-03 07:59:27', '[Project 4]<br>Qty', 1, 0, NULL),
(398, 'asdf', '2019-02-03 08:00:49', '[Project 4]<br>Qty', 1, 0, NULL),
(399, 'asdf', '2019-02-03 08:01:14', '[Project 4]<br>Qty', 1, 0, NULL),
(400, 'asdf', '2019-02-03 08:01:39', '[Project 4]<br>Qty', 1, 0, NULL),
(401, 'asdf', '2019-02-03 08:03:38', '[Project 4]<br>Qty', 1, 0, NULL),
(402, 'asdf', '2019-02-03 08:07:50', '[Project 4]<br>Qty', 1, 0, NULL),
(403, 'asdf', '2019-02-03 08:11:09', '[Project 4]<br>Qty', 1, 0, NULL),
(404, 'asdf', '2019-02-03 08:12:28', '[Project 4]<br>Qty', 1, 0, NULL),
(405, 'asdf', '2019-02-03 08:14:28', '[Project 4]<br>Qty', 1, 0, NULL),
(406, 'asdf', '2019-02-03 08:15:29', '[Project 4]<br>Qty', 1, 0, NULL),
(407, 'asdf', '2019-02-03 08:19:53', '[Project 4]<br>Qty', 1, 0, NULL),
(408, 'asdf', '2019-02-03 08:24:13', '[Project 4]<br>Qty', 1, 0, NULL),
(409, 'asdf', '2019-02-03 08:29:33', '[Project 4]<br>Qty', 1, 0, NULL),
(410, 'awd', '2019-02-03 10:03:56', '[Stock]<br>Qty', 1, 0, NULL),
(411, 'awd', '2019-02-03 13:13:44', '[Stock]<br>Qty', 2, 0, NULL),
(412, 'awd', '2019-02-03 13:20:28', '[Stock]<br>Qty', 2, 0, NULL),
(413, 'awd', '2019-02-03 13:21:58', '[Stock]<br>Qty', 2, 0, NULL),
(414, 'awd', '2019-02-03 13:22:51', '[Stock]<br>Qty', 2, 0, NULL),
(415, 'awd', '2019-02-03 13:34:22', '[Stock]<br>Qty', 2, 0, NULL),
(416, 'awd', '2019-02-03 13:37:09', '[Stock]<br>Qty', 2, 0, NULL),
(417, 'awd', '2019-02-03 13:39:16', '[Stock]<br>Qty', 2, 0, NULL),
(418, 'awd', '2019-02-03 13:39:41', '[Stock]<br>Qty', 2, 0, NULL),
(419, 'awd', '2019-02-03 13:42:51', '[Stock]<br>Qty', 2, 0, NULL),
(420, 'awd', '2019-02-03 13:49:36', '[Stock]<br>Qty', 2, 0, NULL),
(421, 'awd', '2019-02-03 13:52:01', '[Stock]<br>Qty', 2, 0, NULL),
(422, 'awd', '2019-02-03 13:52:25', '[Stock]<br>Qty', 2, 0, NULL),
(423, 'awd', '2019-02-03 14:14:01', '[Stock]<br>Qty', 2, 0, NULL),
(424, 'awd', '2019-02-03 14:18:20', '[Stock]<br>Qty', 2, 0, NULL),
(425, 'awd', '2019-02-03 14:19:09', '[Stock]<br>Qty', 2, 0, NULL),
(426, 'awd', '2019-02-03 14:21:48', '[Stock]<br>Qty', 2, 0, NULL),
(427, 'awd', '2019-02-03 14:23:28', '[Stock]<br>Qty', 2, 0, NULL),
(428, 'awd', '2019-02-03 14:25:20', '[Stock]<br>Qty', 2, 0, NULL),
(429, 'awd', '2019-02-03 14:29:43', '[Stock]<br>Qty', 2, 0, NULL),
(430, 'awd', '2019-02-03 14:30:08', '[Stock]<br>Qty', 2, 0, NULL),
(431, 'awd', '2019-02-03 14:30:46', '[Stock]<br>Qty', 2, 0, NULL),
(432, 'awd', '2019-02-03 14:32:10', '[Stock]<br>Qty', 2, 0, NULL),
(433, 'awd', '2019-02-03 14:33:03', '[Stock]<br>Qty', 2, 0, NULL),
(434, 'awd', '2019-02-03 14:35:12', '[Stock]<br>Qty', 2, 0, NULL),
(435, 'awd', '2019-02-03 14:40:20', '[Stock]<br>Qty', 2, 0, NULL),
(436, 'awd', '2019-02-03 14:41:37', '[Stock]<br>Qty', 2, 0, NULL),
(437, 'awd', '2019-02-03 14:42:46', '[Stock]<br>Qty', 2, 0, NULL),
(438, 'awd', '2019-02-03 14:46:25', '[Stock]<br>Qty', 2, 0, NULL),
(439, 'awd', '2019-02-03 14:47:09', '[Stock]<br>Qty', 2, 0, NULL),
(440, 'awd', '2019-02-03 14:49:30', '[Stock]<br>Qty', 2, 0, NULL),
(441, 'awd', '2019-02-03 14:50:27', '[Stock]<br>Qty', 2, 0, NULL),
(442, 'asda', '2019-02-04 04:06:55', '[Project 1]<br>Qty-infx-[Project 2]<br>Qty-infx-[Stock]<br>Qty', 2, 0, NULL),
(443, 'adw', '2019-02-04 05:11:36', '[Stock]<br>Qty', 2, 0, NULL),
(444, 'adw', '2019-02-04 05:11:52', '[Stock]<br>Qty', 2, 0, NULL),
(445, 'asf', '2019-02-04 06:00:51', '[Project 3]<br>Qty', 1, 0, NULL),
(446, 'asf', '2019-02-04 06:03:42', '[Project 3]<br>Qty', 1, 0, NULL),
(447, 'asf', '2019-02-04 06:07:28', '[Project 3]<br>Qty', 1, 0, NULL),
(448, 'asf', '2019-02-04 06:08:47', '[Project 3]<br>Qty', 1, 0, NULL),
(449, 'asf', '2019-02-04 06:10:37', '[Project 3]<br>Qty', 1, 0, NULL),
(450, 'asf', '2019-02-04 06:11:28', '[Project 3]<br>Qty', 1, 0, NULL),
(451, 'hi', '2019-02-04 14:48:05', '[Stock]<br>Qty', 1, 0, NULL),
(452, 'tt', '2019-02-10 16:53:41', '[Stock]<br>Qty', 1, 0, NULL),
(453, 'fgy', '2019-02-13 04:11:17', '[Stock]<br>Qty', 1, 0, NULL),
(454, 'fgy', '2019-02-13 04:11:44', '[Stock]<br>Qty', 1, 0, NULL),
(455, 'fgy', '2019-02-13 04:15:20', '[Stock]<br>Qty', 1, 0, NULL),
(456, 'fgy', '2019-02-13 04:17:51', '[Stock]<br>Qty', 1, 0, NULL),
(457, 'fgy', '2019-02-13 04:29:46', '[Stock]<br>Qty', 1, 0, NULL),
(458, 'fgy', '2019-02-13 04:32:59', '[Stock]<br>Qty', 1, 0, NULL),
(459, 'fgy', '2019-02-13 04:35:14', '[Stock]<br>Qty', 1, 0, NULL),
(460, 'fgy', '2019-02-13 04:35:49', '[Stock]<br>Qty', 1, 0, NULL),
(461, 'fgy', '2019-02-13 04:36:21', '[Stock]<br>Qty', 1, 0, NULL),
(462, 'fgy', '2019-02-13 04:44:36', '[Stock]<br>Qty', 1, 0, NULL),
(463, 'fgy', '2019-02-13 04:45:23', '[Stock]<br>Qty', 1, 0, NULL),
(464, 'fgy', '2019-02-13 04:46:01', '[Stock]<br>Qty', 1, 0, NULL),
(465, 'fgy', '2019-02-13 04:47:24', '[Stock]<br>Qty', 1, 0, NULL),
(466, 'fgy', '2019-02-13 04:49:17', '[Stock]<br>Qty', 1, 0, NULL),
(467, 'fgy', '2019-02-13 04:50:44', '[Stock]<br>Qty', 1, 0, NULL),
(468, 'fgy', '2019-02-13 04:51:30', '[Stock]<br>Qty', 1, 0, NULL),
(469, 'fgy', '2019-02-13 04:51:57', '[Stock]<br>Qty', 1, 0, NULL),
(470, 'fgy', '2019-02-13 04:53:42', '[Stock]<br>Qty', 1, 0, NULL),
(471, 'fgy', '2019-02-13 04:54:37', '[Stock]<br>Qty', 1, 0, NULL),
(472, 'fgy', '2019-02-13 04:54:49', '[Stock]<br>Qty', 1, 0, NULL),
(473, 'fgy', '2019-02-13 04:55:07', '[Stock]<br>Qty', 1, 0, NULL),
(474, 'fgy', '2019-02-13 04:57:29', '[Stock]<br>Qty', 1, 0, NULL),
(475, 'fgy', '2019-02-13 04:59:39', '[Stock]<br>Qty', 1, 0, NULL),
(476, 'fgy', '2019-02-13 05:00:28', '[Stock]<br>Qty', 1, 0, NULL),
(477, 'asfe', '2019-02-13 09:35:51', '[Stock]<br>Qty', 1, 0, NULL),
(478, 'Hjvff', '2019-02-16 06:36:47', '[Project 4]<br>Qty-infx-[Project 5]<br>Qty-infx-[Project 6]<br>Qty', 2, 0, NULL),
(479, 'jhuky', '2019-02-16 06:49:13', '[Stock]<br>Qty', 1, 0, NULL),
(480, 'Hdhv', '2019-02-16 06:50:06', '[Project 5]<br>Qty', 2, 0, NULL),
(481, 'sdrtgsg', '2019-02-17 07:24:41', '[Stock]<br>Qty', 2, 0, NULL),
(482, 'sdrtgsg', '2019-02-17 07:29:14', '[Stock]<br>Qty', 2, 0, NULL),
(483, 'sdrtgsg', '2019-02-17 07:30:48', '[Stock]<br>Qty', 2, 0, NULL),
(484, 'sdrtgsg', '2019-02-17 07:35:41', '[Stock]<br>Qty', 2, 0, NULL),
(485, 'sdrtgsg', '2019-02-17 07:43:39', '[Stock]<br>Qty', 2, 0, NULL),
(486, 'sdrtgsg', '2019-02-17 07:44:38', '[Stock]<br>Qty', 2, 0, NULL),
(487, 'sdrtgsg', '2019-02-17 07:46:51', '[Stock]<br>Qty', 2, 0, NULL),
(488, 'sdrtgsg', '2019-02-17 07:49:15', '[Stock]<br>Qty', 2, 0, NULL),
(489, 'awdas', '2019-02-17 07:50:43', '[Stock]<br>Qty', 3, 0, NULL),
(490, 'awdas', '2019-02-17 08:09:48', '[Stock]<br>Qty', 3, 0, NULL),
(491, 'awdas', '2019-02-17 08:10:39', '[Stock]<br>Qty', 3, 0, NULL),
(492, 'awdas', '2019-02-17 08:10:50', '[Stock]<br>Qty', 3, 0, NULL),
(493, 'asda', '2019-02-18 04:01:30', '[Stock]<br>Qty', 2, 0, NULL),
(494, 'asdad', '2019-02-18 04:34:25', '[Stock]<br>Qty', 2, 0, NULL),
(495, 'asdad', '2019-02-18 04:40:32', '[Stock]<br>Qty', 2, 0, NULL),
(496, 'asdad', '2019-02-18 04:41:04', '[Stock]<br>Qty', 2, 0, NULL),
(497, 'asdad', '2019-02-18 04:43:18', '[Stock]<br>Qty', 2, 0, NULL),
(498, 'asdad', '2019-02-18 04:43:53', '[Stock]<br>Qty', 2, 0, NULL),
(499, 'asdasd', '2019-02-18 04:45:17', '[Stock]<br>Qty', 2, 0, NULL),
(500, 'asdasd', '2019-02-18 04:45:37', '[Stock]<br>Qty', 2, 0, NULL),
(501, 'asdasd', '2019-02-18 04:45:54', '[Stock]<br>Qty', 2, 0, NULL),
(502, 'asdfa', '2019-02-18 04:53:42', '[Stock]<br>Qty', 2, 0, NULL),
(503, 'asdfa', '2019-02-18 04:54:17', '[Stock]<br>Qty', 2, 0, NULL),
(504, 'asdfa', '2019-02-18 04:54:47', '[Stock]<br>Qty', 2, 0, NULL),
(505, 'asdfa', '2019-02-18 04:55:10', '[Stock]<br>Qty', 2, 0, NULL),
(506, 'asdfa', '2019-02-18 04:55:26', '[Stock]<br>Qty', 2, 0, NULL),
(507, 'asdfa', '2019-02-18 05:08:19', '[Stock]<br>Qty', 2, 0, NULL),
(508, 'asdfa', '2019-02-18 05:09:01', '[Stock]<br>Qty', 2, 0, NULL),
(509, 'asdfa', '2019-02-18 05:09:19', '[Stock]<br>Qty', 2, 0, NULL),
(510, 'asdfa', '2019-02-18 05:09:50', '[Stock]<br>Qty', 2, 0, NULL),
(511, 'asdfa', '2019-02-18 05:11:02', '[Stock]<br>Qty', 2, 0, NULL),
(512, 'asdfa', '2019-02-18 05:11:50', '[Stock]<br>Qty', 2, 0, NULL),
(513, 'asdfa', '2019-02-18 05:16:11', '[Stock]<br>Qty', 2, 0, NULL),
(514, 'asdfa', '2019-02-18 05:18:48', '[Stock]<br>Qty', 2, 0, NULL),
(515, 'asdfa', '2019-02-18 05:19:30', '[Stock]<br>Qty', 2, 0, NULL),
(516, 'asdfa', '2019-02-18 05:21:11', '[Stock]<br>Qty', 2, 0, NULL),
(517, 'asdfa', '2019-02-18 05:21:57', '[Stock]<br>Qty', 2, 0, NULL),
(518, 'asdfa', '2019-02-18 05:22:20', '[Stock]<br>Qty', 2, 0, NULL),
(519, 'asdfa', '2019-02-18 05:23:32', '[Stock]<br>Qty', 2, 0, NULL),
(520, 'asdfa', '2019-02-18 05:24:03', '[Stock]<br>Qty', 2, 0, NULL),
(521, 'asdfa', '2019-02-18 05:24:39', '[Stock]<br>Qty', 2, 0, NULL),
(522, 'asdfa', '2019-02-18 05:24:59', '[Stock]<br>Qty', 2, 0, NULL),
(523, 'asdfa', '2019-02-18 05:25:33', '[Stock]<br>Qty', 2, 0, NULL),
(524, 'asdfa', '2019-02-18 05:26:17', '[Stock]<br>Qty', 2, 0, NULL),
(525, 'asdfa', '2019-02-18 05:27:53', '[Stock]<br>Qty', 2, 0, NULL),
(526, 'asdfa', '2019-02-18 05:28:16', '[Stock]<br>Qty', 2, 0, NULL),
(527, 'asdfa', '2019-02-18 05:28:45', '[Stock]<br>Qty', 2, 0, NULL),
(528, 'asdfa', '2019-02-18 05:32:54', '[Stock]<br>Qty', 2, 0, NULL),
(529, 'asdfa', '2019-02-18 05:35:20', '[Stock]<br>Qty', 2, 0, NULL),
(530, 'asdfa', '2019-02-18 05:39:36', '[Stock]<br>Qty', 2, 0, NULL),
(531, 'asdfa', '2019-02-18 05:40:48', '[Stock]<br>Qty', 2, 0, NULL),
(532, 'asdfa', '2019-02-18 05:41:15', '[Stock]<br>Qty', 2, 0, NULL),
(533, 'asdfa', '2019-02-18 05:42:42', '[Stock]<br>Qty', 2, 0, NULL),
(534, 'sda', '2019-02-18 05:43:51', '[Stock]<br>Qty', 2, 0, NULL),
(535, 'sda', '2019-02-18 06:03:02', '[Stock]<br>Qty', 2, 0, NULL),
(536, 'sdad', '2019-02-18 07:46:14', '[Stock]<br>Qty', 2, 0, NULL),
(537, 'sdad', '2019-02-18 07:53:28', '[Stock]<br>Qty', 2, 0, NULL),
(538, 'sfa', '2019-02-18 18:06:17', '[Stock]<br>Qty', 2, 0, NULL),
(539, 'sfa', '2019-02-18 18:07:21', '[Stock]<br>Qty', 2, 0, NULL),
(540, 'sfa', '2019-02-18 18:12:11', '[Stock]<br>Qty', 2, 0, NULL),
(541, 'awdw', '2019-02-18 18:13:13', '[Stock]<br>Qty', 2, 0, NULL),
(542, 'sdfsad', '2019-02-20 04:01:48', '[Stock]<br>Qty', 2, 0, NULL),
(543, 'sdfsad', '2019-02-20 04:03:43', '[Stock]<br>Qty', 2, 0, NULL),
(544, 'sdfsad', '2019-02-20 04:04:50', '[Stock]<br>Qty', 2, 0, NULL),
(545, 'sdfsad', '2019-02-20 04:06:19', '[Stock]<br>Qty', 2, 0, NULL),
(546, 'sdfsad', '2019-02-20 04:10:32', '[Stock]<br>Qty', 2, 0, NULL),
(547, 'dfas', '2019-02-20 05:01:24', '[Stock]<br>Qty', 2, 0, NULL),
(548, 'dfas', '2019-02-20 05:06:05', '[Stock]<br>Qty', 2, 0, NULL),
(549, 'dfas', '2019-02-20 05:08:54', '[Stock]<br>Qty', 2, 0, NULL),
(550, 'dfas', '2019-02-20 05:17:32', '[Stock]<br>Qty', 2, 0, NULL),
(551, 'dfas', '2019-02-20 05:18:10', '[Stock]<br>Qty', 2, 0, NULL),
(552, 'dfas', '2019-02-20 05:20:24', '[Stock]<br>Qty', 2, 0, NULL),
(553, 'dfas', '2019-02-20 05:21:40', '[Stock]<br>Qty', 2, 0, NULL),
(554, 'dfas', '2019-02-20 05:22:46', '[Stock]<br>Qty', 2, 0, NULL),
(555, 'dfas', '2019-02-20 05:31:57', '[Stock]<br>Qty', 2, 0, NULL),
(556, 'dfas', '2019-02-20 05:32:32', '[Stock]<br>Qty', 2, 0, NULL),
(557, 'dfas', '2019-02-20 05:32:46', '[Stock]<br>Qty', 2, 0, NULL),
(558, 'fghdfgh', '2019-02-20 06:22:24', '[Stock]<br>Qty', 2, 0, NULL),
(559, 'fghdfgh', '2019-02-20 06:44:41', '[Stock]<br>Qty', 2, 0, NULL),
(560, 'dfh', '2019-02-20 06:46:48', '[Stock]<br>Qty', 3, 0, NULL),
(561, 'dfh', '2019-02-20 06:47:04', '[Stock]<br>Qty', 3, 0, NULL),
(562, 'fghdfgh', '2019-02-20 06:47:28', '[Stock]<br>Qty', 2, 0, NULL),
(563, 'asef', '2019-02-20 06:50:16', '[Stock]<br>Qty', 2, 0, NULL),
(564, 'asef', '2019-02-20 06:50:19', '[Stock]<br>Qty', 2, 0, NULL),
(565, 'sdad', '2019-02-20 06:55:08', '[Stock]<br>Qty', 2, 0, NULL),
(566, 'sdad', '2019-02-20 06:55:14', '[Stock]<br>Qty', 2, 0, NULL),
(567, 'sdad', '2019-02-20 06:55:29', '[Stock]<br>Qty', 2, 0, NULL),
(568, 'asdad', '2019-02-20 06:58:12', '[Project 1]<br>Qty', 2, 0, NULL),
(569, 'fgdgzdrg', '2019-02-20 07:02:05', '[Stock]<br>Qty', 2, 0, NULL),
(570, 'sdfsf', '2019-02-20 07:02:32', '[Stock]<br>Qty', 2, 0, NULL),
(571, 'sdads', '2019-02-20 07:03:27', '[Stock]<br>Qty', 2, 0, NULL),
(572, 'asdasd', '2019-02-20 07:05:22', '[Project 1]<br>Qty', 2, 0, NULL),
(573, 'sdfga', '2019-02-20 07:07:23', '[Stock]<br>Qty', 2, 0, NULL),
(574, 'sdfasd', '2019-02-20 07:12:41', '[Stock]<br>Qty', 2, 0, NULL),
(575, 'awdas', '2019-02-20 07:15:12', '[Stock]<br>Qty', 3, 0, NULL),
(576, 'rgs', '2019-02-20 10:54:08', '[Stock]<br>Qty', 2, 0, NULL),
(577, 'Electrical', '2019-02-22 02:38:09', '[Stock]<br>Qty', 1, 0, NULL),
(578, 'jhuky', '2019-02-26 11:18:31', '[Stock]<br>Qty', 1, 0, NULL),
(579, 'mokk', '2019-03-01 04:43:04', '[Stock]<br>Qty', 1, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quatation_list`
--

CREATE TABLE `quatation_list` (
  `quat_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `item_company` varchar(120) NOT NULL,
  `req_qty_stock` varchar(200) DEFAULT NULL,
  `UoM` varchar(50) DEFAULT NULL,
  `req_desc` varchar(1000) DEFAULT NULL,
  `pro_bar_code` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quatation_list`
--

INSERT INTO `quatation_list` (`quat_id`, `item_id`, `item_name`, `item_company`, `req_qty_stock`, `UoM`, `req_desc`, `pro_bar_code`) VALUES
(97, 1, 'Tube Light', 'Usha', '0', 'sdv', 'N/A', NULL),
(98, 2, 'Tube Light', 'Usha', '34-xfni-N/A-xfni-N/A', '34qw', 'N/A', NULL),
(99, 3, 'Tube Light', 'Usha', '3-xfni-N/A-xfni-N/A', 'ef', 'N/A', NULL),
(101, 4, 'Fan', 'Usha', '34-xfni-N/A-xfni-N/A', 'w34r5w', 'N/A', NULL),
(102, 5, 'Tube Light', 'Apl Apollo Tubes Ltd', '23-xfni-N/A-xfni-N/A', '23', 'N/A', NULL),
(102, 6, 'fuse', 'Apl Apollo Tubes Ltd', 'N/A-xfni-34-xfni-N/A', 'df', 'N/A', NULL),
(103, 7, 'Tube Light', 'Tata Steel Ltd', 'N/A-xfni-3-xfni-N/A', 'dxdv', 'N/A', NULL),
(104, 8, 'Tube Light', 'Usha', '34-xfni-N/A-xfni-N/A', 'fes', 'N/A', NULL),
(105, 9, 'Tube Light', 'YU', '23-xfni-N/A-xfni-N/A', '214', 'N/A', NULL),
(106, 10, 'Tube Light', 'Usha', '324-xfni-N/A-xfni-N/A', 'sf', 'N/A', NULL),
(107, 11, 'Tube Light', 'Usha', '234-xfni-N/A-xfni-N/A', 'sa', 'N/A', NULL),
(108, 12, 'Tube Light', 'Usha', '23-xfni-N/A-xfni-N/A', 'FA', 'N/A', NULL),
(109, 13, 'Tube Light', 'Usha', '23-xfni-N/A-xfni-N/A', 'AD', 'N/A', NULL),
(110, 14, 'tADW', 't', '3-xfni-N/A-xfni-N/A', 'AD', 'N/A', NULL),
(111, 15, 'AWDF', 'ADW', '23-xfni-N/A-xfni-N/A', 'ADW', 'N/A', NULL),
(117, 26, 'ADW', 'AWD', '23-xfni-N/A-xfni-N/A', 'AD', 'N/A', NULL),
(118, 27, 'WDAD', 'ASDAD', '23-xfni-N/A-xfni-N/A', 'FAS', 'N/A', NULL),
(116, 25, 'SDV', 'GUYI', '12-xfni-N/A-xfni-N/A', 'SF', 'N/A', NULL),
(115, 24, 'WDAW', 'DAW', '23-xfni-N/A-xfni-N/A', 'ASF', 'N/A', NULL),
(119, 28, 'TFH', 'DTH', '54-xfni-N/A-xfni-N/A', 'DRG', 'FGH', NULL),
(120, 29, 'jhgvj', 'fgvf', '12-xfni-N/A-xfni-N/A', 'sf', 'N/A', NULL),
(121, 30, 'kugbk', 'khjuh', '12-xfni-N/A-xfni-N/A', 'ad', 'N/A', NULL),
(122, 31, 'jyf', 'yfuyfuyjfuj', '0231-xfni-N/A-xfni-N/A', 'zc', 'N/A', NULL),
(123, 32, 'sefa', 'ef', 'N/A-xfni-3-xfni-N/A', 'dsf', 'N/A', NULL),
(124, 33, 'adg', 'N/A', '-134-xfni-N/A-xfni-N/A', 'sdg', 'N/A', NULL),
(125, 34, 'sef', 'asef', '342-xfni-N/A-xfni-N/A', 'asdf', 'N/A', NULL),
(125, 35, 'dhsf', 'N/A', 'N/A-xfni-45-xfni-N/A', 'sth', 'N/A', NULL),
(126, 36, 'sehr', 'srhe', '34-xfni-N/A', 'dsrg', 'N/A', NULL),
(127, 37, 'awd', 'awd', '23-xfni-N/A', 'sad', 'N/A', NULL),
(128, 38, 'erg', 'tdh', 'N/A-xfni-5', 'dfth', 'N/A', NULL),
(129, 39, 'szf', 'zg', '34-xfni-N/A', 'dsg', 'N/A', NULL),
(130, 40, 'sfe', 'sdh', '34-xfni-N/A', 'dhb', 'N/A', NULL),
(131, 41, 'kyh', 'olihoiujh', '343-xfni-N/A', 'zdgf', 'N/A', NULL),
(132, 42, 'sef', 'dxrg', '45-xfni-N/A', 'sdfg', 'N/A', NULL),
(133, 43, 'jkh', 'khkj', '13-xfni-N/A', 'sf', 'N/A', NULL),
(134, 44, 'gvut', 'vguy', '56', 'uyfv', 'N/A', NULL),
(135, 45, 'sdfsdf', 'fsfsd', '14', 'xdfxdf', 'N/A', NULL),
(136, 46, 'dfgdfg', 'dfgdfg', '45', 'xzc', 'N/A', NULL),
(137, 47, 'dsfgdfgdg', 'dfgdfgd', '345', 'zxczxc', 'N/A', NULL),
(138, 48, 'asdasd', 'asdas', '4', 'sdas', 'N/A', NULL),
(139, 49, 'eirofopsd', 'jkdfklgjd', '454', 'dfghdfgd', 'N/A', NULL),
(140, 50, 'ghjbjkhjkh', 'jhkjhjkh', '545', 'kjhujkhjk', 'N/A', NULL),
(146, 51, 'asdasd', 'dasdasd', '87-xfni-3543-xfni-5435', 'zxczxc', 'zcx', NULL),
(148, 52, 'awd', 'sd', '23', 'aw', 'N/A', NULL),
(149, 53, 'Tube Light', 'Usha', '34-xfni-N/A-xfni-N/A', 'pc', 'N/A', NULL),
(149, 54, 'Fan', 'Tata Steel Ltd', 'N/A-xfni-45-xfni-N/A', 'pc', 'N/A', NULL),
(151, 55, 'led', 'simgma', '100', 'pc', '20w', NULL),
(188, 57, 'sdfg', 'sdfg', '4', 'dsfg', 'N/A', ''),
(188, 58, 'xcvb', 'sdfg', '3245', 'dfsg', 'N/A', ''),
(188, 59, 'sdrgd', 'hf', '5345', 'xcb', 'N/A', ''),
(189, 60, 'dfsg', 'dsfg', '345', 'dfg', 'N/A', ''),
(190, 61, 'sdfh', 'sdgf', '34', 'dfhg', 'N/A', ''),
(192, 62, 'fgyj', 'fgh', '354', 'dfg', 'N/A', '18517/00764026'),
(193, 63, 'gjdfg', 'dfh', '346', 'dfhg', 'N/A', '18517/00764026'),
(194, 64, 'tfhydrt', 'dhf', '546', 'dfgh', 'N/A', '18517/00764026'),
(195, 65, 'fyk', 'dh', '56', 'dh', 'N/A', '6934177703300'),
(196, 66, 'vh', 'dfgh', '346', 'dfh', 'N/A', '18517/00764026'),
(197, 67, 'rty', 'dfh', '35', 'fnf', 'N/A', '6934177703300'),
(198, 68, 'uy', 'dhf', '546', 'fgdh', 'dfgh', '6934177703300'),
(201, 69, 'Demo Name', 'Demo Company', '23', 'Demo Unit', 'N/A', '6934177703300'),
(201, 70, 'Demo Name', 'Demo Company', '23', 'Demo Unit', 'N/A', 'asdasdad'),
(201, 71, 'Demo Name', 'Demo Company', '123', 'Demo Unit', '234', '6934177703300'),
(201, 72, 'Demo Name', 'Demo Company', '6934177703300', 'Demo Unit', 'N/A', '1500748020909'),
(202, 73, 'dfgbdfg', 'Demo Company', '34', 'Demo Unit', 'N/A', 'awdasda'),
(203, 74, 'Demo Name', 'Demo Company', '12', 'Demo Unit', 'N/A', '8901088136945'),
(205, 75, 'awdaw', 'asdasd', '23', 'dsf', 'sety', 'awdwd'),
(206, 76, 'dfgs', 'sdfg', '234', 'dsb', 'cxvb', 'sdg'),
(207, 77, 'asdf', 'asdf', '23', 'xv', 'zxvc', 'asdf'),
(208, 78, 'asgd', 'asdf', '234', 'adf', 'asgf', 'sdaf'),
(209, 79, 'dsfgsdf', 'dsdf', '345', 'sb', 'N/A', 'sdgfds'),
(210, 80, 'asdfdasf', 'asdffdas', '24', '34sf', 'N/A', 'asdfsd'),
(211, 81, 'sdfgd', 'sdfgf', '345', 'sdbf', 'sdfg', 'sadgasdg'),
(212, 82, 'asf', 'asdf', '43', 'sfzv', 'N/A', 'asfd'),
(213, 83, 'asdf', 'asdf', '234', 'sadf', 'asdf', 'sdf'),
(214, 84, 'asfddsf', 'sadfafd', '1234', 'asdc', 'asdc', 'sdfad'),
(215, 85, 'sadf', 'asdf', '12341-xfni-234-xfni-23423', '243', 'dfv', 'asf'),
(216, 86, 'gkh', 'kjhgjhg', '45-xfni-3-xfni-3', 'fvb', 'N/A', 'jhgujyhg'),
(217, 87, 'Demo Name', 'Demo Company', '123', 'Demo Unit', 'N/A', '1452457'),
(217, 88, 'hgjhg', 'jkhgjhg', '453', 'hjgjh', 'N/A', 'jhgjhyg'),
(218, 89, 'fwvv', 'v', '1', 'kg', 'N/A', 'N/A'),
(220, 90, 'Demo Name', 'Demo Company', '23', 'Demo Unit', '23', '34534'),
(220, 91, 'Demo Name', 'Demo Company', '34', 'Demo Unit', 'N/A', 'dfsdg'),
(224, 92, 'Demo Name', 'Demo Company', '241', 'Demo Unit', 'N/A', '3213'),
(225, 93, 'Demo Name', 'Demo Company', '152', 'Demo Unit', 'N/A', '137'),
(225, 94, 'Demo Name', 'Demo Company', '15', 'Demo Unit', 'N/A', '554'),
(226, 95, 'Demo Name', 'Demo Company', '125', 'Demo Unit', 'N/A', '159'),
(227, 96, 'led', 'sigma', '11', 'pc', 'N/A', 'N/A'),
(252, 97, 'km', 'njhuahfi', '11', 'kg', 'N/A', 'kpkcv'),
(262, 98, 'Demo Name', 'N/A', '234', 'Demo Unit', 'N/A', 'awd.'),
(267, 99, 'Tube Light', 'Ujala', '123', 'PC', 'N/A', '12345'),
(267, 100, 'Fan', 'Ujala', '23', 'pc', 'N/A', '54321'),
(267, 101, 'Fan', 'Ujala', '23', 'pc', 'N/A', '54321'),
(267, 102, 'Tube Light', 'Ujala', '20', 'PC', 'N/A', '12345'),
(267, 103, 'Demo Name', 'Demo Company', '52', 'Demo Unit', 'N/A', '2154'),
(267, 104, 'Demo Name', 'Demo Company', '15', 'Demo Unit', 'N/A', '54512'),
(267, 105, 'Demo Name', 'Demo Company', '88', 'Demo Unit', 'N/A', '54546'),
(269, 106, 'Fan', 'Ujala', '21', 'pc', 'N/A', '54321'),
(269, 107, 'Fan', 'Ujala', '55', 'pc', 'N/A', '54321'),
(276, 108, 'Tube Light', 'Ujala', '23-xfni-N/A-xfni-N/A', 'PC', 'N/A', '12345'),
(277, 109, 'Tube Light', 'Ujala', '23-xfni-N/A-xfni-N/A', 'PC', 'N/A', '12345'),
(277, 110, 'Fan', 'Ujala', '34-xfni-34-xfni-N/A', 'pc', 'N/A', '54321'),
(278, 111, 'Tube Light', 'Ujala', '34', 'PC', 'N/A', '12345'),
(279, 112, 'Tube Light', 'Ujala', '3', 'PC', 'N/A', '12345'),
(279, 113, 'Fan', 'Ujala', '45', 'pc', 'N/A', '54321'),
(279, 114, 'Tube Light', 'Ujala', '22', 'PC', 'N/A', '12345'),
(280, 115, 'Tube Light', 'Ujala', '14', 'PC', 'N/A', '12345'),
(281, 116, 'Tube Light', 'Ujala', '34', 'PC', 'N/A', '12345'),
(282, 117, 'Tube Light', 'Ujala', '78', 'PC', 'N/A', '12345'),
(283, 118, 'Tube Light', 'Ujala', '23', 'PC', 'N/A', '12345'),
(284, 119, 'Tube Light', 'Ujala', '23', 'PC', 'N/A', '12345'),
(285, 120, 'Tube Light', 'Ujala', '2', 'PC', 'N/A', '12345'),
(286, 121, 'Tube Light', 'Ujala', '23', 'PC', 'N/A', '12345'),
(287, 122, 'Tube Light', 'Ujala', '231', 'PC', 'N/A', '12345'),
(288, 123, 'Tube Light', 'Ujala', '78', 'PC', 'N/A', '12345'),
(289, 124, 'Demo Name', 'Demo Company', 'N/A-xfni-12', 'Demo Unit', 'N/A', '12165'),
(289, 125, 'Fan', 'Ujala', '45-xfni-N/A', 'pc', 'N/A', '54321'),
(290, 126, 'Tube Light', 'Ujala', '12-xfni-N/A-xfni-N/A', 'PC', 'N/A', '12345'),
(301, 128, 'Fan', 'Ujala', '12', 'pc', 'N/A', '54321'),
(301, 129, 'Demo Name', 'Demo Company', '45', 'Demo Unit', 'N/A', '15425'),
(312, 130, 'Tube Light', 'Ujala', '12', 'PC', 'N/A', '12345'),
(312, 131, 'Tube Light', 'Ujala', '45', 'PC', 'N/A', '12345'),
(314, 132, 'Tube Light', 'Ujala', '12', 'PC', 'N/A', '12345'),
(315, 133, 'Fan', 'Ujala', '12', 'pc', 'N/A', '54321'),
(316, 134, 'Tube Light', 'Ujala', '25', 'PC', 'N/A', '12345'),
(317, 135, 'Fan', 'Ujala', '12', 'pc', 'N/A', '54321'),
(317, 136, 'Tube Light', 'Ujala', '12', 'PC', 'N/A', '12345'),
(321, 137, 'Tube Light', 'Ujala', '12', 'PC', 'N/A', '6040203479902'),
(323, 138, 'Tube Light', 'Ujala', '20', 'PC', 'N/A', '12345'),
(329, 139, 'Tube Light', 'Ujala', '100', 'PC', 'N/A', '12345'),
(332, 140, 'Tube Light', 'Ujala', '2', 'PC', 'N/A', '12345'),
(333, 141, 'Tube Light', 'Ujala', '12', 'PC', 'N/A', '12345'),
(333, 144, 'Tube Light', 'Ujala', '15', 'PC', 'N/A', '12345'),
(333, 143, 'Fan', 'Ujala', '15', 'pc', 'N/A', '54321'),
(335, 145, 'Tube Light', 'Ujala', '10', 'PC', 'N/A', '12345'),
(336, 146, 'Tube Light', 'Ujala', 'PC', 'N/A', '12345', ''),
(336, 147, 'Fan', 'Ujala', 'pc', 'N/A', '54321', ''),
(336, 148, 'Tube Light', 'Ujala', 'PC', 'N/A', '12345', ''),
(337, 149, 'Tube Light', 'Ujala', 'PC', 'N/A', '12345', ''),
(338, 150, 'Tube Light', 'Ujala', 'PC', 'N/A', '12345', ''),
(340, 151, 'Tube Light', 'Ujala', 'PC', 'N/A', '12345', ''),
(341, 152, 'Tube Light', 'Ujala', 'PC', 'N/A', '12345', ''),
(342, 154, 'Fan', 'Ujala', '45', 'pc', 'N/A', '54321'),
(345, 158, 'Tube Light', 'Ujala', '50', 'PC', 'N/A', '12345'),
(342, 156, 'Fan', 'Ujala', '67', 'pc', 'N/A', '54321'),
(354, 159, 'Tube Light', 'Ujala', '15-xfni-16-xfni-N/A', 'PC', 'N/A', '12345'),
(359, 160, 'Tube Light', 'Ujala', '25-xfni-65-xfni-25', 'PC', 'ljhkj', '12345'),
(362, 161, 'Fan', 'Ujala', '25-xfni-63-xfni-52', 'pc', 'N/A', '54321'),
(363, 162, 'Tube Light', 'Ujala', '25-xfni-655-xfni-25', 'PC', 'sfesfe', '12345'),
(371, 163, 'Tube Light', 'Ujala', '25-xfni-N/A-xfni-N/A', 'PC', 'N/A', '12345'),
(372, 164, 'Tube Light', 'Ujala', '25-xfni-N/A-xfni-N/A', 'PC', 'N/A', '12345'),
(373, 165, 'Tube Light', 'Ujala', '25-xfni-N/A-xfni-N/A', 'PC', 'N/A', '12345'),
(374, 166, 'Tube Light', 'Ujala', '25-xfni-N/A-xfni-N/A', 'PC', 'N/A', '12345'),
(376, 167, 'Tube Light', 'Ujala', '21-xfni-15', 'PC', '16', '12345'),
(378, 168, 'Tube Light', 'Ujala', '14-xfni-52', 'PC', '17', '12345'),
(379, 169, 'Tube Light', 'Ujala', '12-xfni-15', 'PC', 'N/A', '12345'),
(384, 171, 'Tube Light', 'Ujala', '15-xfni-12', 'PC', '13', '12345'),
(386, 175, 'Fan', 'Ujala', '12-xfni-15', 'pc', 'N/A', '54321'),
(385, 173, 'Tube Light', 'Ujala', '5-xfni-5', 'PC', 'N/A', '12345'),
(386, 176, 'Tube Light', 'Ujala', '56-xfni-45', 'PC', 'N/A', '12345'),
(387, 177, 'Tube Light', 'Ujala', '15-xfni-1550', 'PC', 'N/A', '12345'),
(451, 181, 'Tube Light', 'Ujala', '10', 'PC', 'N/A', '12345'),
(452, 182, 'Tube Light', 'Ujala', '8', 'PC', 'N/A', '12345'),
(494, 184, 'Tube Light', 'Ujala', '23', 'PC', 'N/A', '12345'),
(534, 185, 'Fan', 'Ujala', '12', 'pc', 'N/A', '54321'),
(535, 186, 'Tube Light', 'Ujala', '23', 'PC', 'N/A', '12345'),
(542, 187, 'Tube Light', 'Ujala', '12', 'PC', 'N/A', '12345'),
(543, 188, 'Tube Light', 'Ujala', '12', 'PC', 'N/A', '12345'),
(544, 189, 'Tube Light', 'Ujala', '12', 'PC', 'N/A', '12345'),
(547, 190, 'Tube Light', 'Ujala', '12', 'PC', 'N/A', '12345'),
(548, 191, 'Tube Light', 'Ujala', '12', 'PC', 'N/A', '12345'),
(549, 192, 'Tube Light', 'Ujala', '15', 'PC', 'N/A', '12345'),
(550, 193, 'Tube Light', 'Ujala', '15', 'PC', 'N/A', '12345'),
(551, 194, 'Tube Light', 'Ujala', '15', 'PC', 'N/A', '12345'),
(552, 195, 'Tube Light', 'Ujala', '12', 'PC', 'N/A', '12345'),
(553, 196, 'Tube Light', 'Ujala', '12', 'PC', 'N/A', '12345'),
(554, 197, 'Tube Light', 'Ujala', '15', 'PC', 'N/A', '12345'),
(560, 198, 'Tube Light', 'Ujala', '15', 'PC', 'N/A', '12345'),
(562, 199, 'Tube Light', 'Ujala', '12', 'PC', 'N/A', '12345'),
(569, 200, 'Tube Light', 'Ujala', '12', 'PC', 'N/A', '12345'),
(576, 202, 'Tube Light', 'Ujala', '145', 'PC', 'N/A', '12345'),
(577, 203, 'Tube Light', 'Ujala', '1', 'PC', 'Hi', '12345'),
(578, 204, 'Tube Light', 'Ujala', '20', 'PC', 'N/A', '12345'),
(579, 205, 'Tube Light', 'Ujala', '11', 'PC', 'N/A', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `quatation_responce_detail`
--

CREATE TABLE `quatation_responce_detail` (
  `quat_id` int(10) NOT NULL,
  `ven_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `avail_qty` int(10) NOT NULL,
  `delvry_date` date NOT NULL,
  `unit_price` varchar(50) NOT NULL,
  `comment` varchar(400) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quatation_sent_detail`
--

CREATE TABLE `quatation_sent_detail` (
  `quat_id` int(10) NOT NULL,
  `ven_id` int(10) NOT NULL,
  `sent_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `room_list`
--

CREATE TABLE `room_list` (
  `id` int(5) NOT NULL,
  `college_id` int(8) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `room_no` varchar(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room_list`
--

INSERT INTO `room_list` (`id`, `college_id`, `date`, `room_no`) VALUES
(1, 0, '2019-07-25 09:19:18', '165'),
(2, 0, '2019-07-25 09:46:56', '165'),
(3, 904629, '2019-07-25 09:57:55', '165'),
(4, 904629, '2019-07-25 10:46:29', ''),
(5, 904629, '2019-07-25 10:46:34', ''),
(6, 904629, '2019-07-25 10:46:38', '');

-- --------------------------------------------------------

--
-- Table structure for table `technician_exp`
--

CREATE TABLE `technician_exp` (
  `id` int(10) NOT NULL,
  `technician_id` int(15) NOT NULL,
  `cat_id` int(15) NOT NULL,
  `timestmp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `technician_exp`
--

INSERT INTO `technician_exp` (`id`, `technician_id`, `cat_id`, `timestmp`) VALUES
(79, 256317, 52641, '2019-07-08 04:38:02'),
(32, 3408, 65814, '2019-05-02 13:23:17'),
(31, 3408, 77366, '2019-05-02 13:23:17'),
(30, 3408, 31117, '2019-05-02 13:23:17'),
(78, 829655, 65814, '2019-07-08 04:32:12'),
(77, 113958, 52641, '2019-07-06 12:11:37'),
(76, 761953, 65814, '2019-07-06 12:06:23'),
(74, 4661, 17282, '2019-07-06 11:38:49'),
(73, 3520, 17282, '2019-07-06 11:25:50'),
(72, 3927, 52641, '2019-07-06 08:19:25'),
(71, 8726, 65814, '2019-06-07 04:49:35'),
(70, 8726, 21616, '2019-06-07 04:49:35'),
(69, 8726, 84266, '2019-06-07 04:49:35'),
(75, 255, 65814, '2019-07-06 11:41:30'),
(67, 7312, 35042, '2019-05-14 05:38:49'),
(66, 7312, 17282, '2019-05-14 05:38:49'),
(49, 8756, 65814, '2019-05-03 05:13:36'),
(50, 8756, 52641, '2019-05-03 05:13:36'),
(51, 8756, 17282, '2019-05-03 05:13:36'),
(52, 8756, 35042, '2019-05-03 05:13:36'),
(53, 7581, 65814, '2019-05-04 11:19:22'),
(54, 9474, 84266, '2019-05-04 11:28:39'),
(55, 9569, 21616, '2019-05-07 08:04:44'),
(57, 7326, 52641, '2019-05-12 13:48:44'),
(58, 7326, 17282, '2019-05-12 13:48:44'),
(80, 854341, 52641, '2019-07-08 04:39:55'),
(81, 934227, 65814, '2019-07-08 04:47:59'),
(82, 363607, 52641, '2019-07-08 04:51:03'),
(83, 363607, 17282, '2019-07-08 04:51:03'),
(84, 846336, 52641, '2019-07-08 04:54:21'),
(85, 846336, 17282, '2019-07-08 04:54:21'),
(86, 846336, 35042, '2019-07-08 04:54:21'),
(87, 846336, 31117, '2019-07-08 04:54:21'),
(88, 893168, 65814, '2019-07-08 04:56:44'),
(89, 893168, 52641, '2019-07-08 04:56:44'),
(90, 380477, 65814, '2019-07-08 04:59:19'),
(91, 396756, 65814, '2019-07-17 08:42:17'),
(92, 396756, 52641, '2019-07-17 08:42:17'),
(93, 396756, 17282, '2019-07-17 08:42:17'),
(94, 396756, 35042, '2019-07-17 08:42:17'),
(95, 549491, 65814, '2019-07-17 08:47:59'),
(96, 549491, 52641, '2019-07-17 08:47:59'),
(97, 549491, 17282, '2019-07-17 08:47:59'),
(98, 549491, 35042, '2019-07-17 08:47:59'),
(99, 549491, 31117, '2019-07-17 08:47:59'),
(100, 776281, 65814, '2019-07-17 08:53:21'),
(101, 776281, 52641, '2019-07-17 08:53:21'),
(102, 776281, 17282, '2019-07-17 08:53:21'),
(103, 31721, 65814, '2019-07-17 08:56:16'),
(104, 31721, 52641, '2019-07-17 08:56:16'),
(105, 31721, 17282, '2019-07-17 08:56:16'),
(106, 31721, 35042, '2019-07-17 08:56:16'),
(107, 911711, 65814, '2019-07-17 08:58:00'),
(108, 911711, 52641, '2019-07-17 08:58:00'),
(109, 911711, 17282, '2019-07-17 08:58:00'),
(110, 107009, 65814, '2019-07-17 09:08:23'),
(111, 107009, 52641, '2019-07-17 09:08:23'),
(112, 107009, 17282, '2019-07-17 09:08:23'),
(113, 107009, 35042, '2019-07-17 09:08:23'),
(114, 640504, 65814, '2019-07-17 09:09:30'),
(115, 640504, 52641, '2019-07-17 09:09:30'),
(116, 640504, 17282, '2019-07-17 09:09:30'),
(117, 199281, 65814, '2019-07-17 09:12:07'),
(118, 199281, 52641, '2019-07-17 09:12:07'),
(119, 199281, 17282, '2019-07-17 09:12:07'),
(120, 199281, 35042, '2019-07-17 09:12:07'),
(121, 815244, 65814, '2019-07-17 09:14:04'),
(122, 815244, 52641, '2019-07-17 09:14:04'),
(123, 815244, 17282, '2019-07-17 09:14:04'),
(124, 815244, 35042, '2019-07-17 09:14:04'),
(125, 344092, 65814, '2019-07-17 09:49:40'),
(126, 344092, 52641, '2019-07-17 09:49:40'),
(127, 344092, 17282, '2019-07-17 09:49:40'),
(128, 344092, 35042, '2019-07-17 09:49:40'),
(129, 344092, 31117, '2019-07-17 09:49:40'),
(130, 344092, 52280, '2019-07-17 09:49:40'),
(131, 344092, 83937, '2019-07-17 09:49:40'),
(132, 344092, 77366, '2019-07-17 09:49:40'),
(133, 344092, 64914, '2019-07-17 09:49:40'),
(134, 344092, 21616, '2019-07-17 09:49:40'),
(135, 644243, 65814, '2019-07-17 09:53:01'),
(136, 644243, 52641, '2019-07-17 09:53:01'),
(137, 644243, 17282, '2019-07-17 09:53:01'),
(138, 644243, 35042, '2019-07-17 09:53:01'),
(139, 534357, 65814, '2019-07-17 09:59:31'),
(140, 534357, 52641, '2019-07-17 09:59:31'),
(141, 534357, 17282, '2019-07-17 09:59:31'),
(142, 534357, 35042, '2019-07-17 09:59:31'),
(143, 380490, 65814, '2019-07-17 10:02:01'),
(144, 380490, 52641, '2019-07-17 10:02:01'),
(145, 380490, 17282, '2019-07-17 10:02:01'),
(146, 380490, 35042, '2019-07-17 10:02:01');

-- --------------------------------------------------------

--
-- Table structure for table `userdetail`
--

CREATE TABLE `userdetail` (
  `uid` int(8) NOT NULL,
  `uname` varchar(50) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `passw` varchar(20) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `previlag` varchar(20) NOT NULL,
  `user_adar` varchar(40) NOT NULL,
  `profile_pic` varchar(200) DEFAULT NULL,
  `timestmp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userdetail`
--

INSERT INTO `userdetail` (`uid`, `uname`, `user_id`, `passw`, `mobile`, `email`, `address`, `previlag`, `user_adar`, `profile_pic`, `timestmp`) VALUES
(1, 'Abuzer ansari', 'abuzer', 'abuzer8', '7405305073', 'azad4win@gmail.com', '', 'Super Admin', '', NULL, '2018-12-17 16:33:07'),
(56161, 'bhavi', 'bhavi', 'bhavi', '4546545341', 'bhavi@gmail.com', 'surat', 'Admin', '5658960850', NULL, '2019-07-20 10:42:10'),
(48585, 'namrata', 'namrata', 'namrata', '5675685768', 'namrata@gmail.com', 'punagam', 'Admin', '8457894765', NULL, '2019-07-20 10:34:43'),
(80746, 'monik', 'monik', '1234', '8487548375', 'monik@gmil.com', 'banglore', 'Admin', '5656', NULL, '2019-07-20 10:25:27');

--
-- Triggers `userdetail`
--
DELIMITER $$
CREATE TRIGGER `setlog` AFTER INSERT ON `userdetail` FOR EACH ROW BEGIN
INSERT INTO `login_state`(`uid`, `sid`) VALUES (NEW.uid,'sahds86g78gfd4r');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_ass`
--

CREATE TABLE `user_ass` (
  `id` int(10) NOT NULL,
  `uid` int(15) NOT NULL,
  `hostel_detail` tinyint(1) DEFAULT '0',
  `update_hostel` tinyint(1) DEFAULT '0',
  `htl_floor_des` tinyint(1) DEFAULT '0',
  `htl_floor_assets` tinyint(1) DEFAULT '0',
  `htl_room_assets` tinyint(1) DEFAULT '0',
  `clg_detail` tinyint(1) DEFAULT '0',
  `clg_add_room` tinyint(1) DEFAULT '0',
  `clg_floor_des` tinyint(1) DEFAULT '0',
  `clg_group` tinyint(1) DEFAULT '0',
  `clg_floor_assets` tinyint(1) DEFAULT '0',
  `clg_room_assets` tinyint(1) DEFAULT '0',
  `create_user` tinyint(1) DEFAULT '0',
  `assing_user` tinyint(1) DEFAULT '0',
  `user_ass` tinyint(1) DEFAULT '0',
  `complaint_detail` tinyint(1) DEFAULT '0',
  `complaint_regi` tinyint(1) DEFAULT '0',
  `technician_ass` tinyint(1) DEFAULT '0',
  `regi_technician` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_ass`
--

INSERT INTO `user_ass` (`id`, `uid`, `hostel_detail`, `update_hostel`, `htl_floor_des`, `htl_floor_assets`, `htl_room_assets`, `clg_detail`, `clg_add_room`, `clg_floor_des`, `clg_group`, `clg_floor_assets`, `clg_room_assets`, `create_user`, `assing_user`, `user_ass`, `complaint_detail`, `complaint_regi`, `technician_ass`, `regi_technician`) VALUES
(22, 80746, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 1),
(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(23, 48585, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 1),
(24, 56161, 0, 0, 0, 0, 0, 0, 1, 0, 1, 0, 1, 1, 1, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_complaintes`
--

CREATE TABLE `user_complaintes` (
  `id` int(20) NOT NULL,
  `cmplainer_id` int(20) NOT NULL,
  `cmplainer_name` varchar(50) NOT NULL,
  `cmplainer_contect` varchar(20) NOT NULL,
  `comp_title` varchar(100) DEFAULT NULL,
  `cmp_des` varchar(500) NOT NULL,
  `cmp_priority` varchar(10) NOT NULL,
  `ast_type` varchar(20) DEFAULT NULL,
  `cmp_asset` varchar(60) DEFAULT NULL,
  `cmp_main_asset_id` int(20) DEFAULT NULL,
  `cmp_asset_des_id` int(20) DEFAULT NULL,
  `cmp_asset_uniq_code` varchar(50) DEFAULT NULL,
  `hostel_id` varchar(10) NOT NULL,
  `flr_id` varchar(10) NOT NULL,
  `rm_id` varchar(10) DEFAULT NULL,
  `date` varchar(30) NOT NULL,
  `status` int(5) NOT NULL DEFAULT '0',
  `cmplainer_type` varchar(10) DEFAULT NULL,
  `before_img` varchar(500) DEFAULT NULL,
  `process_img` varchar(50) DEFAULT NULL,
  `final_img` varchar(50) DEFAULT NULL,
  `varify_status` int(2) NOT NULL DEFAULT '0',
  `end_date` varchar(20) DEFAULT NULL,
  `main_tech_id` int(20) DEFAULT NULL,
  `building` varchar(20) DEFAULT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rejected_des` varchar(2000) DEFAULT NULL,
  `rejected_time` timestamp NULL DEFAULT NULL,
  `approved_des` varchar(2000) DEFAULT NULL,
  `approved_time` timestamp NULL DEFAULT NULL,
  `process_des` varchar(2000) DEFAULT NULL,
  `process_time` timestamp NULL DEFAULT NULL,
  `final_des` varchar(2000) DEFAULT NULL,
  `final_time` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_complaintes`
--

INSERT INTO `user_complaintes` (`id`, `cmplainer_id`, `cmplainer_name`, `cmplainer_contect`, `comp_title`, `cmp_des`, `cmp_priority`, `ast_type`, `cmp_asset`, `cmp_main_asset_id`, `cmp_asset_des_id`, `cmp_asset_uniq_code`, `hostel_id`, `flr_id`, `rm_id`, `date`, `status`, `cmplainer_type`, `before_img`, `process_img`, `final_img`, `varify_status`, `end_date`, `main_tech_id`, `building`, `timestemp`, `rejected_des`, `rejected_time`, `approved_des`, `approved_time`, `process_des`, `process_time`, `final_des`, `final_time`) VALUES
(537823743, 7, 'gautam', '9173883777', 'fan issuw', 'wrong with fan', 'LOW', 'Floor', 'fan[center fan]', 31150, 9, '1254', '273', '63929', NULL, '18-06-2019 16:08', 3, 'RECTORE', NULL, 'images/537823743_2.png', 'images/537823743_3.png', 1, '2019-07-25', 9474, 'Hostel', '2019-06-18 10:38:29', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 'hfjdh', '2019-07-29 07:01:16', 'hfjdh', '2019-07-29 10:17:12'),
(519564765, 7, 'gautam', '9173883777', 'xyz ', 'demo fan', 'LOW', 'Floor', 'fan[center fan]', 31150, 9, '1254', '273', '63929', NULL, '18-06-2019 16:11', 0, 'RECTORE', NULL, NULL, NULL, 1, NULL, NULL, 'Hostel', '2019-06-18 10:41:19', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(973460097, 7, 'gautam', '9173883777', 'xusmfmfkf', 'demo dx', 'LOW', 'Floor', 'fan[center fan]', 31150, 9, '1254', '273', '49971', NULL, '18-06-2019 16:12', 1, 'RECTORE', NULL, NULL, NULL, 1, '2019-06-20', 8726, 'Hostel', '2019-06-18 10:42:33', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(125198900, 7, 'gautam', '9173883777', 'cjcjcckc', 'xidkci', 'LOW', 'Floor', 'fan[center fan]', 31150, 9, '1254', '273', '63929', NULL, '18-06-2019 16:15', 0, 'RECTORE', NULL, NULL, NULL, 1, NULL, NULL, 'Hostel', '2019-06-18 10:45:31', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(645880214, 7, 'gautam', '9173883777', 'ckdkfkckc', 'xkxkckckc', 'LOW', 'Floor', 'fan[center fan]', 31150, 9, '1254', '273', '63929', NULL, '18-06-2019 16:17', 0, 'RECTORE', NULL, NULL, NULL, 1, NULL, NULL, 'Hostel', '2019-06-18 10:47:47', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(945101416, 7, 'gautam', '9173883777', 'cmfmfmf', 'dkdkckck', 'HIGH', 'Floor', 'fan[center fan]', 31150, 9, '1254', '273', '63929', NULL, '18-06-2019 16:26', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-06-18 10:56:35', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(697476582, 7, 'gautam', '9173883777', 'cmfmfmf', 'dkdkckck', 'HIGH', 'Floor', 'fan[center fan]', 31150, 9, '1254', '273', '63929', NULL, '18-06-2019 16:28', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-06-18 10:58:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(372965984, 7, 'gautam', '9173883777', 'mcckcmcm', 'xkxkxk', 'HIGH', 'Floor', 'fan[center fan]', 31150, 9, '1254', '273', '63929', NULL, '18-06-2019 16:31', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-06-18 11:01:18', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(88261505, 7, 'gautam', '9173883777', 'somthing wrong', 'cjckckv', 'MEDIUM', 'Floor', 'bulb[white bulb]', 38341, 3, '1254d', '273', '63929', NULL, '16-07-2019 11:48', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-07-16 06:18:54', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(385824797, 7, 'gautam', '9173883777', 'didkif', 'dkdkd', 'LOW', 'Floor', 'bulb[white bulb]', 38341, 3, '1254d', '273', '49971', NULL, '16-07-2019 15:23', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-07-16 09:53:28', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(15459300, 7, 'gautam', '9173883777', 'Xbxnsb', 'Xxxxxx    ', 'HIGH', 'Floor', 'bulb[white bulb]', 38341, 3, '1254d', '273', '63929', NULL, '16-07-2019 15:25', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-07-16 09:55:03', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(516006263, 7, 'gautam', '9173883777', 'Gift y', 'Ggftyh', 'HIGH', 'Floor', 'fan[center fan]', 48497, 10, '1254', '273', '49971', NULL, '17-07-2019 11:57', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-07-17 06:27:23', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(8779069, 7, 'gautam', '9173883777', 'Foram', 'Fhjjjs', 'HIGH', 'Floor', 'fan[center fan]', 48497, 10, '1254', '273', '94395', NULL, '17-07-2019 12:12', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-07-17 06:42:04', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(578969994, 7, 'gautam', '9173883777', 'Ram', 'Fhhs', 'HIGH', 'Floor', 'fan[center fan]', 48497, 10, '1254', '273', '94395', NULL, '17-07-2019 12:13', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-07-17 06:43:25', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(905531025, 7, 'gautam', '9173883777', 'Fgajjanan', 'Hj', 'HIGH', 'Floor', 'fan[center fan]', 48497, 10, '1254', '273', '63929', NULL, '17-07-2019 12:17', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-07-17 06:47:50', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(543783020, 7, 'gautam', '9173883777', 'Ganesha', 'Dhhjn', 'HIGH', 'Floor', 'fan[center fan]', 48497, 10, '1254', '273', '94395', NULL, '17-07-2019 12:18', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-07-17 06:48:39', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(79697912, 7, 'gautam', '9173883777', 'Fhcf', 'Ggu', 'HIGH', 'Floor', 'fan[center fan]', 48497, 10, '1254', '273', '49971', NULL, '17-07-2019 12:19', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-07-17 06:49:28', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(50535841, 7, 'gautam', '9173883777', 'Hggh', 'Ghh', 'HIGH', 'Floor', 'fan[center fan]', 48497, 10, '1254', '273', '63929', NULL, '17-07-2019 12:37', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-07-17 07:07:29', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(771420242, 7, 'gautam', '9173883777', 'Fora', 'Guu', 'HIGH', 'Floor', 'fan[center fan]', 48497, 10, '1254', '273', '63929', NULL, '17-07-2019 12:38', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-07-17 07:08:21', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(427788788, 7, 'gautam', '9173883777', 'Ghsis', 'Guuu', 'HIGH', 'Floor', 'fan[center fan]', 48497, 10, '1254', '273', '94395', NULL, '17-07-2019 12:40', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-07-17 07:10:38', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(206179801, 7, 'gautam', '9173883777', 'Raju ', 'Dghhv', 'HIGH', 'Floor', 'fan[center fan]', 48497, 10, '1254', '273', '49971', NULL, '19-07-2019 16:59', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-07-19 11:29:25', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(22162013, 7, 'gautam', '9173883777', 'Komal', 'Fgfxfg', 'HIGH', 'Floor', 'fan[center fan]', 31150, 9, '1254', '273', '49971', NULL, '19-07-2019 17:23', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-07-19 11:53:11', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(853499540, 7, 'gautam', '9173883777', 'Ansarisir', 'Jbh', 'HIGH', 'Floor', 'fan[center fan]', 31150, 9, '1254', '273', '49971', NULL, '19-07-2019 17:24', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-07-19 11:54:43', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(458117690, 7, 'gautam', '9173883777', 'hgtgvjx', 'hjvyhd', 'LOW', 'Floor', 'fan[center fan]', 48497, 10, '1254', '273', '94395', NULL, '19-07-2019 17:37', 0, 'RECTORE', NULL, NULL, NULL, 1, NULL, NULL, 'Hostel', '2019-07-19 12:07:50', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00'),
(271364719, 7, 'gautam', '9173883777', 'Hii', 'Ghj', 'HIGH', 'Floor', 'fan[center fan]', 48497, 10, '1254', '273', '63929', NULL, '27-07-2019 10:47', 0, 'RECTORE', NULL, NULL, NULL, 0, NULL, NULL, 'Hostel', '2019-07-27 05:17:42', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_complaint_type`
--

CREATE TABLE `user_complaint_type` (
  `id` int(20) NOT NULL,
  `cmp_id` int(20) NOT NULL,
  `type_id` int(20) NOT NULL,
  `type` varchar(50) NOT NULL,
  `timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_complaint_type`
--

INSERT INTO `user_complaint_type` (`id`, `cmp_id`, `type_id`, `type`, `timestemp`) VALUES
(102, 679108452, 65814, 'ELECTRICAL', '2019-06-11 10:00:50'),
(103, 352318918, 65814, 'ELECTRICAL', '2019-06-11 10:10:36'),
(104, 712424766, 65814, 'ELECTRICAL', '2019-06-11 10:13:20'),
(105, 712424766, 17282, 'PLUMBING', '2019-06-11 10:13:20'),
(106, 644920315, 65814, 'ELECTRICAL', '2019-06-11 10:17:23'),
(107, 644920315, 17282, 'PLUMBING', '2019-06-11 10:17:23'),
(108, 159600015, 65814, 'ELECTRICAL', '2019-06-11 10:21:22'),
(109, 159600015, 17282, 'PLUMBING', '2019-06-11 10:21:22'),
(110, 107787142, 65814, 'ELECTRICAL', '2019-06-11 10:44:00'),
(111, 279409655, 65814, 'ELECTRICAL', '2019-06-11 10:46:31'),
(112, 279409655, 52641, 'CLEANER', '2019-06-11 10:46:31'),
(113, 126657911, 17282, 'PLUMBING', '2019-06-11 10:47:54'),
(114, 126657911, 35042, 'CARPENTRY', '2019-06-11 10:47:54'),
(115, 437368313, 65814, 'ELECTRICAL', '2019-06-14 10:09:52'),
(116, 437368313, 52641, 'CLEANER', '2019-06-14 10:09:52'),
(117, 437368313, 17282, 'PLUMBING', '2019-06-14 10:09:52'),
(118, 317204548, 65814, 'ELECTRICAL', '2019-06-14 10:10:35'),
(119, 311258638, 35042, 'CARPENTRY', '2019-06-14 10:13:26'),
(120, 895450637, 52641, 'CLEANER', '2019-06-17 05:07:53'),
(121, 895450637, 17282, 'PLUMBING', '2019-06-17 05:07:53'),
(122, 193222656, 52641, 'CLEANER', '2019-06-17 05:08:31'),
(123, 193222656, 17282, 'PLUMBING', '2019-06-17 05:08:31'),
(124, 48278913, 65814, 'ELECTRICAL', '2019-06-17 06:51:42'),
(125, 48278913, 52641, 'CLEANER', '2019-06-17 06:51:42'),
(126, 946567616, 65814, 'ELECTRICAL', '2019-06-17 07:24:54'),
(127, 946567616, 52641, 'CLEANER', '2019-06-17 07:24:54'),
(128, 726123241, 65814, 'ELECTRICAL', '2019-06-17 07:25:36'),
(129, 726123241, 52641, 'CLEANER', '2019-06-17 07:25:36'),
(130, 726123241, 17282, 'PLUMBING', '2019-06-17 07:25:36'),
(131, 306997783, 65814, 'ELECTRICAL', '2019-06-17 07:26:46'),
(132, 306997783, 52641, 'CLEANER', '2019-06-17 07:26:46'),
(133, 306997783, 17282, 'PLUMBING', '2019-06-17 07:26:46'),
(134, 444945832, 65814, 'ELECTRICAL', '2019-06-17 07:30:41'),
(135, 444945832, 52641, 'CLEANER', '2019-06-17 07:30:41'),
(136, 233849931, 52280, 'LAUNDRY', '2019-06-17 08:57:52'),
(137, 703557802, 65814, 'ELECTRICAL', '2019-06-18 08:49:11'),
(138, 703557802, 17282, 'PLUMBING', '2019-06-18 08:49:11'),
(139, 703557802, 84266, 'CONTRACTOR', '2019-06-18 08:49:11'),
(140, 475644339, 35042, 'CARPENTRY', '2019-06-18 09:07:05'),
(141, 844904514, 65814, 'ELECTRICAL', '2019-06-18 09:11:17'),
(142, 844904514, 52641, 'CLEANER', '2019-06-18 09:11:17'),
(143, 452705833, 65814, 'ELECTRICAL', '2019-06-18 09:24:40'),
(144, 452705833, 52641, 'CLEANER', '2019-06-18 09:24:40'),
(145, 717629968, 17282, 'PLUMBING', '2019-06-18 09:59:07'),
(146, 245570611, 17282, 'PLUMBING', '2019-06-18 09:59:41'),
(147, 615716770, 65814, 'ELECTRICAL', '2019-06-18 10:00:43'),
(148, 615716770, 17282, 'PLUMBING', '2019-06-18 10:00:43'),
(149, 660235130, 65814, 'ELECTRICAL', '2019-06-18 10:34:13'),
(150, 537823743, 65814, 'ELECTRICAL', '2019-06-18 10:38:29'),
(151, 519564765, 65814, 'ELECTRICAL', '2019-06-18 10:41:19'),
(152, 973460097, 65814, 'ELECTRICAL', '2019-06-18 10:42:33'),
(153, 125198900, 65814, 'ELECTRICAL', '2019-06-18 10:45:31'),
(154, 645880214, 65814, 'ELECTRICAL', '2019-06-18 10:47:47'),
(155, 945101416, 65814, 'ELECTRICAL', '2019-06-18 10:56:35'),
(156, 697476582, 65814, 'ELECTRICAL', '2019-06-18 10:58:00'),
(157, 372965984, 65814, 'ELECTRICAL', '2019-06-18 11:01:18'),
(158, 119233457, 65814, 'ELECTRICAL', '2019-06-18 14:46:58'),
(159, 834503404, 65814, 'ELECTRICAL', '2019-06-18 14:47:02'),
(160, 366623226, 65814, 'ELECTRICAL', '2019-06-18 14:47:12'),
(161, 178427634, 65814, 'ELECTRICAL', '2019-06-18 14:47:21'),
(162, 349202990, 65814, 'ELECTRICAL', '2019-06-18 14:47:23'),
(163, 88261505, 65814, 'ELECTRICAL', '2019-07-16 06:18:54'),
(164, 88261505, 52641, 'CLEANER', '2019-07-16 06:18:54'),
(165, 385824797, 65814, 'ELECTRICAL', '2019-07-16 09:53:28'),
(166, 385824797, 17282, 'PLUMBING', '2019-07-16 09:53:28'),
(167, 385824797, 35042, 'CARPENTRY', '2019-07-16 09:53:28'),
(168, 15459300, 65814, 'ELECTRICAL', '2019-07-16 09:55:03'),
(169, 15459300, 52641, 'CLEANER', '2019-07-16 09:55:03'),
(170, 15459300, 17282, 'PLUMBING', '2019-07-16 09:55:03'),
(171, 447260154, 65814, 'ELECTRICAL', '2019-07-16 12:29:37'),
(172, 447260154, 52641, 'CLEANER', '2019-07-16 12:29:37'),
(173, 392349774, 65814, 'ELECTRICAL', '2019-07-16 12:29:51'),
(174, 392349774, 52641, 'CLEANER', '2019-07-16 12:29:51'),
(175, 401916837, 65814, 'ELECTRICAL', '2019-07-16 12:30:23'),
(176, 401916837, 52641, 'CLEANER', '2019-07-16 12:30:23'),
(177, 470364274, 65814, 'ELECTRICAL', '2019-07-16 12:30:37'),
(178, 470364274, 52641, 'CLEANER', '2019-07-16 12:30:37'),
(179, 754527314, 65814, 'ELECTRICAL', '2019-07-16 12:30:39'),
(180, 754527314, 52641, 'CLEANER', '2019-07-16 12:30:39'),
(181, 791893543, 65814, 'ELECTRICAL', '2019-07-16 12:31:49'),
(182, 791893543, 52641, 'CLEANER', '2019-07-16 12:31:49'),
(183, 177206748, 65814, 'ELECTRICAL', '2019-07-16 12:32:07'),
(184, 177206748, 52641, 'CLEANER', '2019-07-16 12:32:07'),
(185, 788551694, 65814, 'ELECTRICAL', '2019-07-16 12:32:13'),
(186, 788551694, 52641, 'CLEANER', '2019-07-16 12:32:13'),
(187, 454568859, 65814, 'ELECTRICAL', '2019-07-16 12:32:33'),
(188, 454568859, 52641, 'CLEANER', '2019-07-16 12:32:33'),
(189, 453675968, 65814, 'ELECTRICAL', '2019-07-16 12:42:56'),
(190, 453675968, 52641, 'CLEANER', '2019-07-16 12:42:56'),
(191, 453675968, 35042, 'CARPENTRY', '2019-07-16 12:42:56'),
(192, 911442953, 65814, 'ELECTRICAL', '2019-07-16 12:42:58'),
(193, 911442953, 52641, 'CLEANER', '2019-07-16 12:42:58'),
(194, 911442953, 35042, 'CARPENTRY', '2019-07-16 12:42:58'),
(195, 454414319, 65814, 'ELECTRICAL', '2019-07-16 12:43:00'),
(196, 454414319, 52641, 'CLEANER', '2019-07-16 12:43:00'),
(197, 454414319, 35042, 'CARPENTRY', '2019-07-16 12:43:00'),
(198, 147342707, 65814, 'ELECTRICAL', '2019-07-16 12:43:20'),
(199, 147342707, 52641, 'CLEANER', '2019-07-16 12:43:20'),
(200, 147342707, 35042, 'CARPENTRY', '2019-07-16 12:43:20'),
(201, 147342707, 21616, 'HEALTH CARE', '2019-07-16 12:43:20'),
(202, 147342707, 84266, 'CONTRACTOR', '2019-07-16 12:43:20'),
(203, 64383168, 65814, 'ELECTRICAL', '2019-07-16 12:43:22'),
(204, 64383168, 52641, 'CLEANER', '2019-07-16 12:43:22'),
(205, 64383168, 35042, 'CARPENTRY', '2019-07-16 12:43:22'),
(206, 64383168, 21616, 'HEALTH CARE', '2019-07-16 12:43:22'),
(207, 64383168, 84266, 'CONTRACTOR', '2019-07-16 12:43:22'),
(208, 736560372, 65814, 'ELECTRICAL', '2019-07-16 12:43:24'),
(209, 736560372, 52641, 'CLEANER', '2019-07-16 12:43:24'),
(210, 736560372, 35042, 'CARPENTRY', '2019-07-16 12:43:24'),
(211, 736560372, 21616, 'HEALTH CARE', '2019-07-16 12:43:24'),
(212, 736560372, 84266, 'CONTRACTOR', '2019-07-16 12:43:24'),
(213, 405598607, 65814, 'ELECTRICAL', '2019-07-16 12:43:45'),
(214, 405598607, 52641, 'CLEANER', '2019-07-16 12:43:45'),
(215, 405598607, 35042, 'CARPENTRY', '2019-07-16 12:43:45'),
(216, 405598607, 21616, 'HEALTH CARE', '2019-07-16 12:43:45'),
(217, 405598607, 84266, 'CONTRACTOR', '2019-07-16 12:43:45'),
(218, 353561402, 65814, 'ELECTRICAL', '2019-07-16 12:43:53'),
(219, 353561402, 52641, 'CLEANER', '2019-07-16 12:43:53'),
(220, 353561402, 35042, 'CARPENTRY', '2019-07-16 12:43:53'),
(221, 353561402, 21616, 'HEALTH CARE', '2019-07-16 12:43:53'),
(222, 353561402, 84266, 'CONTRACTOR', '2019-07-16 12:43:53'),
(223, 692218049, 65814, 'ELECTRICAL', '2019-07-16 12:44:02'),
(224, 692218049, 52641, 'CLEANER', '2019-07-16 12:44:02'),
(225, 692218049, 35042, 'CARPENTRY', '2019-07-16 12:44:02'),
(226, 692218049, 21616, 'HEALTH CARE', '2019-07-16 12:44:02'),
(227, 692218049, 84266, 'CONTRACTOR', '2019-07-16 12:44:02'),
(228, 250866412, 65814, 'ELECTRICAL', '2019-07-16 12:44:57'),
(229, 250866412, 52641, 'CLEANER', '2019-07-16 12:44:57'),
(230, 250866412, 35042, 'CARPENTRY', '2019-07-16 12:44:57'),
(231, 250866412, 21616, 'HEALTH CARE', '2019-07-16 12:44:57'),
(232, 250866412, 84266, 'CONTRACTOR', '2019-07-16 12:44:57'),
(233, 567595730, 65814, 'ELECTRICAL', '2019-07-16 12:45:17'),
(234, 567595730, 52641, 'CLEANER', '2019-07-16 12:45:17'),
(235, 567595730, 35042, 'CARPENTRY', '2019-07-16 12:45:17'),
(236, 567595730, 21616, 'HEALTH CARE', '2019-07-16 12:45:17'),
(237, 567595730, 84266, 'CONTRACTOR', '2019-07-16 12:45:17'),
(238, 516006263, 65814, 'ELECTRICAL', '2019-07-17 06:27:23'),
(239, 516006263, 52641, 'CLEANER', '2019-07-17 06:27:23'),
(240, 516006263, 17282, 'PLUMBING', '2019-07-17 06:27:23'),
(241, 8779069, 83937, 'PEST CONTROL', '2019-07-17 06:42:04'),
(242, 8779069, 77366, 'PAINTING', '2019-07-17 06:42:04'),
(243, 578969994, 83937, 'PEST CONTROL', '2019-07-17 06:43:25'),
(244, 578969994, 77366, 'PAINTING', '2019-07-17 06:43:25'),
(245, 905531025, 83937, 'PEST CONTROL', '2019-07-17 06:47:50'),
(246, 905531025, 77366, 'PAINTING', '2019-07-17 06:47:50'),
(247, 256029833, 83937, 'PEST CONTROL', '2019-07-17 06:48:26'),
(248, 256029833, 77366, 'PAINTING', '2019-07-17 06:48:26'),
(249, 256029833, 21616, 'HEALTH CARE', '2019-07-17 06:48:26'),
(250, 256029833, 36842, 'GARDENER', '2019-07-17 06:48:26'),
(251, 543783020, 83937, 'PEST CONTROL', '2019-07-17 06:48:39'),
(252, 543783020, 77366, 'PAINTING', '2019-07-17 06:48:39'),
(253, 543783020, 21616, 'HEALTH CARE', '2019-07-17 06:48:39'),
(254, 543783020, 36842, 'GARDENER', '2019-07-17 06:48:39'),
(255, 79697912, 83937, 'PEST CONTROL', '2019-07-17 06:49:28'),
(256, 79697912, 77366, 'PAINTING', '2019-07-17 06:49:28'),
(257, 79697912, 21616, 'HEALTH CARE', '2019-07-17 06:49:28'),
(258, 79697912, 36842, 'GARDENER', '2019-07-17 06:49:28'),
(259, 50535841, 83937, 'PEST CONTROL', '2019-07-17 07:07:29'),
(260, 50535841, 77366, 'PAINTING', '2019-07-17 07:07:29'),
(261, 50535841, 21616, 'HEALTH CARE', '2019-07-17 07:07:29'),
(262, 50535841, 36842, 'GARDENER', '2019-07-17 07:07:29'),
(263, 771420242, 83937, 'PEST CONTROL', '2019-07-17 07:08:21'),
(264, 771420242, 77366, 'PAINTING', '2019-07-17 07:08:21'),
(265, 771420242, 21616, 'HEALTH CARE', '2019-07-17 07:08:21'),
(266, 771420242, 36842, 'GARDENER', '2019-07-17 07:08:21'),
(267, 427788788, 83937, 'PEST CONTROL', '2019-07-17 07:10:38'),
(268, 427788788, 77366, 'PAINTING', '2019-07-17 07:10:38'),
(269, 427788788, 21616, 'HEALTH CARE', '2019-07-17 07:10:38'),
(270, 427788788, 36842, 'GARDENER', '2019-07-17 07:10:38'),
(271, 621640631, 65814, 'ELECTRICAL', '2019-07-19 11:14:27'),
(272, 621640631, 17282, 'PLUMBING', '2019-07-19 11:14:27'),
(273, 218540220, 65814, 'ELECTRICAL', '2019-07-19 11:14:40'),
(274, 218540220, 17282, 'PLUMBING', '2019-07-19 11:14:40'),
(275, 291133536, 65814, 'ELECTRICAL', '2019-07-19 11:14:46'),
(276, 291133536, 17282, 'PLUMBING', '2019-07-19 11:14:46'),
(277, 395806861, 65814, 'ELECTRICAL', '2019-07-19 11:14:51'),
(278, 395806861, 17282, 'PLUMBING', '2019-07-19 11:14:51'),
(279, 337649816, 65814, 'ELECTRICAL', '2019-07-19 11:16:20'),
(280, 337649816, 17282, 'PLUMBING', '2019-07-19 11:16:20'),
(281, 293319850, 65814, 'ELECTRICAL', '2019-07-19 11:21:59'),
(282, 293319850, 52641, 'CLEANER', '2019-07-19 11:21:59'),
(283, 293319850, 17282, 'PLUMBING', '2019-07-19 11:21:59'),
(284, 308242412, 65814, 'ELECTRICAL', '2019-07-19 11:22:25'),
(285, 308242412, 52641, 'CLEANER', '2019-07-19 11:22:25'),
(286, 308242412, 17282, 'PLUMBING', '2019-07-19 11:22:25'),
(287, 720699574, 65814, 'ELECTRICAL', '2019-07-19 11:22:29'),
(288, 720699574, 52641, 'CLEANER', '2019-07-19 11:22:29'),
(289, 720699574, 17282, 'PLUMBING', '2019-07-19 11:22:29'),
(290, 523932332, 65814, 'ELECTRICAL', '2019-07-19 11:23:01'),
(291, 523932332, 52641, 'CLEANER', '2019-07-19 11:23:01'),
(292, 523932332, 17282, 'PLUMBING', '2019-07-19 11:23:01'),
(293, 991352320, 52641, 'CLEANER', '2019-07-19 11:25:38'),
(294, 991352320, 17282, 'PLUMBING', '2019-07-19 11:25:38'),
(295, 991352320, 35042, 'CARPENTRY', '2019-07-19 11:25:38'),
(296, 158268952, 52641, 'CLEANER', '2019-07-19 11:25:51'),
(297, 158268952, 17282, 'PLUMBING', '2019-07-19 11:25:51'),
(298, 158268952, 35042, 'CARPENTRY', '2019-07-19 11:25:51'),
(299, 781127847, 52641, 'CLEANER', '2019-07-19 11:26:42'),
(300, 781127847, 17282, 'PLUMBING', '2019-07-19 11:26:42'),
(301, 781127847, 35042, 'CARPENTRY', '2019-07-19 11:26:42'),
(302, 206179801, 64914, 'MOVERS AND PACKING', '2019-07-19 11:29:25'),
(303, 206179801, 21616, 'HEALTH CARE', '2019-07-19 11:29:25'),
(304, 206179801, 84266, 'CONTRACTOR', '2019-07-19 11:29:25'),
(305, 384951809, 52641, 'CLEANER', '2019-07-19 11:38:35'),
(306, 384951809, 17282, 'PLUMBING', '2019-07-19 11:38:35'),
(307, 22162013, 65814, 'ELECTRICAL', '2019-07-19 11:53:11'),
(308, 22162013, 52641, 'CLEANER', '2019-07-19 11:53:11'),
(309, 22162013, 17282, 'PLUMBING', '2019-07-19 11:53:11'),
(310, 853499540, 35042, 'CARPENTRY', '2019-07-19 11:54:43'),
(311, 853499540, 31117, 'FABRICATION', '2019-07-19 11:54:43'),
(312, 458117690, 31117, 'FABRICATION', '2019-07-19 12:07:50'),
(313, 458117690, 52280, 'LAUNDRY', '2019-07-19 12:07:50'),
(314, 114413265, 65814, 'ELECTRICAL', '2019-07-26 10:20:17'),
(315, 114413265, 52641, 'CLEANER', '2019-07-26 10:20:17'),
(316, 114413265, 17282, 'PLUMBING', '2019-07-26 10:20:17'),
(317, 114413265, 35042, 'CARPENTRY', '2019-07-26 10:20:17'),
(318, 271364719, 21616, 'HEALTH CARE', '2019-07-27 05:17:42'),
(319, 271364719, 84266, 'CONTRACTOR', '2019-07-27 05:17:42'),
(320, 271364719, 36842, 'GARDENER', '2019-07-27 05:17:42');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `vid` int(11) NOT NULL,
  `vname` varchar(50) NOT NULL,
  `vcontact` varchar(12) NOT NULL,
  `vemail` varchar(50) NOT NULL,
  `resd_address` varchar(500) DEFAULT NULL,
  `cmpny` varchar(100) DEFAULT NULL,
  `cmpny_address` varchar(500) DEFAULT NULL,
  `ven_category` varchar(50) DEFAULT NULL,
  `delete_status` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ac_no` varchar(20) DEFAULT NULL,
  `ac_type` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `ifsc` varchar(50) DEFAULT NULL,
  `branch_name` varchar(50) DEFAULT NULL,
  `branch_code` int(10) DEFAULT NULL,
  `upi_address` varchar(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`vid`, `vname`, `vcontact`, `vemail`, `resd_address`, `cmpny`, `cmpny_address`, `ven_category`, `delete_status`, `timestamp`, `ac_no`, `ac_type`, `bank_name`, `ifsc`, `branch_name`, `branch_code`, `upi_address`) VALUES
(1, 'Gautam Parmar', '8209331987', 'gp213011@gmail.com', NULL, NULL, NULL, '2', 0, '2018-12-18 08:15:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Abuzer Ansari', '7383988833', 'azad4win@gmail.com', NULL, NULL, NULL, '2', 0, '2018-12-18 11:22:52', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'best building ', '8888789788', 'best@gmai.com', NULL, NULL, NULL, '2', 0, '2018-12-19 11:32:52', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'yjhgjhg', '7383988832', 'N/A', NULL, NULL, NULL, '1', 0, '2019-01-27 14:31:49', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, ';iujoi', '7698118928', 'N/A', NULL, NULL, NULL, '3', 0, '2019-01-27 14:33:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'uwais', '9823333856', 'N/A', NULL, NULL, NULL, '1,2,3', 0, '2019-01-27 16:29:22', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_category`
--

CREATE TABLE `vendor_category` (
  `cat_id` int(10) NOT NULL,
  `cat_name` varchar(100) NOT NULL,
  `delete_status` tinyint(1) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vendor_category`
--

INSERT INTO `vendor_category` (`cat_id`, `cat_name`, `delete_status`, `create_date`) VALUES
(1, 'Electrical & Electronics Deparment', 0, '2019-01-21 05:14:16'),
(2, 'Plumbering Department', 0, '2019-01-21 05:14:16'),
(3, 'Carpenter Department', 0, '2019-01-21 05:15:09'),
(10, 'Senetory Department', 0, '2019-02-03 04:49:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_access`
--
ALTER TABLE `admin_access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assing_technician_complaint`
--
ALTER TABLE `assing_technician_complaint`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clg_flr_lby_asset_des`
--
ALTER TABLE `clg_flr_lby_asset_des`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_chat`
--
ALTER TABLE `cms_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_clg_flr_asset_des`
--
ALTER TABLE `cms_clg_flr_asset_des`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_clg_room`
--
ALTER TABLE `cms_clg_room`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `cms_college_department`
--
ALTER TABLE `cms_college_department`
  ADD PRIMARY KEY (`dep_id`);

--
-- Indexes for table `cms_college_floor_assets`
--
ALTER TABLE `cms_college_floor_assets`
  ADD PRIMARY KEY (`asset_id`);

--
-- Indexes for table `cms_floore_details`
--
ALTER TABLE `cms_floore_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_groups`
--
ALTER TABLE `cms_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `cms_hostel_details`
--
ALTER TABLE `cms_hostel_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_hostel_flr_asset_des`
--
ALTER TABLE `cms_hostel_flr_asset_des`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_jiiu_assing_user`
--
ALTER TABLE `cms_jiiu_assing_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_room_details`
--
ALTER TABLE `cms_room_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hostel_actual_flr_asset_des`
--
ALTER TABLE `hostel_actual_flr_asset_des`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hstl_flr_lby_asset_des`
--
ALTER TABLE `hstl_flr_lby_asset_des`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_state`
--
ALTER TABLE `login_state`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `login_state1`
--
ALTER TABLE `login_state1`
  ADD PRIMARY KEY (`uid1`);

--
-- Indexes for table `quatation_detail`
--
ALTER TABLE `quatation_detail`
  ADD PRIMARY KEY (`quat_id`);

--
-- Indexes for table `quatation_list`
--
ALTER TABLE `quatation_list`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `room_list`
--
ALTER TABLE `room_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `technician_exp`
--
ALTER TABLE `technician_exp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userdetail`
--
ALTER TABLE `userdetail`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `user_ass`
--
ALTER TABLE `user_ass`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_complaint_type`
--
ALTER TABLE `user_complaint_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vid`);

--
-- Indexes for table `vendor_category`
--
ALTER TABLE `vendor_category`
  ADD PRIMARY KEY (`cat_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assing_technician_complaint`
--
ALTER TABLE `assing_technician_complaint`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `clg_flr_lby_asset_des`
--
ALTER TABLE `clg_flr_lby_asset_des`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cms_chat`
--
ALTER TABLE `cms_chat`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `cms_clg_flr_asset_des`
--
ALTER TABLE `cms_clg_flr_asset_des`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `cms_clg_room`
--
ALTER TABLE `cms_clg_room`
  MODIFY `room_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `cms_college_department`
--
ALTER TABLE `cms_college_department`
  MODIFY `dep_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cms_college_floor_assets`
--
ALTER TABLE `cms_college_floor_assets`
  MODIFY `asset_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `cms_hostel_flr_asset_des`
--
ALTER TABLE `cms_hostel_flr_asset_des`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `cms_jiiu_assing_user`
--
ALTER TABLE `cms_jiiu_assing_user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cms_room_details`
--
ALTER TABLE `cms_room_details`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=423;

--
-- AUTO_INCREMENT for table `hostel_actual_flr_asset_des`
--
ALTER TABLE `hostel_actual_flr_asset_des`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `hstl_flr_lby_asset_des`
--
ALTER TABLE `hstl_flr_lby_asset_des`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `login_state`
--
ALTER TABLE `login_state`
  MODIFY `uid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `login_state1`
--
ALTER TABLE `login_state1`
  MODIFY `uid1` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quatation_detail`
--
ALTER TABLE `quatation_detail`
  MODIFY `quat_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=580;

--
-- AUTO_INCREMENT for table `quatation_list`
--
ALTER TABLE `quatation_list`
  MODIFY `item_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT for table `room_list`
--
ALTER TABLE `room_list`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `technician_exp`
--
ALTER TABLE `technician_exp`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `userdetail`
--
ALTER TABLE `userdetail`
  MODIFY `uid` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93386;

--
-- AUTO_INCREMENT for table `user_ass`
--
ALTER TABLE `user_ass`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `user_complaint_type`
--
ALTER TABLE `user_complaint_type`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=321;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vendor_category`
--
ALTER TABLE `vendor_category`
  MODIFY `cat_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
