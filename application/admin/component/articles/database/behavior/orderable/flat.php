<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Articles;

use Kodekit\Component\Pages;

/**
 * Flat Orderable Database Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Articles
 */
class DatabaseBehaviorOrderableFlat extends Pages\DatabaseBehaviorOrderableFlat
{
    public function _buildQuery($query)
    {
        parent::_buildQuery($query);

        if ($this->getMixer()->getIdentifier()->name == 'article')
        {
            $query->where('tbl.categories_category_id = :category')
                  ->where('tbl.published >= :published')
                  ->bind(array('category' => $this->categories_category_id, 'published' => 0));

        }
    }
}