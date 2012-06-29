<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Section RSS View Class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesViewSectionRss extends ComArticlesViewRss
{
    protected function _getItems() {
        $section = $this->getModel()->getItem();

        $params = JComponentHelper::getParams('com_articles');

        $user = JFactory::getUser();
        $aid  = $user->get('aid', 0);

        $sort_by_map = array(
            'newest' => array('created' => 'DESC'),
            'oldest' => array('created' => 'ASC'),
            'title'  => array('title' => 'ASC'),
            'order'  => array('ordering' => 'ASC'));
        $sort_by     = $sort_by_map[$params->get('sort_by')];

        $articles = $section->getArticles(array(
            'model_state' => array(
                'sort'      => key($sort_by),
                'direction' => current($sort_by),
                'aid'       => $aid)))->list;

        return $this->_prepareItems($articles);
    }
}