<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-files for the canonical source repository
 */

namespace Kodekit\Component\Files;

use Kodekit\Library;

/**
 * Recursive Folder Entity Iterator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Files
 */
class ModelIteratorFolder extends \RecursiveIteratorIterator
{
    public function __construct(ModelEntityFolders $nodes, $mode = \RecursiveIteratorIterator::SELF_FIRST, $flags = 0)
    {
        parent::__construct($nodes, $mode, $flags);
    }

    public function callGetChildren()
    {
        return $this->current()->getChildren()->getIterator();
    }

    public function callHasChildren()
    {
        return $this->current()->hasChildren();
    }
}