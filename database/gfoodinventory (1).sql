-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2017 at 10:48 AM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gfoodinventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `main_menu`
--

CREATE TABLE `main_menu` (
  `id` int(11) NOT NULL,
  `menu_description` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `main_menu`
--

INSERT INTO `main_menu` (`id`, `menu_description`) VALUES
(1, 'Transaction Manager'),
(2, 'Reports'),
(3, 'Stock Situation');

-- --------------------------------------------------------

--
-- Table structure for table `stock_description`
--

CREATE TABLE `stock_description` (
  `id` int(11) NOT NULL,
  `stock_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stock_description`
--

INSERT INTO `stock_description` (`id`, `stock_description`) VALUES
(1, 'Fresh GF'),
(2, 'Fresh EN'),
(3, 'Dry GF'),
(4, 'Dry EN');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `trans_date` date DEFAULT NULL COMMENT 'Transaction Date',
  `trans_type` int(11) NOT NULL COMMENT 'Type of Transaction',
  `trans_stock_nr_in` int(11) DEFAULT NULL COMMENT 'Stock Nr IN',
  `trans_stock_nr_out` int(11) DEFAULT NULL COMMENT 'Stock Nr OUT',
  `trans_in` decimal(60,2) DEFAULT NULL COMMENT 'Quantity IN',
  `trans_out` decimal(60,2) DEFAULT NULL COMMENT 'Quantity OUT'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `trans_date`, `trans_type`, `trans_stock_nr_in`, `trans_stock_nr_out`, `trans_in`, `trans_out`) VALUES
(167, '0000-00-00', 1, 2, NULL, '13.00', NULL),
(168, '0000-00-00', 1, 1, NULL, '40.00', NULL),
(170, '0000-00-00', 2, 2, 3, '12.00', '13.00'),
(173, '0000-00-00', 5, NULL, 1, NULL, '12.22'),
(178, '0000-00-00', 7, NULL, 2, NULL, '23.43'),
(179, '0000-00-00', 6, 1, 2, '3.00', '3.40'),
(180, '2017-12-07', 7, NULL, 2, NULL, '65.00'),
(181, '2017-12-07', 5, NULL, 3, NULL, '7.00'),
(182, '2017-12-09', 3, NULL, 1, NULL, '1.25'),
(183, '2017-11-29', 1, 4, NULL, '2.00', NULL),
(184, '2017-11-26', 1, 2, NULL, '13.00', NULL),
(185, '2017-11-28', 1, 1, NULL, '65.00', NULL),
(186, '2017-11-29', 1, 3, NULL, '32.00', NULL),
(187, '2017-11-27', 2, 2, 1, '34.00', '45.00'),
(188, '2017-12-13', 1, 1, NULL, '12.00', NULL),
(189, '1970-01-01', 2, 1, 2, '12.00', '23.00');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_type`
--

CREATE TABLE `transaction_type` (
  `id` int(11) NOT NULL,
  `trans_flag_stock_in` tinyint(1) NOT NULL,
  `trans_flag_stock_out` tinyint(1) NOT NULL,
  `trans_description` text NOT NULL,
  `trans_value` decimal(60,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transaction_type`
--

INSERT INTO `transaction_type` (`id`, `trans_flag_stock_in`, `trans_flag_stock_out`, `trans_description`, `trans_value`) VALUES
(1, 1, 0, 'Harvesting', NULL),
(2, 1, 1, 'Dry', NULL),
(3, 0, 1, 'Sale', '9.01'),
(4, 1, 0, 'food', NULL),
(5, 0, 1, 'rasha kna', NULL),
(6, 1, 1, 'olamba', '9.20'),
(7, 0, 1, 'truck', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_name` varchar(150) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_attempt` int(1) NOT NULL,
  `user_menu_access` int(11) NOT NULL,
  `user_disabled` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `user_password`, `user_attempt`, `user_menu_access`, `user_disabled`) VALUES
(1, 'Admin', '1234', 0, 111, 0),
(2, 'User 1', '2345', 1, 100, 0),
(3, 'User 2', '3456', 0, 101, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `main_menu`
--
ALTER TABLE `main_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_description`
--
ALTER TABLE `stock_description`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trans_type` (`trans_type`),
  ADD KEY `trans_stock_nr_in` (`trans_stock_nr_in`),
  ADD KEY `trans_stock_nr_out` (`trans_stock_nr_out`);

--
-- Indexes for table `transaction_type`
--
ALTER TABLE `transaction_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `main_menu`
--
ALTER TABLE `main_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stock_description`
--
ALTER TABLE `stock_description`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT for table `transaction_type`
--
ALTER TABLE `transaction_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `tran_stockdesc_in_fk` FOREIGN KEY (`trans_stock_nr_in`) REFERENCES `stock_description` (`id`),
  ADD CONSTRAINT `tran_stockdesc_out_fk` FOREIGN KEY (`trans_stock_nr_out`) REFERENCES `stock_description` (`id`),
  ADD CONSTRAINT `tran_trantype_fk` FOREIGN KEY (`trans_type`) REFERENCES `transaction_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
