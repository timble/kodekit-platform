<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Orderable Database Behavior Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComArticlesDatabaseBehaviorOrderable extends KDatabaseBehaviorOrderable
{
    public function _buildQueryWhere(KDatabaseQuery $query)
    {
        if($this->mixer->getIdentifier()->name == 'article')
        {
            $query->where('catid', '=', $this->category_id)
                  ->where('state', '>=', 0);
        }
    }
}