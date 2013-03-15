<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Pages;

use Nooku\Framework;

/**
 * Abstract Orderable Database Behavior
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class DatabaseBehaviorOrderableAbstract extends Framework\DatabaseBehaviorAbstract implements DatabaseBehaviorOrderableInterface
{
    public function getMixableMethods(Framework\Object $mixer = null)
    {
        $methods = parent::getMixableMethods($mixer);
        unset($methods['is'.ucfirst($this->getIdentifier()->name)]);
        
        return $methods;
    }
}