CREATE TABLE `cotizaciones` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idTercero` INT(11) UNSIGNED NULL DEFAULT NULL,
	`fecha` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	`contable` INT(1) UNSIGNED NULL DEFAULT NULL,
	`importeNeto` FLOAT UNSIGNED NULL DEFAULT NULL,
	`importeIva` FLOAT UNSIGNED NULL DEFAULT NULL,
	`importeTotal` FLOAT UNSIGNED NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

CREATE TABLE `cotizaciones_detalles` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idCotizacion` INT(11) UNSIGNED NULL DEFAULT NULL,
	`idArticulo` INT(11) UNSIGNED NULL DEFAULT NULL,
	`cantidad` INT(11) UNSIGNED NULL DEFAULT NULL,
	`referencia` VARCHAR(255) NULL DEFAULT NULL,
	`precioReferencia` VARCHAR(255) NULL DEFAULT NULL,
	`idAlicuota` INT(11) UNSIGNED NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
