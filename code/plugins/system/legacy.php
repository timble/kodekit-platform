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
 * Legacy Plugin
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
jimport('joomla.plugin.plugin');

class plgSystemLegacy extends JPlugin
{
    public function onAfterRoute()
    {
        $application = JFactory::getApplication();

        if($application->isSite())
        {
            switch(JRequest::getVar('option', null, 'method', 'cmd'))
            {
                case 'com_user':
                    JRequest::setVar('option', 'com_users', 'get');

                    switch(JRequest::getVar('view', null, 'method', 'cmd'))
                    {
                        case 'login':
                            if(!JFactory::getUser()->guest) {
                                JRequest::setVar('view', 'logout', 'get');
                            }

                            break;

                        case 'remind':
                            JRequest::setVar('view', 'remind', 'get');

                            break;

                        case 'register':
                            JRequest::setVar('view', 'user', 'get');
                            JRequest::setVar('layout', 'register', 'get');

                            break;

                        case 'user':
                            if(JRequest::getVar('task', null, 'get', 'cmd') == 'edit') {
                                JRequest::setVar('layout', 'form', 'get');
                            }
                    }

                    switch(JRequest::getVar('task', null, 'method', 'cmd'))
                    {
                        case 'login':
                            JRequest::setVar('view', 'login', 'get');
                            JRequest::setVar('action', 'login', 'post');
                            JRequest::setVar('password', JRequest::getVar('passwd', null, 'method', 'none'), 'post');

                            break;
                    }

                    break;
            }

            if(JRequest::getMethod() == 'POST')
            {
                $token = JUtility::getToken();

                if(JRequest::getVar($token, null, 'post', 'alnum') == 1) {
                    JRequest::setVar('_token', $token, 'post');
                }
            }
        }
    }
}