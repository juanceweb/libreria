ALTER TABLE `articulos`
	ADD COLUMN `codigosExternos` TEXT NULL AFTER `idUnidadMedidaVenta`;

	ALTER TABLE `articulos-proveedores`
	CHANGE COLUMN `codExterno` `codExterno` VARCHAR(50) NULL DEFAULT NULL AFTER `idArticulo`;

	ALTER TABLE `articulos`
	CHANGE COLUMN `id` `id` INT(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT FIRST;

	ALTER TABLE `movimientos`
	CHANGE COLUMN `ptoVenta` `ptoVenta` INT(4) ZEROFILL NULL DEFAULT NULL AFTER `nroDocTercero`,
	CHANGE COLUMN `nroComprobante` `nroComprobante` INT(8) ZEROFILL NULL DEFAULT NULL AFTER `ptoVenta`;

ALTER TABLE `comprobantes`
	ADD COLUMN `impresion` VARCHAR(255) NULL DEFAULT NULL AFTER `compraStockReservadoCompra`;
