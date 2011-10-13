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
 * Login Controller Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersControllerLogin extends ComDefaultControllerResource
{
    protected function _actionLogin(KCommandContext $context)
    {
        if($return = KRequest::get('post.return', 'base64'))
        {
            $return = base64_decode($return);

            if(!JURI::isInternal($return)) {
                $return = '';
            }
        }

        $options = array(
            'return'   => $return
        );

        $credentials = array(
            'username' => KRequest::get('post.username', 'string'),
            'password' => KRequest::get('post.password', 'raw')
        );

        $result = JFactory::getApplication()->login($credentials, $options);

        if(!JError::isError($result))
        {
            if(!$return) {
                $return = 'index.php?option=com_users&view=user';
            }

            $this->_redirect = $return;
        }
        else
        {
            if(!$return) {
                $return	= 'index.php?option=com_users&view=login';
            }

            $this->setRedirect($return, $result->getError(), 'error');
        }
    }

	public function getView()
	{
		$view = parent::getView();

		if ($view) 
		{
		    $return = $this->getService('koowa:filter.base64')->sanitize($this->_request->return);
			$view->assign('return', $return);
		}

		return $view;
	}
}