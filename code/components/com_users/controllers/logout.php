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
class ComUsersControllerLogout extends ComDefaultControllerPage
{
    protected function _actionLogout(KCommandContext $context)
    {
		$result = KFactory::get('lib.joomla.application')->logout();

		if(!JError::isError($result)) {
		    $this->_redirect = 'index.php?Itemid='.JSite::getMenu()->getDefault()->id;
		} else {
		    $this->setRedirect(KRequest::referrer(), $result->getError(), 'error');
		}
    }
}