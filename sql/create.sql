CREATE TABLE `car` (
  `id`               BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT
  COMMENT 'generovane ID',
  `name`             VARCHAR(255)        NOT NULL
  COMMENT 'nazev vozidla',
  `volume`           INT(11)                      DEFAULT NULL
  COMMENT 'obsah motoru v ccm3',
  `power`            INT(11)                      DEFAULT NULL
  COMMENT 'vykon v kW',
  `mileage`          INT(11)                      DEFAULT NULL
  COMMENT 'pocet najetych kilometru',
  `manufacture_year` INT(11)                      DEFAULT NULL
  COMMENT 'rok vyroby',
  `top_speed`        INT(11)                      DEFAULT NULL
  COMMENT 'maximalni rychlost v km/h',
  `acceleration`     DOUBLE                       DEFAULT NULL
  COMMENT 'akcelerace 0-100km/h',
  `price`            INT(11)                      DEFAULT NULL
  COMMENT 'cena vozidla',
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 1
  DEFAULT CHARSET = utf8
  COMMENT = 'tabulka s vozidly';

CREATE TABLE `car_volume` (
  `id`     BIGINT(20) UNSIGNED NOT NULL
  COMMENT 'generovane ID',
  `volume` DOUBLE              NOT NULL
  COMMENT 'normalizovany obsah motoru v ccm3',
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COMMENT = 'normalizovana tabulka s objemem';

CREATE TABLE `car_power` (
  `id`    BIGINT(20) UNSIGNED NOT NULL
  COMMENT 'generovane ID',
  `power` DOUBLE              NOT NULL
  COMMENT 'normalizovany vykon v kW',
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COMMENT = 'normalizovana tabulka s vykonem';

CREATE TABLE `car_mileage` (
  `id`      BIGINT(20) UNSIGNED NOT NULL
  COMMENT 'generovane ID',
  `mileage` DOUBLE              NOT NULL
  COMMENT 'pocet najetych kilometru',
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COMMENT = 'normalizovana tabulka s najezdem';

CREATE TABLE `car_manufacture_year` (
  `id`               BIGINT(20) UNSIGNED NOT NULL
  COMMENT 'generovane ID',
  `manufacture_year` DOUBLE              NOT NULL
  COMMENT 'rok vyroby',
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COMMENT = 'normalizovana tabulka s rokem vyroby';

CREATE TABLE `car_top_speed` (
  `id`        BIGINT(20) UNSIGNED NOT NULL
  COMMENT 'generovane ID',
  `top_speed` DOUBLE              NOT NULL
  COMMENT 'maximalni rychlost v km/h',
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COMMENT = 'normalizovana tabulka s rychlosti';

CREATE TABLE `car_acceleration` (
  `id`           BIGINT(20) UNSIGNED NOT NULL
  COMMENT 'generovane ID',
  `acceleration` DOUBLE              NOT NULL
  COMMENT 'akcelerace 0-100km/h',
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COMMENT = 'normalizovana tabulka s akceleraci';

CREATE TABLE `car_price` (
  `id`    BIGINT(20) UNSIGNED NOT NULL
  COMMENT 'generovane ID',
  `price` DOUBLE              NOT NULL
  COMMENT 'cena vozidla',
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COMMENT = 'normalizovana tabulka s cenou';
