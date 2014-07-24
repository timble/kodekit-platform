<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Dispatcher Route
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Application
 */
class DispatcherRouterRoute extends Library\DispatcherRouterRoute
{
    public function toString($parts = self::FULL)
    {
        //Add the option to the query for compatibility with the Joomla router
        if(isset($this->query['component']))
        {
            if(!isset($this->query['option'])) {
                $this->query['option'] = 'com_'.$this->query['component'];
            }

            unset($this->query['component']);
        }

        if(isset($this->query['option'])) {
            $this->getObject('application')->getRouter()->build($this);
        }

        return parent::toString($parts);
    }
}