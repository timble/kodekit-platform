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
 * Tags Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Tags
 */
class TagsViewTagsHtml extends Library\ViewHtml
{
    protected function _fetchData(Library\ViewContext $context)
	{
		//If no row exists assign an empty array
		if($this->getModel()->getState()->row) {
			$context->data->disabled = false;
		} else {
			$context->data->disabled = true;
		}

        parent::_fetchData($context);
	}
}
