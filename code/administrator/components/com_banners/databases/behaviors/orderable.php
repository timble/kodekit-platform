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
 * @author      Cristiano Cucco <http://nooku.assembla.com/profile/cristiano.cucco>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */
class ComBannersDatabaseBehaviorOrderable extends KDatabaseBehaviorOrderable
{
    public function _buildQueryWhere(KDatabaseQuery $query)
    {
        //Implement your where query here depending on your conditions
        $query->where('catid', '=', $this->catid);
        $query->where('cid'  , '=', $this->cid);
    }
}