<?php
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Default Toolbar Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 */
class CacheControllerToolbarDefault extends Library\ControllerToolbarModel
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