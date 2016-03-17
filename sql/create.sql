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
  AUTO_INCREMENT = 7
  DEFAULT CHARSET = utf8
  COMMENT = 'tabulka s vozidly'
