CREATE DATABASE  IF NOT EXISTS `assignment_router` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `assignment_router`;
-- MySQL dump 10.13  Distrib 5.6.24, for osx10.8 (x86_64)
--
-- Host: localhost    Database: assignment_router
-- ------------------------------------------------------
-- Server version	5.5.36

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
-- Table structure for table `assignment_files`
--

DROP TABLE IF EXISTS `assignment_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assignment_files` (
  `assignment_id` int(10) unsigned NOT NULL,
  `role_id` tinyint(1) unsigned NOT NULL,
  `file_name` varchar(90) NOT NULL,
  PRIMARY KEY (`assignment_id`,`role_id`,`file_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `assignment_roles`
--

DROP TABLE IF EXISTS `assignment_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assignment_roles` (
  `assignment_id` int(10) unsigned NOT NULL,
  `role_id` tinyint(1) unsigned NOT NULL,
  `role_name` varchar(90) NOT NULL,
  `role_description` text NOT NULL,
  PRIMARY KEY (`assignment_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `assignments`
--

DROP TABLE IF EXISTS `assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assignments` (
  `course_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `assignment_name` varchar(90) NOT NULL,
  PRIMARY KEY (`course_id`,`assignment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `course_sections`
--

DROP TABLE IF EXISTS `course_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course_sections` (
  `course_id` mediumint(8) unsigned NOT NULL,
  `section` tinyint(3) unsigned NOT NULL,
  `section_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`course_id`,`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `course_users`
--

DROP TABLE IF EXISTS `course_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course_users` (
  `course_id` int(10) unsigned NOT NULL,
  `net_id` varchar(12) NOT NULL,
  `user_name` varchar(120) NOT NULL,
  `section_id` int(10) unsigned NOT NULL,
  `canvas_user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`course_id`,`net_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `role_members`
--

DROP TABLE IF EXISTS `role_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_members` (
  `assignment_id` int(10) unsigned NOT NULL,
  `net_id` varchar(12) NOT NULL,
  `role_id` tinyint(2) unsigned NOT NULL,
  `downloaded` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`assignment_id`,`net_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-07-18 15:21:51
