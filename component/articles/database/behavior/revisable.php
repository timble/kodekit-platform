<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-articles for the canonical source repository
 */

namespace Kodekit\Component\Articles;

use Kodekit\Library;
use Kodekit\Component\Revisions;

/**
 * Revisable Database Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Articles
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