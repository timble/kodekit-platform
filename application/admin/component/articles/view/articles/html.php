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
 * Articles HTML View
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Kodekit\Platform\Articles
 */
class ViewArticlesHtml extends Library\ViewHtml
{
    protected function _fetchData(Library\ViewContext $context)
    {
        $state = $this->getModel()->getState();

        // Enable sortable
        $context->data->sortable = $state->category && $state->sort == 'ordering';

        parent::_fetchData($context);
    }
}