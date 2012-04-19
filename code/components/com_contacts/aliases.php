<?php
/**
 * @version     $Id: aliases.php 3541 2012-04-02 18:24:42Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Service Aliases
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 */

KService::setAlias('com://site/newsfeeds.model.categories', 'com://admin/newsfeeds.model.categories');
KService::setAlias('com://site/newsfeeds.model.newsfeeds' , 'com://admin/newsfeeds.model.newsfeeds');