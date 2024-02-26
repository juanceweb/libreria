CREATE TABLE `descuentosAsociados` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idTercero` INT(11) UNSIGNED NOT NULL,
	`idTerceroBase` INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `idTerceroBase` (`idTerceroBase`),
	UNIQUE INDEX `idTercero` (`idTercero`)
)
COLLATE='utf8_spanish_ci'
;
