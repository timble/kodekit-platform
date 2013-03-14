<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Contacts;

use Nooku\Framework;

/**
 * Contact Controller Toolbar
 *
 * @author  Isreal Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @package Nooku\Component\Contacts
 */
class ControllerToolbarContact extends \BaseControllerToolbarDefault
{
    public function onAfterControllerBrowse(Framework\Event $event)
    {    
        parent::onAfterControllerBrowse($event);
        
        $this->addSeparator();
		$this->addEnable(array('label' => 'publish'));
		$this->addDisable(array('label' => 'unpublish'));
    }  
}