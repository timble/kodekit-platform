<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Article Controller
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Component\Articles
 */
class ArticlesControllerArticle extends Library\ControllerModel
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'toolbars'  => array('article'),
            'behaviors' => array('editable', 'searchable'))
        );

        parent::_initialize($config);
    }

    public function getRequest()
    {
        $request = parent::getRequest();

        if (!$this->getUser()->isAuthentic()) {
            $request->query->access = 0;
        }

        $view = $request->query->get('view', 'cmd', null);

        if ($view && Library\StringInflector::isPlural($view))
        {
            if ($request->getFormat() != 'json')
            {
                $sort_by_map = array(
                    'newest' => array('ordering_date' => 'DESC'),
                    'oldest' => array('ordering_date' => 'ASC'),
                    'order'  => array('ordering' => 'ASC'));

                // Get the parameters
                $params = $this->getObject('application')->getParams();

                // Force some request vars based on setting parameters.
                $request->query->limit = (int) $params->get('articles_per_page', 3);

                $sort_by = $sort_by_map[$params->get('sort_by', 'newest')];
                $request->query->sort = key($sort_by);
                $request->query->direction   = current($sort_by);
            }

            // Allow editors (and above) to view unpublished items on lists.
            if (!$this->canEdit()) {
                $request->query->published = 1;
            }

            //Always show child category articles
            $request->query->category_recurse = false;
        }

        return $request;
    }

    protected function _actionAdd(Library\CommandContext $context)
    {
        //Force article to unpublished if you cannot edit
        if (!$this->canEdit()) {
            $context->request->data->set('published', 0);
        }

        return parent::_actionAdd($context);
    }
}