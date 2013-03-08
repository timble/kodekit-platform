<?php
/**
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Activateable Controller Behavior Class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersControllerBehaviorActivateable extends Framework\ControllerBehaviorAbstract
{
    /**
     * Determines whether new created items need activation or not.
     *
     * @var mixed bool
     */
    protected $_enable;

    public function __construct(Framework\Config $config)
    {
        parent::__construct($config);

        $this->_enable = $config->enable;
    }

    protected function _initialize(Framework\Config $config)
    {
        $parameters = $this->getService('application.components')->users->params;

        $config->append(array(
            'enable' => $parameters->get('useractivation', '1')
        ));

        parent::_initialize($config);
    }

    /**
     * Handles item activation via GET requests.
     *
     * @param Framework\CommandContext The command context.
     */
    protected function _afterControllerRead(Framework\CommandContext $context)
    {
        $item = $context->result;

        if ($activation = $context->request->query->get('activation', 'cmd') && $item->activation) {
            $this->activate(array('activation' => $activation));
        }
    }

    protected function _beforeControllerActivate(Framework\CommandContext $context)
    {
        $activation = $context->request->data->get('activation', 'string');
        $item       = $this->getModel()->getRow();

        if ($activation !== $item->activation)
        {
            $context->response->setRedirect(Framework\Request::root(), 'Wrong activation token');
            return false;
        }
    }

    protected function _actionActivate(Framework\CommandContext $context)
    {
        $item             = $this->getModel()->getRow();
        $item->activation = '';
        $item->enabled    = 1;

        if ($item->save())
        {
            $url = $this->getService('application.pages')->home->getLink();
            $context->response->setRedirect($url, 'Activation successfully completed');
            $result = true;
        }
        else throw new Framework\ControllerExceptionActionFailed('Unable to activate user');

        return $result;
    }

    protected function _beforeControllerAdd(Framework\CommandContext $context)
    {
        // Set activation on new records.
        if ($this->_enable)
        {
            $password = $this->getService('com://admin/users.database.row.password');
            $context->request->data->activation = $password->getRandom(32);
            $context->request->data->enabled    = 0;
        }
    }
}