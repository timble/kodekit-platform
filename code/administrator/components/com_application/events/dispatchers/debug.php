<?php
/**
 * @version		$Id: dispatcher.php 5146 2012-09-03 23:25:47Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Debug Event Dispatcher Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationEventDispatcherDebug extends KEventDispatcherError implements KServiceInstantiatable
{
    /**
     * Debug modes
     */
    const SYSTEM      = 0;
    const DEVELOPMENT = 1;
    const PRODUCTION  = 2;

    /**
     * The debug mode.
     *
     * @var int
     */
    protected $_debug_mode;

    /**
     * Constructor.
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->setDebugMode($config->debug_mode);
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'debug_level' => self::PRODUCTION,
        ));

        parent::_initialize($config);
    }

    /**
     * Force creation of a singleton
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @param 	object	A KServiceInterface object
     * @return KDatabaseTableDefault
     */
    public static function getInstance(KConfigInterface $config, KServiceInterface $container)
    {
        // Check if an instance with this identifier already exists or not
        if (!$container->has($config->service_identifier))
        {
            //Create the singleton
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $container->set($config->service_identifier, $instance);
        }

        return $container->get($config->service_identifier);
    }

    /**
     * Set the debug mode
     *
     * @param int $mode
     */
    public function setDebugMode($mode)
    {
        $this->_debug_mode = $mode;

        switch($mode)
        {
            case self::PRODUCTION :
            {
                $this->setErrorLevel(E_ERROR | E_WARNING | E_PARSE);
                ini_set( 'display_errors', 0 );

            } break;

            case self::DEVELOPMENT :
            {
                $this->setErrorLevel(E_ALL | E_STRICT | ~E_DEPRECATED);
                ini_set( 'display_errors', 1 );

            } break;
        }
    }

    /**
     * Get the debug mode
     *
     * @return int The debug mode
     */
    public function getDebugMode()
    {
        return $this->_debug_mode;
    }
}