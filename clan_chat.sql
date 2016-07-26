-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2016 at 04:28 PM
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
  PRIMARY KEY (`cid`),
  UNIQUE KEY `id_2` (`cid`),
  KEY `id` (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`cid`, `Chat_name`, `User_names`, `Started_Date`, `Gtype`, `Chat_image`) VALUES
(11, 'NormalChat', 'user1,nadunnew', '2016-06-28 06:48:30', 'Normal', 'group_pic.png'),
(12, 'Yaluvo', 'user1,nadunnew,user2', '2016-06-28 06:49:11', 'Group', 'group_pic.png'),
(13, 'NormalChat', 'nadunnew,user2', '2016-06-28 12:05:17', 'Normal', 'group_pic.png'),
(14, 'NormalChat', 'user2,user1', '2016-07-01 06:09:42', 'Normal', 'group_pic.png');

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
  KEY `From_user` (`From_user`),
  KEY `ChatID` (`ChatID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `messagers`
--

INSERT INTO `messagers` (`mid`, `ChatID`, `From_user`, `Msg`, `msg_delivered`, `deleted`, `Time`) VALUES
(1, 11, 'user1', 'Chat Created', 'user1,nadunnew', '', '2016-06-28 06:48:30'),
(2, 11, 'user1', 'Adoo bro', 'user1,nadunnew', '', '2016-06-28 06:48:35'),
(3, 12, 'user1', 'Chat Created', 'user1,nadunnew,user2', '', '2016-06-28 06:49:11'),
(4, 12, 'user1', 'Onna chat eka hari', 'user1,nadunnew,user2', '', '2016-06-28 06:49:22'),
(5, 12, 'nadunnew', 'ela ela', 'nadunnew,user1,user2', '', '2016-06-28 06:53:13'),
(6, 13, 'nadunnew', 'Chat Created', 'nadunnew,user2', '', '2016-06-28 12:05:17'),
(7, 13, 'nadunnew', 'Adoo dinuka', 'nadunnew,user2', '', '2016-06-28 12:05:24'),
(8, 13, 'user2', 'fdsf', 'nadunnew,user2', '', '2016-06-28 12:05:38'),
(9, 14, 'user2', 'Chat Created', 'user2', '', '2016-07-01 06:09:42'),
(10, 14, 'user2', 'yo nigga', 'user2', '', '2016-07-01 06:09:48'),
(11, 14, 'user2', 'dsdasa', 'user2', '', '2016-07-01 06:11:51'),
(12, 14, 'user2', 'lol', 'user2', '', '2016-07-01 06:11:56'),
(13, 13, 'nadunnew', 'Lol bro', 'nadunnew,user2', '', '2016-07-01 06:14:46'),
(14, 13, 'nadunnew', '&#128525;', 'nadunnew,user2', 'nadunnew,user2', '2016-07-01 06:14:54'),
(15, 13, 'user2', 'sdsad', 'user2,nadunnew', 'user2,nadunnew', '2016-07-01 07:39:16'),
(16, 11, 'nadunnew', 'Xnnsns', 'nadunnew', '', '2016-07-01 07:41:46'),
(17, 11, 'nadunnew', 'Nznznz', 'nadunnew', '', '2016-07-01 07:41:49'),
(18, 11, 'nadunnew', 'Zbbzbzbz', 'nadunnew', '', '2016-07-01 07:41:54');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(10) NOT NULL AUTO_INCREMENT,
  `Email` varchar(45) NOT NULL,
  `Password` varchar(45) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `IsMale` int(1) NOT NULL,
  `lastonline` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Profile_pic` varchar(500) NOT NULL DEFAULT 'profilepic.png',
  PRIMARY KEY (`uid`,`Email`),
  KEY `Email` (`Email`),
  KEY `Email_2` (`Email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `Email`, `Password`, `Name`, `dob`, `IsMale`, `lastonline`, `Profile_pic`) VALUES
(2, 'nadunnew', '123', 'Thanura Nadun', '1996-11-28', 1, '2016-07-01 11:19:50', 'profilepic.png'),
(3, 'user1', '123', 'Hirusha Gimhan', '1996-01-01', 1, '2016-06-28 06:57:11', 'profilepic.png'),
(4, 'user2', '123', 'Dinuka Nirosh', '1996-06-09', 1, '2016-07-01 12:00:24', 'profilepic.png');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messagers`
--
ALTER TABLE `messagers`
  ADD CONSTRAINT `messagers_ibfk_1` FOREIGN KEY (`From_user`) REFERENCES `user` (`Email`),
  ADD CONSTRAINT `messagers_ibfk_2` FOREIGN KEY (`ChatID`) REFERENCES `chats` (`cid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
