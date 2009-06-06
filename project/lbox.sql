-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.34-log


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
-- Temporary table structure for view `lbox`.`accesnotviewers`
--
DROP TABLE IF EXISTS `lbox`.`accesnotviewers`;
DROP VIEW IF EXISTS `lbox`.`accesnotviewers`;
CREATE TABLE `lbox`.`accesnotviewers` (
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
-- Temporary table structure for view `lbox`.`accesviewers`
--
DROP TABLE IF EXISTS `lbox`.`accesviewers`;
DROP VIEW IF EXISTS `lbox`.`accesviewers`;
CREATE TABLE `lbox`.`accesviewers` (
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
-- Temporary table structure for view `lbox`.`accesviewersunique`
--
DROP TABLE IF EXISTS `lbox`.`accesviewersunique`;
DROP VIEW IF EXISTS `lbox`.`accesviewersunique`;

--
-- Temporary table structure for view `lbox`.`performance_pages_base`
--
DROP TABLE IF EXISTS `lbox`.`performance_pages_base`;
DROP VIEW IF EXISTS `lbox`.`performance_pages_base`;
CREATE TABLE `lbox`.`performance_pages_base` (
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
-- Temporary table structure for view `lbox`.`performance_urls_base`
--
DROP TABLE IF EXISTS `lbox`.`performance_urls_base`;
DROP VIEW IF EXISTS `lbox`.`performance_urls_base`;
CREATE TABLE `lbox`.`performance_urls_base` (
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
-- Definition of table `lbox`.`acces`
--

DROP TABLE IF EXISTS `lbox`.`acces`;
CREATE TABLE  `lbox`.`acces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_time` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `ip` varchar(64) NOT NULL,
  `url` varchar(128) NOT NULL,
  `referer` varchar(256) NOT NULL,
  `agent` varchar(255) NOT NULL,
  `queries` int(10) unsigned NOT NULL DEFAULT '0',
  `time_execution` float DEFAULT NULL,
  `cache_read` int(11) NOT NULL,
  `cache_write` int(11) NOT NULL,
  `memory` int(11) NOT NULL DEFAULT '0',
  `memory_limit` int(11) NOT NULL DEFAULT '0',
  `ref_xtUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ref_xtUser` (`ref_xtUser`),
  CONSTRAINT `ref_xtUser` FOREIGN KEY (`ref_xtUser`) REFERENCES `xtUsers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47454 DEFAULT CHARSET=utf8;

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lbox`.`xtRoles`
--

/*!40000 ALTER TABLE `xtRoles` DISABLE KEYS */;
LOCK TABLES `xtRoles` WRITE;
INSERT INTO `lbox`.`xtRoles` VALUES  (5,'admin'),
 (6,'superadmin'),
 (4,'user');
UNLOCK TABLES;
/*!40000 ALTER TABLE `xtRoles` ENABLE KEYS */;


--
-- Definition of table `lbox`.`xtUsers`
--

DROP TABLE IF EXISTS `lbox`.`xtUsers`;
CREATE TABLE  `lbox`.`xtUsers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(128) NOT NULL,
  `ref_xtRole` int(11) NOT NULL,
  `password` varchar(128) NOT NULL,
  `created` datetime NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `surname` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `www` varchar(128) DEFAULT NULL,
  `confirmed` int(1) NOT NULL,
  `in_mailing` int(1) NOT NULL DEFAULT '0',
  `hash` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nick` (`nick`),
  KEY `ref_xtRole` (`ref_xtRole`),
  KEY `in_mailing` (`in_mailing`),
  CONSTRAINT `ref_xtRole` FOREIGN KEY (`ref_xtRole`) REFERENCES `xtRoles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lbox`.`xtUsers`
--

/*!40000 ALTER TABLE `xtUsers` DISABLE KEYS */;
LOCK TABLES `xtUsers` WRITE;
INSERT INTO `lbox`.`xtUsers` VALUES  (1,'palmic',6,'b3b4d2dbedc99fe843fd3dedb02f086f','2008-09-21 12:23:15','','','michal.palma@gmail.com','',1,1,'7005f23d64560fcac780f4e28dee1f3b'),
 (2,'brady',6,'8e060ab9ce958ed166f209fcb3622744','2008-09-21 15:22:31','Stepan','Brada','brady@techhouse.cz','http://www.djbrady.cz',1,1,'7005f23d645d08cac780f4228dee1f3b'),
 (3,'blond',5,'aafc46ba9d415655c6f9fbdec29aea4c','2008-09-23 17:45:37','','','blond@techhouse.cz','',1,0,'7005f23ds55d08cac78014248dee1f3b'),
 (4,'next',5,'db4dad8a4896602b676a2fd075517574','2008-10-09 21:27:16','Libor','Šoch','next@techhouse.cz','http://www.techhouse.cz',1,0,'7005f23dd58d08gac78054248des41f3'),
 (5,'Dancer',4,'186167bf392750a108b00eab5f4f9d39','2009-03-27 23:08:32','Lucas','Kosora voe','lukatyr@seznam.cz','',1,0,'6c0c2778a81980ea4a8743af23c8892e'),
 (6,'ura1967',4,'5b23e70cc27103e8ef6d745aaed944f1','2009-03-31 20:00:58','','','kopkin@list.ru','',0,1,'90703cf5a4cb8ba8daca36071c7da378'),
 (7,'side',4,'65a4e611081093e10cf28758f23a7855','2009-04-23 15:34:10','andy','andy','info@autodoprava-ap.sk','',1,0,'a28b9bf6a0217e8f3353e0dab67d6f9f'),
 (8,'K_O_S',4,'ac47421cd4352c0f2cc42d557d8059bc','2009-05-27 11:26:41','','','fuck_your_ass@inbox.ru','',1,0,'3bf10841cda7bc4066e11ee74273d5f5'),
 (9,'seegeng',4,'cb8c31a57b18878df5e8f0b75396e175','2009-05-30 17:50:24','','','seegeng@seznam.cz','',1,0,'68c36b0ab15c32ba85152d7e674c58b1');
UNLOCK TABLES;
/*!40000 ALTER TABLE `xtUsers` ENABLE KEYS */;


--
-- Definition of view `lbox`.`accesnotviewers`
--

DROP TABLE IF EXISTS `lbox`.`accesnotviewers`;
DROP VIEW IF EXISTS `lbox`.`accesnotviewers`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW  `lbox`.`accesnotviewers` AS select `acces`.`id` AS `id`,`acces`.`request_time` AS `request_time`,`acces`.`time` AS `time`,`acces`.`ip` AS `ip`,`acces`.`url` AS `url`,`acces`.`referer` AS `referer`,`acces`.`agent` AS `agent`,`acces`.`ref_xtUser` AS `ref_xtUser` from `acces` where ((not((lcase(`acces`.`agent`) like _utf8'%mozilla%'))) and (not((lcase(`acces`.`agent`) like _utf8'%opera%'))));

--
-- Definition of view `lbox`.`accesviewers`
--

DROP TABLE IF EXISTS `lbox`.`accesviewers`;
DROP VIEW IF EXISTS `lbox`.`accesviewers`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW  `lbox`.`accesviewers` AS select `acces`.`id` AS `id`,`acces`.`request_time` AS `request_time`,`acces`.`time` AS `time`,`acces`.`ip` AS `ip`,`acces`.`url` AS `url`,`acces`.`referer` AS `referer`,`acces`.`agent` AS `agent`,`acces`.`ref_xtUser` AS `ref_xtUser` from `acces` where ((lcase(`acces`.`agent`) like _utf8'%windows%') or (lcase(`acces`.`agent`) like _utf8'%linux%') or (lcase(`acces`.`agent`) like _utf8'%mac os%'));

--
-- Definition of view `lbox`.`accesviewersunique`
--

DROP TABLE IF EXISTS `lbox`.`accesviewersunique`;
DROP VIEW IF EXISTS `lbox`.`accesviewersunique`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW  `lbox`.`accesviewersunique` AS select distinct `acces`.`ip` AS `ip` from `acces` where ((lcase(`acces`.`agent`) like _utf8'%windows%') or (lcase(`acces`.`agent`) like _utf8'%linux%') or (lcase(`acces`.`agent`) like _utf8'%mac os%'));

--
-- Definition of view `lbox`.`performance_pages_base`
--

DROP TABLE IF EXISTS `lbox`.`performance_pages_base`;
DROP VIEW IF EXISTS `lbox`.`performance_pages_base`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW  `lbox`.`performance_pages_base` AS select (case when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) > 0) and (locate(_utf8'?',`acces`.`url`) < 1)) then substr(`acces`.`url`,1,((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) + locate(_utf8'//',`acces`.`url`)) - 2)) when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) < 1) and (locate(_utf8'?',`acces`.`url`) > 0)) then substr(`acces`.`url`,1,(locate(_utf8'?',`acces`.`url`) - 1)) when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) > 0) and (locate(_utf8'?',`acces`.`url`) > 0)) then (case when (locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) < locate(_utf8'?',`acces`.`url`)) then substr(`acces`.`url`,1,((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) + locate(_utf8'//',`acces`.`url`)) - 2)) else substr(`acces`.`url`,1,(locate(_utf8'?',`acces`.`url`) - 1)) end) else `acces`.`url` end) AS `page`,avg(`acces`.`time_execution`) AS `time_execution_avg`,sum(`acces`.`time_execution`) AS `time_execution_sum`,round(avg(`acces`.`queries`),2) AS `queries_avg`,sum(`acces`.`queries`) AS `queries_sum`,count(`acces`.`id`) AS `hits`,min(`acces`.`time`) AS `first_hit`,max(`acces`.`time`) AS `last_hit` from `acces` where (`acces`.`time_execution` is not null) group by (case when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) > 0) and (locate(_utf8'?',`acces`.`url`) < 1)) then substr(`acces`.`url`,1,((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) + locate(_utf8'//',`acces`.`url`)) - 2)) when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) < 1) and (locate(_utf8'?',`acces`.`url`) > 0)) then substr(`acces`.`url`,1,(locate(_utf8'?',`acces`.`url`) - 1)) when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) > 0) and (locate(_utf8'?',`acces`.`url`) > 0)) then (case when (locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) < locate(_utf8'?',`acces`.`url`)) then substr(`acces`.`url`,1,((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) + locate(_utf8'//',`acces`.`url`)) - 2)) else substr(`acces`.`url`,1,(locate(_utf8'?',`acces`.`url`) - 1)) end) else `acces`.`url` end) order by avg(`acces`.`time_execution`) desc;

--
-- Definition of view `lbox`.`performance_urls_base`
--

DROP TABLE IF EXISTS `lbox`.`performance_urls_base`;
DROP VIEW IF EXISTS `lbox`.`performance_urls_base`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW  `lbox`.`performance_urls_base` AS select `acces`.`url` AS `url`,avg(`acces`.`time_execution`) AS `time_execution_avg`,sum(`acces`.`time_execution`) AS `time_execution_sum`,round(avg(`acces`.`queries`),2) AS `queries_avg`,sum(`acces`.`queries`) AS `queries_sum`,count(0) AS `hits`,min(`acces`.`time`) AS `first_hit`,max(`acces`.`time`) AS `last_hit` from `acces` where (`acces`.`time_execution` is not null) group by `acces`.`url` order by avg(`acces`.`time_execution`) desc;



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
