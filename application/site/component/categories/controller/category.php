<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Categories;

/**
 * Category Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Categories
 */
abstract class CategoriesControllerCategory extends Categories\ControllerCategory
{
    public function getRequest()
	{
		$request = parent::getRequest();

        $request->query->access    = $this->getUser()->isAuthentic();
        $request->query->published = 1;

	    return $request;
	}
}