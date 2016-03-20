<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Abstract Orderable Database Behavior
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Pages
 */
class DatabaseBehaviorOrderableAbstract extends Library\DatabaseBehaviorAbstract implements DatabaseBehaviorOrderableInterface
{
    public function getMixableMethods($exclude = array())
    {
        $exclude = array_merge($exclude, array('is'.ucfirst($this->getIdentifier()->name)));
        return parent::getMixableMethods($exclude);
    }
}