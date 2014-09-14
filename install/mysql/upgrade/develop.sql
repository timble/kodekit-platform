# -----------------------------------------------------------
# This script will update a Nooku 0.9 database to Nooku develop

UPDATE `pages` SET `link_url` = REPLACE(`link_url`, 'option=com_', 'component=');

UPDATE `pages_modules` SET `name` = REPLACE(`name`, 'mod_', '');