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
 * Default Controller Toolbar
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Cache
 */
class CacheControllerToolbarDefault extends Library\ControllerToolbarActionbar
{
    public function getCommands()
    {
        $this->addPurge();
		     
        return parent::getCommands();
    }
     
    protected function _commandPurge(Library\ControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs' => array(
                'data-novalidate' =>'novalidate',
                'data-action'     => 'purge'
            )
        ));
    }
}