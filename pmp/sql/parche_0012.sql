ALTER TABLE `comprobantes`
	ADD COLUMN `muestraPendientes` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `impresion`;

ALTER TABLE `terceros`
	ADD COLUMN `condicionVenta` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `limiteDescubierto`;	

ALTER TABLE `movimientos`
	ADD COLUMN `condicionVenta` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `comentarios`;
	