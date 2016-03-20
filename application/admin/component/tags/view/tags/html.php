<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Tags;

use Kodekit\Library;

/**
 * Tags Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Tags
 */
class ViewTagsHtml extends Library\ViewHtml
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
