ALTER TABLE `terceros-medios-contactos`
	ADD COLUMN `denominacion` VARCHAR(80) NULL DEFAULT NULL AFTER `contacto`;

ALTER TABLE `terceros`
	ADD COLUMN `dto1` FLOAT NULL DEFAULT NULL AFTER `dtoCliente`,
	ADD COLUMN `dto2` FLOAT NULL DEFAULT NULL AFTER `dto1`,
	ADD COLUMN `dto3` FLOAT NULL DEFAULT NULL AFTER `dto2`;

ALTER TABLE `articulos-proveedores`
	ADD COLUMN `dto1` FLOAT NULL DEFAULT NULL AFTER `precio`,
	ADD COLUMN `dto2` FLOAT NULL DEFAULT NULL AFTER `dto1`,
	ADD COLUMN `dto3` FLOAT NULL DEFAULT NULL AFTER `dto2`;

CREATE TABLE `articulos-stock` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`idArticulo` INT UNSIGNED NULL DEFAULT '0',
	`idUnidadMedida` INT UNSIGNED NULL DEFAULT '0',
	`indiceConversion` FLOAT NULL DEFAULT '0',
	`stock` FLOAT NULL DEFAULT '0',
	`stockReservado` FLOAT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
);

ALTER TABLE `articulos-stock`
	CHANGE COLUMN `stockReservado` `stockReservadoVenta` FLOAT NULL DEFAULT '0' AFTER `stock`,
	ADD COLUMN `stockReservadoCompra` FLOAT NULL DEFAULT '0' AFTER `stockReservadoVenta`,
	ADD COLUMN `stockMinimo` FLOAT NULL DEFAULT '0' AFTER `stockReservadoCompra`;

ALTER TABLE `comprobantes`
	ADD COLUMN `ventaStock` INT(11) NULL DEFAULT NULL AFTER `activo`,
	ADD COLUMN `ventaStockReservadoVenta` INT(11) NULL DEFAULT NULL AFTER `ventaStock`,
	ADD COLUMN `ventaStockReservadoCompra` INT(11) NULL DEFAULT NULL AFTER `ventaStockReservadoVenta`,
	ADD COLUMN `compraStock` INT(11) NULL DEFAULT NULL AFTER `ventaStockReservadoCompra`,
	ADD COLUMN `compraStockReservadoVenta` INT(11) NULL DEFAULT NULL AFTER `compraStock`,
	ADD COLUMN `compraStockReservadoCompra` INT(11) NULL DEFAULT NULL AFTER `compraStockReservadoVenta`;	

ALTER TABLE `articulos`
	ADD COLUMN `idUnidadMedidaCompra` INT UNSIGNED NULL AFTER `detalle`,
	ADD COLUMN `idUnidadMedidaVenta` INT UNSIGNED NULL AFTER `idUnidadMedidaCompra`;

ALTER TABLE `movimientos-detalle`
	CHANGE COLUMN `alicuotaIva` `alicuotaIva` INT(11) NULL DEFAULT NULL AFTER `importeTotal`;

CREATE TABLE `respuestas-afip` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`respuesta` TEXT NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
);
