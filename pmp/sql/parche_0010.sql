CREATE TABLE `comprobantes-bloqueados-condiciones-iva` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`idCondicionIva` INT UNSIGNED NULL DEFAULT NULL,
	`idComprobanteBloqueado` INT UNSIGNED NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

CREATE TABLE `accesos-directos` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`link` VARCHAR(255) NULL DEFAULT NULL,
	`texto` VARCHAR(255) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
