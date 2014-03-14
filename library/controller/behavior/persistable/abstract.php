<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Persistable Controller Behavior
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Nooku\Library\Controller
 */
abstract class ControllerBehaviorPersistableAbstract extends ControllerBehaviorAbstract
{
    /**
     * Associative array containing elements to be excluded from a persisted data set.
     *
     * @var array
     */
    protected $_exclude;

    /**
     * The identifier of the session container for persisting data.
     *
     * @var string
     */
    protected $_container;

    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_exclude   = $config->exclude;
        $this->_container = $config->container;
    }

    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array('exclude' => array()));
        parent::_initialize($config);
    }

    /**
     * Session container identifier getter.
     *
     * @param ControllerContextInterface $context The command context.
     *
     * @return string The session container identifier.
     */
    protected function _getContainer(ControllerContextInterface $context)
    {
        if (!$this->_container)
        {
            $this->_setContainer($context);
        }

        return $this->_container;
    }

    /**
     * Session container identifier setter.
     *
     * @param ControllerContextInterface $context The command context.
     */
    protected function _setContainer(ControllerContextInterface $context)
    {
        $this->_container = $context->subject->getIdentifier() . '.' . $this->getIdentifier()->name;
    }

    /**
     * Persisted data setter.
     *
     * @param   array                    $data    The data to be persisted.
     * @param ControllerContextInterface $context The Command context.
     *
     * @return $this
     */
    protected function _setData($data, ControllerContextInterface $context)
    {
        $session = $context->user->getSession();

        // Start session if not yet active.
        if (!$session->isActive())
        {
            $session->start();
        }

        $exclude = ObjectConfig::unbox($this->_exclude);

        // Remove excluded elements.
        $data    = array_diff_key((array) $data, array_combine($exclude, $exclude));

        $session->set($this->_getContainer($context), $data);

        return $this;
    }

    /**
     * Persisted data getter.
     *
     * @param ControllerContextInterface $context The command context.
     *
     * @return mixed The persisted data.
     */
    protected function _getData(ControllerContextInterface $context)
    {
        return $context->user->getSession()->get($this->_getContainer($context));
    }

    /**
     * Un-sets the persisted data from the session.
     *
     * @param ControllerContextInterface $context The command context.
     *
     * @return $this
     */
    protected function _unsetData(ControllerContextInterface $context)
    {
        unset($context->user->getSession()->{$this->_getContainer($context)});
        return $this;
    }
}