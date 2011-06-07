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
        $option      = JRequest::getVar('option', null, 'method', 'cmd');
        $view        = JRequest::getVar('view', null, 'method', 'cmd');
        $layout      = JRequest::getVar('layout', null, 'method', 'cmd');
        $task        = JRequest::getVar('task', null, 'method', 'cmd');

        if($application->isSite())
        {
            switch($option)
            {
                case 'com_user':
                    JRequest::setVar('option', 'com_users', 'get');

                    switch($view)
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
                            if($task == 'edit') {
                                JRequest::setVar('layout', 'form', 'get');
                            }
                    }

                    switch($task)
                    {
                        case 'login':
                            JRequest::setVar('view', 'login', 'get');
                            JRequest::setVar('action', 'login', 'post');
                            JRequest::setVar('password', JRequest::getVar('passwd', null, 'method', 'none'), 'post');

                            break;
                    }

                    break;

                case 'com_content':
                    JRequest::setVar('option', 'com_articles', 'get');

                    switch($view)
                    {
                        case 'frontpage':
                            JRequest::setVar('view', 'articles', 'get');
                            JRequest::setVar('featured', 'true', 'get');

                            break;

                        case 'category':
                            JRequest::setVar('view', 'articles', 'get');
                            JRequest::setVar('category', JRequest::getVar('id', null, 'get', 'int'));
                            JRequest::setVar('id', null, 'get');

                            switch($layout)
                            {
                                case 'blog':
                                    JRequest::setVar('layout', 'category_blog', 'get');
                                    break;

                                default:
                                    JRequest::setVar('layout', 'category_default', 'get');
                                    break;
                            }
                            break;

                        case 'section':
                            JRequest::setVar('view', 'articles', 'get');
                            JRequest::setVar('section', JRequest::getVar('id', null, 'get', 'int'));
                            JRequest::setVar('id', null, 'get');

                            switch($layout)
                            {
                                case 'blog':
                                    JRequest::setVar('layout', 'section_blog', 'get');
                                    break;

                                default:
                                    JRequest::setVar('layout', 'section_default', 'get');
                                    break;
                            }
                            break;

                        case 'archive':
                            JRequest::setVar('view', 'articles', 'get');
                            JRequest::setVar('layout', 'archived', 'get');
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