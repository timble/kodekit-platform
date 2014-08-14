<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Articles HTML View
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
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