-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.35-community


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
-- Temporary table structure for view `accesnotviewers`
--
DROP TABLE IF EXISTS `accesnotviewers`;
DROP VIEW IF EXISTS `accesnotviewers`;
CREATE TABLE `accesnotviewers` (
  `id` int(11),
  `request_time` int(11),
  `time` datetime,
  `ip` varchar(64),
  `url` varchar(128),
  `referer` varchar(256),
  `agent` varchar(255),
  `ref_xtUser` int(11)
);

--
-- Temporary table structure for view `access_xtusers`
--
DROP TABLE IF EXISTS `access_xtusers`;
DROP VIEW IF EXISTS `access_xtusers`;
CREATE TABLE `access_xtusers` (
  `id` int(11),
  `request_time` int(11),
  `time` datetime,
  `ip` varchar(64),
  `url` varchar(128),
  `referer` varchar(256),
  `agent` varchar(255),
  `queries` int(10) unsigned,
  `time_execution` float,
  `cache_read` int(11),
  `cache_write` int(11),
  `memory` int(11),
  `memory_limit` int(11),
  `ref_xtuser` int(11),
  `session_id` varchar(255),
  `nick` varchar(255),
  `ref_xtRole` int(11),
  `password` varchar(255),
  `created` datetime,
  `name` varchar(255),
  `surname` varchar(255),
  `email` varchar(255),
  `www` varchar(255),
  `confirmed` int(1),
  `in_mailing` int(1),
  `hash` varchar(255)
);

--
-- Temporary table structure for view `accesviewers`
--
DROP TABLE IF EXISTS `accesviewers`;
DROP VIEW IF EXISTS `accesviewers`;
CREATE TABLE `accesviewers` (
  `id` int(11),
  `request_time` int(11),
  `time` datetime,
  `ip` varchar(64),
  `url` varchar(128),
  `referer` varchar(256),
  `agent` varchar(255),
  `ref_xtUser` int(11)
);

--
-- Temporary table structure for view `accesviewersunique`
--
DROP TABLE IF EXISTS `accesviewersunique`;
DROP VIEW IF EXISTS `accesviewersunique`;
CREATE TABLE `accesviewersunique` (
  `ip` varchar(64)
);

--
-- Temporary table structure for view `performance_pages_base`
--
DROP TABLE IF EXISTS `performance_pages_base`;
DROP VIEW IF EXISTS `performance_pages_base`;
CREATE TABLE `performance_pages_base` (
  `page` varchar(384),
  `time_execution_avg` double,
  `time_execution_sum` double,
  `queries_avg` decimal(14,2),
  `queries_sum` decimal(33,0),
  `hits` bigint(21),
  `first_hit` datetime,
  `last_hit` datetime
);

--
-- Temporary table structure for view `performance_urls_base`
--
DROP TABLE IF EXISTS `performance_urls_base`;
DROP VIEW IF EXISTS `performance_urls_base`;
CREATE TABLE `performance_urls_base` (
  `url` varchar(128),
  `time_execution_avg` double,
  `time_execution_sum` double,
  `queries_avg` decimal(14,2),
  `queries_sum` decimal(33,0),
  `hits` bigint(21),
  `first_hit` datetime,
  `last_hit` datetime
);

--
-- Definition of table `acces`
--

DROP TABLE IF EXISTS `acces`;
CREATE TABLE `acces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_time` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `ip` varchar(64) NOT NULL,
  `url` varchar(128) NOT NULL,
  `referer` varchar(256) NOT NULL,
  `agent` varchar(255) NOT NULL,
  `queries` int(10) unsigned NOT NULL DEFAULT '0',
  `time_execution` float DEFAULT NULL,
  `cache_read` int(11) DEFAULT '0',
  `cache_write` int(11) DEFAULT '0',
  `memory` int(11) DEFAULT '0',
  `memory_limit` int(11) DEFAULT '0',
  `ref_xtuser` int(11) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ref_xtuser` (`ref_xtuser`),
  KEY `acces_time` (`time`),
  CONSTRAINT `ref_xtuser` FOREIGN KEY (`ref_xtuser`) REFERENCES `xtusers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `acces`
--

/*!40000 ALTER TABLE `acces` DISABLE KEYS */;
/*!40000 ALTER TABLE `acces` ENABLE KEYS */;


--
-- Definition of table `xtroles`
--

DROP TABLE IF EXISTS `xtroles`;
CREATE TABLE `xtroles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `xtroles`
--

/*!40000 ALTER TABLE `xtroles` DISABLE KEYS */;
INSERT INTO `xtroles` (`id`,`name`) VALUES 
 (5,'admin'),
 (6,'superadmin'),
 (4,'user');
/*!40000 ALTER TABLE `xtroles` ENABLE KEYS */;


--
-- Definition of table `xtusers`
--

DROP TABLE IF EXISTS `xtusers`;
CREATE TABLE `xtusers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(255) NOT NULL,
  `ref_xtRole` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `www` varchar(255) DEFAULT NULL,
  `confirmed` int(1) NOT NULL,
  `in_mailing` int(1) NOT NULL DEFAULT '0',
  `hash` varchar(255) DEFAULT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nick` (`nick`),
  KEY `ref_xtRole` (`ref_xtRole`),
  KEY `in_mailing` (`in_mailing`),
  CONSTRAINT `ref_xtRole` FOREIGN KEY (`ref_xtRole`) REFERENCES `xtroles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `xtusers`
--

/*!40000 ALTER TABLE `xtusers` DISABLE KEYS */;
INSERT INTO `xtusers` (`id`,`nick`,`ref_xtRole`,`password`,`created`,`name`,`surname`,`email`,`www`,`confirmed`,`in_mailing`,`hash`,`user_type`) VALUES 
 (1,'palmic',5,'b3b4d2dbedc99fe843fd3dedb02f086f','2008-09-21 12:23:15','','','michal.palma@gmail.com','',1,1,'7005f23d64560fcac780f4e28dee1f3b',''),
 (2,'zuzka',6,'76dad8045cbdda90b165e6c2b7c47961','2009-07-24 15:54:39','Zuzka','Svobodová','zuzana.svobodova@praguebistro.cz',NULL,1,0,'6e67a7ec60ce45d847837aaaee0ec2ab',NULL),
 (3,'ondra',6,'47e53c8527863a978365301ac02a80dc','2009-07-24 15:54:39','Ondřej','Bach','ondrej.bach@praguebistro.cz',NULL,1,0,NULL,NULL),
/*!40000 ALTER TABLE `xtusers` ENABLE KEYS */;


--
-- Definition of view `accesnotviewers`
--

DROP TABLE IF EXISTS `accesnotviewers`;
DROP VIEW IF EXISTS `accesnotviewers`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `accesnotviewers` AS select `acces`.`id` AS `id`,`acces`.`request_time` AS `request_time`,`acces`.`time` AS `time`,`acces`.`ip` AS `ip`,`acces`.`url` AS `url`,`acces`.`referer` AS `referer`,`acces`.`agent` AS `agent`,`acces`.`ref_xtuser` AS `ref_xtUser` from `acces` where ((not((lcase(`acces`.`agent`) like _utf8'%mozilla%'))) and (not((lcase(`acces`.`agent`) like _utf8'%opera%'))));

--
-- Definition of view `access_xtusers`
--

DROP TABLE IF EXISTS `access_xtusers`;
DROP VIEW IF EXISTS `access_xtusers`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `access_xtusers` AS select `acces`.`id` AS `id`,`acces`.`request_time` AS `request_time`,`acces`.`time` AS `time`,`acces`.`ip` AS `ip`,`acces`.`url` AS `url`,`acces`.`referer` AS `referer`,`acces`.`agent` AS `agent`,`acces`.`queries` AS `queries`,`acces`.`time_execution` AS `time_execution`,`acces`.`cache_read` AS `cache_read`,`acces`.`cache_write` AS `cache_write`,`acces`.`memory` AS `memory`,`acces`.`memory_limit` AS `memory_limit`,`acces`.`ref_xtuser` AS `ref_xtuser`,`acces`.`session_id` AS `session_id`,`xtusers`.`nick` AS `nick`,`xtusers`.`ref_xtRole` AS `ref_xtRole`,`xtusers`.`password` AS `password`,`xtusers`.`created` AS `created`,`xtusers`.`name` AS `name`,`xtusers`.`surname` AS `surname`,`xtusers`.`email` AS `email`,`xtusers`.`www` AS `www`,`xtusers`.`confirmed` AS `confirmed`,`xtusers`.`in_mailing` AS `in_mailing`,`xtusers`.`hash` AS `hash` from (`xtusers` left join `acces` on((`acces`.`ref_xtuser` = `xtusers`.`id`))) where (`acces`.`id` is not null);

--
-- Definition of view `accesviewers`
--

DROP TABLE IF EXISTS `accesviewers`;
DROP VIEW IF EXISTS `accesviewers`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `accesviewers` AS select `acces`.`id` AS `id`,`acces`.`request_time` AS `request_time`,`acces`.`time` AS `time`,`acces`.`ip` AS `ip`,`acces`.`url` AS `url`,`acces`.`referer` AS `referer`,`acces`.`agent` AS `agent`,`acces`.`ref_xtuser` AS `ref_xtUser` from `acces` where ((lcase(`acces`.`agent`) like _utf8'%windows%') or (lcase(`acces`.`agent`) like _utf8'%linux%') or (lcase(`acces`.`agent`) like _utf8'%mac os%'));

--
-- Definition of view `accesviewersunique`
--

DROP TABLE IF EXISTS `accesviewersunique`;
DROP VIEW IF EXISTS `accesviewersunique`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `accesviewersunique` AS select distinct `acces`.`ip` AS `ip` from `acces` where ((lcase(`acces`.`agent`) like _utf8'%windows%') or (lcase(`acces`.`agent`) like _utf8'%linux%') or (lcase(`acces`.`agent`) like _utf8'%mac os%'));

--
-- Definition of view `performance_pages_base`
--

DROP TABLE IF EXISTS `performance_pages_base`;
DROP VIEW IF EXISTS `performance_pages_base`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `performance_pages_base` AS select (case when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) > 0) and (locate(_utf8'?',`acces`.`url`) < 1)) then substr(`acces`.`url`,1,((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) + locate(_utf8'//',`acces`.`url`)) - 2)) when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) < 1) and (locate(_utf8'?',`acces`.`url`) > 0)) then substr(`acces`.`url`,1,(locate(_utf8'?',`acces`.`url`) - 1)) when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) > 0) and (locate(_utf8'?',`acces`.`url`) > 0)) then (case when (locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) < locate(_utf8'?',`acces`.`url`)) then substr(`acces`.`url`,1,((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) + locate(_utf8'//',`acces`.`url`)) - 2)) else substr(`acces`.`url`,1,(locate(_utf8'?',`acces`.`url`) - 1)) end) else `acces`.`url` end) AS `page`,avg(`acces`.`time_execution`) AS `time_execution_avg`,sum(`acces`.`time_execution`) AS `time_execution_sum`,round(avg(`acces`.`queries`),2) AS `queries_avg`,sum(`acces`.`queries`) AS `queries_sum`,count(`acces`.`id`) AS `hits`,min(`acces`.`time`) AS `first_hit`,max(`acces`.`time`) AS `last_hit` from `acces` where (`acces`.`time_execution` is not null) group by (case when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) > 0) and (locate(_utf8'?',`acces`.`url`) < 1)) then substr(`acces`.`url`,1,((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) + locate(_utf8'//',`acces`.`url`)) - 2)) when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) < 1) and (locate(_utf8'?',`acces`.`url`) > 0)) then substr(`acces`.`url`,1,(locate(_utf8'?',`acces`.`url`) - 1)) when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) > 0) and (locate(_utf8'?',`acces`.`url`) > 0)) then (case when (locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) < locate(_utf8'?',`acces`.`url`)) then substr(`acces`.`url`,1,((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) + locate(_utf8'//',`acces`.`url`)) - 2)) else substr(`acces`.`url`,1,(locate(_utf8'?',`acces`.`url`) - 1)) end) else `acces`.`url` end) order by avg(`acces`.`time_execution`) desc;

--
-- Definition of view `performance_urls_base`
--

DROP TABLE IF EXISTS `performance_urls_base`;
DROP VIEW IF EXISTS `performance_urls_base`;
CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `performance_urls_base` AS select `acces`.`url` AS `url`,avg(`acces`.`time_execution`) AS `time_execution_avg`,sum(`acces`.`time_execution`) AS `time_execution_sum`,round(avg(`acces`.`queries`),2) AS `queries_avg`,sum(`acces`.`queries`) AS `queries_sum`,count(0) AS `hits`,min(`acces`.`time`) AS `first_hit`,max(`acces`.`time`) AS `last_hit` from `acces` where (`acces`.`time_execution` is not null) group by `acces`.`url` order by avg(`acces`.`time_execution`) desc;



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
