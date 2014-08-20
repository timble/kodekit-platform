<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Users;

/**
 * Session Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Users
 */
class UsersControllerSession extends Users\ControllerSession
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        //Authorize the user before adding
        $this->addCommandCallback('after.add'  , '_passwordRedirect');
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'com:activities.controller.behavior.loggable' => array('title_column' => 'name')
            )
        ));

        parent::_initialize($config);
    }

    protected function _passwordRedirect(Library\ControllerContextInterface $context)
    {
        if ($context->result !== false)
        {
            $user     = $context->user;
            $password = $this->getObject('com:users.model.passwords')
                ->id($user->getEmail())
                ->fetch();

            if ($password->expired())
            {
                $pages  = $this->getObject('application.pages');

                $page = $pages->find(array(
                    'component' => 'users',
                    'link'      => array(array('view' => 'user'))));

                if ($page)
                {
                    $url                  = $page->getLink();
                    $url->query['layout'] = 'password';
                    $url->query['id']     = $user->getId();

                    $this->getObject('application')->getRouter()->build($url);
                    $context->response->setRedirect($url,
                        $this->getObject('translator')->translate('Your password has expired. Please set a new password'), 'notice');
                }
            }
        }
    }

    protected function _actionAdd(Library\ControllerContextInterface $context)
    {
        $result = parent::_actionAdd($context);

        //Set the session data
        if($context->response->isSuccess())
        {
            $context->user->getSession()->site = $this->getObject('application')->getSite();

            $url = $this->getObject('application.pages')->getHome()->getLink();
            $this->getObject('application')->getRouter()->build($url);

            $context->response->setRedirect($url);
        }

        return $result;
    }
}