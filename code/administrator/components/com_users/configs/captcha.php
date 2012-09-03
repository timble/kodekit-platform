<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Captcha configuration class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersConfigCaptcha extends KConfig implements KServiceInstantiatable, KObjectServiceable
{
    /**
     * The service identifier
     *
     * @var KServiceIdentifier
     */
    private $__service_identifier;

    /**
     * The service manager
     *
     * @var KServiceManager
     */
    private $__service_manager;

    /**
     * Constructor.
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        //Set the service container
        if (isset($config->service_manager)) {
            $this->__service_container = $config->service_manager;
        }

        //Set the service identifier
        if (isset($config->service_identifier)) {
            $this->__service_identifier = $config->service_identifier;
        }

        parent::__construct($config);
    }

    /**
     * Force creation of a singleton
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @param 	object	A KServiceInterface object
     * @return ComUsersConfigCaptcha
     */
    static public function getInstance(KConfigInterface $config, KServiceInterface $container)
    {
        $instance = new self($config);

        // Force singleton behavior.
        $container->set($config->service_manager, $instance);

        return $instance;
    }

    /**
     * Get an instance of a class based on a class identifier only creating it if it does not exist yet.
     *
     * @param	string|object	$identifier The class identifier or identifier object
     * @param	array  			$config     An optional associative array of configuration settings.
     * @throws	\RuntimeException If the service manager has not been defined.
     * @return	object  		Return object on success, throws exception on failure
     * @see 	KObjectServiceable
     */
    final public function getService($identifier = null, array $config = array())
    {
        if(isset($identifier))
        {
            if(!isset($this->__service_manager))
            {
                throw new RuntimeException(
                    "Failed to call ".get_class($this)."::getService(). No service_manager object defined."
                );
            }

            $result = $this->__service_manager->get($identifier, $config);
        }
        else $result = $this->__service_manager;

        return $result;
    }

    /**
     * Gets the service identifier.
     *
     * @param	string|object	$identifier The class identifier or identifier object
     * @throws	\RuntimeException If the service manager has not been defined.
     * @return	KServiceIdentifier
     * @see 	KObjectServiceable
     */
    final public function getIdentifier($identifier = null)
    {
        if(isset($identifier))
        {
            if(!isset($this->__service_manager))
            {
                throw new RuntimeException(
                    "Failed to call ".get_class($this)."::getIdentifier(). No service_manager object defined."
                );
            }

            $result = $this->__service_manager->getIdentifier($identifier);
        }
        else  $result = $this->__service_identifier;

        return $result;
    }
}