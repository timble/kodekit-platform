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
     * Constructor.
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->__object_manager = $config->object_manager;
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
     * Check if the bootstrapper has been run
     *
     * @return bool TRUE if the bootstrapping has run FALSE otherwise
     */
    public function isBootstrapped()
    {
        return false;
    }
}