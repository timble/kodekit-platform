<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Activatable Controller Behavior
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Nooku\Component\Users
 */
class ControllerBehaviorActivatable extends Library\ControllerBehaviorAbstract
{
    /**
     * Determines whether new created items will be forced for activation.
     *
     * @var mixed bool
     */
    protected $_force;

    /**
     * @var string The filter to be used on activation tokens.
     */
    protected $_filter;

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_force  = $config->force;
        $this->_filter = $config->filter;
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'force'  => true,
            'filter' => 'alnum'
        ));

        parent::_initialize($config);
    }

    protected function _beforeActivate(Library\ControllerContextInterface $context)
    {
        $result = true;
        $row    = $this->getModel()->fetch();

        $activation = $context->request->data->get('activation', $this->_filter);
        $row        = $this->getModel()->fetch();

        if ($activation !== $row->activation) {
            $result = false;
        }

        return $result;
    }

    protected function _actionActivate(Library\ControllerContextInterface $context)
    {
        $result = true;

        $row = $this->getModel()->fetch();
        $row->setProperties(array('activation' => '', 'enabled' => 1));

        if (!$row->save()) {
            $context->error = $row->getStatusMessage();
            $result         = false;
        }

        return $result;
    }

    protected function _beforeAdd(Library\ControllerContextInterface $context)
    {
        // Force activation on new records.
        if ($this->_force) {
            $context->request->data->enabled = 0;
        }

        if (!$context->request->data->enabled) {
            $context->request->data->activation = $this->getObject('com:users.model.entity.password')->createPassword(32);
        }
    }

    protected function _afterEdit(Library\ControllerContextInterface $context)
    {
        $row = $context->result;

        // Reset activation token if necessary.
        if ($row->enabled && $row->activation)
        {
            $row->activation = '';
            $row->save();
        }
    }
}