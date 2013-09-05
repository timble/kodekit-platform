<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
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

        $this->_filter = $config->filter;
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array('filter' => 'alnum'));
        parent::_initialize($config);
    }

    protected function _beforeControllerReset(Library\CommandContext $context)
    {
        $result = true;

        if ($this->getModel()->getRow()->isNew() || !$this->_isTokenValid($context))
        {
            $url = $this->getObject('application.pages')->getHome()->getLink();
            $this->getObject('application')->getRouter()->build($url);

            $context->response->setRedirect($url, \JText::_('INVALID_REQUEST'), 'error');
            $result = false;
        }

        return $result;
    }

    protected function _actionReset(Library\CommandContext $context)
    {
        $result = true;

        $password           = $this->getModel()->getRow()->getPassword();
        $password->password = $context->request->data->get('password', 'string');
        $password->save();

        if ($password->getStatus() == Library\Database::STATUS_FAILED)
        {
            $context->error = $password->getStatusMessage();
            $result         = false;
        }

        return $result;
    }

    protected function _beforeControllerToken(Library\CommandContext $context)
    {
        $row = $this->getObject('com:users.model.users')
               ->email($context->request->data->get('email', 'email'))
               ->getRow();

        if ($row->isNew() || !$row->enabled)
        {
            $url = $context->request->getReferrer();
            $context->response->setRedirect($url, \JText::_('COULD_NOT_FIND_USER'), 'error');
            $result = false;
        }
        else
        {
            $context->row = $row;
            $result       = true;
        }

        return $result;
    }

    protected function _isTokenValid(Library\CommandContext $context)
    {
        $result = false;

        $password = $this->getModel()->getRow()->getPassword();
        $hash     = $password->reset;
        $token    = $context->request->data->get('token', $this->_filter);

        if ($hash && ($password->verify($token, $hash))) {
            $result = true;
        }

        return $result;
    }

    protected function _actionToken(Library\CommandContext $context)
    {
        $result = true;

        $row   = $context->row;
        $token = $row->getPassword()->setReset();

        $extension = $this->getObject('application.extensions')->getExtension('users');
        $page      = $this->getObject('application.pages')->find(array(
            'extensions_extension_id' => $extension->id,
            'access'                  => 0,
            'link'                    => array(array('view' => 'user'))));

        $url                  = $page->getLink();
        $url->query['layout'] = 'password';
        $url->query['token']  = $token;
        $url->query['uuid']   = $row->uuid;

        $this->getObject('application')->getRouter()->build($url);

        $url = $context->request->getUrl()
               ->toString(Library\HttpUrl::SCHEME | Library\HttpUrl::HOST | Library\HttpUrl::PORT) . $url;

        $site_name = \JFactory::getConfig()->getValue('sitename');

        $subject = \JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TITLE', $site_name);
        // TODO Fix when language package is re-factored.
        //$message    = \JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TEXT', $site_name, $url);
        $message = $url;

        if (!$row->notify(array('subject' => $subject, 'message' => $message))) {
            $result = false;
        }

        return $result;
    }
}
