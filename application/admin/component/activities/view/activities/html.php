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
 * Activities Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Activities
 */
class ActivitiesViewActivitiesHtml extends Library\ViewHtml
{
	protected function _fetchData(Library\ViewContext $context)
	{
        if ($this->getLayout() == 'default')
		{
			$query = $this->getObject('lib:database.query.select')->table('activities')->columns('package')->distinct();
			$context->data->packages = $this->getModel()->getTable()->getDriver()->select($query, Library\Database::FETCH_FIELD_LIST);
		}

		parent::_fetchData($context);
	}
}