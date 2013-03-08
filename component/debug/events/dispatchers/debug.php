<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Framework;

/**
 * Debug Event Dispatcher
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Debug
 */
class ComDebugEventDispatcherDebug extends Framework\EventDispatcherException
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
     * @param   object  An optional Framework\Config object with configuration options
     */
    public function __construct(Framework\Config $config)
    {
        parent::__construct($config);

        $this->setDebugMode($config->debug_mode);
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional Framework\Config object with configuration options.
     * @return void
     */
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'debug_level' => self::PRODUCTION,
        ));

        parent::_initialize($config);
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