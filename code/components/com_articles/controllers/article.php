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
 * Article controller class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesControllerArticle extends ComDefaultControllerDefault
{
    public function __construct(KConfig $config) {
        parent::__construct($config);

        $this->registerCallback('after.read', array($this, setViewAcls));
    }

    public function setRequest(array $request) {

        $view   = $request['view'];
        $params = JComponentHelper::getParams('com_articles');

        if (KInflector::isPlural($view) && $request['format'] == 'html') {
            $sort_by_map = array(
                'newest' => array('created' => 'DESC'),
                'oldest' => array('created' => 'ASC'),
                'author' => array('created_by_name', 'ASC'),
                'order'  => array('ordering' => 'ASC'));
            // Force some request vars based on setting parameters.
            $request['limit']     = (int) $params->get('articles_per_page');
            $request['featured']  = (int) $params->get('show_featured');
            $sort_by              = $sort_by_map[$params->get('sort_by')];
            $request['sort']      = key($sort_by);
            $request['direction'] = current($sort_by);
        }

        // Filter rowsets based on current logged user's permissions.
        $user           = JFactory::getUser();
        $request['aid'] = $user->get('aid', 0);

        return parent::setRequest($request);
    }

    public function setViewAcls(KCommandContext $context) {
        $this->getView()->canEdit = $this->canEdit();
    }
}