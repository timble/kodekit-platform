<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Default Database Lockable Behavior
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComBaseDatabaseBehaviorLockable extends Framework\DatabaseBehaviorLockable
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional Framework\Config object with configuration options
     * @return void
     */
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'lifetime'   =>  $this->getService('user')->getSession()->getLifetime()
        ));

        parent::_initialize($config);
    }
}