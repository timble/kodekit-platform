<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Categories;

use Nooku\Library;

/**
 * Cascadable Database Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Categories
 */
class DatabaseBehaviorCascadable extends Library\DatabaseBehaviorAbstract
{
    protected function _beforeDelete(Library\DatabaseContextInterface $context)
    {
        $result = true;

        $table      = $this->table;
        $identifier = 'com:'.$table.'.database.table.'.$table;

        $entity = $this->getObject($identifier)->select(array('categories_category_id' => $this->id));

        if($entity->count()) {
            $result = $entity->delete();
        }

        return $result;
    }
}