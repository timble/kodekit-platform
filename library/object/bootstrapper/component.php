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
 * Component Object Bootstrapper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Bootstrapper
 */
class ObjectBootstrapperComponent extends ObjectBootstrapperAbstract
{
    /**
     * The object aliases
     *
     * @var array
     */
    protected $_aliases;

    /**
     * The object identifiers
     *
     * @var array
     */
    protected $_identifiers;

    /**
     * The class namespaces
     *
     * @var array
     */
    protected $_namespaces;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_aliases     = $config->aliases;
        $this->_identifiers = $config->identifiers;
        $this->_namespaces  = $config->namespaces;
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
            'aliases'     => array(),
            'identifiers' => array(),
            'namespaces' => array(),
        ));

        parent::_initialize($config);
    }

    /**
     * Bootstrap the object manager
     *
     * @return void
     */
    public function bootstrap()
    {
        $manager = $this->getObjectManager();
        $loader  = $this->getClassLoader();

        //Aliases
        foreach ($this->_aliases as $alias => $identifier) {
            $manager->registerAlias($identifier, $alias);
        }

        //Identifiers
        foreach ($this->_identifiers as $identifier => $config) {
            $manager->setConfig($identifier, $config);
        }

        //Namespaces
        foreach ($this->_namespaces as $type => $namespaces)
        {
           if($locator = $loader->getLocator($type)) {
               $locator->registerNamespaces($namespaces);
           }
        }
    }
}