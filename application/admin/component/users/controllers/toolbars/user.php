<?php
/**
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Framework;

/**
 * User Toolbar Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */
class ComUsersControllerToolbarUser extends ComBaseControllerToolbarDefault
{
    public function onAfterControllerBrowse(Framework\Event $event)
    {
        parent::onAfterControllerBrowse($event);
        
        $this->addSeparator();
		$this->addEnable();
		$this->addDisable();
		$this->addSeparator();
    }
}