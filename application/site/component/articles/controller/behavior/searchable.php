<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Articles;

use Kodekit\Library;

/**
 * Searchable Controller Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Articles
 */
class ControllerBehaviorSearchable extends Library\ControllerBehaviorAbstract
{
    protected function _beforeBrowse(Library\ControllerContextModel $context)
    {
        $request = $this->getRequest();

        if ($search = $request->query->get('search', 'string'))
        {
            $this->getView()->setLayout('search');

            $this->getModel()->getTable()
                ->addBehavior('com:articles.database.behavior.pageable', array('user' => $this->getUser()->getId()));
        }
    }
}