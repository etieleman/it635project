-- MySQL dump 10.13  Distrib 5.7.22, for Linux (x86_64)
--
-- Host: localhost    Database: it635
-- ------------------------------------------------------
-- Server version	5.7.22-0ubuntu0.16.04.1-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `it635`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `it635` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `it635`;

--
-- Table structure for table `assets`
--

DROP TABLE IF EXISTS `assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assets` (
  `aid` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `owner` varchar(10) DEFAULT NULL,
  `curr` varchar(10) DEFAULT NULL,
  `assetCondition` varchar(6) NOT NULL,
  `notes` text,
  PRIMARY KEY (`aid`),
  KEY `owner` (`owner`),
  KEY `curr` (`curr`),
  CONSTRAINT `assets_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `employees` (`eid`) ON DELETE CASCADE,
  CONSTRAINT `assets_ibfk_2` FOREIGN KEY (`curr`) REFERENCES `employees` (`eid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10014 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assets`
--

LOCK TABLES `assets` WRITE;
/*!40000 ALTER TABLE `assets` DISABLE KEYS */;
INSERT INTO `assets` VALUES (10001,'Dell Optiplex 2000','ert4','rps9','Okay',NULL),(10002,'Cisco Phone 300','ert4','rps9','Okay',NULL),(10003,'Samsung Galaxy Note 2','fmg10','storage','New',NULL),(10004,'Dell Optiplex 2000','fmg10','lol6','Poor',NULL),(10005,'Cisco Phone 300','ert4','lol6','Broken','No longer working'),(10006,'Wacom Tablet','ert4','retired','Broken','Broken, retired'),(10007,'Asus UltraView Monitor','fmg10','storage','New','144hz for gaming!'),(10008,'Dell Optiplex','fmg10','retired','Okay','For useing'),(10009,'ReplicationTest','fmg10','fmg10','Good','For testing'),(10010,'TestAsset2','fmg10','retired','Okay','testing 2'),(10011,'ProcTest','fmg10','retired','Okay','Procedure Test'),(10012,'WebProcTest','fmg10','fmg10','Good','Web procedure test');
/*!40000 ALTER TABLE `assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `eid` varchar(10) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `role` varchar(10) DEFAULT NULL,
  `pass` char(64) NOT NULL,
  PRIMARY KEY (`eid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES ('ert4','Erik','Tieleman','Manager','b97873a40f73abedd8d685a7cd5e5f85e4a9cfb83eac26886640a0813850122b'),('fmg10','Friedrich','Griswald','Manager','aa4a9ea03fcac15b5fc63c949ac34e7b0fd17906716ac3b8e58c599cdc5a52f0'),('lol6','Larry','Levin','User','598a1a400c1dfdf36974e69d7e1bc98593f2e15015eed8e9b7e47a83b31693d5'),('retired','Retired','Assets','nologin','ba9abeca6d1f9283ee52aaa4db2114df4374a5ae9c2af490824be02fb27e0d0f'),('rps9','Richard','Stephenson','User','9323dd6786ebcbf3ac87357cc78ba1abfda6cf5e55cd01097b90d4a286cac90e'),('storage','Storage','User','nologin','ba9abeca6d1f9283ee52aaa4db2114df4374a5ae9c2af490824be02fb27e0d0f');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `requests` (
  `rid` int(5) NOT NULL AUTO_INCREMENT,
  `eid` varchar(10) NOT NULL,
  `aid` int(5) DEFAULT NULL,
  `reqtype` varchar(11) NOT NULL,
  `status` varchar(8) NOT NULL,
  `opened` datetime NOT NULL,
  `closed` datetime DEFAULT NULL,
  `details` text,
  PRIMARY KEY (`rid`),
  KEY `eid` (`eid`),
  KEY `aid` (`aid`),
  CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`eid`) REFERENCES `employees` (`eid`) ON DELETE CASCADE,
  CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`aid`) REFERENCES `assets` (`aid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20009 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requests`
--

LOCK TABLES `requests` WRITE;
/*!40000 ALTER TABLE `requests` DISABLE KEYS */;
INSERT INTO `requests` VALUES (20001,'rps9',10001,'Use','Approved','2018-03-01 12:00:00','2018-03-11 14:00:00','Need PC to do work'),(20002,'rps9',10002,'Use','Approved','2018-03-01 12:10:00','2018-03-11 14:10:00','Need phone for calls'),(20003,'lol6',10006,'Retirement','Approved','2018-03-03 08:00:00','2018-03-04 19:00:00','Device is broken'),(20004,'lol6',NULL,'Purchase','Open','2018-03-05 12:00:00',NULL,'Need a powerful desktop for rendering work'),(20005,'rps9',NULL,'Purchase','Denied','2018-03-06 14:00:00','2018-03-15 14:10:00','Unlimited snack bar for the office'),(20006,'rps9',10007,'Retirement','Open','2018-03-08 19:00:00',NULL,'Waste of budget; we need to return this'),(20007,'lol6',10003,'Use','Open','2018-03-07 11:00:00',NULL,'Need a cell phone for attending conferences'),(20008,'lol6',10005,'Use','Approved','2018-04-05 00:01:49','2018-04-05 00:02:43','I need a phone');
/*!40000 ALTER TABLE `requests` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-09 20:06:11
