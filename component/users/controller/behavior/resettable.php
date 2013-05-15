<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright      Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Resettable Controller Behavior
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Users
 */
class ControllerBehaviorResettable extends Library\ControllerBehaviorAbstract
{
    /**
     * @var string The token filter.
     */
    protected $_filter;

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        //@TODO Remove when PHP 5.5 becomes a requirement.
        //Library\ClassLoader::getInstance()->loadFile(JPATH_ROOT . '/component/users/legacy.php');

        $this->_filter = $config->filter;
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array('filter' => 'alnum'));
        parent::_initialize($config);
    }

    protected function _beforeControllerRead(Library\CommandContext $context)
    {
        if ($token = $context->request->query->get('token', $this->_filter)) {
            // Push the token to the view.
            $this->getView()->token = $token;
        }
    }

    protected function _beforeControllerReset(Library\CommandContext $context)
    {
        $user     = $this->getModel()->getRow();
        $password = $user->getPassword();

        if (!$user->isNew() && $this->_tokenValid($context->request->data->get('token', $this->_filter), $password))
        {
            $context->password = $password;
            $result            = true;
        }
        else
        {
            $url = $this->getObject('application.pages')->getHome()->getLink();
            $this->getObject('application')->getRouter()->build($url);

            $context->user->addFlashMessage(JText::_('INVALID_REQUEST'), 'error');
            $context->response->setRedirect($url);

            $result = false;
        }

        return $result;
    }

    protected function _actionReset(Library\CommandContext $context)
    {
        $password = $context->password;

        $password->password = $context->request->data->get('password', 'string');
        $password->save();

        if ($password->getStatus() == Library\Database::STATUS_FAILED)
        {
            $context->user->message($password->getStatusMessage(), 'error');
            $context->response->setRedirect($context->request->getReferrer());

            $result = false;
        }
        else
        {
            $url = $this->getObject('application.pages')->getHome()->getLink();
            $this->getObject('application')->getRouter()->build($url);

            $context->user->message(JText::_('PASSWORD_RESET_SUCCESS'));
            $context->response->setRedirect($url);

            $result = true;
        }

        return $result;
    }

    protected function _tokenValid($token, DatabaseRowPassword $password)
    {
        $result = false;

        if ($password->reset && ($password->verify($token, $password->reset))) {
            $result = true;
        }

        return $result;
    }

    protected function _beforeControllerToken(Library\CommandContext $context)
    {
        $user = $this->getObject('com:users.model.users')
            ->set('email', $context->request->data->get('email', 'email'))
            ->getRow();

        if ($user->isNew() || !$user->enabled)
        {
            $context->user->addFlashMessage(\JText::_('COULD_NOT_FIND_USER'), 'error');
            $context->response->setRedirect($context->request->getReferrer());

            $result = false;
        }
        else
        {
            $context->user = $user;
            $result        = true;
        }

        return $result;
    }

    protected function _actionToken(Library\CommandContext $context)
    {
        $result = true;

        $user  = $context->user;
        $token = $user->getPassword()->setReset();

        $component = $this->getObject('application.components')->getComponent('users');
        $page      = $this->getObject('application.pages')->find(array(
            'extensions_component_id' => $component->id,
            'access'                  => 0,
            'link'                    => array(array('view' => 'user'))));

        $url                  = $page->getLink();
        $url->query['layout'] = 'password';
        $url->query['token']  = $token;
        $url->query['uuid']   = $user->uuid;

        $this->getObject('application')->getRouter()->build($url);

        $url = $context->request->getUrl()
            ->toString(Library\HttpUrl::SCHEME | Library\HttpUrl::HOST | Library\HttpUrl::PORT) . $url;

        $site_name = \JFactory::getConfig()->getValue('sitename');

        $subject = \JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TITLE', $site_name);
        // TODO Fix when language package is re-factored.
        //$message    = \JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TEXT', $site_name, $url);
        $message = $url;

        if (!$user->notify(array('subject' => $subject, 'message' => $message)))
        {
            $context->user->addFlashMessage(JText::_('ERROR_SENDING_CONFIRMATION_EMAIL'), 'error');
            $context->response->setRedirect($context->request->getReferrer());

            $result = false;
        }

        return $result;
    }
}