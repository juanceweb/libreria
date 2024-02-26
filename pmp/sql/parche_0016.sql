ALTER TABLE `cotizaciones_detalles`
	ADD COLUMN `cantidadAprobada` INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `idAlicuota`;
ALTER TABLE `cotizaciones`
	ADD COLUMN `estado` INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `importeTotal`;