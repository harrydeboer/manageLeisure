-- MySQL dump 10.13  Distrib 8.0.25, for Win64 (x86_64)
--
-- Host: localhost    Database: my_life
-- ------------------------------------------------------
-- Server version	8.0.25

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
-- Dumping data for table `wine`
--

LOCK TABLES `wine` WRITE;
/*!40000 ALTER TABLE `wine` DISABLE KEYS */;
INSERT INTO `wine` VALUES (1,1,1,'Viña Mayor Crianza','png',2018,60,899,'wrangig, boerenjongens',1635852086),(2,1,3,'M. Chapoutier Côtes du Rhône','png',2018,75,1098,NULL,1635852086),(3,1,3,'Ondarre Rioja Reserva','png',2016,65,799,NULL,1635852086),(4,1,2,'Valdivieso Gran Reserva carmenère','png',2019,77,998,NULL,1635852086),(5,1,5,'Mommessin Domaine De La Presle Fleurie','png',2019,90,1329,NULL,1635852086),(6,1,5,'Masi Frescaripa Bardolino','png',2019,68,929,NULL,1635852086),(7,1,5,'Zonin Valpolicella Classico','png',2020,70,799,NULL,1635852086),(8,1,4,'Catena Altamira Malbec','png',2018,90,1065,NULL,1635852086),(9,1,2,'Catena Appelation Lunlunta Malbec','png',2019,78,1065,NULL,1635856835),(10,1,5,'Hieron Montepulciano D\'Abruzzo','png',2019,77,449,NULL,1636289235),(11,1,1,'Cadiot','jpg',2018,90,899,NULL,1636974777);
/*!40000 ALTER TABLE `wine` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-11-22 15:22:42
