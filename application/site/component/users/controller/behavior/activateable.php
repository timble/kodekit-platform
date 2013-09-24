<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library, Nooku\Component\Users;

/**
 * Activateable Controller Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Users
 */
class UsersControllerBehaviorActivatable extends Users\ControllerBehaviorActivatable
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $parameters = $this->getObject('application.extensions')->users->params;

        $config->append(array(
            'enable' => $parameters->get('useractivation', '1')
        ));

        parent::_initialize($config);
    }

    protected function _afterControllerAdd(Library\CommandContext $context)
    {
        $user = $context->result;

        if ($user->getStatus() == Library\Database::STATUS_CREATED && $user->activation)
        {
            $url = $context->request->getUrl()
                ->toString(Library\HttpUrl::SCHEME | Library\HttpUrl::HOST | Library\HttpUrl::PORT) . $this->_getActivationUrl();

            $translator = $this->getObject('translator');

            $subject = $translator->translate('User Account Activation');
            $message = $translator->translate('SEND_MSG_ACTIVATE', array(
                'name' => $user->name,
                'site' => $this->getObject('application')->getCfg('sitename'),
                'url'  => $url
            ));

            if ($user->notify(array('subject' => $subject, 'message' => $message))) {
                $context->response->addMessage($translator->translate('Activation E-mail sent'));
            } else {
                $context->reponse->addMessage($translator->translate('Failed to send activation E-mail', 'error'));
            }
        }
    }

    protected function _getActivationUrl()
    {
        $user = $this->getModel()->getRow();

        $extension = $this->getObject('application.extensions')->getExtension('users');
        $page      = $this->getObject('application.pages')->find(array(
            'extensions_extension_id' => $extension->id,
            'access'                  => 0,
            'link'                    => array(array('view' => 'user'))));

        $url                      = $page->getLink();
        $url->query['activation'] = $user->activation;
        $url->query['uuid']       = $user->uuid;

        $this->getObject('application')->getRouter()->build($url);

        return $url;
    }
}