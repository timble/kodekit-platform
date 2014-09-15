<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Categories;

use Nooku\Library;

/**
 * Cascadable Database Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
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