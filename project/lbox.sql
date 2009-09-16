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
  CONSTRAINT `ref_xtuser` FOREIGN KEY (`ref_xtuser`) REFERENCES `xtusers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3050 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `acces`
--

/*!40000 ALTER TABLE `acces` DISABLE KEYS */;
/*!40000 ALTER TABLE `acces` ENABLE KEYS */;


--
-- Definition of table `active_forms`
--

DROP TABLE IF EXISTS `active_forms`;
CREATE TABLE `active_forms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_xtuser` int(10) unsigned NOT NULL,
  `ref_lead` int(10) unsigned NOT NULL,
  `form_name` varchar(255) NOT NULL,
  `ref_access` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_form_name` (`form_name`,`ref_lead`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `active_forms`
--

/*!40000 ALTER TABLE `active_forms` DISABLE KEYS */;
INSERT INTO `active_forms` (`id`,`ref_xtuser`,`ref_lead`,`form_name`,`ref_access`,`time`) VALUES 
 (1,1,11,'calling-step-1',3045,1253093519),
 (2,1,11,'calling-step-2',3045,1253093519),
 (3,1,11,'calling-step-3',3045,1253093519),
 (4,1,11,'meeting-step-1',3045,1253093520),
 (5,1,11,'meeting-step-2',3045,1253093520),
 (6,1,11,'meeting-step-3',3045,1253093520),
 (7,1,11,'conclusion-step-1',3045,1253093521);
/*!40000 ALTER TABLE `active_forms` ENABLE KEYS */;


--
-- Definition of table `data_callings`
--

DROP TABLE IF EXISTS `data_callings`;
CREATE TABLE `data_callings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_lead` int(10) unsigned NOT NULL,
  `ref_access` int(10) unsigned NOT NULL,
  `step` int(10) unsigned NOT NULL,
  `happend` varchar(255) DEFAULT NULL,
  `happend_reason_not` text,
  `date_called` varchar(255) DEFAULT NULL,
  `calling_result` varchar(255) DEFAULT NULL,
  `calling_result_reason` varchar(255) DEFAULT NULL,
  `reason_other` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_step` (`ref_lead`,`step`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `data_callings`
--

/*!40000 ALTER TABLE `data_callings` DISABLE KEYS */;
INSERT INTO `data_callings` (`id`,`ref_lead`,`ref_access`,`step`,`happend`,`happend_reason_not`,`date_called`,`calling_result`,`calling_result_reason`,`reason_other`) VALUES 
 (1,1,2942,1,'ano','','2009-09-25','špatné telefonní číslo','',''),
 (2,10,2950,1,'ano','','2009-09-15','nemá zájem','produkt mu přijde drahý',''),
 (3,11,2963,1,'ano','','2009-09-15','domluvena schůzka','','');
/*!40000 ALTER TABLE `data_callings` ENABLE KEYS */;


--
-- Definition of table `data_conclusions`
--

DROP TABLE IF EXISTS `data_conclusions`;
CREATE TABLE `data_conclusions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_lead` int(10) unsigned NOT NULL,
  `ref_access` int(10) unsigned NOT NULL,
  `step` int(10) unsigned NOT NULL,
  `process_length` varchar(255) DEFAULT NULL,
  `message_risk_life_insurance` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_step` (`ref_lead`,`step`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `data_conclusions`
--

/*!40000 ALTER TABLE `data_conclusions` DISABLE KEYS */;
INSERT INTO `data_conclusions` (`id`,`ref_lead`,`ref_access`,`step`,`process_length`,`message_risk_life_insurance`) VALUES 
 (1,1,2944,1,'méně než hodina','error'),
 (2,10,2952,1,'méně než hodina','nezajem');
/*!40000 ALTER TABLE `data_conclusions` ENABLE KEYS */;


--
-- Definition of table `data_meetings`
--

DROP TABLE IF EXISTS `data_meetings`;
CREATE TABLE `data_meetings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_lead` int(10) unsigned NOT NULL,
  `ref_access` int(10) unsigned NOT NULL,
  `step` int(10) unsigned NOT NULL,
  `happend` varchar(45) DEFAULT NULL,
  `happend_not_reason` varchar(45) DEFAULT NULL,
  `happend_not_reason_other` varchar(45) DEFAULT NULL,
  `happend_not_reason_client_mindchange` varchar(45) DEFAULT NULL,
  `happend_not_reason_client_mindchange_other` varchar(45) DEFAULT NULL,
  `date_happend` varchar(45) DEFAULT NULL,
  `result` varchar(45) DEFAULT NULL,
  `zaujal_ho_produkt_obecne` int(1) unsigned DEFAULT NULL,
  `produkt_mu_prijde_vyhodny` int(1) unsigned DEFAULT NULL,
  `zaujaly_ho_parametry_produktu_obecne` int(1) unsigned DEFAULT NULL,
  `zaujal_ho_direct_mail` int(1) unsigned DEFAULT NULL,
  `zaujalo_ho_nizke_pojistne` int(1) unsigned DEFAULT NULL,
  `zaujala_ho_vyse_pojistne_castky` int(1) unsigned DEFAULT NULL,
  `zaujala_ho_delka_pojisteni` int(1) unsigned DEFAULT NULL,
  `zaujala_ho_pojistna_rizika` int(1) unsigned DEFAULT NULL,
  `zaujalo_ho_neco_jineho` text,
  `sell_other` text,
  `nezaujal_ho_produkt_obecne` int(1) unsigned DEFAULT NULL,
  `produkt_mu_prijde_nevyhodny` int(1) unsigned DEFAULT NULL,
  `nezaujaly_ho_parametry_produktu_obecne` int(1) unsigned DEFAULT NULL,
  `nezaujal_ho_direct_mail` int(1) unsigned DEFAULT NULL,
  `nezaujalo_ho_nizke_pojistne` int(1) unsigned DEFAULT NULL,
  `nezaujala_ho_vyse_pojistne_castky` int(1) unsigned DEFAULT NULL,
  `nezaujala_ho_delka_pojisteni` int(1) unsigned DEFAULT NULL,
  `nezaujala_ho_pojistna_rizika` int(1) unsigned DEFAULT NULL,
  `nezaujalo_ho_neco_jineho` text,
  `not_sell_other` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_step` (`ref_lead`,`step`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `data_meetings`
--

/*!40000 ALTER TABLE `data_meetings` DISABLE KEYS */;
/*!40000 ALTER TABLE `data_meetings` ENABLE KEYS */;


--
-- Definition of table `data_questionaries`
--

DROP TABLE IF EXISTS `data_questionaries`;
CREATE TABLE `data_questionaries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_lead` int(10) unsigned NOT NULL,
  `ref_access` int(10) unsigned NOT NULL,
  `step` int(10) unsigned NOT NULL,
  `date_called` varchar(255) DEFAULT NULL,
  `calling_result` varchar(255) DEFAULT NULL,
  `reaction_dm_remember` varchar(255) DEFAULT NULL,
  `reaction_dm_conclusion` varchar(255) DEFAULT NULL,
  `reaction_dm_conclusion_reasons_produkt_obecne` int(1) unsigned DEFAULT NULL,
  `reaction_dm_conclusion_reasons_forma_kreativní_cast` int(1) unsigned DEFAULT NULL,
  `reaction_dm_conclusion_reasons_forma_dopis_od_bankere` int(1) unsigned DEFAULT NULL,
  `reaction_dm_conclusion_reasons_obsah_parametry_produktu` int(1) unsigned DEFAULT NULL,
  `reaction_dm_conclusion_reasons_jiny_duvod` int(1) unsigned DEFAULT NULL,
  `reaction_dm_reasons_other` varchar(255) DEFAULT NULL,
  `zaujal_ho_produkt_obecne` int(1) unsigned DEFAULT NULL,
  `produkt_mu_prijde_vyhodny` int(1) unsigned DEFAULT NULL,
  `zaujaly_ho_parametry_produktu_obecne` int(1) unsigned DEFAULT NULL,
  `zaujal_ho_direct_mail` int(1) unsigned DEFAULT NULL,
  `zaujalo_ho_nizke_pojistne` int(1) unsigned DEFAULT NULL,
  `zaujala_ho_vyse_pojistne_castky` int(1) unsigned DEFAULT NULL,
  `zaujala_ho_delka_pojisteni` int(1) unsigned DEFAULT NULL,
  `zaujala_ho_pojistna_rizika` int(1) unsigned DEFAULT NULL,
  `presvedcil_ho_poradce` int(1) unsigned DEFAULT NULL,
  `zaujalo_ho_neco_jineho` int(1) unsigned DEFAULT NULL,
  `reaction_sell_reasons_happend_other` varchar(255) DEFAULT NULL,
  `nezaujal_ho_produkt_obecne` int(1) unsigned DEFAULT NULL,
  `produkt_mu_prijde_nevyhodny` int(1) unsigned DEFAULT NULL,
  `nezaujaly_ho_parametry_produktu_obecne` int(1) unsigned DEFAULT NULL,
  `nezaujal_ho_direct_mail` int(1) unsigned DEFAULT NULL,
  `nezaujalo_ho_nizke_pojistne` int(1) unsigned DEFAULT NULL,
  `nezaujala_ho_vyse_pojistne_castky` int(1) unsigned DEFAULT NULL,
  `nezaujala_ho_delka_pojisteni` int(1) unsigned DEFAULT NULL,
  `nezaujala_ho_pojistna_rizika` int(1) unsigned DEFAULT NULL,
  `nepresvedcil_ho_poradce` int(1) unsigned DEFAULT NULL,
  `nezaujalo_ho_neco_jineho` int(1) unsigned DEFAULT NULL,
  `reaction_sell_reasons_not_happend_other` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_step` (`ref_lead`,`step`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `data_questionaries`
--

/*!40000 ALTER TABLE `data_questionaries` DISABLE KEYS */;
/*!40000 ALTER TABLE `data_questionaries` ENABLE KEYS */;


--
-- Definition of table `leads`
--

DROP TABLE IF EXISTS `leads`;
CREATE TABLE `leads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(255) DEFAULT NULL,
  `creative_name` varchar(255) DEFAULT NULL,
  `date_sent` varchar(255) DEFAULT NULL,
  `banker_name` varchar(255) DEFAULT NULL,
  `client_segment` varchar(255) DEFAULT NULL,
  `client_age` varchar(255) DEFAULT NULL,
  `client_last_client_care` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `credit_card` varchar(255) DEFAULT NULL,
  `account` varchar(255) DEFAULT NULL,
  `retirement` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `leads`
--

/*!40000 ALTER TABLE `leads` DISABLE KEYS */;
INSERT INTO `leads` (`id`,`hash`,`creative_name`,`date_sent`,`banker_name`,`client_segment`,`client_age`,`client_last_client_care`,`firstname`,`surname`,`street`,`region`,`zip`,`phone`,`credit_card`,`account`,`retirement`) VALUES 
 (1,'dffa0','sloupce','29.9.2009','Božena Trousilová','A','28','4.1.2009','JOSEF','TESTOVACÍ1','KŘELOVICE 122','KŘELOVICE','394 45','776709672','1','1','1'),
 (2,'7c82b','sloupce','6.10.2009','Božena Trousilová','A','40','','JOSEF','TESTOVACÍ2','BĚLEHRADSKÁ 1402/47','PRAHA 2','120 00','724360585','1','1','1'),
 (3,'687b5','sloupce','13.10.2009','Božena Trousilová','A','35','7.29.2009','JOSEF','TESTOVACÍ3','BĚLEHRADSKÁ 76','PRAHA 2','120 00','603433231','1','1','1'),
 (4,'3b387','louka','29.9.2009','Božena Trousilová','A','29','','JOSEF','TESTOVACÍ4','KOSTŘÍNSKÁ 583','PRAHA 8','181 00','607585708','1','1','1'),
 (5,'91597','louka','6.10.2009','Božena Trousilová','A','29','4.16.2009','JOSEF','TESTOVACÍ5','NA LADA 16','SVĚTICE','251 01','602169927','1','1','1'),
 (6,'247d1','louka','13.10.2009','Božena Trousilová','A','38','3.17.2009','JOSEF','TESTOVACÍ6','PŘEMYSLOVSKÁ 2244','ROZTOKY U PRAHY','252 63','731135729','1','1','1'),
 (7,'e2a52','louka','29.9.2009','Božena Trousilová','A','40','8.6.2008','JOSEF','TESTOVACÍ7','Františka Diviše 1437/54 M','Praha 4-Uhřiněves','104 00','602347619','1','1','1'),
 (8,'32122','louka','6.10.2009','Božena Trousilová','A','29','','JOSEF','TESTOVACÍ8','SÍDLIŠTĚ I 17','KAMENICE','251 68','607558961','1','1','1'),
 (9,'31794','vodaci','13.10.2009','Božena Trousilová','A','30','11.20.2007','JOSEF','TESTOVACÍ9','VENUŠINA 1335/30','PRAHA 4 - MODŘANY','143 00','604257866','1','1','1'),
 (10,'86e32','sloupce','29.9.2009','Božena Trousilová','A','33','','JOSEF','TESTOVACÍ10','LUBLINSKÁ 574/7','PRAHA 8','181 00','607871446','1','1','1'),
 (11,'050ad','sloupce','6.10.2009','Božena Trousilová','B','30','','JOSEF','TESTOVACÍ11','U TŘEŠŇOVKY 9','PRAHA 8','182 00','602682627','1','1','1'),
 (12,'12b7b','sloupce','13.10.2009','Božena Trousilová','A','40','','JOSEF','TESTOVACÍ12','Široká 10','Jablonec nad Nisou','466 01','602183690','1','1','1'),
 (13,'11e7f','sloupce','29.9.2009','Božena Trousilová','A','25','11.9.2007','JOSEF','TESTOVACÍ13','ZAKŠÍNSKÁ 571/8','PRAHA 9 - STŘÍŽKOV','190 00','731105901','1','1','1'),
 (14,'f6a7b','louka','6.10.2009','Božena Trousilová','B','33','','JOSEF','TESTOVACÍ14','PŘÍSTAVNÍ 23','PRAHA 7','170 00','732105901','1','1','1'),
 (15,'5cdbd','louka','13.10.2009','Božena Trousilová','A','38','8.14.2007','JOSEF','TESTOVACÍ15','MALKOVSKÉHO 601','PRAHA 9 LETŇANY','199 00','776230086','1','1','1'),
 (16,'e799a','vodaci','29.9.2009','Božena Trousilová','A','33','','JOSEF','TESTOVACÍ16','MAKOVSKÉHO 1177/1','PRAHA 6','163 00','603316624','1','1','1'),
 (17,'92f5d','vodaci','6.10.2009','Božena Trousilová','A','36','','JOSEF','TESTOVACÍ17','TAUSSIGOVA 1168/9','PRAHA 8','182 00','608755716','1','1','1'),
 (18,'778b5','sloupce','13.10.2009','Božena Trousilová','A','40','','JOSEF','TESTOVACÍ18','ELIŠKY PŘEMYSLOVNY 1259','PRAHA 5','150 00','603442093','1','1','1'),
 (19,'0c95d','sloupce','29.9.2009','Božena Trousilová','A','36','7.1.2009','JOSEF','TESTOVACÍ19','MYSLÍKOVA 6/257','PRAHA 2','120 00','603429193','1','1','1'),
 (20,'523dc','sloupce','6.10.2009','Božena Trousilová','A','35','6.8.2009','JOSEF','TESTOVACÍ20','CECHOVNÍ 161','JENŠTEJN','250 73','602285171','1','1','1'),
 (21,'c517b','sloupce','13.10.2009','Božena Trousilová','A','30','','JOSEF','TESTOVACÍ21','BUDAPEŠŤSKÁ 1491/3','PRAHA 10','102 00','602425878','1','1','1'),
 (22,'c0f2d','sloupce','29.9.2009','Božena Trousilová','A','37','9.3.2008','JOSEF','TESTOVACÍ22','DOLANY 12','DOLANY','783 16','776729210','1','1','1'),
 (23,'93ca4','sloupce','6.10.2009','Božena Trousilová','A','37','4.7.2009','JOSEF','TESTOVACÍ23','BRANDÝSKÁ 314/C','MRATÍN','250 63','606231648','1','1','1'),
 (24,'dd33a','sloupce','13.10.2009','Božena Trousilová','A','35','3.4.2008','JOSEF','TESTOVACÍ24','K LESÍKU 473','LETY','252 29','602213197','1','1','1'),
 (25,'cf555','sloupce','29.9.2009','Božena Trousilová','A','31','4.7.2009','JOSEF','TESTOVACÍ25','ŠKOLSKÁ 321','KLADNO','272 01','737280110','1','1','1'),
 (26,'c9290','louka','6.10.2009','Božena Trousilová','A','35','8.29.2008','JOSEF','TESTOVACÍ26','NA CÍSAŘCE 3224/26','PRAHA 5 - SMÍCHOV','150 00','775387479','1','1','1'),
 (27,'3fc72','louka','13.10.2009','Božena Trousilová','A','34','5.27.2009','JOSEF','TESTOVACÍ27','PRAŽSKÁ 1430/36','PRAHA 10','102 00','777070514','1','1','1'),
 (28,'fe0b4','louka','29.9.2009','Božena Trousilová','A','36','8.12.2008','JOSEF','TESTOVACÍ28','CTĚNICKÁ 693/9','PRAHA 9 PROSEK','190 00','724371721','1','1','1'),
 (29,'fa1cb','louka','6.10.2009','Božena Trousilová','A','36','7.22.2009','JOSEF','TESTOVACÍ29','NA TŘEBEŠÍNĚ 1016/23','PRAHA 10','100 00','775955007','1','1','1'),
 (30,'91014','vodaci','13.10.2009','Božena Trousilová','A','37','6.10.2009','JOSEF','TESTOVACÍ30','NOVÁ LIPOVÁ 610','VELKÉ POPOVICE','251 69','602612175','1','1','1');
/*!40000 ALTER TABLE `leads` ENABLE KEYS */;


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
 (4,'test-poradce',5,'202cb962ac59075b964b07152d234b70','2009-09-14 21:44:17',NULL,NULL,NULL,NULL,1,0,NULL,NULL),
 (5,'test-operator',5,'202cb962ac59075b964b07152d234b70','2009-09-14 21:44:17',NULL,NULL,NULL,NULL,1,0,NULL,'cc'),
 (6,'poradce1',5,'ec6ef230f1828039ee794566b9c58adc','2009-09-14 23:02:28',NULL,NULL,NULL,NULL,1,0,NULL,NULL),
 (7,'poradce2',5,'1d665b9b1467944c128a5575119d1cfd','2009-09-14 23:02:28',NULL,NULL,NULL,NULL,1,0,NULL,NULL),
 (8,'poradce3',5,'7bc3ca68769437ce986455407dab2a1f','2009-09-14 23:02:28',NULL,NULL,NULL,NULL,1,0,NULL,NULL),
 (9,'poradce4',5,'13207e3d5722030f6c97d69b4904d39d','2009-09-14 23:02:28',NULL,NULL,NULL,NULL,1,0,NULL,NULL),
 (10,'operator1',5,'f1584b995a4770986ad75bb8d29e9734','2009-09-14 23:03:02',NULL,NULL,NULL,NULL,1,0,NULL,'cc');
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
