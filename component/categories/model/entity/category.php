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
 * Category Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Categories
 */
class ModelEntityCategory extends Library\ModelEntityRow
{
    /**
     * Return an associative array of the data
     *
     * Add the children to a 'children' property
     *
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();

        if($this->isRecursable() && $this->hasChildren())  {
            $data['children'] = array_values($this->getChildren()->toArray());
        }

        return $data;
    }
}