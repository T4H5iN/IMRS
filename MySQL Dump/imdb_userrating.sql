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
-- Table structure for table `userrating`
--

DROP TABLE IF EXISTS `userrating`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `userrating` (
  `titleid` varchar(45) NOT NULL,
  `username` varchar(45) NOT NULL,
  `rating` int NOT NULL,
  PRIMARY KEY (`username`,`titleid`),
  KEY `fk_userRating_1_idx` (`titleid`),
  CONSTRAINT `fk_userRating_1` FOREIGN KEY (`titleid`) REFERENCES `titles` (`id`),
  CONSTRAINT `fk_userRating_2` FOREIGN KEY (`username`) REFERENCES `userinfo` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userrating`
--

LOCK TABLES `userrating` WRITE;
/*!40000 ALTER TABLE `userrating` DISABLE KEYS */;
INSERT INTO `userrating` VALUES ('tt0068646','Mdti',9),('tt0111161','Mdti',10),('tt0114709','Mdti',8),('tt0172495','Mdti',9),('tt0266543','Mdti',8),('tt0317705','Mdti',8),('tt0903747','Mdti',9),('tt0910970','Mdti',8),('tt10234724','Mdti',8),('tt10366460','Mdti',8),('tt1049413','Mdti',8),('tt11389872','Mdti',6),('tt16026746','Mdti',9),('tt1684562','Mdti',7),('tt2788316','Mdti',9),('tt4633694','Mdti',9),('tt5177120','Mdti',7),('tt0111161','Priskwy',10),('tt11389872','Priskwy',6),('tt12037194','Priskwy',8),('tt16026746','Priskwy',9),('tt1684562','Priskwy',7),('tt2788316','Priskwy',9),('tt4633694','Priskwy',9),('tt5177120','Priskwy',7),('tt8063174','Priskwy',1);
/*!40000 ALTER TABLE `userrating` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-02  1:37:32
