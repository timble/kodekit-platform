<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * User Controller Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersControllerUser extends ComDefaultControllerDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->registerCallback(array('before.edit', 'before.add'), array($this, 'sanitizeData'))
            ->registerCallback('after.add', array($this, 'notify'));
	}
    
    protected function _initialize(KConfig $config)
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

        if($request->layout == 'form') {
            $request->id = JFactory::getUser()->id;
        }

        return $request;
    }

    public function _actionGet(KCommandContext $context)
    {
        $user = JFactory::getUser();

        if($this->_request->layout == 'register' && !$user->guest)
        {
            $url =  'index.php?Itemid='.$this->getService('application.pages')->getHome()->id;
            $msg =  JText::_('You are already registered.');

            $context->response->setRedirect($url);
            //@TODO : Set message in session
            //$this->setRedirect($url, $msg);
            return false;
        }

        return parent::_actionGet($context);
    }

    protected function _actionAdd(KCommandContext $context) {

        $params = $this->getService('application.components')->users->params;
        $context->data->role_id = $params->get('new_usertype', 18);

        return parent::_actionAdd($context);
    }
    
    protected function _actionEdit(KCommandContext $context)
    {
        $data = parent::_actionEdit($context);
        
        if ($context->response->getStatusCode() == KHttpResponse::RESET_CONTENT) {
            JFactory::getUser()->setData($data->getData());
        }
        
        return $data;
    }

    public function notify(KCommandContext $context)
    {
        $user = $context->result;

        if ($user->getStatus() == KDatabase::STATUS_CREATED && $user->activation) {

            $url       = $this->getService('koowa:http.url',
                array('url' => "index.php?option=com_users&view=user&id={$user->id}&activation=" . $user->activation));
            $this->getService('application')->getRouter()->build($url);
            $site_url       = KRequest::url()->getUrl(KHttpUrl::SCHEME | KHttpUrl::HOST | KHttpUrl::PORT);
            $activation_url = $site_url . '/' . $url;

            $subject = JText::_('User Account Activation');
            $message = sprintf(JText::_('SEND_MSG_ACTIVATE'), $user->name,
                $this->getService('application')->getCfg('sitename'), $activation_url, $site_url);

            $user->notify(array('subject' => $subject, 'message' => $message));
        }
    }

    public function sanitizeData(KCommandContext $context)
    {
        // Unset some variables because of security reasons.
        foreach(array('enabled', 'role_id', 'created_on', 'created_by', 'activation') as $variable) {
            unset($context->data->{$variable});
        }
    }
}