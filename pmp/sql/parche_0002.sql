-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: localhost    Database: sierras
-- ------------------------------------------------------
-- Server version	5.6.17

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
-- Table structure for table `adelantos`
--


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adelantos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `idTercero` int(11) DEFAULT NULL,
  `importe` float(12,2) DEFAULT NULL,
  `usado` int(11) DEFAULT NULL,
  `tipoAdelanto` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adelantos`
--

LOCK TABLES `adelantos` WRITE;
/*!40000 ALTER TABLE `adelantos` DISABLE KEYS */;
INSERT INTO `adelantos` VALUES (1,'2017-07-16',3,100.00,1,1),(2,'2017-07-16',3,200.00,1,1),(3,'2017-07-16',3,100.00,1,1),(4,'2017-07-16',3,200.00,1,1),(5,'2017-07-24',2,123123.00,NULL,1),(6,'2017-07-25',3,50.00,1,1),(7,'2017-07-25',3,100.00,1,1),(8,'2017-07-25',3,300.00,1,1),(9,'2017-07-25',3,600.00,NULL,1),(10,'2017-07-25',3,987.43,NULL,1);
/*!40000 ALTER TABLE `adelantos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adelantos-recibos-pagos`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adelantos-recibos-pagos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idRecibo-Pago` int(11) DEFAULT NULL,
  `idAdelanto` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adelantos-recibos-pagos`
--

LOCK TABLES `adelantos-recibos-pagos` WRITE;
/*!40000 ALTER TABLE `adelantos-recibos-pagos` DISABLE KEYS */;
INSERT INTO `adelantos-recibos-pagos` VALUES (2,6,1),(3,6,2),(4,6,3),(5,6,4),(6,7,1),(7,7,2),(8,7,3),(9,7,4),(11,8,6),(12,9,7),(16,10,8);
/*!40000 ALTER TABLE `adelantos-recibos-pagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cheques`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cheques` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nro` varchar(50) DEFAULT NULL,
  `banco` varchar(50) DEFAULT NULL,
  `importe` double(12,2) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cheques`
--

LOCK TABLES `cheques` WRITE;
/*!40000 ALTER TABLE `cheques` DISABLE KEYS */;
INSERT INTO `cheques` VALUES (1,'1','a',100.00,'2017-07-16',1),(2,'123','asd',100.00,'2017-01-31',1),(3,'321','ced',100.00,'2017-01-01',1);
/*!40000 ALTER TABLE `cheques` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cheques-detalle`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cheques-detalle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idCheque` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `fecha` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cheques-detalle`
--

LOCK TABLES `cheques-detalle` WRITE;
/*!40000 ALTER TABLE `cheques-detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `cheques-detalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medios-pagos`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medios-pagos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `denominacion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medios-pagos`
--

LOCK TABLES `medios-pagos` WRITE;
/*!40000 ALTER TABLE `medios-pagos` DISABLE KEYS */;
INSERT INTO `medios-pagos` VALUES (1,'cheque'),(2,'efectivo');
/*!40000 ALTER TABLE `medios-pagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recibos-pagos`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recibos-pagos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipoFlujo` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `idTercero` int(11) DEFAULT NULL,
  `importe` double(12,2) DEFAULT NULL,
  `importeMovimientos` double(12,2) DEFAULT NULL,
  `importeAdelantos` double(12,2) DEFAULT NULL,
  `valorDolar` double(12,2) DEFAULT NULL,
  `nroComprobante` varchar(20) DEFAULT NULL,
  `contable` int(11) DEFAULT NULL,
  `idEstado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recibos-pagos`
--

LOCK TABLES `recibos-pagos` WRITE;
/*!40000 ALTER TABLE `recibos-pagos` DISABLE KEYS */;
INSERT INTO `recibos-pagos` VALUES (1,1,'2017-07-16',3,100.00,0.00,NULL,1.00,'1',1,2),(2,1,'2017-07-16',3,200.00,0.00,NULL,1.00,'2',1,2),(3,1,'2017-07-16',3,100.00,0.00,NULL,1.00,'3',1,2),(4,1,'2017-07-16',3,200.00,0.00,NULL,1.00,'4',1,2),(5,1,'2017-07-24',2,123123.00,0.00,NULL,1.00,'234',1,2),(6,1,'2017-07-24',3,723.00,0.00,NULL,10.00,'10',1,1),(7,1,'2017-07-25',3,123.00,73.00,NULL,1.00,'6549',1,2),(8,1,'2017-07-25',3,100.00,0.00,50.00,1.00,'12312321',1,2),(9,1,'2017-07-25',3,200.00,0.00,100.00,1.00,'1211111111',1,2),(10,1,'2017-07-25',3,300.00,0.00,300.00,1.00,'234444',1,2),(11,1,'2017-07-25',3,1099.00,111.57,0.00,10.00,'2344445',1,2);
/*!40000 ALTER TABLE `recibos-pagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recibos-pagos-detalle`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recibos-pagos-detalle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idRecibo-Pago` int(11) DEFAULT NULL,
  `idMedioPago` int(11) DEFAULT NULL,
  `importe` double(12,2) DEFAULT NULL,
  `codcheque` varchar(11) DEFAULT NULL,
  `banco` varchar(50) DEFAULT NULL,
  `numero` varchar(50) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `observaciones` text,
  `contable` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recibos-pagos-detalle`
--

LOCK TABLES `recibos-pagos-detalle` WRITE;
/*!40000 ALTER TABLE `recibos-pagos-detalle` DISABLE KEYS */;
INSERT INTO `recibos-pagos-detalle` VALUES (1,1,1,100.00,'1','a','1','2017-07-16','',NULL),(3,2,2,200.00,'','','','1970-01-01','',NULL),(4,3,2,100.00,'','','','0000-00-00','',NULL),(5,4,2,200.00,'','','','0000-00-00','',NULL),(6,5,2,123123.00,'','','','0000-00-00','',NULL),(7,6,2,123.00,'','','','0000-00-00','',NULL),(8,7,2,123.00,'','','','0000-00-00','',NULL),(10,8,2,100.00,'','','','1970-01-01','',NULL),(11,9,2,200.00,'','','','0000-00-00','',NULL),(18,10,1,100.00,'2','asd','123','2017-01-31','',NULL),(19,10,2,200.00,'','','','1970-01-01','',NULL),(20,11,1,100.00,'3','ced','321','2017-01-01','',NULL),(21,11,2,999.00,'','','','0000-00-00','',NULL);
/*!40000 ALTER TABLE `recibos-pagos-detalle` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-10-03 14:51:40

CREATE TABLE `movimientos-recibos-pagos` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idRecibo-Pago` INT(11) NULL DEFAULT NULL,
  `idMovimientos` INT(11) NULL DEFAULT NULL,
  `importeCancelado` DOUBLE(12,2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
)

