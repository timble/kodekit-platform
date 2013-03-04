<?php
/**
 * @package     Koowa_Service
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Service Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Service
 */
class KService implements KServiceInterface
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
     * Constructor
     *
     * @param KConfig  $config  An optional KConfig object with configuration options
     * @return KObject
     */
    public function __construct(KConfig $config)
    {
        //Set the service container
        if (isset($config->service_manager)) {
            $this->__service_manager = $config->service_manager;
        }

        //Set the service identifier
        if (isset($config->service_identifier)) {
            $this->__service_identifier = $config->service_identifier;
        }
    }

    /**
     * Get an instance of a class based on a class identifier only creating it if it does not exist yet.
     *
     * @param    string|object    $identifier The class identifier or identifier object
     * @param    array            $config     An optional associative array of configuration settings.
     * @throws   \RuntimeException If the service manager has not been defined.
     * @return   object            Return object on success, throws exception on failure
     * @see      KServiceInterface
     */
    final public function getService($identifier = null, array $config = array())
    {
        if (isset($identifier))
        {
            if (!isset($this->__service_manager))
            {
                throw new \RuntimeException(
                    "Failed to call " . get_class($this) . "::getService(). No service_manager object defined."
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
     * @param   string|object    $identifier The class identifier or identifier object
     * @throws  \RuntimeException If the service manager has not been defined.
     * @return  KServiceIdentifier
     * @see     KServiceInterface
     */
    final public function getIdentifier($identifier = null)
    {
        if (isset($identifier))
        {
            if (!isset($this->__service_manager))
            {
                throw new \RuntimeException(
                    "Failed to call " . get_class($this) . "::getIdentifier(). No service_manager object defined."
                );
            }

            $result = $this->__service_manager->getIdentifier($identifier);
        }
        else  $result = $this->__service_identifier;

        return $result;
    }
}