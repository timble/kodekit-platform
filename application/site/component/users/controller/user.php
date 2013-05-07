<?php
/**
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * User Controller Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Users
 */
class UsersControllerUser extends ApplicationControllerDefault
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->registerCallback(array('before.edit', 'before.add'), array($this, 'sanitizeRequest'))
             ->registerCallback('after.add', array($this, 'redirect'));
	}
    
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'resettable', 'activateable',
                'com:activities.controller.behavior.loggable' => array('title_column' => 'name'),
        )));

        parent::_initialize($config);
    }

    public function getRequest()
    {
        $request = parent::getRequest();

        // Set request so that actions are made against logged user if none was given.
        if (!$request->query->get('id','int') && ($id = $this->getUser()->getId())) {
            $request->query->id = $id;
        }

        return $request;
    }

    public function sanitizeRequest(Library\CommandContext $context)
    {
        // Unset some variables because of security reasons.
        foreach(array('enabled', 'role_id', 'created_on', 'created_by', 'activation') as $variable) {
            $context->request->data->remove($variable);
        }
    }

    protected function _actionAdd(Library\CommandContext $context)
    {
        $params = $this->getObject('application.components')->users->params;
        $context->request->data->role_id = $params->get('new_usertype', 18);

        return parent::_actionAdd($context);
    }

    protected function _actionEdit(Library\CommandContext $context)
    {
        $entity = parent::_actionEdit($context);

        $user = $this->getObject('user');

        // Logged user changed. Updated in memory/session user object.
        if ($context->response->getStatusCode() == self::STATUS_RESET && $entity->id == $user->getId()) {
            $user->values($entity->getSessionData($user->isAuthentic()));
        }
    }

    public function redirect(Library\CommandContext $context)
    {
        $user = $context->result;

        if ($user->getStatus() == Library\Database::STATUS_CREATED)
        {
            $url = $this->getObject('application.pages')->getHome()->getLink();
            $this->getObject('application')->getRouter()->build($url);

            $context->response->setRedirect($url);
        }
    }
}