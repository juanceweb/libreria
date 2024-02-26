ALTER TABLE `unidades-medida`
	ADD COLUMN `indiceConversion` FLOAT NOT NULL DEFAULT 1 AFTER `denominacionCorta`;

ALTER TABLE `articulos-proveedores`
	ADD COLUMN `idUnidadMedida` INT NULL DEFAULT NULL AFTER `precio`;

ALTER TABLE `articulos-proveedores`
	CHANGE COLUMN `idUnidadMedida` `idUnidadMedida` INT(11) NULL DEFAULT 1 AFTER `precio`;

ALTER TABLE `comprobantes`
	ADD COLUMN `cantidadRegistros` INT UNSIGNED NULL DEFAULT 1 AFTER `preimpreso`;			