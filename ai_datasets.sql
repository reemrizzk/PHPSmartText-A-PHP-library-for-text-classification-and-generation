-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 22, 2023 at 06:25 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ai_datasets`
--

-- --------------------------------------------------------

--
-- Table structure for table `dynamic_categories`
--

DROP TABLE IF EXISTS `dynamic_categories`;
CREATE TABLE IF NOT EXISTS `dynamic_categories` (
  `category_id` int(255) NOT NULL AUTO_INCREMENT,
  `category_model` varchar(255) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_words` mediumtext NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `models_datasets`
--

DROP TABLE IF EXISTS `models_datasets`;
CREATE TABLE IF NOT EXISTS `models_datasets` (
  `dataset_id` int(25) NOT NULL AUTO_INCREMENT,
  `model_name` varchar(255) NOT NULL,
  `dataset_text` longtext NOT NULL,
  `dataset_keywords` mediumtext DEFAULT NULL,
  `dataset_classification` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`dataset_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `models_list`
--

DROP TABLE IF EXISTS `models_list`;
CREATE TABLE IF NOT EXISTS `models_list` (
  `model_name` varchar(255) NOT NULL,
  PRIMARY KEY (`model_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
