-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 07, 2014 at 10:22 AM
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
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `subsidy_steps` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `binet_admin`
--

CREATE TABLE IF NOT EXISTS `binet_admin` (
  `binet` int(11) NOT NULL,
  `validated_by` int(11) DEFAULT NULL,
  `student` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

CREATE TABLE IF NOT EXISTS `income` (
`id` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `origin` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `kes_validation_by` int(11) DEFAULT NULL,
  `comment` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `income_origin`
--

CREATE TABLE IF NOT EXISTS `income_origin` (
`id` tinyint(4) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `income_tag`
--

CREATE TABLE IF NOT EXISTS `income_tag` (
  `income` int(11) NOT NULL,
  `tag` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `spending`
--

CREATE TABLE IF NOT EXISTS `spending` (
`id` int(11) NOT NULL,
  `date` date NOT NULL COMMENT 'creation date',
  `amount` int(10) unsigned NOT NULL,
  `bill` varchar(30) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `binet_validation_by` int(11) DEFAULT NULL,
  `kes_validation_by` int(11) DEFAULT NULL,
  `paid_by` int(11) DEFAULT NULL,
  `comment` tinytext NOT NULL COMMENT 'name of the spending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `spending_subsidy`
--

CREATE TABLE IF NOT EXISTS `spending_subsidy` (
  `spending` int(11) NOT NULL,
  `subsidy` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `spending_tag`
--

CREATE TABLE IF NOT EXISTS `spending_tag` (
  `spending` int(11) NOT NULL,
  `tag` int(11) NOT NULL
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
  `origin` int(11) NOT NULL,
  `beneficiary` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `expiration_date` date DEFAULT NULL,
  `purpose` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subsidy_tag`
--

CREATE TABLE IF NOT EXISTS `subsidy_tag` (
  `subsidy` int(11) NOT NULL,
  `tag` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
`id` int(11) NOT NULL,
  `date` date NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `binet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `binet`
--
ALTER TABLE `binet`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income`
--
ALTER TABLE `income`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income_origin`
--
ALTER TABLE `income_origin`
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
-- AUTO_INCREMENT for table `income`
--
ALTER TABLE `income`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `income_origin`
--
ALTER TABLE `income_origin`
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
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
