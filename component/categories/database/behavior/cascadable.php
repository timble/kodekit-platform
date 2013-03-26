<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */


namespace Nooku\Component\Categories;

use Nooku\Framework;

/**
 * Cascadable Database Behavior Class
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Categories
 */
class DatabaseBehaviorCascadable extends Framework\DatabaseBehaviorAbstract
{
    protected function _beforeTableDelete(Framework\CommandContext $context)
    {
        $result = true;

        $table      = $this->table;
        $identifier = 'com:'.$table.'.database.table.'.$table;

        $rowset = $this->getService($identifier)->select(array('categories_category_id' => $this->id));

        if($rowset->count()) {
            $result = $rowset->delete();
        }

        return $result;
    }
}