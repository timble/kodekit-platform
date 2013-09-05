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
 * Searchable Controller Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Articles
 */
class ArticlesControllerBehaviorSearchable extends Library\ControllerBehaviorAbstract
{
    protected function _beforeControllerBrowse(Library\CommandContext $context)
    {
        $request = $this->getRequest();

        if ($searchword = $request->query->get('searchword', 'string'))
        {
            $this->getView()->setLayout('search');

            $this->getModel()->getTable()
                ->attachBehavior('com:articles.database.behavior.pageable', array('user' => $this->getUser()->getId()));
        }
    }
}