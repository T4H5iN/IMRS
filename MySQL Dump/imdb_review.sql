-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: localhost    Database: imdb
-- ------------------------------------------------------
-- Server version	8.0.34

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
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `review` (
  `titleID` varchar(15) NOT NULL,
  `username` varchar(45) NOT NULL,
  `comment` longtext NOT NULL,
  `publishDate` datetime DEFAULT NULL,
  PRIMARY KEY (`titleID`,`username`),
  KEY `fk_Review_2_idx` (`username`),
  CONSTRAINT `fk_Review_1` FOREIGN KEY (`titleID`) REFERENCES `titles` (`id`),
  CONSTRAINT `fk_Review_2` FOREIGN KEY (`username`) REFERENCES `userinfo` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review`
--

LOCK TABLES `review` WRITE;
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
INSERT INTO `review` VALUES ('tt0114369','Mdti','Absolute Masterpiece. The thrill is out of the world.','2024-06-01 12:41:33'),('tt0468569','Priskwy','I am vengeance','2024-05-22 01:08:53'),('tt0903747','Mdti','Bromine & Barium !BOOM','2024-05-30 18:08:20'),('tt11152168','mdti','IF','2024-05-30 18:08:20'),('tt11389872','mdti','monke','2024-06-01 20:25:53'),('tt12037194','mdti','sweet revenge\r\n','2024-05-30 18:08:20'),('tt1684562','mdti','Emily Blunt yay','2024-06-01 20:26:11'),('tt4633694','mdti','Full of emotion','2024-05-30 18:08:20'),('tt5177120','mdti','ikr','2024-06-01 20:25:44'),('tt5177120','Priskwy','Henry Cavil + Guy Richi  FIRE! FIRE! FIRE!','2024-05-22 21:48:21');
/*!40000 ALTER TABLE `review` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-02  1:37:34
