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
class ComArticlesControllerArticle extends ComArticlesControllerDefault
{
    public function __construct(KConfig $config) {
        parent::__construct($config);

        $this->registerCallback(array('after.read', 'after.browse'), array($this, 'setAcls'));
        $this->registerCallback('before.add', array($this, 'filterInput'));
    }

    protected function _initialize(KConfig $config) {
        $config->append(array(
            'behaviors' => array('com://site/articles.controller.behavior.article.executable'),
            'toolbars'  => array('article')));
        parent::_initialize($config);
    }

    public function setRequest(array $request) {

        $view   = $request['view'];
        $params = JComponentHelper::getParams('com_articles');

        if (KInflector::isPlural($view) && $request['format'] == 'html') {
            $sort_by_map = array(
                'newest' => array('created' => 'DESC'),
                'oldest' => array('created' => 'ASC'),
                'order'  => array('ordering' => 'ASC'));
            // Force some request vars based on setting parameters.
            $request['limit']     = (int) $params->get('articles_per_page');
            $request['featured']  = (int) $params->get('show_featured');
            $sort_by              = $sort_by_map[$params->get('sort_by')];
            $request['sort']      = key($sort_by);
            $request['direction'] = current($sort_by);
        }

        // Only return published items.
        $request['state'] = 1;

        return parent::setRequest($request);
    }

    public function filterInput(KCommandContext $context) {
        if (!$this->canEdit()) {
            // Force some default values.
            $data        = $context->data;
            $data->state = 0;
        }
    }

    public function setAcls(KCommandContext $context) {
        $data = $this->getModel()->getData();

        if ($data instanceof KDatabaseRowsetAbstract) {
            foreach ($data as $row) {
                $row->editable = $this->canEdit($row);
            }
        } else {
            $data->editable = $this->canEdit($data);
        }
    }
}