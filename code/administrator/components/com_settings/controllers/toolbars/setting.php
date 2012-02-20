<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Setting Controller Toolbar Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 */
class ComSettingsControllerToolbarSetting extends ComDefaultControllerToolbarDefault
{  
    public function onAfterControllerBrowse(KEvent $event)
    {
        parent::onAfterControllerBrowse($event);
        
        $this->addSave()
			 ->addApply()
			 ->addCancel(array('attribs' => array('data-novalidate' => 'novalidate')));
    }
}