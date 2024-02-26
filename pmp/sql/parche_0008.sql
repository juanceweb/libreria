ALTER TABLE `articulos`
	ADD COLUMN `fabricante` VARCHAR(100) NULL DEFAULT NULL AFTER `codigosExternos`;

ALTER TABLE `movimientos`
	ADD COLUMN `comentarios` TEXT NULL DEFAULT NULL AFTER `importeCancelado`;

CREATE TABLE `comprobantes-numeracion` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idComprobante` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`puntoVenta` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`ultimoNumero` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`ultimoNumeroContable` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
