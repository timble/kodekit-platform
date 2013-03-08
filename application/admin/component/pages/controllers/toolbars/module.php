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
 * Module Toolbar Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class ComPagesControllerToolbarModule extends ComDefaultControllerToolbarDefault
{
    public function onAfterControllerBrowse(Framework\Event $event)
    {
        parent::onAfterControllerBrowse($event);
        
        $this->addSeparator();
        $this->addEnable(array('label' => 'publish'));
        $this->addDisable(array('label' => 'unpublish'));
    }
    
    protected function _commandNew(Framework\ControllerToolbarCommand &$command)
    {
        $command = $this->getCommand('dialog', array('label' => 'new'));
        $command->href = 'option=com_pages&view=modules&layout=list&installed=1&tmpl=dialog';
    }
}