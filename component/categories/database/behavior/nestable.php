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
 * Nestable Database Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Categories
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