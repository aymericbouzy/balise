-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Jan 28, 2015 at 11:11 PM
-- Server version: 5.5.38
-- PHP Version: 5.6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `balise`
--

-- --------------------------------------------------------

--
-- Table structure for table `binet`
--

CREATE TABLE `binet` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `clean_name` varchar(50) NOT NULL,
  `description` text,
  `subsidy_provider` tinyint(1) NOT NULL DEFAULT '0',
  `current_term` smallint(6) DEFAULT NULL,
  `subsidy_steps` text
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `binet`
--

INSERT INTO `binet` (`id`, `name`, `clean_name`, `description`, `subsidy_provider`, `current_term`, `subsidy_steps`) VALUES
(1, 'KÃ¨s', 'kes', '', 1, 2013, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `binet_admin`
--

CREATE TABLE `binet_admin` (
  `binet` int(11) NOT NULL,
  `student` int(11) NOT NULL,
  `term` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `binet_admin`
--

INSERT INTO `binet_admin` (`binet`, `student`, `term`) VALUES
(1, 1, 2013);

-- --------------------------------------------------------

--
-- Table structure for table `budget`
--

CREATE TABLE `budget` (
  `id` int(11) NOT NULL,
  `binet` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `term` smallint(11) NOT NULL,
  `label` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `budget_tag`
--

CREATE TABLE `budget_tag` (
  `budget` int(11) NOT NULL,
  `tag` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `operation`
--

CREATE TABLE `operation` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `binet` int(11) NOT NULL,
  `term` smallint(6) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `bill` varchar(30) DEFAULT NULL,
  `reference` varchar(30) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `binet_validation_by` int(11) DEFAULT NULL,
  `kes_validation_by` int(11) DEFAULT NULL,
  `paid_by` int(11) DEFAULT NULL,
  `comment` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `operation_budget`
--

CREATE TABLE `operation_budget` (
  `operation` int(11) NOT NULL,
  `budget` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `operation_type`
--

CREATE TABLE `operation_type` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `operation_type`
--

INSERT INTO `operation_type` (`id`, `name`) VALUES
(1, 'chèque'),
(2, 'espèces'),
(3, 'virement');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `wave` int(11) NOT NULL,
  `answer` text NOT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `hruid` varchar(100) NOT NULL,
  `name` varchar(80) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subsidy`
--

CREATE TABLE `subsidy` (
  `id` int(11) NOT NULL,
  `budget` int(11) NOT NULL,
  `requested_amount` int(11) NOT NULL,
  `granted_amount` int(11) DEFAULT NULL,
  `purpose` tinytext NOT NULL,
  `explanation` text,
  `request` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `clean_name` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wave`
--

CREATE TABLE `wave` (
  `id` int(11) NOT NULL,
  `binet` int(11) NOT NULL,
  `term` smallint(6) NOT NULL,
  `submission_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `question` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `binet`
--
ALTER TABLE `binet`
ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`), ADD UNIQUE KEY `clean_name` (`clean_name`);

--
-- Indexes for table `binet_admin`
--
ALTER TABLE `binet_admin`
ADD UNIQUE KEY `binet_2` (`binet`,`student`,`term`), ADD KEY `binet` (`binet`);

--
-- Indexes for table `budget`
--
ALTER TABLE `budget`
ADD PRIMARY KEY (`id`);

--
-- Indexes for table `operation`
--
ALTER TABLE `operation`
ADD PRIMARY KEY (`id`);

--
-- Indexes for table `operation_type`
--
ALTER TABLE `operation_type`
ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
ADD PRIMARY KEY (`id`);

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
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `budget`
--
ALTER TABLE `budget`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `operation`
--
ALTER TABLE `operation`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `operation_type`
--
ALTER TABLE `operation_type`
MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
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
