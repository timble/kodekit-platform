<?php
/**
 * @version     $Id: articles.php 1633 2011-06-07 19:24:17Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Factory mappings
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */

KFactory::map('site::com.articles.database.row.article', 'admin::com.articles.database.table.article');
KFactory::map('site::com.articles.database.table.articles', 'admin::com.articles.database.table.articles');