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

        $this->registerCallback('before.edit', array($this, 'sanitizeData'))
             ->registerCallback('before.add' , array($this, 'sanitizeData'))
             ->registerCallback('after.add'  , array($this, 'notify'))
             ->registerCallback('after.read' , array($this, 'activate'));
    }

    public function activate(KCommandContext $context)
    {
    	$row = $context->result;
    	$activation = $context->caller->getModel()->get('activation');
    	if (!empty($activation)) {
    		if ($row->id && $row->activation === $activation) {
	    		$row->activation = '';
	    		$row->enabled = 1;

	    		if ($row->save()) {
	    			return KFactory::get('lib.joomla.application')->redirect(JURI::root(), JText::_('REG_ACTIVATE_COMPLETE'), 'message');
	    		}
    		}

    		return KFactory::get('lib.joomla.application')->redirect(JURI::root(), JText::_('REG_ACTIVATE_NOT_FOUND'), 'error');

    	}
    }

    public function getRequest()
    {
        $request = parent::getRequest();

        if($request->layout == 'form') {
            $request->id = KFactory::get('lib.joomla.user')->id;
        }

        return $request;
    }

    public function _actionGet(KCommandContext $context)
    {
        $user = KFactory::get('lib.joomla.user');

        if($this->_request->layout == 'register' && !$user->guest)
        {
            $url =  'index.php?Itemid='.JSite::getMenu()->getDefault()->id;
            $msg =  JText::_('You are already registered.');

            $this->setRedirect($url, $msg);
            return false;
        }

        return parent::_actionGet($context);
    }

    protected function _actionAdd(KCommandContext $context)
    {
    	$parameters = JComponentHelper::getParams('com_users');

        if(!($group_name = $parameters->get('new_usertype'))) {
            $group_name = 'Registered';
        }

        $context->data->id             = 0;
        $context->data->group_name     = $group_name;
        $context->data->users_group_id = KFactory::get('lib.joomla.acl')->get_group_id('', $group_name, 'ARO');
        $context->data->registered_on  = KFactory::get('lib.joomla.date')->toMySQL();

        if($parameters->get('useractivation') == '1')
        {
            $password = KFactory::get('site::com.users.helper.password');

            $context->data->activation = $password->getHash($password->getRandom());
            $context->data->enabled = 0;

            $message = JText::_('REG_COMPLETE_ACTIVATE');
        }
        else $message = JText::_('REG_COMPLETE');

        $this->setRedirect('index.php?Itemid='.JSite::getMenu()->getDefault()->id, $message);

        return parent::_actionAdd($context);
    }

    public function notify(KCommandContext $context)
    {
        $config = KFactory::get('lib.joomla.config');

        $subject = sprintf(JText::_('Account details for'), $context->data->name, $config->getValue('sitename'));
        $subject = html_entity_decode($subject, ENT_QUOTES);

        $parameters     = JComponentHelper::getParams('com_users');
        $site_name      = $config->getValue('sitename');
        $site_url       = KRequest::url()->get(KHttpUrl::SCHEME | KHttpUrl::HOST | KHttpUrl::PORT);
        $activation_url = $site_url.JRoute::_('index.php?option=com_users&view=user&activation='.$context->data->activation);
        $password       = preg_replace('/[\x00-\x1F\x7F]/', '', $context->data->password);

        if($parameters->get('useractivation') == 1 ) {
            $message = sprintf(JText::_('SEND_MSG_ACTIVATE'), $context->data->name, $site_name, $activation_url, $site_url, $context->data->username, $password);
        } else {
            $message = sprintf(JText::_('SEND_MSG'), $context->data->name, $site_name, $site_url);
        }

        $message = html_entity_decode($message, ENT_QUOTES);

        $super_administrators = KFactory::tmp('site::com.users.model.users')
            ->set('group_name', 'super administrator')
            ->set('limit', 0)
            ->getList();

        $from_email = $config->getValue('mailfrom');
        $from_name  = $config->getValue('fromname');

        if(!$from_email || !$from_name )
        {
            $from_email = $super_administrators[0]->email;
            $from_name  = $super_administrators[0]->name;
        }

        JUtility::sendMail($from_email, $from_name, $context->data->email, $subject, $message);

        //Send email to super administrators
        foreach($super_administrators as $super_administrator)
        {
            if($super_administrator->send_mail)
            {
                $message = sprintf(JText::_('SEND_MSG_ADMIN'), $context->data->name, $site_name, $context->data->name, $context->data->email, $context->data->username);
                $message = html_entity_decode($message, ENT_QUOTES);

                JUtility::sendMail($from_email, $from_name, $super_administrator->email, $subject, $message);
            }
        }
    }

    public function sanitizeData(KCommandContext $context)
    {
        // Unset some variables because of security reasons.
        foreach(array('enabled', 'group_id', 'group_name', 'registered_on', 'activation') as $variable) {
            unset($context->data->{$variable});
        }

        // @TODO: Remove this foreach when we drop legacy.
        foreach(array('gid', 'block', 'usertype', 'registerDate') as $variable) {
            unset($context->data->{$variable});
        }
    }
}