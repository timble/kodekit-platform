<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Object Bootstrapper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Bootstrapper
 */
abstract class ObjectBootstrapperAbstract extends Object implements ObjectBootstrapperInterface
{
    /**
     * The object manager
     *
     * @var ObjectManagerInterface
     */
    private $__object_manager;

    /**
     * The bootstrapper priority
     *
     * @var integer
     */
    protected $_priority;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_priority        = $config->priority;
        $this->__object_manager = $config->object_manager;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'priority'    => self::PRIORITY_NORMAL,
        ));

        parent::_initialize($config);
    }

    /**
     * Get the object manager
     *
     * @return ObjectManagerInterface
     */
    public function getObjectManager()
    {
        return $this->__object_manager;
    }

    /**
     * Get the class loader
     *
     * @return ClassLoaderInterface
     */
    public function getClassLoader()
    {
        return $this->getObjectManager()->getClassLoader();
    }

    /**
     * Get the priority of the bootstrapper
     *
     * @return  integer The priority level
     */
    public function getPriority()
    {
        return $this->_priority;
    }
}