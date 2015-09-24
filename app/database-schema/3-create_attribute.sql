USE ecommerce;

DROP TABLE IF EXISTS linked_attribute;
DROP TABLE IF EXISTS attribute;
DROP TABLE IF EXISTS subcategory;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS attribute_value;
DROP TABLE IF EXISTS attribute_label;
DROP TABLE IF EXISTS attribute_type;

CREATE TABLE attribute_type (
	type_id SMALLINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
	`code` VARCHAR(32) NOT NULL DEFAULT '',
	PRIMARY KEY (type_id),
	UNIQUE KEY uk_attribute_type_code (`code`)
) ENGINE=INNODB DEFAULT CHARSET=UTF8 COMMENT="Attribute's type index";


-- Default attribute_type
INSERT INTO attribute_type (type_id, `code`) VALUES
(1, 'category'),
(2, 'subcategory'),
(3, 'brand'),
(4, 'color'),
(5, 'material');


CREATE TABLE attribute_label (
	label_id SMALLINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
	type_id SMALLINT(3) UNSIGNED NOT NULL,
	PRIMARY KEY (label_id),
	CONSTRAINT fk_label_type FOREIGN KEY (type_id) REFERENCES attribute_type (type_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=UTF8 COMMENT="Attribute's label index";

CREATE TABLE attribute_value (
	value_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	label_id SMALLINT(3) UNSIGNED NOT NULL,
	created_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	updated_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	locale CHAR(2) NOT NULL,
	`name` VARCHAR(255) NOT NULL DEFAULT '',
	urlkey VARCHAR(255) NOT NULL DEFAULT '',
	hash VARCHAR(40) NOT NULL DEFAULT '',
	PRIMARY KEY (value_id),
	UNIQUE KEY uk_attribute_value_label_locale (label_id, locale),
	CONSTRAINT fk_attribute_value_label FOREIGN KEY (label_id) REFERENCES attribute_label (label_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=UTF8 COMMENT='Label name and key of every attributes';

CREATE TABLE category (
	category_id SMALLINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
	type_id SMALLINT(3) UNSIGNED NOT NULL DEFAULT 1,
	label_id SMALLINT(3) UNSIGNED NOT NULL,
	created_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	updated_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	is_active BOOLEAN NOT NULL DEFAULT '1',
	PRIMARY KEY (category_id),
	CONSTRAINT fk_category_type FOREIGN KEY (type_id) REFERENCES attribute_type (type_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_category_label FOREIGN KEY (label_id) REFERENCES attribute_label (label_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=UTF8 COMMENT="Categories index";

CREATE TABLE subcategory (
	subcategory_id SMALLINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
	type_id SMALLINT(3) UNSIGNED NOT NULL DEFAULT 2,
	label_id SMALLINT(3) UNSIGNED NOT NULL,
	category_id SMALLINT(3) UNSIGNED NOT NULL,
	created_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	updated_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	is_active BOOLEAN NOT NULL DEFAULT '1',
	PRIMARY KEY (subcategory_id),
	CONSTRAINT fk_subcategory_type FOREIGN KEY (type_id) REFERENCES attribute_type (type_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_subcategory_label FOREIGN KEY (label_id) REFERENCES attribute_label (label_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_subcategory_category FOREIGN KEY (category_id) REFERENCES category (category_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=UTF8 COMMENT="Subcategories index";

CREATE TABLE attribute (
	attribute_id SMALLINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
	type_id SMALLINT(3) UNSIGNED NOT NULL,
	label_id SMALLINT(3) UNSIGNED NOT NULL,
	created_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	updated_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	is_active BOOLEAN NOT NULL DEFAULT '1',
	PRIMARY KEY (attribute_id),
	CONSTRAINT fk_attribute_type FOREIGN KEY (type_id) REFERENCES attribute_type (type_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_attribute_label FOREIGN KEY (label_id) REFERENCES attribute_label (label_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=UTF8 COMMENT="Attributes index";

CREATE TABLE linked_attribute (
	attribute_id SMALLINT(3) UNSIGNED NOT NULL,
	category_id SMALLINT(3) UNSIGNED NOT NULL,
	subcategory_id SMALLINT(3) UNSIGNED,
	UNIQUE KEY uk_linked_attribute (attribute_id, category_id, subcategory_id),
	CONSTRAINT fk_linked_attribute_attribute FOREIGN KEY (attribute_id) REFERENCES attribute (attribute_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_linked_attribute_category FOREIGN KEY (category_id) REFERENCES category (category_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_linked_material_subcategory FOREIGN KEY (subcategory_id) REFERENCES subcategory (subcategory_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=UTF8 COMMENT="Links between attributes and (sub)categories";
