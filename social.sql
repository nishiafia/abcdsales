
/*MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"*/

MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=nishi9004@gmail.com
MAIL_PASSWORD="opmvwpjxcskqhyvg"
MAIL_ENCRYPTION=tls
MAIL_FROM_NAME="${APP_NAME}"

MAIL_DRIVER=mail
MAIL_HOST=mail.abcdsales.com.bd
MAIL_PORT=587
MAIL_USERNAME=noreply@abcdsales.com.bd
MAIL_PASSWORD="no@123120?#"
MAIL_ENCRYPTION=ssl
MAIL_FROM_NAME="${APP_NAME}"



-- MySQL dump 10.13  Distrib 5.7.28, for Linux (x86_64)
--
-- Host: localhost    Database: social
-- ------------------------------------------------------
-- Server version	5.7.28-0ubuntu0.16.04.2

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
-- Table structure for table `canceltasks`
--

DROP TABLE IF EXISTS `canceltasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `canceltasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(6) unsigned DEFAULT NULL,
  `worker_id` int(6) unsigned DEFAULT NULL,
  `canceldate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `worker_id` (`worker_id`),
  KEY `task_id` (`task_id`),
  CONSTRAINT `canceltasks_ibfk_1` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`id`),
  CONSTRAINT `canceltasks_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `canceltasks`
--

LOCK TABLES `canceltasks` WRITE;
/*!40000 ALTER TABLE `canceltasks` DISABLE KEYS */;
INSERT INTO `canceltasks` VALUES (1,17,1,'2019-08-15 10:12:54'),(2,14,2,'2019-08-16 07:58:13'),(3,2,1,'2019-08-17 11:27:33'),(4,1,2,'2019-08-17 11:34:33'),(5,1,2,'2019-08-17 11:50:50'),(6,1,2,'2019-08-17 11:59:02'),(7,2,2,'2019-08-17 12:05:14'),(8,2,1,'2019-08-17 12:06:19'),(9,3,2,'2019-08-17 12:17:33'),(10,4,2,'2019-08-17 12:26:46'),(11,1,2,'2019-08-17 12:32:48'),(12,1,2,'2019-08-17 12:33:49'),(13,2,2,'2019-08-17 12:35:06'),(14,1,2,'2019-08-17 12:38:02'),(15,2,2,'2019-08-17 12:39:32'),(16,3,2,'2019-08-17 12:43:41'),(17,1,2,'2019-08-17 13:16:16'),(18,2,2,'2019-08-17 13:17:58'),(19,3,2,'2019-08-17 13:21:50'),(20,1,2,'2019-08-17 13:31:53'),(21,2,2,'2019-08-17 13:33:42'),(22,4,2,'2019-08-17 14:16:16'),(23,2,2,'2019-08-17 15:17:11');
/*!40000 ALTER TABLE `canceltasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locationtracks`
--

DROP TABLE IF EXISTS `locationtracks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locationtracks` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `worker_id` int(6) unsigned DEFAULT NULL,
  `locdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `worker_id` (`worker_id`),
  CONSTRAINT `locationtracks_ibfk_1` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locationtracks`
--

LOCK TABLES `locationtracks` WRITE;
/*!40000 ALTER TABLE `locationtracks` DISABLE KEYS */;
INSERT INTO `locationtracks` VALUES (2,3.0531,101.692,2,'2019-05-22 12:55:53'),(3,3.06184,101.71,3,'2019-05-22 12:55:53'),(4,3.07512,101.672,4,'2019-05-22 12:55:53'),(5,3.11857,101.642,5,'2019-08-08 07:00:35'),(9,3.05278,101.688,1,'2019-08-13 11:46:14'),(10,3.14679,101.711,6,'2019-08-20 11:17:11');
/*!40000 ALTER TABLE `locationtracks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ratings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rating` varchar(50) DEFAULT NULL,
  `worker_id` int(6) unsigned DEFAULT NULL,
  `requester_id` int(6) unsigned DEFAULT NULL,
  `task_id` int(10) unsigned DEFAULT NULL,
  `ratingdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `requester_id` (`requester_id`),
  KEY `worker_id` (`worker_id`),
  KEY `task_id` (`task_id`),
  CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`requester_id`) REFERENCES `requesters` (`id`),
  CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`id`),
  CONSTRAINT `ratings_ibfk_3` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ratings`
--

LOCK TABLES `ratings` WRITE;
/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;
/*!40000 ALTER TABLE `ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requesters`
--

DROP TABLE IF EXISTS `requesters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `requesters` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `fullname` varchar(200) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `placename` text,
  `address` text,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pushToken` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requesters`
--

LOCK TABLES `requesters` WRITE;
/*!40000 ALTER TABLE `requesters` DISABLE KEYS */;
INSERT INTO `requesters` VALUES (1,'Mr. XYZ','test@gmail.com','+60123467',NULL,'Bukit Jalil, 5700, Kualalumpur',NULL,NULL,'jZae727K08KaOmKSgOaGzww_XVqGr_PKEgIMkjrcbJI=','2019-11-20 14:12:05','ExponentPushToken[9A5laKIKKBLJAYtZ5APpli]'),(2,'Mr. ABC Rahman','abc@gmail.com','+601333445',NULL,'Petaling Jaya\n',NULL,NULL,'jZae727K08KaOmKSgOaGzww_XVqGr_PKEgIMkjrcbJI=','2019-08-08 07:40:44',NULL),(3,NULL,'test1@gmail.com',NULL,NULL,NULL,NULL,NULL,'jZae727K08KaOmKSgOaGzww_XVqGr_PKEgIMkjrcbJI=','2019-08-20 09:02:46',NULL),(4,NULL,'test2@gmail.com',NULL,NULL,NULL,NULL,NULL,'jZae727K08KaOmKSgOaGzww_XVqGr_PKEgIMkjrcbJI=','2019-08-20 09:11:09',NULL);
/*!40000 ALTER TABLE `requesters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `taskplacename` text,
  `taskaddress` text,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `tasktype` varchar(100) DEFAULT NULL,
  `taskdetail` text,
  `amount` varchar(100) DEFAULT NULL,
  `amounttype` varchar(20) DEFAULT NULL,
  `receiptno` varchar(30) DEFAULT NULL,
  `amountstatus` int(2) DEFAULT '0',
  `wrating` varchar(50) DEFAULT '0',
  `taskstatus` varchar(15) DEFAULT NULL,
  `worker_id` int(6) unsigned DEFAULT NULL,
  `requester_id` int(6) unsigned DEFAULT NULL,
  `taskcompletetiontime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `taskdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `acceptstatus` int(2) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `requester_id` (`requester_id`),
  KEY `worker_id` (`worker_id`),
  CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`requester_id`) REFERENCES `requesters` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES (1,'Bank Islam ATM','Bukit Jalil, Kuala Lumpur','3.0530471','101.6917001','Electrician','Test Job','40',NULL,NULL,0,'0','Done',2,1,'2019-08-23 04:21:51','2019-08-18 12:22:16',1),(2,'Institut Sukan Negara','Kompleks Sukan Negara, Jalan Barat, Bukit Jalil, Kuala Lumpur','3.0525985','101.6917062','AC Maker','Need to repair AC','100',NULL,NULL,0,'0','New',0,1,'2019-08-18 12:24:09','2019-08-18 12:24:09',0),(3,'National Sports Council Of Malaysia','Kompleks Sukan Negara, Jalan Barat, Bukit Jalil, Kuala Lumpur','3.0527015','101.6923285','Electrician','Test New Job','200',NULL,NULL,0,'0','New',0,1,'2019-08-18 12:29:27','2019-08-18 12:28:26',0),(4,'MY81 Restaurant Arena','D-G-3 , arena green appartment Jalan 1/155 A','3.052439799999999','101.6877405','Home tutor','I need a teacher for one month ','500',NULL,NULL,0,'0','New',0,1,'2019-08-22 15:35:53','2019-08-22 15:35:53',0),(5,'MY81 Restaurant Arena','D-G-3 , arena green appartment Jalan 1/155 A','3.052439799999999','101.6877405','Electrician','Hello','200',NULL,NULL,0,'0','Done',1,1,'2019-08-22 15:51:03','2019-08-22 15:44:53',1),(6,'Funpage Creations Marketing','Arena Green, No. 1/155A, F-2-10, Bukit Jalil, Seri Petaling, Wilayah Persekutuan, Kuala Lumpur','3.051735','101.6876017','Electrician','Ghhs','180',NULL,NULL,0,'0','Done',1,1,'2019-08-22 15:54:24','2019-08-22 15:51:34',1),(7,'MY81 Restaurant Arena','D-G-3 , arena green appartment Jalan 1/155 A','3.052439799999999','101.6877405','Electrician','Ghs','231',NULL,NULL,0,'0','Done',1,1,'2019-08-23 08:17:26','2019-08-22 15:54:50',1),(8,'MY81 Restaurant Arena','D-G-3 , arena green appartment Jalan 1/155 A','3.052439799999999','101.6877405','Electrician','Test','123',NULL,NULL,0,'0','New',0,1,'2019-08-23 04:21:46','2019-08-23 04:21:16',0),(9,'Funpage Creations Marketing','Arena Green, No. 1/155A, F-2-10, Bukit Jalil, Seri Petaling, Wilayah Persekutuan, Kuala Lumpur','3.051735','101.6876017','Electrician','Gg','12',NULL,NULL,0,'0','Done',2,1,'2019-08-23 04:24:14','2019-08-23 04:22:36',1),(10,'Mugen Design','No. 33, Jalan 2/155a, Bukit Jalil Golf & Country Resort, Wilayah Persekutuan, Kuala Lumpur','3.0515646','101.6873288','Electrician','I need to repair a fridge.','60',NULL,NULL,0,'0','New',0,1,'2019-08-23 08:45:33','2019-08-23 08:44:02',0),(11,'The Doctors Clinic','D-0-7, Arena Green Apartment, Jalan 1/155a, Jalan Barat, Bukit Jalil, Kuala Lumpur','3.0525958','101.6879927','Electrician','I need to repair fan','60',NULL,NULL,0,'0','Processing',1,1,'2019-08-23 09:39:51','2019-08-23 08:45:57',1),(12,'We Stay - Your Ideal Home Stay Stadium Bukit Jalil / Axiata Arena','A-8-18, Arena Green Apartment, Jalan 1/155A, Bukit Jalil, Kuala Lumpur','3.0544803','101.6878075','Plumber','wewe90','90','',NULL,0,'0','New',0,1,'2019-11-21 09:11:35','2019-11-21 09:11:03',0);
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temporarytasks`
--

DROP TABLE IF EXISTS `temporarytasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temporarytasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `taskplacename` text,
  `taskaddress` text,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `tasktype` varchar(100) DEFAULT NULL,
  `taskdetail` text,
  `amount` varchar(100) DEFAULT NULL,
  `taskstatus` varchar(15) DEFAULT NULL,
  `acceptstatus` int(2) unsigned DEFAULT NULL,
  `worker_id` int(6) unsigned DEFAULT NULL,
  `requester_id` int(6) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `requester_id` (`requester_id`),
  KEY `worker_id` (`worker_id`),
  CONSTRAINT `temporarytasks_ibfk_1` FOREIGN KEY (`requester_id`) REFERENCES `requesters` (`id`),
  CONSTRAINT `temporarytasks_ibfk_2` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temporarytasks`
--

LOCK TABLES `temporarytasks` WRITE;
/*!40000 ALTER TABLE `temporarytasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `temporarytasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workers`
--

DROP TABLE IF EXISTS `workers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workers` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `fullname` varchar(200) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `speciality` varchar(100) DEFAULT NULL,
  `address` text,
  `password` varchar(100) DEFAULT NULL,
  `profilepic` varchar(200) DEFAULT NULL,
  `avgrating` varchar(100) DEFAULT '1.0',
  `regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pushToken` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workers`
--

LOCK TABLES `workers` WRITE;
/*!40000 ALTER TABLE `workers` DISABLE KEYS */;
INSERT INTO `workers` VALUES (1,'Worker11111','worker1@gmail.com','+6012345','Electrician','Arena Green,, Bukit Jalil, Kuala Lumpur','jZae727K08KaOmKSgOaGzww_XVqGr_PKEgIMkjrcbJI=',NULL,'1.0','2019-08-28 09:00:49','ExponentPushToken[kO4LVeLa_NEb5D8OijHSkY]'),(2,'Worker2222','worker2@gmail.com','+60123456','Electrician','Sripetaling','jZae727K08KaOmKSgOaGzww_XVqGr_PKEgIMkjrcbJI=',NULL,'5','2019-09-18 10:00:50','ExponentPushToken[N3mEPQA4soL-mHG5afK3I0]'),(3,'Worker3','worker3@gmail.com','+6012345678','Plumber','Bukit Jalil Statium','jZae727K08KaOmKSgOaGzww_XVqGr_PKEgIMkjrcbJI=',NULL,'1.0','2019-08-17 08:43:14','ExponentPushToken[fIVeCvKxpBbnl2miWxwoHe]'),(4,'Worker4','worker4@gmail.com','+601234567','Electrician','Taman OUG','WZRHGrsBESr8wYFZ9sx0tPURuZgG2lmzyvWpwXPKz8U=',NULL,'1.0','2019-08-17 08:40:22',''),(5,'Mr. Noman','noman@gmail.com','+6012345677','Plumber','UM\n','jZae727K08KaOmKSgOaGzww_XVqGr_PKEgIMkjrcbJI=',NULL,'1.0','2019-08-17 08:40:22',''),(6,'Worker10','worker10@gmail.com','+60123456','AC Maker','Bukit Bintang','jZae727K08KaOmKSgOaGzww_XVqGr_PKEgIMkjrcbJI=',NULL,'1.0','2019-08-20 11:17:11','ExponentPushToken[i8o37eDIba6ONgMQC_R0U2]');
/*!40000 ALTER TABLE `workers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workerspeciality`
--

DROP TABLE IF EXISTS `workerspeciality`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workerspeciality` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `speciality` varchar(100) DEFAULT NULL,
  `worker_id` int(6) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `worker_id` (`worker_id`),
  CONSTRAINT `workerspeciality_ibfk_1` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workerspeciality`
--

CREATE TABLE `usertypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(6) unsigned DEFAULT NULL,
  `worker_id` int(6) unsigned DEFAULT NULL,
  `canceldate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `worker_id` (`worker_id`),
  KEY `task_id` (`task_id`),
  CONSTRAINT `canceltasks_ibfk_1` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`id`),
  CONSTRAINT `canceltasks_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

LOCK TABLES `workerspeciality` WRITE;
/*!40000 ALTER TABLE `workerspeciality` DISABLE KEYS */;
INSERT INTO `workerspeciality` VALUES (1,'Plumber',2),(2,'Electrician',2),(3,'Electrician',1),(4,'AC Maker',1);
/*!40000 ALTER TABLE `workerspeciality` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-11-23 23:36:55



//// -- LEVEL 1
//// -- Tables and References

// Creating tables
Table users as U {
  id int [pk, increment] // auto-increment
  full_name varchar
  created_at timestamp
  country_code int
}

Table countries {
  code int [pk]
  name varchar
  continent_name varchar
 }

// Creating references
// You can also define relaionship separately
// > many-to-one; < one-to-many; - one-to-one
Ref: U.country_code > countries.code  
Ref: merchants.country_code > countries.code

//----------------------------------------------//

//// -- LEVEL 2
//// -- Adding column settings

Table order_items {
  order_id int [ref: > orders.id] // inline relationship (many-to-one)
  product_id int
  quantity int [default: 1] // default value
}

Ref: order_items.product_id > products.id

Table orders {
  id int [pk] // primary key
  user_id int [not null, unique]
  status varchar
  created_at varchar [note: 'When order created'] // add column note
}

//----------------------------------------------//

//// -- Level 3 
//// -- Enum, Indexes

// Enum for 'products' table below
Enum products_status {
  out_of_stock
  in_stock
  running_low [note: 'less than 20'] // add column note
}

// Indexes: You can define a single or multi-column index 
Table products {
  id int [pk]
  name varchar
  merchant_id int [not null]
  price int
  status products_status
  created_at datetime [default: `now()`]
  
  Indexes {
    (merchant_id, status) [name:'product_status']
    id [unique]
  }
}

Table merchants {
  id int
  country_code int
  merchant_name varchar
  
  "created at" varchar
  admin_id int [ref: > U.id]
  Indexes {
    (id, country_code) [pk]
  }
}

Table merchant_periods {
  id int [pk]
  merchant_id int
  country_code int
  start_date datetime
  end_date datetime
}

Ref: products.merchant_id > merchants.id // many-to-one
//composite foreign key
Ref: merchant_periods.(merchant_id, country_code) > merchants.(id, country_code)
