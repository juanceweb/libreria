ALTER TABLE `articulos`
	ADD COLUMN `idRubro` INT(11) NULL DEFAULT NULL AFTER `idSubcateogoria`,
	ADD COLUMN `idMarca` INT(11) NULL DEFAULT NULL AFTER `idRubro`;

CREATE TABLE IF NOT EXISTS `marcas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `denominacion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `rubros` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `denominacion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1904 DEFAULT CHARSET=latin1;

ALTER TABLE `monedas`
	ADD COLUMN `idMonedaActualizacion` INT(11) NULL DEFAULT NULL AFTER `cotizacion`,
	ADD COLUMN `idTipoDolar` INT(11) NULL DEFAULT NULL AFTER `idMonedaActualizacion`;

ALTER TABLE `monedas`
  ADD COLUMN `porcentajeVariacion` FLOAT NULL DEFAULT NULL AFTER `idTipoDolar`;  