-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version 5.5.15


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema volvooncall
--

CREATE DATABASE IF NOT EXISTS volvooncall DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
USE volvooncall;

--
-- Definition of table `timer_heat`
--

CREATE TABLE IF NOT EXISTS `timer_heat` (
  `t_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_name` varchar(255) NOT NULL,
  `t_time` varchar(255) NOT NULL,
  `t_day` varchar(255) NOT NULL,
  `t_enable` varchar(255),
  PRIMARY KEY (`t_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `timer_heat`
--

/*!40000 ALTER TABLE `timer_heat` DISABLE KEYS */;
/*!40000 ALTER TABLE `timer_heat` ENABLE KEYS */;

--
-- Definition of table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

--
-- Definition of table `voc_api_auth`
--

CREATE TABLE IF NOT EXISTS `voc_api_auth` (
  `voc_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `region` varchar(2),
  PRIMARY KEY (`voc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `voc_api_auth`
--

/*!40000 ALTER TABLE `voc_api_auth` DISABLE KEYS */;
/*!40000 ALTER TABLE `voc_api_auth` ENABLE KEYS */;


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
