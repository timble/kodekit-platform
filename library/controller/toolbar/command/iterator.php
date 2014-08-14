<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Controller Toolbar Command Iterator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Controller
 */
class ControllerToolbarCommandIterator extends \RecursiveIteratorIterator
{
    public function __construct(ControllerToolbarInterface $toolbar, $mode = \RecursiveIteratorIterator::SELF_FIRST, $flags = 0)
    {
        parent::__construct($toolbar, $mode, $flags);
    }

    public function callGetChildren()
    {
        return $this->current()->getIterator();
    }

    public function callHasChildren()
    {
        return count($this->current());
    }
}