<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Component\Pages;

/**
 * Flat Orderable Database Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Component\Articles
 */
class ArticlesDatabaseBehaviorOrderableFlat extends Pages\DatabaseBehaviorOrderableFlat
{
    public function _buildQuery($query)
    {
        parent::_buildQuery($query);

        if ($this->getMixer()->getIdentifier()->name == 'article') 
        {
            $query->where('categories_category_id = :category')
                  ->where('published >= :published')
                  ->bind(array('category' => $this->categories_category_id, 'published' => 0));

        }
    }
}