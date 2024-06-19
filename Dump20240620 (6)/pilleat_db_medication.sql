-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: localhost    Database: pilleat_db
-- ------------------------------------------------------
-- Server version	8.0.35

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
-- Table structure for table `medication`
--

DROP TABLE IF EXISTS `medication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medication` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `dosage` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `medication_ibfk_1` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medication`
--

LOCK TABLES `medication` WRITE;
/*!40000 ALTER TABLE `medication` DISABLE KEYS */;
INSERT INTO `medication` VALUES (9,'경동아스피린장용정','100mg','0'),(10,'다이크로질정','25mg','0'),(11,'소론도정','5mg','0'),(12,'경동아스피린장용정','100mg','0'),(13,'다이크로질정','25mg','0'),(14,'소론도정','5mg','0'),(15,'경동아스피린장용정','100mg','0'),(16,'다이크로질정','25mg','0'),(17,'소론도정','5mg','0'),(18,'경동아스피린장용정','100mg','0'),(19,'다이크로질정','25mg','0'),(20,'소론도정','5mg','0'),(21,'경동아스피린장용정','100mg','0'),(22,'소론도정','5mg','0'),(23,'경동아스피린장용정','100mg','0'),(24,'리피토정10mg','10mg','0'),(25,'소론도정','5mg','0'),(28,'소론도정','5mg','jiyong706@naver.com'),(31,'소론도정','5mg','ibb61@naver.com'),(41,'경동아스피린장용정','100mg','20192161@naver.com'),(42,'리피토정10mg','10mg','20192161@naver.com'),(43,'다이크로질정','25mg','20192161@naver.com'),(44,'소론도정','5mg','20192161@naver.com'),(45,'경동아스피린장용정','100mg','20192161@naver.com'),(46,'리피토정10mg','10mg','20192161@naver.com'),(47,'소론도정','5mg','20192161@naver.com'),(48,'알마겔정','500mg','20192161@naver.com');
/*!40000 ALTER TABLE `medication` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-20  2:25:47
