<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Toolbar Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */
class ControllerToolbarFile extends Library\ControllerToolbarModel
{
    public function onBeforeControllerRender(Library\Event $event)
    {     
        parent::onBeforeControllerRender($event);
        
        $this->addCommand('upload');
        $this->addNew(array('label' => 'New Folder'));
        
        $this->addDelete();
    }
}