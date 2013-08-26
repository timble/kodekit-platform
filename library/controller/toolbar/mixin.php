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
 * Toolbar Mixin
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
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
     * Attach a toolbar
     *
     * @param   mixed $toolbar An object that implements ObjectInterface, ObjectIdentifier object
     *                         or valid identifier string
     * @param  array   $config   An optional associative array of configuration settings
     * @param  integer $priority The command priority, usually between 1 (high priority) and 5 (lowest),
     *                 default is 3. If no priority is set, the command priority will be used
     *                 instead.
     * @return  Object The mixer object
     */
    public function attachToolbar($toolbar, $config = array(), $priority = null)
    {
        if (!($toolbar instanceof ControllerToolbarInterface)) {
            $toolbar = $this->createToolbar($toolbar, $config);
        }

        //Store the toolbar to allow for name lookups
        $this->_toolbars[$toolbar->getType()] = $toolbar;

        if ($this->inherits('Nooku\Library\CommandMixin')) {
            $this->getCommandChain()->enqueue($toolbar, $priority);
        }

        return $this->getMixer();
    }

    /**
     * Detach a toolbar
     *
     * @param   ControllerToolbarInterface $toolbar A toolbar instance
     * @return  Object The mixer object
     */
    public function detachToolbar(ControllerToolbarInterface $toolbar)
    {
        if($this->hasToolbar($toolbar->getType()))
        {
            unset($this->_toolbars[$toolbar->getType()]);

            if ($this->inherits('Nooku\Library\CommandMixin')) {
                $this->getCommandChain()->dequeue($toolbar);
            }
        }

        return $this->getMixer();
    }

    /**
     * Check if a toolbar exists
     *
     * @param   string   $type The type of the toolbar
     * @return  boolean  TRUE if the toolbar exists, FALSE otherwise
     */
    public function hasToolbar($type)
    {
        return isset($this->_toolbars[$type]);
    }

    /**
     * Get a toolbar by type
     *
     * @param  string  $type   The toolbar type
     * @return ControllerToolbarInterface
     */
    public function getToolbar($type)
    {
        $result = null;

        if(isset($this->_toolbars[$type])) {
            $result = $this->_toolbars[$type];
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