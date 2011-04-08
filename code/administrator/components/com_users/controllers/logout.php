<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Logout Controller Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersControllerLogout extends ComDefaultControllerView
{
    protected function _actionLogout(KCommandContext $data)
    {
        $result = KFactory::get('lib.joomla.application')->logout();
       
        if(!JError::isError($result)) {
            $this->_redirect = 'index.php?option=com_users&view=login';
        } else {
            $this->setRedirect(KRequest::referrer(), $result->getError(), 'error');
        }
    }
}