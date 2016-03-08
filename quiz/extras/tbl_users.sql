-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2015 at 02:00 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dbtest`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

/*CREATE TABLE IF NOT EXISTS `tbl_users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(100) NOT NULL,
  `userEmail` varchar(100) NOT NULL,
  `userPass` varchar(100) NOT NULL,
  `userStatus` enum('Y','N') NOT NULL DEFAULT 'N',
  `tokenCode` varchar(100) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `userEmail` (`userEmail`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
*/
--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`userID`, `userName`, `userEmail`, `userPass`, `userStatus`, `tokenCode`) VALUES
(1, 'gautam', 'gautamnagraj0@gmail.com', '202cb962ac59075b964b07152d234b70', 'N', 'a91f7123ffe33f224a7592f923bb141a');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
CREATE TABLE `quizit`.`members` (
`userId` INT(10) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key' ,
 `id` VARCHAR(15) NOT NULL COMMENT 'ID' ,
 `fname` VARCHAR(30) NOT NULL COMMENT 'First Name' ,
 `lname` VARCHAR(20) NOT NULL COMMENT 'Last Name' ,
 `email` VARCHAR(100) NOT NULL COMMENT 'Email Id' ,
 `pass` VARCHAR(100) NOT NULL COMMENT 'Password' ,
 `userType` ENUM('T','S') NOT NULL DEFAULT 'S' COMMENT 'Teacher/Student' ,
 `verified` ENUM('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Verification status' ,
 `tokenCode` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`userId`),
  UNIQUE (`id`),
  UNIQUE (`email`)) ENGINE = InnoDB;


  CREATE TABLE `quizlist` ( `quizId` INT(10) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key' , `userId` INT(10) NOT NULL COMMENT 'Foreign Key' , `duration` INT(10) NOT NULL COMMENT 'Time for Quiz' , PRIMARY KEY (`quizId`), CONSTRAINT members_fk FOREIGN KEY(`userId`) REFERENCES `members`(`userId`) ON DELETE CASCADE )ENGINE = InnoDB 
