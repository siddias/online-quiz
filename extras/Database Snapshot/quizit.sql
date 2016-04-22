-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2016 at 03:35 PM
-- Server version: 10.1.8-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quizit`
--

-- --------------------------------------------------------

--
-- Table structure for table `live_quiz41`
--

CREATE TABLE `live_quiz41` (
  `id` int(10) NOT NULL,
  `quizId` int(10) NOT NULL,
  `numSubmissions` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `live_quiz41`
--

INSERT INTO `live_quiz41` (`id`, `quizId`, `numSubmissions`) VALUES
(7, 61, 1);

-- --------------------------------------------------------

--
-- Table structure for table `live_quiz42`
--

CREATE TABLE `live_quiz42` (
  `id` int(10) NOT NULL,
  `quizId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `userId` int(10) NOT NULL COMMENT 'Primary Key',
  `id` varchar(15) NOT NULL COMMENT 'ID',
  `fname` varchar(30) NOT NULL COMMENT 'First Name',
  `lname` varchar(20) NOT NULL COMMENT 'Last Name',
  `email` varchar(100) NOT NULL COMMENT 'Email Id',
  `pass` varchar(100) NOT NULL COMMENT 'Password',
  `userType` enum('T','S') NOT NULL DEFAULT 'S' COMMENT 'Teacher/Student',
  `verified` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Verification status',
  `tokenCode` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`userId`, `id`, `fname`, `lname`, `email`, `pass`, `userType`, `verified`, `tokenCode`) VALUES
(41, '1BM13CS101', 'SIDDHARTH', 'DIAS', 'siddharth007.dias@gmail.com', 'ac32f600827910984f686ff3a7419a7c', 'T', 'Y', '4e6705b9f6c0eb0247827fa80e0efac1'),
(42, '1BM13CS007', 'SID', 'DIAS', 'siddias007@gmail.com', 'ac32f600827910984f686ff3a7419a7c', 'S', 'Y', '236f1ec79e53a4f575ff0036bbeb1c63');

-- --------------------------------------------------------

--
-- Table structure for table `past_quiz41`
--

CREATE TABLE `past_quiz41` (
  `id` int(10) NOT NULL,
  `quizId` int(10) NOT NULL,
  `numSubmissions` int(10) NOT NULL DEFAULT '0',
  `scoreAvg` decimal(4,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `past_quiz42`
--

CREATE TABLE `past_quiz42` (
  `id` int(10) NOT NULL,
  `quizId` int(10) NOT NULL,
  `score` int(10) NOT NULL,
  `submitDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `past_quiz42`
--

INSERT INTO `past_quiz42` (`id`, `quizId`, `score`, `submitDate`) VALUES
(9, 61, 0, '2016-04-17');

-- --------------------------------------------------------

--
-- Table structure for table `quiz55_answers`
--

CREATE TABLE `quiz55_answers` (
  `aId` int(10) NOT NULL,
  `qId` int(10) NOT NULL,
  `answer` varchar(100) NOT NULL,
  `correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quiz55_questions`
--

CREATE TABLE `quiz55_questions` (
  `qId` int(10) NOT NULL,
  `question` varchar(100) NOT NULL,
  `type` enum('TF','MC','FB') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quiz55_takers`
--

CREATE TABLE `quiz55_takers` (
  `id` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `taken` tinyint(1) DEFAULT NULL,
  `score` int(10) NOT NULL DEFAULT '0',
  `submitTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quiz56_answers`
--

CREATE TABLE `quiz56_answers` (
  `aId` int(10) NOT NULL,
  `qId` int(10) NOT NULL,
  `answer` varchar(100) NOT NULL,
  `correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz56_answers`
--

INSERT INTO `quiz56_answers` (`aId`, `qId`, `answer`, `correct`) VALUES
(1, 1, 'True', 1),
(2, 1, 'False', NULL),
(3, 2, 'True', 1),
(4, 2, 'False', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quiz56_questions`
--

CREATE TABLE `quiz56_questions` (
  `qId` int(10) NOT NULL,
  `question` varchar(100) NOT NULL,
  `type` enum('TF','MC','FB') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz56_questions`
--

INSERT INTO `quiz56_questions` (`qId`, `question`, `type`) VALUES
(1, 'f', 'TF'),
(2, 'sad', 'TF');

-- --------------------------------------------------------

--
-- Table structure for table `quiz56_takers`
--

CREATE TABLE `quiz56_takers` (
  `id` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `taken` tinyint(1) DEFAULT NULL,
  `score` int(10) NOT NULL DEFAULT '0',
  `submitTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz56_takers`
--

INSERT INTO `quiz56_takers` (`id`, `userId`, `taken`, `score`, `submitTime`) VALUES
(1, 42, NULL, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `quiz57_answers`
--

CREATE TABLE `quiz57_answers` (
  `aId` int(10) NOT NULL,
  `qId` int(10) NOT NULL,
  `answer` varchar(100) NOT NULL,
  `correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz57_answers`
--

INSERT INTO `quiz57_answers` (`aId`, `qId`, `answer`, `correct`) VALUES
(1, 1, 'True', 1),
(2, 1, 'False', NULL),
(3, 2, 'True', NULL),
(4, 2, 'False', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quiz57_questions`
--

CREATE TABLE `quiz57_questions` (
  `qId` int(10) NOT NULL,
  `question` varchar(100) NOT NULL,
  `type` enum('TF','MC','FB') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz57_questions`
--

INSERT INTO `quiz57_questions` (`qId`, `question`, `type`) VALUES
(1, 'A', 'TF'),
(2, 'B', 'TF');

-- --------------------------------------------------------

--
-- Table structure for table `quiz57_takers`
--

CREATE TABLE `quiz57_takers` (
  `id` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `taken` tinyint(1) DEFAULT NULL,
  `score` int(10) NOT NULL DEFAULT '0',
  `submitTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz57_takers`
--

INSERT INTO `quiz57_takers` (`id`, `userId`, `taken`, `score`, `submitTime`) VALUES
(1, 42, NULL, 0, '0000-00-00 00:00:00'),
(2, 42, NULL, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `quiz58_answers`
--

CREATE TABLE `quiz58_answers` (
  `aId` int(10) NOT NULL,
  `qId` int(10) NOT NULL,
  `answer` varchar(100) NOT NULL,
  `correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz58_answers`
--

INSERT INTO `quiz58_answers` (`aId`, `qId`, `answer`, `correct`) VALUES
(1, 1, 'True', 1),
(2, 1, 'False', NULL),
(3, 2, 'True', NULL),
(4, 2, 'False', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quiz58_questions`
--

CREATE TABLE `quiz58_questions` (
  `qId` int(10) NOT NULL,
  `question` varchar(100) NOT NULL,
  `type` enum('TF','MC','FB') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz58_questions`
--

INSERT INTO `quiz58_questions` (`qId`, `question`, `type`) VALUES
(1, 'BH', 'TF'),
(2, 'M', 'TF');

-- --------------------------------------------------------

--
-- Table structure for table `quiz58_takers`
--

CREATE TABLE `quiz58_takers` (
  `id` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `taken` tinyint(1) DEFAULT NULL,
  `score` int(10) NOT NULL DEFAULT '0',
  `submitTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz58_takers`
--

INSERT INTO `quiz58_takers` (`id`, `userId`, `taken`, `score`, `submitTime`) VALUES
(1, 42, NULL, 0, '0000-00-00 00:00:00'),
(2, 42, NULL, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `quiz59_answers`
--

CREATE TABLE `quiz59_answers` (
  `aId` int(10) NOT NULL,
  `qId` int(10) NOT NULL,
  `answer` varchar(100) NOT NULL,
  `correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz59_answers`
--

INSERT INTO `quiz59_answers` (`aId`, `qId`, `answer`, `correct`) VALUES
(1, 1, 'True', NULL),
(2, 1, 'False', 1),
(3, 2, 'True', 1),
(4, 2, 'False', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quiz59_questions`
--

CREATE TABLE `quiz59_questions` (
  `qId` int(10) NOT NULL,
  `question` varchar(100) NOT NULL,
  `type` enum('TF','MC','FB') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz59_questions`
--

INSERT INTO `quiz59_questions` (`qId`, `question`, `type`) VALUES
(1, 'm', 'TF'),
(2, 'm', 'TF');

-- --------------------------------------------------------

--
-- Table structure for table `quiz59_takers`
--

CREATE TABLE `quiz59_takers` (
  `id` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `taken` tinyint(1) DEFAULT NULL,
  `score` int(10) NOT NULL DEFAULT '0',
  `submitTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz59_takers`
--

INSERT INTO `quiz59_takers` (`id`, `userId`, `taken`, `score`, `submitTime`) VALUES
(1, 42, NULL, 0, '0000-00-00 00:00:00'),
(2, 42, NULL, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `quiz60_answers`
--

CREATE TABLE `quiz60_answers` (
  `aId` int(10) NOT NULL,
  `qId` int(10) NOT NULL,
  `answer` varchar(100) NOT NULL,
  `correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz60_answers`
--

INSERT INTO `quiz60_answers` (`aId`, `qId`, `answer`, `correct`) VALUES
(1, 1, 'True', 1),
(2, 1, 'False', NULL),
(3, 2, 'True', 1),
(4, 2, 'False', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quiz60_questions`
--

CREATE TABLE `quiz60_questions` (
  `qId` int(10) NOT NULL,
  `question` varchar(100) NOT NULL,
  `type` enum('TF','MC','FB') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz60_questions`
--

INSERT INTO `quiz60_questions` (`qId`, `question`, `type`) VALUES
(1, 'dsmf', 'TF'),
(2, 'sdf', 'TF');

-- --------------------------------------------------------

--
-- Table structure for table `quiz60_takers`
--

CREATE TABLE `quiz60_takers` (
  `id` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `taken` tinyint(1) DEFAULT NULL,
  `score` int(10) NOT NULL DEFAULT '0',
  `submitTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz60_takers`
--

INSERT INTO `quiz60_takers` (`id`, `userId`, `taken`, `score`, `submitTime`) VALUES
(1, 42, 1, 2, '2016-04-17 15:10:18');

-- --------------------------------------------------------

--
-- Table structure for table `quiz61_answers`
--

CREATE TABLE `quiz61_answers` (
  `aId` int(10) NOT NULL,
  `qId` int(10) NOT NULL,
  `answer` varchar(100) NOT NULL,
  `correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz61_answers`
--

INSERT INTO `quiz61_answers` (`aId`, `qId`, `answer`, `correct`) VALUES
(1, 1, 'True', NULL),
(2, 1, 'False', 1),
(3, 2, 'True', 1),
(4, 2, 'False', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quiz61_questions`
--

CREATE TABLE `quiz61_questions` (
  `qId` int(10) NOT NULL,
  `question` varchar(100) NOT NULL,
  `type` enum('TF','MC','FB') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz61_questions`
--

INSERT INTO `quiz61_questions` (`qId`, `question`, `type`) VALUES
(1, 'dfsdf', 'TF'),
(2, 'fsdf', 'TF');

-- --------------------------------------------------------

--
-- Table structure for table `quiz61_takers`
--

CREATE TABLE `quiz61_takers` (
  `id` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `taken` tinyint(1) DEFAULT NULL,
  `score` int(10) NOT NULL DEFAULT '0',
  `submitTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz61_takers`
--

INSERT INTO `quiz61_takers` (`id`, `userId`, `taken`, `score`, `submitTime`) VALUES
(1, 42, 1, 0, '2016-04-17 15:17:26');

-- --------------------------------------------------------

--
-- Table structure for table `quizlist`
--

CREATE TABLE `quizlist` (
  `quizId` int(10) NOT NULL COMMENT 'Primary Key',
  `userId` int(10) NOT NULL COMMENT 'Foreign Key',
  `name` varchar(50) NOT NULL COMMENT 'Name of Quiz',
  `sub` varchar(50) NOT NULL COMMENT 'Subject of Quiz',
  `duration` int(5) NOT NULL DEFAULT '20' COMMENT 'Time for Quiz',
  `startTime` datetime NOT NULL,
  `endTime` datetime NOT NULL,
  `numQuestions` int(10) NOT NULL COMMENT 'Number of Questions',
  `numQuizTakers` int(10) NOT NULL DEFAULT '0' COMMENT 'Number of Quiz Takers'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quizlist`
--

INSERT INTO `quizlist` (`quizId`, `userId`, `name`, `sub`, `duration`, `startTime`, `endTime`, `numQuestions`, `numQuizTakers`) VALUES
(61, 41, '2', '2', 20, '2016-04-17 15:15:00', '2016-04-17 15:19:00', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `u42q60_answers`
--

CREATE TABLE `u42q60_answers` (
  `id` int(10) NOT NULL,
  `qId` int(10) NOT NULL,
  `aId` int(10) NOT NULL,
  `correct` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `u42q60_answers`
--

INSERT INTO `u42q60_answers` (`id`, `qId`, `aId`, `correct`) VALUES
(1, 1, 1, 1),
(2, 2, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `u42q61_answers`
--

CREATE TABLE `u42q61_answers` (
  `id` int(10) NOT NULL,
  `qId` int(10) NOT NULL,
  `aId` int(10) NOT NULL,
  `correct` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `u42q61_answers`
--

INSERT INTO `u42q61_answers` (`id`, `qId`, `aId`, `correct`) VALUES
(1, 1, 2, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `live_quiz41`
--
ALTER TABLE `live_quiz41`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizId` (`quizId`);

--
-- Indexes for table `live_quiz42`
--
ALTER TABLE `live_quiz42`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizId` (`quizId`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `past_quiz41`
--
ALTER TABLE `past_quiz41`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizId` (`quizId`);

--
-- Indexes for table `past_quiz42`
--
ALTER TABLE `past_quiz42`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizId` (`quizId`);

--
-- Indexes for table `quiz55_answers`
--
ALTER TABLE `quiz55_answers`
  ADD PRIMARY KEY (`aId`),
  ADD KEY `qId` (`qId`);

--
-- Indexes for table `quiz55_questions`
--
ALTER TABLE `quiz55_questions`
  ADD PRIMARY KEY (`qId`);

--
-- Indexes for table `quiz55_takers`
--
ALTER TABLE `quiz55_takers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `quiz56_answers`
--
ALTER TABLE `quiz56_answers`
  ADD PRIMARY KEY (`aId`),
  ADD KEY `qId` (`qId`);

--
-- Indexes for table `quiz56_questions`
--
ALTER TABLE `quiz56_questions`
  ADD PRIMARY KEY (`qId`);

--
-- Indexes for table `quiz56_takers`
--
ALTER TABLE `quiz56_takers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `quiz57_answers`
--
ALTER TABLE `quiz57_answers`
  ADD PRIMARY KEY (`aId`),
  ADD KEY `qId` (`qId`);

--
-- Indexes for table `quiz57_questions`
--
ALTER TABLE `quiz57_questions`
  ADD PRIMARY KEY (`qId`);

--
-- Indexes for table `quiz57_takers`
--
ALTER TABLE `quiz57_takers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `quiz58_answers`
--
ALTER TABLE `quiz58_answers`
  ADD PRIMARY KEY (`aId`),
  ADD KEY `qId` (`qId`);

--
-- Indexes for table `quiz58_questions`
--
ALTER TABLE `quiz58_questions`
  ADD PRIMARY KEY (`qId`);

--
-- Indexes for table `quiz58_takers`
--
ALTER TABLE `quiz58_takers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `quiz59_answers`
--
ALTER TABLE `quiz59_answers`
  ADD PRIMARY KEY (`aId`),
  ADD KEY `qId` (`qId`);

--
-- Indexes for table `quiz59_questions`
--
ALTER TABLE `quiz59_questions`
  ADD PRIMARY KEY (`qId`);

--
-- Indexes for table `quiz59_takers`
--
ALTER TABLE `quiz59_takers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `quiz60_answers`
--
ALTER TABLE `quiz60_answers`
  ADD PRIMARY KEY (`aId`),
  ADD KEY `qId` (`qId`);

--
-- Indexes for table `quiz60_questions`
--
ALTER TABLE `quiz60_questions`
  ADD PRIMARY KEY (`qId`);

--
-- Indexes for table `quiz60_takers`
--
ALTER TABLE `quiz60_takers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `quiz61_answers`
--
ALTER TABLE `quiz61_answers`
  ADD PRIMARY KEY (`aId`),
  ADD KEY `qId` (`qId`);

--
-- Indexes for table `quiz61_questions`
--
ALTER TABLE `quiz61_questions`
  ADD PRIMARY KEY (`qId`);

--
-- Indexes for table `quiz61_takers`
--
ALTER TABLE `quiz61_takers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `quizlist`
--
ALTER TABLE `quizlist`
  ADD PRIMARY KEY (`quizId`),
  ADD KEY `members_fk` (`userId`);

--
-- Indexes for table `u42q60_answers`
--
ALTER TABLE `u42q60_answers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `qId` (`qId`),
  ADD UNIQUE KEY `aId` (`aId`);

--
-- Indexes for table `u42q61_answers`
--
ALTER TABLE `u42q61_answers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `qId` (`qId`),
  ADD UNIQUE KEY `aId` (`aId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `live_quiz41`
--
ALTER TABLE `live_quiz41`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `live_quiz42`
--
ALTER TABLE `live_quiz42`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `userId` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key', AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `past_quiz41`
--
ALTER TABLE `past_quiz41`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `past_quiz42`
--
ALTER TABLE `past_quiz42`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `quiz55_answers`
--
ALTER TABLE `quiz55_answers`
  MODIFY `aId` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `quiz55_questions`
--
ALTER TABLE `quiz55_questions`
  MODIFY `qId` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `quiz55_takers`
--
ALTER TABLE `quiz55_takers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `quiz56_answers`
--
ALTER TABLE `quiz56_answers`
  MODIFY `aId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `quiz56_questions`
--
ALTER TABLE `quiz56_questions`
  MODIFY `qId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `quiz56_takers`
--
ALTER TABLE `quiz56_takers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `quiz57_answers`
--
ALTER TABLE `quiz57_answers`
  MODIFY `aId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `quiz57_questions`
--
ALTER TABLE `quiz57_questions`
  MODIFY `qId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `quiz57_takers`
--
ALTER TABLE `quiz57_takers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `quiz58_answers`
--
ALTER TABLE `quiz58_answers`
  MODIFY `aId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `quiz58_questions`
--
ALTER TABLE `quiz58_questions`
  MODIFY `qId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `quiz58_takers`
--
ALTER TABLE `quiz58_takers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `quiz59_answers`
--
ALTER TABLE `quiz59_answers`
  MODIFY `aId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `quiz59_questions`
--
ALTER TABLE `quiz59_questions`
  MODIFY `qId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `quiz59_takers`
--
ALTER TABLE `quiz59_takers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `quiz60_answers`
--
ALTER TABLE `quiz60_answers`
  MODIFY `aId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `quiz60_questions`
--
ALTER TABLE `quiz60_questions`
  MODIFY `qId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `quiz60_takers`
--
ALTER TABLE `quiz60_takers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `quiz61_answers`
--
ALTER TABLE `quiz61_answers`
  MODIFY `aId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `quiz61_questions`
--
ALTER TABLE `quiz61_questions`
  MODIFY `qId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `quiz61_takers`
--
ALTER TABLE `quiz61_takers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `quizlist`
--
ALTER TABLE `quizlist`
  MODIFY `quizId` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key', AUTO_INCREMENT=62;
--
-- AUTO_INCREMENT for table `u42q60_answers`
--
ALTER TABLE `u42q60_answers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `u42q61_answers`
--
ALTER TABLE `u42q61_answers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `live_quiz41`
--
ALTER TABLE `live_quiz41`
  ADD CONSTRAINT `live_quiz41_ibfk_1` FOREIGN KEY (`quizId`) REFERENCES `quizlist` (`quizId`) ON DELETE CASCADE;

--
-- Constraints for table `live_quiz42`
--
ALTER TABLE `live_quiz42`
  ADD CONSTRAINT `live_quiz42_ibfk_1` FOREIGN KEY (`quizId`) REFERENCES `quizlist` (`quizId`) ON DELETE CASCADE;

--
-- Constraints for table `past_quiz41`
--
ALTER TABLE `past_quiz41`
  ADD CONSTRAINT `past_quiz41_ibfk_1` FOREIGN KEY (`quizId`) REFERENCES `quizlist` (`quizId`) ON DELETE CASCADE;

--
-- Constraints for table `past_quiz42`
--
ALTER TABLE `past_quiz42`
  ADD CONSTRAINT `past_quiz42_ibfk_1` FOREIGN KEY (`quizId`) REFERENCES `quizlist` (`quizId`) ON DELETE CASCADE;

--
-- Constraints for table `quiz55_answers`
--
ALTER TABLE `quiz55_answers`
  ADD CONSTRAINT `quiz55_answers_ibfk_1` FOREIGN KEY (`qId`) REFERENCES `quiz55_questions` (`qId`) ON DELETE CASCADE;

--
-- Constraints for table `quiz55_takers`
--
ALTER TABLE `quiz55_takers`
  ADD CONSTRAINT `quiz55_takers_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `members` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `quiz56_answers`
--
ALTER TABLE `quiz56_answers`
  ADD CONSTRAINT `quiz56_answers_ibfk_1` FOREIGN KEY (`qId`) REFERENCES `quiz56_questions` (`qId`) ON DELETE CASCADE;

--
-- Constraints for table `quiz56_takers`
--
ALTER TABLE `quiz56_takers`
  ADD CONSTRAINT `quiz56_takers_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `members` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `quiz57_answers`
--
ALTER TABLE `quiz57_answers`
  ADD CONSTRAINT `quiz57_answers_ibfk_1` FOREIGN KEY (`qId`) REFERENCES `quiz57_questions` (`qId`) ON DELETE CASCADE;

--
-- Constraints for table `quiz57_takers`
--
ALTER TABLE `quiz57_takers`
  ADD CONSTRAINT `quiz57_takers_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `members` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `quiz58_answers`
--
ALTER TABLE `quiz58_answers`
  ADD CONSTRAINT `quiz58_answers_ibfk_1` FOREIGN KEY (`qId`) REFERENCES `quiz58_questions` (`qId`) ON DELETE CASCADE;

--
-- Constraints for table `quiz58_takers`
--
ALTER TABLE `quiz58_takers`
  ADD CONSTRAINT `quiz58_takers_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `members` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `quiz59_answers`
--
ALTER TABLE `quiz59_answers`
  ADD CONSTRAINT `quiz59_answers_ibfk_1` FOREIGN KEY (`qId`) REFERENCES `quiz59_questions` (`qId`) ON DELETE CASCADE;

--
-- Constraints for table `quiz59_takers`
--
ALTER TABLE `quiz59_takers`
  ADD CONSTRAINT `quiz59_takers_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `members` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `quiz60_answers`
--
ALTER TABLE `quiz60_answers`
  ADD CONSTRAINT `quiz60_answers_ibfk_1` FOREIGN KEY (`qId`) REFERENCES `quiz60_questions` (`qId`) ON DELETE CASCADE;

--
-- Constraints for table `quiz60_takers`
--
ALTER TABLE `quiz60_takers`
  ADD CONSTRAINT `quiz60_takers_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `members` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `quiz61_answers`
--
ALTER TABLE `quiz61_answers`
  ADD CONSTRAINT `quiz61_answers_ibfk_1` FOREIGN KEY (`qId`) REFERENCES `quiz61_questions` (`qId`) ON DELETE CASCADE;

--
-- Constraints for table `quiz61_takers`
--
ALTER TABLE `quiz61_takers`
  ADD CONSTRAINT `quiz61_takers_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `members` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `quizlist`
--
ALTER TABLE `quizlist`
  ADD CONSTRAINT `members_fk` FOREIGN KEY (`userId`) REFERENCES `members` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `u42q60_answers`
--
ALTER TABLE `u42q60_answers`
  ADD CONSTRAINT `u42q60_answers_ibfk_1` FOREIGN KEY (`qId`) REFERENCES `quiz60_questions` (`qId`) ON DELETE CASCADE,
  ADD CONSTRAINT `u42q60_answers_ibfk_2` FOREIGN KEY (`aId`) REFERENCES `quiz60_answers` (`aId`) ON DELETE CASCADE;

--
-- Constraints for table `u42q61_answers`
--
ALTER TABLE `u42q61_answers`
  ADD CONSTRAINT `u42q61_answers_ibfk_1` FOREIGN KEY (`qId`) REFERENCES `quiz61_questions` (`qId`) ON DELETE CASCADE,
  ADD CONSTRAINT `u42q61_answers_ibfk_2` FOREIGN KEY (`aId`) REFERENCES `quiz61_answers` (`aId`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
