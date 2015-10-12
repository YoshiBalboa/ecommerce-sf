CREATE DATABASE ecommerce DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci;

USE ecommerce;

CREATE TABLE geo_country (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	code VARCHAR(5) NOT NULL,
	label VARCHAR(255) NOT NULL,
	PRIMARY KEY (id),
	CONSTRAINT UNIQUE uk_row (code)
) ENGINE=INNODB DEFAULT CHARSET=UTF8;

CREATE TABLE geo_subdivision (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	code VARCHAR(5) NOT NULL,
	label VARCHAR(255) NOT NULL,
	country_id INT UNSIGNED NOT NULL,
	PRIMARY KEY (id),
	CONSTRAINT UNIQUE uk_row (code, country_id),
	CONSTRAINT fk_geo_subdivision_country FOREIGN KEY (country_id) REFERENCES geo_country (id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=UTF8;

CREATE TABLE geo_location (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	code VARCHAR(5) NOT NULL,
	label VARCHAR(255) NOT NULL,
	latitude DECIMAL(18,12),
	longitude DECIMAL(18,12),
	subdivision_id INT UNSIGNED,
	country_id INT UNSIGNED NOT NULL,
	PRIMARY KEY (id),
	CONSTRAINT UNIQUE uk_row (code, subdivision_id),
	CONSTRAINT fk_geo_location_subdivision FOREIGN KEY (subdivision_id) REFERENCES geo_subdivision (id) ON UPDATE SET NULL ON DELETE CASCADE,
	CONSTRAINT fk_geo_location_country FOREIGN KEY (country_id) REFERENCES geo_country (id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=UTF8;

/*
-- loading countries
DROP TABLE IF EXISTS tmp.country;
CREATE TABLE tmp.country (
	label VARCHAR(255) NOT NULL,
	code VARCHAR(5) NOT NULL,
	PRIMARY KEY (code)
) ENGINE=INNODB DEFAULT CHARSET=UTF8;

LOAD DATA LOCAL INFILE '%PROJECT_FULL_PATH%/app/database-schema/geo/countries.csv'
INTO TABLE tmp.country
CHARACTER SET 'UTF8'
FIELDS TERMINATED BY ';'
LINES TERMINATED BY "\n"
IGNORE 1 LINES;

INSERT IGNORE
INTO ecommerce.geo_country (code, label)
SELECT code, label
FROM tmp.country;


-- loading subdivisions
DROP TABLE IF EXISTS tmp.subdivision;
CREATE TABLE tmp.subdivision (
	country_code VARCHAR(5) NOT NULL,
	division_code VARCHAR(5) NOT NULL,
	division_label VARCHAR(255) NOT NULL,
	division_group VARCHAR(255) NOT NULL,
	PRIMARY KEY (country_code, division_code)
) ENGINE=INNODB DEFAULT CHARSET=UTF8;

LOAD DATA LOCAL INFILE '%PROJECT_FULL_PATH%/app/database-schema/geo/subdivisions.csv' IGNORE
INTO TABLE tmp.subdivision
CHARACTER SET 'UTF8'
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY "\n"
IGNORE 0 LINES;

INSERT IGNORE
INTO ecommerce.geo_subdivision (code, label, country_id)
SELECT s.division_code, s.division_label, c.id
FROM tmp.subdivision AS s
INNER JOIN ecommerce.geo_country AS c ON c.code = s.country_code;


-- loading cities
DROP TABLE IF EXISTS tmp.location;
CREATE TABLE tmp.location (
	change_code CHAR(1),
	country_code VARCHAR(5) NOT NULL,
	city_code VARCHAR(5),
	city_name_a VARCHAR(255),
	city_name_b VARCHAR(8),
	subdivision_code VARCHAR(8),
	classify1 VARCHAR(8),
	classify2 VARCHAR(4),
	updated VARCHAR(8),
	classify3 VARCHAR(4),
	geoloc VARCHAR(255),
	PRIMARY KEY (country_code, city_code, subdivision_code)
) ENGINE=INNODB DEFAULT CHARSET=UTF8;

LOAD DATA LOCAL INFILE '%PROJECT_FULL_PATH%/app/database-schema/geo/locations_part1.csv' IGNORE
INTO TABLE tmp.location
CHARACTER SET 'UTF8'
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY "\n"
IGNORE 0 LINES;

LOAD DATA LOCAL INFILE '%PROJECT_FULL_PATH%/app/database-schema/geo/locations_part2.csv' IGNORE
INTO TABLE tmp.location
CHARACTER SET 'UTF8'
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY "\n"
IGNORE 0 LINES;

LOAD DATA LOCAL INFILE '%PROJECT_FULL_PATH%/app/database-schema/geo/locations_part3.csv' IGNORE
INTO TABLE tmp.location
CHARACTER SET 'UTF8'
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY "\n"
IGNORE 0 LINES;

INSERT IGNORE INTO ecommerce.geo_location (code, label, subdivision_id, country_id, latitude, longitude)
SELECT l.city_code, l.city_name_a, s.id, c.id
	, CONCAT(
		CASE WHEN SUBSTRING(SUBSTRING_INDEX(l.geoloc, ' ', 1), -1) = 'N' THEN '' ELSE '-' END,
		TRIM(TRAILING 'N' FROM TRIM(TRAILING 'S' FROM SUBSTRING_INDEX(l.geoloc, ' ', 1))) / 100
	)
	, CONCAT(
		CASE WHEN SUBSTRING(SUBSTRING_INDEX(l.geoloc, ' ', -1), -1) = 'E' THEN '' ELSE '-' END,
		TRIM(TRAILING 'E' FROM TRIM(TRAILING 'W' FROM SUBSTRING_INDEX(l.geoloc, ' ', -1))) / 100
	)
FROM tmp.location AS l
INNER JOIN ecommerce.geo_country AS c ON c.code = l.country_code
LEFT JOIN ecommerce.geo_subdivision AS s ON s.code = l.subdivision_code AND c.id = s.country_id;

UPDATE ecommerce.geo_location SET latitude = NULL WHERE latitude = 0;
UPDATE ecommerce.geo_location SET longitude = NULL WHERE longitude = 0;
*/
