ALTER TABLE `movimientos-detalle`
	ADD COLUMN `cantidadImportada` DOUBLE(12,2) NULL DEFAULT NULL AFTER `estadoImportacion`;

ALTER TABLE `movimientos-detalle`
	CHANGE COLUMN `cantidadImportada` `cantidadImportada` DOUBLE(12,2) NULL DEFAULT '0' AFTER `estadoImportacion`;

CREATE TABLE `sucursales` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`idTerceroPadre` INT UNSIGNED NULL DEFAULT NULL,
	`idTerceroSucursal` INT UNSIGNED NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;