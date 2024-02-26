CREATE TABLE `comprobantes` (
  `id` int(2) NOT NULL DEFAULT '0',
  `denominacion` varchar(57) DEFAULT NULL,
  `discriminaIVA` int(11) DEFAULT NULL,
  `seAutoriza` int(11) DEFAULT NULL,
  `letra` varchar(1) DEFAULT NULL,
  `seanula` int(11) DEFAULT NULL,
  `contracomprobante` int(11) DEFAULT NULL,
  `comportamiento` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


LOCK TABLES `comprobantes` WRITE;
INSERT INTO `comprobantes` VALUES (1,'Factura A',1,1,'A',1,3,1),(2,'Nota de Débito A',1,1,'A',NULL,NULL,1),(3,'Nota de Crédito A',1,1,'A',NULL,NULL,2),(4,'Recibos A',1,1,'A',NULL,NULL,NULL),(5,'Notas de Venta al contado A',1,1,'A',NULL,NULL,NULL),(6,'Factura B',NULL,1,'B',1,8,1),(7,'Nota de Débito B',NULL,1,'B',NULL,NULL,1),(8,'Nota de Crédito B',NULL,1,'B',NULL,NULL,2),(9,'Recibos B',NULL,1,'B',NULL,NULL,NULL),(10,'Notas de Venta al contado B',NULL,1,'B',NULL,NULL,NULL),(11,'Factura C',NULL,1,'C',1,13,1),(12,'Nota de Débito C',NULL,1,'C',NULL,NULL,1),(13,'Nota de Crédito C',NULL,1,'C',NULL,NULL,2),(15,'Recibo C',NULL,1,'C',NULL,NULL,NULL),(34,'Cbtes. A del Anexo I, Apartado A,inc.f),R.G.Nro. 1415',1,NULL,'A',NULL,NULL,NULL),(35,'Cbtes. B del Anexo I,Apartado A,inc. f),R.G. Nro. 1415',1,NULL,'A',NULL,NULL,NULL),(39,'Otros comprobantes A que cumplan con R.G.Nro. 1415',1,NULL,'A',NULL,NULL,NULL),(40,'Otros comprobantes B que cumplan con R.G.Nro. 1415',1,NULL,'B',NULL,NULL,NULL),(49,'Comprobante de Compra de Bienes Usados a Consumidor Final',1,NULL,NULL,NULL,NULL,NULL),(60,'Cta de Vta y Liquido prod. A',1,NULL,'A',NULL,NULL,NULL),(61,'Cta de Vta y Liquido prod. B',1,NULL,'B',NULL,NULL,NULL),(63,'Liquidacion A',1,1,'A',NULL,NULL,NULL),(64,'Liquidacion B',1,1,'B',NULL,NULL,NULL),(65,'Factura E',1,NULL,'E',NULL,NULL,NULL),(99,'Presupuesto',NULL,NULL,'X',1,6,NULL);
UNLOCK TABLES;


CREATE TABLE `movimientos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `idSociedad` int(11) DEFAULT NULL,
  `idTercero` int(11) DEFAULT NULL,
  `idComprobante` int(11) DEFAULT NULL,
  `importeTotal` double(12,2) DEFAULT NULL,
  `importeIva` double(12,2) DEFAULT NULL,
  `importeNeto` double(12,2) DEFAULT NULL,
  `nombreTercero` varchar(50) DEFAULT NULL,
  `idDocTercero` int(11) DEFAULT NULL,
  `nroDocTercero` varchar(30) DEFAULT NULL,
  `ptoVenta` int(11) DEFAULT NULL,
  `nroComprobante` int(11) DEFAULT NULL,
  `cae` varchar(30) DEFAULT NULL,
  `vtoCae` date DEFAULT NULL,
  `idEstado` int(11) DEFAULT NULL,
  `idUsuarioAlta` int(11) DEFAULT NULL,
  `fechaAlta` date DEFAULT NULL,
  `idUsuarioModificacion` int(11) DEFAULT NULL,
  `fechaModificacion` date DEFAULT NULL,
  `contable` int(11) DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `tipoMovimiento` int(11) DEFAULT NULL,
  `valorDolar` float(12,2) DEFAULT NULL,
  `importeCancelado` double(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

LOCK TABLES `movimientos` WRITE;
INSERT INTO `movimientos` VALUES (1,'2017-07-16',1,3,1,17.12,3.59,20.71,'ACINAR',80,'30687927768',0,0,'','0000-00-00',2,1,'2017-07-16',1,'2017-07-16',1,'',1,1.00,20.71),(2,'2017-07-16',1,3,1,43.22,9.08,52.29,'ACINAR',80,'30687927768',0,0,'','0000-00-00',2,1,'2017-07-16',1,'2017-07-16',1,'',1,1.00,52.29),(3,'2017-07-23',1,3,1,7.83,0.00,7.83,'ACINAR',80,'30687927768',0,0,'','0000-00-00',2,1,'2017-07-23',1,'2017-07-23',1,'',1,1.00,7.83),(4,'2017-07-23',1,3,1,7.83,0.00,7.83,'ACINAR',80,'30687927768',0,0,'','0000-00-00',2,1,'2017-07-23',1,'2017-07-23',1,'',1,1.00,7.83),(5,'2017-07-23',1,3,1,1.00,0.21,1.21,'ACINAR',80,'30687927768',0,0,'','0000-00-00',2,1,'2017-07-23',1,'2017-07-23',1,'',1,1.00,1.21),(6,'2017-07-23',1,1,1,1.00,0.21,1.21,'Leandro',96,'32832032',0,0,'','0000-00-00',2,1,'2017-07-23',1,'2017-07-23',1,'',2,1.00,0.00),(7,'2017-07-24',1,3,1,78.30,16.40,94.70,'ACINAR',80,'30687927768',0,0,'','0000-00-00',2,1,'2017-07-24',1,'2017-07-24',1,'',1,10.00,94.70),(32,'2017-08-22',1,3,1,7.83,1.64,9.47,'ACINAR',80,'30687927768',0,0,'','0000-00-00',1,1,'2017-08-22',1,'2017-08-22',1,'',1,1.00,0.00);
UNLOCK TABLES;

CREATE TABLE `movimientos-detalle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idMovimientos` int(11) DEFAULT NULL,
  `cant` double(12,2) DEFAULT NULL,
  `idUnidadMedida` int(11) DEFAULT NULL,
  `codProducto` int(11) DEFAULT NULL,
  `medida` double(5,2) DEFAULT NULL,
  `nombreProducto` varchar(50) DEFAULT NULL,
  `importeUnitario` double(12,3) DEFAULT NULL,
  `bonificacion` double(12,2) DEFAULT NULL,
  `importeTotal` double(12,2) DEFAULT NULL,
  `alicuotaIva` double(12,2) DEFAULT NULL,
  `importeIva` double(12,2) DEFAULT NULL,
  `importeNeto` double(12,2) DEFAULT NULL,
  `importePesos` double(12,2) DEFAULT NULL,
  `estadoImportacion` int(11) DEFAULT NULL,
  `origenImportacion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=latin1;

LOCK TABLES `movimientos-detalle` WRITE;
INSERT INTO `movimientos-detalle` VALUES (1,1,1.00,1,32,50.00,'',21.150,63.00,7.83,21.00,1.64,9.47,9.47,2,NULL),(2,1,1.00,1,34,100.00,'',25.100,63.00,9.29,21.00,1.95,11.24,11.24,1,NULL),(3,2,5.00,1,33,0.00,'',23.360,63.00,43.22,21.00,9.08,52.29,52.29,1,NULL),(6,3,1.00,1,32,0.00,'',21.150,0.00,7.83,0.00,0.00,7.83,7.83,NULL,NULL),(7,4,1.00,1,32,0.00,'',21.150,63.00,7.83,0.00,0.00,7.83,7.83,NULL,NULL),(8,5,1.00,1,0,0.00,'asd',1.000,0.00,1.00,21.00,0.21,1.21,1.21,NULL,NULL),(9,6,1.00,1,0,0.00,'123',1.000,0.00,1.00,21.00,0.21,1.21,1.21,NULL,NULL),(10,7,1.00,1,32,0.00,'',21.150,63.00,7.83,21.00,1.64,9.47,94.70,NULL,NULL),(120,32,1.00,1,32,50.00,'',21.150,63.00,7.83,21.00,1.64,9.47,9.47,NULL,1);
UNLOCK TABLES;

CREATE TABLE `unidades-medida` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `denominacion` varchar(50) DEFAULT NULL,
  `denominacionCorta` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

LOCK TABLES `unidades-medida` WRITE;
INSERT INTO `unidades-medida` VALUES (1,'Kilogramo','Kg'),(2,'Metros','Mts'),(3,'Litros','lts'),(4,'Unidades','Un');
UNLOCK TABLES;

ALTER TABLE `comprobantes` ADD `activo` INT NULL AFTER `comportamiento`;

