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
 * Menubar Controller Toolbar
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Application
 */
class ApplicationControllerToolbarMenubar extends Library\ControllerToolbarAbstract
{
    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Library\ObjectConfig $config Configuration options
     * @return  void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'type'  => 'menubar',
        ));

        parent::_initialize($config);
    }
}

