<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Abstract Orderable Database Behavior Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesDatabaseBehaviorOrderableAbstract extends Framework\DatabaseBehaviorAbstract implements ComPagesDatabaseBehaviorOrderableInterface
{
    public function getMixableMethods(Framework\Object $mixer = null)
    {
        $methods = parent::getMixableMethods($mixer);
        unset($methods['is'.ucfirst($this->getIdentifier()->name)]);
        
        return $methods;
    }
}