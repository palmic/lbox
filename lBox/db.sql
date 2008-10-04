-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.67


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema lbox
--

CREATE DATABASE IF NOT EXISTS lbox;
USE lbox;

--
-- Definition of table `lbox`.`acces`
--

DROP TABLE IF EXISTS `lbox`.`acces`;
CREATE TABLE  `lbox`.`acces` (
  `id` int(11) NOT NULL auto_increment,
  `request_time` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `ip` varchar(64) NOT NULL,
  `url` varchar(128) NOT NULL,
  `referer` varchar(256) NOT NULL,
  `agent` varchar(128) NOT NULL,
  `queries` int(10) unsigned NOT NULL default '0',
  `time_execution` float default NULL,
  `ref_xtUser` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `ref_xtUser` (`ref_xtUser`),
  KEY `a_url` (`url`),
  KEY `a_time_execution` (`time_execution`),
  KEY `a_queries` (`queries`),
  KEY `a_time` (`time`),
  CONSTRAINT `a_ref_xtuser` FOREIGN KEY (`ref_xtUser`) REFERENCES `xtUsers` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `lbox`.`acces`
--

/*!40000 ALTER TABLE `acces` DISABLE KEYS */;
LOCK TABLES `acces` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `acces` ENABLE KEYS */;


--
-- Definition of table `lbox`.`xtRoles`
--

DROP TABLE IF EXISTS `lbox`.`xtRoles`;
CREATE TABLE  `lbox`.`xtRoles` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lbox`.`xtRoles`
--

/*!40000 ALTER TABLE `xtRoles` DISABLE KEYS */;
LOCK TABLES `xtRoles` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `xtRoles` ENABLE KEYS */;


--
-- Definition of table `lbox`.`xtUsers`
--

DROP TABLE IF EXISTS `lbox`.`xtUsers`;
CREATE TABLE  `lbox`.`xtUsers` (
  `id` int(11) NOT NULL auto_increment,
  `nick` varchar(128) NOT NULL,
  `ref_xtRole` int(11) NOT NULL,
  `password` varchar(128) NOT NULL,
  `created` datetime NOT NULL,
  `name` varchar(128) default NULL,
  `surname` varchar(128) default NULL,
  `email` varchar(128) default NULL,
  `www` varchar(128) default NULL,
  `confirmed` int(1) default '0',
  `hash` varchar(255) NOT NULL,
  `phone` char(13) default NULL,
  `city` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `nick` (`nick`),
  KEY `ref_xtRole` (`ref_xtRole`),
  CONSTRAINT `xtu_ref_xtrole` FOREIGN KEY (`ref_xtRole`) REFERENCES `xtRoles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `lbox`.`xtUsers`
--

/*!40000 ALTER TABLE `xtUsers` DISABLE KEYS */;
LOCK TABLES `xtUsers` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `xtUsers` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
