<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Platform\Categories;

use Nooku\Library;

/**
 * Categories Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Categories
 */
class ViewCategoriesHtml extends Library\ViewHtml
{
    protected function _fetchData(Library\ViewContext $context)
	{
		$context->data->params =  $params = $this->getObject('pages')->getActive()->getParams('page');
        parent::_fetchData($context);
	}
}