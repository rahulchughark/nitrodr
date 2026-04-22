-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 05, 2020 at 03:27 PM
-- Server version: 5.7.26-0ubuntu0.18.10.1
-- PHP Version: 7.2.19-0ubuntu0.18.10.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coreldr`
--

-- --------------------------------------------------------

--
-- Table structure for table `lead_notification`
--

CREATE TABLE `lead_notification` (
  `id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` varchar(50) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `submitted_by` varchar(100) DEFAULT NULL,
  `sender_type` varchar(100) DEFAULT NULL,
  `partner_name` varchar(100) DEFAULT NULL,
  `is_read` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lead_notification`
--

INSERT INTO `lead_notification` (`id`, `type_id`, `sender_id`, `receiver_id`, `title`, `company_name`, `submitted_by`, `sender_type`, `partner_name`, `is_read`, `created_at`) VALUES
(1, 61371, 15, '1,12,218', 'Request Incoming to LC', 'National Film Archive of India', 'Hetal Parmar ', 'Partner', 'Gaurav Pawar', 0, '2020-03-05 11:25:08'),
(2, 61234, 15, '1,12,218', 'Request BD to LC', 'Bhairav Jewellers', 'Gaurav Pawar', 'Partner', 'Gaurav Pawar', 1, '2020-03-05 11:25:13'),
(3, 61234, 1, '15', 'Approved Request BD to LC', 'Bhairav Jewellers', 'Gaurav Pawar', 'Admin', '', 1, '2020-03-05 11:25:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lead_notification`
--
ALTER TABLE `lead_notification`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lead_notification`
--
ALTER TABLE `lead_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
