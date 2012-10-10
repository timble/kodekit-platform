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
 * Article controller class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesControllerArticle extends ComArticlesControllerDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'toolbars'  => array('article'))
        );

        parent::_initialize($config);
    }

    public function setRequest(array $request)
    {
        $view = isset($request['view']) ? $request['view'] : null;

        if ($view && KInflector::isPlural($view))
        {
            if ($request['format'] != 'json')
            {
                $sort_by_map = array(
                    'newest' => array('created_on' => 'DESC'),
                    'oldest' => array('created_on' => 'ASC'),
                    'order'  => array('ordering' => 'ASC'));

                // Get the parameters
                $params = $this->getService('application')->getParams();

                // Force some request vars based on setting parameters.
                $request['limit']     = (int) $params->get('articles_per_page', 10);
                $request['featured']  = (int) $params->get('show_featured', 0);
                $sort_by              = $sort_by_map[$params->get('sort_by', 'newest')];
                $request['sort']      = key($sort_by);
                $request['direction'] = current($sort_by);
            }

            // Allow editors (and above) to view unpublished items on lists.
            if (!$this->canEdit()) {
                $request['state'] = 1;
            }

            //Always show child category articles
            $request['category_recurse'] = true;
        }

        return parent::setRequest($request);
    }

    protected function _actionAdd(KCommandContext $context)
    {
        //Force article to unpublished if you cannot edit
        if (!$this->canEdit()) {
            $context->data->state = 0;
        }

        return parent::_actionAdd($context);
    }
}