<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Abstract Orderable Database Behavior
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class DatabaseBehaviorOrderableAbstract extends Library\DatabaseBehaviorAbstract implements DatabaseBehaviorOrderableInterface
{
    public function getMixableMethods($exclude = array())
    {
        $exclude = array_merge($exclude, array('is'.ucfirst($this->getIdentifier()->name)));
        return parent::getMixableMethods($exclude);
    }
}