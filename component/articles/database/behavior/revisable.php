<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Articles;

use Nooku\Library;
use Nooku\Component\Revisions;

/**
 * Revisable Database Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Articles
 */
class DatabaseBehaviorRevisable extends Revisions\DatabaseBehaviorRevisable
{
    protected function _selectRevisions($table, $status, Library\DatabaseQueryInterface $query)
    {
        $result = parent::_selectRevisions($table, $status, $query);

        //Filter the rowset based on the category id
        if($query->params->has('categories_category_id'))
        {
            $needle = array();
            $needle['categories_category_id'] = $query->params->get('categories_category_id');

            $result = $result->find($needle);
        }

        return $result;
    }
}