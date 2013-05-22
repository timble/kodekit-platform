<?php
/**
 * Created by JetBrains PhpStorm.
 * User: arunasmazeika
 * Date: 17/04/13
 * Time: 11:58
 * To change this template use File | Settings | File Templates.
 */

use Nooku\Library, Nooku\Component\Users;

class UsersControllerBehaviorActivateable extends Users\ControllerBehaviorActivateable
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $parameters = $this->getObject('application.components')->users->params;

        $config->append(array(
            'enable' => $parameters->get('useractivation', '1')
        ));

        parent::_initialize($config);
    }

    protected function _afterControllerAdd(Library\CommandContext $context)
    {
        $user = $context->result;

        if ($user->getStatus() == Library\Database::STATUS_CREATED && $user->activation) {

            $url = $context->request->getUrl()
                ->toString(Library\HttpUrl::SCHEME | Library\HttpUrl::HOST | Library\HttpUrl::PORT) . $this->_getActivationUrl();

            // TODO Uncomment and fix after Langauge support is re-factored.
            //$subject = JText::_('User Account Activation');
            //$message = sprintf(JText::_('SEND_MSG_ACTIVATE'), $user->name,
            //    $this->getObject('application')->getCfg('sitename'), $url, $site_url);
            $subject = 'User Account Activation';
            $message = $url;

            if ($user->notify(array('subject' => $subject, 'message' => $message))) {
                $context->user->addFlashMessage('Activation E-mail sent');
            } else {
                $context->user->addFlashMessage('Failed to send activation E-mail', 'error');
            }
        }
    }

    protected function _getActivationUrl()
    {
        $user = $this->getModel()->getRow();

        $component = $this->getObject('application.components')->getComponent('users');
        $page      = $this->getObject('application.pages')->find(array(
            'extensions_component_id' => $component->id,
            'access'                  => 0,
            'link'                    => array(array('view' => 'user'))));

        $url                      = $page->getLink();
        $url->query['activation'] = $user->activation;
        $url->query['uuid']       = $user->uuid;

        $this->getObject('application')->getRouter()->build($url);

        return $url;
    }
}