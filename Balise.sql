-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 14, 2014 at 10:03 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `Balise`
--

-- --------------------------------------------------------

--
-- Table structure for table `binet`
--

CREATE TABLE IF NOT EXISTS `binet` (
`id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `subsidy_provider` tinyint(1) NOT NULL DEFAULT '0',
  `current_term` smallint(6) DEFAULT NULL,
  `subsidy_steps` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `binet_admin`
--

CREATE TABLE IF NOT EXISTS `binet_admin` (
  `binet` int(11) NOT NULL,
  `student` int(11) NOT NULL,
  `term` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `budget`
--

CREATE TABLE IF NOT EXISTS `budget` (
`id` int(11) NOT NULL,
  `binet` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `term` smallint(11) NOT NULL,
  `label` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `budget_tag`
--

CREATE TABLE IF NOT EXISTS `budget_tag` (
  `budget` int(11) NOT NULL,
  `tag` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

CREATE TABLE IF NOT EXISTS `income` (
`id` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `binet` int(11) NOT NULL,
  `term` smallint(6) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `kes_validation_by` int(11) DEFAULT NULL,
  `comment` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `income_budget`
--

CREATE TABLE IF NOT EXISTS `income_budget` (
  `income` int(11) NOT NULL,
  `budget` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `income_type`
--

CREATE TABLE IF NOT EXISTS `income_type` (
`id` tinyint(4) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `spending`
--

CREATE TABLE IF NOT EXISTS `spending` (
`id` int(11) NOT NULL,
  `date` date NOT NULL COMMENT 'creation date',
  `amount` int(10) unsigned NOT NULL,
  `binet` int(11) NOT NULL,
  `term` smallint(6) NOT NULL,
  `bill` varchar(30) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `binet_validation_by` int(11) DEFAULT NULL,
  `kes_validation_by` int(11) DEFAULT NULL,
  `paid_by` int(11) DEFAULT NULL,
  `comment` tinytext NOT NULL COMMENT 'name of the spending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `spending_budget`
--

CREATE TABLE IF NOT EXISTS `spending_budget` (
  `spending` int(11) NOT NULL,
  `budget` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
`id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subsidy`
--

CREATE TABLE IF NOT EXISTS `subsidy` (
`id` int(11) NOT NULL,
  `budget` int(11) NOT NULL,
  `requested_amount` int(11) NOT NULL,
  `granted_amount` int(11) DEFAULT NULL,
  `purpose` tinytext NOT NULL,
  `explanation` text,
  `wave` int(11) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
`id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wave`
--

CREATE TABLE IF NOT EXISTS `wave` (
`id` int(11) NOT NULL,
  `binet` int(11) NOT NULL,
  `term` smallint(6) NOT NULL,
  `submission_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `binet`
--
ALTER TABLE `binet`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `budget`
--
ALTER TABLE `budget`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income`
--
ALTER TABLE `income`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income_type`
--
ALTER TABLE `income_type`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `spending`
--
ALTER TABLE `spending`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `bill` (`bill`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subsidy`
--
ALTER TABLE `subsidy`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`), ADD UNIQUE KEY `name_2` (`name`);

--
-- Indexes for table `wave`
--
ALTER TABLE `wave`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `binet`
--
ALTER TABLE `binet`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `budget`
--
ALTER TABLE `budget`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `income`
--
ALTER TABLE `income`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `income_type`
--
ALTER TABLE `income_type`
MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `spending`
--
ALTER TABLE `spending`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `subsidy`
--
ALTER TABLE `subsidy`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wave`
--
ALTER TABLE `wave`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
