ALTER TABLE `movimientos-detalle`
	ADD COLUMN `cantidadImporto` DOUBLE(12,2) NULL DEFAULT NULL AFTER `origenImportacion`;