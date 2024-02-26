ALTER TABLE `comprobantes`
	ADD COLUMN `denominacionCorta` VARCHAR(3) NULL DEFAULT NULL AFTER `denominacion`;

ALTER TABLE `comprobantes`
	ADD COLUMN `preimpreso` VARCHAR(255) NULL DEFAULT NULL AFTER `impresion`,
	ADD COLUMN `configuracionImpresion` TEXT NULL DEFAULT NULL AFTER `preimpreso`;
	