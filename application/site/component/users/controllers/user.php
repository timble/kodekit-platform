<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * User Controller Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersControllerUser extends ComBaseControllerModel
{
    public function __construct(Framework\Config $config)
    {
        parent::__construct($config);

        $this->registerCallback(array('before.edit', 'before.add'), array($this, 'sanitizeRequest'))
             ->registerCallback('after.add', array($this, 'notify'));
	}
    
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'behaviors' => array(
                'resettable',
                'com://admin/activities.controller.behavior.loggable' => array('title_column' => 'name'),
                'activateable')));

        parent::_initialize($config);
    }

    public function getRequest()
    {
        $request = parent::getRequest();

        if($request->query->get('layout', 'alpha') == 'form') {
            $request->id = $this->getUser()->getId();
        }

        return $request;
    }

    public function _actionRender(Framework\CommandContext $context)
    {
        if($context->request->query-get('layout', 'alpha') == 'register' && $context->user->isAuthentic())
        {
            $url =  '?Itemid='.$this->getService('application.pages')->getHome()->id;
            $context->response->setRedirect($url, 'You are already registered');
            return false;
        }

        return parent::_actionRender($context);
    }

    protected function _actionAdd(Framework\CommandContext $context) {

        $params = $this->getService('application.components')->users->params;
        $context->request->data->role_id = $params->get('new_usertype', 18);

        return parent::_actionAdd($context);
    }
    
    protected function _actionEdit(Framework\CommandContext $context)
    {
        $entity = parent::_actionEdit($context);
        
        if ($context->getSubject()->getStatus() == self::STATUS_RESET) {
            $this->getService('user')->setData($entity->getData());
        }
        
        return $entity;
    }

    public function notify(Framework\CommandContext $context)
    {
        $user = $context->result;

        if ($user->getStatus() == Framework\Database::STATUS_CREATED && $user->activation) {

            $url       = $this->getService('lib://nooku/http.url',
                array('url' => "option=com_users&view=user&id={$user->id}&activation=" . $user->activation));
            $this->getService('application')->getRouter()->build($url);
            $site_url       = $context->request->getUrl()->toString(Framework\HttpUrl::SCHEME | Framework\HttpUrl::HOST | Framework\HttpUrl::PORT);
            $activation_url = $site_url . '/' . $url;

            $subject = JText::_('User Account Activation');
            $message = sprintf(JText::_('SEND_MSG_ACTIVATE'), $user->name,
                $this->getService('application')->getCfg('sitename'), $activation_url, $site_url);

            $user->notify(array('subject' => $subject, 'message' => $message));
        }
    }

    public function sanitizeRequest(Framework\CommandContext $context)
    {
        // Unset some variables because of security reasons.
        foreach(array('enabled', 'role_id', 'created_on', 'created_by', 'activation') as $variable) {
            $context->request->data->remove($variable);
        }
    }
}