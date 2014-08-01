<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Articles HTML View
 *
 * @author  Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package Component\Articles
 */
class ArticlesViewArticlesHtml extends Library\ViewHtml
{
    protected function _fetchData(Library\ViewContext $context)
    {        
        $state = $this->getModel()->getState();
        
        // Enable sortable
        $context->data->sortable = $state->category && $state->sort == 'ordering' && $state->direction == 'asc';

        parent::_fetchData($context);
    }
}