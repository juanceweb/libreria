ALTER TABLE `cotizaciones`
	ADD COLUMN `discriminaIVA` TINYINT(1) UNSIGNED NULL DEFAULT NULL AFTER `vigencia`;
