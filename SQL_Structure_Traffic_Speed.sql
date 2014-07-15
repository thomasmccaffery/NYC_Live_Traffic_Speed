-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 15, 2014 at 05:00 PM
-- Server version: 5.5.37-cll
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tqrgpjyc_NYC_Traffic_Speed`
--

-- --------------------------------------------------------

--
-- Table structure for table `Traffic_Speed`
--

CREATE TABLE IF NOT EXISTS `Traffic_Speed` (
  `Id` int(11) NOT NULL,
  `Speed` float NOT NULL,
  `TravelTime` float NOT NULL,
  `Status` varchar(3) NOT NULL,
  `DataAsOf` datetime NOT NULL,
  `linkId` int(11) NOT NULL,
  `linkPoints` text NOT NULL,
  `EncodedPolyLine` text NOT NULL,
  `EncodedPolyLineLvls` text NOT NULL,
  `Owner` text NOT NULL,
  `Transcom_id` int(11) NOT NULL,
  `Borough` varchar(25) NOT NULL,
  `linkName` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
