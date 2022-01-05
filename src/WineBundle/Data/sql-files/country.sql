-- MySQL dump 10.13  Distrib 8.0.25, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: manage_leisure
-- ------------------------------------------------------
-- Server version	8.0.27

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `country`
--

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` VALUES (2,'Afghanistan'),(3,'Åland Islands'),(4,'Albania'),(5,'Algeria'),(6,'American Samoa'),(7,'Andorra'),(8,'Angola'),(9,'Anguilla'),(10,'Antarctica'),(11,'Antigua and Barbuda'),(12,'Argentina'),(13,'Armenia'),(14,'Aruba'),(15,'Australia'),(16,'Austria'),(17,'Azerbaijan'),(18,'Bahamas'),(19,'Bahrain'),(20,'Bangladesh'),(21,'Barbados'),(22,'Belarus'),(23,'Belgium'),(24,'Belize'),(25,'Benin'),(26,'Bermuda'),(27,'Bhutan'),(28,'Bolivia, Plurinational State Of'),(247,'Bonaire, Sint Eustatius and Saba'),(29,'Bosnia and Herzegovina'),(30,'Botswana'),(31,'Bouvet Island'),(32,'Brazil'),(33,'British Indian Ocean Territory'),(34,'Brunei Darussalam'),(35,'Bulgaria'),(36,'Burkina Faso'),(37,'Burundi'),(38,'Cambodia'),(39,'Cameroon'),(40,'Canada'),(41,'Cape Verde'),(42,'Cayman Islands'),(43,'Central African Republic'),(44,'Chad'),(45,'Chile'),(46,'China'),(47,'Christmas Island'),(48,'Cocos (Keeling) Islands'),(49,'Colombia'),(50,'Comoros'),(51,'Congo'),(52,'Congo, The Democratic Republic Of The'),(53,'Cook Islands'),(54,'Costa Rica'),(55,'Côte D\'Ivoire'),(56,'Croatia'),(57,'Cuba'),(248,'Curaçao'),(58,'Cyprus'),(59,'Czech Republic'),(60,'Denmark'),(61,'Djibouti'),(62,'Dominica'),(63,'Dominican Republic'),(64,'Ecuador'),(65,'Egypt'),(66,'El Salvador'),(67,'Equatorial Guinea'),(68,'Eritrea'),(69,'Estonia'),(70,'Ethiopia'),(71,'Falkland Islands  (Malvinas)'),(72,'Faroe Islands'),(73,'Fiji'),(74,'Finland'),(75,'France'),(76,'French Guiana'),(77,'French Polynesia'),(78,'French Southern Territories'),(79,'Gabon'),(80,'Gambia'),(1,'Georgia'),(81,'Germany'),(82,'Ghana'),(83,'Gibraltar'),(84,'Greece'),(85,'Greenland'),(86,'Grenada'),(87,'Guadeloupe'),(88,'Guam'),(89,'Guatemala'),(90,'Guernsey'),(91,'Guinea'),(92,'Guinea-Bissau'),(93,'Guyana'),(94,'Haiti'),(95,'Heard and McDonald Islands'),(96,'Holy See (Vatican City State)'),(97,'Honduras'),(98,'Hong Kong'),(99,'Hungary'),(100,'Iceland'),(101,'India'),(102,'Indonesia'),(103,'Iran, Islamic Republic Of'),(104,'Iraq'),(105,'Ireland'),(106,'Isle of Man'),(107,'Israel'),(108,'Italy'),(109,'Jamaica'),(110,'Japan'),(111,'Jersey'),(112,'Jordan'),(113,'Kazakhstan'),(114,'Kenya'),(115,'Kiribati'),(117,'Korea, Democratic People\'s Republic Of'),(116,'Korea, Republic of'),(118,'Kuwait'),(119,'Kyrgyzstan'),(120,'Lao People\'s Democratic Republic'),(121,'Latvia'),(122,'Lebanon'),(123,'Lesotho'),(124,'Liberia'),(125,'Libyan Arab Jamahiriya'),(126,'Liechtenstein'),(127,'Lithuania'),(128,'Luxembourg'),(129,'Macao'),(130,'Macedonia, the Former Yugoslav Republic Of'),(131,'Madagascar'),(132,'Malawi'),(133,'Malaysia'),(134,'Maldives'),(135,'Mali'),(136,'Malta'),(137,'Marshall Islands'),(138,'Martinique'),(139,'Mauritania'),(140,'Mauritius'),(141,'Mayotte'),(142,'Mexico'),(143,'Micronesia, Federated States Of'),(144,'Moldova, Republic of'),(145,'Monaco'),(146,'Mongolia'),(147,'Montenegro'),(148,'Montserrat'),(149,'Morocco'),(150,'Mozambique'),(151,'Myanmar'),(152,'Namibia'),(153,'Nauru'),(154,'Nepal'),(155,'Netherlands'),(156,'Netherlands Antilles'),(157,'New Caledonia'),(158,'New Zealand'),(159,'Nicaragua'),(160,'Niger'),(161,'Nigeria'),(162,'Niue'),(163,'Norfolk Island'),(164,'Northern Mariana Islands'),(165,'Norway'),(166,'Oman'),(167,'Pakistan'),(168,'Palau'),(169,'Palestinian Territory, Occupied'),(170,'Panama'),(171,'Papua New Guinea'),(172,'Paraguay'),(173,'Peru'),(174,'Philippines'),(176,'Pitcairn'),(177,'Poland'),(178,'Portugal'),(179,'Puerto Rico'),(180,'Qatar'),(181,'Réunion'),(182,'Romania'),(183,'Russian Federation'),(184,'Rwanda'),(245,'Saint Barthélemy'),(185,'Saint Helena, Ascension and Tristan Da Cunha'),(186,'Saint Kitts And Nevis'),(187,'Saint Lucia'),(246,'Saint Martin'),(175,'Saint Pierre And Miquelon'),(188,'Saint Vincent And The Grenedines'),(189,'Samoa'),(190,'San Marino'),(191,'Sao Tome and Principe'),(192,'Saudi Arabia'),(193,'Senegal'),(194,'Serbia'),(195,'Seychelles'),(196,'Sierra Leone'),(197,'Singapore'),(249,'Sint Maarten (Dutch part)'),(198,'Slovakia'),(199,'Slovenia'),(200,'Solomon Islands'),(201,'Somalia'),(202,'South Africa'),(203,'South Georgia and the South Sandwich Islands'),(250,'South Sudan'),(204,'Spain'),(205,'Sri Lanka'),(206,'Sudan'),(207,'Suriname'),(208,'Svalbard And Jan Mayen'),(209,'Swaziland'),(210,'Sweden'),(211,'Switzerland'),(212,'Syrian Arab Republic'),(213,'Taiwan, Province Of China'),(214,'Tajikistan'),(215,'Tanzania, United Republic of'),(216,'Thailand'),(217,'Timor-Leste'),(218,'Togo'),(219,'Tokelau'),(220,'Tonga'),(221,'Trinidad and Tobago'),(222,'Tunisia'),(223,'Turkey'),(224,'Turkmenistan'),(225,'Turks and Caicos Islands'),(226,'Tuvalu'),(227,'Uganda'),(228,'Ukraine'),(229,'United Arab Emirates'),(230,'United Kingdom'),(231,'United States'),(232,'United States Minor Outlying Islands'),(233,'Uruguay'),(234,'Uzbekistan'),(235,'Vanuatu'),(236,'Venezuela, Bolivarian Republic of'),(237,'Viet Nam'),(238,'Virgin Islands, British'),(239,'Virgin Islands, U.S.'),(240,'Wallis and Futuna'),(241,'Western Sahara'),(242,'Yemen'),(243,'Zambia'),(244,'Zimbabwe');
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-01-05 15:47:36
