-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 01, 2012 at 12:32 AM
-- Server version: 5.5.24
-- PHP Version: 5.3.12

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40101 SET @OLD_TIME_ZONE=@@TIME_ZONE, TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

--
-- Dumping data for table `#__extensions_components`
--

INSERT INTO `#__extensions_components` (`id`, `title`, `name`, `params`, `enabled`)
VALUES
    (4, 'Web Links', 'com_weblinks', 'show_comp_description=1\ncomp_description=\nshow_link_hits=1\nshow_link_description=1\nshow_other_cats=1\nshow_headings=1\nshow_page_title=1\nlink_target=0\nlink_icons=\n\n', 1),
    (7, 'Contacts', 'com_contacts', 'contact_icons=0\nicon_address=\nicon_email=\nicon_telephone=\nicon_fax=\nicon_misc=\nshow_headings=1\nshow_position=1\nshow_email=0\nshow_telephone=1\nshow_mobile=1\nshow_fax=1\nbannedEmail=\nbannedSubject=\nbannedText=\nsession=1\ncustomReply=0\n\n', 1),
    (15, 'Search', 'com_search', 'enabled=0\n\n', 1),
    (19, 'Files', 'com_files', 'pload_extensions=bmp,csv,doc,epg,gif,ico,jpg,odg,odp,ods,odt,pdf,png,ppt,swf,txt,xcf,xls,BMP,CSV,DOC,EPG,GIF,ICO,JPG,ODG,ODP,ODS,ODT,PDF,PNG,PPT,SWF,TXT,XCF,XLS\nupload_maxsize=10000000\nimage_path=images\nrestrict_uploads=1\ncheck_mime=1\nimage_extensions=bmp,gif,jpg,png\nignore_extensions=\nupload_mime=image/jpeg,image/gif,image/png,image/bmp,application/x-shockwave-flash,application/msword,application/excel,application/pdf,application/powerpoint,text/plain,application/x-zip\nupload_mime_illegal=text/html', 1),
    (20, 'Articles', 'com_articles', 'show_noauth=0\nshow_title=1\nlink_titles=0\nshow_intro=1\nshow_section=0\nlink_section=0\nshow_category=0\nlink_category=0\nshow_author=1\nshow_create_date=1\nshow_modify_date=1\nshow_item_navigation=0\nshow_readmore=1\nshow_vote=0\nshow_icons=1\nshow_pdf_icon=1\nshow_print_icon=1\nshow_hits=1\nfeed_summary=0\n\n', 1),
    (23, 'Languages', 'com_languages', '', 1),
    (25, 'Pages', 'com_pages', '', 1),
    (28, 'Extensions', 'com_extensions', 'template_site=bootstrap\ntemplate_administrator=default\nlanguage_site=en-GB\nlanguage_administrator=en-GB', 1),
    (31, 'Users', 'com_users', 'allowUserRegistration=1\nnew_usertype=Registered\nuseractivation=1\nfrontend_userparams=1\n\n', 1),
    (32, 'Cache', 'com_cache', '', 1),
    (34, 'Activities', 'com_activities', '', 1),
    (35, 'Dashboard', 'com_dashboard', '', 1);   

--
-- Dumping data for table `#__languages`
--

INSERT INTO `#__languages` (`languages_language_id`, `application`, `name`, `native_name`, `iso_code`, `slug`, `enabled`, `primary`)
VALUES
    (1, 'admin', 'English (United Kingdom)', 'English (United Kingdom)', 'en-GB', 'en', 1, 1),
    (2, 'site', 'English (United Kingdom)', 'English (United Kingdom)', 'en-GB', 'en', 1, 1);

--
-- Dumping data for table `#__languages_components`
--

INSERT INTO `#__languages_tables` (`extensions_component_id`, `name`, `unique_column`, `enabled`)
VALUES
    (20, 'articles', 'articles_article_id', 0),
    (20, 'categories', 'categories_category_id', 0);

--
-- Dumping data for table `#__pages_menus`
--

INSERT INTO `#__pages_menus` (`pages_menu_id`, `title`, `slug`, `description`)
VALUES
    (1, 'Main Menu', 'mainmenu', 'The main menu for the site');

--
-- Dumping data for table `#__pages_modules_pages`
--

INSERT INTO `#__pages_modules_pages` (`modules_module_id`, `pages_page_id`) VALUES (1, 0);

--
-- Dumping data for table `#__pages_orderings`
--

INSERT INTO `#__pages_orderings` (`pages_page_id`, `title`, `custom`)
VALUES
    (1, 2, 1);

--
-- Dumping data for table `#__pages_closures`
--

INSERT INTO `#__pages_closures` (`ancestor_id`, `descendant_id`, `level`)
VALUES
    (1, 1, 0);

--
-- Dumping data for table `#__pages_modules`
--

INSERT INTO `#__pages_modules` (`id`, `title`, `content`, `ordering`, `position`, `created_by`, `created_on`, `modified_by`, `modified_on`, `locked_by`, `locked_on`, `published`, `name`, `access`, `showtitle`, `params`, `extensions_component_id`, `application`)
VALUES
	(1, 'Main Menu', '', 2, 'left', NULL, NULL, NULL, NULL, 0, '0000-00-00 00:00:00', 1, 'mod_menu', 0, 1, 'menu_id=1\nmoduleclass_sfx=_menu\n', 25, 'site');

--
-- Dumping data for table `#__pages`
--

INSERT INTO `#__pages` (`pages_page_id`, `pages_menu_id`, `title`, `slug`, `link_url`, `link_id`, `type`, `published`, `hidden`, `home`, `extensions_component_id`, `created_by`, `created_on`, `modified_by`, `modified_on`, `locked_by`, `locked_on`, `access`, `params`)
VALUES
	(1, 1, 'Home', 'home', 'index.php?option=com_articles&view=articles', NULL, 'component', 1, 0, 1, 20, NULL, NULL, NULL, NULL, 0, '0000-00-00 00:00:00', 0, 'show_featured=1\nshow_page_title=1\npage_title=Welcome to the Frontpage\nshow_description=0\nshow_description_image=0\nnum_leading_articles=1\nnum_intro_articles=4\nnum_columns=2\nnum_links=4\nshow_title=1\nmenu_image=-1\nsecure=0\norderby_pri=\norderby_sec=front\nshow_pagination=2\nshow_pagination_results=1\nshow_noauth=0\nlink_titles=0\nshow_intro=1\nshow_section=0\nlink_section=0\nshow_category=0\nlink_category=0\nshow_author=1\nshow_create_date=1\nshow_modify_date=1\nshow_item_navigation=0\nshow_readmore=1\nshow_vote=0\nshow_icons=1\nshow_pdf_icon=1\nshow_print_icon=1\nshow_email_icon=1\nshow_hits=1\n\n');

--
-- Dumping data for table `#__core_acl_aro_groups`
--

INSERT INTO `#__core_acl_aro_groups` VALUES (17,0,'ROOT',1,22,'ROOT');
INSERT INTO `#__core_acl_aro_groups` VALUES (28,17,'USERS',2,21,'USERS');
INSERT INTO `#__core_acl_aro_groups` VALUES (29,28,'Public Frontend',3,12,'Public Frontend');
INSERT INTO `#__core_acl_aro_groups` VALUES (18,29,'Registered',4,11,'Registered');
INSERT INTO `#__core_acl_aro_groups` VALUES (19,18,'Author',5,10,'Author');
INSERT INTO `#__core_acl_aro_groups` VALUES (20,19,'Editor',6,9,'Editor');
INSERT INTO `#__core_acl_aro_groups` VALUES (21,20,'Publisher',7,8,'Publisher');
INSERT INTO `#__core_acl_aro_groups` VALUES (30,28,'Public Backend',13,20,'Public Backend');
INSERT INTO `#__core_acl_aro_groups` VALUES (23,30,'Manager',14,19,'Manager');
INSERT INTO `#__core_acl_aro_groups` VALUES (24,23,'Administrator',15,18,'Administrator');
INSERT INTO `#__core_acl_aro_groups` VALUES (25,24,'Super Administrator',16,17,'Super Administrator');

--
-- Dumping data for table `#__core_acl_aro_sections`
--

INSERT INTO `#__core_acl_aro_sections` VALUES (10,'users',1,'Users',0);

--
-- Dumping data for table `#__files_containers`
--

INSERT INTO `#__files_containers` (`files_container_id`, `slug`, `title`, `path`, `parameters`)
VALUES
    (NULL, 'files-files', 'Images', 'images', '{"thumbnails": true,"maximum_size":"10485760","allowed_extensions": ["bmp", "csv", "doc", "gif", "ico", "jpg", "jpeg", "odg", "odp", "ods", "odt", "pdf", "png", "ppt", "swf", "txt", "xcf", "xls"],"allowed_mimetypes": ["image/jpeg", "image/gif", "image/png", "image/bmp", "application/x-shockwave-flash", "application/msword", "application/excel", "application/pdf", "application/powerpoint", "text/plain", "application/x-zip"],"allowed_media_usergroup":3}');

--
-- Dumping data for table `#__users`
--

INSERT INTO `#__users` (`users_user_id`, `name`, `email`, `usertype`, `enabled`, `send_email`, `users_group_id`, `registered_on`, `last_visited_on`, `created_by`, `created_on`, `modified_by`, `modified_on`, `locked_by`, `locked_on`, `activation`, `params`, `uuid`)
VALUES
	(1, 'Administrator', 'admin@localhost.home', 'Super Administrator', 0, 1, 25, '', '', NULL, NULL, NULL, NULL, NOW(), '', '', '', UUID());


--
-- Dumping data for table `#__passwords`
--

INSERT INTO `#__users_passwords` (`users_user_email`, `expiration`, `hash`) VALUES
('admin@localhost.home', NULL, 'e290e05761fc8cc389b3455c9f542a12:1DCv4IYMFTrxblCfGwUulyTXYeKqQCh3');

--
-- Dumping data for table `#__core_acl_aro`
--

INSERT INTO `#__core_acl_aro` (`id`, `section_value`, `value`, `order_value`, `name`, `hidden`)
VALUES 
    (10, 'users', '1', 0, 'Administrator', 0);

--
-- Dumping data for table `#__core_acl_groups_aro_map`
--

INSERT INTO `#__core_acl_groups_aro_map` (`group_id`, `section_value`, `aro_id`)
VALUES
    (25, '', 10);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;