-- $Id$

CREATE OR REPLACE VIEW `jos_pages_menus` AS
	SELECT
		`tbl`.`id`,
		`tbl`.`menutype` AS `name`,
		`tbl`.`title`,
		`tbl`.`description`,
		COUNT(`menu`.`id`) AS `page_count`
	FROM
		`jos_menu_types` AS `tbl`
	LEFT JOIN
		`jos_menu` AS `menu` ON `menu`.`menutype` = `tbl`.`menutype`
	GROUP BY
		`tbl`.`id`;

CREATE OR REPLACE VIEW `jos_pages_pages` AS
	SELECT
		`tbl`.`id`,
		`tbl`.`parent` AS `parent_id`,
		`type`.`id` AS `pages_menu_id`,
		`type`.`menutype` AS `pages_menu_name`,
		`tbl`.`componentid` AS `component_id`,
		`component`.`name` AS `component_name`,
		`tbl`.`name` AS `title`,
		`tbl`.`alias` AS `slug`,
		`tbl`.`link`,
		`tbl`.`type`,
		`tbl`.`published` AS `enabled`,
		IF(`tbl`.`published` = -2, 1, 0) AS `trashed`,
		`tbl`.`sublevel` AS `level`,
		`tbl`.`ordering`,
		`tbl`.`checked_out` AS `locked_by`,
		`locker`.`username` AS `locked_by_username`,
		`locker`.`name` AS `locked_by_name`,
		`locker`.`email` AS `locked_by_email`,
		`tbl`.`checked_out_time` AS `locked_on`,
		`tbl`.`access` AS `group_id`,
		`g`.`name` AS `group_name`,
		`tbl`.`utaccess`,
		`tbl`.`params`,
		`tbl`.`home`
	FROM
		`jos_menu` AS `tbl`
	LEFT JOIN
		`jos_menu_types` AS `type` ON `tbl`.`menutype` = `type`.`menutype`
	LEFT JOIN
		`jos_groups` AS `g` ON `g`.`id` = `tbl`.`access`
	LEFT JOIN
		`jos_components` AS `component` ON `component`.`id` = `tbl`.`componentid` AND `tbl`.`type` = "component"
	LEFT JOIN
		`jos_users` AS `locker`	ON `locker`.`id` = `tbl`.`checked_out`
	GROUP BY
		`tbl`.`id`;

CREATE OR REPLACE VIEW `jos_pages_pages_modules_relations` AS
	SELECT
		`moduleid` AS `extensions_module_id`,
		`menuid` AS `pages_page_id`
	FROM
		`jos_modules_menu`;