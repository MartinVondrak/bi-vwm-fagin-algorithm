-- Vytvoreni tabulky pro entitu auta
CREATE TABLE `car` (
 `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'generovane ID',
 `name` varchar(255) NOT NULL COMMENT 'nazev vozidla',
 `volume` int(11) DEFAULT NULL COMMENT 'obsah motoru v ccm3',
 `power` int(11) DEFAULT NULL COMMENT 'vykon v kW',
 `mileage` int(11) DEFAULT NULL COMMENT 'pocet najetych kilometru',
 `manufacture_year` int(11) DEFAULT NULL COMMENT 'rok vyroby',
 `doors` int(11) DEFAULT NULL COMMENT 'pocet dveri',
 `airbags` int(11) DEFAULT NULL COMMENT 'pocet airbagu',
 `seats` int(11) DEFAULT NULL COMMENT 'pocet sedadel',
 `gears` int(11) DEFAULT NULL COMMENT 'pocet stupnu prevodovky',
 `top_speed` int(11) DEFAULT NULL COMMENT 'maximalni rychlost v km/h',
 `acceleration` double DEFAULT NULL COMMENT 'akcelerace 0-100km/h',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='tabulka s vozidly'
