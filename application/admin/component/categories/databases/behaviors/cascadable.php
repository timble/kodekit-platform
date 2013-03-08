<?php
/**
 * @package      Nooku_Server
 * @subpackage   Articles
 * @copyright    Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Cascadable Database Behavior Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Articles
 */
class ComCategoriesDatabaseBehaviorCascadable extends Framework\DatabaseBehaviorAbstract
{
    protected function _beforeTableDelete(Framework\CommandContext $context)
    {
        $result = true;

        $table      = $this->table;
        $identifier = 'com://admin/'.$table.'.database.table.'.$table;

        $rowset = $this->getService($identifier)->select(array('categories_category_id' => $this->id));

        if($rowset->count()) {
            $result = $rowset->delete();
        }

        return $result;
    }
}