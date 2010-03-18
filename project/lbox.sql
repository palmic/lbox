-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.42-log


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

--
-- Temporary table structure for view `inquiries_options_responses`
--
DROP TABLE IF EXISTS `inquiries_options_responses`;
DROP VIEW IF EXISTS `inquiries_options_responses`;
CREATE TABLE `inquiries_options_responses` (
  `ref_inquiry` int(11),
  `ref_option` int(11),
  `ref_response` int(11),
  `question` text,
  `created` datetime,
  `answer` text,
  `time` datetime,
  `ref_xtUser` int(11),
  `ip` varchar(64)
);

--
-- Temporary table structure for view `inquiries_summaries`
--
DROP TABLE IF EXISTS `inquiries_summaries`;
DROP VIEW IF EXISTS `inquiries_summaries`;
CREATE TABLE `inquiries_summaries` (
  `ref_inquiry` int(11),
  `ref_option` int(11),
  `question` text,
  `created` datetime,
  `answer` text,
  `count_responses_option` bigint(21),
  `count_responses_inquiry` bigint(21),
  `responses_option_percent` decimal(24,0)
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
CREATE TABLE  `acces` (
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
) ENGINE=InnoDB AUTO_INCREMENT=533 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `acces`
--

/*!40000 ALTER TABLE `acces` DISABLE KEYS */;
LOCK TABLES `acces` WRITE;
INSERT INTO `acces` VALUES  (1,1267963960,'2010-03-07 13:12:40','127.0.0.1','http://lbox.localhost/','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',5,0.405038,1,1,11272192,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (2,1267964199,'2010-03-07 13:16:39','127.0.0.1','http://lbox.localhost/sitemap.xml','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',5,0.079381,1,1,10223616,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (3,1267965527,'2010-03-07 13:38:47','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',5,0.0605981,1,1,7077888,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (4,1267965792,'2010-03-07 13:43:12','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',7,0.132471,1,1,10747904,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (5,1267966471,'2010-03-07 13:54:31','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',11,0.114696,1,1,8912896,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (6,1267967547,'2010-03-07 14:12:27','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',9,0.092298,1,0,8912896,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (7,1267967549,'2010-03-07 14:12:29','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',9,0.075897,1,0,8912896,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (8,1267994885,'2010-03-07 21:48:05','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',9,0.086355,1,0,11534336,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (9,1267994892,'2010-03-07 21:48:12','127.0.0.1','http://lbox.localhost/admin','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',5,0.121176,1,1,10747904,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (10,1267994943,'2010-03-07 21:49:03','127.0.0.1','http://lbox.localhost/admin','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',5,0.046278,1,0,6815744,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (11,1267994960,'2010-03-07 21:49:20','127.0.0.1','http://lbox.localhost/admin','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',5,0.056078,1,0,6291456,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (12,1267994960,'2010-03-07 21:49:20','127.0.0.1','http://lbox.localhost/admin/login/','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',5,0.0328019,1,1,7602176,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (13,1267994961,'2010-03-07 21:49:21','127.0.0.1','http://lbox.localhost/admin','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',5,0.0529399,1,0,6291456,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (14,1267994991,'2010-03-07 21:49:51','127.0.0.1','http://lbox.localhost/admin','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',5,0.0717771,1,0,6291456,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (15,1267994991,'2010-03-07 21:49:51','127.0.0.1','http://lbox.localhost/admin/login/','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',5,0.144687,1,1,12058624,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (16,1267994993,'2010-03-07 21:49:53','127.0.0.1','http://lbox.localhost/admin','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',5,0.056349,1,0,6291456,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (17,1267995006,'2010-03-07 21:50:06','127.0.0.1','http://lbox.localhost/admin','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',5,0.045079,1,0,6291456,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (18,1267995006,'2010-03-07 21:50:07','127.0.0.1','http://lbox.localhost/admin/login/','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',5,0.10711,1,1,12058624,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (19,1267995008,'2010-03-07 21:50:08','127.0.0.1','http://lbox.localhost/admin','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',5,0.0834341,1,0,6291456,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (20,1267995022,'2010-03-07 21:50:22','127.0.0.1','http://lbox.localhost/admin','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',5,0.044893,1,0,6291456,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (21,1267995022,'2010-03-07 21:50:22','127.0.0.1','http://lbox.localhost/admin/login/','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',5,0.0669801,1,0,12058624,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (22,1267995037,'2010-03-07 21:50:37','127.0.0.1','http://lbox.localhost/admin/login/','http://lbox.localhost/admin/login/','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',9,0.085027,1,1,10223616,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (23,1267995042,'2010-03-07 21:50:42','127.0.0.1','http://lbox.localhost/admin/login/','http://lbox.localhost/admin/login/','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',19,0.0961969,1,1,8912896,104857600,NULL,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (24,1267995042,'2010-03-07 21:50:42','127.0.0.1','http://lbox.localhost/admin/login/','http://lbox.localhost/admin/login/','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',8,0.025753,1,0,7077888,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (25,1267995042,'2010-03-07 21:50:42','127.0.0.1','http://lbox.localhost/admin','http://lbox.localhost/admin/login/','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',12,0.035289,1,0,8126464,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (26,1267995067,'2010-03-07 21:51:07','127.0.0.1','http://lbox.localhost/admin','http://lbox.localhost/admin/login/','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',12,0.0903659,1,0,11272192,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (27,1267995081,'2010-03-07 21:51:21','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',13,0.074317,1,1,8650752,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (28,1267995176,'2010-03-07 21:52:56','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',13,0.0672941,1,0,8650752,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (29,1267995177,'2010-03-07 21:52:57','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',13,0.0597079,1,0,8650752,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (30,1267995209,'2010-03-07 21:53:29','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',13,0.085969,1,0,8126464,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (31,1267995214,'2010-03-07 21:53:34','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',13,0.066484,1,0,8126464,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (32,1267995324,'2010-03-07 21:55:24','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',14,0.14641,1,1,8388608,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (33,1267995420,'2010-03-07 21:57:00','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',14,0.11844,1,1,8388608,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (34,1267995470,'2010-03-07 21:57:50','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',14,0.104031,1,1,8388608,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (35,1267995488,'2010-03-07 21:58:08','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',14,0.138342,1,1,8388608,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (36,1267995503,'2010-03-07 21:58:23','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',14,0.101774,1,1,8388608,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (37,1267995518,'2010-03-07 21:58:38','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',14,0.115143,1,1,8388608,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (38,1267995534,'2010-03-07 21:58:54','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',14,0.101455,1,1,8388608,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (39,1267995549,'2010-03-07 21:59:09','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',14,0.0991552,1,1,8388608,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (40,1267995563,'2010-03-07 21:59:23','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',14,0.082917,1,1,9437184,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (41,1267995603,'2010-03-07 22:00:03','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',14,0.117613,1,0,8650752,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (42,1267995700,'2010-03-07 22:01:40','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.0937271,1,1,9961472,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (43,1267995789,'2010-03-07 22:03:09','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.135938,1,0,12582912,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (44,1267995847,'2010-03-07 22:04:07','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.12397,1,0,12582912,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (45,1267995884,'2010-03-07 22:04:44','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.111983,1,0,12582912,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (46,1267995904,'2010-03-07 22:05:04','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.0823181,1,0,10223616,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (47,1267996119,'2010-03-07 22:08:39','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.0829971,1,0,10223616,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (48,1267996227,'2010-03-07 22:10:27','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.132511,1,0,13107200,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (49,1267996236,'2010-03-07 22:10:36','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.112041,1,0,10485760,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (50,1267996246,'2010-03-07 22:10:46','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.12776,1,0,13107200,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (51,1267996256,'2010-03-07 22:10:56','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.072258,1,0,10485760,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (52,1267996284,'2010-03-07 22:11:24','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.0924952,1,0,10485760,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (53,1267996415,'2010-03-07 22:13:35','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.173416,1,0,13369344,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (54,1267996423,'2010-03-07 22:13:43','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.114213,1,0,10485760,104857600,1,'b47fccbdeeffd8d6e92af7292fa984a1'),
 (55,1268485425,'2010-03-13 14:03:46','127.0.0.1','http://lbox.localhost/','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',5,1.33412,0,1,11272192,104857600,NULL,'6981d1289cf341e32911eb013465c4e4'),
 (56,1268485468,'2010-03-13 14:04:28','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',9,0.297264,1,1,11534336,104857600,NULL,'6981d1289cf341e32911eb013465c4e4'),
 (57,1268495144,'2010-03-13 16:45:44','127.0.0.1','http://lbox.localhost/admin','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',5,0.0689089,1,0,6291456,104857600,NULL,'6981d1289cf341e32911eb013465c4e4'),
 (58,1268495144,'2010-03-13 16:45:44','127.0.0.1','http://lbox.localhost/admin/login/','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',5,0.1392,1,1,12582912,104857600,NULL,'6981d1289cf341e32911eb013465c4e4'),
 (59,1268495145,'2010-03-13 16:45:45','127.0.0.1','http://lbox.localhost/admin/login/','http://lbox.localhost/admin/login/','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',19,0.105393,1,1,8912896,104857600,NULL,'6981d1289cf341e32911eb013465c4e4'),
 (60,1268495146,'2010-03-13 16:45:46','127.0.0.1','http://lbox.localhost/admin/login/','http://lbox.localhost/admin/login/','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',8,0.0330832,1,0,7077888,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (61,1268495146,'2010-03-13 16:45:46','127.0.0.1','http://lbox.localhost/admin','http://lbox.localhost/admin/login/','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',12,0.079021,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (62,1268495148,'2010-03-13 16:45:48','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.243077,1,1,13631488,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (63,1268495195,'2010-03-13 16:46:36','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.209252,1,1,14155776,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (64,1268495739,'2010-03-13 16:55:39','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.31463,1,0,13893632,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (65,1268495761,'2010-03-13 16:56:01','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.61288,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (66,1268495773,'2010-03-13 16:56:13','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.10182,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (67,1268495777,'2010-03-13 16:56:17','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.12364,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (68,1268495783,'2010-03-13 16:56:23','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.104803,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (69,1268495813,'2010-03-13 16:56:53','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.129153,1,0,13631488,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (70,1268495857,'2010-03-13 16:57:37','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.111102,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (71,1268495955,'2010-03-13 16:59:15','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.15747,1,0,13893632,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (72,1268496033,'2010-03-13 17:00:33','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.100246,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (73,1268496050,'2010-03-13 17:00:50','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.155207,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (74,1268496062,'2010-03-13 17:01:03','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.11144,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (75,1268496279,'2010-03-13 17:04:39','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.144085,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (76,1268496286,'2010-03-13 17:04:46','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.134753,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (77,1268496296,'2010-03-13 17:04:56','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.11541,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (78,1268496306,'2010-03-13 17:05:06','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.0836718,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (79,1268496532,'2010-03-13 17:08:52','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.107209,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (80,1268496549,'2010-03-13 17:09:09','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.097162,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (81,1268496556,'2010-03-13 17:09:16','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.13209,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (82,1268496888,'2010-03-13 17:14:48','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.10305,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (83,1268496894,'2010-03-13 17:14:54','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.104643,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (84,1268496906,'2010-03-13 17:15:06','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.107449,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (85,1268496939,'2010-03-13 17:15:40','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.111673,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (86,1268496973,'2010-03-13 17:16:13','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.125297,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (87,1268497020,'2010-03-13 17:17:00','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.105725,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (88,1268497237,'2010-03-13 17:20:37','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.192441,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (89,1268497362,'2010-03-13 17:22:42','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.184438,1,0,13631488,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (90,1268497395,'2010-03-13 17:23:15','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.111011,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (91,1268497436,'2010-03-13 17:23:57','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.117014,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (92,1268497972,'2010-03-13 17:32:52','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.150575,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (93,1268498369,'2010-03-13 17:39:29','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.109947,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (94,1268498379,'2010-03-13 17:39:39','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.154381,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (95,1268498413,'2010-03-13 17:40:13','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.103901,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (96,1268498516,'2010-03-13 17:41:56','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.491182,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (97,1268498542,'2010-03-13 17:42:22','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.0977421,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (98,1268498669,'2010-03-13 17:44:29','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.202229,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (99,1268498781,'2010-03-13 17:46:21','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.108858,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (100,1268498806,'2010-03-13 17:46:46','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.129495,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (101,1268498845,'2010-03-13 17:47:26','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.134846,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (102,1268498912,'2010-03-13 17:48:32','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.135994,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (103,1268499059,'2010-03-13 17:51:00','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.110381,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (104,1268499100,'2010-03-13 17:51:40','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.106853,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (105,1268499128,'2010-03-13 17:52:08','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.107503,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (106,1268499228,'2010-03-13 17:53:48','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.10576,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (107,1268499240,'2010-03-13 17:54:00','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.098479,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (108,1268499300,'2010-03-13 17:55:01','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.118688,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (109,1268499333,'2010-03-13 17:55:33','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.0989058,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (110,1268499449,'2010-03-13 17:57:29','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.113209,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (111,1268499471,'2010-03-13 17:57:51','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.212405,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (112,1268499539,'2010-03-13 17:58:59','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.10766,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (113,1268499592,'2010-03-13 17:59:52','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.103185,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (114,1268499628,'2010-03-13 18:00:28','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.148832,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (115,1268499645,'2010-03-13 18:00:45','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.110755,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (116,1268499793,'2010-03-13 18:03:13','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.12745,1,0,13631488,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (117,1268499807,'2010-03-13 18:03:27','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.166089,1,0,13631488,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (118,1268500407,'2010-03-13 18:13:27','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.115498,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (119,1268500710,'2010-03-13 18:18:30','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.121102,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (120,1268500809,'2010-03-13 18:20:09','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.150126,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (121,1268500890,'2010-03-13 18:21:30','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.100177,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (122,1268500966,'2010-03-13 18:22:46','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.106973,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (123,1268500982,'2010-03-13 18:23:02','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.108974,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (124,1268501010,'2010-03-13 18:23:30','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.135357,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (125,1268501061,'2010-03-13 18:24:21','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.0925829,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (126,1268501082,'2010-03-13 18:24:42','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.102144,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (127,1268501104,'2010-03-13 18:25:04','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.135188,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (128,1268501130,'2010-03-13 18:25:30','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.109347,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (129,1268501301,'2010-03-13 18:28:21','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.149635,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (130,1268501325,'2010-03-13 18:28:45','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.123325,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (131,1268501346,'2010-03-13 18:29:06','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.103624,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (132,1268501559,'2010-03-13 18:32:39','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.094337,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (133,1268501581,'2010-03-13 18:33:01','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.201982,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (134,1268501641,'2010-03-13 18:34:01','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.104168,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (135,1268501654,'2010-03-13 18:34:14','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.176553,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (136,1268501680,'2010-03-13 18:34:40','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.109206,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (137,1268501705,'2010-03-13 18:35:05','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.111232,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (138,1268501736,'2010-03-13 18:35:36','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.135096,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (139,1268502220,'2010-03-13 18:43:40','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.093852,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (140,1268502237,'2010-03-13 18:43:57','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.103648,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (141,1268502267,'2010-03-13 18:44:27','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.117575,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (142,1268502279,'2010-03-13 18:44:40','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.11393,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (143,1268502293,'2010-03-13 18:44:53','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.115413,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (144,1268502357,'2010-03-13 18:45:57','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.0971401,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (145,1268502506,'2010-03-13 18:48:26','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.102436,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (146,1268502561,'2010-03-13 18:49:21','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.120586,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (147,1268502722,'2010-03-13 18:52:02','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.138376,1,0,13631488,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (148,1268502746,'2010-03-13 18:52:26','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.108517,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (149,1268502782,'2010-03-13 18:53:02','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.111724,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (150,1268502794,'2010-03-13 18:53:14','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.117991,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (151,1268502807,'2010-03-13 18:53:28','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.124423,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (152,1268502820,'2010-03-13 18:53:40','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.109344,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (153,1268502894,'2010-03-13 18:54:54','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.10831,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (154,1268503177,'2010-03-13 18:59:37','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.263338,1,0,13893632,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (155,1268503209,'2010-03-13 19:00:09','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.131611,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (156,1268503222,'2010-03-13 19:00:23','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.174007,1,0,13893632,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (157,1268503266,'2010-03-13 19:01:06','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.233867,1,0,13893632,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (158,1268503293,'2010-03-13 19:01:33','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.100499,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (159,1268503306,'2010-03-13 19:01:46','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.113472,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (160,1268503378,'2010-03-13 19:02:58','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.220137,1,0,13893632,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (161,1268503389,'2010-03-13 19:03:09','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.081054,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (162,1268503422,'2010-03-13 19:03:42','127.0.0.1','http://lbox.localhost/admin','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',12,0.066227,1,0,8912896,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (163,1268503426,'2010-03-13 19:03:46','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.124465,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (164,1268503430,'2010-03-13 19:03:50','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.0977702,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (165,1268503567,'2010-03-13 19:06:07','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.203638,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (166,1268503607,'2010-03-13 19:06:48','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.108542,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (167,1268503628,'2010-03-13 19:07:08','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.136977,1,0,13893632,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (168,1268503708,'2010-03-13 19:08:28','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.112388,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (169,1268503721,'2010-03-13 19:08:41','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.103291,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (170,1268503729,'2010-03-13 19:08:49','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.103033,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (171,1268503760,'2010-03-13 19:09:20','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.11614,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (172,1268503831,'2010-03-13 19:10:31','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.117971,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (173,1268503868,'2010-03-13 19:11:08','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.156891,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (174,1268503899,'2010-03-13 19:11:39','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.125651,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (175,1268503994,'2010-03-13 19:13:14','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.1056,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (176,1268504083,'2010-03-13 19:14:43','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.128267,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (177,1268504117,'2010-03-13 19:15:17','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.10828,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (178,1268504140,'2010-03-13 19:15:40','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.103437,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (179,1268504184,'2010-03-13 19:16:24','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.107145,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (180,1268504209,'2010-03-13 19:16:49','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.107945,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (181,1268504300,'2010-03-13 19:18:20','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.189938,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (182,1268504354,'2010-03-13 19:19:14','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.103805,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (183,1268504396,'2010-03-13 19:19:56','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.107665,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (184,1268504417,'2010-03-13 19:20:17','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.114446,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (185,1268504584,'2010-03-13 19:23:04','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.137638,1,0,13631488,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (186,1268504644,'2010-03-13 19:24:04','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.136775,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (187,1268504776,'2010-03-13 19:26:16','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.142152,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (188,1268504966,'2010-03-13 19:29:26','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.151801,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (189,1268504979,'2010-03-13 19:29:39','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.133685,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (190,1268504991,'2010-03-13 19:29:51','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.0971081,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (191,1268505051,'2010-03-13 19:30:51','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.125102,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (192,1268505155,'2010-03-13 19:32:35','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.127068,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (193,1268505310,'2010-03-13 19:35:10','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.100306,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (194,1268505340,'2010-03-13 19:35:40','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.0989819,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (195,1268505448,'2010-03-13 19:37:28','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.10602,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (196,1268505520,'2010-03-13 19:38:40','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.10724,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (197,1268505588,'2010-03-13 19:39:48','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.127046,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (198,1268505606,'2010-03-13 19:40:06','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.091862,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (199,1268505637,'2010-03-13 19:40:37','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.100227,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (200,1268505688,'2010-03-13 19:41:28','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.13179,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (201,1268505695,'2010-03-13 19:41:35','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.125259,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (202,1268505782,'2010-03-13 19:43:02','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.113569,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (203,1268505798,'2010-03-13 19:43:18','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.126171,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (204,1268505873,'2010-03-13 19:44:33','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.098314,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (205,1268506541,'2010-03-13 19:55:41','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.373071,0,1,15204352,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (206,1268506553,'2010-03-13 19:55:53','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.155888,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (207,1268507647,'2010-03-13 20:14:07','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.179496,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (208,1268516347,'2010-03-13 22:39:08','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.381781,0,1,15204352,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (209,1268516406,'2010-03-13 22:40:06','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.13527,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (210,1268516445,'2010-03-13 22:40:45','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.121471,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (211,1268517066,'2010-03-13 22:51:06','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.113978,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (212,1268517228,'2010-03-13 22:53:48','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.13467,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (213,1268517244,'2010-03-13 22:54:04','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.152989,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (214,1268518685,'2010-03-13 23:18:05','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.144945,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (215,1268518943,'2010-03-13 23:22:23','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.155261,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (216,1268519111,'2010-03-13 23:25:11','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.122884,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (217,1268519228,'2010-03-13 23:27:08','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.10613,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (218,1268520157,'2010-03-13 23:42:37','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.108469,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (219,1268521065,'2010-03-13 23:57:45','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.143642,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (220,1268521164,'2010-03-13 23:59:24','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.107234,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (221,1268521929,'2010-03-14 00:12:09','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.118259,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (222,1268522042,'2010-03-14 00:14:03','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.126501,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (223,1268522063,'2010-03-14 00:14:23','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.105839,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (224,1268522240,'2010-03-14 00:17:20','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.142928,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (225,1268522434,'2010-03-14 00:20:34','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.13818,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (226,1268522524,'2010-03-14 00:22:04','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.116818,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (227,1268522525,'2010-03-14 00:22:06','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.116417,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (228,1268522544,'2010-03-14 00:22:24','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.138314,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (229,1268522585,'2010-03-14 00:23:05','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.113596,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (230,1268522604,'2010-03-14 00:23:24','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.11139,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (231,1268522785,'2010-03-14 00:26:25','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.111273,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (232,1268523288,'2010-03-14 00:34:48','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.251362,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (233,1268523335,'2010-03-14 00:35:35','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.111346,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (234,1268523447,'2010-03-14 00:37:28','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.10211,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (235,1268523469,'2010-03-14 00:37:49','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.103829,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (236,1268523542,'2010-03-14 00:39:02','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.111392,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (237,1268523582,'2010-03-14 00:39:42','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.109893,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (238,1268523620,'2010-03-14 00:40:20','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.143731,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (239,1268523665,'2010-03-14 00:41:05','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.105267,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (240,1268525493,'2010-03-14 01:11:35','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,1.27793,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (241,1268525911,'2010-03-14 01:18:31','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.136952,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (242,1268525956,'2010-03-14 01:19:16','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.101885,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (243,1268525974,'2010-03-14 01:19:35','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.0990131,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4');
INSERT INTO `acces` VALUES  (244,1268525989,'2010-03-14 01:19:49','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.113598,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (245,1268526094,'2010-03-14 01:21:34','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.131541,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (246,1268526105,'2010-03-14 01:21:45','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.137592,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (247,1268526121,'2010-03-14 01:22:01','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.113381,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (248,1268527074,'2010-03-14 01:37:54','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.165132,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (249,1268527164,'2010-03-14 01:39:24','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,1.87465,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (250,1268527207,'2010-03-14 01:40:07','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.131131,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (251,1268527212,'2010-03-14 01:40:12','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.131643,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (252,1268527229,'2010-03-14 01:40:30','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.129051,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (253,1268527251,'2010-03-14 01:40:52','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.114821,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (254,1268528194,'2010-03-14 01:56:34','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.20648,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (255,1268528224,'2010-03-14 01:57:04','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.111385,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (256,1268528236,'2010-03-14 01:57:16','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.11433,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (257,1268528307,'2010-03-14 01:58:27','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.107996,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (258,1268528941,'2010-03-14 02:09:01','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.130832,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (259,1268529098,'2010-03-14 02:11:38','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.108163,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (260,1268529135,'2010-03-14 02:12:15','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.219637,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (261,1268529156,'2010-03-14 02:12:36','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.112766,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (262,1268529384,'2010-03-14 02:16:24','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.124393,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (263,1268529400,'2010-03-14 02:16:40','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.149242,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (264,1268529421,'2010-03-14 02:17:01','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.128918,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (265,1268529716,'2010-03-14 02:21:56','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.114786,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (266,1268529965,'2010-03-14 02:26:06','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.178322,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (267,1268529988,'2010-03-14 02:26:28','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.136034,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (268,1268530006,'2010-03-14 02:26:46','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.130886,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (269,1268530129,'2010-03-14 02:28:49','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.258703,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (270,1268530189,'2010-03-14 02:29:49','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.098732,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (271,1268530275,'2010-03-14 02:31:16','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.212128,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (272,1268530310,'2010-03-14 02:31:50','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.115812,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (273,1268530382,'2010-03-14 02:33:03','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.107866,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (274,1268530393,'2010-03-14 02:33:13','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.147889,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (275,1268530479,'2010-03-14 02:34:39','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.32743,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (276,1268530532,'2010-03-14 02:35:32','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.188974,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (277,1268530591,'2010-03-14 02:36:31','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.140366,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (278,1268530749,'2010-03-14 02:39:09','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.136915,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (279,1268530796,'2010-03-14 02:39:57','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.123684,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (280,1268530916,'2010-03-14 02:41:57','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.142125,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (281,1268530967,'2010-03-14 02:42:47','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.10991,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (282,1268531019,'2010-03-14 02:43:39','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.250968,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (283,1268531044,'2010-03-14 02:44:04','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.114202,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (284,1268531476,'2010-03-14 02:51:16','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.152412,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (285,1268531476,'2010-03-14 02:51:16','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.0882199,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (286,1268531528,'2010-03-14 02:52:08','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.110454,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (287,1268531549,'2010-03-14 02:52:29','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.143214,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (288,1268532111,'2010-03-14 03:01:51','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.142624,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (289,1268532461,'2010-03-14 03:07:42','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.177365,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (290,1268532476,'2010-03-14 03:07:56','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.140578,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (291,1268532549,'2010-03-14 03:09:09','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.179591,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (292,1268532657,'2010-03-14 03:10:57','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.20041,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (293,1268532692,'2010-03-14 03:11:32','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.139295,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (294,1268532758,'2010-03-14 03:12:38','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.122662,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (295,1268532773,'2010-03-14 03:12:53','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.117092,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (296,1268532903,'2010-03-14 03:15:03','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.152854,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (297,1268532965,'2010-03-14 03:16:05','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.112271,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (298,1268533121,'2010-03-14 03:18:41','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.109617,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (299,1268533170,'2010-03-14 03:19:30','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.121431,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (300,1268533469,'2010-03-14 03:24:29','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.111202,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (301,1268533483,'2010-03-14 03:24:43','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.206121,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (302,1268533521,'2010-03-14 03:25:21','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.110464,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (303,1268533590,'2010-03-14 03:26:30','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.112027,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (304,1268533665,'2010-03-14 03:27:45','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.163772,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (305,1268534648,'2010-03-14 03:44:08','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.398465,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (306,1268566134,'2010-03-14 12:28:54','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,1.03204,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (307,1268567979,'2010-03-14 12:59:39','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.110879,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (308,1268567987,'2010-03-14 12:59:47','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.122573,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (309,1268569919,'2010-03-14 13:31:59','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.159165,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (310,1268569979,'2010-03-14 13:32:59','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.109104,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (311,1268570001,'2010-03-14 13:33:21','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.107152,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (312,1268570142,'2010-03-14 13:35:42','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.112116,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (313,1268570351,'2010-03-14 13:39:11','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.110692,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (314,1268570476,'2010-03-14 13:41:16','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.160751,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (315,1268572000,'2010-03-14 14:06:40','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.109007,1,0,11272192,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (316,1268573229,'2010-03-14 14:27:09','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',0,NULL,0,0,0,0,1,'6981d1289cf341e32911eb013465c4e4'),
 (317,1268573339,'2010-03-14 14:28:59','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',0,NULL,0,0,0,0,1,'6981d1289cf341e32911eb013465c4e4'),
 (318,1268573374,'2010-03-14 14:29:34','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',26,3.8147e-06,1,0,9175040,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (319,1268573392,'2010-03-14 14:29:52','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',26,5.96046e-06,1,0,9175040,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (320,1268573404,'2010-03-14 14:30:04','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',26,5.00679e-06,1,0,9175040,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (321,1268573417,'2010-03-14 14:30:17','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',26,4.05312e-06,1,0,9175040,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (322,1268573510,'2010-03-14 14:31:51','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',0,NULL,0,0,0,0,1,'6981d1289cf341e32911eb013465c4e4'),
 (323,1268573552,'2010-03-14 14:32:32','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',0,NULL,0,0,0,0,1,'6981d1289cf341e32911eb013465c4e4'),
 (324,1268573561,'2010-03-14 14:32:42','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.114706,1,1,10223616,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (325,1268573653,'2010-03-14 14:34:13','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',19,0.128548,1,0,12582912,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (326,1268573669,'2010-03-14 14:34:29','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.16701,1,1,14155776,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (327,1268574304,'2010-03-14 14:45:04','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.159117,1,0,14155776,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (328,1268574447,'2010-03-14 14:47:27','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.112125,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (329,1268574519,'2010-03-14 14:48:39','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.136323,1,0,14155776,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (330,1268574805,'2010-03-14 14:53:25','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.160476,1,0,14155776,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (331,1268574872,'2010-03-14 14:54:32','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.109812,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (332,1268574897,'2010-03-14 14:54:57','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,3.8147e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (333,1268574988,'2010-03-14 14:56:28','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.123545,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (334,1268575002,'2010-03-14 14:56:42','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (335,1268575016,'2010-03-14 14:56:56','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.135731,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (336,1268575028,'2010-03-14 14:57:08','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (337,1268575057,'2010-03-14 14:57:37','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.158637,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (338,1268575069,'2010-03-14 14:57:49','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (339,1268576776,'2010-03-14 15:26:16','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.128085,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (340,1268576792,'2010-03-14 15:26:32','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,6.91414e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (341,1268576805,'2010-03-14 15:26:45','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.156847,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (342,1268578264,'2010-03-14 15:51:04','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.144001,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (343,1268578277,'2010-03-14 15:51:17','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,6.91414e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (344,1268579772,'2010-03-14 16:16:12','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.150343,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (345,1268579799,'2010-03-14 16:16:39','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (346,1268579883,'2010-03-14 16:18:03','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.1725,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (347,1268579898,'2010-03-14 16:18:19','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,3.8147e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (348,1268579907,'2010-03-14 16:18:27','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.207187,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (349,1268579931,'2010-03-14 16:18:51','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (350,1268579942,'2010-03-14 16:19:02','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (351,1268579944,'2010-03-14 16:19:04','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (352,1268579948,'2010-03-14 16:19:08','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (353,1268579954,'2010-03-14 16:19:14','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (354,1268579965,'2010-03-14 16:19:25','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (355,1268579967,'2010-03-14 16:19:27','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (356,1268579984,'2010-03-14 16:19:44','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.177329,1,0,12320768,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (357,1268580004,'2010-03-14 16:20:04','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (358,1268580017,'2010-03-14 16:20:17','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (359,1268580054,'2010-03-14 16:20:54','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.199565,1,0,12582912,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (360,1268580071,'2010-03-14 16:21:11','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (361,1268580158,'2010-03-14 16:22:38','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.185816,1,0,12582912,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (362,1268580170,'2010-03-14 16:22:50','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,3.8147e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (363,1268580206,'2010-03-14 16:23:26','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.203179,1,0,12582912,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (364,1268580224,'2010-03-14 16:23:44','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (365,1268580493,'2010-03-14 16:28:13','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.205703,1,0,12582912,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (366,1268580502,'2010-03-14 16:28:22','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.212147,1,0,12582912,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (367,1268580528,'2010-03-14 16:28:48','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,7.15256e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (368,1268580533,'2010-03-14 16:28:53','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (369,1268580534,'2010-03-14 16:28:55','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (370,1268594123,'2010-03-14 20:15:23','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.235533,1,0,12845056,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (371,1268594150,'2010-03-14 20:15:50','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (372,1268594196,'2010-03-14 20:16:36','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.237313,1,0,12845056,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (373,1268594224,'2010-03-14 20:17:04','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (374,1268594762,'2010-03-14 20:26:02','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.252067,1,0,15466496,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (375,1268595950,'2010-03-14 20:45:51','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (376,1268596489,'2010-03-14 20:54:49','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.223076,1,0,12845056,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (377,1268596545,'2010-03-14 20:55:45','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (378,1268596601,'2010-03-14 20:56:41','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.231366,1,0,12845056,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (379,1268596645,'2010-03-14 20:57:25','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (380,1268596679,'2010-03-14 20:58:00','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.246174,1,0,13107200,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (381,1268596757,'2010-03-14 20:59:17','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,3.8147e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (382,1268596854,'2010-03-14 21:00:54','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.237576,1,0,13107200,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (383,1268596871,'2010-03-14 21:01:11','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (384,1268596953,'2010-03-14 21:02:33','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.256654,1,0,13369344,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (385,1268596968,'2010-03-14 21:02:48','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (386,1268597143,'2010-03-14 21:05:43','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.259489,1,0,13369344,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (387,1268597157,'2010-03-14 21:05:57','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (388,1268597190,'2010-03-14 21:06:30','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.311324,1,0,13369344,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (389,1268597201,'2010-03-14 21:06:41','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (390,1268597237,'2010-03-14 21:07:17','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.326935,1,0,13369344,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (391,1268597256,'2010-03-14 21:07:36','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (392,1268597556,'2010-03-14 21:12:36','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.278384,1,0,13369344,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (393,1268597578,'2010-03-14 21:12:58','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (394,1268597608,'2010-03-14 21:13:28','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.28572,1,0,13631488,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (395,1268597632,'2010-03-14 21:13:52','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,3.8147e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (396,1268597742,'2010-03-14 21:15:43','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.150821,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (397,1268597762,'2010-03-14 21:16:02','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (398,1268597774,'2010-03-14 21:16:14','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.123859,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (399,1268597792,'2010-03-14 21:16:32','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,3.8147e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (400,1268597949,'2010-03-14 21:19:09','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.125097,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (401,1268597964,'2010-03-14 21:19:24','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,3.8147e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (402,1268597985,'2010-03-14 21:19:46','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.139631,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (403,1268598006,'2010-03-14 21:20:06','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (404,1268598018,'2010-03-14 21:20:19','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.130405,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (405,1268598035,'2010-03-14 21:20:35','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (406,1268598048,'2010-03-14 21:20:48','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.203097,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (407,1268598069,'2010-03-14 21:21:09','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (408,1268598120,'2010-03-14 21:22:00','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.154331,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (409,1268598149,'2010-03-14 21:22:29','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (410,1268598171,'2010-03-14 21:22:51','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.154081,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (411,1268598187,'2010-03-14 21:23:07','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (412,1268598218,'2010-03-14 21:23:38','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.146564,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (413,1268598236,'2010-03-14 21:23:56','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (414,1268598263,'2010-03-14 21:24:23','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.161858,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (415,1268598286,'2010-03-14 21:24:47','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (416,1268598418,'2010-03-14 21:26:58','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.222728,1,0,14680064,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (417,1268598790,'2010-03-14 21:33:11','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.199163,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (418,1268598807,'2010-03-14 21:33:27','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (419,1268598870,'2010-03-14 21:34:30','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.159977,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (420,1268598883,'2010-03-14 21:34:43','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,3.8147e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (421,1268598910,'2010-03-14 21:35:10','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.178029,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (422,1268598930,'2010-03-14 21:35:30','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (423,1268599350,'2010-03-14 21:42:30','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (424,1268599509,'2010-03-14 21:45:09','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.119631,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (425,1268599530,'2010-03-14 21:45:30','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,3.8147e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (426,1268599538,'2010-03-14 21:45:38','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.137082,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (427,1268599561,'2010-03-14 21:46:01','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.116991,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (428,1268599574,'2010-03-14 21:46:14','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (429,1268599617,'2010-03-14 21:46:57','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.197223,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (430,1268599637,'2010-03-14 21:47:17','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,3.8147e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (431,1268599654,'2010-03-14 21:47:34','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.175085,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (432,1268599654,'2010-03-14 21:47:35','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.132527,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (433,1268599673,'2010-03-14 21:47:53','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (434,1268599702,'2010-03-14 21:48:22','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.144208,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (435,1268599709,'2010-03-14 21:48:29','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.129916,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (436,1268599721,'2010-03-14 21:48:41','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (437,1268599739,'2010-03-14 21:48:59','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.234292,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (438,1268599792,'2010-03-14 21:49:52','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,3.8147e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (439,1268600123,'2010-03-14 21:55:24','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,3.8147e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (440,1268600592,'2010-03-14 22:03:12','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.145263,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (441,1268600618,'2010-03-14 22:03:38','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (442,1268600646,'2010-03-14 22:04:06','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,6.91414e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (443,1268600664,'2010-03-14 22:04:24','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.155377,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (444,1268600716,'2010-03-14 22:05:16','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (445,1268600787,'2010-03-14 22:06:27','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.175551,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (446,1268600799,'2010-03-14 22:06:39','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,3.8147e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (447,1268601175,'2010-03-14 22:12:55','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.172731,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (448,1268601199,'2010-03-14 22:13:19','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (449,1268601383,'2010-03-14 22:16:23','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.180646,1,0,12320768,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (450,1268601460,'2010-03-14 22:17:40','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (451,1268601507,'2010-03-14 22:18:27','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.198645,1,0,12320768,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (452,1268601531,'2010-03-14 22:18:51','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (453,1268601583,'2010-03-14 22:19:43','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.174471,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (454,1268601601,'2010-03-14 22:20:01','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (455,1268601678,'2010-03-14 22:21:18','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.117874,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (456,1268601689,'2010-03-14 22:21:29','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (457,1268601916,'2010-03-14 22:25:16','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (458,1268601926,'2010-03-14 22:25:26','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.307838,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (459,1268601945,'2010-03-14 22:25:45','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (460,1268601995,'2010-03-14 22:26:35','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.524285,0,1,15990784,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (461,1268602025,'2010-03-14 22:27:06','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',27,3.8147e-06,1,1,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (462,1268602034,'2010-03-14 22:27:14','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (463,1268602040,'2010-03-14 22:27:20','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',27,3.8147e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (464,1268602042,'2010-03-14 22:27:22','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.145281,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (465,1268602057,'2010-03-14 22:27:37','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.147835,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (466,1268602076,'2010-03-14 22:27:56','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.144903,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (467,1268602094,'2010-03-14 22:28:14','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.157831,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (468,1268602096,'2010-03-14 22:28:16','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.149222,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (469,1268602122,'2010-03-14 22:28:42','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (470,1268602126,'2010-03-14 22:28:46','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.155443,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (471,1268602128,'2010-03-14 22:28:48','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.164476,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4');
INSERT INTO `acces` VALUES  (472,1268602596,'2010-03-14 22:36:36','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.233587,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (473,1268602609,'2010-03-14 22:36:49','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',27,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (474,1268602632,'2010-03-14 22:37:12','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',27,3.8147e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (475,1268898229,'2010-03-18 08:43:50','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.887755,1,0,14680064,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (476,1268936810,'2010-03-18 19:26:52','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.83295,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (477,1268937209,'2010-03-18 19:33:29','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (478,1268937329,'2010-03-18 19:35:29','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.601701,1,0,15466496,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (479,1268937372,'2010-03-18 19:36:12','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',27,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (480,1268937407,'2010-03-18 19:36:47','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.209092,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (481,1268937422,'2010-03-18 19:37:03','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.560833,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (482,1268937426,'2010-03-18 19:37:06','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.653509,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (483,1268938061,'2010-03-18 19:47:41','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.124813,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (484,1268938105,'2010-03-18 19:48:25','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',18,0.109681,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (485,1268938286,'2010-03-18 19:51:26','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.146374,1,0,14155776,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (486,1268938294,'2010-03-18 19:51:34','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',18,0.114064,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (487,1268939010,'2010-03-18 20:03:30','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',19,0.125411,1,0,12845056,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (488,1268939153,'2010-03-18 20:05:53','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',16,0.10195,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (489,1268939190,'2010-03-18 20:06:30','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',19,0.12959,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (490,1268942096,'2010-03-18 20:54:57','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',8,0.152138,1,0,7340032,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (491,1268942117,'2010-03-18 20:55:17','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',9,0.189453,1,1,7602176,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (492,1268942137,'2010-03-18 20:55:37','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',17,0.15576,1,0,8912896,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (493,1268942272,'2010-03-18 20:57:53','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',17,0.259902,1,0,11010048,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (494,1268943083,'2010-03-18 21:11:23','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',17,0.211588,1,0,13369344,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (495,1268943176,'2010-03-18 21:12:56','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',17,0.159388,1,0,10747904,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (496,1268943337,'2010-03-18 21:15:37','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',13,0.081424,1,0,8388608,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (497,1268943342,'2010-03-18 21:15:42','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',13,0.0787559,1,0,8388608,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (498,1268943686,'2010-03-18 21:21:26','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',17,0.24335,1,0,14155776,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (499,1268943746,'2010-03-18 21:22:26','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.105262,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (500,1268943809,'2010-03-18 21:23:29','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.123587,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (501,1268943868,'2010-03-18 21:24:28','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.106501,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (502,1268944190,'2010-03-18 21:29:50','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',26,5.00679e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (503,1268944194,'2010-03-18 21:29:54','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',17,0.120745,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (504,1268944295,'2010-03-18 21:31:35','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',17,0.151379,1,0,14155776,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (505,1268944480,'2010-03-18 21:34:40','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.147075,1,0,14417920,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (506,1268944528,'2010-03-18 21:35:28','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.137387,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (507,1268944582,'2010-03-18 21:36:22','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.161279,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (508,1268944613,'2010-03-18 21:36:53','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.136391,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (509,1268944618,'2010-03-18 21:36:58','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.165896,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (510,1268944820,'2010-03-18 21:40:20','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.144456,1,0,12058624,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (511,1268945089,'2010-03-18 21:44:49','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.135419,1,0,14155776,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (512,1268945173,'2010-03-18 21:46:13','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.139387,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (513,1268945191,'2010-03-18 21:46:31','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.125969,1,0,11534336,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (514,1268945211,'2010-03-18 21:46:51','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',26,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (515,1268945215,'2010-03-18 21:46:55','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.154519,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (516,1268945327,'2010-03-18 21:48:47','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.12359,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (517,1268945334,'2010-03-18 21:48:54','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.120441,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (518,1268945352,'2010-03-18 21:49:12','127.0.0.1','http://lbox.localhost/api/metarecords/v0.01/','http://lbox.localhost/test','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',26,4.05312e-06,1,0,9437184,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (519,1268945522,'2010-03-18 21:52:02','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.124435,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (520,1268945529,'2010-03-18 21:52:09','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.147433,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (521,1268945567,'2010-03-18 21:52:47','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.129592,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (522,1268945581,'2010-03-18 21:53:01','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.158285,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (523,1268945609,'2010-03-18 21:53:29','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.264571,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (524,1268945641,'2010-03-18 21:54:01','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.189862,1,0,14155776,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (525,1268945681,'2010-03-18 21:54:41','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.125281,1,0,11796480,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (526,1268945753,'2010-03-18 21:55:53','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.202338,1,0,15204352,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (527,1268945778,'2010-03-18 21:56:18','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.282779,1,0,12845056,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (528,1268945815,'2010-03-18 21:56:55','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',17,0.166092,1,0,12845056,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (529,1268945869,'2010-03-18 21:57:50','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6',17,0.516267,1,0,12845056,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (530,1268946401,'2010-03-18 22:06:41','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.146406,1,0,14155776,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (531,1268946428,'2010-03-18 22:07:08','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.194936,1,0,15204352,104857600,1,'6981d1289cf341e32911eb013465c4e4'),
 (532,1268946717,'2010-03-18 22:11:57','127.0.0.1','http://lbox.localhost/test','','Mozilla/5.0 (X11; U; Linux x86_64; cs-CZ; rv:1.9.2.0) Gecko/20100115 SUSE/3.6.0-1.2 Firefox/3.6 FirePHP/0.4',17,0.165875,1,0,12845056,104857600,1,'6981d1289cf341e32911eb013465c4e4');
UNLOCK TABLES;
/*!40000 ALTER TABLE `acces` ENABLE KEYS */;


--
-- Definition of table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE  `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_type` int(11) NOT NULL,
  `url_cs` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url_sk` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `heading_cs` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `heading_sk` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `perex_cs` text COLLATE utf8_unicode_ci NOT NULL,
  `perex_sk` text COLLATE utf8_unicode_ci NOT NULL,
  `body_cs` text COLLATE utf8_unicode_ci NOT NULL,
  `body_sk` text COLLATE utf8_unicode_ci NOT NULL,
  `description_cs` text COLLATE utf8_unicode_ci,
  `description_sk` text COLLATE utf8_unicode_ci,
  `time_published` int(11) NOT NULL,
  `ref_photo` int(11) DEFAULT NULL,
  `ref_access` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='created 2010-03-07 13:54:31';

--
-- Dumping data for table `articles`
--

/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
LOCK TABLES `articles` WRITE;
INSERT INTO `articles` VALUES  (1,1,'novy-zaznam','novy-zaznam','novy zaznam','novy zaznam','novy zaznam','novy zaznam','novy zaznam','novy zaznam',NULL,NULL,0,NULL,479),
 (2,1,'test','test','test','test','test<br><div firebugversion=\"1.5.3\" style=\"display: none;\" id=\"_firebugConsole\"></div>','test','test','test',NULL,NULL,0,NULL,502),
 (3,1,'ahoj','ahoj','ahoj','ahoj','ahoj<br><div firebugversion=\"1.5.3\" style=\"display: none;\" id=\"_firebugConsole\"></div>','ahoj','ahoj','ahoj',NULL,NULL,0,NULL,514),
 (4,1,'asdasd','asdasd','asdasd','asdasd','asdasd<br><div firebugversion=\"1.5.3\" style=\"display: none;\" id=\"_firebugConsole\"></div>','asdasd','asdasd','asdasd',NULL,NULL,0,NULL,518);
UNLOCK TABLES;
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;


--
-- Definition of table `articles_types`
--

DROP TABLE IF EXISTS `articles_types`;
CREATE TABLE  `articles_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `articles_types`
--

/*!40000 ALTER TABLE `articles_types` DISABLE KEYS */;
LOCK TABLES `articles_types` WRITE;
INSERT INTO `articles_types` VALUES  (1,'news');
UNLOCK TABLES;
/*!40000 ALTER TABLE `articles_types` ENABLE KEYS */;


--
-- Definition of table `inquiries`
--

DROP TABLE IF EXISTS `inquiries`;
CREATE TABLE  `inquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_active` int(1) NOT NULL,
  `created` datetime NOT NULL,
  `question` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `i_is_active` (`is_active`),
  KEY `i_created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inquiries`
--

/*!40000 ALTER TABLE `inquiries` DISABLE KEYS */;
LOCK TABLES `inquiries` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `inquiries` ENABLE KEYS */;


--
-- Definition of table `inquiries_options`
--

DROP TABLE IF EXISTS `inquiries_options`;
CREATE TABLE  `inquiries_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_inquiry` int(11) NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `io_ref_inquiry` (`ref_inquiry`),
  CONSTRAINT `io_ref_inquiry` FOREIGN KEY (`ref_inquiry`) REFERENCES `inquiries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inquiries_options`
--

/*!40000 ALTER TABLE `inquiries_options` DISABLE KEYS */;
LOCK TABLES `inquiries_options` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `inquiries_options` ENABLE KEYS */;


--
-- Definition of table `inquiries_responses`
--

DROP TABLE IF EXISTS `inquiries_responses`;
CREATE TABLE  `inquiries_responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_option` int(11) NOT NULL,
  `ref_access` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ir_ref_option` (`ref_option`),
  KEY `ir_ref_access` (`ref_access`),
  CONSTRAINT `ir_ref_access` FOREIGN KEY (`ref_access`) REFERENCES `acces` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `ir_ref_option` FOREIGN KEY (`ref_option`) REFERENCES `inquiries_options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inquiries_responses`
--

/*!40000 ALTER TABLE `inquiries_responses` DISABLE KEYS */;
LOCK TABLES `inquiries_responses` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `inquiries_responses` ENABLE KEYS */;


--
-- Definition of table `xtroles`
--

DROP TABLE IF EXISTS `xtroles`;
CREATE TABLE  `xtroles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `xtroles`
--

/*!40000 ALTER TABLE `xtroles` DISABLE KEYS */;
LOCK TABLES `xtroles` WRITE;
INSERT INTO `xtroles` VALUES  (5,'admin'),
 (6,'superadmin'),
 (4,'user');
UNLOCK TABLES;
/*!40000 ALTER TABLE `xtroles` ENABLE KEYS */;


--
-- Definition of table `xtusers`
--

DROP TABLE IF EXISTS `xtusers`;
CREATE TABLE  `xtusers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(255) NOT NULL,
  `ref_xtRole` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `www` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `street_number` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `confirmed` int(1) DEFAULT NULL,
  `in_mailing` int(1) DEFAULT '0',
  `hash` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nick` (`nick`),
  KEY `ref_xtRole` (`ref_xtRole`),
  KEY `in_mailing` (`in_mailing`),
  CONSTRAINT `ref_xtRole` FOREIGN KEY (`ref_xtRole`) REFERENCES `xtroles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `xtusers`
--

/*!40000 ALTER TABLE `xtusers` DISABLE KEYS */;
LOCK TABLES `xtusers` WRITE;
INSERT INTO `xtusers` VALUES  (1,'palmic',5,'b3b4d2dbedc99fe843fd3dedb02f086f','2008-09-21 12:23:15','','','michal.palma@gmail.com','',NULL,NULL,NULL,NULL,1,1,'7005f23d64560fcac780f4e28dee1f3b'),
 (2,'zuzka',6,'76dad8045cbdda90b165e6c2b7c47961','2009-07-24 15:54:39','Zuzka','Svobodová','zuzana.svobodova@praguebistro.cz',NULL,NULL,NULL,NULL,NULL,1,0,'6e67a7ec60ce45d847837aaaee0ec2ab'),
 (3,'ondra',6,'47e53c8527863a978365301ac02a80dc','2009-07-24 15:54:39','Ondřej','Bach','ondrej.bach@praguebistro.cz',NULL,NULL,NULL,NULL,NULL,1,0,NULL),
 (4,'zdenda',6,'b8662d5fd0b74be56da560c01d37d739','2009-07-24 15:54:39',NULL,NULL,'zdenek.reichl@praguebistro.cz',NULL,NULL,NULL,NULL,NULL,1,0,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `xtusers` ENABLE KEYS */;


--
-- Definition of view `accesnotviewers`
--

DROP TABLE IF EXISTS `accesnotviewers`;
DROP VIEW IF EXISTS `accesnotviewers`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `accesnotviewers` AS select `acces`.`id` AS `id`,`acces`.`request_time` AS `request_time`,`acces`.`time` AS `time`,`acces`.`ip` AS `ip`,`acces`.`url` AS `url`,`acces`.`referer` AS `referer`,`acces`.`agent` AS `agent`,`acces`.`ref_xtuser` AS `ref_xtUser` from `acces` where ((not((lcase(`acces`.`agent`) like _utf8'%mozilla%'))) and (not((lcase(`acces`.`agent`) like _utf8'%opera%'))));

--
-- Definition of view `access_xtusers`
--

DROP TABLE IF EXISTS `access_xtusers`;
DROP VIEW IF EXISTS `access_xtusers`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `access_xtusers` AS select `acces`.`id` AS `id`,`acces`.`request_time` AS `request_time`,`acces`.`time` AS `time`,`acces`.`ip` AS `ip`,`acces`.`url` AS `url`,`acces`.`referer` AS `referer`,`acces`.`agent` AS `agent`,`acces`.`queries` AS `queries`,`acces`.`time_execution` AS `time_execution`,`acces`.`cache_read` AS `cache_read`,`acces`.`cache_write` AS `cache_write`,`acces`.`memory` AS `memory`,`acces`.`memory_limit` AS `memory_limit`,`acces`.`ref_xtuser` AS `ref_xtuser`,`acces`.`session_id` AS `session_id`,`xtusers`.`nick` AS `nick`,`xtusers`.`ref_xtRole` AS `ref_xtRole`,`xtusers`.`password` AS `password`,`xtusers`.`created` AS `created`,`xtusers`.`name` AS `name`,`xtusers`.`surname` AS `surname`,`xtusers`.`email` AS `email`,`xtusers`.`www` AS `www`,`xtusers`.`confirmed` AS `confirmed`,`xtusers`.`in_mailing` AS `in_mailing`,`xtusers`.`hash` AS `hash` from (`xtusers` left join `acces` on((`acces`.`ref_xtuser` = `xtusers`.`id`))) where (`acces`.`id` is not null);

--
-- Definition of view `accesviewers`
--

DROP TABLE IF EXISTS `accesviewers`;
DROP VIEW IF EXISTS `accesviewers`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `accesviewers` AS select `acces`.`id` AS `id`,`acces`.`request_time` AS `request_time`,`acces`.`time` AS `time`,`acces`.`ip` AS `ip`,`acces`.`url` AS `url`,`acces`.`referer` AS `referer`,`acces`.`agent` AS `agent`,`acces`.`ref_xtuser` AS `ref_xtUser` from `acces` where ((lcase(`acces`.`agent`) like _utf8'%windows%') or (lcase(`acces`.`agent`) like _utf8'%linux%') or (lcase(`acces`.`agent`) like _utf8'%mac os%'));

--
-- Definition of view `accesviewersunique`
--

DROP TABLE IF EXISTS `accesviewersunique`;
DROP VIEW IF EXISTS `accesviewersunique`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `accesviewersunique` AS select distinct `acces`.`ip` AS `ip` from `acces` where ((lcase(`acces`.`agent`) like _utf8'%windows%') or (lcase(`acces`.`agent`) like _utf8'%linux%') or (lcase(`acces`.`agent`) like _utf8'%mac os%'));

--
-- Definition of view `inquiries_options_responses`
--

DROP TABLE IF EXISTS `inquiries_options_responses`;
DROP VIEW IF EXISTS `inquiries_options_responses`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `inquiries_options_responses` AS select `inquiries`.`id` AS `ref_inquiry`,`inquiries_options`.`id` AS `ref_option`,`inquiries_responses`.`id` AS `ref_response`,`inquiries`.`question` AS `question`,`inquiries`.`created` AS `created`,`inquiries_options`.`answer` AS `answer`,`acces`.`time` AS `time`,`acces`.`ref_xtuser` AS `ref_xtUser`,`acces`.`ip` AS `ip` from (((`inquiries` left join `inquiries_options` on((`inquiries_options`.`ref_inquiry` = `inquiries`.`id`))) left join `inquiries_responses` on((`inquiries_responses`.`ref_option` = `inquiries_options`.`id`))) left join `acces` on((`inquiries_responses`.`ref_access` = `acces`.`id`)));

--
-- Definition of view `inquiries_summaries`
--

DROP TABLE IF EXISTS `inquiries_summaries`;
DROP VIEW IF EXISTS `inquiries_summaries`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `inquiries_summaries` AS select `inq`.`id` AS `ref_inquiry`,`inq_opt`.`id` AS `ref_option`,`inq`.`question` AS `question`,`inq`.`created` AS `created`,`inq_opt`.`answer` AS `answer`,count(`inq_res`.`id`) AS `count_responses_option`,(select count(`inquiries_responses`.`id`) AS `count(inquiries_responses.id)` from ((`inquiries` left join `inquiries_options` on((`inquiries_options`.`ref_inquiry` = `inquiries`.`id`))) left join `inquiries_responses` on((`inquiries_responses`.`ref_option` = `inquiries_options`.`id`))) where (`inquiries_options`.`ref_inquiry` = `inq`.`id`)) AS `count_responses_inquiry`,(case when ((select count(`inquiries_responses`.`id`) AS `count(inquiries_responses.id)` from ((`inquiries` left join `inquiries_options` on((`inquiries_options`.`ref_inquiry` = `inquiries`.`id`))) left join `inquiries_responses` on((`inquiries_responses`.`ref_option` = `inquiries_options`.`id`))) where (`inquiries_options`.`ref_inquiry` = `inq`.`id`)) > 0) then round(((count(`inq_res`.`id`) / (select count(`inquiries_responses`.`id`) AS `count(inquiries_responses.id)` from ((`inquiries` left join `inquiries_options` on((`inquiries_options`.`ref_inquiry` = `inquiries`.`id`))) left join `inquiries_responses` on((`inquiries_responses`.`ref_option` = `inquiries_options`.`id`))) where (`inquiries_options`.`ref_inquiry` = `inq`.`id`))) * 100),0) else 0 end) AS `responses_option_percent` from ((`inquiries` `inq` left join `inquiries_options` `inq_opt` on((`inq_opt`.`ref_inquiry` = `inq`.`id`))) left join `inquiries_responses` `inq_res` on((`inq_res`.`ref_option` = `inq_opt`.`id`))) group by `inq_opt`.`id` order by `inq`.`created` desc;

--
-- Definition of view `performance_pages_base`
--

DROP TABLE IF EXISTS `performance_pages_base`;
DROP VIEW IF EXISTS `performance_pages_base`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `performance_pages_base` AS select (case when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) > 0) and (locate(_utf8'?',`acces`.`url`) < 1)) then substr(`acces`.`url`,1,((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) + locate(_utf8'//',`acces`.`url`)) - 2)) when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) < 1) and (locate(_utf8'?',`acces`.`url`) > 0)) then substr(`acces`.`url`,1,(locate(_utf8'?',`acces`.`url`) - 1)) when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) > 0) and (locate(_utf8'?',`acces`.`url`) > 0)) then (case when (locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) < locate(_utf8'?',`acces`.`url`)) then substr(`acces`.`url`,1,((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) + locate(_utf8'//',`acces`.`url`)) - 2)) else substr(`acces`.`url`,1,(locate(_utf8'?',`acces`.`url`) - 1)) end) else `acces`.`url` end) AS `page`,avg(`acces`.`time_execution`) AS `time_execution_avg`,sum(`acces`.`time_execution`) AS `time_execution_sum`,round(avg(`acces`.`queries`),2) AS `queries_avg`,sum(`acces`.`queries`) AS `queries_sum`,count(`acces`.`id`) AS `hits`,min(`acces`.`time`) AS `first_hit`,max(`acces`.`time`) AS `last_hit` from `acces` where (`acces`.`time_execution` is not null) group by (case when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) > 0) and (locate(_utf8'?',`acces`.`url`) < 1)) then substr(`acces`.`url`,1,((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) + locate(_utf8'//',`acces`.`url`)) - 2)) when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) < 1) and (locate(_utf8'?',`acces`.`url`) > 0)) then substr(`acces`.`url`,1,(locate(_utf8'?',`acces`.`url`) - 1)) when ((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) > 0) and (locate(_utf8'?',`acces`.`url`) > 0)) then (case when (locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) < locate(_utf8'?',`acces`.`url`)) then substr(`acces`.`url`,1,((locate(_utf8':',substr(`acces`.`url`,locate(_utf8'//',`acces`.`url`))) + locate(_utf8'//',`acces`.`url`)) - 2)) else substr(`acces`.`url`,1,(locate(_utf8'?',`acces`.`url`) - 1)) end) else `acces`.`url` end) order by avg(`acces`.`time_execution`) desc;

--
-- Definition of view `performance_urls_base`
--

DROP TABLE IF EXISTS `performance_urls_base`;
DROP VIEW IF EXISTS `performance_urls_base`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `performance_urls_base` AS select `acces`.`url` AS `url`,avg(`acces`.`time_execution`) AS `time_execution_avg`,sum(`acces`.`time_execution`) AS `time_execution_sum`,round(avg(`acces`.`queries`),2) AS `queries_avg`,sum(`acces`.`queries`) AS `queries_sum`,count(0) AS `hits`,min(`acces`.`time`) AS `first_hit`,max(`acces`.`time`) AS `last_hit` from `acces` where (`acces`.`time_execution` is not null) group by `acces`.`url` order by avg(`acces`.`time_execution`) desc;



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
