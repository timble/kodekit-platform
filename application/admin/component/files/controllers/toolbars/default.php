<?php
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Toolbar Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */
class ComFilesControllerToolbarDefault extends ComBaseControllerToolbarDefault
{
    public function onBeforeControllerRender(Framework\Event $event)
    {     
        parent::onBeforeControllerRender($event);
        
        $this->addCommand('upload');
        $this->addNew(array('label' => 'New Folder'));
        
        $this->addDelete();
    }
}