-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 09, 2021 at 08:29 AM
-- Server version: 10.3.27-MariaDB-log-cll-lve
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `abuzzszo_karavelo`
--

-- --------------------------------------------------------

--
-- Table structure for table `contractor_detail`
--

CREATE TABLE `contractor_detail` (
  `id` int(11) NOT NULL,
  `emp_category` varchar(50) DEFAULT NULL,
  `title` varchar(10) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `passcode` varchar(500) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `bank_name` varchar(50) DEFAULT NULL,
  `bank_account_name` varchar(100) DEFAULT NULL,
  `account_no` varchar(15) DEFAULT NULL,
  `sort_code` varchar(15) DEFAULT NULL,
  `hasAccountant` tinyint(1) DEFAULT NULL,
  `company_register_no` varchar(20) DEFAULT NULL,
  `utr` varchar(20) DEFAULT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `birth_town` varchar(100) DEFAULT NULL,
  `birth_country` varchar(100) DEFAULT NULL,
  `emrg_contact_name` varchar(100) DEFAULT NULL,
  `emrg_contact` varchar(25) DEFAULT NULL,
  `national_insurance_no` varchar(50) DEFAULT NULL,
  `national_insurance_file` varchar(250) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `town_city` varchar(100) DEFAULT NULL,
  `county_district` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `adress_proof_file` varchar(250) DEFAULT NULL,
  `passport_no` varchar(50) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `country_of_pass_issue` varchar(50) DEFAULT NULL,
  `pass_isuue_date` date DEFAULT NULL,
  `pass_expiry_date` date DEFAULT NULL,
  `passport_file` varchar(250) DEFAULT NULL,
  `driving_licence_no` varchar(50) DEFAULT NULL,
  `driv_licenc_expiry` date DEFAULT NULL,
  `driv_issue_country` varchar(50) DEFAULT NULL,
  `criv_licen_front_file` varchar(250) DEFAULT NULL,
  `criv_licen_back_file` varchar(250) DEFAULT NULL,
  `work_permit_no` varchar(50) DEFAULT NULL,
  `permit_expiry` date DEFAULT NULL,
  `work_permit_front_file` varchar(250) DEFAULT NULL,
  `work_permit_back_file` varchar(250) DEFAULT NULL,
  `alcohol_test_file` varchar(250) DEFAULT NULL,
  `status` varchar(15) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contractor_detail`
--

INSERT INTO `contractor_detail` (`id`, `emp_category`, `title`, `name`, `email`, `passcode`, `phone`, `bank_name`, `bank_account_name`, `account_no`, `sort_code`, `hasAccountant`, `company_register_no`, `utr`, `gender`, `dob`, `birth_town`, `birth_country`, `emrg_contact_name`, `emrg_contact`, `national_insurance_no`, `national_insurance_file`, `address`, `town_city`, `county_district`, `postal_code`, `country`, `adress_proof_file`, `passport_no`, `nationality`, `country_of_pass_issue`, `pass_isuue_date`, `pass_expiry_date`, `passport_file`, `driving_licence_no`, `driv_licenc_expiry`, `driv_issue_country`, `criv_licen_front_file`, `criv_licen_back_file`, `work_permit_no`, `permit_expiry`, `work_permit_front_file`, `work_permit_back_file`, `alcohol_test_file`, `status`, `timestamp`) VALUES
(1, 'self-employed', NULL, 'fdhtgdfg', 'dfhddfgh', '', 'dfghdfghdhdf', 'ghdfghdf', NULL, 'ghdfghd', 'dfghdh', NULL, 'dfghdfghdfgh', 'dfghdfghf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'dfghd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '2021-03-01 20:07:32'),
(2, 'self-employed', NULL, 'Asad Kardame', 'asadkardame@gmail.com', '', '+447971623244', 'hsbc', NULL, '123456', '12-34-56', NULL, 'Softech World', 'asx1311346461', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '6, 998 N circular road', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '2021-03-02 13:57:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contractor_detail`
--
ALTER TABLE `contractor_detail`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contractor_detail`
--
ALTER TABLE `contractor_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
