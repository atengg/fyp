-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2019 at 11:49 AM
-- Server version: 5.6.25
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fyp`
--

-- --------------------------------------------------------

--
-- Table structure for table `absent`
--

CREATE TABLE IF NOT EXISTS `absent` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `in_time` time NOT NULL,
  `out_time` time DEFAULT NULL,
  `work_hour` time DEFAULT NULL,
  `over_time` time DEFAULT NULL,
  `late_time` time DEFAULT NULL,
  `early_out_time` time DEFAULT NULL,
  `in_location` varchar(200) NOT NULL,
  `out_location` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `history_qr`
--

CREATE TABLE IF NOT EXISTS `history_qr` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `history_qr`
--

INSERT INTO `history_qr` (`id`, `name`) VALUES
(3, 'Sarah Aqilah'),
(4, 'sasa eaea'),
(5, 'una dada'),
(6, 'aa adad');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `out_time` time NOT NULL,
  `many_employee` varchar(50) NOT NULL,
  `key_insert` char(40) NOT NULL,
  `timezone` varchar(100) NOT NULL,
  `recaptcha` tinyint(4) NOT NULL,
  `date` date NOT NULL,
  `offence` varchar(50) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `start_time`, `out_time`, `many_employee`, `key_insert`, `timezone`, `recaptcha`, `date`, `offence`) VALUES
(1, '08:00:00', '17:00:00', '8', '51e69892ab49df85c6230ccc57f8e1d1606cabbb', 'Asia/Makassar', 0, '0000-00-00', '');

-- --------------------------------------------------------

--
-- Table structure for table `summons`
--

CREATE TABLE IF NOT EXISTS `summons` (
  `time` time NOT NULL,
  `date` date NOT NULL,
  `offence` varchar(50) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE IF NOT EXISTS `tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `user_id` int(10) NOT NULL,
  `created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `role` varchar(10) NOT NULL,
  `password` text NOT NULL,
  `last_login` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `s_mcard` varchar(50) NOT NULL,
  `s_ic` varchar(50) NOT NULL,
  `s_plate` varchar(50) NOT NULL,
  `s_type` varchar(50) NOT NULL,
  `s_program` varchar(50) NOT NULL,
  `banned_users` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `first_name`, `last_name`, `role`, `password`, `last_login`, `status`, `s_mcard`, `s_ic`, `s_plate`, `s_type`, `s_program`, `banned_users`) VALUES
(1, 'admin@gmail.com', 'admin', 'admin', '1', 'sha256:1000:afMG4GHoH0gR1YsZz3Odq6W3n1M7wTUG:gjbZhnxB9K0im16imrc+yDM23q+8n1Wm', '2019-11-17 08:30:05 AM', 'approved', '', '', '', '', '', NULL),
(4, 'sarah@gmail.com', 'Sarah', 'Aqilah', '4', 'sha256:1000:JDJ5JDEwJHB6Q1U0WFpoYVUxemJRSU5hdzlqWHVrYndhU08uZGNwbkM2dTQ1a1Y0bEk0ajRsUHNyQlVP:xZ06U5LewWEk5HNE9ZZPRco6M14wFr28', '2019-11-17 09:12:09 AM', 'approved', '', '', '', '', '', NULL),
(5, 'sasa@gmail.com', 'aa', 'adad', '', 'sha256:1000:JDJ5JDEwJHUzUVcwaUFIcUprRHFycjRZeENldS50Y3J4Nlg4cWdlajBZcFFmS2RZdXFsOG5aNVRoZHNX:IAzKQ9+YGl0s0mk6qpmAkBhOY/e8dEMP', '', 'approved', '', '', '', '', '', 'unban');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absent`
--
ALTER TABLE `absent`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_qr`
--
ALTER TABLE `history_qr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `summons`
--
ALTER TABLE `summons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
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
-- AUTO_INCREMENT for table `absent`
--
ALTER TABLE `absent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `history_qr`
--
ALTER TABLE `history_qr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `summons`
--
ALTER TABLE `summons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
