<?php
/**
 * @version     $Id$
 * @package     Koowa_Mixin
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Toolbar Mixin Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Mixin
 */
class KMixinToolbar extends KMixinAbstract
{
    /**
     * List of toolbars
     *
     * Associative array of toolbars, where key holds the toolbar identifier string
     * and the value is an identifier object.
     *
     * @var    array
     */
    protected $_toolbars = array();

    /**
     * Constructor
     *
     * @param     object     An optional KConfig object with configuration options.
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        //Add the toolbars
        $toolbars = (array)KConfig::unbox($config->toolbars);

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
     * @param     object     An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        parent::_initialize($config);

        $config->append(array(
            'toolbars' => array(),
        ));
    }

    /**
     * Add one or more toolbars
     *
     * @param   mixed    An object that implements KObjectServiceable, KServiceIdentifier object
     *                     or valid identifier string
     * @return  KObject    The mixer object
     */
    public function attachToolbar($toolbar, $config = array(), $priority = KEvent::PRIORITY_NORMAL)
    {
        if (!($toolbar instanceof KControllerToolbarInterface)) {
            $toolbar = $this->getToolbar($toolbar, $config);
        }

        if ($this->inherits('KMixinEvent')) {
            $this->addEventSubscriber($toolbar, $priority);
        }

        return $this->getMixer();
    }

    /**
     * Check if a toolbar exists
     *
     * @param     string    The name of the toolbar
     * @return  boolean    TRUE if the toolbar exists, FALSE otherwise
     */
    public function hasToolbar($toolbar)
    {
        return isset($this->_toolbars[$toolbar]);
    }

    /**
     * Get a toolbar by identifier
     *
     * @return KControllerToolbarAbstract
     */
    public function getToolbar($toolbar, $config = array())
    {
        if (!($toolbar instanceof KServiceIdentifier))
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

        if (!isset($this->_toolbars[$identifier->name]))
        {
            $config['controller'] = $this->getMixer();
            $toolbar = $this->getService($identifier, $config);

            if (!($toolbar instanceof KControllerToolbarInterface)) {
                throw new KControllerToolbarException("Controller toolbar $identifier does not implement KControllerToolbarInterface");
            }

            $this->_toolbars[$toolbar->getIdentifier()->name] = $toolbar;
        }
        else $toolbar = $this->_toolbars[$identifier->name];

        return $toolbar;
    }

    /**
     * Gets the toolbars
     *
     * @return array    An asscociate array of toolbars, keys are the toolbar names
     */
    public function getToolbars()
    {
        return $this->_toolbars;
    }
}