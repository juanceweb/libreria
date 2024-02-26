ALTER TABLE `terceros`
	ADD COLUMN `codigoPostal` VARCHAR(20) NULL DEFAULT NULL AFTER `direccion`,
	ADD COLUMN `codigoPostalFiscal` VARCHAR(20) NULL DEFAULT NULL AFTER `direccionFiscal`;
