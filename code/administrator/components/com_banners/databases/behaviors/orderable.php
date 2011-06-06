<?php
/**
 * @version      $Id$
 * @category     Nooku
 * @package      Nooku_Server
 * @subpackage   Banners
 * @copyright    Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

/**
 * Banners Orderable Behavior Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */
class ComBannersDatabaseBehaviorOrderable extends ComCategoriesDatabaseBehaviorOrderable
{
    public function _buildQueryWhere(KDatabaseQuery $query)
    {
        //Implement your where query here depending on your conditions
        $parent = $this->_parent ? $this->_parent : $this->catid;
        $query->where('catid', '=', $parent);
    }

    /**
     * Reorders the old category if record has changed categories
     *
     * @param   KCommandContext Context
     */
    protected function _afterTableUpdate(KCommandContext $context)
    {
        if (isset($this->old_parent) && $this->old_parent != $this->catid )
        {
            //category has changed,
            //tidy up the old category
            $this->_parent = $this->old_parent;
            $this->reorder();
        }
    }

}