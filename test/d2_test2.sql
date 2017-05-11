-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 11, 2017 at 03:24 PM
-- Server version: 5.7.18-0ubuntu0.16.04.1
-- PHP Version: 5.6.30-10+deb.sury.org~xenial+2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `d2_test2`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_client`
--

CREATE TABLE `tbl_client` (
  `client_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_client`
--

INSERT INTO `tbl_client` (`client_id`, `client_name`, `level`) VALUES
(1, 'Client 1', 2),
(2, 'CLient 2', 2),
(3, 'Client 3', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_client_app`
--

CREATE TABLE `tbl_client_app` (
  `app_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `app_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_client_app`
--

INSERT INTO `tbl_client_app` (`app_id`, `client_id`, `app_name`) VALUES
(1, 1, 'APP 1'),
(2, 1, 'App 2'),
(3, 2, 'Client 2 APp 1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_client_group`
--

CREATE TABLE `tbl_client_group` (
  `auto_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_client_group`
--

INSERT INTO `tbl_client_group` (`auto_id`, `client_id`, `group_id`) VALUES
(1, 2, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_client_info`
--

CREATE TABLE `tbl_client_info` (
  `info_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL DEFAULT '0',
  `passcode` varchar(255) DEFAULT NULL,
  `visa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_client_info`
--

INSERT INTO `tbl_client_info` (`info_id`, `client_id`, `passcode`, `visa`) VALUES
(1, 2, 'Passcode 1', 'Visa 1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_client_type`
--

CREATE TABLE `tbl_client_type` (
  `id` int(11) NOT NULL,
  `level_name` varchar(255) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_client_type`
--

INSERT INTO `tbl_client_type` (`id`, `level_name`, `description`) VALUES
(1, 'Level 1', 'Level 1'),
(2, 'Level 2', 'Level 2');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_group`
--

CREATE TABLE `tbl_group` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_group`
--

INSERT INTO `tbl_group` (`id`, `name`, `description`) VALUES
(1, 'Group 1', 'Group Level 1'),
(2, 'Group 2', 'Group Level 2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_client`
--
ALTER TABLE `tbl_client`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `tbl_client_app`
--
ALTER TABLE `tbl_client_app`
  ADD PRIMARY KEY (`app_id`);

--
-- Indexes for table `tbl_client_group`
--
ALTER TABLE `tbl_client_group`
  ADD PRIMARY KEY (`auto_id`);

--
-- Indexes for table `tbl_client_info`
--
ALTER TABLE `tbl_client_info`
  ADD PRIMARY KEY (`info_id`);

--
-- Indexes for table `tbl_client_type`
--
ALTER TABLE `tbl_client_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_group`
--
ALTER TABLE `tbl_group`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_client`
--
ALTER TABLE `tbl_client`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_client_app`
--
ALTER TABLE `tbl_client_app`
  MODIFY `app_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_client_group`
--
ALTER TABLE `tbl_client_group`
  MODIFY `auto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_client_info`
--
ALTER TABLE `tbl_client_info`
  MODIFY `info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_client_type`
--
ALTER TABLE `tbl_client_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_group`
--
ALTER TABLE `tbl_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
