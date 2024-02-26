ALTER TABLE `cotizaciones_detalles`
	ADD COLUMN `margenCotizado` DOUBLE NULL DEFAULT '0' AFTER `cantidadAprobada`,
	ADD COLUMN `precioCotizado` DOUBLE NULL DEFAULT '0' AFTER `margenCotizado`;

ALTER TABLE `cotizaciones_detalles`
	ADD COLUMN `dtoCliente` DOUBLE NULL DEFAULT '0' AFTER `precioCotizado`;

ALTER TABLE `cotizaciones`
	ADD COLUMN `vigencia` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `estado`;

ALTER TABLE `movimientos`
	ADD COLUMN `vigencia` INT UNSIGNED NULL DEFAULT '0' AFTER `fecha`;
		