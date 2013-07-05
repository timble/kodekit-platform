<?php
/**
 * @package     Koowa_Object
 * @subpackage  Mixin
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Toolbar Mixin Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 * @subpackage  Mixin
 */
class ControllerToolbarMixin extends ObjectMixinAbstract
{
    /**
     * List of toolbars
     *
     * The key holds the behavior name and the value the behavior object
     *
     * @var    array
     */
    protected $_toolbars = array();

    /**
     * Constructor
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Add the toolbars
        $toolbars = (array)ObjectConfig::unbox($config->toolbars);

        foreach ($toolbars as $key => $value)
        {
            if (is_numeric($key)) {
                $this->attachToolbar($value);
            } else {
                $this->attachToolbar($key, $value);
            }
        }
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        parent::_initialize($config);

        $config->append(array(
            'toolbars' => array(),
        ));
    }

    /**
     * Add one or more toolbars
     *
     * @param   mixed $toolbar An object that implements ObjectInterface, ObjectIdentifier object
     *                         or valid identifier string
     * @param  array  $config   An optional associative array of configuration settings
     * @param  integer $priority The event priority, usually between 1 (high priority) and 5 (lowest),
     *                 default is 3. If no priority is set, the command priority will be used
     *                 instead.
     * @return  Object The mixer object
     */
    public function attachToolbar($toolbar, $config = array(), $priority = Event::PRIORITY_NORMAL)
    {
        if (!($toolbar instanceof ControllerToolbarInterface)) {
            $toolbar = $this->createToolbar($toolbar, $config);
        }

        //Store the toolbar to allow for name lookups
        $this->_toolbars[$toolbar->getIdentifier()->name] = $toolbar;

        if ($this->inherits('Nooku\Library\EventMixin')) {
            $this->addEventSubscriber($toolbar, $priority);
        }

        return $this->getMixer();
    }

    /**
     * Check if a toolbar exists
     *
     * @param   string   $toolbar The name of the toolbar
     * @return  boolean  TRUE if the toolbar exists, FALSE otherwise
     */
    public function hasToolbar($name)
    {
        return isset($this->_toolbars[$name]);
    }

    /**
     * Get a toolbar by name
     *
     * @param  string  $name   The toolbar name
     * @return ControllerToolbarInterface
     */
    public function getToolbar($name)
    {
        $result = null;

        if(isset($this->_toolbars[$name])) {
            $result = $this->_toolbars[$name];
        }

        return $result;
    }

    /**
     * Gets the toolbars
     *
     * @return array  An associative array of toolbars, keys are the toolbar names
     */
    public function getToolbars()
    {
        return $this->_toolbars;
    }

    /**
     * Get a toolbar by identifier
     *
     * @return ControllerToolbarInterface
     */
    public function createToolbar($toolbar, $config = array())
    {
        if (!($toolbar instanceof ObjectIdentifier))
        {
            //Create the complete identifier if a partial identifier was passed
            if (is_string($toolbar) && strpos($toolbar, '.') === false)
            {
                $identifier = clone $this->getIdentifier();
                $identifier->path = array('controller', 'toolbar');
                $identifier->name = $toolbar;
            }
            else $identifier = $this->getIdentifier($toolbar);
        }
        else $identifier = $toolbar;

        $config['controller'] = $this->getMixer();
        $toolbar = $this->getObject($identifier, $config);

        if (!($toolbar instanceof ControllerToolbarInterface)) {
            throw new \UnexpectedValueException("Controller toolbar $identifier does not implement ControllerToolbarInterface");
        }

        return $toolbar;
    }
}