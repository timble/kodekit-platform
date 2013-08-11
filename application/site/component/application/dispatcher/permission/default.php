<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Default Dispatcher Permission
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Application
 */
class ApplicationDispatcherPermissionDefault extends Library\DispatcherPermissionAbstract
{
    /**
     * Permission handler for dispatch actions
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canDispatch()
    {
        $extension = $this->getIdentifier()->package;

        if($extension != 'application')
        {
            if (!$this->getObject('application.extensions')->isEnabled($extension)) {
                return false;
            }
        }

        return parent::canDispatch();
    }
}