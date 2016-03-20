<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Categories;

use Kodekit\Library;
use Kodekit\Component\Categories;

/**
 * Category Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Categories
 */
abstract class ControllerCategory extends Categories\ControllerCategory
{
    public function getRequest()
	{
		$request = parent::getRequest();

        $request->query->access    = $this->getUser()->isAuthentic();
        $request->query->published = 1;

	    return $request;
	}
}