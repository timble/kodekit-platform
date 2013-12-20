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
     * The object mixins
     *
     * @var array
     */
    protected $_mixins;

    /**
     * The object decorators
     *
     * @var array
     */
    protected $_decorators;

    /**
     * The object configs
     *
     * @var array
     */
    protected $_configs;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_aliases    = $config->aliases;
        $this->_mixins     = $config->mixins;
        $this->_decorators = $config->decorators;
        $this->_configs    = $config->configs;
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
            'aliases'    => array(),
            'configs'    => array(),
            'mixins'     => array(),
            'decorators' => array(),
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

        //Aliases
        foreach ($this->_aliases as $alias => $identifier) {
            $manager->registerAlias($alias, $identifier);
        }

        //Configs
        foreach ($this->_configs as $identifier => $config) {
            $manager->setConfig($identifier, $config);
        }

        //Mixins
        foreach ($this->_mixins as $identifier => $mixins)
        {
            foreach($mixins as $key => $value)
            {
                if (is_numeric($key)) {
                    $manager->registerMixin($identifier, $value);
                } else {
                    $manager->registerMixin($identifier, $key, $value);
                }
            }
        }

        //Decorators
        foreach ($this->_decorators as $identifier => $decorators)
        {
            foreach($decorators as $key => $value)
            {
                if (is_numeric($key)) {
                    $manager->registerDecorator($identifier, $value);
                } else {
                    $manager->registerDecorator($identifier, $key, $value);
                }
            }
        }
    }
}