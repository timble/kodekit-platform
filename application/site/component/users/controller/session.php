<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Users;

/**
 * Session Controller
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Users
 */
class UsersControllerSession extends Users\ControllerSession
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        //Authorize the user before adding
        $this->addCommandCallback('after.add'  , '_resetPassword');
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

    protected function _resetPassword(Library\ControllerContextInterface $context)
    {
        if ($context->result !== false)
        {
            $user     = $context->user;
            $password = $this->getObject('com:users.database.row.password')->set('id', $user->getEmail())->load();

            if ($password->expired())
            {
                $extension = $this->getObject('application.extensions')->getExtension('users');
                $pages     = $this->getObject('application.pages');

                $page = $pages->find(array(
                    'extensions_extension_id' => $extension->id,
                    'link'                    => array(array('view' => 'user'))));

                $url                  = $page->getLink();
                $url->query['layout'] = 'password';
                $url->query['id']     = $user->getId();

                $this->getObject('application')->getRouter()->build($url);
                $this->getObject('application')->redirect($url);
            }
        }
    }

    protected function _actionAdd(Library\ControllerContextInterface $context)
    {
        $session = $context->user->getSession();

        //Insert the session into the database
        if(!$session->isActive()) {
            throw new Library\ControllerExceptionActionFailed('Session could not be stored. No active session');
        }

        //Fork the session to prevent session fixation issues
        $session->fork();

        //Prepare the data
        $data = array(
            'id'          => $session->getId(),
            'guest'       => !$context->user->isAuthentic(),
            'email'       => $context->user->getEmail(),
            'data'        => '',
            'time'        => time(),
            'application' => 'site',
        );

        $context->request->data->add($data);

        //Store the session
        $entity = parent::_actionAdd($context);

        //Set the session data
        $session->site = $this->getObject('application')->getSite();

        //Redirect to caller
        $context->response->setRedirect($context->request->getReferrer());

        return $entity;
    }

    protected function _actionDelete(Library\ControllerContextInterface $context)
    {
        //Force logout from site only
        $context->request->query->application = array('site');

        return parent::_actionDelete($context);
    }
}