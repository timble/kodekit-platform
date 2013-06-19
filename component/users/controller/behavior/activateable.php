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
 * Activateable Controller Behavior
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Nooku\Component\Users
 */
class ControllerBehaviorActivateable extends Library\ControllerBehaviorAbstract
{
    /**
     * Determines whether new created items need activation or not.
     *
     * @var mixed bool
     */
    protected $_enable;

    /**
     * @var string The filter to be used on activation tokens.
     */
    protected $_filter;

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_enable = $config->enable;
        $this->_filter = $config->filter;
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'enable' => true,
            'filter' => 'alnum'
        ));

        parent::_initialize($config);
    }

    protected function _beforeControllerRender(Library\CommandContext $context)
    {
        $row = $this->getModel()->getRow();

        if (($activation = $context->request->query->get('activation', $this->_filter)))
        {
            if ($row->activation)
            {
                $this->activate(array('activation' => $activation));
            }
            else
            {
                $context->response->setRedirect($this->getObject('lib:dispatcher.router.route',
                    array('url' => $this->getObject('application.pages')->getHome()->getLink())));
                $context->user->addFlashMessage('Invalid request', 'error');
            }
            return false;
        }
    }

    protected function _beforeControllerActivate(Library\CommandContext $context)
    {
        $activation = $context->request->data->get('activation', $this->_filter);
        $row        = $this->getModel()->getRow();

        if ($activation !== $row->activation)
        {
            $url = $this->getObject('application.pages')->getHome()->getLink();
            $this->getObject('application')->getRouter()->build($url);

            $context->user->addFlashMessage('Wrong activation token', 'error');
            $context->response->setRedirect($url);

            return false;
        }
    }

    protected function _actionActivate(Library\CommandContext $context)
    {
        $result = true;

        $row = $this->getModel()->getRow();
        $row->setData(array('activation' => '', 'enabled' => 1));

        if (!$row->save())
        {
            $result = false;
        }

        return $result;
    }

    protected function _afterControllerActivate(Library\CommandContext $context)
    {
        $url = $this->getObject('application.pages')->getHome()->getLink();
        $this->getObject('application')->getRouter()->build($url);


        if ($context->result === true)
        {
            $context->user->addFlashMessage('Activation successfully completed');
        }
        else
        {
            $context->user->addFlashMessage('Activation failed', 'error');
        }

        $context->response->setRedirect($url);
    }

    protected function _beforeControllerAdd(Library\CommandContext $context)
    {
        // Set activation on new records.
        if ($this->_enable)
        {
            $context->request->data->activation = $this->getObject('com:users.database.row.password')->getRandom(32);
            $context->request->data->enabled    = 0;
        }
    }
}