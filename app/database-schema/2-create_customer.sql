USE ecommerce;

DROP TABLE IF EXISTS customer_details;
DROP TABLE IF EXISTS customer_address_details;
DROP TABLE IF EXISTS customer_address;
DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS customer_group;


CREATE TABLE customer_group (
	group_id SMALLINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
	group_code VARCHAR(32) NOT NULL DEFAULT '',
	PRIMARY KEY (group_id)
) ENGINE=INNODB DEFAULT CHARSET=UTF8 COMMENT='Customer groups';

INSERT INTO customer_group (group_id, group_code) VALUES
(1, 'Particulier'),
(2, 'Désinscrits'),
(3, 'Professionnel'),
(4, 'Admin');


CREATE TABLE customer (
	customer_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	email VARCHAR(255) NOT NULL DEFAULT '',
	password VARCHAR(255),
	group_id SMALLINT(3) UNSIGNED DEFAULT '1',
	created_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	updated_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	is_active BOOLEAN NOT NULL DEFAULT '1',
	PRIMARY KEY (customer_id),
	UNIQUE KEY uk_customer_entity_email (email),
	KEY idx_createdat (created_at),
	CONSTRAINT fk_customer_group FOREIGN KEY (group_id) REFERENCES customer_group (group_id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=INNODB DEFAULT CHARSET=UTF8 ROW_FORMAT=DYNAMIC COMMENT='Customers accounts entities';


CREATE TABLE customer_address (
	address_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	customer_id INT(10) UNSIGNED,
	created_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	updated_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	is_active BOOLEAN NOT NULL DEFAULT '1',
	PRIMARY KEY (address_id),
	CONSTRAINT fk_customer_address_customer FOREIGN KEY (customer_id) REFERENCES customer (customer_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=UTF8 ROW_FORMAT=DYNAMIC COMMENT='Customer address entities';


CREATE TABLE customer_address_details (
	address_id INT(10) UNSIGNED NOT NULL,
	prefix VARCHAR(5),
	firstname VARCHAR(255),
	lastname VARCHAR(255),
	street TEXT,
	postcode VARCHAR(255),
	location_id INT(10) UNSIGNED,
	subdivision_id INT(10) UNSIGNED,
	country_id INT(10) UNSIGNED,
	telephone VARCHAR(255),
	PRIMARY KEY (address_id),
	CONSTRAINT fk_customer_address_details_address FOREIGN KEY (address_id) REFERENCES customer_address (address_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_customer_address_details_country FOREIGN KEY (country_id) REFERENCES geo_country (id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT fk_customer_address_details_subdivision FOREIGN KEY (subdivision_id) REFERENCES geo_subdivision (id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT fk_customer_address_details_location FOREIGN KEY (location_id) REFERENCES geo_location (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=UTF8 COMMENT='Customer address details';


CREATE TABLE customer_details (
	customer_id INT(10) UNSIGNED NOT NULL,
	prefix VARCHAR(5),
	firstname VARCHAR(255),
	lastname VARCHAR(255),
	telephone VARCHAR(255),
	crypt_current_vector VARCHAR(255),
	locale CHAR(2) NOT NULL DEFAULT 'fr',
	default_billing_id INT(10) UNSIGNED,
	default_shipping_id INT(10) UNSIGNED,
	sponsorship_key VARCHAR(255),
	facebook_user_id VARCHAR(255),
	cgu_validated_at DATETIME,
	birthday DATE,
	PRIMARY KEY (customer_id),
	UNIQUE KEY uk_customer_details_sponsorship (sponsorship_key),
	CONSTRAINT fk_customer_details_customer FOREIGN KEY (customer_id) REFERENCES customer (customer_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_customer_details_billing FOREIGN KEY (default_billing_id) REFERENCES customer_address (address_id) ON DELETE SET NULL ON UPDATE CASCADE,
	CONSTRAINT fk_customer_details_shipping FOREIGN KEY (default_shipping_id) REFERENCES customer_address (address_id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=UTF8 COMMENT='Customers accounts details';
