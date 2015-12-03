<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Recursive Folder Entity Iterator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Files
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