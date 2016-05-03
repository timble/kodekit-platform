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
 * Category Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Categories
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
            $data['children'] = array_values(iterator_to_array($this->getChildren()));
        }

        return $data;
    }
}