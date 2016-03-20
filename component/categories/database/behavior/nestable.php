<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-categories for the canonical source repository
 */

namespace Kodekit\Component\Categories;

use Kodekit\Library;

/**
 * Nestable Database Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Categories
 */
class DatabaseBehaviorNestable extends Library\DatabaseBehaviorAbstract
{
    /**
     * Get the node path
     *
     * @return  array
     */
    public function getPath()
    {
        return array_map('intval', explode('/', $this->path));
    }

    /**
     * Get the node level
     *
     * @return  int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Get the node parent identifier
     *
     * @return int
     */
    public function getParent()
    {
        return $this->parent_id;
    }

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