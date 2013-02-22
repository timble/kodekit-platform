<?php
/**
 * @version     $Id: aliases.php 3776 2012-06-01 16:12:14Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Service Aliases
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

KService::setAlias('com://site/files.model.containers', 'com://admin/files.model.containers');
KService::setAlias('com://site/files.model.default'   , 'com://admin/files.model.default');
KService::setAlias('com://site/files.model.files'     , 'com://admin/files.model.files');
KService::setAlias('com://site/files.model.folders'   , 'com://admin/files.model.folders');
KService::setAlias('com://site/files.model.nodes'     , 'com://admin/files.model.nodes');
KService::setAlias('com://site/files.model.state'	  , 'com://admin/files.model.state');
KService::setAlias('com://site/files.model.thumbnails', 'com://admin/files.model.thumbnails');

KService::setAlias('com://site/files.database.row.file', 'com://admin/files.database.row.file');
KService::setAlias('com://site/files.database.row.folder', 'com://admin/files.database.row.folder');
KService::setAlias('com://site/files.database.row.node', 'com://admin/files.database.row.node');