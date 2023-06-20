

CREATE TABLE `codigopermiso` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `codigo` varchar(5) NOT NULL,
  `orden` int(11) NOT NULL,
  `estatus` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `usuarioId` bigint(20) unsigned NOT NULL,
  `tipo` varchar(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuarioId` (`usuarioId`),
  CONSTRAINT `codigopermiso_ibfk_1` FOREIGN KEY (`usuarioId`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `usuariopermiso` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(5) NOT NULL,
  `valor` varchar(5) NOT NULL,
  `usuarioId` bigint(20) unsigned NOT NULL,
  `codigopermisoId` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `codigopermisoId` (`codigopermisoId`),
  KEY `usuarioId` (`usuarioId`),
  CONSTRAINT `usuariopermiso_ibfk_1` FOREIGN KEY (`codigopermisoId`) REFERENCES `codigopermiso` (`id`),
  CONSTRAINT `usuariopermiso_ibfk_2` FOREIGN KEY (`usuarioId`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `bo_kpi_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dateComment` date DEFAULT NULL,
  `commentNum` bigint(10) DEFAULT NULL,
  `idInforme` bigint(10) DEFAULT NULL,
  `commentTitle` varchar(150) DEFAULT NULL,
  `estatus` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `comentario` text,
  `usuarioId` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuarioId` (`usuarioId`),
  CONSTRAINT `bo_kpi_comments_ibfk_1` FOREIGN KEY (`usuarioId`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `bo_kpi_store_cebececo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `regional` varchar(25) DEFAULT NULL,
  `segmento` varchar(25) DEFAULT NULL,
  `formato` varchar(50) DEFAULT NULL,
  `nombre_segmento` varchar(50) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `cebe_ceco` varchar(15) NOT NULL,
  `nombre_cebe_ceco` varchar(50) DEFAULT NULL,
  `cod_region` bigint(10) DEFAULT NULL,
  `cod_formato` varchar(3) DEFAULT NULL,
  `cod_direccion` bigint(10) DEFAULT NULL,
  `seg_administrado` bit(1) DEFAULT NULL,
  `estatus` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `comentario` text,
  `usuarioId` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuarioId` (`usuarioId`),
  CONSTRAINT `bo_kpi_store_cebececo_ibfk_1` FOREIGN KEY (`usuarioId`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `bo_kpi_general` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) DEFAULT NULL,
  `codeGeneral` varchar(4) DEFAULT NULL,
  `idInforme` bigint(10) DEFAULT NULL,
  `estatus` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `comentario` text,
  `usuarioId` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codeGeneral_UNIQUE` (`codeGeneral`),
  KEY `usuarioId` (`usuarioId`),
  CONSTRAINT `bo_kpi_general_ibfk_1` FOREIGN KEY (`usuarioId`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `bo_kpi_secundaria` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `codeGeneral` varchar(4) DEFAULT NULL,
  `codeSecundaria` varchar(4) DEFAULT NULL,
  `idInforme` bigint(10) DEFAULT NULL,
  `estatus` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `comentario` text,
  `usuarioId` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codeSecundaria_UNIQUE` (`codeSecundaria`),
  KEY `usuarioId` (`usuarioId`),
  KEY `codeGeneral` (`codeGeneral`),
  CONSTRAINT `bo_kpi_secundaria_ibfk_1` FOREIGN KEY (`usuarioId`) REFERENCES `users` (`id`),
  CONSTRAINT `bo_kpi_secundaria_ibfk_2` FOREIGN KEY (`codeGeneral`) REFERENCES `bo_kpi_general` (`codeGeneral`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `bo_kpi_especifica` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `codeGeneral` varchar(4) DEFAULT NULL,
  `codeSecundaria` varchar(4) DEFAULT NULL,
  `codeEspecifica` varchar(4) DEFAULT NULL,
  `idInforme` bigint(10) DEFAULT NULL,
  `estatus` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `comentario` text,
  `usuarioId` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codeEspecifica_UNIQUE` (`codeEspecifica`),
  KEY `usuarioId` (`usuarioId`),
  KEY `codeGeneral` (`codeGeneral`),
  KEY `codeSecundaria` (`codeSecundaria`),
  CONSTRAINT `bo_kpi_especifica_ibfk_1` FOREIGN KEY (`usuarioId`) REFERENCES `users` (`id`),
  CONSTRAINT `bo_kpi_especifica_ibfk_2` FOREIGN KEY (`codeGeneral`) REFERENCES `bo_kpi_secundaria` (`codeGeneral`) ON DELETE NO ACTION,
  CONSTRAINT `bo_kpi_especifica_ibfk_3` FOREIGN KEY (`codeSecundaria`) REFERENCES `bo_kpi_secundaria` (`codeSecundaria`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `bo_kpi_naturaleza` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `codeGeneral` varchar(4) DEFAULT NULL,
  `codeSecundaria` varchar(4) DEFAULT NULL,
  `codeEspecifica` varchar(4) DEFAULT NULL,
  `codeNaturaleza` varchar(4) DEFAULT NULL,
  `idInforme` bigint(10) DEFAULT NULL,
  `estatus` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `comentario` text,
  `usuarioId` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codeNaturaleza_UNIQUE` (`codeNaturaleza`),
  KEY `usuarioId` (`usuarioId`),
  KEY `codeGeneral` (`codeGeneral`),
  KEY `codeSecundaria` (`codeSecundaria`),
  KEY `codeEspecifica` (`codeEspecifica`),
  CONSTRAINT `bo_kpi_naturaleza_ibfk_1` FOREIGN KEY (`usuarioId`) REFERENCES `users` (`id`),
  CONSTRAINT `bo_kpi_naturaleza_ibfk_2` FOREIGN KEY (`codeGeneral`) REFERENCES `bo_kpi_especifica` (`codeGeneral`) ON DELETE NO ACTION,
  CONSTRAINT `bo_kpi_naturaleza_ibfk_3` FOREIGN KEY (`codeSecundaria`) REFERENCES `bo_kpi_especifica` (`codeSecundaria`) ON DELETE NO ACTION,
  CONSTRAINT `bo_kpi_naturaleza_ibfk_4` FOREIGN KEY (`codeEspecifica`) REFERENCES `bo_kpi_especifica` (`codeEspecifica`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `bo_kpi_cuenta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cuenta` varchar(10) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `codeGeneral` varchar(4) DEFAULT NULL,
  `codeSecundaria` varchar(4) DEFAULT NULL,
  `codeEspecifica` varchar(4) DEFAULT NULL,
  `codeNaturaleza` varchar(4) DEFAULT NULL,
  `idInforme` bigint(10) DEFAULT NULL,
  `estatus` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `comentario` text,
  `usuarioId` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuarioId` (`usuarioId`),
  KEY `codeGeneral` (`codeGeneral`),
  KEY `codeSecundaria` (`codeSecundaria`),
  KEY `codeEspecifica` (`codeEspecifica`),
  KEY `codeNaturaleza` (`codeNaturaleza`),
  CONSTRAINT `bo_kpi_cuenta_ibfk_1` FOREIGN KEY (`usuarioId`) REFERENCES `users` (`id`),
  CONSTRAINT `bo_kpi_cuenta_ibfk_2` FOREIGN KEY (`codeGeneral`) REFERENCES `bo_kpi_especifica` (`codeGeneral`) ON DELETE NO ACTION,
  CONSTRAINT `bo_kpi_cuenta_ibfk_3` FOREIGN KEY (`codeSecundaria`) REFERENCES `bo_kpi_especifica` (`codeSecundaria`) ON DELETE NO ACTION,
  CONSTRAINT `bo_kpi_cuenta_ibfk_4` FOREIGN KEY (`codeEspecifica`) REFERENCES `bo_kpi_especifica` (`codeEspecifica`) ON DELETE NO ACTION,
  CONSTRAINT `bo_kpi_cuenta_ibfk_5` FOREIGN KEY (`codeNaturaleza`) REFERENCES `bo_kpi_naturaleza` (`codeNaturaleza`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


