<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Resettable Controller Behavior
 *
 * @author     Arunas Mazeika <http://github.com/amazeika>
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
        $config->append(array(
            'filter' => 'alnum'
        ));

        parent::_initialize($config);
    }

    protected function _beforeReset(Library\ControllerContextInterface $context)
    {
        $result = true;

        if ($this->getModel()->fetch()->isNew() || !$this->_isTokenValid($context))
        {
            $result = false;
        }

        return $result;
    }

    protected function _actionReset(Library\ControllerContextInterface $context)
    {
        $result = true;

        $password           = $this->getModel()->fetch()->getPassword();
        $password->password = $context->request->data->get('password', 'string');
        $password->save();

        if ($password->getStatus() == $password::STATUS_FAILED)
        {
            $context->error = $password->getStatusMessage();
            $result = false;
        }

        return $result;
    }

    protected function _beforeToken(Library\ControllerContextInterface $context)
    {
        $result = false;

        $entity = $this->getObject('com:users.model.users')
                    ->email($context->request->data->get('email', 'email'))
                    ->fetch();

        if (!$entity->isNew())
        {
            $context->entity = $entity;
            $result          = true;
        }

        return $result;
    }

    protected function _isTokenValid(Library\ControllerContextInterface $context)
    {
        $result = false;

        $password = $this->getModel()->fetch()->getPassword();
        $hash     = $password->reset;
        $token    = $context->request->data->get('token', $this->_filter);

        if ($hash && ($password->verifyPassword($token, $hash))) {
            $result = true;
        }

        return $result;
    }

    protected function _actionToken(Library\ControllerContextInterface $context)
    {
        $result = false;

        $entity = $context->entity;

        // Set the password as resettable and keep a copy of the token for further use.
        if ($token = $entity->getPassword()->resetPassword())
        {
            $context->token = $token;
            $result         = true;
        }

        return $result;
    }
}
