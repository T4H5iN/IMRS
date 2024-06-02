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
-- Table structure for table `favourite`
--

DROP TABLE IF EXISTS `favourite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favourite` (
  `titleid` varchar(45) NOT NULL,
  `username` varchar(45) NOT NULL,
  PRIMARY KEY (`titleid`,`username`),
  KEY `fk_Favourite_2_idx` (`username`),
  CONSTRAINT `fk_Favourite_1` FOREIGN KEY (`titleid`) REFERENCES `titles` (`id`),
  CONSTRAINT `fk_Favourite_2` FOREIGN KEY (`username`) REFERENCES `userinfo` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favourite`
--

LOCK TABLES `favourite` WRITE;
/*!40000 ALTER TABLE `favourite` DISABLE KEYS */;
INSERT INTO `favourite` VALUES ('tt0102926','Mdti'),('tt0108052','Mdti'),('tt0110912','Mdti'),('tt0114369','Mdti'),('tt0167260','Mdti'),('tt0245429','Mdti'),('tt0347149','Mdti'),('tt0372784','Mdti'),('tt0417299','Mdti'),('tt0468569','Mdti'),('tt0903747','Mdti'),('tt0944947','Mdti'),('tt1375666','Mdti'),('tt2096673','Mdti'),('tt2948372','Mdti'),('tt4633694','Mdti'),('tt6587046','Mdti'),('tt7286456','Mdti'),('tt0245429','Priskwy'),('tt0468569','Priskwy'),('tt0944947','Priskwy'),('tt11389872','Priskwy'),('tt1375666','Priskwy'),('tt1431045','Priskwy'),('tt2096673','Priskwy'),('tt2380307','Priskwy'),('tt4154756','Priskwy'),('tt4154796','Priskwy'),('tt4633694','Priskwy'),('tt0012349','zephyr'),('tt0017136','zephyr'),('tt0022100','zephyr'),('tt0027977','zephyr');
/*!40000 ALTER TABLE `favourite` ENABLE KEYS */;
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
