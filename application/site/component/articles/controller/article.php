<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Articles;

use Kodekit\Library;

/**
 * Article Controller
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Platform\Articles
 */
class ControllerArticle extends Library\ControllerModel
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'formats'   => array('rss'),
            'toolbars'  => array('article'),
            'behaviors' => array('editable', 'searchable'))
        );

        parent::_initialize($config);
    }

    public function getRequest()
    {
        $request = parent::getRequest();

        // Public users can only access published none registered articles
        if ($this->isDispatched() && !$this->getUser()->isAuthentic())
        {
            $request->query->access    = 0;
            $request->query->published = 1;
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
                $page   = $this->getObject('pages')->getActive();
                $params = $page->getParams('page');

                // Force some request vars based on setting parameters.
                $request->query->limit = (int) $params->get('articles_per_page', 3);

                $sort_by = $sort_by_map[$params->get('sort_by', 'newest')];
                $request->query->sort      = key($sort_by);
                $request->query->direction = current($sort_by);
            }
        }

        return $request;
    }

    protected function _actionAdd(Library\ControllerContextInterface $context)
    {
        //Force article to unpublished if you cannot edit
        if (!$this->canEdit()) {
            $context->request->data->set('published', 0);
        }

        return parent::_actionAdd($context);
    }
}