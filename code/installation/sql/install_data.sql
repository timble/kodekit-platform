#
# IMPORTANT - THIS FILE MUST BE SAVED WITH UTF-8 ENCODING ONLY. BEWARE IF EDITING!
#

# --------------------------------------------------------

INSERT INTO `#__components` VALUES (4, 'Web Links', 'option=com_weblinks', 0, 0, '', 'Manage Weblinks', 'com_weblinks', 0, '', 0, 'show_comp_description=1\ncomp_description=\nshow_link_hits=1\nshow_link_description=1\nshow_other_cats=1\nshow_headings=1\nshow_page_title=1\nlink_target=0\nlink_icons=\n\n', 1);
INSERT INTO `#__components` VALUES (5, 'Links', '', 0, 4, 'option=com_weblinks', 'View existing weblinks', 'com_weblinks', 1, '', 0, '', 1);
INSERT INTO `#__components` VALUES (6, 'Categories', '', 0, 4, 'option=com_weblinks&view=categories', 'Manage weblink categories', '', 2, '', 0, '', 1);
INSERT INTO `#__components` VALUES (7, 'Contacts', 'option=com_contacts', 0, 0, '', 'Edit contact details', 'com_contacts', 0, '', 0, 'contact_icons=0\nicon_address=\nicon_email=\nicon_telephone=\nicon_fax=\nicon_misc=\nshow_headings=1\nshow_position=1\nshow_email=0\nshow_telephone=1\nshow_mobile=1\nshow_fax=1\nbannedEmail=\nbannedSubject=\nbannedText=\nsession=1\ncustomReply=0\n\n', 1);
INSERT INTO `#__components` VALUES (8, 'Contacts', '', 0, 7, 'option=com_contacts', 'Edit contact details', 'com_contacts', 0, '', 0, '', 1);
INSERT INTO `#__components` VALUES (9, 'Categories', '', 0, 7, 'option=com_contacts&view=categories', 'Manage contact categories', '', 2, '', 0, 'contact_icons=0\nicon_address=\nicon_email=\nicon_telephone=\nicon_fax=\nicon_misc=\nshow_headings=1\nshow_position=1\nshow_email=0\nshow_telephone=1\nshow_mobile=1\nshow_fax=1\nbannedEmail=\nbannedSubject=\nbannedText=\nsession=1\ncustomReply=0\n\n', 1);
INSERT INTO `#__components` VALUES (19, 'Media Manager', '', 0, 0, 'option=com_files', 'Media Manager', 'com_files', 0, '', 1, 'upload_extensions=bmp,csv,doc,epg,gif,ico,jpg,odg,odp,ods,odt,pdf,png,ppt,swf,txt,xcf,xls,BMP,CSV,DOC,EPG,GIF,ICO,JPG,ODG,ODP,ODS,ODT,PDF,PNG,PPT,SWF,TXT,XCF,XLS\nupload_maxsize=10000000\nimage_path=images\nrestrict_uploads=1\ncheck_mime=1\nimage_extensions=bmp,gif,jpg,png\nignore_extensions=\nupload_mime=image/jpeg,image/gif,image/png,image/bmp,application/x-shockwave-flash,application/msword,application/excel,application/pdf,application/powerpoint,text/plain,application/x-zip\nupload_mime_illegal=text/html', 1);
INSERT INTO `#__components` VALUES (20, 'Articles', 'option=com_articles', '0', '0', 'option=com_articles', 'Articles', 'com_articles', '0', '', '0', 'show_noauth=0\nshow_title=1\nlink_titles=0\nshow_intro=1\nshow_section=0\nlink_section=0\nshow_category=0\nlink_category=0\nshow_author=1\nshow_create_date=1\nshow_modify_date=1\nshow_item_navigation=0\nshow_readmore=1\nshow_vote=0\nshow_icons=1\nshow_pdf_icon=1\nshow_print_icon=1\nshow_hits=1\nfeed_summary=0\n\n', '1');
INSERT INTO `#__components` VALUES (25, 'Menu Editor', '', 0, 0, '', 'Menu Editor', 'com_menus', 0, '', 1, '', 1);
INSERT INTO `#__components` VALUES (28, 'Extension Manager', '', 0, 0, '', 'Extensions', 'com_extensions', 0, '', 1, 'template_site=bootstrap\ntemplate_administrator=default\nlanguage_site=en-GB\nlanguage_administrator=en-GB', 1);
INSERT INTO `#__components` VALUES (31, 'User Manager', 'option=com_users', 0, 0, '', 'Users', 'com_users', 0, '', 1, 'allowUserRegistration=1\nnew_usertype=Registered\nuseractivation=1\nfrontend_userparams=1\n\n', 1);
INSERT INTO `#__components` VALUES (32, 'Cache Manager', '', 0, 0, '', 'Cache', 'com_cache', 0, '', 1, '', 1);

# --------------------------------------------------------

INSERT INTO `#__groups` VALUES (0, 'Public');
INSERT INTO `#__groups` VALUES (1, 'Registered');
INSERT INTO `#__groups` VALUES (2, 'Special');

# --------------------------------------------------------

INSERT INTO `#__plugins` VALUES (6, 'Search - Content','content','search',0,1,1,0,0,0,'0000-00-00 00:00:00','search_limit=50\nsearch_content=1\nsearch_uncategorised=1\nsearch_archived=1\n\n');
INSERT INTO `#__plugins` VALUES (7, 'Search - Contacts','contacts','search',0,3,1,0,0,0,'0000-00-00 00:00:00','search_limit=50\n\n');
INSERT INTO `#__plugins` VALUES (8, 'Search - Categories', 'categories', 'search', 0, 4, 1, 0, 0, 0, '0000-00-00 00:00:00', 'search_limit=50\n\n');
INSERT INTO `#__plugins` VALUES (9, 'Search - Sections', 'sections', 'search', 0, 5, 1, 0, 0, 0, '0000-00-00 00:00:00', 'search_limit=50\n\n');
INSERT INTO `#__plugins` VALUES (11, 'Search - Weblinks','weblinks','search',0,2,1,0,0,0,'0000-00-00 00:00:00','search_limit=50\n\n');
INSERT INTO `#__plugins` VALUES (12, 'Content - Pagebreak','pagebreak','content',0,10000,0,0,0,0,'0000-00-00 00:00:00','enabled=1\ntitle=1\nmultipage_toc=1\nshowall=1\n\n');
INSERT INTO `#__plugins` VALUES (14, 'Content - Email Cloaking', 'emailcloak', 'content', 0, 5, 1, 0, 0, 0, '0000-00-00 00:00:00', 'mode=1\n\n');
INSERT INTO `#__plugins` VALUES (17, 'Content - Page Navigation','pagenavigation','content',0,2,1,0,0,0,'0000-00-00 00:00:00','position=1\n\n');
INSERT INTO `#__plugins` VALUES (26, 'System - Koowa', 'koowa', 'system', 0, 1, 1, 1, 0, 0, '0000-00-00 00:00:00', '');
INSERT INTO `#__plugins` VALUES (27, 'System - SEF','sef','system',0,2,1,0,0,0,'0000-00-00 00:00:00','');
INSERT INTO `#__plugins` VALUES (28, 'System - Debug', 'debug', 'system', 0, 3, 1, 0, 0, 0, '0000-00-00 00:00:00', 'queries=1\nmemory=1\nlangauge=1\n\n');
INSERT INTO `#__plugins` VALUES (30, 'System - Cache', 'cache', 'system', 0, 5, 0, 0, 0, 0, '0000-00-00 00:00:00', 'browsercache=0\ncachetime=15\n\n');
INSERT INTO `#__plugins` VALUES (31, 'System - Expire', 'expire', 'system', 0, 6, 1, 1, 0, 0, '0000-00-00 00:00:00', '');

# --------------------------------------------------------

INSERT INTO `#__menu` VALUES (1, 'mainmenu', 'Home', 'home', 'index.php?option=com_articles&view=articles', 'component', 1, 0, 20, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'articles_per_page=5\nshow_featured=1\nsort_by=newest\nshow_feed_link=1\nshow_create_date=1\nshow_modify_date=1\nshow_readmore=1\npage_title=Welcome to the Frontpage\nshow_page_title=1\npageclass_sfx=\nsecure=0\n\n', 0, 0, 1);
INSERT INTO `#__menu_types` VALUES (1, 'mainmenu', 'Main Menu', 'The main menu for the site');

# --------------------------------------------------------

INSERT INTO `#__modules` VALUES (1, 'Main Menu', '', 1, 'user3', 0, '0000-00-00 00:00:00', 1, 'mod_mainmenu', 0, 0, 1, 'menutype=mainmenu\nstartLevel=0\nendLevel=1\nmoduleclass_sfx=_menu\n', 0, 0, '');
INSERT INTO `#__modules` VALUES (2, 'Login', '', 1, 'login', 0, '0000-00-00 00:00:00', 1, 'mod_login', 0, 0, 1, '', 0, 1, '');
INSERT INTO `#__modules` VALUES (8, 'Toolbar','',1,'toolbar',0,'0000-00-00 00:00:00',1,'mod_toolbar',0,2,1,'',1, 1, '');
INSERT INTO `#__modules` VALUES (10, 'Logged in Users','',2,'cpanel',0,'0000-00-00 00:00:00',1,'mod_logged',0,2,1,'',0,1, '');
INSERT INTO `#__modules` VALUES (12, 'Admin Menu','', 1,'menu', 0,'0000-00-00 00:00:00', 1,'mod_menu', 0, 2, 1, '', 1, 1, '');
INSERT INTO `#__modules` VALUES (13, 'Admin SubMenu','', 1,'submenu', 0,'0000-00-00 00:00:00', 1,'mod_submenu', 0, 2, 1, '', 1, 1, '');
INSERT INTO `#__modules` VALUES (14, 'User Status','', 1,'status', 0,'0000-00-00 00:00:00', 1,'mod_status', 0, 2, 1, '', 0, 1, '');
INSERT INTO `#__modules` VALUES (15, 'Title','', 1,'title', 0,'0000-00-00 00:00:00', 1,'mod_title', 0, 2, 1, '', 1, 1, '');

INSERT INTO `#__modules_menu` VALUES (1,0);

# --------------------------------------------------------

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

INSERT INTO `#__core_acl_aro_sections` VALUES (10,'users',1,'Users',0);

# --------------------------------------------------------
                                                 
INSERT INTO `#__files_containers` (`files_container_id`, `slug`, `title`, `path`, `parameters`) VALUES
(NULL, 'files-files', 'Images', 'images', '{"thumbnails": true,"maximum_size":"10485760","allowed_extensions": ["bmp", "csv", "doc", "gif", "ico", "jpg", "jpeg", "odg", "odp", "ods", "odt", "pdf", "png", "ppt", "swf", "txt", "xcf", "xls"],"allowed_mimetypes": ["image/jpeg", "image/gif", "image/png", "image/bmp", "application/x-shockwave-flash", "application/msword", "application/excel", "application/pdf", "application/powerpoint", "text/plain", "application/x-zip"],"allowed_media_usergroup":3}');

# --------------------------------------------------------

INSERT INTO `#__users` (`id`, `uuid`, `name`, `username`, `email`, `password`, `usertype`, `block`, `sendEmail`, `gid`, `registerDate`, `lastvisitDate`, `activation`, `params`)
VALUES (62, '721a87b1471d4973ada0d69447d2c85f', 'Administrator', 'admin', 'admin@localhost.home', 'e290e05761fc8cc389b3455c9f542a12:1DCv4IYMFTrxblCfGwUulyTXYeKqQCh3', 'Super Administrator', 0, 1, 25, '2012-03-24 13:13:43', '2012-06-26 13:40:09', '', '');


INSERT INTO `#__core_acl_aro` (`id`, `section_value`, `value`, `order_value`, `name`, `hidden`)
VALUES (10, 'users', '62', 0, 'Administrator', 0);


INSERT INTO `#__core_acl_groups_aro_map` (`group_id`, `section_value`, `aro_id`)
VALUES (25, '', 10);