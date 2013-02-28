
-- --------------------------------------------------------
--
-- Table structure for table `#__languages`
--

CREATE TABLE `#__languages` (
    `languages_language_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `application` VARCHAR(50) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `native_name` VARCHAR(150) NOT NULL,
    `iso_code` VARCHAR(8) NOT NULL,
    `slug` VARCHAR(50) NOT NULL,
    `enabled` BOOLEAN NOT NULL DEFAULT 0,
    `primary` BOOLEAN NOT NULL DEFAULT 0,
    PRIMARY KEY (`languages_language_id`)
) ENGINE = InnoDB CHARSET = utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__languages_translations`
--

CREATE TABLE `#__languages_translations` (
    `languages_translation_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `iso_code` VARCHAR(8) NOT NULL,
    `table` VARCHAR(64) NOT NULL,
    `row` INT UNSIGNED NOT NULL,
    `status` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `original` BOOLEAN NOT NULL DEFAULT 0,
    `deleted` BOOLEAN NOT NULL DEFAULT 0,
    PRIMARY KEY (`languages_translation_id`),
    KEY `table_row_iso_code` (`table`, `row`, `iso_code`)
) ENGINE = InnoDB CHARSET = utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__languages_tables`
--

CREATE TABLE `#__languages_tables` (
    `languages_table_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `extensions_component_id` INT(11) UNSIGNED,
    `name` VARCHAR(64) NOT NULL,
    `unique_column` VARCHAR(64) NOT NULL,
    `enabled` BOOLEAN NOT NULL DEFAULT 0,
    PRIMARY KEY (`languages_table_id`),
    CONSTRAINT `#__languages_tables__extensions_component_id` FOREIGN KEY (`extensions_component_id`) REFERENCES `#__extensions_components` (`extensions_component_id`) ON DELETE CASCADE
) ENGINE=InnoDB CHARSET=utf8;