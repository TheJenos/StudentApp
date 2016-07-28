-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 28, 2016 at 08:01 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `clan_chat`
--

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE IF NOT EXISTS `chats` (
  `cid` int(255) NOT NULL AUTO_INCREMENT,
  `Chat_name` varchar(255) NOT NULL,
  `User_names` text NOT NULL,
  `Started_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Gtype` enum('Normal','Group') NOT NULL,
  `Chat_image` varchar(255) NOT NULL DEFAULT 'group_pic.png',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `messagers`
--

CREATE TABLE IF NOT EXISTS `messagers` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `ChatID` int(255) NOT NULL,
  `From_user` varchar(45) NOT NULL,
  `Msg` text NOT NULL,
  `msg_delivered` text NOT NULL,
  `deleted` text NOT NULL,
  `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`mid`),
  KEY `FK_messagers_1` (`ChatID`),
  KEY `FK_messagers_2` (`From_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `NID` int(11) NOT NULL AUTO_INCREMENT,
  `Nuser` varchar(45) NOT NULL,
  `Ntype` enum('Commented','Tread Created') NOT NULL,
  `Ndata` text NOT NULL,
  `Ntxt` text NOT NULL,
  `Ntime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Nstatus` enum('Unreaded','Readed') NOT NULL DEFAULT 'Unreaded',
  `Nposted` varchar(45) NOT NULL,
  PRIMARY KEY (`NID`),
  KEY `FK_notification_1` (`Nuser`),
  KEY `FK_notification_2` (`Nposted`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE IF NOT EXISTS `subjects` (
  `SID` int(11) NOT NULL AUTO_INCREMENT,
  `Sname` varchar(255) NOT NULL,
  `Teacher` varchar(45) NOT NULL,
  `Started_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`SID`),
  KEY `FK_subjects_1` (`Teacher`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subjects_list`
--

CREATE TABLE IF NOT EXISTS `subjects_list` (
  `SLID` int(11) NOT NULL AUTO_INCREMENT,
  `User_name` varchar(45) NOT NULL,
  `SID` int(11) NOT NULL,
  PRIMARY KEY (`SLID`),
  KEY `FK_subjects_list_1` (`User_name`),
  KEY `FK_subjects_list_2` (`SID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subjects_threads`
--

CREATE TABLE IF NOT EXISTS `subjects_threads` (
  `TID` int(11) NOT NULL AUTO_INCREMENT,
  `SID` int(11) NOT NULL,
  `Started_User` varchar(45) NOT NULL,
  `TText` text NOT NULL,
  `Nusers` text NOT NULL,
  `Posted_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`TID`),
  KEY `FK_subjects_threads_1` (`SID`),
  KEY `FK_subjects_threads_2` (`Started_User`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subjects_threads_comments`
--

CREATE TABLE IF NOT EXISTS `subjects_threads_comments` (
  `CID` int(11) NOT NULL AUTO_INCREMENT,
  `TID` int(11) NOT NULL,
  `Cuser` varchar(45) NOT NULL,
  `Ctype` enum('Normal','Unknown') NOT NULL DEFAULT 'Normal',
  `Ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Comment` text NOT NULL,
  PRIMARY KEY (`CID`),
  KEY `FK_subjects_threads_comments_1` (`TID`),
  KEY `FK_subjects_threads_comments_2` (`Cuser`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(10) NOT NULL AUTO_INCREMENT,
  `Email` varchar(45) NOT NULL,
  `Password` varchar(45) NOT NULL,
  `UType` enum('Student','Instructor') NOT NULL DEFAULT 'Student',
  `Name` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `IsMale` int(1) NOT NULL,
  `lastonline` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Profile_pic` varchar(500) NOT NULL DEFAULT 'profilepic.png',
  PRIMARY KEY (`uid`,`Email`),
  KEY `Email` (`Email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `Email`, `Password`, `UType`, `Name`, `dob`, `IsMale`, `lastonline`, `Profile_pic`) VALUES
(1, 'Unknown', '123', 'Student', 'Unknown', '2016-07-01', 1, '2016-07-28 17:53:59', 'profilepic.png');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messagers`
--
ALTER TABLE `messagers`
  ADD CONSTRAINT `FK_messagers_1` FOREIGN KEY (`ChatID`) REFERENCES `chats` (`cid`),
  ADD CONSTRAINT `FK_messagers_2` FOREIGN KEY (`From_user`) REFERENCES `user` (`Email`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `FK_notification_1` FOREIGN KEY (`Nuser`) REFERENCES `user` (`Email`),
  ADD CONSTRAINT `FK_notification_2` FOREIGN KEY (`Nposted`) REFERENCES `user` (`Email`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `FK_subjects_1` FOREIGN KEY (`Teacher`) REFERENCES `user` (`Email`);

--
-- Constraints for table `subjects_list`
--
ALTER TABLE `subjects_list`
  ADD CONSTRAINT `FK_subjects_list_1` FOREIGN KEY (`User_name`) REFERENCES `user` (`Email`),
  ADD CONSTRAINT `FK_subjects_list_2` FOREIGN KEY (`SID`) REFERENCES `subjects` (`SID`);

--
-- Constraints for table `subjects_threads`
--
ALTER TABLE `subjects_threads`
  ADD CONSTRAINT `FK_subjects_threads_1` FOREIGN KEY (`SID`) REFERENCES `subjects` (`SID`),
  ADD CONSTRAINT `FK_subjects_threads_2` FOREIGN KEY (`Started_User`) REFERENCES `user` (`Email`);

--
-- Constraints for table `subjects_threads_comments`
--
ALTER TABLE `subjects_threads_comments`
  ADD CONSTRAINT `FK_subjects_threads_comments_1` FOREIGN KEY (`TID`) REFERENCES `subjects_threads` (`TID`),
  ADD CONSTRAINT `FK_subjects_threads_comments_2` FOREIGN KEY (`Cuser`) REFERENCES `user` (`Email`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
