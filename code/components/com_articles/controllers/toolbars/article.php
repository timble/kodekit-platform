<?php
/**
 * @version        $Id$
 * @category       Nooku
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Article controller toolbar class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Articles
 */

require_once (JPATH_ADMINISTRATOR . '/components/com_default/controllers/toolbars/default.php');

class ComArticlesControllerToolbarArticle extends ComDefaultControllerToolbarDefault
{
    protected function _initialize(KConfig $config) {
        $config->append(array('controller' => 'com://site/articles.controller.article'));
        parent::_initialize($config);
    }
}